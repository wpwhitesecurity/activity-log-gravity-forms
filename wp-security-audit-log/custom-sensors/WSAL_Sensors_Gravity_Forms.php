<?php // phpcs:disable WordPress.Files.FileName.NotHyphenatedLowercase

/**
 * Custom Sensors for Gravity Forms plugin.
 *
 * Class file for alert manager.
 *
 * @since   1.0.0
 * @package wsal
 * @subpackage wsal-gravity-forms
 */
class WSAL_Sensors_Gravity_Forms extends WSAL_AbstractSensor {

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
		if ( is_user_logged_in() ) {
			add_action( 'gform_form_post_get_meta', array( $this, 'get_before_post_edit_data' ) );

			// Forms.
			add_action( 'gform_after_save_form', array( $this, 'event_form_saved' ), 10, 2 );
			add_action( 'gform_post_form_trashed', array( $this, 'event_form_trashed' ), 10, 1 );
			add_action( 'gform_before_delete_form', array( $this, 'event_form_deleted' ) );
			add_action( 'gform_post_form_duplicated', array( $this, 'event_form_duplicated' ), 10, 2 );
			add_action( 'gform_post_update_form_meta', array( $this, 'event_form_meta_updated' ), 10, 3 );
			add_action( 'gform_forms_post_import', array( $this, 'event_forms_imported' ), 10, 1 );

			// Confirmations.
			add_action( 'gform_pre_confirmation_save', array( $this, 'event_form_confirmation_saved' ), 10, 3 );
			add_action( 'gform_pre_confirmation_deleted', array( $this, 'event_form_confirmation_deleted' ), 10, 2 );

			// Notifications.
			add_action( 'gform_pre_notification_deleted', array( $this, 'event_form_notification_deleted' ), 10, 2 );
			add_action( 'gform_pre_notification_activated', array( $this, 'event_form_notification_activated' ), 10, 2 );
			add_action( 'gform_pre_notification_deactivated', array( $this, 'event_form_notification_deactivated' ), 10, 2 );

			// Entries.
			add_action( 'gform_delete_entry', array( $this, 'event_form_entry_deleted' ), 10, 1 );
			add_action( 'gform_update_status', array( $this, 'event_form_entry_trashed' ), 10, 3 );
			add_action( 'gform_post_note_added', array( $this, 'event_form_entry_note_added' ), 10, 6 );
			add_action( 'gform_pre_note_deleted', array( $this, 'event_form_entry_note_deleted' ), 10, 2 );
			add_action( 'gform_post_update_entry_property', array( $this, 'event_form_entry_updated' ), 10, 4 );

			// Global Settings.
			add_action( 'updated_option', array( $this, 'event_settings_updated' ), 10, 3 );
			add_action( 'gform_post_export_entries', array( $this, 'event_process_export' ), 10, 5 );
			add_action( 'gform_form_export_filename', array( $this, 'event_process_export_forms' ), 10, 2 );
		}

		// Form submitted.
		add_action( 'gform_after_submission', array( $this, 'event_form_submitted' ), 10, 2 );
	}

	/**
	 * Trigger event when an entry is modified.
	 *
	 * @param int    $entry_id - Entry ID.
	 * @param string $property_name - Value being updated.
	 * @param string $property_value - New value.
	 * @param string $previous_value - Old value.
	 * @return void
	 */
	public function event_form_entry_updated( $entry_id, $property_name, $property_value, $previous_value ) {

		if ( isset( $_POST['gforms_save_entry'] ) || isset( $_POST['name'] ) ) {
			$item_being_updated = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : false;
			$entry              = GFAPI::get_entry( $entry_id );
			$form               = GFAPI::get_form( $entry['form_id'] );
			$entry_name         = $this->determine_entry_name( $entry );

			$editor_link = esc_url(
				add_query_arg(
					array(
						'view' => 'entry',
						'id'   => $entry['form_id'],
						'lid'  => $entry_id,
					),
					admin_url( 'admin.php?page=gf_entries' )
				)
			);

			if ( 'is_starred' === $item_being_updated ) {
				// Starred.
				if ( $previous_value !== $property_value && $property_value ) {
					$variables = array(
						'EventType'   => 'starred',
						'entry_title' => $entry_name,
						'form_name'   => $form['title'],
						'form_id'     => $form['id'],
						'EntryLink'   => $editor_link,
					);
					$this->trigger_event( 5710, $variables );
				}

				// Unstarred.
				if ( $previous_value !== $property_value && ! $property_value ) {
					$variables = array(
						'EventType'   => 'unstarred',
						'entry_title' => $entry_name,
						'form_name'   => $form['title'],
						'form_id'     => $form['id'],
						'EntryLink'   => $editor_link,
					);
					$this->trigger_event( 5710, $variables );
				}
			} elseif ( 'is_read' === $item_being_updated ) {
				// Starred.
				if ( $property_value ) {
					$variables = array(
						'EventType'   => 'read',
						'entry_title' => $entry_name,
						'form_name'   => $form['title'],
						'form_id'     => $form['id'],
						'EntryLink'   => $editor_link,
					);
					$this->trigger_event( 5711, $variables );
				}

				// Unstarred.
				if ( ! $property_value ) {
					$variables = array(
						'EventType'   => 'unread',
						'entry_title' => $entry_name,
						'form_name'   => $form['title'],
						'form_id'     => $form['id'],
						'EntryLink'   => $editor_link,
					);
					$this->trigger_event( 5711, $variables );
				}
			} else {
				$variables = array(
					'entry_name' => sanitize_text_field( $entry_name ),
					'form_name'  => sanitize_text_field( $form['title'] ),
					'form_id'    => $form['id'],
					'EntryLink'  => $editor_link,
				);

				$this->trigger_event( 5717, $variables );
			}
		}
	}

	/**
	 * Handles forms being imported.
	 *
	 * @param  array $forms - new form data.
	 * @return void
	 */
	public function event_forms_imported( $forms ) {
		$wsal = WpSecurityAuditLog::GetInstance();
		if ( ! isset( $wsal->alerts ) ) {
			$wsal->alerts = new WSAL_AlertManager( $wsal );
		}

		foreach ( $forms as $form ) {
			$variables = array(
				'EventType' => 'imported',
				'form_name' => sanitize_text_field( $form['title'] ),
				'form_id'   => $form['id'],
			);

			if ( method_exists( $wsal->alerts, 'trigger_event' ) ) {
				$wsal->alerts->trigger_event( 5719, $variables );
			} else {
				$wsal->alerts->Trigger( 5719, $variables );
			}
		}
	}

	/**
	 * Handles triggering an event during export of fields
	 *
	 * @param array  $form       The Form object to get the entries from.
	 * @param string $start_date The start date for when the export of entries should take place.
	 * @param string $end_date   The end date for when the export of entries should stop.
	 * @param array  $fields     The specified fields where the entries should be exported from.
	 * @param string $export_id  A unique ID for the export.
	 */
	public function event_process_export( $form, $start_date, $end_date, $fields, $export_id ) {

		if ( isset( $_POST['export_form'] ) && check_admin_referer( 'rg_start_export', 'rg_start_export_nonce' ) ) {

			$form = GFAPI::get_form( sanitize_text_field( wp_unslash( $_POST['export_form'] ) ) );

			$variables = array(
				'form_name' => sanitize_text_field( $form['title'] ),
				'form_id'   => sanitize_text_field( wp_unslash( $_POST['export_form'] ) ),
				'start'     => ( ! empty( $start_date ) ) ? sanitize_text_field( wp_unslash( $start_date ) ) : esc_html__( 'Not supplied', 'wsal-gravityforms' ),
				'end'       => ( ! empty( $end_date ) ) ? sanitize_text_field( wp_unslash( $end_date ) ) : esc_html__( 'Not supplied', 'wsal-gravityforms' ),
			);

			$this->trigger_event( 5718, $variables );
		}
	}

	/**
	 * Report event when forms are exported.
	 *
	 * @param string $filename - Export filename.
	 * @param array  $form_ids - Ids being exported.
	 * @return string $filename - Orignal filename.
	 */
	public function event_process_export_forms( $filename, $form_ids ) {
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

			if ( method_exists( $wsal->alerts, 'trigger_event' ) ) {
				$wsal->alerts->trigger_event( $alert_code, $variables );
			} else {
				$wsal->alerts->Trigger( $alert_code, $variables );
			}
		}

		return $filename;
	}


	/**
	 * Gather form data during an edit so we know what the old form was.
	 *
	 * @param  object $form - Form data.
	 *
	 * @return object $form - Form data, passed back so things can continue as normal.
	 */
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
	 * @param  array   $form   New form data.
	 * @param  boolean $is_new Is a new form or an update.
	 *
	 * @return void
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
			$variables = array(
				'EventType'      => 'created',
				'form_name'      => sanitize_text_field( $form['title'] ),
				'form_id'        => $form['id'],
				'EditorLinkForm' => $editor_link,
			);

			$this->trigger_event( 5700, $variables );
		} else {
			// Otherwise, the form has been edited, so lets see whats going on.
			?>
			<?php
		}

	}

	/**
	 * Trigger event when a form is sent to trash.
	 *
	 * @param  int $form_id Form ID.
	 *
	 * @return int $form_id Form ID.
	 */
	public function event_form_trashed( $form_id ) {
		$form = GFAPI::get_form( $form_id );

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

		$this->trigger_event( 5701, $variables );

		return $form_id;
	}

	/**
	 * Trigger an event when a form is deleted.
	 *
	 * @param  int $form_id Form ID.
	 * @return void
	 */
	public function event_form_deleted( $form_id ) {
		$form = GFAPI::get_form( $form_id );

		$variables = array(
			'EventType' => 'deleted',
			'form_name' => sanitize_text_field( $form['title'] ),
			'form_id'   => $form['id'],
		);

		$this->trigger_event( 5702, $variables );
	}

	/**
	 * Trigger an event when a form is duplicated.
	 *
	 * @param  int $form_id Form ID.
	 * @param  int $new_id New form ID.
	 * @return void
	 */
	public function event_form_duplicated( $form_id, $new_id ) {
		$original_form = GFAPI::get_form( $form_id );
		$new_form      = GFAPI::get_form( $new_id );
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
			'new_form_id'        => sanitize_text_field( $new_form['id'] ),
			'EditorLinkForm'     => $editor_link,
		);

		$this->trigger_event( 5704, $variables );
	}

	/**
	 * Trigger an event when a form us updated.
	 *
	 * @param array  $form_meta - Form metadata.
	 * @param int    $form_id - Form id.
	 * @param string $meta_name - Changes item name.
	 * @return void
	 */
	public function event_form_meta_updated( $form_meta, $form_id, $meta_name ) {

		if ( isset( $this->_old_form ) ) {
			$form = (array) GFAPI::get_form( $form_id );
			// Compare the 2 arrays and create array of changed.
			$compare_changed_items = array_diff_assoc(
				array_map( 'serialize', $form ),
				array_map( 'serialize', $this->_old_form )
			);
			$changed_data          = array_map( 'unserialize', $compare_changed_items );

			foreach ( $changed_data as $changed_setting => $value ) {

				if ( 'confirmations' === $changed_setting ) {

					$old_confirmations = $this->_old_form['confirmations'];

					$compare_changed_items = array_diff_assoc(
						array_map( 'serialize', $value ),
						array_map( 'serialize', $old_confirmations )
					);
					$changed_items         = array_map( 'unserialize', $compare_changed_items );

					$alert_code  = 5708;
					$editor_link = esc_url(
						add_query_arg(
							array(
								'id' => $form['id'],
							),
							admin_url( 'admin.php?page=gf_edit_forms' )
						)
					);

					foreach ( $changed_items as $confirmation ) {

						if ( ! $this->was_triggered_recently( 5705 ) ) {
							if ( empty( $confirmation['isActive'] ) ) {
								$active_state = 'deactivated';
							} else {
								$active_state = 'activated';
							}

							$variables = array(
								'EventType'            => $active_state,
								'form_name'            => sanitize_text_field( $form['title'] ),
								'form_id'              => $form['id'],
								'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
								'confirmation_type'    => $confirmation['type'],
								'confirmation_message' => $confirmation['message'],
								'EditorLinkForm'       => $editor_link,
							);

							$this->trigger_event_if( $alert_code, $variables, array( $this, 'check_if_new_confirmation' ) );
						}
					}
				}

				// Handle form notifications.
				if ( 'notifications' === $changed_setting ) {
					$old_fields = $this->_old_form[ $changed_setting ];
					$alert_code = 5706;

					$event_type       = false;
					$new_fields_count = count( json_decode( $form_meta, true ) );
					$old_fields_count = count( $old_fields );

					if ( $new_fields_count > $old_fields_count ) {
						$event_type = 'created';
					}

					if ( $new_fields_count === $old_fields_count ) {
						$event_type = 'modified';
					}

					$notification_id = ( 'created' !== $event_type ) ? rgpost( 'gform_notification_id' ) : key( array_slice( $value, -1, 1, true ) );

					$editor_link = esc_url(
						add_query_arg(
							array(
								'id'      => $form['id'],
								'nid'     => $notification_id,
								'view'    => 'settings',
								'subview' => 'notification',
							),
							admin_url( 'admin.php?page=gf_edit_forms' )
						)
					);

					if ( 'created' === $event_type ) {
						$notification = $value[ $notification_id ];
					} else {
						// If there is no such notification (notificationId is the key) then notification has been deleted,
						// that event wont be triggered and array with empy name is just to fulfill the logic later on.
						$notification = ( isset( $value[ $notification_id ] ) ) ? $value[ $notification_id ] : array( 'name' => '' );
					}

					if ( isset( $_REQUEST['action'] ) && 'duplicate' === $_REQUEST['action'] && count( $value ) > count( $old_fields ) ) {
						$event_type = 'duplicated';
					}

					$title = $notification['name'];
					if ( '' === trim( $title ) ) {
						$title = esc_html__( '-- UNTITLED --', 'wsal-gravityforms' );
					}

					$variables = array(
						'EventType'         => $event_type,
						'form_name'         => sanitize_text_field( $form['title'] ),
						'form_id'           => $form['id'],
						'notification_name' => sanitize_text_field( $title ),
						'EditorLinkForm'    => $editor_link,
					);

					if ( $event_type ) {
						$this->trigger_event_if(
							$alert_code,
							$variables,
							/**
							 * The WSAL alert manager.
							 *
							 * @param WSAL_AlertManager $manager
							 * @return bool
							 */
							function ( $manager ) {
								if ( method_exists( $manager, 'will_or_has_triggered' ) ) {
									// don't fire if there's already an event 5707.
									return ! $manager->will_or_has_triggered( 5707 );
								} else {
									// don't fire if there's already an event 5707.
									return ! $manager->WillOrHasTriggered( 5707 );
								}
							}
						);
					}
				}

				if ( 'fields' === $changed_setting ) {

					$notification_id   = rgpost( 'gform_notification_id' );
					$notification_data = array();
					if ( $notification_id && '' !== trim( $notification_id ) && isset( $form['notifications'][ $notification_id ] ) ) {
						$notification_data = $form['notifications'][ $notification_id ];
					}

					$old_fields     = $this->_old_form['fields'];
					$current_fields = $value;

					$compare_added_items = array_diff(
						array_map( 'serialize', $current_fields ),
						array_map( 'serialize', $old_fields )
					);
					$added_items         = array_map( 'unserialize', $compare_added_items );

					$compare_removed_items = array_diff(
						array_map( 'serialize', $old_fields ),
						array_map( 'serialize', $current_fields )
					);
					$removed_items         = array_map( 'unserialize', $compare_removed_items );

					$compare_changed_items = array_diff_assoc(
						array_map( 'serialize', $current_fields ),
						array_map( 'serialize', $old_fields )
					);
					$changed_items         = array_map( 'unserialize', $compare_changed_items );

					// Handle added items.
					if ( ! empty( $added_items ) && ( count( $current_fields ) > count( $old_fields ) ) ) {
						foreach ( $added_items as $item ) {

							// Make sure this field is not a modified field.
							$ok_to_alert = true;
							foreach ( $old_fields as $old => $value ) {
								if ( $value->id === $item->id ) {
									$ok_to_alert = false;
								}
							}

							if ( $ok_to_alert ) {
								$editor_link = esc_url(
									add_query_arg(
										array(
											'id' => $form_id,
										),
										admin_url( 'admin.php?page=gf_edit_forms' )
									)
								);

								$variables = array(
									'EventType'      => 'added',
									'field_name'     => $item->label,
									'field_type'     => $item->type,
									'form_name'      => sanitize_text_field( $form['title'] ),
									'form_id'        => $form_id,
									'EditorLinkForm' => $editor_link,
								);

								$this->trigger_event_if( 5715, $variables, array( $this, 'must_not_duplicated_form' ) );
							}
						}
					}

					if ( ! empty( $changed_items ) && ( count( $old_fields ) === count( $current_fields ) ) ) {
						foreach ( $changed_items as $item ) {
							$ok_to_alert = true;

							foreach ( $removed_items as $removed => $value ) {
								if ( $item->id === $value->id ) {
									unset( $removed_items[ $removed ] );
								}
							}

							if ( $ok_to_alert ) {
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
									'field_name'     => $item->label,
									'field_type'     => $item->type,
									'form_name'      => sanitize_text_field( $form['title'] ),
									'form_id'        => $form_id,
									'EditorLinkForm' => $editor_link,
								);

								$this->trigger_event_if( 5715, $variables, array( $this, 'must_not_duplicated_form' ) );
							}
						}
					}

					if ( ! empty( $removed_items ) && ( count( $old_fields ) > count( $current_fields ) ) ) {

						foreach ( $removed_items as $item ) {
							// Make sure this field is not a modified field.
							$ok_to_alert = true;
							foreach ( $current_fields as $old => $value ) {
								if ( $value->id === $item->id && $value->label === $item->label ) {
									$ok_to_alert = false;
								}
							}

							if ( $ok_to_alert ) {
								$editor_link = esc_url(
									add_query_arg(
										array(
											'id' => $form_id,
										),
										admin_url( 'admin.php?page=gf_edit_forms' )
									)
								);

									$variables = array(
										'EventType'      => 'removed',
										'field_name'     => $item->label,
										'field_type'     => $item->type,
										'form_name'      => sanitize_text_field( $form['title'] ),
										'form_id'        => $form_id,
										'EditorLinkForm' => $editor_link,
									);

									$this->trigger_event( 5715, $variables );
							}
						}
					}
				}

				// Handle personal data settings.
				if ( 'personalData' === $changed_setting ) {
					$editor_link = esc_url(
						add_query_arg(
							array(
								'id' => $form_id,
							),
							admin_url( 'admin.php?page=gf_edit_forms' )
						)
					);

					if ( isset( $this->_old_form[ $changed_setting ] ) ) {
						$old_fields = $this->_old_form[ $changed_setting ];
					} else {
						$old_fields = array();
					}

					$compare_changed_items = array_diff_assoc(
						array_map( 'serialize', $value ),
						array_map( 'serialize', $old_fields )
					);
					$changed_items         = array_map( 'unserialize', $compare_changed_items );

					foreach ( $changed_items as $name => $value ) {

						if ( 'preventIP' === $name ) {
							$name = 'Prevent the storage of IP addresses';
						} elseif ( 'retention' === $name ) {
							$name  = 'Retention policy';
							$value = $value['policy'];
						} elseif ( 'exportingAndErasing' === $name ) {
							$name  = 'Enable integration for exporting and erasing personal data';
							$value = $value['enabled'];
						}

						$event_type = 'modified';
						$old_value  = $this->_old_form[ $changed_setting ];

						if ( is_array( $value ) ) {
							$value = $this->recursive_implode( $value, ' | ', true, false );
						}

						if ( is_array( $old_value ) ) {
							$old_value = $this->recursive_implode( $old_value, ' | ', true, false );
						}

						// Give the value a more useful label.
						if ( empty( $value ) || 0 === $value ) {
							$value      = 'Disabled';
							$event_type = 'disabled';
						} elseif ( 1 === $value ) {
							$value      = 'Enabled';
							$event_type = 'enabled';
						} elseif ( 'retain' === $value ) {
							$value = 'Retain entries indefinitely';
						} elseif ( 'trash' === $value ) {
							$value = 'Trash entries automatically';
						} elseif ( 'delete' === $value ) {
							$value = 'Delete entries permanently automatically';
						}

						if ( ! $this->was_triggered_recently( 5703 ) ) {
							$variables = array(
								'EventType'         => $event_type,
								'setting_name'      => sanitize_text_field( str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $name ) ) ) ),
								'old_setting_value' => ( isset( $this->_old_form[ $changed_setting ] ) && $this->_old_form[ $changed_setting ] ) ? $old_value : 'N/A',
								'setting_value'     => sanitize_text_field( $value ),
								'form_name'         => sanitize_text_field( $form['title'] ),
								'form_id'           => $form_id,
								'EditorLinkForm'    => $editor_link,
							);
							$this->trigger_event( 5703, $variables );
						}
					}
				} else {

					// Ensure we dont process fields we dont want at this stage.
					if ( 'is_active' === $changed_setting || 'is_trash' === $changed_setting || 'date_created' === $changed_setting || 'confirmations' === $changed_setting || 'nextFieldId' === $changed_setting || 'notifications' === $changed_setting || 'fields' === $changed_setting ) {
							continue;
					}

					// Handle everything else.
					if ( isset( $this->_old_form[ $changed_setting ] ) ) {
						$editor_link = esc_url(
							add_query_arg(
								array(
									'id' => $form_id,
								),
								admin_url( 'admin.php?page=gf_edit_forms' )
							)
						);

						$old_value        = $this->_old_form[ $changed_setting ];
						$value_unmodified = $value;

						if ( is_array( $value ) ) {
							$value = $this->recursive_implode( $value, ' | ', true, false );
						}

						if ( is_array( $old_value ) ) {
							$old_value = $this->recursive_implode( $old_value, ' | ', true, false );
						}

						$event_type = 'modified';

						switch ( $changed_setting ) {
							case 'title':
								$setting_name = esc_html__( 'Form title', 'wsal-gravityforms' );
								break;

							case 'cssClass':
								$setting_name = esc_html__( 'CSS Class', 'wsal-gravityforms' );
								break;

							case 'description':
								$setting_name = esc_html__( 'Form title', 'wsal-gravityforms' );
								break;

							case 'enableAnimation':
							case 'enableHoneypot':
							case 'requireLogin':
							case 'scheduleForm':
								$setting_name = str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $changed_setting ) ) );
								$event_type   = ( 1 === $value ) ? 'enabled' : 'disabled';
								// Tidy up bools.
								if ( ! $old_value && 1 === $value || 1 === $old_value && ! $value ) {
									$old_value = esc_html__( 'Disabled', 'wsal-gravityforms' );
									$value     = esc_html__( 'Enabled', 'wsal-gravityforms' );
								}
								break;

							case 'save':
								$setting_name = str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $changed_setting ) ) );
								$event_type   = ( isset( $value_unmodified['enabled'] ) && $value_unmodified['enabled'] ) ? 'enabled' : 'disabled';
								break;

							default:
								$setting_name = sanitize_text_field( str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $changed_setting ) ) ) );
						}

						$variables = array(
							'EventType'         => $event_type,
							'setting_name'      => $setting_name,
							'old_setting_value' => ( $old_value ) ? $old_value : 'N/A',
							'setting_value'     => ( $value ) ? $value : 'N/A',
							'form_name'         => sanitize_text_field( $form['title'] ),
							'form_id'           => $form_id,
							'EditorLinkForm'    => $editor_link,
						);
						$this->trigger_event_if( 5703, $variables, array( $this, 'must_not_duplicated_form' ) );
					}
				}
			}
		}

	}

	/**
	 * Check if another relevant event has triggered.
	 *
	 * @param WSAL_AlertManager $manager - The WSAL alert manager.
	 * @return bool
	 */
	public function must_not_duplicated_form( WSAL_AlertManager $manager ) {
		if ( method_exists( $manager, 'will_or_has_triggered' ) ) {
			if ( $manager->will_or_has_triggered( 5704 ) ) {
				return false;
			}
			if ( $manager->will_or_has_triggered( 5705 ) ) {
				return false;
			}
			if ( $manager->will_or_has_triggered( 5706 ) ) {
				return false;
			}
		} else {
			if ( $manager->WillOrHasTriggered( 5704 ) ) {
				return false;
			}
			if ( $manager->WillOrHasTriggered( 5705 ) ) {
				return false;
			}
			if ( $manager->WillOrHasTriggered( 5706 ) ) {
				return false;
			}		
		}
		return true;
	}

	/**
	 * Check if 5705 specifical has been fired.
	 *
	 * @param WSAL_AlertManager $manager - The WSAL alert manager.
	 * @return bool
	 */
	public function check_if_new_confirmation( WSAL_AlertManager $manager ) {
		if ( method_exists( $manager, 'will_or_has_triggered' ) ) {
			if ( $manager->will_or_has_triggered( 5705 ) ) {
				return false;
			}
		} else {
			if ( $manager->WillOrHasTriggered( 5705 ) ) {
				return false;
			}	
		}
		return true;
	}

	/**
	 * Trigger an event when a confirmation has been saved.
	 *
	 * @param array $confirmation - Confirmaiton data.
	 * @param array $form - Form data.
	 * @return array $confirmation - Confirmaiton data.
	 */
	public function event_form_confirmation_saved( $confirmation, $form ) {

		$is_an_update = false;

		// Handle modified confirmations.
		if ( isset( $this->_old_form['confirmations'] ) ) {
			foreach ( $this->_old_form['confirmations'] as $old_confirmation ) {
				$id_to_lookup = $old_confirmation['id'];
				// Check if confirmation is found in old form, if so, we know its a modification.
				if ( $id_to_lookup === $confirmation['id'] ) {
					$is_an_update = true;
				}
			}

			if ( $is_an_update ) {
				$editor_link = esc_url(
					add_query_arg(
						array(
							'id' => $form['id'],
						),
						admin_url( 'admin.php?page=gf_edit_forms' )
					)
				);

				$message = $confirmation['message'];
				if ( '' === trim( $message ) ) {
					$message = esc_html__( '-- NO MESSAGE TEXT --', 'wsal-gravityforms' );
				}

				$variables = array(
					'EventType'            => 'modified',
					'form_name'            => sanitize_text_field( $form['title'] ),
					'form_id'              => $form['id'],
					'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
					'confirmation_type'    => $confirmation['type'],
					'confirmation_message' => $message,
					'EditorLinkForm'       => $editor_link,
				);

				$this->trigger_event( 5705, $variables );
			} else {
				$editor_link = esc_url(
					add_query_arg(
						array(
							'id' => $form['id'],
						),
						admin_url( 'admin.php?page=gf_edit_forms' )
					)
				);

				$message = $confirmation['message'];
				if ( '' === trim( $message ) ) {
					$message = esc_html__( '-- NO MESSAGE TEXT --', 'wsal-gravityforms' );
				}

				$variables = array(
					'EventType'            => 'created',
					'form_name'            => sanitize_text_field( $form['title'] ),
					'form_id'              => $form['id'],
					'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
					'confirmation_type'    => $confirmation['type'],
					'confirmation_message' => $message,
					'EditorLinkForm'       => $editor_link,
				);

				$this->trigger_event_if( 5705, $variables );
			}
		}

		return $confirmation;
	}

	/**
	 * Trigger event if confirmation is deleted.
	 *
	 * @param array $confirmation - Confirmation data.
	 * @param array $form - Form data.
	 * @return array $confirmation - Confirmation data.
	 */
	public function event_form_confirmation_deleted( $confirmation, $form ) {

		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$message = $confirmation['message'];
		if ( '' === trim( $message ) ) {
			$message = esc_html__( '-- NO MESSAGE TEXT --', 'wsal-gravityforms' );
		}

		$variables = array(
			'EventType'            => 'deleted',
			'form_name'            => sanitize_text_field( $form['title'] ),
			'form_id'              => $form['id'],
			'confirmation_name'    => sanitize_text_field( $confirmation['name'] ),
			'confirmation_type'    => $confirmation['type'],
			'confirmation_message' => $message,
			'EditorLinkForm'       => $editor_link,
		);

		$this->trigger_event( 5705, $variables );

		return $confirmation;
	}

	/**
	 * Trigger event if notification is deleted.
	 *
	 * @param array $notification - Notification data.
	 * @param array $form - Form data.
	 * @return array $notification - Notification data.
	 */
	public function event_form_notification_deleted( $notification, $form ) {
		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$title = $notification['name'];
		if ( '' === trim( $title ) ) {
			$title = esc_html__( '-- UNTITLED --', 'wsal-gravityforms' );
		}

		$variables = array(
			'EventType'         => 'deleted',
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'notification_name' => sanitize_text_field( $title ),
			'EditorLinkForm'    => $editor_link,
		);

		$this->trigger_event( 5706, $variables );

		return $notification;
	}

	/**
	 * Process and triggers the event loging
	 *
	 * @param array        $notification - Notification data.
	 * @param GFFormsModel $form - Form data.
	 * @param string       $event_type - Current event type.
	 *
	 * @return array $notification - Notification data.
	 */
	private function formActivationDeactivationEventLog( $notification, $form, string $event_type ) {
		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'         => $event_type,
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'notification_name' => sanitize_text_field( $notification['name'] ),
			'EditorLinkForm'    => $editor_link,
		);

		$this->trigger_event( 5707, $variables, true );

		return $notification;
	}

	/**
	 * A notificationw as activated
	 *
	 * @param array        $notification - Notification data.
	 * @param GFFormsModel $form - Form data.
	 *
	 * @return array $notification - Notification data.
	 */
	public function event_form_notification_activated( $notification, $form ) {
		return $this->formActivationDeactivationEventLog( $notification, $form, 'activated' );
	}

	/**
	 * A notificationw as deactivated
	 *
	 * @param array        $notification - Notification data.
	 * @param GFFormsModel $form - Form data.
	 *
	 * @return array $notification - Notification data.
	 */
	public function event_form_notification_deactivated( $notification, $form ) {
		return $this->formActivationDeactivationEventLog( $notification, $form, 'deactivated' );
	}

	/**
	 * Trigger event when an entry is deleted.
	 *
	 * @param  int $entry_id - Entry ID.
	 * @return void
	 */
	public function event_form_entry_deleted( $entry_id ) {
		$entry      = GFAPI::get_entry( $entry_id );
		$form       = GFAPI::get_form( $entry['form_id'] );
		$entry_name = $this->determine_entry_name( $entry );

		$variables = array(
			'EventType'   => 'deleted',
			'entry_title' => $entry_name,
			'form_name'   => $form['title'],
			'form_id'     => $form['id'],
		);
		$this->trigger_event( 5713, $variables );

	}

	/**
	 * Trigger an event when an entry is trashed
	 *
	 * @param int    $entry_id - Entry ID.
	 * @param string $property_value - New value.
	 * @param string $previous_value - Old value.
	 * @return void
	 */
	public function event_form_entry_trashed( $entry_id, $property_value, $previous_value ) {
		if ( $previous_value !== $property_value && 'trash' === $property_value ) {
			$entry      = GFAPI::get_entry( $entry_id );
			$form       = GFAPI::get_form( $entry['form_id'] );
			$entry_name = $this->determine_entry_name( $entry );

			$editor_link = esc_url(
				add_query_arg(
					array(
						'view' => 'entry',
						'id'   => $entry['form_id'],
						'lid'  => $entry_id,
					),
					admin_url( 'admin.php?page=gf_entries' )
				)
			);

			$variables = array(
				'EventType'   => 'deleted',
				'event_desc'  => esc_html__( 'moved to trash', 'wsal-gravity-forms' ),
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->trigger_event( 5712, $variables );
		}

		if ( $previous_value !== $property_value && 'active' === $property_value ) {
			$entry      = GFAPI::get_entry( $entry_id );
			$form       = GFAPI::get_form( $entry['form_id'] );
			$entry_name = $this->determine_entry_name( $entry );

			$editor_link = esc_url(
				add_query_arg(
					array(
						'view' => 'entry',
						'id'   => $entry['form_id'],
						'lid'  => $entry_id,
					),
					admin_url( 'admin.php?page=gf_entries' )
				)
			);

			$variables = array(
				'EventType'   => 'restored',
				'event_desc'  => esc_html__( 'restored', 'wsal-gravity-forms' ),
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->trigger_event( 5712, $variables );
		}

	}

	/**
	 * Trigger an event when a note is added to an entry.
	 *
	 * @param int    $insert_id - Note ID.
	 * @param int    $entry_id - Entry ID.
	 * @param int    $user_id - User ID.
	 * @param string $user_name - User name.
	 * @param string $note - Note content.
	 * @param string $note_type - Note type.
	 * @return void
	 */
	public function event_form_entry_note_added( $insert_id, $entry_id, $user_id, $user_name, $note, $note_type ) {

		if ( 'user' === $note_type ) {
			$entry      = GFAPI::get_entry( $entry_id );
			$form       = GFAPI::get_form( $entry['form_id'] );
			$entry_name = $this->determine_entry_name( $entry );

			$editor_link = esc_url(
				add_query_arg(
					array(
						'view' => 'entry',
						'id'   => $entry['form_id'],
						'lid'  => $entry_id,
					),
					admin_url( 'admin.php?page=gf_entries' )
				)
			);

			$variables = array(
				'EventType'   => 'added',
				'entry_note'  => $note,
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->trigger_event( 5714, $variables );
		}
	}

	/**
	 * Handle entry notes being deleted.
	 *
	 * @param int $note_id - Note ID.
	 * @param int $lead_id - Lead ID.
	 */
	public function event_form_entry_note_deleted( $note_id, $lead_id ) {
		$entry      = GFAPI::get_entry( $lead_id );
		$form       = GFAPI::get_form( $entry['form_id'] );
		$note       = GFAPI::get_note( $note_id );
		$entry_name = $this->determine_entry_name( $entry );

		$editor_link = esc_url(
			add_query_arg(
				array(
					'view' => 'entry',
					'id'   => $entry['form_id'],
					'lid'  => $lead_id,
				),
				admin_url( 'admin.php?page=gf_entries' )
			)
		);

		$variables = array(
			'EventType'   => 'deleted',
			'entry_note'  => $note->value,
			'entry_title' => $entry_name,
			'form_name'   => $form['title'],
			'form_id'     => $form['id'],
			'EntryLink'   => $editor_link,
		);
		$this->trigger_event( 5714, $variables );

	}

	/**
	 * Handle global settings changes.
	 *
	 * @param string $option_name - Option being changed.
	 * @param string $old_value   - Old value.
	 * @param string $value       - New value.
	 */
	public function event_settings_updated( $option_name, $old_value, $value ) {
		if ( ( strpos( $option_name, 'gform' ) !== false || strpos( $option_name, 'gravityforms' ) !== false ) && ( 'gform_version_info' !== $option_name ) || strpos( $option_name, 'gravityformsaddon' ) !== false ) {

			// Skip settings we dont want.
			if ( 'rg_gforms_key' === $option_name || 'rg_gforms_message' === $option_name || 'gform_sticky_admin_messages' === $option_name || 'gform_email_count' === $option_name || 'gform_installation_wizard_license_key' === $option_name || 'gform_pending_installation' === $option_name ) {
				return;
			}

			$event_type   = 'modified';
			$option_found = false;

			if ( 'rg_gforms_disable_css' === $option_name ) {
				$option_found = true;
				$option_name  = 'Output CSS';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'No' : 'Yes';
				$old_value    = ( 1 === $old_value ) ? 'No' : 'Yes';
			}

			if ( 'rg_gforms_enable_html5' === $option_name ) {
				$option_found = true;
				$option_name  = 'Output HTML5';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'Yes' : 'No';
				$old_value    = ( 1 === $old_value ) ? 'Yes' : 'No';
			}

			if ( 'gform_enable_noconflict' === $option_name ) {
				$option_found = true;
				$option_name  = 'No-Conflict Mode';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'On' : 'Off';
				$old_value    = ( 1 === $old_value ) ? 'On' : 'Off';
			}

			if ( 'rg_gforms_currency' === $option_name ) {
				$option_found = true;
				$option_name  = 'Currency';
			}

			if ( 'gform_enable_background_updates' === $option_name ) {
				$option_found = true;
				$option_name  = 'Background updates';
				$event_type   = ( $value ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'On' : 'Off';
				$old_value    = ( 1 === $old_value ) ? 'On' : 'Off';
			}

			if ( 'gform_enable_toolbar_menu' === $option_name ) {
				$option_found = true;
				$option_name  = 'Toolbar menu';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'On' : 'Off';
				$old_value    = ( 1 === $old_value ) ? 'On' : 'Off';
			}

			if ( 'gform_enable_logging' === $option_name ) {
				$option_found = true;
				$option_name  = 'Logging';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value ) ? 'On' : 'Off';
				$old_value    = ( 1 === $old_value ) ? 'On' : 'Off';
			}

			if ( 'rg_gforms_captcha_type' === $option_name ) {
				$option_found = true;
				$option_name  = 'Captcha type';
			}

			if ( 'gravityformsaddon_gravityformswebapi_settings' === $option_name ) {
				$option_found = true;
				$option_name  = 'Gravity Forms API Settings';
				$event_type   = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value        = ( 1 === $value['enabled'] ) ? 'On' : 'Off';
				$old_value    = ( 1 === $old_value['enabled'] ) ? 'On' : 'Off';
			}

			if ( 'rg_gforms_enable_akismet' === $option_name ) {
				$option_found = true;
				if ( ! isset( $old_value['enabled'] ) ) {
					return;
				}
				$option_name = 'Enable akisment';
				$event_type  = ( 1 === $value['enabled'] ) ? 'enabled' : 'disabled';
				$value       = ( isset( $value['enabled'] ) && 1 === $value['enabled'] ) ? 'On' : 'Off';
				$old_value   = ( isset( $old_value['enabled'] ) && 1 === $old_value['enabled'] ) ? 'On' : 'Off';
			}

			if ( $option_found ) {
				$alert_code = 5716;
				$variables  = array(
					'EventType'    => $event_type,
					'setting_name' => $option_name,
					'old_value'    => $old_value,
					'new_value'    => $value,
				);
				$this->trigger_event( $alert_code, $variables );
			}
		}
	}

	/**
	 * Handle form submission.
	 *
	 * @param string $entry - Entry data.
	 * @param string $form   - From data.
	 */
	public function event_form_submitted( $entry, $form ) {

		// Determine from address by validating entry items.
		$from_addresss = '';
		foreach ( $entry as $entry_item => $item ) {
			if ( filter_var( $item, FILTER_VALIDATE_EMAIL ) ) {
				$from_addresss = $item;
			}
		}

		if ( empty( $from_addresss ) ) {
			$from_addresss = esc_html__( 'Not provided', 'wsal-gravity-forms' );
		}

		$editor_link = esc_url(
			add_query_arg(
				array(
					'view' => 'entry',
					'id'   => $form['id'],
					'lid'  => $entry['id'],
				),
				admin_url( 'admin.php?page=gf_entries' )
			)
		);

		$variables = array(
			'EventType' => 'submitted',
			'form_name' => $form['title'],
			'form_id'   => $form['id'],
			'email'     => $from_addresss,
			'EntryLink' => $editor_link,
		);
		$this->trigger_event( 5709, $variables );
	}

	/**
	 * Recursively implodes an array with optional key inclusion
	 *
	 * Example of $include_keys output: key, value, key, value, key, value
	 *
	 * @access  public
	 * @param   array  $array         multi-dimensional array to recursively implode.
	 * @param   string $glue          Thing to stick the results together with.
	 * @param   bool   $include_keys  include keys before their values.
	 * @param   bool   $trim_all      trim ALL whitespace from string.
	 * @return  string  imploded array
	 */
	public function recursive_implode( array $array, $glue = ',', $include_keys = false, $trim_all = true ) {
		$glued_string = '';

		// Recursively iterates array and adds key/value to glued string.
		array_walk_recursive(
			$array,
			function( $value, $key ) use ( $glue, $include_keys, &$glued_string ) {
				if ( $value ) {
					$tidy_key                       = str_replace( ',', '', str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $key ) ) ) ) . ': ';
					$include_keys && $glued_string .= $tidy_key;
					$glued_string                  .= $value . $glue;
				}
			}
		);

		// Removes last $glue from string.
		strlen( $glue ) > 0 && $glued_string = substr( $glued_string, 0, -strlen( $glue ) );

		// Trim ALL whitespace.
		$trim_all && $glued_string = preg_replace( '/(\s)/ixsm', '', $glued_string );

		return (string) $glued_string;
	}

	/**
	 * Determien the name for a given, entry.
	 *
	 * @param  array $entry - Entry to determine name of.
	 * @return string $entry_name - The name.
	 */
	public function determine_entry_name( $entry ) {
		$propery_ids = array(
			'1',
			'2',
			'3',
		);

		foreach ( $propery_ids as $id ) {
			$have_name = rgar( $entry, $id );
			if ( ! empty( $have_name ) ) {
				return $have_name;
			}
		}

		return esc_html__( 'Not found', 'wsal-gravity-forms' );
	}

	/**
	 * Check if the alert was triggered recently.
	 *
	 * Checks last 5 events if they occured less than 20 seconds ago.
	 *
	 * @param integer|array $alert_id - Alert code.
	 * @return boolean
	 */
	protected function was_triggered_recently( $alert_id ) {

		if ( method_exists( 'self', 'was_triggered_recently' ) ) {
			return self::was_triggered_recently();
		}

		// if we have already checked this don't check again.
		if ( isset( $this->cached_alert_checks ) && array_key_exists( $alert_id, $this->cached_alert_checks ) && $this->cached_alert_checks[ $alert_id ] ) {
			return true;
		}
		$query = new WSAL_Models_OccurrenceQuery();
    
		if ( method_exists( $query, 'add_order_by' ) ) {
			$query->add_order_by( 'created_on', true );
			$query->set_limit( 5 );
			$last_occurences  = $query->get_adapter()->execute( $query );
		} else {
			$query->addOrderBy( 'created_on', true );
			$query->setLimit( 5 );
			$last_occurences  = $query->GetAdapter()->Execute( $query );
		}

		$known_to_trigger = false;
		foreach ( $last_occurences as $last_occurence ) {
			if ( $known_to_trigger ) {
				break;
			}
			if ( ! empty( $last_occurence ) && ( $last_occurence->created_on + 20 ) > time() ) {
				if ( ! is_array( $alert_id ) && $last_occurence->alert_id === $alert_id ) {
					$known_to_trigger = true;
				} elseif ( is_array( $alert_id ) && in_array( $last_occurence[0]->alert_id, $alert_id, true ) ) {
					$known_to_trigger = true;
				}
			}
		}
		// once we know the answer to this don't check again to avoid queries.
		$this->cached_alert_checks[ $alert_id ] = $known_to_trigger;
		return $known_to_trigger;
	}

	/**
	 * Temporary function to bridge the transition between WSAL pre/post coding-standards.
	 */
	private function trigger_event_if( $alert_code, $variables, $condition ) {
		$alert_manager = $this->plugin->alerts;

		if ( method_exists( $alert_manager, 'trigger_event_if' ) ) {
			$this->plugin->alerts->trigger_event_if( $alert_code, $variables, $condition );
		} else {
			$this->plugin->alerts->TriggerIf( $alert_code, $variables, $condition );
		}
	}

	/**
	 * Temporary function to bridge the transition between WSAL pre/post coding-standards.
	 */
	private function trigger_event( $alert_code, $variables ) {
		$alert_manager = $this->plugin->alerts;

		if ( method_exists( $alert_manager, 'trigger_event' ) ) {
			$this->plugin->alerts->trigger_event_if( $alert_code, $variables );
		} else {
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}
	}
}
