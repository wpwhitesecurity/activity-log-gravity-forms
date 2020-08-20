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
		add_action( 'gform_pre_notification_deleted', array( $this, 'event_form_notification_deleted' ), 10, 2 );
		add_action( 'gform_pre_notification_activated', array( $this, 'event_form_notification_activated' ), 10, 2 );
		add_action( 'gform_pre_notification_deactivated', array( $this, 'event_form_notification_deactivated' ), 10, 2 );

		// Entries
		add_action( 'gform_update_is_starred', array( $this, 'event_form_entry_starred' ), 10, 3 );
		add_action( 'gform_update_is_read', array( $this, 'event_form_entry_read' ), 10, 3 );
		add_action( 'gform_delete_entry', array( $this, 'event_form_entry_deleted' ), 10, 1 );
		add_action( 'gform_update_status', array( $this, 'event_form_entry_trashed' ), 10, 3 );
		add_action( 'gform_post_note_added', array( $this, 'event_form_entry_note_added' ), 10, 6 );
		add_action( 'gform_pre_note_deleted', array( $this, 'event_form_entry_note_deleted' ), 10, 2 );

		// Global Settings
		add_action( 'updated_option', array( $this, 'event_settings_updated' ), 10, 3 );
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

		return $form_id;
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

		if ( isset( $this->_old_form ) ) {
			$form = GFAPI::get_form( $form_id );
			// Compare the 2 arrays and create array of changed.
			$compare_changed_items = array_diff_assoc(
				array_map( 'serialize', $form ),
				array_map( 'serialize', $this->_old_form )
			);
			$changed_data          = array_map( 'unserialize', $compare_changed_items );

			foreach ( $changed_data as $changed_setting => $value ) {

				if ( 'is_active' === $changed_setting || 'is_trash' === $changed_setting || 'date_created' === $changed_setting || 'confirmations' === $changed_setting || 'notifications' === $changed_setting || 'nextFieldId' === $changed_setting ) {
						continue;
				}

				if ( 'fields' === $changed_setting ) {

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

					// Handle added items
					if ( ! empty( $added_items ) && ( count( $current_fields ) > count( $old_fields ) ) ) {
						error_log( print_r( 'ADDED RUNNING', true ) );
						foreach ( $added_items as $item ) {

							// Make sure this field is not a modified field.
							$ok_to_alert = true;
							foreach ( $old_fields as $old => $value ) {
								if ( $value->id === $item->id ) {
									$ok_to_alert = false;
								}
							}
							foreach ( $changed_items as $changed => $value ) {
								if ( $value->id === $item->id ) {
									unset( $changed_items[ $changed ] );
								}
							}

							if ( $ok_to_alert ) {
								$alert_code  = 5715;
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

								$this->plugin->alerts->Trigger( $alert_code, $variables );
							}
						}
					}

					if ( ! empty( $changed_items ) && ( count( $old_fields ) === count( $current_fields ) ) ) {
						foreach ( $changed_items as $item ) {
							$ok_to_alert = true;
							foreach ( $old_fields as $old => $value ) {
								if ( $value->id === $item->id && $value->label === $item->label ) {
									$ok_to_alert = false;
								}
							}

							foreach ( $removed_items as $removed => $value ) {
								if ( $item->id === $value->id ) {
									unset( $removed_items[ $removed ] );
								}
							}

							if ( $ok_to_alert ) {
								$alert_code  = 5715;
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

								$this->plugin->alerts->Trigger( $alert_code, $variables );
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
								$alert_code          = 5715;
										$editor_link = esc_url(
											add_query_arg(
												array(
													'id' => $form_id,
												),
												admin_url( 'admin.php?page=gf_edit_forms' )
											)
										);

										  $variables = array(
											  'EventType'  => 'removed',
											  'field_name' => $item->label,
											  'field_type' => $item->type,
											  'form_name'  => sanitize_text_field( $form['title'] ),
											  'form_id'    => $form_id,
											  'EditorLinkForm' => $editor_link,
										  );

										  $this->plugin->alerts->Trigger( $alert_code, $variables );
							}
						}
					}
				} else {
					$alert_code  = 5703;
					$editor_link = esc_url(
						add_query_arg(
							array(
								'id' => $form_id,
							),
							admin_url( 'admin.php?page=gf_edit_forms' )
						)
					);

					// Handle personal data settings.
					if ( 'personalData' === $changed_setting ) {
						$old_fields            = $this->_old_form[ $changed_setting ];
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

							// Give the value a more useful label.
							if ( empty( $value ) ) {
								$value = 'disabled';
							} elseif ( 1 == $value ) {
								$value = 'enabled';
							} elseif ( 'retain' === $value ) {
								$value = 'Retain entries indefinitely';
							} elseif ( 'trash' === $value ) {
								$value = 'Trash entries automatically';
							} elseif ( 'delete' === $value ) {
								$value = 'Delete entries permanently automatically';
							}

							if ( ! $this->was_triggered_recently( 5703 ) ) {
								$variables = array(
									'EventType'      => 'modified',
									'setting_name'   => sanitize_text_field( str_replace( '_', ' ', ucfirst( preg_replace( '/([a-z0-9])([A-Z])/', '$1 $2', $name ) ) ) ),
									'setting_value'  => sanitize_text_field( $value ),
									'form_name'      => sanitize_text_field( $form['title'] ),
									'form_id'        => $form_id,
									'EditorLinkForm' => $editor_link,
								);
								$this->plugin->alerts->Trigger( $alert_code, $variables );
							}
						}
					}

					// Handle everything else.
					if ( 'personalData' !== $changed_setting ) {
						if ( isset( $this->_old_form[ $changed_setting ] ) ) {
							if ( is_array( $value ) ) {
								$value_string = '';
								foreach ( $value as $value_name => $val ) {
									if ( is_array( $val ) ) {
										foreach ( $val as $name => $v ) {
											$value_string .= ucfirst( $name ) . ': ' . ucfirst( $v ) . ' | ';
										}
									} elseif ( ! empty( $val ) ) {
										$value_string .= ucfirst( $value_name ) . ': ' . ucfirst( $val ) . ' | ';
									}
								}
								$value = substr( $value_string, 0, -2 );
							}
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
			}
		}

	}

	public function event_form_confirmation_saved( $confirmation, $form ) {

		$is_an_update = false;

		// Handle modified confirmations
		if ( isset( $this->_old_form['confirmations'] ) ) {
			foreach ( $this->_old_form['confirmations'] as $old_confirmation ) {
				$id_to_lookup = $old_confirmation['id'];
				// Check if confirmation is found in old form, if so, we know its a modification.
				if ( $id_to_lookup == $confirmation['id'] ) {
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

				$this->plugin->alerts->TriggerIf( $alert_code, $variables );
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
			'EventType'         => 'deleted',
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'confirmation_name' => sanitize_text_field( $confirmation['name'] ),
			'confirmation_type' => $confirmation['type'],
			'EditorLinkForm'    => $editor_link,
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
				'EventType'         => 'created',
				'form_name'         => sanitize_text_field( $form['title'] ),
				'form_id'           => $form['id'],
				'notification_name' => sanitize_text_field( $notification['name'] ),
				'EditorLinkForm'    => $editor_link,
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
				'EventType'         => 'modified',
				'form_name'         => sanitize_text_field( $form['title'] ),
				'form_id'           => $form['id'],
				'notification_name' => sanitize_text_field( $notification['name'] ),
				'EditorLinkForm'    => $editor_link,
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

	}

	public function event_form_notification_deleted( $notification, $form ) {
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
			'EventType'         => 'deleted',
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'notification_name' => sanitize_text_field( $notification['name'] ),
			'EditorLinkForm'    => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );

		return $notification;
	}

	public function event_form_notification_activated( $notification, $form ) {
		$alert_code  = 5707;
		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'         => 'activated',
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'notification_name' => sanitize_text_field( $notification['name'] ),
			'EditorLinkForm'    => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );

		return $notification;
	}

	public function event_form_notification_deactivated( $notification, $form ) {
		$alert_code  = 5707;
		$editor_link = esc_url(
			add_query_arg(
				array(
					'id' => $form['id'],
				),
				admin_url( 'admin.php?page=gf_edit_forms' )
			)
		);

		$variables = array(
			'EventType'         => 'deactivated',
			'form_name'         => sanitize_text_field( $form['title'] ),
			'form_id'           => $form['id'],
			'notification_name' => sanitize_text_field( $notification['name'] ),
			'EditorLinkForm'    => $editor_link,
		);

		$this->plugin->alerts->Trigger( $alert_code, $variables );

		return $notification;
	}

	public function event_form_entry_starred( $entry_id, $property_value, $previous_value ) {

		$entry = GFAPI::get_entry( $entry_id );
		$form  = GFAPI::get_form( $entry['form_id'] );

		// Get the 1st field with a value so we can use it as the name.
		if ( ! empty( rgar( $entry, '1' ) ) ) {
			$entry_name = rgar( $entry, '1' );
		} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
			$entry_name = rgar( $entry, '2' );
		} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
			$entry_name = rgar( $entry, '3' );
		} else {
			$entry_name = 'Not found';
		}

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

		// Starred.
		if ( $previous_value != $property_value && $property_value == 1 ) {
			$alert_code = 5710;
			$variables  = array(
				'EventType'   => 'starred',
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

		// Unstarred.
		if ( $previous_value != $property_value && $property_value == 0 ) {
			$alert_code = 5710;
			$variables  = array(
				'EventType'   => 'unstarred',
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

	}

	public function event_form_entry_read( $entry_id, $property_value, $previous_value ) {
		$entry = GFAPI::get_entry( $entry_id );
		$form  = GFAPI::get_form( $entry['form_id'] );

		// Get the 1st field with a value so we can use it as the name.
		if ( ! empty( rgar( $entry, '1' ) ) ) {
			$entry_name = rgar( $entry, '1' );
		} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
			$entry_name = rgar( $entry, '2' );
		} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
			$entry_name = rgar( $entry, '3' );
		} else {
			$entry_name = 'Not found';
		}

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

		// Starred.
		if ( $property_value == 1 ) {
			$alert_code = 5711;
			$variables  = array(
				'EventType'   => 'read',
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

		// Unstarred.
		if ( $property_value == 0 ) {
			$alert_code = 5711;
			$variables  = array(
				'EventType'   => 'unread',
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

	}

	public function event_form_entry_deleted( $entry_id ) {
		$entry = GFAPI::get_entry( $entry_id );
		$form  = GFAPI::get_form( $entry['form_id'] );

		// Get the 1st field with a value so we can use it as the name.
		if ( ! empty( rgar( $entry, '1' ) ) ) {
			$entry_name = rgar( $entry, '1' );
		} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
			$entry_name = rgar( $entry, '2' );
		} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
			$entry_name = rgar( $entry, '3' );
		} else {
			$entry_name = 'Not found';
		}

		$alert_code = 5713;
		$variables  = array(
			'EventType'   => 'deleted',
			'entry_title' => $entry_name,
			'form_name'   => $form['title'],
			'form_id'     => $form['id'],
		);
		$this->plugin->alerts->Trigger( $alert_code, $variables );

	}

	public function event_form_entry_trashed( $entry_id, $property_value, $previous_value ) {
		if ( $previous_value !== $property_value && 'trash' === $property_value ) {
			$entry = GFAPI::get_entry( $entry_id );
			$form  = GFAPI::get_form( $entry['form_id'] );

			// Get the 1st field with a value so we can use it as the name.
			if ( ! empty( rgar( $entry, '1' ) ) ) {
				$entry_name = rgar( $entry, '1' );
			} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
				$entry_name = rgar( $entry, '2' );
			} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
				$entry_name = rgar( $entry, '3' );
			} else {
				$entry_name = 'Not found';
			}

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

			$alert_code = 5712;
			$variables  = array(
				'EventType'   => 'deleted',
				'event_desc'  => esc_html__( 'moved to trash', 'wsal-gravity-forms' ),
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

		if ( $previous_value !== $property_value && 'active' === $property_value ) {
			$entry = GFAPI::get_entry( $entry_id );
			$form  = GFAPI::get_form( $entry['form_id'] );

			// Get the 1st field with a value so we can use it as the name.
			if ( ! empty( rgar( $entry, '1' ) ) ) {
				$entry_name = rgar( $entry, '1' );
			} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
				$entry_name = rgar( $entry, '2' );
			} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
				$entry_name = rgar( $entry, '3' );
			} else {
				$entry_name = 'Not found';
			}

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

			$alert_code = 5712;
			$variables  = array(
				'EventType'   => 'restored',
				'event_desc'  => esc_html__( 'restored', 'wsal-gravity-forms' ),
				'entry_title' => $entry_name,
				'form_name'   => $form['title'],
				'form_id'     => $form['id'],
				'EntryLink'   => $editor_link,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}

	}

	public function event_form_entry_note_added( $insert_id, $entry_id, $user_id, $user_name, $note, $note_type ) {

		$entry = GFAPI::get_entry( $entry_id );
		$form  = GFAPI::get_form( $entry['form_id'] );

		// Get the 1st field with a value so we can use it as the name.
		if ( ! empty( rgar( $entry, '1' ) ) ) {
			$entry_name = rgar( $entry, '1' );
		} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
			$entry_name = rgar( $entry, '2' );
		} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
			$entry_name = rgar( $entry, '3' );
		} else {
			$entry_name = 'Not found';
		}

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

		$alert_code = 5714;
		$variables  = array(
			'EventType'   => 'added',
			'entry_note'  => $note,
			'entry_title' => $entry_name,
			'form_name'   => $form['title'],
			'form_id'     => $form['id'],
			'EntryLink'   => $editor_link,
		);
		$this->plugin->alerts->Trigger( $alert_code, $variables );

	}

	public function event_form_entry_note_deleted( $note_id, $lead_id ) {

		$entry = GFAPI::get_entry( $lead_id );
		$form  = GFAPI::get_form( $entry['form_id'] );
		$note  = GFAPI::get_note( $note_id );

		// Get the 1st field with a value so we can use it as the name.
		if ( ! empty( rgar( $entry, '1' ) ) ) {
			$entry_name = rgar( $entry, '1' );
		} elseif ( ! empty( rgar( $entry, '2' ) ) ) {
			$entry_name = rgar( $entry, '2' );
		} elseif ( ! empty( rgar( $entry, '3' ) ) ) {
			$entry_name = rgar( $entry, '3' );
		} else {
			$entry_name = 'Not found';
		}

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

		$alert_code = 5714;
		$variables  = array(
			'EventType'   => 'deleted',
			'entry_note'  => $note->value,
			'entry_title' => $entry_name,
			'form_name'   => $form['title'],
			'form_id'     => $form['id'],
			'EntryLink'   => $editor_link,
		);
		$this->plugin->alerts->Trigger( $alert_code, $variables );

	}

	public function event_settings_updated( $option_name, $old_value, $value ) {

		if ( ( strpos( $option_name, 'gform' ) !== false || strpos( $option_name, 'gravityforms' ) !== false ) && ( 'gform_version_info' !== $option_name ) ) {

			// Skip settings we dont want.
			if ( 'rg_gforms_key' === $option_name || 'rg_gforms_message' === $option_name ) {
				return;
			}

			if ( 'rg_gforms_disable_css' === $option_name ) {
				$option_name = 'Output CSS';
				$value       = ( 1 == $value ) ? 'No' : 'Yes';
				$old_value   = ( 1 == $old_value ) ? 'No' : 'Yes';
			}

			if ( 'rg_gforms_enable_html5' === $option_name ) {
				$option_name = 'Output HTML5';
				$value       = ( 1 == $value ) ? 'Yes' : 'No';
				$old_value   = ( 1 == $old_value ) ? 'Yes' : 'No';
			}

			if ( 'gform_enable_noconflict' === $option_name ) {
				$option_name = 'No-Conflict Mode';
				$value       = ( 1 == $value ) ? 'On' : 'Off';
				$old_value   = ( 1 == $old_value ) ? 'On' : 'Off';
			}

			if ( 'rg_gforms_currency' === $option_name ) {
				$option_name = 'Currency';
			}

			if ( 'gform_enable_background_updates' === $option_name ) {
				$option_name = 'Background updates';
				$value       = ( 1 == $value ) ? 'On' : 'Off';
				$old_value   = ( 1 == $old_value ) ? 'On' : 'Off';
			}

			if ( 'gform_enable_toolbar_menu' === $option_name ) {
				$option_name = 'Toolbar menu';
				$value       = ( 1 == $value ) ? 'On' : 'Off';
				$old_value   = ( 1 == $old_value ) ? 'On' : 'Off';
			}

			if ( 'gform_enable_logging' === $option_name ) {
				$option_name = 'Logging';
				$value       = ( 1 == $value ) ? 'On' : 'Off';
				$old_value   = ( 1 == $old_value ) ? 'On' : 'Off';
			}

			if ( 'rg_gforms_captcha_type' === $option_name ) {
				$option_name = 'Captcha type';
			}

			if ( 'gravityformsaddon_gravityformswebapi_settings' === $option_name ) {
				$option_name = 'Gravity Forms API Settings';
				$value       = ( 1 == $value['enabled'] ) ? 'On' : 'Off';
				$old_value   = ( 1 == $old_value['enabled'] ) ? 'On' : 'Off';
			}

			$alert_code = 5716;
			$variables  = array(
				'EventType'    => 'modified',
				'setting_name' => $option_name,
				'old_value'    => $old_value,
				'new_value'    => $value,
			);
			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}
	}

	/**
	 * Check if the alert was triggered recently.
	 *
	 * Checks last 5 events if they occured less than 5 seconds ago.
	 *
	 * @param integer|array $alert_id - Alert code.
	 * @return boolean
	 */
	private function was_triggered_recently( $alert_id ) {
		// if we have already checked this don't check again.
		if ( isset( $this->cached_alert_checks ) && array_key_exists( $alert_id, $this->cached_alert_checks ) && $this->cached_alert_checks[ $alert_id ] ) {
			return true;
		}
		$query = new WSAL_Models_OccurrenceQuery();
		$query->addOrderBy( 'created_on', true );
		$query->setLimit( 5 );
		$last_occurences  = $query->getAdapter()->Execute( $query );
		$known_to_trigger = false;
		foreach ( $last_occurences as $last_occurence ) {
			if ( $known_to_trigger ) {
				break;
			}
			if ( ! empty( $last_occurence ) && ( $last_occurence->created_on + 5 ) > time() ) {
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
}
