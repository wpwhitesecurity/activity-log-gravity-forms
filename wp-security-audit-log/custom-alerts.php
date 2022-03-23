<?php
/**
 * Our list of events.
 *
 * @package wsal
 * @subpackage wsal-gravity-forms
 */

// phpcs:disable WordPress.WP.I18n.UnorderedPlaceholdersText 
// phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment

$custom_alerts = array(
	esc_html__( 'Gravity Forms', 'wsal-gravity-forms' ) => array(
		esc_html__( 'Monitor Gravity Forms', 'wsal-gravity-forms' ) => array(

			array(
				5700,
				WSAL_LOW,
				esc_html__( 'A form was created, modified', 'wsal-gravity-forms' ),
				esc_html__( 'The Form called %form_name%.', 'wsal-gravity-forms' ),

				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'created',
			),

			array(
				5701,
				WSAL_MEDIUM,
				esc_html__( 'A form was moved to trash', 'wsal-gravity-forms' ),
				esc_html__( 'Moved the form to trash.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'created',
			),

			array(
				5702,
				WSAL_MEDIUM,
				esc_html__( 'A form was permanently deleted', 'wsal-gravity-forms' ),
				esc_html__( 'Permanently deleted the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(),
				'gravityforms_forms',
				'created',
			),

			array(
				5703,
				WSAL_MEDIUM,
				esc_html__( 'A form setting was modified', 'wsal-gravity-forms' ),
				esc_html__( 'The setting %setting_name% in form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Previous value', 'wsal-gravity-forms' ) => '%old_setting_value%',
					esc_html__( 'New value', 'wsal-gravity-forms' ) => '%setting_value%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'modified',
			),

			array(
				5704,
				WSAL_LOW,
				esc_html__( 'A form was duplicated', 'wsal-gravity-forms' ),
				esc_html__( 'Duplicated the form %original_form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'New form name', 'wsal-gravity-forms' ) => '%new_form_name%',
					esc_html__( 'Source form ID', 'wsal-gravity-forms' ) => '%original_form_id%',
					esc_html__( 'New form ID', 'wsal-gravity-forms' ) => '%new_form_id%',
				),
				array(
					esc_html__( 'View new duplicated form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_forms',
				'duplicated',
			),

			array(
				5715,
				WSAL_MEDIUM,
				esc_html__( 'A field was created, modified or deleted', 'wsal-gravity-forms' ),
				esc_html__( 'The Field called %field_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Field type', 'wsal-gravity-forms' ) => '%field_type%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_fields',
				'created',
			),

			array(
				5709,
				WSAL_LOW,
				esc_html__( 'A form was submitted', 'wsal-gravity-forms' ),
				esc_html__( 'Submitted the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
					esc_html__( 'Submission email', 'wsal-gravity-forms' ) => '%email%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
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
				esc_html__( 'A confirmation was created, modified or deleted', 'wsal-gravity-forms' ),
				esc_html__( 'The Confirmation called %confirmation_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Confirmation type', 'wsal-gravity-forms' ) => '%confirmation_type%',
					esc_html__( 'Confirmation message', 'wsal-gravity-forms' ) => '%confirmation_message%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_confirmations',
				'created',
			),

			array(
				5708,
				WSAL_LOW,
				esc_html__( 'A confirmation was activated or deactivated', 'wsal-gravity-forms' ),
				esc_html__( 'The confirmation %confirmation_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
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
				esc_html__( 'A notification was created, modified or deleted', 'wsal-gravity-forms' ),
				esc_html__( 'The Notification called %notification_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
				),
				'gravityforms_notifications',
				'created',
			),

			array(
				5707,
				WSAL_LOW,
				esc_html__( 'A notification was activated or deactivated', 'wsal-gravity-forms' ),
				esc_html__( 'Changed the status of the Notification called %notification_name% in the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View form in the editor', 'wsal-gravity-forms' ) => '%EditorLinkForm%',
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
				esc_html__( 'An entry was starred or unstarred', 'wsal-gravity-forms' ),
				esc_html__( 'Entry title: %entry_title%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'starred',
			),

			array(
				5711,
				WSAL_LOW,
				esc_html__( 'An entry was marked as read or unread', 'wsal-gravity-forms' ),
				esc_html__( 'The entry called %entry_title% from form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			array(
				5712,
				WSAL_MEDIUM,
				esc_html__( 'An entry was moved to trash', 'wsal-gravity-forms' ),
				esc_html__( 'Deleted the entry %event_desc%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID ', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			array(
				5713,
				WSAL_MEDIUM,
				esc_html__( 'An entry was permanently deleted', 'wsal-gravity-forms' ),
				esc_html__( 'Permanently deleted the entry %entry_title%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(),
				'gravityforms_entries',
				'read',
			),

			array(
				5714,
				WSAL_MEDIUM,
				esc_html__( 'An entry note was created or deleted', 'wsal-gravity-forms' ),
				esc_html__( 'The entry note %entry_note%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Entry title', 'wsal-gravity-forms' ) => '%entry_title%',
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'read',
			),

			array(
				5717,
				WSAL_MEDIUM,
				esc_html__( 'An entry was edited', 'wsal-gravity-forms' ),
				esc_html__( 'The entry %entry_name% was edited.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form name', 'wsal-gravity-forms' ) => '%form_name%',
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(
					esc_html__( 'View entry', 'wsal-gravity-forms' ) => '%EntryLink%',
				),
				'gravityforms_entries',
				'modified',
			),

			/*
			 * Settings.
			 */
			array(
				5716,
				WSAL_HIGH,
				esc_html__( 'A plugin setting was changed.', 'wsal-gravity-forms' ),
				esc_html__( 'Changed the plugin setting %setting_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Previous value', 'wsal-gravity-forms' ) => '%old_value%',
					esc_html__( 'New value', 'wsal-gravity-forms' ) => '%new_value%',
				),
				array(),
				'gravityforms_settings',
				'modified',
			),
			array(
				5718,
				WSAL_LOW,
				esc_html__( 'Form entries were imported / exported.', 'wsal-gravity-forms' ),
				esc_html__( 'The entries from the form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
					esc_html__( 'Date range start', 'wsal-gravity-forms' ) => '%start%',
					esc_html__( 'Date range end', 'wsal-gravity-forms' ) => '%end%',
				),
				array(),
				'gravityforms_settings',
				'exported',
			),
			array(
				5719,
				WSAL_LOW,
				esc_html__( 'A form was imported / exported.', 'wsal-gravity-forms' ),
				esc_html__( 'The form %form_name%.', 'wsal-gravity-forms' ),
				array(
					esc_html__( 'Form ID', 'wsal-gravity-forms' ) => '%form_id%',
				),
				array(),
				'gravityforms_settings',
				'imported',
			),
		),
	),
);