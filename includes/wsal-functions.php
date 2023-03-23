<?php
/**
 * Add our neccesary hooks and filters.
 *
 * @package wsal
 * @subpackage wsal-gravity-forms
 */

use WSAL\Helpers\Classes_Helper;

add_filter( 'wsal_event_objects', 'wsal_gravityforms_add_custom_event_objects', 10, 2 );
add_filter( 'wsal_event_type_data', 'wsal_gravityforms_add_custom_event_type', 10, 2 );
add_filter( 'wsal_togglealerts_sub_category_events', 'wsal_gravityforms_extension_togglealerts_sub_category_events' );
add_filter( 'wsal_togglealerts_sub_category_titles', 'wsal_gravityforms_extension_togglealerts_sub_category_titles', 10, 2 );
add_filter( 'admin_init', 'wsal_gravityforms_extension_replace_duplicate_event_notice' );
add_filter( 'wsal_load_public_sensors', 'wsal_gravityforms_extension_load_public_sensors' );
add_action( 'wsal_togglealerts_append_content_to_toggle', 'append_content_to_toggle' );
add_filter( 'wsal_load_on_frontend', 'wsal_gravityforms_allow_sensor_on_frontend', 10, 2 );

/**
 * Addes our plugin to the list of allowed public sensors.
 *
 * @param  array ] $value - Allowed sensors.
 * @return array
 */
function wsal_gravityforms_extension_load_public_sensors( $value ) {
	$value[] = 'Gravity_Forms';
	return $value;
}

/**
 * Ensures front end sensor can load when needed.
 *
 * @param bool  $default - Current loading situation.
 * @param array $frontend_events - Array of current front end events.
 *
 * @return bool
 */
function wsal_gravityforms_allow_sensor_on_frontend( $default, $frontend_events ) {
	return ( $default || ! empty( $frontend_events['gravityforms'] ) );
}

/**
 * Append some extra content below an event in the ToggleAlerts view.
 *
 * @param int $alert_id - Event ID.
 *
 * @return void
 */
function append_content_to_toggle( $alert_id ) {

	if ( 5709 === $alert_id ) {
		$frontend_events     = WSAL_Settings::get_frontend_events();
		$enable_for_visitors = ( isset( $frontend_events['gravityforms'] ) && $frontend_events['gravityforms'] ) ? true : false;
		?>
	<tr class="alert-wrapper" data-alert-cat="Gravity Forms" data-alert-subcat="Monitor Gravity Forms" data-is-attached-to-alert="5709">
		<td></td>
		<td>
		<input name="frontend-events[gravityforms]" type="checkbox" id="frontend-events[woocommerce]" value="1" <?php checked( $enable_for_visitors ); ?> />
		</td>
		<td colspan="2"><?php esc_html_e( 'Keep a log when website visitors submits a form (by default the plugin only keeps a log when logged in users submit a form).', 'wsal-gravity-forms' ); ?></td>
	</tr>
		<?php
	}
}

/**
 * Register a custom event object within WSAL.
 *
 * @param array $objects array of objects current registered within WSAL.
 */
function wsal_gravityforms_add_custom_event_objects( $objects ) {
	$new_objects = array(
		'gravityforms_forms'         => esc_html__( 'Forms in Gravity Forms', 'wsal-gravity-forms' ),
		'gravityforms_confirmations' => esc_html__( 'Confirmations in Gravity Forms', 'wsal-gravity-forms' ),
		'gravityforms_notifications' => esc_html__( 'Notifications in Gravity Forms', 'wsal-gravity-forms' ),
		'gravityforms_entries'       => esc_html__( 'Entries in Gravity Forms', 'wsal-gravity-forms' ),
		'gravityforms_fields'        => esc_html__( 'Fields in Gravity Forms', 'wsal-gravity-forms' ),
		'gravityforms_settings'      => esc_html__( 'Settings in Gravity Forms', 'wsal-gravity-forms' ),
	);

	// combine the two arrays.
	$objects = array_merge( $objects, $new_objects );

	return $objects;
}

/**
 * Added our event types to the available list.
 *
 * @param  array $types - Current event types.
 *
 * @return array $types - Altered list.
 */
function wsal_gravityforms_add_custom_event_type( $types ) {
	$new_types = array(
		'starred'   => esc_html__( 'Starred', 'wsal-gravity-forms' ),
		'unstarred' => esc_html__( 'Unstarred', 'wsal-gravity-forms' ),
		'read'      => esc_html__( 'Read', 'wsal-gravity-forms' ),
		'unread'    => esc_html__( 'Unread', 'wsal-gravity-forms' ),
		'submitted' => esc_html__( 'Submitted', 'wsal-gravity-forms' ),
		'imported'  => esc_html__( 'Imported', 'wsal-gravity-forms' ),
		'exported'  => esc_html__( 'Exported', 'wsal-gravity-forms' ),
	);

	// combine the two arrays.
	$types = array_merge( $types, $new_types );

	return $types;
}

/**
 * Lets WSAL know which events should have a sub category.
 *
 * @param  array $sub_category_events - Current list of events.
 *
 * @return array $sub_category_events - Appended list of events.
 */
function wsal_gravityforms_extension_togglealerts_sub_category_events( $sub_category_events ) {
	$new_events          = array( 5700, 5705, 5706, 5710, 5716 );
	$sub_category_events = array_merge( $sub_category_events, $new_events );
	return $sub_category_events;
}

/**
 * Adds the titles to the ToggleEvents view for the relevent events.
 *
 * @param string $title - Default title for this event.
 * @param int    $alert_id - Alert ID we are determining the title for.
 *
 * @return string $title - Our new title.
 */
function wsal_gravityforms_extension_togglealerts_sub_category_titles( $title, $alert_id ) {
	if ( 5700 === $alert_id ) {
		$title = esc_html_e( 'Forms', 'wp-security-audit-log' );
	}
	if ( 5705 === $alert_id ) {
		$title = esc_html_e( 'Form confirmations', 'wp-security-audit-log' );
	}
	if ( 5706 === $alert_id ) {
		$title = esc_html_e( 'Form notifications', 'wp-security-audit-log' );
	}
	if ( 5710 === $alert_id ) {
		$title = esc_html_e( 'Entries', 'wp-security-audit-log' );
	}
	if ( 5716 === $alert_id ) {
		$title = esc_html_e( 'Settings', 'wp-security-audit-log' );
	}
	return $title;
}

/**
 * If a user is running an older version of WSAL, they will see a "duplicate event" error.
 * This function checks and runs a filter to replace that notice. Its done via JS as we cant
 * currently give this notice a neat ID/class.
 */
function wsal_gravityforms_extension_replace_duplicate_event_notice() {
	$wsal_version = get_site_option( 'wsal_version' );
	if ( version_compare( $wsal_version, '4.1.3.2', '<=' ) ) {
		add_action( 'admin_footer', 'wsal_gravityforms_extension_replacement_duplicate_event_notice' );
	}
}

/**
 * Replacement "duplicate event" notice text.
 */
function wsal_gravityforms_extension_replacement_duplicate_event_notice() {
	$replacement_text = esc_html__( 'You are running an old version of WP Activity Log. Please update the plugin to run it alongside this extension: GravityForms', 'wp-security-audit-log' );
	?>
	<script type="text/javascript">
		if ( jQuery( '.notice.notice-error span[style="color:#dc3232; font-weight:bold;"]' ).length ) {
			jQuery( '.notice.notice-error span[style="color:#dc3232; font-weight:bold;"]' ).parent().text( '<?php echo esc_html( $replacement_text ); ?>' );
		}
	</script>
	<?php
}

add_action(
	'wsal_sensors_manager_add',
	/**
	* Adds sensors classes to the Class Helper
	*
	* @return void
	*
	* @since latest
	*/
	function () {
		require_once __DIR__ . '/../wp-security-audit-log/sensors/class-gravity-forms-sensor.php';

		Classes_Helper::add_to_class_map(
			array(
				'WSAL\\Plugin_Sensors\\Gravity_Forms_Sensor' => __DIR__ . '/../wp-security-audit-log/sensors/class-gravity-forms-sensor.php',
			)
		);
	}
);

