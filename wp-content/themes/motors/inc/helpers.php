<?php
add_action(
	'stm_remove_theme_site_meta',
	function () {
		delete_site_option( STM_TOKEN_OPTION );
		delete_site_option( 'stm_motors_chosen_template' );
		delete_site_option( 'stm_changelog_udated' );
	}
);

if ( ! function_exists( 'stm_get_layout_labels' ) ) {
	function stm_get_layout_labels() {
		$layouts = array(
			'ev_dealer'      => esc_html__( 'ELECTRIC VEHICLE DEALERSHIP', 'motors' ),
			'car_dealer'     => esc_html__( 'CAR DEALERSHIP ONE', 'motors' ),
			'car_dealer_two' => esc_html__( 'CAR DEALERSHIP TWO', 'motors' ),
			'listing'        => esc_html__( 'CLASSIFIED LISTING', 'motors' ),
			'listing_two'    => esc_html__( 'CLASSIFIED LISTING 2', 'motors' ),
			'listing_three'  => esc_html__( 'CLASSIFIED LISTING 3', 'motors' ),
			'listing_four'   => esc_html__( 'CLASSIFIED LISTING 4', 'motors' ),
			'listing_five'   => esc_html__( 'CLASSIFIED LISTING 5', 'motors' ),
			'listing_six'    => esc_html__( 'CLASSIFIED LISTING 6', 'motors' ),
			'car_rental'     => esc_html__( 'RENT A CAR SERVICE', 'motors' ),
			'motorcycle'     => esc_html__( 'MOTORCYCLES DEALERS', 'motors' ),
			'boats'          => esc_html__( 'BOATS DEALERSHIP', 'motors' ),
			'service'        => esc_html__( 'CAR REPAIR SERVICE', 'motors' ),
			'car_magazine'   => esc_html__( 'CAR MAGAZINE', 'motors' ),
			'auto_parts'     => esc_html__( 'AUTO PARTS', 'motors' ),
			'aircrafts'      => esc_html__( 'AIRCRAFTS', 'motors' ),
			'rental_two'     => esc_html__( 'RENT A CAR TWO', 'motors' ),
			'equipment'      => esc_html__( 'EQUIPMENT', 'motors' ),
		);

		return apply_filters( 'stm_get_layout_labels', $layouts );
	}
}

if ( ! function_exists( 'stm_is_use_plugin' ) ) {
	function stm_is_use_plugin( $plug ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return in_array( $plug, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plug );
	}
}

if ( ! function_exists( 'stm_is_not_use_plugin' ) ) {
	function stm_is_not_use_plugin( $plug ) {
		return ! stm_is_use_plugin( $plug );
	}
}

if ( ! function_exists( 'stm_get_header_layout' ) ) {
	function stm_get_header_layout() {
		$selected_layout = get_stm_theme_demo_layout();

		if ( empty( $selected_layout ) ) {
			return 'car_dealer';
		}

		$array_header = array(
			'service'                => 'service',
			'listing_two'            => 'listing',
			'listing_two_elementor'  => 'listing',
			'listing_three'          => 'listing',
			'listing_four'           => 'car_dealer',
			'listing_four_elementor' => 'car_dealer',
			'ev_dealer'              => 'ev_dealer',
			'auto_parts'             => 'auto_parts',
		);

		$default_header = ( ! empty( $array_header[ $selected_layout ] ) ) ? $array_header[ $selected_layout ] : $selected_layout;

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
		*/

		if ( apply_filters( 'stm_is_listing_six', false ) ) {
			return 'listing_five';
		}

		$header_layout = apply_filters( 'stm_me_get_nuxy_mod', $default_header, 'header_layout' );

		return apply_filters( 'stm_selected_header', $header_layout );
	}
}

// check for multilisting
if ( ! function_exists( 'stm_is_multilisting' ) ) {
	add_filter( 'stm_is_multilisting', 'stm_is_multilisting' );
	function stm_is_multilisting() {
		if ( defined( 'MULTILISTING_PATH' ) && class_exists( 'STMMultiListing' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

add_filter(
	'motors_vl_slide_affix',
	function ( $affix ) {
		if ( apply_filters( 'stm_is_motorcycle', false ) && function_exists( 'stm_distance_measure_unit' ) ) {
			$affix = '&nbsp;' . stm_distance_measure_unit();
		}

		return $affix;
	}
);

// we've made the listing price field dynamic, this function checks if the given option is the price field.
if ( ! function_exists( 'stm_is_listing_price_field' ) ) {
	add_filter( 'stm_is_listing_price_field', 'stm_is_listing_price_field', 10, 2 );
	function stm_is_listing_price_field( $default, $field = false ) {

		if ( false === $field ) {
			return false;
		}

		// check the default listing type price field.
		if ( 'price' === $field ) {
			return true;
		}

		// check for multilisting fields.
		if ( stm_is_multilisting() ) {
			$opts  = array();
			$slugs = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				foreach ( $slugs as $slug ) {
					$type_options = get_option( "stm_{$slug}_options", array() );
					if ( ! empty( $type_options ) ) {
						$opts = array_merge( $opts, $type_options );
					}
				}

				if ( ! empty( $opts ) ) {
					$arr_key = array_search( $field, array_column( $opts, 'slug' ), true );
					if ( false !== $arr_key ) {
						if ( ! empty( $opts[ $arr_key ]['listing_price_field'] ) && 1 === $opts[ $arr_key ]['listing_price_field'] ) {
							return true;
						}
					}
				}
			}
		}

		return false;
	}
}


// get multilisting post types (array of post types) including/excluding default "listings" post type.
if ( ! function_exists( 'stm_listings_multi_type' ) ) {

	function stm_listings_multi_type( $post_types ) {
		if ( stm_is_multilisting() ) {
			$types = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $types ) ) {
				$post_types = array_merge( $post_types, $types );
			}
		}

		return $post_types;
	}

	add_filter( 'stm_listings_multi_type', 'stm_listings_multi_type' );
}


// get multilisting post types (associative array of slug => label pairs) including/excluding default "listings" post type.
if ( ! function_exists( 'stm_listings_multi_type_labeled' ) ) {

	function stm_listings_multi_type_labeled( $include_default = false ) {
		$post_types = array();

		if ( $include_default ) {
			$post_types[ apply_filters( 'stm_listings_post_type', 'listings' ) ] = esc_html__( 'Listings', 'motors' );
		}

		if ( stm_is_multilisting() ) {
			$types = STMMultiListing::stm_get_listings();
			if ( ! empty( $types ) ) {
				foreach ( $types as $key => $listing ) {
					$post_types[ $listing['slug'] ] = esc_html( $listing['label'] );
				}
			}
		}

		return apply_filters( 'stm_listings_multi_type_labeled', $post_types );
	}
}

// get all listing attributes.
if ( ! function_exists( 'stm_get_all_listing_attributes' ) ) {
	function stm_get_all_listing_attributes( $default, $filter = 'all' ) {
		$multilisting_attrs = array();
		$attributes         = array();

		// default attributes
		$default_attrs = get_option( 'stm_vehicle_listing_options', array() );

		// get multilisting attributes, if MLT is active
		if ( stm_is_multilisting() && ( 'all' === $filter || 'multilisting' === $filter ) ) {
			$slugs = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				foreach ( $slugs as $slug ) {
					$type_options = get_option( "stm_{$slug}_options", array() );
					if ( ! empty( $type_options ) ) {
						$multilisting_attrs = array_merge( $multilisting_attrs, $type_options );
					}
				}
			}
		}

		if ( 'all' === $filter ) {
			$attributes = array_merge( $default_attrs, $multilisting_attrs );
		} elseif ( 'multilisting' === $filter ) {
			$attributes = $multilisting_attrs;
		} else {
			$attributes = $default_attrs;
		}

		return $attributes;
	}

	add_filter( 'stm_get_all_listing_attributes', 'stm_get_all_listing_attributes' );
}


// check "mark cars as sold" feature
if ( ! function_exists( 'stm_sold_status_enabled' ) ) {
	add_filter( 'stm_sold_status_enabled', 'stm_sold_status_enabled' );
	function stm_sold_status_enabled() {
		if ( apply_filters( 'stm_is_auto_parts', false ) || apply_filters( 'stm_is_magazine', false ) || apply_filters( 'stm_is_rental', false ) || apply_filters( 'stm_is_rental_one_elementor', false ) || apply_filters( 'stm_is_rental_two', false ) || apply_filters( 'stm_is_service', false ) ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'stm_get_current_layout' ) ) {
	function stm_get_current_layout() {
		$layout = get_stm_theme_demo_layout();

		if ( empty( $layout ) ) {
			$layout = 'car_dealer';
		}

		return $layout;
	}
}


// sell car online, only for Dealership Two layout
if ( ! function_exists( 'stm_ajax_buy_car_online' ) ) {
	if ( apply_filters( 'stm_is_dealer_two', false ) ) {
		add_action( 'wp_ajax_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );
		add_action( 'wp_ajax_nopriv_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );
	}

	function stm_ajax_buy_car_online() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array( 'status' => 'Error' );

		$car_id = intval( filter_var( wp_unslash( $_POST['car_id'] ), FILTER_SANITIZE_NUMBER_INT ) );
		$price  = floatval( filter_var( wp_unslash( $_POST['price'] ), FILTER_SANITIZE_NUMBER_FLOAT ) );

		if ( ! empty( $car_id ) && ! empty( $price ) ) {
			if ( class_exists( 'WooCommerce' ) && apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' ) ) {

				update_post_meta( $car_id, '_price', $price );
				update_post_meta( $car_id, 'is_sell_online_status', 'in_cart' );

				$checkout_url = wc_get_checkout_url() . '?add-to-cart=' . $car_id;

				$response = array(
					'status'       => 'success',
					'redirect_url' => $checkout_url,
				);

				wp_send_json( $response );
			}
		}

		wp_send_json( $response );
	}
}


if ( function_exists( 'stm_dt_before_create_order' ) ) {
	if ( apply_filters( 'stm_is_dealer_two', false ) ) {
		add_action( 'woocommerce_checkout_update_order_meta', 'stm_dt_before_create_order', 200, 2 );
	}

	function stm_dt_before_create_order( $order_id, $data ) {
		$cart = WC()->cart->get_cart();

		foreach ( $cart as $cart_item ) {
			$id          = $cart_item['product_id'];
			$post_object = get_post( $cart_item['product_id'] );

			if ( 'product' === $post_object->post_type || 'car_option' === $post_object->post_type ) {
				continue;
			}

			if ( ! empty( get_post_meta( $id, 'is_sell_online_on_checkout', true ) ) ) {
				update_post_meta( $order_id, 'order_sell_online_car_id', $id );
			}
		}

		return true;
	}
}

if ( ! defined( 'STM_MOTORS_EXTENDS_PLUGIN_VERSION' ) && ! function_exists( 'stm_motors_get_wpcfto_icon' ) ) {
	add_filter( 'stm_me_get_wpcfto_icon', 'stm_motors_get_wpcfto_icon', 10, 3 );
	function stm_motors_get_wpcfto_icon( $option_name, $default_icon, $other_classes = '' ) {
		return '<i class="' . esc_attr( $default_icon . ' ' . $other_classes ) . '"></i>';
	}
}

if ( ! function_exists( 'stm_me_get_wpcfto_icon' ) ) {
	function stm_me_get_wpcfto_icon( $opt_name, $default, $other_classes = '' ) {
		return apply_filters( 'stm_me_get_wpcfto_icon', $opt_name, $default, $other_classes );
	}
}

// for developer and QA use only!!! generates random view and phone reveal stats data for testing purposes
function stm_generate_random_listing_stats_data() {
	$start_thirty = strtotime( gmdate( 'Y-m-d', strtotime( '-32 days', time() ) ) );
	$end_thirty   = strtotime( gmdate( 'Y-m-d' ) );

	$week_days = array();
	for ( $i = $start_thirty; $i <= $end_thirty; $i = $i + 86400 ) {
		$day_number = gmdate( 'Y-m-d', $i );
		array_push( $week_days, $day_number );
	}

	$posts = get_posts(
		array(
			'post_type'   => 'listings',
			'numberposts' => - 1,
		)
	);

	foreach ( $posts as $post ) {
		foreach ( $week_days as $date ) {
			$view_count  = wp_rand( 1, 3 );
			$phone_count = wp_rand( 1, 3 );
			update_post_meta( $post->ID, 'phone_reveals_stat_' . $date, $phone_count );
			update_post_meta( $post->ID, 'stm_phone_reveals', $phone_count );

			update_post_meta( $post->ID, 'car_views_stat_' . $date, $view_count );
			update_post_meta( $post->ID, 'stm_car_views', $view_count );
		}
	}
}

if ( ! function_exists( 'stm_compare_cookie_name_prefix' ) ) {
	// compare cookie name
	function stm_compare_cookie_name_prefix() {
		$name = 'stm' . get_current_blog_id() . '_compare_';

		return apply_filters( 'stm_compare_cookie_name_prefix', $name );
	}
}


if ( ! function_exists( 'motors_frontend_javascript_variables' ) ) {
	add_action( 'wp_footer', 'motors_frontend_javascript_variables' );
	function motors_frontend_javascript_variables() {
		$locale                     = explode( '_', get_locale() );
		$stm_security_nonce         = wp_create_nonce( 'stm_security_nonce' );
		$compare_cookie_prefix      = stm_compare_cookie_name_prefix();
		$allow_dealers_add_category = '';

		if ( apply_filters( 'stm_get_user_role', false ) && apply_filters( 'motors_vl_get_nuxy_mod', false, 'allow_dealer_add_new_category' ) ) {
			$allow_dealers_add_category = '1';
		}

		$listing_types       = stm_listings_multi_type( true );
		$compare_init_object = array();
		foreach ( $listing_types as $slug ) {
			$compare_init_object[ $slug ] = apply_filters( 'stm_get_compared_items', array(), $slug );
		}
		//phpcs:disable
		?>
		<script>
            var stm_security_nonce = '<?php echo esc_js( $stm_security_nonce ); ?>';
            var stm_motors_current_ajax_url = '<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>';
            var resetAllTxt = '<?php echo esc_html__( 'Reset All', 'motors' ); ?>';
            var prevText = '<?php echo esc_html__( 'Previous', 'motors' ); ?>';
            var nextText = '<?php echo esc_html__( 'Next', 'motors' ); ?>';
            var is_rental = '<?php echo ( apply_filters( 'stm_is_rental', false ) ) ? 'true' : 'false'; ?>';
            var file_type = '<?php echo esc_html__( 'file type noimg', 'motors' ); ?>';
            var file_size = '<?php echo esc_html__( 'file size big', 'motors' ); ?>';
            var max_img_quant = '<?php echo esc_html__( 'max imgs 3', 'motors' ); ?>';
            var currentLocale = '<?php echo esc_html( $locale[0] ); ?>';
            var noFoundSelect2 = '<?php echo esc_html__( 'No results found', 'motors' ); ?>';
            var stm_login_to_see_plans = '<?php echo esc_html__( 'Please, log in to view your available plans', 'motors' ); ?>';
            var compare_init_object = <?php echo wp_json_encode( $compare_init_object ); ?>;
            var ajax_url = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
            var cc_prefix = '<?php echo esc_js( $compare_cookie_prefix ); ?>';
		</script>
		<?php
		//phpcs:enable
	}
}


add_action( 'admin_head', 'stm_backend_javascript_variables' );
function stm_backend_javascript_variables() {
	$admin_patch_price        = wp_create_nonce( 'stm_admin_patch_price' );
	$admin_patch_location     = wp_create_nonce( 'stm_admin_patch_location' );
	$admin_patch_category_img = wp_create_nonce( 'stm_admin_patch_cat_image' );
	$install_plugin           = wp_create_nonce( 'motors_install_plugin' );
	$close_ad_popup           = wp_create_nonce( 'motors_ad_popup' );
	$stm_ajax_add_review      = wp_create_nonce( 'stm_ajax_add_review' );
	$stm_security_nonce       = wp_create_nonce( 'stm_security_nonce' );
	//phpcs:disable
	?>
	<script>
        var adminPatchPrice = '<?php echo esc_js( $admin_patch_price ); ?>';
        var adminPatchLocation = '<?php echo esc_js( $admin_patch_location ); ?>';
        var adminPatchCatImg = '<?php echo esc_js( $admin_patch_category_img ); ?>';
        var installPlugin = '<?php echo esc_js( $install_plugin ); ?>';
        var closeAddvPopup = '<?php echo esc_js( $close_ad_popup ); ?>';
        var stm_ajax_add_review = '<?php echo esc_js( $stm_ajax_add_review ); ?>';
        var stm_security_nonce = '<?php echo esc_js( $stm_security_nonce ); ?>';
	</script>
	<?php
	//phpcs:endable
}

// get gallery image URLs for interactive hoverable gallery
if ( ! function_exists( 'stm_get_hoverable_thumbs' ) ) {
	function stm_get_hoverable_thumbs( $returned_value, $listing_id, $thumb_size = 'thumbnail' ) {
		$ids   = array_unique( (array) get_post_meta( $listing_id, 'gallery', true ) );
		$count = 0;

		// push featured image id
		if ( has_post_thumbnail( $listing_id ) && ! in_array( get_post_thumbnail_id( $listing_id ), $ids, true ) ) {
			array_unshift( $ids, get_post_thumbnail_id( $listing_id ) );
		}

		$returned_value = array(
			'gallery'   => array(),
			'ids'       => array(),
			'remaining' => 0,
		);

		$ids = array_filter( $ids );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $attachment_id ) {
				// only first five images!
				if ( $count >= 5 ) {
					continue;
				}

				$img = wp_get_attachment_image_url( $attachment_id, $thumb_size );

				if ( ! empty( $img ) ) {
					if ( has_image_size( $thumb_size . '-x-2' ) ) {
						$imgs   = array();
						$imgs[] = $img;
						$imgs[] = wp_get_attachment_image_url( $attachment_id, $thumb_size . '-x-2' );
						$img    = $imgs;
					}

					array_push( $returned_value['gallery'], $img );
					array_push( $returned_value['ids'], $attachment_id );
					$count ++;
				}
			}
		}

		// get remaining count of gallery images
		$remaining                   = count( $ids ) - count( $returned_value['gallery'] );
		$returned_value['remaining'] = ( 0 <= $remaining ) ? $remaining : 0;

		return $returned_value;
	}

	add_filter( 'stm_get_hoverable_thumbs', 'stm_get_hoverable_thumbs', 10, 3 );
}

if ( ! function_exists( 'motors_render_elementor_content' ) && class_exists( \Elementor\Plugin::class ) ) {
	add_filter( 'motors_render_elementor_content', 'motors_render_elementor_content' );
	function motors_render_elementor_content( $post_id ) {
		$template_listing = get_post( $post_id );
		setup_postdata( $template_listing );
		//phpcs:ignore
		echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_listing->ID );
		wp_reset_postdata();
	}
}

if ( ! function_exists( 'stm_get_default_color' ) ) {
	function stm_get_default_color( $layout, $color_key ) {
		$default_colors = array(
			'listing'                  => array(
				'site_style_base_color'              => '#1bc744',
				'site_style_secondary_color'         => '#153e4d',
				'site_style_base_color_listing'      => '#1bc744',
				'site_style_secondary_color_listing' => '#153e4d',
			),
			'listing_two'              => array(
				'site_style_base_color'              => '#4971ff',
				'site_style_secondary_color'         => '#ffb129',
				'site_style_base_color_listing'      => '#3350b8',
				'site_style_secondary_color_listing' => '#ffb100',
			),
			'listing_two_elementor'    => array(
				'site_style_base_color'              => '#4971ff',
				'site_style_secondary_color'         => '#ffb129',
				'site_style_base_color_listing'      => '#3350b8',
				'site_style_secondary_color_listing' => '#ffb100',
			),
			'listing_three'            => array(
				'site_style_base_color'              => '#4971ff',
				'site_style_secondary_color'         => '#ffb129',
				'site_style_base_color_listing'      => '#3350b8',
				'site_style_secondary_color_listing' => '#ffb100',
			),
			'listing_four'             => array(
				'site_style_base_color'              => '#183650',
				'site_style_secondary_color'         => '#6c98e1',
				'site_style_base_color_listing'      => '#2289e2',
				'site_style_secondary_color_listing' => '#2289e2',
			),
			'listing_four_elementor'   => array(
				'site_style_base_color'              => '#183650',
				'site_style_secondary_color'         => '#6c98e1',
				'site_style_base_color_listing'      => '#2289e2',
				'site_style_secondary_color_listing' => '#2289e2',
			),
			'listing_five'             => array(
				'site_style_base_color'      => '#183650',
				'site_style_secondary_color' => '#34ccff',
			),
			'listing_five_elementor'   => array(
				'site_style_base_color'      => '#183650',
				'site_style_secondary_color' => '#34ccff',
			),
			'listing_one_elementor'    => array(
				'site_style_base_color'              => '#1bc744',
				'site_style_secondary_color'         => '#153e4d',
				'site_style_base_color_listing'      => '#1bc744',
				'site_style_secondary_color_listing' => '#153e4d',
			),
			'motorcycle'               => array(
				'site_style_base_color'      => '#df1d1d',
				'site_style_secondary_color' => '#2f3c40',
			),
			'car_rental'               => array(
				'site_style_base_color'      => '#f0c540',
				'site_style_secondary_color' => '#2a4045',
			),
			'car_rental_elementor'     => array(
				'site_style_base_color'      => '#f0c540',
				'site_style_secondary_color' => '#2a4045',
			),
			'car_dealer_elementor'     => array(
				'site_style_base_color'      => '#cc6119',
				'site_style_secondary_color' => '#6c98e1',
			),
			'car_dealer_elementor_rtl' => array(
				'site_style_base_color'      => '#cc6119',
				'site_style_secondary_color' => '#6c98e1',
			),
			'car_dealer'               => array(
				'site_style_base_color'      => '#cc6119',
				'site_style_secondary_color' => '#6c98e1',
			),
			'service'                  => array(
				'site_style_base_color'      => '#183650',
				'site_style_secondary_color' => '#34ccff',
			),
			'ev_dealer'                => array(
				'site_style_base_color'      => '#0d46ff',
				'site_style_secondary_color' => '#0d46ff',
			),
			'car_magazine'             => array(
				'site_style_base_color'      => '#18ca3e',
				'site_style_secondary_color' => '#3c98ff',
			),
			'car_dealer_two'           => array(
				'site_style_base_color'              => '#4971ff',
				'site_style_secondary_color'         => '#ffb129',
				'site_style_base_color_listing'      => '#3350b8',
				'site_style_secondary_color_listing' => '#ffb100',
			),
			'auto_parts'               => array(
				'site_style_base_color'              => '#cc6119',
				'site_style_secondary_color'         => '#6c98e1',
				'site_style_base_color_listing'      => '#cc6119',
				'site_style_secondary_color_listing' => '#cc6119',
			),
			'aircrafts'                => array(
				'site_style_base_color'              => '#6c98e1',
				'site_style_secondary_color'         => '#cc6119',
				'site_style_base_color_listing'      => '#4c94fa',
				'site_style_secondary_color_listing' => '#ff9420',
			),
			'rental_two'               => array(
				'site_style_base_color'      => '#6c98e1',
				'site_style_secondary_color' => '#cc6119',
			),
			'equipment'                => array(
				'site_style_base_color'      => '#fab637',
				'site_style_secondary_color' => '#cc6119',
			),
			'boats'                    => array(
				'site_style_base_color'         => '#31a3c6',
				'site_style_secondary_color'    => '#ceac61',
				'site_style_base_color_listing' => '#002568',
			),
		);

		$default_colors = apply_filters( 'stm_get_default_color', $default_colors );

		if ( ! isset( $default_colors[ $layout ] ) ) {
			return '#1bc744';
		}

		if ( ! isset( $default_colors[ $layout ][ $color_key ] ) ) {
			return $default_colors[ $layout ]['site_style_base_color'];
		}

		return $default_colors[ $layout ][ $color_key ];
	}
}

if ( ! function_exists( 'stm_get_theme_color' ) ) {
	function stm_get_theme_color( $color_key ) {
		$site_color_style = apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' );
		if ( 'site_style_default' === $site_color_style ) {
			$layout = stm_get_current_layout();

			return stm_get_default_color( $layout, $color_key );
		}

		return apply_filters( 'stm_me_get_nuxy_mod', '', $color_key );

	}
}

if ( ! function_exists( 'stm_get_date_format' ) ) {
	function stm_get_date_format(): string {
		$dFormat = get_option( 'date_format' );
		$tFormat = get_option( 'time_format' );

		return $dFormat . ' ' . $tFormat;
	}
}

if ( ! function_exists( 'stm_get_clear_date_format' ) ) {
	function stm_get_clear_date_format( $format = '', $remove_am_pm = false ): string {
		if ( empty( $format ) ) {
			$format = stm_get_date_format();
		}

		$letters = array(
			' :s',
			':s ',
			':s',
			' s',
			's ',
			's',
			' :S',
			':S ',
			':S',
			' S',
			'S ',
			'S',
		);

		if ( $remove_am_pm ) {
			$letters = array_merge(
				array(
					' :a',
					':a ',
					':a',
					' a',
					'a ',
					'a',
					' :A',
					':A ',
					':A',
					' A',
					'A ',
					'A',
				),
				$letters
			);
		}

		foreach ( $letters as $letter ) {
			$format = str_replace( $letter, '', $format );
		}

		return $format;
	}
}

if ( ! function_exists( 'stm_date_create_from_format' ) ) {
	function stm_date_create_from_format( $date, $format = '' ) {
		if ( is_array( $date ) && ! empty( $date ) ) {
			$date = $date[0];
		}

		if ( $date instanceof DateTime ) {
			return $date;
		}

		if ( empty( $date ) ) {
			return false;
		}

		if ( empty( $format ) ) {
			$format = 'm d, Y H:i';
		}

		$date = trim( urldecode( $date ) );

		$find_date = preg_match( '/([0-9,*\/ :.-]+)/', $date, $date );
		if ( empty( $find_date ) ) {
			return false;
		}

		$format = str_replace( 'F', 'm', $format );
		$format = str_replace( 'j', 'd', $format );

		return date_create_from_format( $format, trim( $date[0] ) );
	}
}

if ( ! function_exists( 'stm_remove_pickup_return_cookie' ) ) {
	function stm_remove_pickup_return_cookie(): void {
		if ( ! empty( $_COOKIE ) ) {
			$blog_id      = get_current_blog_id();
			$blog_pattern = '/' . $blog_id . '/';
			foreach ( $_COOKIE as $cookie => $value ) {
				if ( ( strpos( $cookie, 'pickup_date' ) || strpos( $cookie, 'return_date' ) ) && preg_match( $blog_pattern, $cookie ) ) {
					setcookie( $cookie, '', time() - 3600 );
				}
			}
		}
	}
}

/*Boats hooks*/
add_filter(
	'already_added_to_compare',
	function ( $text ) {
		if ( true === apply_filters( 'stm_is_boats', false ) ) {
			return esc_html__( 'You have already added 3 boats', 'motors' );
		}

		return $text;
	}
);

add_filter(
	'stm_select_sorting_options',
	function ( $sort_args ) {
		if ( true === apply_filters( 'stm_is_boats', false ) ) {
			unset( $sort_args['mileage_low'] );
			unset( $sort_args['mileage_high'] );
		}

		return $sort_args;
	}
);

//open tags for HFE header
add_action( 'hfe_header', 'hfe_open_tags' );
function hfe_open_tags() {
	echo wp_kses_post( '<div>' );
}

//close tags for HFE footer
add_action( 'hfe_footer', 'hfe_close_tags' );
function hfe_close_tags() {
	echo wp_kses_post( '</div></div>' );
}

if ( ! function_exists( 'stm_getCurrencySelectorHtml' ) ) {
	function stm_getCurrencySelectorHtml() {
		if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_currency_enable' ) ) {
			echo apply_filters( 'output_multiple_currency_html', '' );
		}
	}

	add_filter( 'stm_get_currency_selector_html', 'stm_getCurrencySelectorHtml' );
}

//update selected Currency value in Cookie on Motors Settings update
function wpcfto_settings_update_currency( $id, $settings ){
	if ( 'mvl_search_results_settings' === $id && ! empty( $settings['price_currency'] ) && isset( $_COOKIE['stm_current_currency'] ) ) {
		$cookie_data = explode( '-', $_COOKIE['stm_current_currency'] );
		$selected_currency = $cookie_data[0];
		$old_value = apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' );
		if ( $selected_currency === $old_value && $settings['price_currency'] !== $old_value ) {
			setcookie( 'stm_current_currency', $settings['price_currency'] . '-1', time() + ( 86400 * 7 ), '/' );
		}
	}
}
add_action( 'wpcfto_settings_saved', 'wpcfto_settings_update_currency', 10, 2 );
