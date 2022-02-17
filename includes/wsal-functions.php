<?php
/*
Filter in our custom functions into WSAL.
 */
add_filter( 'wsal_event_objects', 'wsal_gravityforms_add_custom_event_objects', 10, 2 );
add_filter( 'wsal_event_type_data', 'wsal_gravityforms_add_custom_event_type', 10, 2 );
add_filter( 'wsal_togglealerts_sub_category_events', 'wsal_gravityforms_extension_togglealerts_sub_category_events' );
add_filter( 'wsal_togglealerts_sub_category_titles', 'wsal_gravityforms_extension_togglealerts_sub_category_titles', 10, 2 );
add_filter( 'admin_init', 'wsal_gravityforms_extension_replace_duplicate_event_notice' );
add_filter( 'wsal_load_public_sensors', 'wsal_gravityforms_extension_load_public_sensors' );
add_action( 'wsal_togglealerts_append_content_to_toggle', 'append_content_to_toggle' );
add_filter( 'wsal_load_on_frontend', 'wsal_gravityforms_allow_sensor_on_frontend', 10, 2 );
add_action( 'init', 'wsal_gravityforms_init' );


function wsal_gravityforms_extension_load_public_sensors( $value ) {
	$value[] = 'Gravity_Forms';
	return $value;
}

function wsal_gravityforms_allow_sensor_on_frontend( $default, $frontend_events ) {
	$should_load = ( $default || ! empty( $frontend_events['gravityforms'] ) ) ? true : false;

	return $should_load;
}

/**
 * Append some extra content below an event in the ToggleAlerts view.
 */
function append_content_to_toggle( $alert_id ) {

	if ( 5709 === $alert_id ) {
		$frontend_events     = WSAL_Settings::get_frontend_events();
		$enable_for_visitors = ( isset( $frontend_events['gravityforms'] ) && $frontend_events['gravityforms'] ) ? true : false;
		?>
	<tr>
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
  * Add specific events so we can use them for category titles.
  */
function wsal_gravityforms_extension_togglealerts_sub_category_events( $sub_category_events ) {
	$new_events          = array( 5700, 5705, 5706, 5710, 5716 );
	$sub_category_events = array_merge( $sub_category_events, $new_events );
	return $sub_category_events;
}

/**
 * Add sub cateogry titles to ToggleView page in WSAL.
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

/**
 * Checks for exporing of form data and triggers an event if needed. We do this here has the sensor does not detect this change.
 * Exactly like event 9099 within the WooCommerce extension and quite rare.
 */
function wsal_gravityforms_init() {
	if ( isset( $_POST['export_forms'] ) && check_admin_referer( 'gf_export_forms', 'gf_export_forms_nonce' ) ) {
		$form_ids = isset( $_POST['gf_form_id'] ) ? $_POST['gf_form_id'] : array();
		if ( ! empty( $form_ids ) ) {
			wsal_gravityforms_event_process_export_forms( $form_ids );
		}
	}
}

/**
 * Triggers event 5719
 *
 * @param  array $form_ids - Array of form IDs being exported.
 */
function wsal_gravityforms_event_process_export_forms( $form_ids ) {
	foreach ( $form_ids as $form_id ) {
		$form       = GFAPI::get_form( $form_id );
		$alert_code = 5719;

		$wsal = WpSecurityAuditLog::GetInstance();

		if ( ! isset( $wsal->alerts ) ) {
			$wsal->alerts = new WSAL_AlertManager( $wsal );
		}

		$variables = array(
			'EventType' => 'exported',
			'form_name' => sanitize_text_field( $form['title'] ),
			'form_id'   => $form_id,
		);

		$wsal->alerts->Trigger( $alert_code, $variables );
	}
}
