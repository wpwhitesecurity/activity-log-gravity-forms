<?php

$custom_alerts = [
    __( 'Gravity Forms', 'wsal-gravity-forms' ) => [
        __( 'Monitor Gravity Forms', 'wsal-gravity-forms' ) => [

            [
                5700,
                WSAL_LOW,
                __( 'A form was created, modified', 'wsal-gravity-forms' ),
                __( 'A form was created, modified', 'wsal-gravity-forms' ),

                [
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_forms',
                'created',
            ],

            [
                5701,
                WSAL_MEDIUM,
                __( 'A form was moved to trash', 'wsal-gravity-forms' ),
                __( 'Moved the form to trash', 'wsal-gravity-forms' ),
                [
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_forms',
                'created',
            ],

            [
                5702,
                WSAL_MEDIUM,
                __( 'A form was permanently deleted', 'wsal-gravity-forms' ),
                __( 'A form was permanently deleted', 'wsal-gravity-forms' ),
                [
                    __( 'Permanently deleted the form', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [],
                'gravityforms_forms',
                'created',
            ],

            [
                5703,
                WSAL_MEDIUM,
                __( 'A form setting was modified', 'wsal-gravity-forms' ),
                __( 'The setting %setting_name% in form %form_name%', 'wsal-gravity-forms' ),
                [
                    __( 'Previous value', 'wsal-gravity-forms' ) => '%old_setting_value%',
                    __( 'New value', 'wsal-gravity-forms' ) => '%setting_value%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_forms',
                'modified',
            ],

            [
                5704,
                WSAL_LOW,
                __( 'A form was duplicated', 'wsal-gravity-forms' ),
                __( 'A form was duplicated', 'wsal-gravity-forms' ),
                [
                    __( 'Source form', 'wsal-gravity-forms' ) => '%original_form_name%',
                    __( 'New form name', 'wsal-gravity-forms' ) => '%new_form_name%',
                    __( 'Source form ID', 'wsal-gravity-forms' ) => '%original_form_id%',
                    __( 'New form ID', 'wsal-gravity-forms' ) => '%new_form_id%',
                ],
                [
                    __( 'View new duplicated form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkFormDuplicated%',
                ],
                'gravityforms_forms',
                'duplicated',
            ],

            [
                5715,
                WSAL_MEDIUM,
                __( 'A field was created, modified or deleted', 'wsal-gravity-forms' ),
                __( 'A field was created, modified or deleted', 'wsal-gravity-forms' ),
                [
                    __( 'Field name', 'wsal-gravity-forms' ) => '%field_name%',
                    __( 'Field type', 'wsal-gravity-forms' ) => '%field_type%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_fields',
                'created',
            ],

            [
                5709,
                WSAL_LOW,
                __( 'A form was submitted', 'wsal-gravity-forms' ),
                __( 'A form was submitted', 'wsal-gravity-forms' ),
                [
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                    __( 'Submission email', 'wsal-gravity-forms' ) => '%email%',
                ],
                [
                    __( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
                ],
                'gravityforms_forms',
                'duplicated',
            ],

            /*
             * Form confirmations.
             */
            [
                5705,
                WSAL_MEDIUM,
                __( 'A confirmation was created, modified or deleted', 'wsal-gravity-forms' ),
                __( 'A confirmation was created, modified or deleted', 'wsal-gravity-forms' ),
                [
                    __( 'Confirmation name', 'wsal-gravity-forms' ) => '%confirmation_name%',
                    __( 'Confirmation type', 'wsal-gravity-forms' ) => '%confirmation_type%',
                    __( 'Confirmation message', 'wsal-gravity-forms' ) => '%confirmation_message%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_confirmations',
                'created',
            ],

            [
                5708,
                WSAL_LOW,
                __( 'A confirmation was activated or deactivated', 'wsal-gravity-forms' ),
                __( 'The confirmation %confirmation_name% in the form %form_name%', 'wsal-gravity-forms' ),
                [
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_confirmations',
                'created',
            ],

            /*
             * Form notifications.
             */
            [
                5706,
                WSAL_MEDIUM,
                __( 'A notification was created, modified or deleted', 'wsal-gravity-forms' ),
                __( 'A notification was created, modified or deleted', 'wsal-gravity-forms' ),
                [
                    __( 'Notification name', 'wsal-gravity-forms' ) => '%notification_name%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_notifications',
                'created',
            ],

            [
                5707,
                WSAL_LOW,
                __( 'A notification was activated or deactivated', 'wsal-gravity-forms' ),
                __( 'A notification was activated or deactivated', 'wsal-gravity-forms' ),
                [
                    __( 'Notification name', 'wsal-gravity-forms' ) => '%notification_name%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
                ],
                'gravityforms_notifications',
                'activated',
            ],

            /*
             * Form entries.
             */
            [
                5710,
                WSAL_LOW,
                __( 'An entry was starred or unstarred', 'wsal-gravity-forms' ),
                __( 'An entry was starred or unstarred', 'wsal-gravity-forms' ),
                [
                    __( 'Entry title', 'wsal-gravity-forms' ) => '%entry_title%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
                ],
                'gravityforms_entries',
                'starred',
            ],

            [
                5711,
                WSAL_LOW,
                __( 'An entry was marked as read or unread', 'wsal-gravity-forms' ),
                __( 'An entry was marked as read or unread', 'wsal-gravity-forms' ),
                [
                    __( 'Entry title', 'wsal-gravity-forms' ) => '%entry_title%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
                ],
                'gravityforms_entries',
                'read',
            ],

            [
                5712,
                WSAL_MEDIUM,
                __( 'An entry was moved to trash', 'wsal-gravity-forms' ),
                __( 'An entry was moved to trash', 'wsal-gravity-forms' ),
                [
                    __( 'An entry was', 'wsal-gravity-forms' ) => '%event_desc%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID ', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
                ],
                'gravityforms_entries',
                'read',
            ],

            [
                5713,
                WSAL_MEDIUM,
                __( 'An entry was permanently deleted', 'wsal-gravity-forms' ),
                __( 'An entry was permanently deleted', 'wsal-gravity-forms' ),
                [
                    __( 'Permanently deleted the entry', 'wsal-gravity-forms' ) => '%entry_title%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [],
                'gravityforms_entries',
                'read',
            ],

            [
                5714,
                WSAL_MEDIUM,
                __( 'An entry note was created or deleted', 'wsal-gravity-forms' ),
                __( 'An entry note was created or deleted', 'wsal-gravity-forms' ),
                [
                    __( 'The entry note', 'wsal-gravity-forms' ) => '%entry_note%',
                    __( 'Entry title', 'wsal-gravity-forms' ) => '%entry_title%',
                    __( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
                    __( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
                ],
                [
                    __( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
                ],
                'gravityforms_entries',
                'read',
            ],

            /*
             * Settings.
             */
            [
                5716,
                WSAL_HIGH,
                __( 'A plugin setting was changed.', 'wsal-gravity-forms' ),
                __( 'A plugin setting was changed.', 'wsal-gravity-forms' ),
                [
                    __( 'The plugin setting', 'wsal-gravity-forms' ) => '%setting_name%',
                    __( 'Previous value', 'wsal-gravity-forms' ) => '%old_value%',
                    __( 'New value', 'wsal-gravity-forms' ) => '%new_value%',
                ],
                [],
                'gravityforms_settings',
                'modified',
            ],
        ],
    ],
];
