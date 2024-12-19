<?php

$theme_info = wp_get_theme();
define( 'STM_THEME_VERSION', ( WP_DEBUG ) ? time() : $theme_info->get( 'Version' ) );

if ( ! is_admin() ) {
	// Motors icons for all layouts.
	add_action( 'wp_enqueue_scripts', 'stm_load_all_icons' );

	// scripts and styles.
	if ( defined( 'ULISTING_VERSION' ) ) {
		add_action( 'wp_enqueue_scripts', 'stm_load_ulisting_theme_ss' );
	} else {
		add_action( 'wp_enqueue_scripts', 'stm_load_theme_ss' );
	}
}

function stm_load_all_icons() {
	wp_enqueue_style( 'stm-theme-icons', get_theme_file_uri( '/assets/css/dist/theme-icons.css' ), null, STM_THEME_VERSION, 'all' );
}

function stm_load_theme_ss() {
	/*deregister scripts&styles from plugin*/
	$plugin_ss = array( 'bootstrap', 'sell-a-car-form', 'listings-add-car', 'mlv-plugin-scripts', 'bootstrap-grid', 'modal-bootstrap', 'modal-bootstrap-js', 'compare-modal', 'motors-single-listing' );
	foreach ( $plugin_ss as $ss ) {
		wp_dequeue_style( $ss );
		wp_deregister_style( $ss );
		wp_dequeue_script( $ss );
		wp_deregister_script( $ss );
	}

	$template_directory_uri = get_template_directory_uri();
	$jquery                 = array( 'jquery' );

	wp_enqueue_script( 'jquery', false, array(), STM_THEME_VERSION, false );
	wp_enqueue_script( 'jquery-touch-punch' );

	if ( ! wp_script_is( 'jquery-migrate', 'enqueued' ) ) {
		wp_enqueue_script( 'stm-jquerymigrate', get_theme_file_uri( '/assets/js/jquery-migrate-3.3.2.min.js' ), $jquery, STM_THEME_VERSION, true );
	}

	// header styles.
	$file_name = 'header-' . stm_get_header_layout();
	stm_motors_enqueue_header_scripts_styles( $file_name );
	$site_color_style = apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' );
	if ( 'site_style_default' !== $site_color_style ) {
		wp_dequeue_style( $file_name );
		wp_deregister_style( $file_name );
	}

	// Styles
	// Fonts.
	$typography_body_font_family    = apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_body_font_family' );
	$typography_heading_font_family = apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_heading_font_family' );

	$layout          = stm_get_current_layout();
	$upload_dir      = wp_upload_dir();
	$stm_upload_dir  = $upload_dir['baseurl'] . '/stm_uploads';
	$stm_upload_path = $upload_dir['basedir'] . '/stm_uploads';

	// Main font if user hasn't chosen anything.
	if ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_body_font_family' ) ) || ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_heading_font_family' ) ) ) {
		stm_nuxy_google_fonts();
	}

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share' ) ) {
		wp_register_script( 'addtoany-core', 'https://static.addtoany.com/menu/page.js', array(), STM_THEME_VERSION, true );
	}

	wp_register_style( 'taxonomy-archive-style', get_theme_file_uri( '/assets/css/dist/taxonomy-archive.css' ), null, STM_THEME_VERSION, 'all' );

	if ( is_tax() ) {
		wp_enqueue_style( 'stm_classic_filter' );
		wp_enqueue_style( 'motors-inventory-search-filter' );
		wp_enqueue_style( 'taxonomy-archive-style' );
	}

	wp_deregister_style( 'bootstrap' );
	wp_deregister_script( 'bootstrap' );
	wp_register_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap/main.css' ), null, STM_THEME_VERSION, 'all' );
	wp_register_script( 'bootstrap', get_theme_file_uri( '/assets/js/dist/bootstrap.js' ), $jquery, STM_THEME_VERSION, true );

	//DateTime Picker
	wp_register_style( 'stmdatetimepicker', get_theme_file_uri( '/assets/css/stmdatetimepicker.css' ), null, STM_THEME_VERSION, 'all' );
	wp_register_script( 'stmdatetimepicker', get_theme_file_uri( '/assets/js/stmdatetimepicker.js' ), $jquery, STM_THEME_VERSION, true );
	wp_register_script( 'app-datetime', get_theme_file_uri( '/assets/js/parts/datetime.js' ), 'stmdatetimepicker', STM_THEME_VERSION, true );

	//LoadImage
	wp_register_script( 'load-image', get_theme_file_uri( '/assets/js/load-image.all.min.js' ), array(), STM_THEME_VERSION, true );

	//Edit listing in single page
	wp_register_style( 'stm-listing-edit-panel', get_theme_file_uri( '/assets/css/dist/listing-edit-panel.css' ), array(), STM_THEME_VERSION );

	//IsoTope
	wp_register_script( 'isotope', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery', 'imagesloaded' ), STM_THEME_VERSION, true );

	//uniform
	wp_register_script( 'uniform', get_theme_file_uri( '/assets/js/jquery.uniform.min.js' ), $jquery, STM_THEME_VERSION, true );
	wp_register_script( 'uniform-init', get_theme_file_uri( '/assets/js/parts/uniform.js' ), array( 'uniform' ), STM_THEME_VERSION, true );

	//LightGallery
	wp_enqueue_script( 'lg-video', get_theme_file_uri( '/assets/js/lg-video.js' ), array( 'jquery' ), STM_THEME_VERSION, true );

	//TypeAHead
	wp_enqueue_script( 'typeahead', get_theme_file_uri( '/assets/js/typeahead.jquery.min.js' ), $jquery, STM_THEME_VERSION, true );

	//UserSidebar
	wp_register_script( 'stm-theme-user-sidebar', get_theme_file_uri( '/assets/js/app-user-sidebar.js' ), $jquery, STM_THEME_VERSION, true );

	//CountDown
	wp_register_script( 'jquery.countdown.js', get_theme_file_uri( '/assets/js/jquery.countdown.min.js' ), $jquery, STM_THEME_VERSION, true );

	if ( is_post_type_archive( apply_filters( 'stm_listings_multi_type', array( 'listings' ) ) ) || ( apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_currency_enable' ) && ! empty( apply_filters( 'motors_vl_get_nuxy_mod', '', 'currency_list' ) ) ) || apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_wpml_switcher' ) || ( class_exists( 'WooCommerce' ) && is_product() ) ) {
		wp_enqueue_style( 'stmselect2' );
		wp_enqueue_script( 'stmselect2' );
		wp_enqueue_script( 'app-select2' );
	}

	if ( is_post_type_archive( apply_filters( 'stm_listings_multi_type', array( 'listings' ) ) ) || is_singular( array( 'listings', 'stm_events' ) ) ) {
		wp_enqueue_script( 'uniform' );
		wp_enqueue_script( 'uniform-init' );
	}

	if ( stm_motors_is_unit_test_mod() && file_exists( get_template_directory() . '/assets/css/unit-test-styles.css' ) ) {
		wp_enqueue_style( 'stm-unit-test-styles', $template_directory_uri . '/assets/css/unit-test-styles.css', null, STM_THEME_VERSION, 'all' );
	}

	// Electric Vehicle Dealership.
	if ( apply_filters( 'stm_is_ev_dealer', false ) ) {
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );
	}

	if ( 'site_style_default' !== apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' ) && is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
		wp_enqueue_style( 'stm-skin-custom', $stm_upload_dir . '/skin-custom.css', array( 'bootstrap' ), get_option( 'stm_custom_style', '4' ), 'all' );
	} else {
		if ( file_exists( get_theme_file_path( '/assets/css/dist/app-' . $layout . '.css' ) ) ) {
			wp_enqueue_style( 'stm-theme-style-css', get_theme_file_uri( '/assets/css/dist/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );

			$_deps = array( 'bootstrap' );
			if ( defined( 'ELEMENTOR_VERSION' ) && ( new Elementor\Preview() )->is_preview_mode() ) {
				$_deps[] = 'elementor-select2';
			}

			wp_enqueue_style( 'stm-theme-style-' . $layout . '-css', get_theme_file_uri( '/assets/css/dist/app-' . $layout . '.css' ), $_deps, STM_THEME_VERSION, 'all' );
		} else {
			/**
			 * Layouts NOT using the main app.css:
			 * 1. Boats
			 * 2. Motorcycles
			 * 3. Auto Parts
			 * 4. Rental One
			 */
			if ( 'boats' === $layout ) {
				wp_enqueue_style( 'stm-theme-style-boats', get_theme_file_uri( '/assets/css/dist/boats/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
			} elseif ( 'motorcycle' === $layout ) {
				wp_enqueue_style( 'stm-theme-style-sass', get_theme_file_uri( '/assets/css/dist/motorcycle/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
			} elseif ( apply_filters( 'stm_is_auto_parts', false ) ) {
				wp_enqueue_style( 'stm-theme-style-ap-sass', get_theme_file_uri( '/assets/css/dist/auto-parts/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
			} else {
				wp_enqueue_style( 'stm-theme-style-sass', get_theme_file_uri( '/assets/css/dist/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );

				if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
					if ( apply_filters( 'stm_is_listing_four', false ) ) {
						wp_enqueue_style( 'stm-theme-style-listing-four-sass', get_theme_file_uri( '/assets/css/dist/listing_four/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
					} else {
						wp_enqueue_style( 'stm-theme-style-listing-sass', get_theme_file_uri( '/assets/css/dist/listing/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
						if ( apply_filters( 'stm_is_listing_two', false ) ) {
							if ( apply_filters( 'stm_is_listing_two_elementor', false ) ) {
								wp_enqueue_style( 'stm-theme-style-listing-two-sass', get_theme_file_uri( '/assets/css/dist//app-listing_two_elementor.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
							} else {
								wp_enqueue_style( 'stm-theme-style-listing-two-sass', get_theme_file_uri( '/assets/css/dist/listing_two/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
							}
						}
						if ( apply_filters( 'stm_is_listing_three', false ) ) {
							if ( apply_filters( 'stm_is_listing_three_elementor', false ) ) {
								wp_enqueue_style( 'stm-theme-style-listing-three-sass', get_theme_file_uri( '/assets/css/dist/app-listing_three_elementor.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
							} else {
								wp_enqueue_style( 'stm-theme-style-listing-three-sass', get_theme_file_uri( '/assets/css/dist/listing_three/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
							}
						}
					}
				} elseif ( 'car_magazine' === $layout ) {
					wp_enqueue_style( 'stm-theme-style-magazine-sass', get_theme_file_uri( '/assets/css/dist/magazine/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
				} elseif ( 'car_dealer_two' === $layout ) {
					wp_enqueue_style( 'stm-theme-style-dealer-two-sass', get_theme_file_uri( '/assets/css/dist/dealer_two/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
				}
			}

			if ( apply_filters( 'stm_is_rental', false ) ) {
				wp_enqueue_style( 'stm-theme-style-rental', get_theme_file_uri( '/assets/css/dist/rental/app.css' ), array( 'bootstrap' ), STM_THEME_VERSION, 'all' );
			}
		}
	}

	// Animations.
	wp_enqueue_style( 'stm-theme-style-animation', get_theme_file_uri( '/assets/css/animation.css' ), null, STM_THEME_VERSION, 'all' );

	$site_style = apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' );

	if ( $site_style && 'site_style_default' !== $site_style && 'site_style_custom' !== $site_style ) {
		wp_enqueue_style( STM_THEME_SLUG . '-' . $site_style );
	}

	// Theme main stylesheet.
	wp_enqueue_style( 'stm-theme-style', $template_directory_uri . '/style.css', array(), STM_THEME_VERSION, 'all' );

	if ( file_exists( $stm_upload_path . '/wpcfto-generate.css' ) ) {
		wp_enqueue_style( 'stm-wpcfto-styles', $stm_upload_dir . '/wpcfto-generate.css', null, get_option( 'stm_wpcfto_style' ), 'all' );
	}

	// Scripts.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$google_marker_cluster = 'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js';

	wp_register_script( 'stm_marker_cluster', $google_marker_cluster, $jquery, STM_THEME_VERSION, true );

	wp_register_script( 'stm_grecaptcha', 'https://www.google.com/recaptcha/api.js?onload=stmMotorsCaptcha&render=explicit', $jquery, STM_THEME_VERSION, true );

	wp_enqueue_script( 'stm-classie', get_theme_file_uri( '/assets/js/classie.js' ), $jquery, STM_THEME_VERSION, false );
	wp_enqueue_script( 'lazyload' );

	if ( class_exists( 'WooCommerce' ) && ( is_checkout() || is_shop() || is_account_page() || is_edit_account_page() || ( apply_filters( 'stm_is_rental_two', false ) && is_product() ) ) ) {
		motors_include_once_scripts_styles( array( 'uniform', 'uniform-init', 'stmselect2', 'app-select2' ) );
	}

	if ( ! apply_filters( 'stm_is_auto_parts', true ) ) {
		if ( file_exists( get_theme_file_path( '/assets/js/app-' . $layout . '.js' ) ) ) {
			if ( apply_filters( 'stm_is_listing_five', false ) ) {
				wp_enqueue_script( 'stm-theme-scripts-main', get_theme_file_uri( '/assets/js/app.js' ), $jquery, STM_THEME_VERSION, true );
			}

			wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app-' . $layout . '.js' ), array( 'jquery', 'bootstrap' ), STM_THEME_VERSION, true );
		} else {
			wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app.js' ), array( 'jquery', 'bootstrap' ), STM_THEME_VERSION, true );
			if ( apply_filters( 'stm_is_rental', false ) ) {
				wp_enqueue_script( 'stm-theme-rental-scripts', get_theme_file_uri( '/assets/js/app-rental.js' ), array( 'jquery', 'bootstrap' ), STM_THEME_VERSION, true );
			}
		}
	} else {
		wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app-auto-parts.js' ), array( 'jquery', 'bootstrap' ), STM_THEME_VERSION, true );
	}

	if ( apply_filters( 'stm_is_magazine', false ) ) {

		if ( is_single() ) {
			wp_enqueue_style( 'stmselect2' );
			wp_enqueue_script( 'stmselect2' );
			wp_enqueue_script( 'app-select2' );
		}

		wp_enqueue_script( 'stm-magazine-theme-scripts', get_theme_file_uri( '/assets/js/magazine_scripts.js' ), $jquery, STM_THEME_VERSION, true );
		wp_enqueue_script( 'vue_min', get_theme_file_uri( '/assets/js/vue.min.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
		wp_enqueue_script( 'vue_resource', get_theme_file_uri( '/assets/js/vue-resource.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
		wp_enqueue_script( 'vue_app', get_theme_file_uri( '/assets/js/vue-app.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
	}

	wp_add_inline_script( 'stm-theme-scripts', apply_filters( 'stm_me_get_nuxy_mod', '', 'footer_custom_scripts' ) );

	$cats_conf = apply_filters( 'stm_get_car_filter', array() );

	$is_num_conf = array();
	if ( ! empty( $cats_conf ) ) {
		foreach ( $cats_conf as $key => $cat ) {
			$is_num_conf[ $cat['slug'] ] = ( $cat['numeric'] ) ? true : false;
		}
	}

	if ( class_exists( 'STMMultiListing' ) ) {
		$custom_post_types = STMMultiListing::stm_get_listings();

		foreach ( $custom_post_types as $cpt ) {
			$options = get_option( "stm_{$cpt['slug']}_options" );
			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $cat ) {
					$is_num_conf[ $cat['slug'] ] = ( $cat['numeric'] ) ? true : false;
				}
			}
		}
	}

	wp_add_inline_script( 'stm-theme-scripts', 'var stm_cats_conf = ' . wp_json_encode( $is_num_conf ) . ';' );

	wp_register_script( 'stm-countUp.min.js', get_theme_file_uri( '/assets/js/countUp.min.js' ), $jquery, STM_THEME_VERSION, true );

	// Enable scroll js only if user wants header be fixed.
	$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
	if ( ! empty( $fixed_header ) && $fixed_header ) {
		wp_enqueue_script( 'stm-theme-scripts-header-scroll', get_theme_file_uri( '/assets/js/app-header-scroll.js' ), $jquery, STM_THEME_VERSION, true );
	}

	if ( apply_filters( 'stm_is_rental', false ) ) {
		wp_enqueue_script( 'moment-localize', get_theme_file_uri( '/assets/js/moment.min.js' ), $jquery, STM_THEME_VERSION, false );
	}

	$smooth_scroll = apply_filters( 'stm_me_get_nuxy_mod', false, 'smooth_scroll' );

	if ( ! empty( $smooth_scroll ) && true === $smooth_scroll && ! is_admin() && ( class_exists( 'Elementor' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode( get_the_ID() ) ) ) {
		wp_enqueue_script( 'stm-smooth-scroll', get_theme_file_uri( '/assets/js/smoothScroll.js' ), $jquery, STM_THEME_VERSION, true );
	}

	if ( ! apply_filters( 'stm_is_auto_parts', true ) && ! apply_filters( 'stm_is_rental_two', true ) ) {
		wp_enqueue_script( 'stm-theme-scripts-ajax', get_theme_file_uri( '/assets/js/app-ajax.js' ), array( 'jquery', 'jquery-cookie' ), STM_THEME_VERSION, true );
	}

	if ( ! apply_filters( 'stm_is_auto_parts', true ) && ! apply_filters( 'stm_is_rental_two', true ) ) {
		wp_enqueue_script( 'stm-theme-script-filter', get_theme_file_uri( '/assets/js/filter.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
	}

	if ( apply_filters( 'stm_is_boats', false ) || apply_filters( 'stm_is_dealer_two', false ) || boolval( apply_filters( 'is_listing', array() ) ) || apply_filters( 'stm_is_car_dealer', false ) ) {
		wp_enqueue_script( 'custom_scrollbar' );
	}

	wp_localize_script(
		'stm-theme-scripts',
		'stm_i18n',
		array(
			'remove_from_compare'   => __( 'Remove from compare', 'motors' ),
			'remove_from_favorites' => __( 'Remove from favorites', 'motors' ),
			'add_to_favorites'      => __( 'Add to favorites', 'motors' ),
			'add_to_compare'        => __( 'Add to compare', 'motors' ),
		)
	);

	wp_localize_script(
		'stm-theme-scripts',
		'stm_theme_config',
		array(
			'enable_friendly_urls' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ),
		)
	);
}


function stm_load_ulisting_theme_ss() {
	$directoryStylesheet = get_template_directory_uri();

	$jquery = array( 'jquery' );

	wp_enqueue_script( 'jquery', false, array(), STM_THEME_VERSION, false );
	wp_enqueue_script( 'jquery-effects-slide' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'uniform', get_theme_file_uri( '/assets/js/jquery.uniform.min.js' ), $jquery, STM_THEME_VERSION, true );

	$layout          = stm_get_current_layout();
	$upload_dir      = wp_upload_dir();
	$stm_upload_dir  = $upload_dir['baseurl'] . '/stm_uploads';
	$stm_upload_path = $upload_dir['basedir'] . '/stm_uploads';

	if ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_body_font_family' ) ) || ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'typography_heading_font_family' ) ) ) {
		stm_nuxy_google_fonts();
	}

	wp_enqueue_script( 'light-gallery' );

	if ( file_exists( get_theme_file_path( '/assets/js/app-' . $layout . '.js' ) ) ) {
		wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app-' . $layout . '.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
	}

	// header styles.
	$file_name = 'header-' . stm_get_header_layout();
	stm_motors_enqueue_header_scripts_styles( $file_name );
	$site_color_style = apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' );
	if ( 'site_style_default' !== $site_color_style ) {
		wp_dequeue_style( $file_name );
		wp_deregister_style( $file_name );
	}

	wp_enqueue_style( 'light-gallery' );

	wp_enqueue_style( 'stm-theme-style', $directoryStylesheet . '/style.css', null, STM_THEME_VERSION, 'all' );

	if ( apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' ) !== 'site_style_default' && is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
		wp_enqueue_style( 'stm-skin-custom', $stm_upload_dir . '/skin-custom.css', null, get_option( 'stm_custom_style', '4' ), 'all' );
	} else {
		wp_enqueue_style( 'stm-theme-style-ulisting', get_theme_file_uri( '/assets/css/dist/app-' . $layout . '.css' ), null, STM_THEME_VERSION, 'all' );
	}

	if ( file_exists( $stm_upload_path . '/wpcfto-generate.css' ) ) {
		wp_enqueue_style( 'stm-wpcfto-styles', $stm_upload_dir . '/wpcfto-generate.css', null, get_option( 'stm_wpcfto_style' ), 'all' );
	}
}


// Admin styles.
add_action( 'admin_enqueue_scripts', 'stm_admin_assets' );
add_action( 'admin_enqueue_scripts', 'stm_load_all_icons' );
function stm_admin_assets() {
	wp_enqueue_style( 'stm-theme-admin-css', get_template_directory_uri() . '/assets/admin/css/style.css', null, 4.1, 'all' );
}

if ( ! function_exists( 'stm_motors_enqueue_header_scripts_styles' ) ) {
	function stm_motors_enqueue_header_scripts_styles( $file_name ) {
		if ( ! wp_style_is( $file_name, 'enqueued' ) && file_exists( get_theme_file_path( '/assets/css/dist/headers/' . $file_name . '.css' ) ) ) {
			wp_enqueue_style( $file_name, get_theme_file_uri( '/assets/css/dist/headers/' . $file_name . '.css' ), null, STM_THEME_VERSION, 'all' );
		}

		if ( ! wp_script_is( $file_name, 'enqueued' ) && file_exists( get_theme_file_path( '/assets/js/headers/' . $file_name . '.js' ) ) ) {
			wp_enqueue_script( $file_name, get_theme_file_uri( '/assets/js/headers/' . $file_name . '.js' ), 'jquery', STM_THEME_VERSION, false );
		}
	}
}

if ( ! function_exists( 'motors_include_once_scripts_styles' ) ) {
	function motors_include_once_scripts_styles( $handle ) {
		if ( is_array( $handle ) ) {
			foreach ( $handle as $id ) {
				if ( wp_style_is( $id, 'registered' ) && ! wp_style_is( $id, 'enqueued' ) ) {
					wp_enqueue_style( $id );
				}

				if ( wp_script_is( $id, 'registered' ) && ! wp_script_is( $id, 'enqueued' ) ) {
					wp_enqueue_script( $id );
				}
			}
		} else {
			if ( wp_style_is( $handle, 'registered' ) && ! wp_style_is( $handle, 'enqueued' ) ) {
				wp_enqueue_style( $handle );
			}

			if ( wp_script_is( $handle, 'registered' ) && ! wp_script_is( $handle, 'enqueued' ) ) {
				wp_enqueue_script( $handle );
			}
		}
	}
}

// Default Google fonts enqueue.
if ( ! function_exists( 'stm_default_google_fonts_enqueue' ) ) {
	function stm_default_google_fonts_enqueue() {
		$fonts_url       = '';
		$fonts_url_local = array();

		$typography_body_font_family = apply_filters( 'stm_me_get_nuxy_mod', array(), 'typography_body_font_family' );
		$enable_download_fonts       = apply_filters( 'stm_me_get_nuxy_mod', array(), 'fonts_download_settings' );
		if ( $enable_download_fonts && ! empty( $typography_body_font_family['font-data']['local_url'] ) && ! empty( $typography_body_font_family['font-data']['family'] ) ) {
			$font_families_local['typography_body_font_family'] = $typography_body_font_family['font-data']['local_url'];
		} elseif ( ! empty( $typography_body_font_family['font-data']['family'] ) ) {
			$font_families[ strtolower( str_replace( ' ', '_', $typography_body_font_family['font-data']['family'] ) ) ] = $typography_body_font_family['font-data']['family'] . ':' . implode( ',', $typography_body_font_family['font-data']['variants'] );
		}

		$typography_heading_font_family = apply_filters( 'stm_me_get_nuxy_mod', array(), 'typography_heading_font_family' );
		if ( $enable_download_fonts && ! empty( $typography_heading_font_family['font-data']['local_url'] ) && ! empty( $typography_heading_font_family['font-data']['family'] ) ) {
			$font_families_local['typography_heading_font_family'] = $typography_heading_font_family['font-data']['local_url'];
		} elseif ( ! empty( $typography_heading_font_family['font-data']['family'] ) ) {
			$font_families[ strtolower( str_replace( ' ', '_', $typography_heading_font_family['font-data']['family'] ) ) ] = $typography_heading_font_family['font-data']['family'] . ':' . implode( ',', $typography_heading_font_family['font-data']['variants'] );
		}

		$typography_menu_font_family = apply_filters( 'stm_me_get_nuxy_mod', array(), 'typography_menu_font_family' );
		if ( $enable_download_fonts && ! empty( $typography_menu_font_family['font-data']['local_url'] ) && ! empty( $typography_menu_font_family['font-data']['family'] ) ) {
			$font_families_local['typography_menu_font_family'] = $typography_menu_font_family['font-data']['local_url'];
		} elseif ( ! empty( $typography_menu_font_family['font-data']['family'] ) ) {
			$font_families[ strtolower( str_replace( ' ', '_', $typography_menu_font_family['font-data']['family'] ) ) ] = $typography_menu_font_family['font-data']['family'] . ':' . implode( ',', $typography_menu_font_family['font-data']['variants'] );
		}

		$typography_main_menu_font_family = apply_filters( 'stm_me_get_nuxy_mod', array(), 'typography_main_menu_font_settings' );
		if ( $enable_download_fonts && ! empty( $typography_main_menu_font_family['font-data']['local_url'] ) && ! empty( $typography_main_menu_font_family['font-data']['family'] ) ) {
			$font_families_local['typography_main_menu_font_settings'] = $typography_main_menu_font_family['font-data']['local_url'];
		} elseif ( ! empty( $typography_main_menu_font_family['font-data']['family'] ) ) {
			$font_families[ strtolower( str_replace( ' ', '_', $typography_main_menu_font_family['font-data']['family'] ) ) ] = $typography_main_menu_font_family['font-data']['family'] . ':' . implode( ',', $typography_main_menu_font_family['font-data']['variants'] );
		}

		$logo_font_family = apply_filters( 'stm_me_get_nuxy_mod', array(), 'logo_font_family' );
		if ( $enable_download_fonts && ! empty( $logo_font_family['font-data']['local_url'] ) && ! empty( $logo_font_family['font-data']['family'] ) ) {
			$font_families_local['logo_font_family'] = $logo_font_family['font-data']['local_url'];
		} elseif ( ! empty( $logo_font_family['font-data']['family'] ) ) {
			$font_families[ strtolower( str_replace( ' ', '_', $logo_font_family['font-data']['family'] ) ) ] = $logo_font_family['font-data']['family'] . ':' . implode( ',', $logo_font_family['font-data']['variants'] );
		}

		if ( apply_filters( 'stm_is_ev_dealer', false ) ) {
			$font_families[] = 'Montserrat:400,500,600,700,800,900';
		}

		if ( ! empty( $font_families_local ) ) {
			$fonts_url_local['fonts_local'] = $font_families_local;
		} elseif ( ! empty( $font_families ) ) {
			$query_args = array(
				'family' => rawurlencode( implode( '|', $font_families ) ),
				'subset' => rawurlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		} else {
			if ( apply_filters( 'stm_is_motorcycle', false ) ) {
				$montserrat = _x( 'on', 'Exo 2 font: on or off', 'motors' );
			} else {
				$montserrat = _x( 'on', 'Montserrat font: on or off', 'motors' );
			}
			$open_sans = _x( 'on', 'Open Sans font: on or off', 'motors' );

			if ( 'off' !== $montserrat || 'off' !== $open_sans ) {
				$font_families = array();

				if ( 'off' !== $montserrat ) {
					if ( apply_filters( 'stm_is_motorcycle', false ) ) {
						$font_families[] = 'Exo 2:400,300,500,600,700,800,900';
					} else {
						$font_families[] = 'Montserrat:400,500,600,700,800,900';
					}
				}

				if ( 'off' !== $open_sans ) {
					$font_families[] = 'Open Sans:300,400,500,700,800,900';
				}

				$query_args = array(
					'family' => rawurlencode( implode( '|', $font_families ) ),
					'subset' => rawurlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			}
		}

		if ( ! empty( $fonts_url_local ) ) {
			return $fonts_url_local;
		} else {
			return esc_url_raw( $fonts_url );
		}
	}
}

function stm_nuxy_google_fonts() {
	$fonts_local = stm_default_google_fonts_enqueue();
	if ( ! empty( $fonts_local['fonts_local'] ) ) {
		foreach ( $fonts_local['fonts_local'] as $key => $font ) {
			$font_url = site_url( $font );
			wp_enqueue_style( 'stm_default_google_font_' . $key, $font_url, null, STM_THEME_VERSION, 'all' );
		}
	} else {
		wp_enqueue_style( 'stm_default_google_font', $fonts_local, null, STM_THEME_VERSION, 'all' );
	}
}

add_action( 'customize_controls_enqueue_scripts', 'motors_customize_scripts' );
if ( ! function_exists( 'motors_customize_scripts' ) ) {
	function motors_customize_scripts() {
		wp_enqueue_script( 'motors-customize-script', get_template_directory_uri() . '/assets/js/customize.js', array( 'customize-controls' ), 'all', true );

		wp_localize_script(
			'motors-customize-script',
			'customize_data',
			array(
				'message' => sprintf(
					__( 'Motors theme Customize Settings were moved to <a href="%s">Dashboard > Theme Options</a>.', 'motors' ),
					admin_url( '?page=wpcfto_motors_' . stm_get_current_layout() . '_settings' )
				),
			)
		);
	}
}
