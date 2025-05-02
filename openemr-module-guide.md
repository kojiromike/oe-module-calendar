# Creating a Custom OpenEMR Module From Scratch

## Introduction

OpenEMR is an open-source electronic medical record system that supports customization through modules. Modules in OpenEMR allow developers to add functionality without modifying the core codebase, making updates easier and preventing conflicts between custom code and the main application.

## What is an OpenEMR Module?

A module in OpenEMR is "a cohesive package of functionality that can operate independently of OpenEMR" that you can turn on or off. It's a way to enhance the OpenEMR core by connecting at specific points in the code.

The purpose of a module is to package related functionality that can be enabled or disabled as needed. This allows OpenEMR to support different workflows and specialties without making the core system overly complex. Modules follow the SOLID principle of being "open for extension but closed for modification," meaning core OpenEMR can change as long as it maintains the module interface.

## Types of OpenEMR Modules

There are two main types of modules in OpenEMR:

1. **Laminas (formerly Zend) modules** - These are more enterprise-level modules with a complex framework that includes presentation layers, dependency injection, and other higher-level concepts.

2. **Custom modules** - Simpler modules that don't require the full Laminas framework. These are ideal for smaller projects that don't need the complexity of the Laminas framework.

This guide will focus on creating custom modules.

## Module Lifecycle and Installation

### When and Where Modules Run

Modules begin their lifecycle in the `global.php` file, which is included at the top of almost every page in OpenEMR. After loading globals, it instantiates the `ModulesApplication` class within the OpenEMR kernel (the main dependency injection container).

Custom modules are loaded after the Laminas modules in the order they were installed. Any page that includes `global.php` will execute all active modules, so your module code will run on every page that includes this file.

### Module Installation Process

The installation of a module involves three steps:

1. **Registration** - This makes OpenEMR aware of the module's existence
2. **Installation** - This installs any database tables or data needed by the module
3. **Enablement** - This activates the module so it runs on page loads

## Creating a Custom Module

### Directory Structure

Create your module in the `interface/modules/custom_modules/` directory with the following structure:

```
module_name/
├── info.txt                     # Human-readable name of your module
├── openemr.bootstrap.php        # Entry point for your module
├── table.sql or install.sql     # SQL for database installation
├── composer.json                # Module configuration
└── src/                         # Your module code
```

### Step 1: Creating the info.txt File

The `info.txt` file contains the human-readable name of your module, which will be displayed on the module registration screen. If this file doesn't exist, the directory name will be used instead.

Example:
```
My Custom Module
```

### Step 2: Creating the openemr.bootstrap.php File

The bootstrap file is the entry point for your module. OpenEMR looks specifically for a file named `openemr.bootstrap.php` in your module directory.

This file is loaded in an isolated context, so it won't pollute the variable scope of other modules.

Example bootstrap file:

```php
<?php
/**
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  [Your Name]
 * @copyright Copyright (c) [Year]
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace Your\Namespace;


// Note: The event dispatcher is automatically in scope in this context
// No need to require it

/**
 * @global OpenEMR\Core\ModulesClassLoader $classLoader
 *
 * Registers a namespace if it doesn't already exist in the autoloader.
 *
 * This function is intended for scenarios where the module is installed manually
 * without using Composer. It provides a fallback autoloading mechanism.
 *
 * Note: This does nothing when the module is properly configured with Composer,
 * which is the recommended way to install and use modules.
 */
$classLoader->registerNamespaceIfNotExists(__NAMESPACE__ . '\\', __DIR__ . DIRECTORY_SEPARATOR . 'src');

// Subscribe to events
$eventDispatcher->addListener(
    'globals.load',
    function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
        // Your code here that runs when globals are loaded
    }
);
```

 Note: You may see `$classLoader->registerNamespaceIfNotExists()` in some examples. This is not necessary when the module is registered with the composer autoloader, as the namespace will be automatically registered through the composer's PSR-4 autoloading mechanism.


### Step 3: Creating the SQL Installation File

For database installation, create either a `table.sql` or `install.sql` file in your module's root directory or in a `sql` subfolder. OpenEMR will look for these files during the installation process.

These SQL files use the same OpenEMR-specific database syntax that's used in the system's SQL upgrade scripts, including domain-specific language like `IF NOT EXISTS` checks.

Example:
```sql
--
-- Table structure for module data
--

CREATE TABLE IF NOT EXISTS `mod_my_custom_module` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field1` varchar(255) DEFAULT NULL,
  `field2` text,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
```

### Step 4: Setting Up composer.json

To properly distribute your module and set up the autoloader, create a `composer.json` file with the following key elements:

1. Set the type to "openemr-module"
2. Include the module-installer plugin
3. Configure the PSR-4 autoloader for your namespace

Example:
```json
{
    "name": "yourname/your-module-name",
    "description": "A custom module for OpenEMR",
    "type": "openemr-module",
    "license": "GPL-3.0",
    "authors": [
        {
            "name": "Your Name",
            "email": "your.email@example.com"
        }
    ],
    "require": {
        "openemr/module-installer-plugin": "^0.1.0"
    },
    "autoload": {
        "psr-4": {
            "OpenEMR\\Modules\\CustomModuleNamespace\\": "src/"
        }
    }
}
```

## Connecting to OpenEMR: Using Events

The primary way modules interact with OpenEMR is through the event system. When something happens in OpenEMR, it fires an event that your module can listen for and respond to.

### Working with the Event System

OpenEMR uses a robust event system based on Symfony's EventDispatcher component. Instead of using string literals for event names, you should always use the constants defined in the event classes for better type safety and maintainability.

### Example: Adding a Global Setting

To add a configuration option to the globals page, listen for the `GlobalsInitializedEvent::EVENT_HANDLE` event:

```php
// Always import the event classes you're using
use OpenEMR\Events\Globals\GlobalsInitializedEvent;

$eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
    // Get the globals service from the event
    $globalsService = $event->getSubject();

    // Create a section for your module's settings
    $sectionName = 'My Module';
    $globalsService->createSection($sectionName, 'Portal');

    // Add a text setting
    $globalsService->appendToSection(
        $sectionName,
        'MY_MODULE_TEXT_SETTING',
        'My Setting',
        'text',
        '',
        'This is a description of my setting'
    );

    // Add a boolean/checkbox setting
    $globalsService->appendToSection(
        $sectionName,
        'MY_MODULE_ENABLED',
        'Enable Feature',
        'bool',
        '0',
        'Enable this feature in the module'
    );
});
```

### Example: Adding a Menu Item

To add an entry to the OpenEMR menu, listen for the `MenuEvent::MENU_UPDATE` event:

```php
use OpenEMR\Menu\MenuEvent;

$eventDispatcher->addListener(MenuEvent::MENU_UPDATE, function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
    $menu = $event->getSubject();

    // Add menu item under the "Modules" main menu
    $menuItem = new \stdClass();
    $menuItem->requirement = 0;
    $menuItem->target = '';
    $menuItem->menu_id = 'mod0';
    $menuItem->label = 'My Module';
    $menuItem->url = '/interface/modules/custom_modules/my_module/public/index.php';
    $menuItem->children = [];

    // Find the "Modules" menu
    foreach ($menu as $item) {
        if ($item->menu_id == 'modimg') {
            $item->children[] = $menuItem;
            break;
        }
    }
});
```

### Example: Adding JavaScript and CSS to Pages

To add JavaScript or CSS to OpenEMR pages, listen for the `RenderEvent::EVENT_BODY_RENDER_POST` event:

```php
use OpenEMR\Events\Main\Tabs\RenderEvent;

$eventDispatcher->addListener(RenderEvent::EVENT_BODY_RENDER_POST, function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
    // Get module directory path
    $modulePath = '/interface/modules/custom_modules/my_module';

    // Add CSS file
    echo "<link rel='stylesheet' href='" . $modulePath . "/public/assets/css/style.css' />\n";

    // Add JavaScript file
    echo "<script src='" . $modulePath . "/public/assets/js/script.js'></script>\n";
});
```

### Example: Replacing a Template

To override a template in OpenEMR, you can replace Twig templates:

```php
use OpenEMR\Events\Globals\GlobalsInitializedEvent;

$eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
    // Get the globals service
    $globalsService = $event->getSubject();

    // Create module section and enable template override
    $sectionName = 'My Module';
    $globalsService->createSection($sectionName, 'Portal');
    $globalsService->appendToSection(
        $sectionName,
        'MY_MODULE_OVERRIDE_TEMPLATES',
        'Override Templates',
        'bool',
        '1',
        'Enable template overrides'
    );
});

// In your module's code, you would place override templates in a structure matching the core templates
// For example, to override the login page:
// module_name/templates/login/login.html.twig
```

### Common OpenEMR Events

Here are some common events you may want to listen for in your modules:

| Event Constant | Description |
|----------------|-------------|
| `GlobalsInitializedEvent::EVENT_HANDLE` | Fired when global settings are initialized, use to add your module settings |
| `MenuEvent::MENU_UPDATE` | Fired when building the main menu, use to add your module's menu entries |
| `RenderEvent::EVENT_BODY_RENDER_PRE` | Fired before the main body content is rendered |
| `RenderEvent::EVENT_BODY_RENDER_POST` | Fired after the main body content is rendered, ideal for adding JS/CSS |
| `PatientMenuEvent::MENU_UPDATE` | Fired when building the patient menu |
| `AppointmentRenderEvent::RENDER_JAVASCRIPT` | Fired when rendering appointment JavaScript |
| `PatientCreatedEvent::EVENT_HANDLE` | Fired when a new patient is created |
| `PatientUpdatedEvent::EVENT_HANDLE` | Fired when a patient record is updated |
| `UserCreatedEvent::EVENT_HANDLE` | Fired when a new user is created |
| `RestApiCreateEvent::EVENT_HANDLE` | Fired when creating REST API routes |

For a complete list of available events, you can refer to the event classes in the OpenEMR codebase or use the events.md document.

## Best Practices for Module Development

1. **Performance Considerations**
   Be careful with network requests in modules as they run on every page load. Cache data when possible, use the Redis server if available, and minimize database requests.

2. **Namespace Your Code**
   Keep your module code in a proper namespace to avoid conflicts with other modules or core code.

3. **Use Event Listeners**
   Use the event system to hook into OpenEMR rather than modifying core files. This makes your module more maintainable across OpenEMR updates.

4. **Consider Module Distribution**
   When ready to share your module, publish it to Packagist.org so others can install it via Composer.

## Installing and Activating Your Module

### Making Your Module Installable via Composer

For development and early-stage modules, you can use Composer's local path repository feature:

1. **Prepare Your Module**
   - Make sure your `composer.json` file is properly configured as described in Step 4 above
   - Ensure the "type" is set to "openemr-module"
   - Include the dependency on "openemr/module-installer-plugin"

2. **Add as a Local Repository**
   - In your OpenEMR installation, add your module as a local repository:
   ```bash
   composer config repositories.your-module-name path /path/to/your/module
   ```
   - This tells Composer to look for this package in the specified local directory

3. **Install the Module**
   - Install the module:
   ```bash
   composer require yourname/your-module-name:@dev
   ```
   - The `@dev` suffix tells Composer to use the development version
   - The module-installer-plugin will automatically place your module in the correct directory

Note: Creating a Git repository and publishing to Packagist for wider distribution are options for more mature modules, but those topics are not covered in this document.

4. **Register, Install, and Enable**
   - Go to Modules > Manage Modules in OpenEMR
   - Find your module under the "Unregistered" tab and click "Register"
   - After registration, find your module in the "Registered" tab and click "Install"
   - Finally, click "Enable" to activate your module so it runs on page loads

## What Goes in the `src` Directory and How Code Gets Called

After your module is installed and enabled in OpenEMR, the next question is how your code in the `src` directory actually gets executed. Let's examine this process using the custom module skeleton as an example.

### The `src` Directory Structure

The `src` directory contains the main PHP classes for your module. Looking at the skeleton module structure:

```
src/
├── Bootstrap.php       # Primary entry point for your module
├── ModuleConfig.php    # Configuration for your module
└── ...                 # Other support classes
```

### The Bootstrap Process

When the OpenEMR system loads your module, it follows this sequence:

1. First, the `openemr.bootstrap.php` file in your module's root directory is called on every page load that includes `global.php` (which is most pages in OpenEMR)

2. The `openemr.bootstrap.php` file typically initializes your module by including the `src/Bootstrap.php` class

3. The `src/Bootstrap.php` class is where you configure:
   - Event listeners that connect to OpenEMR's event system
   - Menu items that should appear in OpenEMR's interface
   - Global settings your module needs
   - API endpoints your module provides
   - Other integrations with the OpenEMR system

### Example Bootstrap Class

The Bootstrap class is the heart of your module. It defines how your module integrates with OpenEMR through event listeners. Here's a simplified example based on the skeleton module:

```php
namespace OpenEMR\Modules\CustomModuleName;

use OpenEMR\Common\Logging\SystemLogger;
use OpenEMR\Events\Globals\GlobalsInitializedEvent;
use OpenEMR\Events\Main\Menu\MainMenuRole;
use OpenEMR\Events\RestApiExtend\RestApiResourceServiceEvent;
use OpenEMR\Menu\MenuEvent;
use OpenEMR\Services\Globals\GlobalSetting;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Bootstrap
{
    /**
     * @var EventDispatcherInterface The event dispatcher object
     */
    private $eventDispatcher;

    /**
     * @var SystemLogger
     */
    private $logger;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = new SystemLogger();
    }

    public function subscribeToEvents()
    {
        // Add global settings
        $this->eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, [$this, 'addGlobalSettings']);

        // Add menu items
        $this->eventDispatcher->addListener(MenuEvent::MENU_UPDATE, [$this, 'addMenuItems']);

        // Add API endpoints
        $this->eventDispatcher->addListener(RestApiResourceServiceEvent::EVENT_HANDLE, [$this, 'addApiEndpoints']);
    }

    // Method that adds global settings
    public function addGlobalSettings(GlobalsInitializedEvent $event)
    {
        $service = $event->getGlobalsService();
        $section = "Module Name";
        $service->createSection($section, "Module Description");

        // Add settings to your section
        $setting = new GlobalSetting(
            'module_enable_feature',
            'bool',                // Field type
            '0',                   // Default value
            'Enable Feature X',    // Title
            'Enables feature X in the module' // Description
        );
        $service->appendToSection($section, $setting);
    }

    // Method that adds menu items
    public function addMenuItems(MenuEvent $event)
    {
        $menu = $event->getMenu();

        // Add your module menu under the Modules menu
        $menuItem = new \stdClass();
        $menuItem->requirement = 0;
        $menuItem->target = '';
        $menuItem->menu_id = 'mod0';
        $menuItem->label = 'My Module';
        $menuItem->url = '/interface/modules/custom_modules/my-module/public/index.php';
        $menuItem->children = [];

        // Find the "Modules" menu
        foreach ($menu as $item) {
            if ($item->menu_id == 'modimg') {
                $item->children[] = $menuItem;
                break;
            }
        }
    }

    // Method that adds API endpoints
    public function addApiEndpoints(RestApiResourceServiceEvent $event)
    {
        $event->addResource('custom-module-name', '\OpenEMR\Modules\CustomModuleName\RestControllers\RestApiController');
    }
}
```

### How Your Module Gets Initialized

1. The OpenEMR module system loads each active module on every page load where `global.php` is included

2. For custom modules, it runs the `openemr.bootstrap.php` file which typically looks like:

```php
<?php
// This bootstrapper is loaded for every page load when the module is enabled

// The event dispatcher is already in scope at this point
use OpenEMR\Modules\CustomModuleName\Bootstrap;

// Include autoloader if needed (but usually Composer's autoloader is already loaded)
// Instantiate the main Bootstrap class of your module
$bootstrap = new Bootstrap($eventDispatcher);

// Subscribe to OpenEMR events
$bootstrap->subscribeToEvents();
```

### Key Classes and Methods You Can Implement

In your `src` directory, you typically have these key components:

1. **Bootstrap.php** - The main entry point for your module that subscribes to events
2. **Controllers** - Classes that handle specific UI or API endpoints
3. **Models** - Classes that represent your data structures
4. **Services** - Business logic classes for your module
5. **Views** - Templates and UI components

### Working with Events

The event system is how your module integrates with OpenEMR. Common events you might subscribe to:

1. **GlobalsInitializedEvent** - To add global settings for your module
2. **MenuEvent** - To add menu items to the OpenEMR interface
3. **RestApiResourceServiceEvent** - To add API endpoints
4. **PageHeadEvent** - To add JavaScript or CSS to pages
5. **PatientFilterEvent** - To filter patient data
6. Various other clinical, billing, and system events

### Additional Files in the Module

Besides the `src` directory, your module might include:

1. **public/** - Public-facing files that can be accessed directly by the browser
2. **templates/** - Template files for your views
3. **assets/** - JavaScript, CSS and other assets
4. **sql/** - SQL files for database operations
5. **tests/** - Unit and integration tests

Your module's code in the `src` directory is only executed when:
1. The module is enabled
2. An event your module listens for is triggered
3. A page, API endpoint, or other entry point specific to your module is accessed
