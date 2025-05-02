<?php
/**
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  Michael A. Smith
 * @copyright Copyright (c) 2025
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

// The event dispatcher is automatically in scope in this context
$eventDispatcher->addListener(
    'globals.load',
    function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
        // Create a section for your module's settings
        $globalsService = $event->getSubject();
        $sectionName = 'Calendar Integration';
        $globalsService->createSection($sectionName, 'Portal');

        // Add configuration settings
        $globalsService->appendToSection(
            $sectionName,
            'CALENDAR_API_ENDPOINT',
            'Calendar API Endpoint',
            'text',
            '',
            'The endpoint URL for the calendar service'
        );

        $globalsService->appendToSection(
            $sectionName,
            'CALENDAR_SYNC_ENABLED',
            'Enable Calendar Sync',
            'bool',
            '0',
            'Enable synchronization with external calendar service'
        );
    }
);

// Add menu item for calendar management
$eventDispatcher->addListener('menu_update', function (\Symfony\Component\EventDispatcher\GenericEvent $event) {
    $menu = $event->getSubject();

    // Add menu item under the "Modules" main menu
    $menuItem = new \stdClass();
    $menuItem->requirement = 0;
    $menuItem->target = '';
    $menuItem->menu_id = 'calendar_module';
    $menuItem->label = 'Calendar Integration';
    $menuItem->url = '/interface/modules/custom_modules/oe-module-calendar/src/public/index.php';
    $menuItem->children = [];

    // Find the "Modules" menu
    foreach ($menu as $item) {
        if ($item->menu_id == 'modimg') {
            $item->children[] = $menuItem;
            break;
        }
    }
});
