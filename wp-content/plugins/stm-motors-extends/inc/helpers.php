<?php
if ( ! function_exists( 'stm_slider_listing_info' ) ) {
	function stm_slider_listing_info( $atts ) {
		$model    = uListing\Classes\StmListing::load( get_post( $atts['listing_id'] ) );
		$title    = $model->getOptions( $atts['listing_title'] );
		$title    = ( ! empty( $title[0] ) ) ? $title[0]->option_name : '';
		$subtitle = $model->getOptions( $atts['listing_subtitle'] );
		$subtitle = ( ! empty( $subtitle[0] ) ) ? $subtitle[0]->option_name : '';
		$price    = $model->getOptions( $atts['listing_price'] );
		$price    = ( ! empty( $price[0] ) && ! empty( $price[0]->option_name ) ) ? $price[0]->option_name : ( ( ! empty( $price[0]->value ) ) ? $price[0]->value : '' );
		$attr     = ( ! empty( $atts['listing_attr_slugs'] ) ) ? explode( ',', $atts['listing_attr_slugs'] ) : null;

		ob_start();
		?>
		<div class="stm-slider-listing-info-wrap">
			<div class="sl-info-left">
				<div class="make heading-font"><?php echo esc_html( $title ); ?></div>
				<div class="model heading-font"><?php echo esc_html( $subtitle ); ?></div>
				<div class="options">
					<ul>
						<?php
						foreach ( $attr as $slug ) :
							$attribute_data = $model->getOptions( trim( $slug ) );

							if ( ! empty( $attribute_data ) ) :
								?>
								<li>
									<span class="label heading-font"><?php echo esc_html( $attribute_data[0]->attribute_title ); ?></span>
									<span class="value heading-font"><?php echo esc_html( $attribute_data[0]->value ); ?></span>
								</li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</div>
			</div>
			<div class="sl-info-right">
				<div class="price-wrap">
					<span class="from heading-font"><?php echo esc_html__( 'From: ', 'stm_motors_extends' ); ?></span>
					<span class="price heading-font"><?php echo wp_kses_post( ulisting_currency_format( $price ) ); ?></span>
				</div>
				<div class="button-wrap">
					<a href="<?php echo esc_url( get_the_permalink( $atts['listing_id'] ) ); ?>" class="heading-font">
						<?php esc_html_e( 'BUY NOW', 'stm_motors_extends' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
		$info = ob_get_clean();

		return $info;
	}
}

add_shortcode( 'listing_info', 'stm_slider_listing_info' );

function stm_me_get_search_form( $atts ) {
	$form  = '<form method="get" id="searchform" action="' . home_url( '/' ) . '">';
	$form .= '<div class="searchform-wrapper">
				<div class="search-wrapper">
					<input placeholder="' . $atts['placeholder'] . '" type="text" class="form-control search-input" value="' . get_search_query() . '" name="s" id="s" />
					<button type="submit" class="search-submit" ><i class="fas fa-search"></i></button>
				</div>';
	$form .= '<div class="checkbox-wrapper">';
	if ( isset( $atts['post_types'] ) && ! empty( $atts['post_types'] ) ) {
		foreach ( explode( ',', $atts['post_types'] ) as $value ) {
			$form .= '<label for="rev-search-' . $value . '">
							<input id="rev-search-' . $value . '" type="checkbox" name="search_by_post_type[]" value="' . $value . '" />
							' . $value . '
						</label>';
		}
	}
	$form .= '</div>';
	$form .= '</div></form>';

	return $form;
}

add_shortcode( 'get_search_form', 'stm_me_get_search_form' );

if ( ! function_exists( 'stm_me_get_map_pin' ) ) {
	function stm_me_get_map_pin() {
		$pin_id = apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_pin' );

		if ( empty( $pin_id ) ) {
			$pin = 'map-marker';
			if ( apply_filters( 'stm_is_boats', false ) ) {
				$pin = 'boats-pin';
			}

			$pin = get_stylesheet_directory_uri() . '/assets/images/' . $pin . '.png';
		} else {
			$pin = wp_get_attachment_url( $pin_id );
		}

		return $pin;
	}

	add_filter( 'stm_me_get_map_pin', 'stm_me_get_map_pin', 10, 1 );
}

if ( function_exists( 'vc_add_shortcode_param' ) ) {

	function stm_autocomplete_vc_st( $settings, $value ) {
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">'
			. 'var st_vc_taxonomies = ' . wp_json_encode( stm_get_categories() )
			. '</script>'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}

	vc_add_shortcode_param( 'stm_autocomplete_vc', 'stm_autocomplete_vc_st', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );

	function stm_autocomplete_vc_st_taxonomies( $settings, $value ) {
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">'
			. 'var st_vc_taxonomies = ' . wp_json_encode( apply_filters( 'stm_get_taxonomies', array() ) )
			. '</script>'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}

	vc_add_shortcode_param( 'stm_autocomplete_vc_taxonomies', 'stm_autocomplete_vc_st_taxonomies', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );

	function stm_autocomplete_vc_st_location( $settings, $value ) {
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">
            jQuery("#ca_location_listing_filter_map").on("click", function() {
                
                var input = document.getElementById("ca_location_listing_filter_map");
                
                if(input !== null) {
                    
                    var autocomplete = new google.maps.places.Autocomplete(input);
        
                    google.maps.event.addListener(autocomplete, "place_changed", function (e) {
                        var place = autocomplete.getPlace();
        
                        var lat = "";
                        var lng = "";
        
                        if (typeof(place.geometry) != "undefined") {
                            lat = place.geometry.location.lat();
                            lng = place.geometry.location.lng();
                        } else {
                            lat = "";
                            lng = "";
                        }
        
                        jQuery("input[name=\'lat\']").val(lat);
                        jQuery("input[name=\'lng\']").val(lng);
                    });
        
                    google.maps.event.addDomListener(input, "keydown", function (e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                        }
                    });
                }
            });
            </script>'
			. '<input type="text" id="ca_location_listing_filter_map" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}

	vc_add_shortcode_param( 'stm_autocomplete_vc_location', 'stm_autocomplete_vc_st_location', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );

	// custom notice param.
	function stm_motors_swiper_slider_notice_for_admin( $settings, $value ) {
		$slider_settings_url = admin_url( 'admin.php?page=stm-listing-slider' );
		/* translators: settings page URL */
		return sprintf( __( 'You can add slides and configure settings <a href="%s">here</a>.', 'stm_motors_extends' ), $slider_settings_url );
	}

	vc_add_shortcode_param( 'stm_slider_notice', 'stm_motors_swiper_slider_notice_for_admin' );
}

function stm_me_theme_deregister() {
	if ( class_exists( 'woocommerce' ) && ! defined( 'ULISTING_VERSION' ) ) {
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );

		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
	}

	/*Deregister theme styles and scripts*/
	wp_dequeue_script( 'listings-frontend' );
	if ( wp_style_is( 'listings-frontend' ) ) {
		wp_dequeue_style( 'listings-frontend' );
	}

	wp_dequeue_style( 'listings-add-car' );
	wp_deregister_style( 'listings-add-car' );

	wp_dequeue_script( 'listings-add-car' );
	wp_deregister_script( 'listings-add-car' );
}

add_action( 'wp_enqueue_scripts', 'stm_me_theme_deregister', 999 );

/*WP MAIL FUNC*/

function stm_set_mail_html_content_type() {
	return 'text/html';
}

add_action( 'stm_set_html_content_type', 'stm_set_mail_html_content_type' );

function stm_me_enqueue_styles() {
	if ( defined( 'STM_WPCFTO_URL' ) ) {
		$v      = time();
		$assets = STM_WPCFTO_URL . 'metaboxes/assets';

		wp_enqueue_style( 'font-awesome-min', $assets . '/vendors/font-awesome.min.css', null, $v, 'all' );
	}
}

add_action( 'wp_enqueue_scripts', 'stm_me_enqueue_styles' );

/*WP MAIL FUNC*/
function stm_remove_woo_widgets() {
	unregister_widget( 'WC_Widget_Recent_Products' );
	unregister_widget( 'WC_Widget_Featured_Products' );
	unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
	unregister_widget( 'WC_Widget_Layered_Nav' );
	unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
	unregister_widget( 'WC_Widget_Product_Search' );
	unregister_widget( 'WC_Widget_Recent_Reviews' );
	unregister_widget( 'WC_Widget_Recently_Viewed' );
	unregister_widget( 'WC_Widget_Best_Sellers' );
	unregister_widget( 'WC_Widget_Onsale' );
	unregister_widget( 'WC_Widget_Random_Products' );
}

function stm_me_get_current_layout() {
	return get_option( 'stm_motors_chosen_template' );
}

function stm_me_is_boats() {
	$current_layout = stm_me_get_current_layout();

	if ( $current_layout ) {
		if ( 'boats' === $current_layout ) {
			$current_layout = true;
		} else {
			$current_layout = false;
		}
	} else {
		if ( 4 === get_current_blog_id() ) {
			return true;
		} else {
			return false;
		}
	}

	return $current_layout;
}

function stm_me_is_dealer_two() {
	$current_layout = stm_me_get_current_layout();

	if ( $current_layout ) {
		if ( 'car_dealer_two' === $current_layout ) {
			$current_layout = true;
		} else {
			$current_layout = false;
		}
	} else {
		if ( 9 === get_current_blog_id() ) {
			return true;
		} else {
			return false;
		}
	}

	return $current_layout;
}

function stm_me_is_dealer() {
	$current_layout = stm_me_get_current_layout();

	if ( $current_layout ) {
		if ( 'car_dealer' === $current_layout ) {
			$current_layout = true;
		} else {
			$current_layout = false;
		}
	} else {
		if ( 1 === get_current_blog_id() ) {
			return true;
		} else {
			return false;
		}
	}

	return $current_layout;
}

if ( ! function_exists( 'stm_me_is_listing' ) ) {
	function stm_me_is_listing( $only = array() ) {
		if ( count( $only ) > 0 ) {
			$listing = stm_me_get_current_layout();

			foreach ( $only as $layout ) {
				if ( $layout === $listing ) {
					return true;
				}
			}
		}

		return false;
	}
}

function stm_me_wp_mail( $to, $subject, $body, $headers ) {
	add_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
	wp_mail( $to, $subject, $body, $headers );
	remove_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
}

add_action( 'stm_wp_mail', 'stm_me_wp_mail', 10, 4 );

function stm_me_wp_mail_files( $to, $subject, $body, $headers, $files ) {
	add_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
	wp_mail( $to, $subject, $body, $headers, $files );
	remove_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
}

add_action( 'stm_wp_mail_files', 'stm_me_wp_mail_files', 10, 5 );

function stm_me_get_global_server_val( $val ) {
	if ( ! empty( $_SERVER[ $val ] ) ) {
		return $_SERVER[ $val ]; // phpcs:ignore WordPress.Security
	}

	return null;
}

add_filter( 'stm_get_global_server_val', 'stm_me_get_global_server_val' );

function stm_me_balance_tags( $text ) {
	return balanceTags( $text, true );
}

add_filter( 'stm_balance_tags', 'stm_me_balance_tags' );

function stm_me_rental_add_meta_box() {
	add_action( 'add_meta_boxes', 'stm_me_add_price_per_hour_metabox' );
}

function stm_me_add_price_per_hour_metabox() {
	$title = __( 'Car Rent Price Info', 'stm_motors_extends' );

	if ( get_the_ID() !== apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ) ) {
		$title = __( 'Car Rent Price Info (This fields are not editable.)', 'stm_motors_extends' );
	}

	add_meta_box(
		'price_per_hour',
		$title,
		array( 'PricePerHour', 'pricePerHourView' ),
		'product',
		'advanced',
		'high'
	);
}

add_action( 'stm_rental_meta_box', 'stm_me_rental_add_meta_box' );


// Add hidden price before user can update plugin.
function stm_add_genuine_price_hidden() {
	add_meta_box( 'stm_genuine_price', 'stm genuine price', 'stm_me_do_genuine_price_hidden', apply_filters( 'stm_listings_post_type', 'listings' ) );
}

function stm_me_do_genuine_price_hidden() {
	add_action( 'add_meta_boxes', 'stm_add_genuine_price_hidden' );
}

add_action( 'stm_do_genuine_price_hidden', 'stm_me_do_genuine_price_hidden' );

if ( ! function_exists( 'stm_paypal_url' ) ) {
	function stm_paypal_url() {
		$paypal_mode = apply_filters( 'motors_vl_get_nuxy_mod', 'sandbox', 'paypal_mode' );
		$paypal_url  = ( 'live' === $paypal_mode ) ? 'www.paypal.com' : 'www.sandbox.paypal.com';

		return $paypal_url;
	}
}

if ( ! function_exists( 'stm_check_payment' ) ) {

	function stm_check_payment( $data ) {
		if ( ! empty( $data['invoice'] ) ) {

			$invoice = $data['invoice'];

			$req = 'cmd=_notify-validate';

			foreach ( $data as $key => $value ) {
				$value = rawurlencode( stripslashes( $value ) );
				$req  .= "&$key=$value";
			}

			echo 'https://' . esc_url( stm_paypal_url() ) . '/cgi-bin/webscr';

			$ch = curl_init( 'https://' . stm_paypal_url() . '/cgi-bin/webscr' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_init
			curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_POST, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSLVERSION, 6 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt

			$res = curl_exec( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_exec

			if ( empty( $res ) ) {
				echo( 'Got ' . esc_html( curl_error( $ch ) ) . ' when processing IPN data' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_error
				curl_close( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close
				return false;
			}

			curl_close( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close

			if ( 0 === strcmp( $res, 'VERIFIED' ) ) {

				update_user_meta( intval( $invoice ), 'stm_payment_status', 'completed' );

				$member_admin_email_subject = esc_html__( 'New Payment received', 'stm_motors_extends' );
				$member_admin_email_message = esc_html__( 'User paid for submission. User ID:', 'stm_motors_extends' ) . ' ' . $invoice;

				do_action( 'stm_set_html_content_type' );

				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
				$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) ) );
				$headers  = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

				do_action( 'stm_wp_mail_files', get_bloginfo( 'admin_email' ), $member_admin_email_subject, nl2br( $member_admin_email_message ), $headers );
			}
		}
	}
}


if ( ! empty( $_GET['stm_check_membership_payment'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	header( 'HTTP/1.1 200 OK' );
	stm_check_payment( $_REQUEST ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	exit;
}

function stm_me_create_dir() {
	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$upload_dir = wp_upload_dir();

	$wp_filesystem->mkdir( $upload_dir['basedir'] . '/stm_uploads', FS_CHMOD_DIR );
}

add_action( 'stm_create_dir', 'stm_me_create_dir' );

add_filter( 'stm_modify_key_filter', 'stm_me_modify_key' );

add_filter(
	'addtoany_script_disabled',
	function () {
		$post_type = get_post_type( get_the_id() );

		$ml_inventory = false;
		if ( class_exists( 'STMMultiListing' ) ) {
			$options = get_option( 'stm_motors_listing_types', array() );
			if ( isset( $options['multilisting_repeater'] ) && ! empty( $options['multilisting_repeater'] ) ) {
				foreach ( $options['multilisting_repeater'] as $key => $listing ) {
					if ( ! empty( $listing['inventory_page'] ) && get_the_ID() === (int) $listing['inventory_page'] ) {
						$ml_inventory = true;
					}
				}
			}
		}

		if (
			is_single()
			|| ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share', true ) && (int) apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_archive', true ) === get_the_ID() || 'listings' === $post_type )
			|| ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_share', true ) && is_singular( apply_filters( 'stm_listings_post_type', 'listings' ) ) )
			|| $ml_inventory
		) {
			return false;
		}
		return true;
	},
	101,
	1
);

add_action(
	'init',
	function () {
		global $wp_locale, $wp_styles;
		if ( 'car_dealer_elementor_rtl' === get_option( 'stm_motors_chosen_template', 'car_dealer' ) || is_rtl() ) {
			$wp_locale->text_direction = 'rtl';
			if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
				$wp_styles = new WP_Styles();
			}
			$wp_styles->text_direction = 'rtl';
		}
	}
);

add_filter( 'grid_title_dependency', 'stm_grid_title_dependency' );
function stm_grid_title_dependency( $deps ) {
	$current_layout = stm_me_get_current_layout();
	if ( 'equipment' !== $current_layout ) {
		return $deps;
	}

	return array();
}

add_filter( 'features_search_dependencies', 'stm_features_dependencies' );
function stm_features_dependencies( $deps ) {
	if ( defined( 'WPB_VC_VERSION' ) ) {
		return array(
			'dependency'   => array(
				array(
					'key'   => 'listing_filter_position',
					'value' => 'left',
				),
				array(
					'key'   => 'listing_filter_position',
					'value' => 'right',
				),
			),
			'dependencies' => '||',
		);
	}

	return $deps;
}

// Disable the regeneration of fonts
add_filter(
	'wpcfto_enable_regenerate_fonts',
	function () {
		return false;
	},
	99
);

add_filter(
	'stm_get_sort_options_array',
	function ( $sorts ) {

		if ( true === apply_filters( 'stm_is_boats', false ) ) {
			unset( $sorts['mileage_low'] );
			unset( $sorts['mileage_high'] );
		}

		return $sorts;
	},
	50,
	1
);
