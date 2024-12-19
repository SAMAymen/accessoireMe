<?php
// enqueue scripts and styles per module basis.
if ( ! function_exists( 'stm_motors_enqueue_scripts_styles' ) ) {
	function stm_motors_enqueue_scripts_styles( $file_name ) {
		if ( wp_style_is( $file_name, 'registered' ) ) {
			wp_enqueue_style( $file_name );
		}

		if ( wp_script_is( $file_name, 'registered' ) ) {
			wp_enqueue_script( $file_name );
		}
	}
}

// global styles for shortcodes.
add_action( 'wp_enqueue_scripts', 'stm_mww_global_styles' );
function stm_mww_global_styles() {

	$css_dir_files = array_filter(
		scandir( STM_MWW_PATH . '/assets/css' ),
		function ( $item ) {
			return ! is_dir( STM_MWW_PATH . '/assets/css/' . $item );
		}
	);

	$js_dir_files = array_filter(
		scandir( STM_MWW_PATH . '/assets/js' ),
		function ( $item ) {
			return ! is_dir( STM_MWW_PATH . '/assets/js/' . $item );
		}
	);

	if ( ! empty( $css_dir_files ) ) {
		foreach ( $css_dir_files as $file ) {
			if ( 'site_style_default' === apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' ) ) {
				wp_register_style( str_replace( '.css', '', $file ), STM_MWW_URL . '/assets/css/' . $file, null, get_bloginfo( 'version' ), 'all' );
			}
		}
	}

	if ( ! empty( $js_dir_files ) ) {
		foreach ( $js_dir_files as $file ) {
			wp_register_script( str_replace( '.js', '', $file ), STM_MWW_URL . '/assets/js/' . $file, 'jquery', get_bloginfo( 'version' ), true );
		}
	}

	wp_enqueue_style( 'stm-vc_templates_color_scheme', STM_MWW_URL . '/assets/css/vc_templates_color_scheme.css', null, get_bloginfo( 'version' ), 'all' );
}

// look for VC templates in this plugin.
vc_set_shortcodes_templates_dir( STM_MWW_PATH . '/templates' );

// use VC as a theme.
add_action( 'vc_before_init', 'stm_vc_set_as_theme' );
function stm_vc_set_as_theme() {
	vc_set_as_theme( true );
}


// use VC to edit these post types.
if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
	vc_set_default_editor_post_types(
		array(
			'page',
			'post',
			'sidebar',
			'product',
			apply_filters( 'stm_listings_post_type', 'listings' ),
		)
	);
}


// change native shortcodes.
add_action( 'init', 'stm_update_existing_shortcodes' );
function stm_update_existing_shortcodes() {
	if ( function_exists( 'vc_add_params' ) ) {

		vc_add_params(
			'vc_row',
			array(
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Enable STM Fullwidth', 'motors-wpbakery-widgets' ),
					'param_name' => 'stm_fullwidth',
					'value'      => array(
						__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Enable STM Fullwidth without js', 'motors-wpbakery-widgets' ),
					'param_name' => 'stm_fullwidth',
					'value'      => array(
						__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Blackout Opacity', 'motors-wpbakery-widgets' ),
					'param_name' => 'blackout_opacity',
					'value'      => array(
						__( '0%', 'motors-wpbakery-widgets' )  => '0',
						__( '20%', 'motors-wpbakery-widgets' ) => '20',
						__( '40%', 'motors-wpbakery-widgets' ) => '40',
						__( '60%', 'motors-wpbakery-widgets' ) => '60',
						__( '80%', 'motors-wpbakery-widgets' ) => '80',
					),
					'dependency' => array(
						'element' => 'stm_fullwidth',
						'value'   => 'yes',
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Float menu item title', 'motors-wpbakery-widgets' ),
					'param_name' => 'float_menu_item_title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Float menu color', 'motors-wpbakery-widgets' ),
					'param_name' => 'float_menu_color',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Anchor for float menu', 'motors-wpbakery-widgets' ),
					'param_name' => 'anchor_id_float_menu',
				),
			)
		);

		vc_add_param(
			'vc_single_image',
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Enable STM theme LightGallery on click', 'motors-wpbakery-widgets' ),
				'param_name' => 'stm_fancybox',
				'value'      => array(
					__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
				),
			)
		);

		vc_add_param(
			'vc_tabs',
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Style 2', 'motors-wpbakery-widgets' ),
				'param_name' => 'vc_tabs_style_2',
				'value'      => array(
					__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
				),
			)
		);

		vc_add_param(
			'vc_tabs',
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Service Style', 'motors-wpbakery-widgets' ),
				'param_name' => 'vc_tabs_style_service',
				'value'      => array(
					__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
				),
			)
		);

	}

	if ( function_exists( 'vc_remove_param' ) ) {
		vc_remove_param( 'vc_cta_button2', 'h2' );
		vc_remove_param( 'vc_cta_button2', 'content' );
		vc_remove_param( 'vc_cta_button2', 'btn_style' );
		vc_remove_param( 'vc_cta_button2', 'color' );
		vc_remove_param( 'vc_cta_button2', 'size' );
		vc_remove_param( 'vc_cta_button2', 'css_animation' );

		// Accordion.
		vc_remove_param( 'vc_tta_accordion', 'color' );
		vc_remove_param( 'vc_tta_accordion', 'shape' );
		vc_remove_param( 'vc_tta_accordion', 'style' );
		vc_remove_param( 'vc_tta_accordion', 'spacing' );
		vc_remove_param( 'vc_tta_accordion', 'c_align' );
		vc_remove_param( 'vc_tta_accordion', 'c_position' );

		// Tabs.
		vc_remove_param( 'vc_tta_tabs', 'title' );
		vc_remove_param( 'vc_tta_tabs', 'style' );
		vc_remove_param( 'vc_tta_tabs', 'shape' );
		vc_remove_param( 'vc_tta_tabs', 'color' );
		vc_remove_param( 'vc_tta_tabs', 'spacing' );
		vc_remove_param( 'vc_tta_tabs', 'gap' );
		vc_remove_param( 'vc_tta_tabs', 'alignment' );
		vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
		vc_remove_param( 'vc_tta_tabs', 'pagination_color' );

		// Toggle.
		vc_remove_param( 'vc_toggle', 'style' );
		vc_remove_param( 'vc_toggle', 'color' );
		vc_remove_param( 'vc_toggle', 'size' );
	}
}

// Add icons.
add_filter( 'vc_iconpicker-type-fontawesome', 'vc_stm_icons' );

if ( ! function_exists( 'vc_stm_icons' ) ) {
	function vc_stm_icons( $fonts ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$custom_fonts = get_option( 'stm_fonts' );
		if ( ! empty( $custom_fonts ) ) {
			foreach ( $custom_fonts as $font => $info ) {

				if ( empty( $info['config'] ) || empty( $info['json'] ) ) {
					continue;
				}

				$icon_set   = array();
				$icons      = array();
				$upload_dir = wp_upload_dir();
				$path       = trailingslashit( $upload_dir['basedir'] );
				$config     = $path . $info['include'] . '/' . $info['config'];
				$json       = $path . $info['include'] . '/' . $info['json'];

				if ( file_exists( $config ) && file_exists( $json ) ) {
					include $config;

					$file_contents = file_get_contents( $json ); // phpcs:ignore
					$selection     = json_decode( $file_contents, true );

					if ( ! empty( $selection ) ) {
						if ( ! empty( $selection['preferences'] ) && ! empty( $selection['preferences']['fontPref'] ) && ! empty( $selection['preferences']['fontPref']['prefix'] ) ) {
							$prefix = $selection['preferences']['fontPref']['prefix'];

							if ( ! empty( $prefix ) ) {
								if ( ! empty( $icons ) ) {
									$icon_set = array_merge( $icon_set, $icons );
								}
								if ( ! empty( $icon_set ) ) {
									foreach ( $icon_set as $icons ) {
										foreach ( $icons as $icon ) {
											$fonts['Theme Icons'][] = array(
												$prefix . $icon['class'] => $icon['class'],
											);
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$theme_icons = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/assets/icons_json/theme_icons.json' ), true );

		foreach ( $theme_icons['icons'] as $icon ) {
			$fonts['Service Icons'][] = array(
				'stm-icon-' . $icon['properties']['name'] => 'STM ' . $icon['properties']['name'],
			);
		}

		$service_icons = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/assets/icons_json/service_icons.json' ), true );

		foreach ( $service_icons['icons'] as $icon ) {
			$fonts['Service Icons'][] = array(
				'stm-service-icon-' . $icon['properties']['name'] => 'STM ' . $icon['properties']['name'],
			);
		}

		if ( apply_filters( 'stm_is_boats', false ) ) {
			$boat_icons = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/assets/icons_json/boat_icons.json' ), true );

			foreach ( $boat_icons['icons'] as $icon ) {
				$fonts['Boat Icons'][] = array(
					'stm-boats-icon-' . $icon['properties']['name'] => 'STM ' . $icon['properties']['name'],
				);
			}
		}

		$moto_icons = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/assets/icons_json/moto_icons.json' ), true );

		foreach ( $moto_icons['icons'] as $icon ) {
			$fonts['Motorcycle Icons'][] = array(
				'stm-moto-icon-' . $icon['properties']['name'] => 'STM ' . $icon['properties']['name'],
			);
		}

		$rent_icons = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/assets/icons_json/rental_one_icons.json' ), true );

		foreach ( $rent_icons['icons'] as $icon ) {
			$fonts['Rental Icons'][] = array(
				'stm-rental-' . $icon['properties']['name'] => 'STM ' . $icon['properties']['name'],
			);
		}

		return $fonts;
	}
}

function stm_mm_get_top_vehicles() {

	$cached_makes = get_transient( 'stm_mm_get_top_vehicles' );

	if ( ! empty( $cached_makes ) && count( $cached_makes ) > 0 ) {
		return $cached_makes;
	}

	$query = new WP_Query(
		array(
			'post_type'              => apply_filters( 'stm_listings_post_type', 'listings' ),
			'ignore_sticky_posts'    => 1,
			'post_status'            => 'publish',
			'posts_per_page'         => - 1,
			'meta_query'             => array(
				array(
					'key'     => 'stm_car_views',
					'value'   => '0',
					'compare' => '!=',
				),
			),
			'update_post_meta_cache' => false,
			'orderby'                => 'meta_value',
			'order'                  => 'DESC',
		)
	);

	$makes = array( __( 'All Makes', 'motors-wpbakery-widgets' ) => 'all_makes' );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$make = get_post_meta( get_the_ID(), 'make', true );
			if ( empty( $make ) ) {
				continue;
			}

			$makeName = get_term_by( 'slug', $make, 'make' );

			if ( ! $makeName || isset( $makes[ $makeName->name ] ) ) {
				continue;
			}

			$makes[ $makeName->name ] = $make;
		}
	}

	wp_reset_postdata();

	set_transient( 'stm_mm_get_top_vehicles', $makes );

	return $makes;
}
