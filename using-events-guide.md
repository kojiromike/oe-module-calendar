# OpenEMR Events Guide: Using Events in Module Development

## Introduction

This guide will help you understand how to effectively use OpenEMR's event system in your module development. Instead of using string literals for event names, which are prone to errors and difficult to maintain, you should always use the event class constants provided by OpenEMR.

## Why Use Event Constants?

Using event constants instead of string literals offers several advantages:

1. **Type Safety**: IDE auto-complete works with constants
2. **Error Prevention**: Typos in string literals are easy to make and hard to debug
3. **Maintainability**: If an event name changes, only the constant needs to be updated
4. **Discoverability**: You can easily find all usages of an event

## How to Use Event Constants

### Step 1: Import the Event Class

First, import the event class that contains the constant you want to use:

```php
use OpenEMR\Events\Globals\GlobalsInitializedEvent;
use OpenEMR\Menu\MenuEvent;
// Import other event classes as needed
```

### Step 2: Subscribe to the Event Using the Constant

When adding an event listener, use the class constant instead of a string:

```php
// CORRECT: Using the event constant
$eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, function($event) {
    // Your event handler code
});

// INCORRECT: Using a string literal
$eventDispatcher->addListener('globals.initialized', function($event) {
    // Your event handler code
});
```

## Event Categories in OpenEMR

OpenEMR has events for various parts of the system. Here are the main categories:

### 1. User Interface Events

These events allow you to add UI elements or modify the interface:

| Event Class | Constant | Description |
|------------|----------|-------------|
| `RenderEvent` | `EVENT_BODY_RENDER_PRE` | Before rendering main body content |
| `RenderEvent` | `EVENT_BODY_RENDER_POST` | After rendering main body content |
| `MenuEvent` | `MENU_UPDATE` | When building the main menu |
| `PatientMenuEvent` | `MENU_UPDATE` | When building the patient menu |
| `EncounterButtonEvent` | `BUTTON_RENDER` | When rendering encounter buttons |
| `EncounterMenuEvent` | `MENU_RENDER` | When rendering encounter menu |

### 2. Patient-Related Events

These events are triggered during patient operations:

| Event Class | Constant | Description |
|------------|----------|-------------|
| `PatientCreatedEvent` | `EVENT_HANDLE` | When a patient is created |
| `PatientUpdatedEvent` | `EVENT_HANDLE` | When a patient is updated |
| `BeforePatientCreatedEvent` | `EVENT_HANDLE` | Before a patient is created |
| `BeforePatientUpdatedEvent` | `EVENT_HANDLE` | Before a patient is updated |

### 3. Appointment-Related Events

These are particularly relevant for your calendar module:

| Event Class | Constant | Description |
|------------|----------|-------------|
| `AppointmentRenderEvent` | `RENDER_JAVASCRIPT` | When rendering appointment JavaScript |
| `AppointmentRenderEvent` | `RENDER_BELOW_PATIENT` | When rendering below the patient section in appointments |
| `AppointmentRenderEvent` | `RENDER_BEFORE_ACTION_BAR` | When rendering before the action bar in appointments |
| `AppointmentSetEvent` | `EVENT_HANDLE` | When an appointment is set/created |
| `AppointmentDialogCloseEvent` | `EVENT_NAME` | Before closing an appointment dialog |
| `CalendarFilterEvent` | `EVENT_HANDLE` | When filtering calendar data |

### 4. Global Settings Events

Use these to add configuration options:

| Event Class | Constant | Description |
|------------|----------|-------------|
| `GlobalsInitializedEvent` | `EVENT_HANDLE` | When global settings are initialized |

### 5. API-Related Events

Useful for extending the REST API:

| Event Class | Constant | Description |
|------------|----------|-------------|
| `RestApiCreateEvent` | `EVENT_HANDLE` | When creating REST API routes |
| `RestApiScopeEvent` | `EVENT_TYPE_GET_SUPPORTED_SCOPES` | When getting supported API scopes |
| `RestApiSecurityCheckEvent` | `EVENT_HANDLE` | When checking API security |

## Example: Using Appointment Events in Your Calendar Module

For a calendar module, you'll likely want to subscribe to appointment-related events. Here's how to do this:

```php
<?php
// In your openemr.bootstrap.php or Bootstrap class

use OpenEMR\Events\Appointments\AppointmentSetEvent;
use OpenEMR\Events\Appointments\CalendarFilterEvent;

// Listen for appointment creation/update
$eventDispatcher->addListener(AppointmentSetEvent::EVENT_HANDLE, function($event) {
    // Get the appointment data
    $appointmentData = $event->getAppointmentData();

    // Sync with external calendar system
    $calendarService = new YourCalendarService();
    $calendarService->syncAppointment($appointmentData);
});

// Listen for calendar filter events (customize what's shown in the calendar)
$eventDispatcher->addListener(CalendarFilterEvent::EVENT_HANDLE, function($event) {
    $filters = $event->getFilters();
    // Modify filters or add your own
    $filters['custom_calendar_view'] = [
        // Your custom filter logic
    ];
    $event->setFilters($filters);
});
```

## Best Practices for Working with Events

1. **Use Dependency Injection**: When possible, inject services instead of creating them in your event handlers

2. **Keep Event Handlers Focused**: Each event handler should do one thing well

3. **Performance Matters**: Remember that your event code runs on every trigger, so keep it efficient

4. **Error Handling**: Always include proper error handling in event listeners

5. **Ordering Matters**: The event system executes listeners in the order they were added, so be mindful of this when registering multiple listeners for the same event

## Finding Available Events

To discover available events in OpenEMR, you can:

1. Look at the events.md file (lists all event constants)
2. Search the codebase for classes that extend `Event`
3. Look for constant definitions with patterns like `const EVENT_NAME` or similar

## Conclusion

Using event constants instead of string literals is a best practice that makes your code more maintainable and less error-prone. By following this guide and leveraging OpenEMR's event system properly, you'll create modules that integrate seamlessly with the OpenEMR ecosystem while maintaining good separation of concerns.