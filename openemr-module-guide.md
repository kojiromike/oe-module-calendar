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

// Note: The event dispatcher is automatically in scope in this context
// No need to require it

// Subscribe to events
$eventDispatcher->addListener(
    'globals.load',
    function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
        // Your code here that runs when globals are loaded
    }
);
```

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

### Example: Adding a Global Setting

To add a configuration option to the globals page, listen for the `globals.load` event:

```php
$eventDispatcher->addListener('globals.load', function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
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

To add an entry to the OpenEMR menu, listen for the `menu_update` event:

```php
$eventDispatcher->addListener('menu_update', function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
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

To add JavaScript or CSS to OpenEMR pages, listen for the `core.body.render` event:

```php
$eventDispatcher->addListener('core.body.render', function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
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
$eventDispatcher->addListener('globals.load', function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
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

1. **Place your module in the custom_modules directory**
   Put your module files in `interface/modules/custom_modules/your_module_name/`

2. **Register your module**
   Go to Modules > Manage Modules in OpenEMR and find your module under the "Unregistered" tab. Click "Register" to make OpenEMR aware of your module.

3. **Install your module**
   After registration, find your module in the "Registered" tab and click "Install" to set up any database tables or data.

4. **Enable your module**
   After installation, click "Enable" to activate your module so it runs on page loads.

## Setting Up Autoloading for Development

For development purposes, if you need to set up the autoloader for your module:

1. Edit the main OpenEMR `composer.json` file
2. Add your module's namespace to the autoload section:

```json
"autoload": {
    "psr-4": {
        "OpenEMR\\": "src",
        "OpenEMR\\Modules\\CustomModuleName\\": "interface/modules/custom_modules/your_module_name/src"
    }
}
```

3. Run `composer dump-autoload` to regenerate the autoloader

## Conclusion

Creating custom modules for OpenEMR allows you to extend functionality without modifying core code. By following this guide, you can create, install, and distribute your own modules for OpenEMR that work with the event system to enhance the platform while maintaining compatibility with future OpenEMR updates.

For more complex modules, you might want to explore the Laminas module framework, which offers additional features for larger-scale development projects.

## Resources

- OpenEMR Skeleton Module: https://github.com/openemr/oe-module-custom-skeleton
- OpenEMR Module Installer: https://packagist.org/packages/openemr/module-installer-plugin
- OpenEMR Official Website: https://www.open-emr.org
