<?php
new Motors_WPCFTO();

class Motors_WPCFTO {

	private $current_layout = '';

	public function __construct() {
		$this->current_layout = get_option( 'stm_motors_chosen_template', 'car_dealer' );

		if ( get_transient( 'temp_setup_layout' ) ) {
			$this->current_layout = get_transient( 'temp_setup_layout' );
		}

		add_filter(
			'wpcfto_field_stm-hidden',
			function () {
				return STM_MOTORS_EXTENDS_PATH . '/inc/wpcfto_conf/custom_fields/stm-hidden.php';
			}
		);

		add_action( 'init', array( $this, 'layout_conf_autoload' ) );
		add_action( 'admin_bar_menu', array( $this, 'stm_me_admin_bar_item' ), 500 );
		add_action( 'wp_ajax_wpcfto_save_settings', array( $this, 'motors_save_settings' ), 9, 1 );
		add_action( 'stm_importer_done', array( $this, 'motors_save_settings' ), 20, 1 );
		add_action( 'wpcfto_after_settings_saved', array( $this, 'stm_me_save_featured_as_term' ), 50, 2 );
		add_filter( 'wpcfto_options_page_setup', array( $this, 'motors_layout_options' ) );
	}

	public function stm_me_admin_bar_item( WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$admin_bar_icon = '<span class="ab-icon dashicons dashicons-admin-settings" style="top: 2px"></span>';
		if ( defined( 'STM_THEME_NAME' ) && 'Motors' === STM_THEME_NAME ) {
			$admin_bar_icon = '<span class="ab-icon"><img style="margin-top: -6px; max-height: 22px;" height="22" width="22" src="' . get_template_directory_uri() . '/assets/admin/images/icon.png" /></span>';
		}

		$admin_bar->add_menu(
			array(
				'id'     => 'stm-me-theme-options',
				'parent' => null,
				'group'  => null,
				'title'  => $admin_bar_icon . '<span class="ab-label">' . esc_html__( 'Theme Options', 'stm_motors_extends' ),
				'</span>',
				'href'   => admin_url( '?page=wpcfto_motors_' . $this->current_layout . '_settings' ),
				'meta'   => array(
					'title' => esc_html__( 'Theme Options', 'stm_motors_extends' ),
				),
			)
		);
	}

	public function layout_conf_autoload() {
		$config_map = array(
			'header_sm_logo'         => array( 'all' ),
			'header_sm_menu'         => array( 'all' ),
			'header_sm_socials'      => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'listing_five', 'listing_five_elementor', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'service', 'car_dealer_elementor_rtl' ),
			'header_sm_buttons'      => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'listing_five', 'listing_five_elementor', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'service', 'car_dealer_elementor_rtl' ),
			'header_layout_conf'     => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'listing_five', 'listing_five_elementor', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'rental_two', 'service', 'car_dealer_elementor_rtl', 'auto_parts', 'service' ),
			'site_style_conf'        => array( 'all' ),
			'general_conf'           => array( 'all' ),
			'google_conf'            => array( 'car_rental', 'car_rental_elementor', 'rental_two' ),
			'top_bar_conf'           => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'listing_five', 'listing_five_elementor', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'service', 'car_dealer_elementor_rtl', 'auto_parts' ),
			'blog_conf'              => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'listing_five', 'listing_five_elementor', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'car_dealer_elementor_rtl' ),
			'rental_layout_conf'     => array( 'car_rental', 'car_rental_elementor', 'rental_two' ),
			'single_listing'         => array( 'car_dealer_two', 'car_dealer_two_elementor', 'motorcycle', 'equipment' ),
			'auto_parts_layout_conf' => array( 'auto_parts' ),
			'shop_conf'              => array( 'ev_dealer', 'car_dealer_elementor', 'car_dealer', 'car_dealer_two', 'car_dealer_two_elementor', 'car_magazine', 'equipment', 'listing', 'listing_one_elementor', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_four', 'listing_four_elementor', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'car_rental_elementor', 'rental_two', 'auto_parts', 'car_dealer_elementor_rtl' ),
			'typography_conf'        => array( 'all' ),
			'socials_conf'           => array( 'all' ),
			'footer_layout_conf'     => array( 'all' ),
			'custom_css_conf'        => array( 'all' ),
			'custom_js_conf'         => array( 'all' ),
			'user_dealer_conf'       => array( 'all' ),
			'stm_motors_events'      => array( 'car_magazine' ),
			'stm_motors_review'      => array( 'car_magazine', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor' ),
		);

		if ( is_plugin_active( 'pearl-header-builder/pearl_header_builder.php' ) ) {
			$keysToRemove = array_keys( $config_map['header_layout_conf'], 'auto_parts', true );

			foreach ( $keysToRemove as $key ) {
				unset( $config_map['header_layout_conf'][ $key ] );
			}

			$keysToRemove = array_keys( $config_map['top_bar_conf'], 'auto_parts', true );

			foreach ( $keysToRemove as $key ) {
				unset( $config_map['top_bar_conf'][ $key ] );
			}
		}

		foreach ( $config_map as $file_name => $layouts ) {
			if ( 'all' === $layouts[0] || in_array( stm_me_get_current_layout(), $layouts, true ) ) {
				require_once STM_MOTORS_EXTENDS_PATH . '/inc/wpcfto_conf/layout_conf/' . $file_name . '.php';
			}
		}

		if ( empty( get_transient( 'theme_icons_set' ) ) ) {
			$this->stm_me_wpcfto_custom_icons();
		}
	}

	public function motors_layout_options( $setup ) {
		$opts = apply_filters( 'motors_get_all_wpcfto_config', array() );

		$motors_favicon = false;
		$motors_thumb   = false;
		if ( defined( 'STM_THEME_NAME' ) && 'Motors' === STM_THEME_NAME ) {
			$motors_favicon = get_template_directory_uri() . '/assets/admin/images/icon.png';
			$motors_thumb   = get_template_directory_uri() . '/assets/admin/images/logo.png';
		}

		$setup[] = array(
			// Here we specify option name. It will be a key for storing in wp_options table.
			'option_name' => 'wpcfto_motors_' . $this->current_layout . '_settings',

			'title'       => esc_html__( 'Theme options', 'stm_motors_extends' ),
			'sub_title'   => esc_html__( 'by StylemixThemes', 'stm_motors_extends' ),
			'logo'        => $motors_thumb,

			/*
			* Next we add a page to display our awesome settings.
			* All parameters are required and same as WordPress add_menu_page.
			*/
			'page'        => array(
				'page_title' => 'Theme Options',
				'menu_title' => 'Theme Options',
				'menu_slug'  => 'wpcfto_motors_' . $this->current_layout . '_settings',
				'icon'       => $motors_favicon,
				'position'   => 3,
			),

			/*
			* And Our fields to display on a page. We use tabs to separate settings on groups.
			*/
			'fields'      => $opts,
		);

		return $setup;
	}

	public function motors_save_settings( $layout = '' ) {
		if ( isset( $_GET['stm_demo_import_template'] ) ) {
			$layout = sanitize_text_field( wp_unslash( $_GET['stm_demo_import_template'] ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		if ( empty( $layout ) ) {
			check_ajax_referer( 'wpcfto_save_settings', 'nonce' );
			if ( empty( $_REQUEST['name'] ) ) {
				die;
			}
		}

		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$styles = '';

		if ( empty( $layout ) ) {
			$request_body = file_get_contents( 'php://input' );
			if ( ! empty( $request_body ) ) {
				$request_body = json_decode( $request_body, true );
				$styles       = $this->stm_me_collect_wpcfto_styles( $request_body );
			}
		} else {
			$options = wpcfto_get_settings_map( 'settings', 'wpcfto_motors_' . $layout . '_settings' );
			$styles  = $this->stm_me_collect_wpcfto_styles( $options );
		}

		$upload_dir = wp_upload_dir();

		if ( ! $wp_filesystem->is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
			do_action( 'stm_create_dir' );
		}

		if ( ! empty( $styles ) ) {
			$css_to_filter = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles );
			$css_to_filter = str_replace(
				array(
					"\r\n",
					"\r",
					"\n",
					"\t",
					'  ',
					'    ',
					'    ',
				),
				'',
				$css_to_filter
			);

			$custom_style_file = $upload_dir['basedir'] . '/stm_uploads/wpcfto-generate.css';

			$wp_filesystem->put_contents( $custom_style_file, $css_to_filter, FS_CHMOD_FILE );

			$current_style = get_option( 'stm_wpcfto_style', '1' );
			update_option( 'stm_wpcfto_style', $current_style + 1 );
		}

		$this->stm_me_wpcfto_custom_icons();
	}

	public function stm_me_save_featured_as_term( $id, $settings ) {

		if ( array_key_exists( 'site_style', $settings ) ) {
			set_transient( 'site_style_transient', $settings['site_style'] );
		}

		if ( array_key_exists( 'addl_user_features', $settings ) ) {
			foreach ( $settings['addl_user_features'] as $addl_user_feature ) {
				if ( ! empty( $addl_user_feature['tab_title_labels'] ) ) {
					$feature_list = explode( ',', $addl_user_feature['tab_title_labels'] );

					foreach ( $feature_list as $item ) {
						wp_insert_term( trim( $item ), 'stm_additional_features' );
					}
				}
			}
		}

		/* Patch for displaying settings 'Distance search' & 'Recommend items in other locations in case of empty result' */
		if ( $settings['enable_location'] ) {
			if ( $settings['enable_distance_search'] && $settings['recommend_items_empty_result'] ) {
				$settings['recommend_items_empty_result'] = false;

				update_option( $id, $settings );
			}
		}
	}

	private function stm_me_collect_wpcfto_styles( $request_body ) {
		$styles = '';

		$current_demo  = $request_body['general_tab']['fields']['header_current_layout']['value'];
		$header_layout = $request_body['header']['fields']['header_layout']['value'];

		foreach ( $request_body as $section_name => $section ) {
			foreach ( $section['fields'] as $field_name => $field ) {

				if ( ! empty( $field['output'] ) && ! empty( $field['value'] ) ) {

					if ( isset( $field['dependency'] ) && ! $this->stm_me_parse_dependency( $request_body, $section['fields'], $field['dependency'], ( isset( $field['dependencies'] ) ) ? $field['dependencies'] : false, $current_demo, $header_layout ) ) {
						continue;
					}

					$units     = '';
					$important = ( isset( $field['style_important'] ) ) ? ' !important' : '';

					if ( ! empty( $field['units'] ) ) {
						$units = $field['units'];
					}

					if ( ! empty( $field['mode'] ) && is_array( $field['mode'] ) ) {
						foreach ( $field['mode'] as $mode ) {
							$styles .= $field['output'] . '{' . $mode . ':' . $field['value'] . $units . $important . ';}';
						}
					} else {
						if ( 'spacing' === $field['type'] && ! empty( $field['mode'] ) ) {
							$unit   = $field['value']['unit'];
							$top    = ( '0' === $field['value']['top'] || (int) $field['value']['top'] > 0 ) ? $field['mode'] . '-top: ' . $field['value']['top'] . $unit . ' ' . $important . ';' : '';
							$left   = ( '0' === $field['value']['left'] || (int) $field['value']['left'] > 0 ) ? $field['mode'] . '-left: ' . $field['value']['left'] . $unit . ' ' . $important . ';' : '';
							$right  = ( '0' === $field['value']['right'] || (int) $field['value']['right'] > 0 ) ? $field['mode'] . '-right: ' . $field['value']['right'] . $unit . ' ' . $important . ';' : '';
							$bottom = ( '0' === $field['value']['bottom'] || (int) $field['value']['bottom'] > 0 ) ? $field['mode'] . '-bottom: ' . $field['value']['bottom'] . $unit . ' ' . $important . ';' : '';

							$styles .= $field['output'] . '{' . $top . ' ' . $right . ' ' . $bottom . ' ' . $left . '}';
						} elseif ( 'typography' === $field['type'] ) {
							$styles .= $field['output'] . '{';
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'font-family', $field['excluded'], true ) ) ) ) {
								$styles .= 'font-family:' . $field['value']['font-family'];
							}
							if ( ! empty( $field['value']['backup-font'] ) ) {
								$styles .= ', ' . $field['value']['backup-font'];
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'color', $field['excluded'], true ) ) ) ) {
								$styles .= '; color:' . $field['value']['color'] . ' ' . $important . ';';
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'font-size', $field['excluded'], true ) ) ) ) {
								$styles .= '; font-size:' . $field['value']['font-size'] . 'px';
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'line-height', $field['excluded'], true ) ) ) ) {
								$styles .= '; line-height:' . $field['value']['line-height'] . 'px';
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'font-weight', $field['excluded'], true ) ) ) ) {
								$styles .= '; font-weight:' . $field['value']['font-weight'];
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'font-style', $field['excluded'], true ) ) ) ) {
								$styles .= '; font-style:' . $field['value']['font-style'];
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'text-align', $field['excluded'], true ) ) ) ) {
								$styles .= '; text-align:' . $field['value']['text-align'];
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'text-transform', $field['excluded'], true ) ) ) ) {
								$styles .= '; text-transform:' . $field['value']['text-transform'];
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'letter-spacing', $field['excluded'], true ) ) ) ) {
								$styles .= '; letter-spacing:' . $field['value']['letter-spacing'] . 'px';
							}
							if ( ! isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && ! in_array( 'word-spacing', $field['excluded'], true ) ) ) ) {
								$styles .= '; word-spacing:' . $field['value']['word-spacing'] . 'px';
							}
							$styles .= '; }';
						} else {
							if ( 'hma_underline' === $field_name || 'hma_hover_underline' === $field_name ) {
								$styles .= $field['output'] . '{' . $field['mode'] . ': 2px solid ' . $field['value'] . $important . ';}';
							} else {
								$styles .= $field['output'] . '{' . $field['mode'] . ':' . $field['value'] . $units . $important . ';}';
							}
						}
					}
				}
			}
		}

		return $styles;
	}

	private function stm_me_parse_dependency( $config_all, $config_section, $dependency, $dependencies, $current_demo, $header_layout ) {

		if ( ! $dependencies ) {
			$options = explode( '||', $dependency['value'] );
			foreach ( $options as $opt ) {
				if ( 'header_current_layout' === $dependency['key'] ) {
					if ( $current_demo === $opt ) {
						return true;
					}
				} elseif ( 'header_layout' === $dependency['key'] ) {
					if ( $header_layout === $opt ) {
						return true;
					}
				} elseif ( 'not_empty' === $dependency['value'] ) {
					if ( isset( $dependency['section'] ) ) {
						if ( ! empty( $config_all[ $dependency['section'] ]['fields'][ $dependency['key'] ] ) ) {
							return true;
						}
					} else {
						if ( ! empty( $config_section[ $dependency['key'] ] ) ) {
							return true;
						}
					}
				}
			}
		} else {
			$bool_array = array();
			foreach ( $dependency as $k => $depends ) {
				$bool_option = array();
				$options     = explode( '||', $depends['value'] );

				foreach ( $options as $opt ) {
					if ( 'header_current_layout' === $depends['key'] ) {
						if ( $current_demo === $opt ) {
							$bool_option[] = 1;
						}
					} elseif ( 'header_layout' === $depends['key'] ) {
						if ( $header_layout === $opt ) {
							$bool_option[] = 1;
						}
					} elseif ( 'not_empty' === $depends['value'] ) {
						if ( isset( $depends['section'] ) ) {
							if ( ! empty( $config_all[ $depends['section'] ]['fields'][ $depends['key'] ] ) ) {
								$bool_option[] = 1;
							}
						} else {
							if ( ! empty( $config_section[ $depends['key'] ] ) ) {
								$bool_option[] = 1;
							}
						}
					}
				}

				$bool_array[] = ( count( $bool_option ) === 0 ) ? 0 : 1;
			}

			if ( '||' === $dependencies && array_sum( $bool_array ) > 0 ) {
				return true;
			}
			if ( '&&' === $dependencies && array_sum( $bool_array ) === count( $bool_array ) ) {
				return true;
			}
		}

		return false;
	}

	private function stm_me_wpcfto_custom_icons() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$iconset = array();

		$icons_config_map = array(
			'theme_icons',
			'aircrafts_icons',
			'auto_parts_icons',
			'listing_icons',
			'magazine_icons',
			'boat_icons',
			'moto_icons',
			'rental_one_icons',
			'service_icons',
		);

		foreach ( $icons_config_map as $file_name ) {
			$_local_file = get_template_directory() . '/assets/icons_json/' . $file_name . '.json';

			if ( file_exists( $_local_file ) ) {
				$icon_config = wp_json_file_decode( $_local_file, array( 'associative' => true ) );

				if ( ! empty( $icon_config ) ) {
					$prefix = $icon_config['preferences']['fontPref']['prefix'];

					foreach ( $icon_config['icons'] as $k => $icon ) {
						$attrs = array_filter( $icon['attrs'] );

						// Exclude icons with two or more colors
						if ( ! empty( $attrs ) && count( $attrs ) > 1 ) {
							continue;
						}

						$iconset[] = array(
							'title'       => $prefix . $icon['properties']['name'],
							'searchTerms' => array( $icon['properties']['name'] ),
						);
					}
				}
			}
		}

		if ( defined( 'CEI_CLASSES_PATH' ) ) {
			$extra_fonts = get_option( 'stm_fonts' );

			if ( empty( $extra_fonts ) ) {
				$extra_fonts = array();
			}

			$font_configs = $extra_fonts;

			$upload_dir = wp_upload_dir();
			$path       = trailingslashit( $upload_dir['basedir'] );
			$url        = trailingslashit( $upload_dir['baseurl'] );

			foreach ( $font_configs as $key => $config ) {
				if ( empty( $config['full_path'] ) ) {
					$font_configs[ $key ]['include'] = $path . $font_configs[ $key ]['include'];
					$font_configs[ $key ]['folder']  = $url . $font_configs[ $key ]['folder'];
				}
			}

			if ( ! empty( $font_configs ) ) {

				foreach ( $font_configs as $k => $val ) {

					if ( empty( $font_configs[ $k ]['json'] ) ) {
						continue;
					}

					$config_exists = file_exists( $font_configs[ $k ]['include'] . '/' . $font_configs[ $k ]['config'] );
					$json_exists   = file_exists( $font_configs[ $k ]['include'] . '/' . $font_configs[ $k ]['json'] );

					if ( $config_exists && $json_exists ) {

						require_once $font_configs[ $k ]['include'] . '/' . $font_configs[ $k ]['config'];

						$selection = json_decode( $wp_filesystem->get_contents( $font_configs[ $k ]['include'] . '/' . $font_configs[ $k ]['json'] ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

						if ( ! empty( $selection ) ) {
							if ( ! empty( $selection['preferences'] ) && ! empty( $selection['preferences']['fontPref'] ) && ! empty( $selection['preferences']['fontPref']['prefix'] ) ) {
								$prefix = $selection['preferences']['fontPref']['prefix'];

								if ( ! isset( $icons ) ) {
									continue;
								}

								foreach ( $icons[ $k ] as $key => $item ) {
									$iconset[] = array(
										'title'       => $prefix . $item['class'],
										'searchTerms' => array( $item['tags'] ),
									);
								}
							}
						}
					}
				}
			}
		}

		set_transient( 'theme_icons_set', $iconset );
	}
}

add_filter( 'stm_me_get_nuxy_mod', 'stm_me_get_nuxy_mod', 10, 3 );
function stm_me_get_nuxy_mod( $default = '', $opt_name = '', $return_default = false ) {
	$wpcfto_option_name = 'wpcfto_motors_' . stm_me_get_current_layout() . '_settings';
	$options            = get_option( $wpcfto_option_name, array() );

	$value_or_false = ( isset( $options[ $opt_name ] ) ) ? $options[ $opt_name ] : $default;

	if ( has_filter( 'wpcfto_motors_' . $opt_name ) ) {
		return apply_filters( 'wpcfto_motors_' . $opt_name, $value_or_false, $opt_name );
	}

	if ( is_bool( $value_or_false ) || ! empty( $value_or_false ) ) {
		return $value_or_false;
	}

	if ( $return_default ) {
		return $default;
	}

	return false;
}

function stm_me_set_wpcfto_mod( $opt_name, $value ) {
	$settings_name = 'wpcfto_motors_' . stm_me_get_current_layout() . '_settings';
	$options       = get_option( $settings_name, array() );

	if ( ! empty( $options[ $opt_name ] ) ) {
		$options[ $opt_name ] = apply_filters( 'wpcfto_motors_set_option_' . $opt_name, $value );
	}

	update_option( $settings_name, $options );
}

add_filter( 'stm_me_wpcfto_parse_spacing', 'stm_me_wpcfto_parse_spacing', 100, 2 );
function stm_me_wpcfto_parse_spacing( $default, $settings ) {
	if ( empty( $settings ) ) {
		return '';
	}

	$style  = ( ! empty( $settings['top'] ) ) ? 'margin-top: ' . $settings['top'] . 'px; ' : '';
	$style .= ( ! empty( $settings['right'] ) ) ? 'margin-right: ' . $settings['right'] . 'px; ' : '';
	$style .= ( ! empty( $settings['bottom'] ) ) ? 'margin-bottom: ' . $settings['bottom'] . 'px; ' : '';
	$style .= ( ! empty( $settings['left'] ) ) ? 'margin-left: ' . $settings['left'] . 'px; ' : '';

	return $style;
}

add_filter( 'stm_me_get_wpcfto_icon', 'stm_me_motors_get_wpcfto_icon', 100, 3 );
function stm_me_motors_get_wpcfto_icon( $option_name, $default_icon, $other_classes = '' ) {
	$icon_array = stm_me_get_nuxy_mod( $default_icon, $option_name, false );

	$style_array = array();

	// if color is not default.
	if ( ! empty( $icon_array['color'] ) && '#000' !== $icon_array['color'] ) {
		$style_array['color'] = $icon_array['color'];
	}

	// if icon size is not default.
	if ( ! empty( $icon_array['size'] ) && 15 !== $icon_array['size'] ) {
		$style_array['size'] = $icon_array['size'];
	}

	// if icon is set.
	if ( $icon_array && ! empty( $icon_array['icon'] ) ) {
		$default_icon = $icon_array['icon'];
	}

	// style string.
	$style_string = '';
	if ( ! empty( $style_array['color'] ) ) {
		$style_string .= 'color: ' . $style_array['color'] . '; ';
	}
	if ( ! empty( $style_array['size'] ) ) {
		$style_string .= 'font-size: ' . $style_array['size'] . 'px;';
	}

	$icon_element = '<i class="' . esc_attr( $default_icon . ' ' . $other_classes ) . '" style="' . esc_attr( $style_string ) . '"></i>';

	return $icon_element;
}

add_filter( 'stm_me_get_nuxy_img_src', 'stm_me_get_nuxy_img_src', 10, 3 );
function stm_me_get_nuxy_img_src( $default, $opt_name, $size = 'full' ) {
	$image = stm_me_get_nuxy_mod( $default, $opt_name, true );
	if ( is_numeric( $image ) && $image > 0 ) {
		$image = wp_get_attachment_image_url( $image, $size );

		// always return original full size image for logo.
		if ( 'logo' === $opt_name && is_string( $image ) && preg_match( '/-\d+[Xx]\d+\./', $image ) ) {
			$image = preg_replace( '/-\d+[Xx]\d+\./', '.', $image );
		}
	}

	return $image;
}

function stm_me_wpcfto_sidebars() {
	$sidebars = array(
		'no_sidebar' => esc_html__( 'Without sidebar', 'stm_motors_extends' ),
		'default'    => esc_html__( 'Primary sidebar', 'stm_motors_extends' ),
	);

	$query = get_posts(
		array(
			'post_type'      => 'sidebar',
			'posts_per_page' => -1,
		)
	);

	if ( $query ) {
		foreach ( $query as $post ) {
			$sidebars[ $post->ID ] = get_the_title( $post->ID );
		}
	}

	$sidebars = apply_filters( 'stm_me_wpcfto_sidebars_list', $sidebars );

	return $sidebars;
}

function stm_me_wpcfto_pages_list() {
	$post_types[] = __( '--- Default ---', 'stm_motors_extends' );
	$query        = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
		)
	);

	if ( $query ) {
		foreach ( $query as $post ) {
			$post_types[ $post->ID ] = get_the_title( $post->ID );
		}
	}

	return $post_types;
}

function stm_me_wpcfto_socials() {
	$socials = array(
		'facebook'     => esc_html__( 'Facebook', 'stm_motors_extends' ),
		'x-twitter'      => esc_html__( 'Twitter', 'stm_motors_extends' ),
		'vk'           => esc_html__( 'VK', 'stm_motors_extends' ),
		'instagram'    => esc_html__( 'Instagram', 'stm_motors_extends' ),
		'behance'      => esc_html__( 'Behance', 'stm_motors_extends' ),
		'dribbble'     => esc_html__( 'Dribbble', 'stm_motors_extends' ),
		'flickr'       => esc_html__( 'Flickr', 'stm_motors_extends' ),
		'git'          => esc_html__( 'Git', 'stm_motors_extends' ),
		'linkedin'     => esc_html__( 'Linkedin', 'stm_motors_extends' ),
		'pinterest'    => esc_html__( 'Pinterest', 'stm_motors_extends' ),
		'yahoo'        => esc_html__( 'Yahoo', 'stm_motors_extends' ),
		'delicious'    => esc_html__( 'Delicious', 'stm_motors_extends' ),
		'dropbox'      => esc_html__( 'Dropbox', 'stm_motors_extends' ),
		'reddit'       => esc_html__( 'Reddit', 'stm_motors_extends' ),
		'soundcloud'   => esc_html__( 'Soundcloud', 'stm_motors_extends' ),
		'google'       => esc_html__( 'Google', 'stm_motors_extends' ),
		'skype'        => esc_html__( 'Skype', 'stm_motors_extends' ),
		'youtube'      => esc_html__( 'Youtube', 'stm_motors_extends' ),
		'youtube-play' => esc_html__( 'Youtube Play', 'stm_motors_extends' ),
		'tumblr'       => esc_html__( 'Tumblr', 'stm_motors_extends' ),
		'whatsapp'     => esc_html__( 'Whatsapp', 'stm_motors_extends' ),
		'tiktok'       => esc_html__( 'Tiktok', 'stm_motors_extends' ),
	);

	return $socials;
}

function stm_me_wpcfto_kv_socials() {
	$socials          = stm_me_wpcfto_socials();
	$response_socials = array();

	foreach ( $socials as $k => $social ) {
		$response_socials[] = array(
			'key'   => $k,
			'label' => $social,
		);
	}

	return $response_socials;
}

function stm_me_wpcfto_headers_list() {
	$headers = array(
		'car_dealer'     => esc_html__( 'Dealer', 'stm_motors_extends' ),
		'car_dealer_two' => esc_html__( 'Dealer Two', 'stm_motors_extends' ),
		'ev_dealer'      => esc_html__( 'EV Dealer', 'stm_motors_extends' ),
		'listing'        => esc_html__( 'Classified', 'stm_motors_extends' ),
		'listing_five'   => esc_html__( 'Classified Five', 'stm_motors_extends' ),
		'boats'          => esc_html__( 'Boats', 'stm_motors_extends' ),
		'motorcycle'     => esc_html__( 'Motorcycle', 'stm_motors_extends' ),
		'car_rental'     => esc_html__( 'Rental', 'stm_motors_extends' ),
		'car_magazine'   => esc_html__( 'Magazine', 'stm_motors_extends' ),
		'aircrafts'      => esc_html__( 'Aircrafts', 'stm_motors_extends' ),
		'equipment'      => esc_html__( 'Equipment', 'stm_motors_extends' ),
		'service'        => esc_html__( 'Service', 'stm_motors_extends' ),
	);

	return $headers;
}

add_filter( 'stm_selected_header', 'stm_me_get_header_layout' );

function stm_me_get_header_layout() {
	$selected_layout = get_option( 'stm_motors_chosen_template' );

	if ( empty( $selected_layout ) ) {
		return 'car_dealer';
	}

	$headers_array = array(
		'service'                  => 'service',
		'listing_two'              => 'listing',
		'listing_two_elementor'    => 'listing',
		'listing_three'            => 'listing',
		'listing_three_elementor'  => 'listing',
		'listing_four'             => 'car_dealer',
		'listing_four_elementor'   => 'car_dealer',
		'ev_dealer'                => 'ev_dealer',
		'car_dealer_elementor_rtl' => 'car_dealer',
		'auto_parts'               => 'auto_parts',
	);

	$default_header = ( ! empty( $headers_array[ $selected_layout ] ) ) ? $headers_array[ $selected_layout ] : $selected_layout;

	/*
	* aircrafts
	* boats
	* car_dealer
	* car_dealer_two
	* equipment
	* listing
	* listing_five
	* magazine
	* motorcycle
	* car_rental
	* car_rental_elementor
	*/

	if ( apply_filters( 'stm_is_listing_six', false ) ) {
		return 'listing_five';
	}

	return stm_me_get_nuxy_mod( $default_header, 'header_layout', true );
}

function stm_me_wpcfto_positions() {
	$positions = array(
		'left'  => esc_html__( 'Left', 'stm_motors_extends' ),
		'right' => esc_html__( 'Right', 'stm_motors_extends' ),
	);

	return $positions;
}

function stm_me_wpcfto_sort_options() {
	$options = array();

	if ( function_exists( 'stm_listings_attributes' ) ) {
		$numeric_filters = array_keys(
			stm_listings_attributes(
				array(
					'where'  => array(
						'numeric' => true,
					),
					'key_by' => 'slug',
				)
			)
		);

		if ( ! empty( $numeric_filters ) ) {
			foreach ( $numeric_filters as $tax_name ) {
				$tax = get_taxonomy( $tax_name );
				if ( $tax ) {
					$options[ $tax->name ] = $tax->labels->singular_name;
				}
			}
		}
	}

	return $options;
}

function stm_me_wpcfto_sortby() {
	$sorts = array(
		'date_high' => esc_html__( 'Date: newest first', 'stm_motors_extends' ),
		'date_low'  => esc_html__( 'Date: oldest first', 'stm_motors_extends' ),
	);

	$options = stm_me_wpcfto_sort_options();
	if ( ! empty( $options ) ) {
		foreach ( $options as $slug => $name ) {
			/* translators: option name */
			$sorts[ $slug . '_high' ] = sprintf( esc_html__( '%s: highest first', 'stm_motors_extends' ), $name );
			/* translators: option name */
			$sorts[ $slug . '_low' ] = sprintf( esc_html__( '%s: lowest first', 'stm_motors_extends' ), $name );
		}
	}

	return $sorts;
}
add_filter( 'stm_me_wpcfto_sortby', 'stm_me_wpcfto_sortby' );

add_filter( 'wpcfto_icons_set', 'stm_me_get_theme_icons_set' );
function stm_me_get_theme_icons_set( $iconset ) {
	if ( ! empty( get_transient( 'theme_icons_set' ) ) ) {
		return array_merge( $iconset, get_transient( 'theme_icons_set' ) );
	}

	return $iconset;
}

add_filter( 'wpcfto_icons_set', 'stm_custom_icons_set' );
function stm_custom_icons_set( $icons ) {
	if ( function_exists( 'stm_get_cat_icons' ) ) {
		$external_icons = stm_get_cat_icons( 'external' );
		if ( ! empty( $external_icons ) ) {
			$custom_icons = array_map(
				function ( $icon ) {
					return array(
						'title'       => $icon,
						'searchTerms' => array( $icon ),
					);
				},
				$external_icons
			);
			return array_merge(
				$icons,
				$custom_icons
			);
		}
	}
	return $icons;
}

function wpcfto_print_settings( $settings_name = null ) {
	if ( empty( $settings_name ) ) {
		$settings_name = 'wpcfto_motors_' . get_option( 'stm_motors_chosen_template', 'car_dealer' ) . '_settings';
	}

	echo wp_json_encode( get_option( $settings_name ), true );
	exit;
}

function stm_me_get_all_pages() {
	$pages = array();
	$query = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => - 1,
		)
	);
	if ( $query ) {
		foreach ( $query as $page ) {
			$pages[ $page->ID ] = get_the_title( $page->ID );
		}
	}
	return $pages;
}
