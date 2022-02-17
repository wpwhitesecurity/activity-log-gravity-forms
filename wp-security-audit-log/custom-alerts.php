<?php // phpcs:disable WordPress.WP.I18n.UnorderedPlaceholdersText

$custom_alerts = array(
	__( 'Gravity Forms', 'wsal-gravity-forms' ) => array(
		__( 'Monitor Gravity Forms', 'wsal-gravity-forms' ) => array(

			array(
				5700,
				WSAL_LOW,
				__( 'A form was created, modified', 'wsal-gravity-forms' ),
				__( 'The Form called %form_name%.', 'wsal-gravity-forms' ),

				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'created',
			),

			array(
				5701,
				WSAL_MEDIUM,
				__( 'A form was moved to trash', 'wsal-gravity-forms' ),
				__( 'Moved the form to trash.', 'wsal-gravity-forms' ),
				array(
					__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'created',
			),

			array(
				5702,
				WSAL_MEDIUM,
				__( 'A form was permanently deleted', 'wsal-gravity-forms' ),
				__( 'Permanently deleted the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(),
				'gravityforms_forms',
				'created',
			),

			array(
				5703,
				WSAL_MEDIUM,
				__( 'A form setting was modified', 'wsal-gravity-forms' ),
				__( 'The setting %setting_name% in form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Previous value', 'wsal-gravity-forms' ) => '%old_setting_value%',
					__( 'New value', 'wsal-gravity-forms' ) => '%setting_value%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'modified',
			),

			array(
				5704,
				WSAL_LOW,
				__( 'A form was duplicated', 'wsal-gravity-forms' ),
				__( 'Duplicated the form %original_form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'New form name', 'wsal-gravity-forms' ) => '%new_form_name%',
					__( 'Source form ID', 'wsal-gravity-forms' ) => '%original_form_id%',
					__( 'New form ID', 'wsal-gravity-forms' ) => '%new_form_id%',
				),
				array(
					__( 'View new duplicated form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'duplicated',
			),

			array(
				5715,
				WSAL_MEDIUM,
				__( 'A field was created, modified or deleted', 'wsal-gravity-forms' ),
				__( 'The Field called %field_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Field type', 'wsal-gravity-forms' ) => '%field_type%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_fields',
				'created',
			),

			array(
				5709,
				WSAL_LOW,
				__( 'A form was submitted', 'wsal-gravity-forms' ),
				__( 'Submitted the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
					__( 'Submission email', 'wsal-gravity-forms' ) => '%email%',
				),
				array(
					__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_forms',
				'duplicated',
			),

			/*
			 * Form confirmations.
			 */
			array(
				5705,
				WSAL_MEDIUM,
				__( 'A confirmation was created, modified or deleted', 'wsal-gravity-forms' ),
				__( 'The Confirmation called %confirmation_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Confirmation type', 'wsal-gravity-forms' ) => '%confirmation_type%',
					__( 'Confirmation message', 'wsal-gravity-forms' ) => '%confirmation_message%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_confirmations',
				'created',
			),

			array(
				5708,
				WSAL_LOW,
				__( 'A confirmation was activated or deactivated', 'wsal-gravity-forms' ),
				__( 'The confirmation %confirmation_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_confirmations',
				'created',
			),

			/*
			 * Form notifications.
			 */
			array(
				5706,
				WSAL_MEDIUM,
				__( 'A notification was created, modified or deleted', 'wsal-gravity-forms' ),
				__( 'The Notification called %notification_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_notifications',
				'created',
			),

			array(
				5707,
				WSAL_LOW,
				__( 'A notification was activated or deactivated', 'wsal-gravity-forms' ),
				__( 'Changed the status of the Notification called %notification_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_notifications',
				'activated',
			),

			/*
			 * Form entries.
			 */
			array(
				5710,
				WSAL_LOW,
				__( 'An entry was starred or unstarred', 'wsal-gravity-forms' ),
				__( 'Entry title: %entry_title%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'starred',
			),

			array(
				5711,
				WSAL_LOW,
				__( 'An entry was marked as read or unread', 'wsal-gravity-forms' ),
				__( 'The entry called %entry_title% from form %form_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			array(
				5712,
				WSAL_MEDIUM,
				__( 'An entry was moved to trash', 'wsal-gravity-forms' ),
				__( 'Deleted the entry %event_desc%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					__( 'Form ID ', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			array(
				5713,
				WSAL_MEDIUM,
				__( 'An entry was permanently deleted', 'wsal-gravity-forms' ),
				__( 'Permanently deleted the entry %entry_title%.', 'wsal-gravity-forms' ),
				array(
					__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(),
				'gravityforms_entries',
				'read',
			),

			array(
				5714,
				WSAL_MEDIUM,
				__( 'An entry note was created or deleted', 'wsal-gravity-forms' ),
				__( 'The entry note %entry_note%.', 'wsal-gravity-forms' ),
				array(
					__( 'Entry title', 'wsal-gravity-forms' ) => '%entry_title%',
					__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			/*
			 * Settings.
			 */
			array(
				5716,
				WSAL_HIGH,
				__( 'A plugin setting was changed.', 'wsal-gravity-forms' ),
				__( 'Changed the plugin setting %setting_name%.', 'wsal-gravity-forms' ),
				array(
					__( 'Previous value', 'wsal-gravity-forms' ) => '%old_value%',
					__( 'New value', 'wsal-gravity-forms' ) => '%new_value%',
				),
				array(),
				'gravityforms_settings',
				'modified',
			),
		),
	),
);
