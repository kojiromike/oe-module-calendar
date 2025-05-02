# OpenEMR Events Documentation

<!--
for f in $(git grep -lF 'extends Event' '**/*.php'); do git grep -E -e "const [A-Z_]+ = '[^']*\.[^']*'" -e 'const [A-Z_]+ = "[^"]*\.[^"]*"' "$f" || { echo "$f" >&2; }; done
-->

This document provides a comprehensive list of events available in OpenEMR, organized in a table format with their fully qualified names and constant identifiers.

| Event | Description |
|-------|-------------|
| `OpenEMR\Events\Appointments\AppointmentDialogCloseEvent::EVENT_NAME` | openemr.appointment.add_edit_event.close.before |
| `OpenEMR\Events\Appointments\AppointmentRenderEvent::RENDER_JAVASCRIPT` | appointment.render.javascript |
| `OpenEMR\Events\Appointments\AppointmentRenderEvent::RENDER_BELOW_PATIENT` | appointment.render.below.patient |
| `OpenEMR\Events\Appointments\AppointmentRenderEvent::RENDER_BEFORE_ACTION_BAR` | appointment.render.action-bar.before |
| `OpenEMR\Events\Appointments\AppointmentSetEvent::EVENT_HANDLE` | appointment.set |
| `OpenEMR\Events\Appointments\CalendarFilterEvent::EVENT_HANDLE` | calendar.customFilter |
| `OpenEMR\Events\Billing\Payments\DeletePayment::ACTION_DELETE_PAYMENT` | billing.payment.action.delete.payment |
| `OpenEMR\Events\Billing\Payments\PostFrontPayment::ACTION_POST_FRONT_PAYMENT` | billing.payment.action.post.front.payment |
| `OpenEMR\Events\CDA\CDAPostParseEvent::EVENT_HANDLE` | cda.component.post.parse |
| `OpenEMR\Events\CDA\CDAPreParseEvent::EVENT_HANDLE` | cda.component.pre.parse |
| `OpenEMR\Events\Codes\ExternalCodesCreatedEvent::EVENT_HANDLE` | external_codes.register |
| `OpenEMR\Events\Core\ModuleLoadEvents::MODULES_LOADED` | modules.loaded |
| `OpenEMR\Events\Core\Sanitize\IsAcceptedFileFilterEvent::EVENT_GET_ACCEPTED_LIST` | sanitize.isWhiteFile.getAcceptedList |
| `OpenEMR\Events\Core\Sanitize\IsAcceptedFileFilterEvent::EVENT_FILTER_IS_ACCEPTED_FILE` | sanitize.isWhiteFile.filterIsAccepted |
| `OpenEMR\Events\Encounter\EncounterButtonEvent::BUTTON_RENDER` | button.render |
| `OpenEMR\Events\Encounter\EncounterMenuEvent::MENU_RENDER` | menu.render |
| `OpenEMR\Events\Facility\FacilityCreatedEvent::EVENT_HANDLE` | facility.created |
| `OpenEMR\Events\Facility\FacilityUpdatedEvent::EVENT_HANDLE` | facility.updated |
| `OpenEMR\Events\Globals\GlobalsInitializedEvent::EVENT_HANDLE` | globals.initialized |
| `OpenEMR\Events\Main\Tabs\RenderEvent::EVENT_BODY_RENDER_PRE` | main.body.render.pre |
| `OpenEMR\Events\Main\Tabs\RenderEvent::EVENT_BODY_RENDER_POST` | main.body.render.post |
| `OpenEMR\Events\Messaging\SendNotificationEvent::ACTIONS_RENDER_NOTIFICATION_POST` | sendNotification.actions.render.post |
| `OpenEMR\Events\Messaging\SendNotificationEvent::JAVASCRIPT_READY_NOTIFICATION_POST` | sendNotification.javascript.load.post |
| `OpenEMR\Events\Messaging\SendNotificationEvent::SEND_NOTIFICATION_BY_SERVICE` | sendNotification.send |
| `OpenEMR\Events\Messaging\SendNotificationEvent::SEND_NOTIFICATION_SERVICE_ONETIME` | sendNotification.service.onetime |
| `OpenEMR\Events\Messaging\SendNotificationEvent::ACTIONS_RENDER_NOTIFICATION_UNIVERSAL` | sendNotification.actions.render.universal |
| `OpenEMR\Events\Messaging\SendNotificationEvent::JAVASCRIPT_LOAD_NOTIFICATION_UNIVERSAL` | sendNotification.javascript.load.universal |
| `OpenEMR\Events\Messaging\SendNotificationEvent::SEND_NOTIFICATION_SERVICE_UNIVERSAL_ONETIME` | sendNotification.service.universal.onetime |
| `OpenEMR\Events\Messaging\SendSmsEvent::ACTIONS_RENDER_SMS_POST` | sendSMS.actions.render.post |
| `OpenEMR\Events\Messaging\SendSmsEvent::JAVASCRIPT_READY_SMS_POST` | sendSMS.javascript.load.post |
| `OpenEMR\Events\Patient\BeforePatientCreatedEvent::EVENT_HANDLE` | patient.before-created |
| `OpenEMR\Events\Patient\BeforePatientUpdatedEvent::EVENT_HANDLE` | patient.before-updated |
| `OpenEMR\Events\Patient\PatientBeforeCreatedAuxEvent::EVENT_HANDLE` | patient.before-created-aux |
| `OpenEMR\Events\Patient\PatientCreatedEvent::EVENT_HANDLE` | patient.created |
| `OpenEMR\Events\Patient\PatientUpdatedEvent::EVENT_HANDLE` | patient.updated |
| `OpenEMR\Events\Patient\PatientUpdatedEventAux::EVENT_HANDLE` | patient.updated.aux |
| `OpenEMR\Events\Patient\Summary\Card\RenderEvent::EVENT_HANDLE` | patientSummaryCard.render |
| `OpenEMR\Events\Patient\Summary\Card\SectionEvent::EVENT_HANDLE` | section.render |
| `OpenEMR\Events\Patient\Summary\PortalCredentialsTemplateDataFilterEvent::EVENT_HANDLE` | patient.portal-credentials.filter |
| `OpenEMR\Events\PatientDemographics\RenderEvent::EVENT_SECTION_LIST_RENDER_BEFORE` | patientDemographics.render.section.before |
| `OpenEMR\Events\PatientDemographics\RenderEvent::EVENT_SECTION_LIST_RENDER_AFTER` | patientDemographics.render.section.after |
| `OpenEMR\Events\PatientDemographics\RenderEvent::EVENT_RENDER_POST_PAGELOAD` | patientDemographics.render.post_page_load |
| `OpenEMR\Events\PatientDemographics\RenderPharmacySectionEvent::RENDER_JAVASCRIPT` | patientDemographics.render.javascript |
| `OpenEMR\Events\PatientDemographics\RenderPharmacySectionEvent::RENDER_AFTER_PHARMACY_SECTION` | patientDemographics.render.section.after.pharmacy |
| `OpenEMR\Events\PatientDemographics\RenderPharmacySectionEvent::RENDER_AFTER_SELECTED_PHARMACY_SECTION` | patientDemographics.render.after.selected.pharmacy |
| `OpenEMR\Events\PatientDemographics\UpdateEvent::EVENT_HANDLE` | patientDemographics.update |
| `OpenEMR\Events\PatientDemographics\ViewEvent::EVENT_HANDLE` | patientDemographics.view |
| `OpenEMR\Events\PatientDocuments\PatientDocumentEvent::ACTIONS_RENDER_FAX_ANCHOR` | documents.actions.render.fax.anchor |
| `OpenEMR\Events\PatientDocuments\PatientDocumentEvent::JAVASCRIPT_READY_FAX_DIALOG` | documents.javascript.fax.dialog |
| `OpenEMR\Events\PatientDocuments\PatientDocumentStoreOffsite::REMOTE_STORAGE_LOCATION` | documents.remote.storage.location |
| `OpenEMR\Events\PatientDocuments\PatientRetrieveOffsiteDocument::REMOTE_DOCUMENT_LOCATION` | remote.document.retrieve.location |
| `OpenEMR\Events\PatientPortal\AppointmentFilterEvent::EVENT_NAME` | home.appointment.filter |
| `OpenEMR\Events\PatientReport\PatientReportEvent::ACTIONS_RENDER_POST` | patientReport.actions.render.post |
| `OpenEMR\Events\PatientReport\PatientReportEvent::JAVASCRIPT_READY_POST` | patientReport.javascript.load.post |
| `OpenEMR\Events\PatientReport\PatientReportFilterEvent::FILTER_PORTAL_TWIG_DATA` | patientReport.filter.portal.twig.data |
| `OpenEMR\Events\PatientReport\PatientReportFilterEvent::FILTER_PORTAL_HEALTHSNAPSHOT_TWIG_DATA` | home.filter.portal.healthsnapshot.twig.data |
| `OpenEMR\Events\RestApiExtend\RestApiCreateEvent::EVENT_HANDLE` | restConfig.route_map.create |
| `OpenEMR\Events\RestApiExtend\RestApiScopeEvent::EVENT_TYPE_GET_SUPPORTED_SCOPES` | api.scope.get-supported-scopes |
| `OpenEMR\Events\RestApiExtend\RestApiSecurityCheckEvent::EVENT_HANDLE` | api.route.security.check |
| `OpenEMR\Events\Services\QuestLabTransmitEvent::EVENT_LAB_TRANSMIT` | lab.transmit |
| `OpenEMR\Events\Services\QuestLabTransmitEvent::EVENT_LAB_POST_ORDER_LOAD` | lab.post_order_load |
| `OpenEMR\Events\Services\ServiceDeleteEvent::EVENT_PRE_DELETE` | service.delete.pre |
| `OpenEMR\Events\Services\ServiceDeleteEvent::EVENT_POST_DELETE` | service.delete.post |
| `OpenEMR\Events\Services\ServiceSaveEvent::EVENT_PRE_SAVE` | service.save.pre |
| `OpenEMR\Events\Services\ServiceSaveEvent::EVENT_POST_SAVE` | service.save.post |
| `OpenEMR\Events\User\UserCreatedEvent::EVENT_HANDLE` | user.created |
| `OpenEMR\Events\User\UserUpdatedEvent::EVENT_HANDLE` | user.updated |
| `OpenEMR\Menu\MenuEvent::MENU_UPDATE` | menu.update |
| `OpenEMR\Menu\MenuEvent::MENU_RESTRICT` | menu.restrict |
| `OpenEMR\Menu\PatientMenuEvent::MENU_UPDATE` | patient.menu.update |
| `OpenEMR\Menu\PatientMenuEvent::MENU_RESTRICT` | patient.menu.restrict |
