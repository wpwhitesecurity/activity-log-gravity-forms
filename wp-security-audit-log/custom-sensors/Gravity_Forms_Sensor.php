<?php
/**
 * Custom Sensors for PLUGINNAME
 *
 * Class file for alert manager.
 *
 * @since   1.0.0
 * @package Wsal
 */

class WSAL_Sensors_Gravity_Forms_Sensor extends WSAL_AbstractSensor {

	/**
	 * Holds a cached value if the checked alert has recently fired.
	 *
	 * @var null|array
	 */
	private $cached_alert_checks = null;

	/**
	 * Hook events related to sensor.
	 *
	 * @since 1.0.0
	 */
	public function HookEvents() {
		add_action( 'gform_form_post_get_meta', array( $this, 'get_before_post_edit_data' ) );

		// Forms
		add_action( 'gform_after_save_form', array( $this, 'event_form_saved' ), 10, 2 );
		add_action( 'gform_post_form_trashed', array( $this, 'event_form_trashed' ), 10, 1 );
		add_action( 'gform_before_delete_form', array( $this, 'event_form_deleted' ) );
		add_action( 'gform_post_form_duplicated', array( $this, 'event_form_duplicated' ), 10, 2 );
		add_action( 'gform_post_update_form_meta', array( $this, 'event_form_meta_updated' ), 10, 3 );

		// Confirmations
		add_action( 'gform_pre_confirmation_save', array( $this, 'event_form_confirmation_saved' ), 10, 3 );
		add_action( 'gform_pre_confirmation_deleted', array( $this, 'event_form_confirmation_deleted' ), 10, 2 );

		// Notifications
		add_action( 'gform_post_notification_save', array( $this, 'event_form_notification_saved' ), 10, 3 );

	}

	public function get_before_post_edit_data( $form ) {

		if ( isset( $form ) ) {
			if ( isset( $this->_old_form ) ) {
				$this->_old_form = $this->_old_form;
			} else {
				$this->_old_form = $form;
			}
		}

		return $form;
	}

	/**
	 * Forms created,
	 *
	 * @param  [type]  $form   [description]
	 * @param  boolean $is_new [description]
	 * @return [type]          [description]
	 */
	public function event_form_saved( $form, $is_new ) {

		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		if ( $is_new ) {
			// If we are here, then the form being saved is fresh.
			$alert_code = 5700;

			$variables = array(
				'EventType'      => 'created',
				'form_name'      => sanitize_text_field( $form['title'] ),
				'form_id'        => $form['id'],
				'EditorLinkForm' => $editor_link,
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		} else {
			// Otherwise, the form has been edited, so lets see whats going on.

		}

	}

	/**
	 * [event_form_trashed description]
	 *
	 * @param  [type] $form_id [description]
	 * @return [type]          [description]
	 */
	public function event_form_trashed( $form_id ) {
		$form       = GFAPI::get_form( $form_id );
		$alert_code = 5701;

		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'      => 'deleted',
			'form_name'      => sanitize_text_field( $form['title'] ),
			'form_id'        => $form['id'],
			'EditorLinkForm' => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );
	}

	/**
	 * [event_form_deleted description]
	 *
	 * @param  [type] $form_id [description]
	 * @return [type]          [description]
	 */
	public function event_form_deleted( $form_id ) {
		$form       = GFAPI::get_form( $form_id );
		$alert_code = 5702;

		$variables = array(
			'EventType' => 'deleted',
			'form_name' => sanitize_text_field( $form['title'] ),
			'form_id'   => $form['id'],
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );
	}

	public function event_form_duplicated( $form_id, $new_id ) {
		$original_form = GFAPI::get_form( $form_id );
		$new_form      = GFAPI::get_form( $new_id );
		$alert_code    = 5704;
		$editor_link   = esc_url(
			add_query_arg(
				array(
					'id' => $new_form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'          => 'duplicated',
			'original_form_name' => sanitize_text_field( $original_form['title'] ),
			'new_form_name'      => sanitize_text_field( $new_form['title'] ),
			'original_form_id'   => sanitize_text_field( $original_form['id'] ),
			'new_form_id'        => sanitize_text_field( $new_form['title'] ),
			'EditorLinkForm'     => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );
	}

	public function event_form_meta_updated( $form_meta, $form_id, $meta_name ) {

		error_log( print_r( $form_id, true ) );

		if ( isset( $this->_old_form ) ) {
			$form = GFAPI::get_form( $form_id );
			// Compare the 2 arrays and create array of changed.
			$compare_changed_items = array_diff_assoc(
				array_map( 'serialize', $form ),
				array_map( 'serialize', $this->_old_form )
			);
			$changed_data          = array_map( 'unserialize', $compare_changed_items );

			foreach ( $changed_data as $changed_setting => $value ) {

				// error_log( print_r( $changed_setting, true ) );
				// error_log( print_r( $value, true ) );

				if ( 'is_active' === $changed_setting || 'is_trash' === $changed_setting || 'date_created' === $changed_setting || 'confirmations' === $changed_setting || 'notifications' === $changed_setting ) {
						continue;
				}

				$alert_code  = 5703;
				$editor_link = esc_url(
					add_query_arg(
						array(
							'id' => $form_id,
						),
						admin_url( 'admin.php?page=gf_edit_forms' )
					)
				);

				$variables = array(
					'EventType'      => 'modified',
					'setting_name'   => sanitize_text_field( str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $changed_setting ) ) ) ),
					'setting_value'  => sanitize_text_field( str_replace( '_', '', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $value ) ) ) ),
					'form_name'      => sanitize_text_field( $form['title'] ),
					'form_id'        => $form_id,
					'EditorLinkForm' => $editor_link,
				);

				$this->plugin->alerts->Trigger( $alert_code, $variables );
			}
		}

	}

	public function event_form_confirmation_saved( $confirmation, $form ) {

		$is_an_update = false;

		// Handle modified confirmations
		if ( isset( $this->_old_form['confirmations'] ) ) {
			foreach ( $this->_old_form['confirmations'] as $old_confirmation ) {
				$id_to_lookup = $old_confirmation['id'];
				error_log( print_r( 'IDS', true ) );
				error_log( print_r( $id_to_lookup, true ) );
				error_log( print_r( $confirmation['id'], true ) );
				// Check if confirmation is found in old form, if so, we know its a modification.
				if ( $id_to_lookup == $confirmation['id'] ) {
					error_log( print_r( 'found', true ) );
					$is_an_update = true;

				}
			}

			if ( $is_an_update ) {
				$alert_code  = 5705;
				$editor_link = esc_url(
					add_query_arg(
						array(
							'id' => $form['id'],
						),
						admin_url( 'admin.php?page=gf_edit_forms' )
					)
				);

				$variables = array(
					'EventType'            => 'modified',
					'form_name'            => sanitize_text_field( $form['title'] ),
					'form_id'              => $form['id'],
					'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
					'confirmation_type'    => $confirmation['type'],
					'confirmation_message' => $confirmation['message'],
					'EditorLinkForm'       => $editor_link,
				);

				$this->plugin->alerts->Trigger( $alert_code, $variables );
			} else {
				$alert_code  = 5705;
				$editor_link = esc_url(
					add_query_arg(
						array(
							'id' => $form['id'],
						),
						admin_url( 'admin.php?page=gf_edit_forms' )
					)
				);

				$variables = array(
					'EventType'            => 'created',
					'form_name'            => sanitize_text_field( $form['title'] ),
					'form_id'              => $form['id'],
					'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
					'confirmation_type'    => $confirmation['type'],
					'confirmation_message' => $confirmation['message'],
					'EditorLinkForm'       => $editor_link,
				);

				$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'has_event_already_triggered' ) );
			}
		}

		return $confirmation;
	}

	public function event_form_confirmation_deleted( $confirmation, $form ) {

		$alert_code  = 5705;
		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'            => 'deleted',
			'form_name'            => sanitize_text_field( $form['title'] ),
			'form_id'              => $form['id'],
			'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
			'confirmation_type'    => $confirmation['type'],
			'EditorLinkForm'       => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );

		return $confirmation;
	}

	public function event_form_notification_saved( $notification, $form, $is_new_notification ) {

		if ( $is_new_notification ) {
			$alert_code  = 5706;
			$editor_link = esc_url(
				add_query_arg(
					array(
						'id' => $form['id'],
					),
					admin_url( 'admin.php?page=gf_edit_forms' )
				)
			);

			$variables = array(
				'EventType'            => 'created',
				'form_name'            => sanitize_text_field( $form['title'] ),
				'form_id'              => $form['id'],
				'notification_name'    => sanitize_text_field( $notification['name'] ),
				'EditorLinkForm'       => $editor_link,
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		} else {
			$alert_code  = 5706;
			$editor_link = esc_url(
				add_query_arg(
					array(
						'id' => $form['id'],
					),
					admin_url( 'admin.php?page=gf_edit_forms' )
				)
			);

			$variables = array(
				'EventType'            => 'modified',
				'form_name'            => sanitize_text_field( $form['title'] ),
				'form_id'              => $form['id'],
				'notification_name'    => sanitize_text_field( $notification['name'] ),
				'EditorLinkForm'       => $editor_link,
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

	}

	/**
	 * Method: This function make sures that alert 5500
	 * has not been triggered before triggering.
	 *
	 * @param  WSAL_AlertManager $manager - WSAL Alert Manager.
	 * @return bool
	 */
	public function has_event_already_triggered( WSAL_AlertManager $manager ) {
		// if ( $manager->WillOrHasTriggered( 5705 ) ) {
		// 	return false;
		// }
		return true;
	}
}
