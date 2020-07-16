<?php

$custom_alerts = array(
	__( 'Gravity Forms', 'wp-security-audit-log' ) => array(
		__( 'Monitor Gravity Forms', 'wp-security-audit-log' ) => array(

			array(
				5700,
				WSAL_LOW,
				__( 'A form was created, modified or deleted', 'wp-security-audit-log' ),
				__( 'Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_forms',
				'created',
			),

			array(
				5701,
				WSAL_LOW,
				__( 'A form was moved to trash', 'wp-security-audit-log' ),
				__( 'Form was moved to trash %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_forms',
				'created',
			),

			array(
				5702,
				WSAL_LOW,
				__( 'A form was permanently deleted', 'wp-security-audit-log' ),
				__( 'Form was permanently deleted %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id%', 'wp-security-audit-log' ),
				'gravityforms_forms',
				'created',
			),

			array(
				5703,
				WSAL_MEDIUM,
				__( 'A form setting was enabled, modified or disabled', 'wp-security-audit-log' ),
				__( 'Setting name %setting_name% %LineBreak% Setting value %setting_value% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_forms',
				'modified',
			),

			array(
				5704,
				WSAL_LOW,
				__( 'A form was duplicated', 'wp-security-audit-log' ),
				__( 'Source form %original_form_name% %LineBreak% New form name %new_form_name% %LineBreak% Source form ID %original_form_id% %LineBreak% New form ID: %new_form_id% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_forms',
				'duplicated',
			),

			array(
				5705,
				WSAL_MEDIUM,
				__( 'A form confirmation was created, modified or deleted', 'wp-security-audit-log' ),
				__( 'Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% Confirmation name: %confirmation_name% %LineBreak% Confirmation type: %confirmation_type% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_confirmations',
				'created',
			),

			array(
				5706,
				WSAL_LOW,
				__( 'A form notification was created, modified or deleted', 'wp-security-audit-log' ),
				__( 'Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% Notification name: %notification_name% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_notifications',
				'created',
			),

			array(
				5707,
				WSAL_MEDIUM,
				__( 'A form notification was activated or deactivated', 'wp-security-audit-log' ),
				__( 'Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% Notification name: %notification_name% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_notifications',
				'activated',
			),

			array(
				5710,
				WSAL_LOW,
				__( 'A form entry was starred or unstarred', 'wp-security-audit-log' ),
				__( 'Entry title %entry_title% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EntryLink%', 'wp-security-audit-log' ),
				'gravityforms_entries',
				'starred',
			),

			array(
				5711,
				WSAL_LOW,
				__( 'A form entry was marked as read or unread', 'wp-security-audit-log' ),
				__( 'Entry title %entry_title% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EntryLink%', 'wp-security-audit-log' ),
				'gravityforms_entries',
				'read',
			),

			array(
				5712,
				WSAL_LOW,
				__( 'A form entry was moved to trash', 'wp-security-audit-log' ),
				__( 'A form entry was %event_desc% %LineBreak% Entry title %entry_title% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EntryLink%', 'wp-security-audit-log' ),
				'gravityforms_entries',
				'read',
			),

			array(
				5713,
				WSAL_MEDIUM,
				__( 'A form entry was permanently deleted', 'wp-security-audit-log' ),
				__( 'A form entry was permanently deleted %LineBreak% Entry title %entry_title% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id%', 'wp-security-audit-log' ),
				'gravityforms_entries',
				'read',
			),

			array(
				5714,
				WSAL_LOW,
				__( 'A form entry note was created or deleted', 'wp-security-audit-log' ),
				__( 'Note %entry_note% %LineBreak% Entry title %entry_title% %LineBreak% Form name: %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EntryLink%', 'wp-security-audit-log' ),
				'gravityforms_entries',
				'read',
			),

			array(
				5715,
				WSAL_MEDIUM,
				__( 'A field created, modified or deleted', 'wp-security-audit-log' ),
				__( 'Field name %field_name% %LineBreak% Field type %field_type% %LineBreak% Form name %form_name% %LineBreak% Form ID: %form_id% %LineBreak% %EditorLinkForm%', 'wp-security-audit-log' ),
				'gravityforms_fields',
				'created',
			),

			array(
				5716,
				WSAL_LOW,
				__( 'A setting was modified', 'wp-security-audit-log' ),
				__( 'Setting name %setting_name% %LineBreak% Old value %old_value% %LineBreak% New value %new_value% %LineBreak%', 'wp-security-audit-log' ),
				'gravityforms_notifications',
				'created',
			),

		),
	),
);
