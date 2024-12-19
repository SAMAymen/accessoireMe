<?php
// phpcs:disable
if ( ! apply_filters( 'stm_is_rental_two', false ) ) {
	require_once 'rental/vehicles_listing_rental_extends.php';
}

function stm_get_empty_placeholder( $empty = false ) {
	$symbol = '--';
	if ( $empty ) {
		$symbol = '';
	}
	return apply_filters( 'stm_get_empty_placeholder', $symbol );
}

if ( ! stm_is_woocommerce_activated() ) {
	function stm_get_rental_order_fields_values( $empty = false ) {
		$d_format = get_option( 'date_format' );
		$t_format = get_option( 'time_format' );

		$t_format = str_replace( 'g', 'hh', $t_format ); //moment.js didn't work with 'g'
		$t_format = str_replace( 'i', 'mm', $t_format ); //moment.js didn't work with 'i'

		$d_format = str_replace( 'm', 'MM', $d_format );
		$d_format = str_replace( 'd', 'DD', $d_format );
		$d_format = str_replace( 'F', 'MM', $d_format );
		$d_format = str_replace( 'j', 'DD', $d_format );

		$date_time_format = $d_format . ' ' . $t_format;

		return array(
			'pickup_location_id' => '',
			'pickup_location'    => '',
			'pickup_date'        => '',
			'calc_pickup_date'   => '',
			'return_date'        => '',
			'calc_return_date'   => '',
			'return_location_id' => '',
			'return_location'    => '',
			'return_same'        => '',
			'order_days'         => 0,
			'order_hours'        => 0,
			'ceil_days'          => 0,
			'format'             => stm_get_clear_date_format(),
			'moment_format'      => stm_get_clear_date_format( $date_time_format ),
		);
	}

	function stm_get_cart_items() {
		return array(
			'has_car'      => false,
			'option_total' => 0,
			'options_list' => array(),
			'car_class'    => array(),
			'options'      => array(),
			'total'        => 0,
			'option_ids'   => array(),
			'oldData'      => 0,
		);
	}

	function stm_rental_billing_info() {
		return array(
			'first_name' => '',
			'last_name'  => '',
			'email'      => '',
			'payment'    => '',
			'total'      => '',
		);
	}

	return;
} else {
	require_once 'rental/PricePerHour.php';
	require_once 'rental/PriceForDatePeriod.php';
	if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_fixed_price_for_days' ) ) {
		require_once 'rental/PriceForQuantityDays.php';
	} else {
		require_once 'rental/DiscountByDays.php';
	}

	/**
	 * Register the custom product type after init
	 */
	function stm_register_car_option_product_type() {
		/**
		 * This should be in its own separate file.
		 */
		class WC_Product_Car_Option extends WC_Product_Simple {

			/**
			 * Get internal type.
			 *
			 * @return string
			 */
			public function get_type() {
				return 'car_option';
			}

		}

	}

	add_action( 'init', 'stm_register_car_option_product_type' );

	function stm_save_selected_offices( $post_id ) {
		if ( $post_id != apply_filters( 'stm_get_wpml_product_parent_id', $post_id ) ) {
			$selected_offices = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', $post_id ), 'stm_rental_office', true );
			update_post_meta( $post_id, 'stm_rental_office', $selected_offices );
		}
	}
	add_action( 'motors_butterbean_save_post', 'stm_save_selected_offices' );

	function stm_add_car_option_product( $types ) {
		// Key should be exactly the same as in the class product_type parameter
		$types['car_option'] = __( 'Car Option', 'motors' );

		return $types;

	}

	add_filter( 'product_type_selector', 'stm_add_car_option_product' );

	/**
	 * Show pricing fields for simple_rental product.
	 */
	function stm_car_option_custom_js() {
		if ( 'product' !== get_post_type() ) :
			return;
		endif;

		?>
		<script>
			jQuery(document).ready(function ($) {
				$('.options_group.pricing, .options_group ._manage_stock_field').addClass('show_if_car_option').show();
				$('.general_options.general_tab, ' +
					'.inventory_options.inventory_tab ').show();
			});
		</script>
		<?php
	}

	add_action( 'admin_footer', 'stm_car_option_custom_js' );

	function stm_get_cart_current_total() {
		if ( ! is_admin() && ! empty( WC()->cart ) && ! empty( WC()->cart->get_total() ) ) {
			return apply_filters( 'stm_rent_current_total', WC()->cart->get_total() );
		}

		return 0;
	}

	function stm_get_cart_items() {
		$total_sum             = stm_get_cart_current_total();
		$fields                = stm_get_rental_order_fields_values();
		$cart                  = ( ! empty( WC()->cart ) && ! empty( WC()->cart->get_cart() ) ) ? WC()->cart->get_cart() : '';
		$coupons               = WC()->cart ? WC()->cart->get_applied_coupons() : array();
		$total_discount_amount = 0;

		if ( ! empty( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				$coupon        = new WC_Coupon( $coupon );
				$discount_type = $coupon->get_discount_type();
				$coupon_amount = $coupon->get_amount();

				if ( 'percent' === $discount_type ) {
					$discount = ( floatval( $coupon_amount ) / 100 ) * WC()->cart->subtotal;
				} else {
					$discount = floatval( $coupon_amount );
				}
				$total_discount_amount += $discount;
			}
		}

		$cart_items = array(
			'has_car'      => false,
			'option_total' => 0,
			'options_list' => array(),
			'car_class'    => array(),
			'options'      => array(),
			'total'        => $total_sum,
			'option_ids'   => array(),
			'oldData'      => 0,
			'coupon_code'  => $coupons,
			'coupon'       => $total_discount_amount,
		);

		if ( ! empty( $cart ) ) {
			$cart_old_data = ( isset( $_GET['order_old_days'] ) && ! empty( intval( $_GET['order_old_days'] ) ) ) ? intval( $_GET['order_old_days'] ) : 0;
			foreach ( $cart as $cart_item ) {
				$id   = apply_filters( 'stm_get_wpml_product_parent_id', $cart_item['product_id'] );
				$post = $cart_item['data'];

				$buy_type = ( 'WC_Product_Car_Option' === get_class( $cart_item['data'] ) ) ? 'options' : 'car_class';

				if ( 'options' === $buy_type ) {
					$cart_item_quantity = $cart_item['quantity'];
					if ( $cart_old_data > 0 ) {
						if ( 1 !== $cart_item['quantity'] ) {
							$cart_item_quantity = ( $cart_item['quantity'] / $cart_old_data );
						} else {
							$cart_item_quantity = 1;
						}
					}

					$price_data = $cart_item['data']->get_data();
					$price      = $price_data['price'];

					if ( empty( $price ) ) {
						$price = 0;
					}
					if ( empty( get_post_meta( $cart_item['product_id'], '_car_option', true ) ) ) {
						$total = $cart_item_quantity * $price * $fields['ceil_days'];
					} else {
						$total = $cart_item_quantity * $price;
					}

					$cart_items['option_total'] += $total;
					$cart_items['option_ids'][]  = $id;

					$cart_items[ $buy_type ][] = array(
						'id'       => $id,
						'quantity' => $cart_item_quantity,
						'name'     => $post->get_title(),
						'price'    => $price,
						'total'    => $total,
						'opt_days' => $fields['ceil_days'],
						'subname'  => get_post_meta( apply_filters( 'stm_get_wpml_product_child_id', $id ), 'cars_info', true ),
					);

					$cart_items['options_list'][ $id ]    = $post->get_title();
					$cart_items['option_quantity'][ $id ] = $cart_item_quantity;
				} else {

					$variation_id = 0;
					if ( ! empty( $cart_item['variation_id'] ) ) {
						$variation_id = apply_filters( 'stm_get_wpml_product_parent_id', $cart_item['variation_id'] );
					}

					if ( isset( $_GET['pickup_location'] ) ) {
						$pickup_location_meta = get_post_meta( $id, 'stm_rental_office' );
						if ( ! in_array( $_GET['pickup_location'], explode( ',', $pickup_location_meta[0] ), true ) ) {
							WC()->cart->empty_cart();
						}
					}

					$price_string = $cart_item['data']->get_data();
					$price        = $price_string['price'];

					if ( empty( $price ) ) {
						$price = 0;
					}

					$cart_items[ $buy_type ][] = array(
						'id'             => $id,
						'variation_id'   => $variation_id,
						'quantity'       => $cart_item['quantity'],
						'name'           => $post->get_title(),
						'price'          => $price,
						'total'          => (int) $fields['order_days'] * $price, // this may be changed in hook below.
						'subtotal'       => (int) $fields['order_days'] * $price,
						'subname'        => get_post_meta( apply_filters( 'stm_get_wpml_product_child_id', $id ), 'cars_info', true ),
						'payment_method' => get_post_meta( $variation_id, '_stm_payment_method', true ),
						'days'           => (int) $fields['order_days'],
						'ceil_days'      => (int) $fields['ceil_days'],
						'oldData'        => $cart_old_data,
					);

					$cart_items['has_car'] = true;
				}
			}

			/*Get only last element*/
			if ( count( $cart_items['car_class'] ) > 1 ) {
				$rent                       = array_pop( $cart_items['car_class'] );
				$cart_items['delete_items'] = $cart_items['car_class'];
				$cart_items['car_class']    = $rent;
			} else {
				if ( ! empty( $cart_items['car_class'] ) ) {
					$cart_items['car_class'] = $cart_items['car_class'][0];
				}
			}
		}
		return apply_filters( 'stm_cart_items_content', $cart_items );
	}

	/*Remove last car everytime another one added*/
	add_action( 'template_redirect', 'stm_rental_remove_car_from_cart' );

	function stm_rental_remove_car_from_cart() {
		/*This code is only for car reservation. Redirect on date reservation if not selected yet. BEGIN*/
		$rental_datepick = apply_filters( 'stm_me_get_nuxy_mod', false, 'rental_datepick' );

		if ( ! empty( $rental_datepick ) && is_checkout() && ! stm_check_rental_date_validation() ) {
			wp_safe_redirect( get_permalink( $rental_datepick ) );
			exit;
		}

		/*This code is only for car reservation. Redirect on date reservation if not selected yet. END*/
		$items = stm_get_cart_items();
		$ids   = array();

		if ( ! empty( $_GET['remove-from-cart'] ) ) {
			$items['delete_items'][] = array(
				'id' => intval( $_GET['remove-from-cart'] ),
			);
		}

		if ( ! empty( $items['delete_items'] ) ) {
			foreach ( $items['delete_items'] as $product ) {
				$ids[] = $product['id'];
			}

			$woo_commerce = WC();

			foreach ( $woo_commerce->cart->get_cart() as $cart_item_key => $cart_item ) {
				// Check to see if IDs match
				if ( in_array( $cart_item['product_id'], $ids, true ) ) {
					$woo_commerce->cart->set_quantity( $cart_item_key, 0, true );
					break;
				}
			}
		}
	}

	/*Add quantity equal to days*/
	add_action( 'template_redirect', 'stm_rental_add_quantity_to_cart' );

	function stm_rental_add_quantity_to_cart() {
		$items = stm_get_cart_items();
		$items = $items['car_class'];

		if ( ! empty( $items ) ) {
			$days = 1;

			if ( ! empty( $items['ceil_days'] ) ) {
				$days = $items['ceil_days'];
			}

			$woo_commerce = WC();
			$cart         = $woo_commerce->cart->get_cart();
			$keys         = array_keys( $cart );
			$keys_count   = count( $keys );

			for ( $q = 0; $q < $keys_count; $q++ ) {

				$quant = $cart[ $keys[ $q ] ]['quantity'];
				if ( ( ! empty( $_GET['add-to-cart'] ) && $_GET['add-to-cart'] === $cart[ $keys[ $q ] ]['product_id'] || isset( $items['oldData'] ) && $items['oldData'] > 0 ) && 'variation' !== $cart[ $keys[ $q ] ]['data']->get_type() ) {
					if ( ! empty( $items['options'] ) ) {
						if ( $items['oldData'] > 0 ) {
							$quant = ( $cart[ $keys[ $q ] ]['quantity'] / $items['oldData'] ) * $days;
						} else {
							$quant = $cart[ $keys[ $q ] ]['quantity'] * $days;
						}
					}

					unset( $_GET['order_old_days'] );
				}

				if ( 'car_option' === $cart[ $keys[ $q ] ]['data']->get_type() ) {
					$single_pay = get_post_meta( $cart[ $keys[ $q ] ]['data']->get_ID(), '_car_option', true );
					if ( empty( $single_pay ) ) {
						$quant = $cart[ $keys[ $q ] ]['quantity'];
					}
				}

				if ( 'variation' === $cart[ $keys[ $q ] ]['data']->get_type() || 'simple' === $cart[ $keys[ $q ] ]['data']->get_type() ) {
					$quant = 1;
				}

				$woo_commerce->cart->set_quantity( $keys[ $q ], $quant, true );
			}
		}
	}


	// Remove Car Options from main shop
	function stm_remove_car_options_from_query( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {

			$tax_query = array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'car_option',
					'operator' => 'NOT IN',
				),
			);

			$query->set( 'tax_query', $tax_query );

			if ( apply_filters( 'stm_is_rental_two', false ) ) {
				if ( ! empty( $_GET ) ) {
					$tax_query = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => 'car_option',
							'operator' => 'NOT IN',
						),
					);

					foreach ( $_GET as $k => $val ) {
						if ( strpos( $k, 'filter_' ) !== false ) {
							$tax_query[ count( $tax_query ) ] = array(
								'taxonomy' => str_replace( 'filter_', 'pa_', $k ),
								'field'    => 'slug',
								'terms'    => $val,
							);
						}
					}

					$query->set( 'tax_query', $tax_query );
				}
			}
		}

		$pl = 'stm_pickup_location_' . get_current_blog_id();

		if ( ! is_admin() && $query->is_main_query() && isset( $_GET['pickup_location'] ) && ! stm_is_checkout( $query ) || ! is_admin() && $query->is_main_query() && ! empty( $_COOKIE[ $pl ] ) && ! stm_is_checkout( $query ) && stm_is_shop( $query ) ) {
			$location_id = ( isset( $_GET['pickup_location'] ) ) ? sanitize_text_field( $_GET['pickup_location'] ) : intval( $_COOKIE[ $pl ] );

			$meta_query = array(
				array(
					'key'     => 'stm_rental_office',
					'value'   => stm_get_wpml_office_parent_id( $location_id ),
					'compare' => 'LIKE',
				),
			);

			$query->set( 'meta_query', $meta_query );
		}

		return $query;
	}

	add_action( 'pre_get_posts', 'stm_remove_car_options_from_query' );

	function stm_is_shop( $query ) {
		$front_page_id        = get_option( 'page_on_front' );
		$current_page_id      = $query->get( 'page_id' );
		$shop_page_id         = apply_filters( 'woocommerce_get_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );
		$is_static_front_page = 'page' === get_option( 'show_on_front' );

		if ( $is_static_front_page && $front_page_id === $current_page_id ) {
			$is_shop_page = ( $current_page_id === $shop_page_id );
		} else {
			$is_shop_page = is_shop();
		}

		return $is_shop_page;
	}

	function stm_is_checkout( $query ) {
		$front_page_id        = get_option( 'page_on_front' );
		$current_page_id      = $query->get( 'page_id' );
		$checkout_page_id     = apply_filters( 'woocommerce_checkout_page_id', get_option( 'woocommerce_checkout_page_id' ) );
		$is_static_front_page = 'page' === get_option( 'show_on_front' );

		if ( $is_static_front_page && $front_page_id === $current_page_id ) {
			$is_checkout_page = ( $current_page_id === $checkout_page_id );
		} else {
			$is_checkout_page = is_checkout();
		}

		return $is_checkout_page;
	}

	/*Checkout fields styling*/
	add_filter( 'woocommerce_checkout_fields', 'stm_custom_override_checkout_fields' );

	// Our hooked in function - $fields is passed via the filter!
	function stm_custom_override_checkout_fields( $fields ) {
		unset( $fields['billing']['billing_address_2'] );

		$billing = $fields['billing'];
		$spliced = array_splice( $billing, 0, 2 );

		$spliced['billing_driver_license'] = array(
			'label'        => esc_html__( 'Driver license', 'motors' ),
			'required'     => false,
			'class'        => array( 'form-row-first' ),
			'autocomplete' => 'driver_license',
			'priority'     => '25',
		);

		$fields['billing'] = array_merge( $spliced, $billing );

		$unvalidated_fields = array(
			'first_name',
			'last_name',
			'billing_first_name',
			'billing_last_name',
			'billing_email',
			'billing_phone',
		);

		if ( ! empty( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['class'] = array(
				'form-row-last',
			);
		}

		unset( $fields['billing']['billing_postcode'] );

		foreach ( $fields['billing'] as $key => $field ) {

			$field['label_class'] = 'heading-font';
			if ( ! in_array( $key, $unvalidated_fields, true ) ) {
				$fields['billing'][ $key ]['required'] = false;
			} else {
				$fields['billing'][ $key ]['required'] = true;
			}
		}

		if ( ! empty( $fields['billing']['billing_state'] ) ) {
			$fields['billing']['billing_state']['class'] = array(
				'address-field',
			);
		}

		return $fields;
	}

	add_filter( 'woocommerce_default_address_fields', 'stm_custom_override_default_address_fields' );

	function stm_custom_override_default_address_fields( $fields ) {
		$unvalidated_fields = array(
			'first_name',
			'last_name',
			'billing_first_name',
			'billing_last_name',
			'billing_email',
		);
		foreach ( $fields as $key => $field ) {
			if ( ! in_array( $key, $unvalidated_fields, true ) ) {
				$fields[ $key ]['required'] = false;
			} else {
				$fields[ $key ]['required'] = true;
			}
		}

		return $fields;
	}

	add_filter( 'woocommerce_form_field_args', 'stm_fields_checkout_args' );

	function stm_fields_checkout_args( $args ) {
		$args['label_class'] = 'heading-font';
		return $args;
	}

	// Add Woocommerce variation payment gateways
	/**
	 * Create new fields for variations
	 */
	function stm_variation_settings_fields( $loop, $variation_data, $variation ) {
		$payment_gateways  = array();
		$available_methods = WC()->payment_gateways;
		if ( ! empty( $available_methods->payment_gateways ) ) {
			foreach ( $available_methods->payment_gateways as $payment_gateway ) {
				$payment_gateways[ $payment_gateway->id ] = $payment_gateway->title;
			}
		}

		// Select
		woocommerce_wp_select(
			array(
				'wrapper_class' => 'stm-custom-select',
				'id'            => '_stm_payment_method[' . $variation->ID . ']',
				'label'         => __( 'Availble payment method', 'motors' ),
				'description'   => __( 'Choose payment method available only for this variable product. If this product will be in cart, all other payment methods will be disabled on checkout page.', 'motors' ),
				'value'         => get_post_meta( $variation->ID, '_stm_payment_method', true ),
				'options'       => $payment_gateways,
			)
		);
	}

	add_action( 'woocommerce_product_after_variable_attributes', 'stm_variation_settings_fields', 10, 3 );

	// Save Woocommerce variation payment gateway
	add_action( 'woocommerce_save_product_variation', 'stm_save_variation_settings_fields', 10, 2 );

	function stm_save_variation_settings_fields( $post_id ) {
		$select = sanitize_text_field( $_POST['_stm_payment_method'][ $post_id ] );

		if ( ! empty( $select ) ) {
			update_post_meta( $post_id, '_stm_payment_method', esc_attr( $select ) );
		}
	}

	add_filter( 'woocommerce_available_payment_gateways', 'stm_filter_gateways', 1 );
	function stm_filter_gateways( $gateways ) {
		if ( is_admin() ) {
			return $gateways;
		}
		$gateway    = array();
		$cart_items = stm_get_cart_items();
		if ( ! empty( $cart_items['car_class'] ) && ! empty( $cart_items['car_class']['payment_method'] ) ) {
			$payment_method = $cart_items['car_class']['payment_method'];
			if ( ! empty( $gateways[ $payment_method ] ) ) {
				$gateway[ $payment_method ] = $gateways[ $payment_method ];
			}
		}

		if ( ! empty( $gateway ) ) {
			$gateways = $gateway;
		}

		return $gateways;
	}


	function stm_rental_total_order_info() {
		$fields         = stm_get_rental_order_fields_values();
		$items          = stm_get_cart_items();
		$billing_fields = stm_rental_billing_info();

		$order_info = array(
			'pickup'  => array(
				'title'   => esc_html__( 'Pick Up', 'motors' ),
				'content' => '',
			),
			'dropoff' => array(
				'title'   => esc_html__( 'Drop off', 'motors' ),
				'content' => '',
			),
			'vehicle' => array(
				'title'   => esc_html__( 'Vehicle Type', 'motors' ),
				'content' => '',
			),
			'addons'  => array(
				'title'   => esc_html__( 'Add-ons', 'motors' ),
				'content' => '',
			),
			'info'    => array(
				'title'   => esc_html__( 'Your Information', 'motors' ),
				'content' => '',
			),
			'payment' => array(
				'title'   => esc_html__( 'Payment information', 'motors' ),
				'content' => '',
			),
		);

		if ( ! empty( $fields['pickup_location'] ) ) {
			$order_info['pickup']['content'] = $fields['pickup_location'] . ' ';
		}

		if ( ! empty( $fields['pickup_date'] ) ) {
			$order_info['pickup']['content'] .= $fields['pickup_date'];
		}

		if ( ! empty( $fields['return_location'] ) ) {
			$order_info['dropoff']['content'] = $fields['return_location'] . ' ';
		}

		if ( ! empty( $fields['return_date'] ) ) {
			$order_info['dropoff']['content'] .= $fields['return_date'];
		}

		if ( ! empty( $items['car_class']['name'] ) ) {
			$order_info['vehicle']['content'] = $items['car_class']['name'] . ' ';
		}

		if ( ! empty( $items['car_class']['subname'] ) ) {
			$order_info['vehicle']['content'] .= $items['car_class']['subname'];
		}

		if ( ! empty( $items['options_list'] ) ) {
			$order_info['addons']['content'] = implode( ', ', $items['options_list'] );
		}

		if ( ! empty( $billing_fields['first_name'] ) && ! empty( $billing_fields['last_name'] ) ) {
			$order_info['info']['content'] = $billing_fields['first_name'] . ' ' . $billing_fields['last_name'];
		}

		if ( ! empty( $billing_fields['total'] ) ) {
			$order_info['payment']['content'] = sprintf(
				/* translators: %s total */
				esc_html__( 'Estimated Total - %s', 'motors' ),
				$billing_fields['total']
			);
		}

		return apply_filters( 'stm_rental_order_info', $order_info );
	}

	function stm_rental_total_order_info_rental_two() {
		$fields        = stm_get_rental_order_fields_values();
		$items         = stm_get_cart_items();
		$product       = new WC_Product( $items['car_class']['id'] );
		$product_attrs = stm_mcr_get_product_atts( $product );

		$order_info = array();

		$order_info['pickup_location'] = $fields['pickup_location'];
		$order_info['pickup_date']     = $fields['pickup_date'];
		$order_info['dropoff']         = $fields['return_location'];
		$order_info['dropoff_date']    = $fields['return_date'];
		$order_info['vehicle_name']    = $items['car_class']['name'];
		$order_info['vehicle_img']     = wp_get_attachment_image_url( get_post_thumbnail_id( $items['car_class']['id'] ), 'stm-img-350' );
		$order_info['vehicle_atts']    = $product_attrs;

		if ( ! empty( $items['options_list'] ) ) {
			$order_info['addons'] = implode( ', ', $items['options_list'] );
		}
		return apply_filters( 'stm_rental_total_order_info_rental_two', $order_info );
	}

	function stm_rental_order_item_info_by_id_rental_two( $order_id, $prod_id ) {
		$product       = new WC_Product( $prod_id );
		$product_attrs = stm_mcr_get_product_atts( $product );

		$order_info       = array();
		$date_format      = get_option( 'date_format' );
		$time_format      = get_option( 'time_format' );
		$date_time_format = $date_format . ' ' . $time_format;

		$date1 = DateTime::createFromFormat( $date_time_format, get_post_meta( $order_id, 'order_pickup_date', true ) );
		$date2 = DateTime::createFromFormat( $date_time_format, get_post_meta( $order_id, 'order_drop_date', true ) );

		$diff = 0;
		if ( $date1 && $date2 ) {
			$diff = $date2->diff( $date1 )->format( '%a.%h' );
			$hm   = explode( '.', $diff );

			if ( '0' === $hm[0] ) {
				$diff = 0;
			}
		}

		$order_info['pickup_location'] = get_post_meta( $order_id, 'order_pickup_location', true );
		$order_info['pickup_date']     = get_post_meta( $order_id, 'order_pickup_date', true );
		$order_info['dropoff']         = get_post_meta( $order_id, 'order_drop_location', true );
		$order_info['dropoff_date']    = get_post_meta( $order_id, 'order_drop_date', true );
		$order_info['vehicle_img']     = wp_get_attachment_image_url( get_post_thumbnail_id( $prod_id ), 'stm-img-350' );
		$order_info['vehicle_name']    = $product->get_name();
		$order_info['vehicle_atts']    = $product_attrs;
		$order_info['order_days']      = $diff;

		if ( ! empty( $items['options_list'] ) ) {
			$order_info['addons'] = implode( ', ', $items['options_list'] );
		}

		return apply_filters( 'stm_rental_total_order_info_rental_two', $order_info );
	}

	function stm_get_order_id() {
		$order_id = false;

		$order_received = get_option( 'woocommerce_checkout_order_received_endpoint', 'order-received' );
		$view_order     = get_option( 'woocommerce_myaccount_view_order_endpoint', 'view-order' );

		if ( isset( $_GET[ $view_order ] ) ) {
			$order_id = intval( $_GET[ $view_order ] );
		} elseif ( isset( $_GET[ $order_received ] ) ) {
			$order_id = intval( $_GET[ $order_received ] );
		} else {
			$url = apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) . apply_filters( 'stm_get_global_server_val', 'REQUEST_URI' );

			if ( strpos( $url, '/' . $order_received . '/' ) !== false ) {
				$start      = strpos( $url, '/' . $order_received . '/' );
				$first_part = substr( $url, $start + strlen( '/' . $order_received . '/' ) );
				$order_id   = substr( $first_part, 0, strpos( $first_part, '/' ) );
			}
		}

		return $order_id;
	}

	/*Update transient of user after new order*/
	function stm_rental_billing_info() {
		$bill = array(
			'first_name' => '',
			'last_name'  => '',
			'email'      => '',
			'payment'    => '',
			'total'      => '',
		);

		$order_id = stm_get_order_id();

		if ( ! is_user_logged_in() ) {
			$first_name = get_post_meta( $order_id, '_billing_first_name', true );
			if ( ! empty( $first_name ) ) {
				$bill['first_name'] = $first_name;
			}

			$last_name = get_post_meta( $order_id, '_billing_last_name', true );
			if ( ! empty( $last_name ) ) {
				$bill['last_name'] = $last_name;
			}
		} else {
			$id        = get_current_user_id();
			$name      = get_user_meta( $id, 'billing_first_name', true );
			$last_name = get_user_meta( $id, 'billing_last_name', true );

			if ( ! empty( $name ) && ! empty( $last_name ) ) {
				$bill['first_name'] = $name;
				$bill['last_name']  = $last_name;
			}
		}

		$order = wc_get_order( $order_id );

		if ( ! empty( $order ) ) {
			if ( preg_replace( '/\D/', '', $order ) ) {
				$bill['total'] = wc_price( $order->get_total() );
			}
		}

		$payment = get_post_meta( $order_id, '_payment_method_title', true );
		if ( ! empty( $payment ) ) {
			$bill['payment'] = $payment;
		}

		return apply_filters( 'stm_billing_rental_info', $bill );
	}

	// change order info when on order page
	add_action( 'stm_cart_items_content', 'stm_order_page_information_rental' );

	function stm_order_page_information_rental( $info ) {
		$order_id = stm_get_order_id();
		if ( ! empty( $order_id ) ) {
			$info_car = get_post_meta( $order_id, 'order_car', true );
			if ( ! empty( $info_car['car_class'] ) ) {
				$info = $info_car;
			}
		}

		return $info;
	}

	add_action( 'stm_rental_date_values', 'stm_order_page_date_rental' );

	function stm_order_page_date_rental( $date ) {
		$order_id = stm_get_order_id();
		if ( ! empty( $order_id ) ) {
			$date_car = get_post_meta( $order_id, 'order_car_date', true );
			if ( ! empty( $date_car ) ) {
				$date = $date_car;
			}
		}
		return $date;
	}

	/*Taxes*/
	function stm_rental_order_taxes() {
		$taxes    = array();
		$order_id = stm_get_order_id();
		if ( ! empty( $order_id ) ) {
			$order       = wc_get_order( $order_id );
			$order_taxes = $order->get_taxes();
			foreach ( $order_taxes as $order_tax ) {
				$taxes[ $order_tax['label'] ] = array(
					'label' => $order_tax['label'],
					'value' => wc_price( $order_tax['tax_amount'] ),
				);
			}
		} else {
			$cart = WC()->cart->get_tax_totals();
			foreach ( $cart as $name => $cart_item ) {
				$taxes[ $name ] = array(
					'label' => $cart_item->label,
					'value' => $cart_item->formatted_amount,
				);
			}
		}

		return apply_filters( 'stm_rental_order_taxes', $taxes );
	}

	if ( apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_office_location_fee' ) ) {
		add_filter( 'woocommerce_product_variation_get_price', 'custom_product_price', 30, 2 );
		add_filter( 'woocommerce_product_get_price', 'custom_product_price', 30, 2 );
	}
	function custom_product_price( $price, $product ) {
		if ( 'car_option' === $product->get_type() ) {
			return $price;
		}
		$order_data = stm_get_rental_order_fields_values();
		if ( 'off' === $order_data['return_same'] ) {
			$price = $price + floatval( $order_data['return_location_fee'] );
		}
		if ( 'on' === $order_data['return_same'] && apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_fee_for_same_location' ) ) {
			$price = $price + floatval( $order_data['pickup_location_fee'] );
		}

		return $price;
	}

	add_action( 'woocommerce_new_order', 'stm_order_fields' );
	function stm_order_fields( $order_id ) {
		if ( ! is_admin() ) {
			$cart_items = stm_get_cart_items();
			$date       = stm_get_rental_order_fields_values();
			update_post_meta( $order_id, 'order_car', $cart_items );
			update_post_meta( $order_id, 'order_car_date', $date );
			update_post_meta( $order_id, 'order_pickup_date', change_date_format( $date['calc_pickup_date'] ) );
			update_post_meta( $order_id, 'order_pickup_location', $date['pickup_location'] );
			update_post_meta( $order_id, 'order_drop_date', change_date_format( $date['calc_return_date'] ) );
			update_post_meta( $order_id, 'order_drop_location', $date['return_location'] );
		}
	}

	//adding custom order meta
	add_action( 'woocommerce_checkout_order_created', 'stm_add_custom_order_meta' );

	function stm_add_custom_order_meta( $order ) {
		$date = stm_get_rental_order_fields_values();
		$order->update_meta_data( 'order_pickup_date', change_date_format( $date['calc_pickup_date'] ) );
		$order->update_meta_data( 'order_pickup_location', $date['pickup_location'] );
		$order->update_meta_data( 'order_drop_date', change_date_format( $date['calc_return_date'] ) );
		$order->update_meta_data( 'order_drop_location', $date['return_location'] );
		$order->save();
	}

	/*Remove notice when adding item to cart*/
	add_filter( 'wc_add_to_cart_message_html', '__return_empty_string' );

	function stm_get_car_rent_info( $id ) {
		$car_info_points = apply_filters( 'stm_get_car_listings', array() );
		$car_info        = array();
		if ( ! empty( $car_info_points ) ) {
			foreach ( $car_info_points as $car_info_point ) {
				$meta = get_post_meta( $id, $car_info_point['slug'], true );
				if ( ! empty( $meta ) ) {
					$slug              = $car_info_point['slug'];
					$car_info[ $slug ] = array(
						'name'    => $car_info_point['plural_name'],
						'value'   => $meta,
						'font'    => '',
						'numeric' => false,
					);

					if ( ! empty( $car_info_point['numeric'] ) ) {
						$car_info[ $slug ]['numeric'] = true;
					}

					if ( ! empty( $car_info_point['font'] ) ) {
						$car_info[ $slug ]['font'] = $car_info_point['font'];
					}
				}
			}
		}
		return apply_filters( 'stm_car_rent_info', $car_info );
	}

	function stm_rental_order_fileds() {
		$blog_id = get_current_blog_id();
		$rents   = array( 'pickup_location', 'pickup_date', 'return_date', 'drop_location', 'return_same', 'calc_pickup_date', 'calc_return_date' );
		foreach ( $rents as $i => $rent ) {
			$rents[ $i ] = 'stm_' . $rent . '_' . $blog_id;
		}
		return $rents;
	}

	function stm_check_rental_date_validation() {
		$r = true;

		$fields = stm_get_rental_order_fields_values();

		if ( stm_get_empty_placeholder() === $fields['pickup_location'] || stm_get_empty_placeholder() === $fields['pickup_date'] || stm_get_empty_placeholder() === $fields['return_date'] ) {
			$r = false;
		}

		return $r;
	}

	function stm_get_rental_order_fields_values( $empty = false ) {
		$values = array(
			'pickup_location_id'  => '',
			'pickup_location'     => '',
			'pickup_date'         => '',
			'calc_pickup_date'    => '',
			'return_date'         => '',
			'calc_return_date'    => '',
			'return_location_id'  => '',
			'return_location'     => '',
			'return_same'         => '',
			'pickup_location_fee' => '',
			'return_location_fee' => '',
			'order_days'          => 0,
			'order_hours'         => 0,
			'ceil_days'           => 0,
		);

		$values['format'] = stm_get_clear_date_format();

		$d_format = get_option( 'date_format' );
		$t_format = get_option( 'time_format' );

		$t_format = str_replace( 'g', 'hh', $t_format ); //moment.js didn't work with 'g'
		$t_format = str_replace( 'i', 'mm', $t_format ); //moment.js didn't work with 'i'

		$d_format = str_replace( 'm', 'MM', $d_format );
		$d_format = str_replace( 'd', 'DD', $d_format );
		$d_format = str_replace( 'F', 'MM', $d_format );
		$d_format = str_replace( 'j', 'DD', $d_format );

		$date_time_format = $d_format . ' ' . $t_format;

		$values['moment_format'] = stm_get_clear_date_format( $date_time_format );

		$fields = stm_rental_order_fileds();

		$pickup_location  = ! empty( $_COOKIE[ $fields[0] ] ) ? intval( sanitize_text_field( $_COOKIE[ $fields[0] ] ) ) : false;
		$pickup_date      = ! empty( $_COOKIE[ $fields[1] ] ) ? urldecode( sanitize_text_field( $_COOKIE[ $fields[1] ] ) ) : false;
		$return_date      = ! empty( $_COOKIE[ $fields[2] ] ) ? urldecode( sanitize_text_field( $_COOKIE[ $fields[2] ] ) ) : false;
		$return_location  = ! empty( $_COOKIE[ $fields[3] ] ) ? intval( sanitize_text_field( $_COOKIE[ $fields[3] ] ) ) : false;
		$return_same      = ! empty( $_COOKIE[ $fields[4] ] ) ? sanitize_text_field( $_COOKIE[ $fields[4] ] ) : 'on';
		$pickup_date_calc = ! empty( $_COOKIE[ $fields[5] ] ) ? urldecode( sanitize_text_field( $_COOKIE[ $fields[5] ] ) ) : false;
		$return_date_calc = ! empty( $_COOKIE[ $fields[6] ] ) ? urldecode( sanitize_text_field( $_COOKIE[ $fields[6] ] ) ) : false;

		/*Pickup Location*/
		if ( $pickup_location ) {
			$values['pickup_location_id']  = $pickup_location;
			$values['pickup_location']     = get_post_meta( $pickup_location, 'address', true );
			$values['pickup_location_fee'] = get_post_meta( $pickup_location, 'office_fee', true );
		} else {
			$values['pickup_location'] = stm_get_empty_placeholder( $empty );
		}

		/*Pickup date*/
		if ( $pickup_date ) {
			$values['pickup_date']      = $pickup_date;
			$values['calc_pickup_date'] = $pickup_date_calc;
		} else {
			$values['pickup_date']      = stm_get_empty_placeholder( $empty );
			$values['calc_pickup_date'] = stm_get_empty_placeholder( $empty );
		}

		/*Return date*/
		if ( $return_date ) {
			$values['return_date']      = $return_date;
			$values['calc_return_date'] = $return_date_calc;
		} else {
			$values['return_date']      = stm_get_empty_placeholder( $empty );
			$values['calc_return_date'] = stm_get_empty_placeholder( $empty );
		}

		/*Drop Location*/
		if ( 'on' === $return_same ) {
			$values['return_location'] = $values['pickup_location'];
		} else {
			if ( ! empty( $return_location ) ) {
				$values['return_location_id']  = $return_location;
				$values['return_location']     = get_post_meta( $return_location, 'address', true );
				$values['return_location_fee'] = floatval( get_post_meta( $return_location, 'office_fee', true ) );
			} else {
				$values['return_location'] = $values['pickup_location'];
			}
		}

		$values['return_same'] = $return_same;

		if ( stm_get_empty_placeholder() !== $values['calc_return_date'] && stm_get_empty_placeholder() !== $values['calc_pickup_date'] ) {
			try {

				$date1 = DateTime::createFromFormat( 'm d, Y H:i', $values['calc_pickup_date'] );
				$date2 = DateTime::createFromFormat( 'm d, Y H:i', $values['calc_return_date'] );

				if ( ! ( $date1 instanceof DateTime ) || ! ( $date2 instanceof DateTime ) ) {
					throw new Exception( 'could not parse dates' );
				}

				$diff = $date2->diff( $date1 )->format( '%a.%h' );
				$hm   = explode( '.', $diff );
				if ( '0' === $hm[0] ) {
					$diff = 0;
				}

				$values['ceil_days']  = (int) ceil( $diff );
				$values['order_days'] = (int) floor( $diff );
			} catch ( Throwable $exception ) {
				$values['pickup_date']      = '';
				$values['return_date']      = '';
				$values['calc_pickup_date'] = '';
				$values['calc_return_date'] = '';
			}
		} else {
			$values['ceil_days'] = 1;
		}
		return apply_filters( 'stm_rental_date_values', $values );
	}

	// function for converting date format on frontend
	function change_date_format( $date ) {
		$date_format      = get_option( 'date_format' );
		$time_format      = get_option( 'time_format' );
		$date_time_format = $date_format . ' ' . $time_format;

		$input_date_format = 'm d, Y H:i';

		$datetime = DateTime::createFromFormat( $input_date_format, $date );

		if ( false === $datetime ) {
			return false;
		}

		return $datetime->format( $date_time_format );
	}

	/*Rental locations*/
	function stm_rental_locations( $locations = array(), $full = false ) {
		if ( ! is_array( $locations ) ) {
			$locations = array();
		}

		$args = array(
			'post_type'      => 'stm_office',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

		$offices = new WP_Query( $args );
		$i       = 0;

		if ( $offices->have_posts() ) :
			while ( $offices->have_posts() ) :
				$offices->the_post();
				$id      = get_the_ID();
				$lat     = get_post_meta( $id, 'latitude', true );
				$lng     = get_post_meta( $id, 'longitude', true );
				$phone   = get_post_meta( $id, 'phone', true );
				$fax     = get_post_meta( $id, 'fax', true );
				$address = get_post_meta( $id, 'address', true );

				if ( ! empty( $lng ) && ! empty( $lat ) ) {
					$google_api_key = apply_filters( 'stm_me_get_nuxy_mod', '', 'google_api_key' );
					$class          = '';
					if ( ! empty( $google_api_key ) ) {
						$class = 'with-map';
					}

					$content  = "<div class='stm_offices_wrapper " . esc_attr( $class ) . "'>";
					$content .= "<div class='location heading-font'>" . get_the_title() . '</div>';
					if ( ! empty( $address ) ) {
						$content .= "<div class='address'><i class='stm-icon-pin'></i>" . $address . '</div>';
					}
					if ( ! empty( $phone ) || ! empty( $fax ) ) {
						$content .= "<div class='phone_fax'><i class='stm-icon-phone'></i> ";
					}
					if ( ! empty( $phone ) ) {
						$content .= "<div class='phone'>" . esc_html__( 'Phone:', 'motors' ) . ' ' . $phone . '</div>';
					}
					if ( ! empty( $fax ) ) {
						$content .= "<div class='fax'>" . esc_html__( 'Fax:', 'motors' ) . ' ' . $fax . '</div>';
					}
					if ( ! empty( $phone ) || ! empty( $fax ) ) {
						$content .= '</div>';
					}

					if ( $full ) {
						$hours = get_post_meta( $id, 'work_hours', true );
						if ( ! empty( $hours ) ) {
							$content .= "<div class='stm_work_hours'><i class='stm-icon-time'></i>";
							$content .= $hours;
							$content .= '</div>';
						}
						if ( ! empty( $google_api_key ) ) {
							$g_map_url      = 'https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=253x253&markers=color:red%7Clabel:C%7C' . $lat . ',' . $lng . '&key=' . $google_api_key;
							$g_map_full_url = 'https://www.google.com/maps/place/' . $lat . ',' . $lng;
							$content       .= "<a href='" . $g_map_full_url . "' target='_blank'><img src='" . $g_map_url . "' alt='' /></a>";
						}
					}

					$content .= '</div>';

					$locations[] = array(
						$content,
						$lat,
						$lng,
						$i,
						( ! empty( stm_motors_wpml_binding( $id, 'product' ) ) ) ? get_the_title( stm_motors_wpml_binding( $id, 'product' ) ) : get_the_title(),
						$id,
					);

					$i++;
				}

			endwhile;

			wp_reset_postdata();
		endif;

		return is_array( $locations ) ? $locations : array();

	}
	add_filter( 'stm_rental_locations', 'stm_rental_locations', 10, 2 );

	function stm_admin_add_offices_to_car( $manager ) {
		/*Offices*/
		$locations = apply_filters( 'stm_rental_locations', array(), true );

		if ( count( $locations ) > 0 ) {
			$offices_arr = array();
			/*Add multiselects*/
			foreach ( $locations as $option ) {
				$offices_arr[ stm_get_wpml_office_parent_id( $option[5] ) ] = $option[4];
			}

			$manager->register_control(
				'stm_rental_office',
				array(
					'type'    => 'multiselect',
					'section' => 'stm_info',
					'label'   => 'Offices',
					'choices' => $offices_arr,
				)
			);

			$manager->register_setting(
				'stm_rental_office',
				array(
					'sanitize_callback' => 'stm_listings_multiselect',
				)
			);
		}
	}

	add_action( 'stm_add_rental_offices', 'stm_admin_add_offices_to_car' );

	function stm_remove_get_params() {
		wp_add_inline_script(
			'stm-theme-scripts',
			'
					jQuery(document).ready(function(){
						window.history.pushState("", "", "' . remove_query_arg( 'order_old_days' ) . '");
					});
				'
		);
	}

	if ( isset( $_GET['order_old_days'] ) ) {
		add_action( 'wp_enqueue_scripts', 'stm_remove_get_params' );
	}

	add_filter( 'woocommerce_get_price_html', 'custom_admin_product_price', 100, 4 );

	function custom_admin_product_price( $price, $product ) {
		$product_id = get_the_ID();
		$product    = wc_get_product( $product_id );
		if ( is_object( $product ) ) {
			$product_type = $product->get_type();
			if ( isset( $product_type ) && 'variable' === $product_type ) {
				$variation_ids = $product->get_children();
				$var_prices    = array();
				foreach ( $variation_ids as $variation_id ) {
					$variation = wc_get_product( $variation_id );
					if ( ! empty( $variation->get_sale_price() ) ) {
						$var_prices[] = format_price( $variation->get_sale_price() );
					} else {
						$var_prices[] = format_price( $variation->get_regular_price() );
					}
				}
				$price = implode( '-', $var_prices );
				return $price;
			} else {
				if ( ! empty( $product->get_sale_price() ) ) {
					$price = $product->get_sale_price();
				} else {
					$price = $product->get_regular_price();
				}
				return format_price( $price );
			}
		}
	}
	function format_price( $price ) {
		$currency_symbol   = get_woocommerce_currency_symbol();
		$decimal_separator = wc_get_price_decimal_separator();
		return $currency_symbol . number_format( floatval( $price ), 2, $decimal_separator, '' );
	}

	function stm_createUnavailableCarListForOrder( $car_ids, $start_date, $end_date ) {
		$unavailable_cars = array();
		foreach ( $car_ids as $car_id ) {
			if ( ! empty( get_post_meta( $car_id, 'cars_qty', true ) ) ) {
				if ( count( stm_check_order_available( $car_id, $start_date, $end_date ) ) > 0 ) {
					$unavailable_cars[] = $car_id;
				}
			}
		}

		return $unavailable_cars;
	}

	function stm_get_date_range( $date1, $date2 ) {
		if ( empty( $date1 ) || empty( $date2 ) ) {
			return array();
		}

		$datetime1 = stm_date_create_from_format( $date1 );
		$datetime2 = stm_date_create_from_format( $date2 );

		if ( ! ( $datetime1 instanceof DateTime ) || ! ( $datetime2 instanceof DateTime ) ) {
			return array();
		}

		$interval = $datetime1->diff( $datetime2 );

		$days           = (int) $interval->format( '%a' );
		$date_range_arr = array( gmdate( 'Y-m-d', strtotime( 'now', $datetime1->getTimestamp() ) ) );

		for ( $q = 1; $q <= $days; $q ++ ) {
			$last_generate_date = gmdate( 'Y-m-d', strtotime( '+1 day', $datetime1->getTimestamp() ) );
			try {
				$datetime1 = new DateTime( $last_generate_date );
			} catch ( Throwable $exception ) {
				continue;
			}
			$date_range_arr[] = $last_generate_date;
		}

		return $date_range_arr;
	}

	function stm_get_date_range_with_time( $date1, $date2 ) {
		if ( '--' !== $date1 && '--' !== $date2 ) {
			$date1 = stm_date_create_from_format( $date1 );
			$date2 = stm_date_create_from_format( $date2 );

			if ( ! ( $date1 instanceof DateTime ) || ! ( $date2 instanceof DateTime ) ) {
				return array();
			}

			$datetime1 = $date1;
			$datetime2 = $date2;

			$interval       = $datetime1->diff( $datetime2 );
			$days           = (int) $interval->format( '%a' );
			$date_range_arr = array( gmdate( 'Y-m-d', strtotime( 'now', $datetime1->getTimestamp() ) ) );
			for ( $q = 1; $q <= $days; $q++ ) {
				$last_generate_date = gmdate( 'Y-m-d', strtotime( '+1 day', $datetime1->getTimestamp() ) );
				$datetime1          = new DateTime( $last_generate_date );
				$date_range_arr[]   = $last_generate_date;
			}

			return apply_filters( 'modify_days_range', $date_range_arr );
		}

		return array();
	}


	function stm_check_order_available( $order_car_class_id, $pickup_date, $return_date ) {
		$unavailable_dates    = array();
		$order_car_class_id   = apply_filters( 'stm_get_wpml_product_parent_id', $order_car_class_id );
		$cars_stock_available = (int) get_post_meta( $order_car_class_id, 'cars_qty', true );
		$range_date           = stm_get_date_range( stripslashes( $pickup_date ), stripslashes( $return_date ) );
		$range_date_count     = count( $range_date );
		for ( $q = 0; $q < $range_date_count; $q++ ) {
			$order_available = (int) get_post_meta( $order_car_class_id, $range_date[ $q ] . '_' . $order_car_class_id, true );
			if ( $cars_stock_available <= $order_available ) {
				$unavailable_dates[] = $range_date[ $q ];
			}
		}

		return $unavailable_dates;
	}

	add_filter( 'get_max_min_days', 'get_max_min_days', 10, 2 );

	function get_max_min_days( $car_id, $order_days ) {
		$min_days = get_post_meta( $car_id, 'min_rent_days', true );
		$max_days = get_post_meta( $car_id, 'max_rent_days', true );

		$rent_allow_data = array(
			'allow'   => true,
			'message' => '',
			'days'    => '',
		);

		if ( ! empty( $min_days ) && $min_days > $order_days ) {
			$rent_allow_data = array(
				'allow'   => false,
				'message' => esc_html__( 'Minimum rental period is ', 'motors' ),
				'days'    => $min_days . esc_html__( ' days', 'motors' ),
			);
		} elseif ( ! empty( $max_days ) && $order_days > $max_days ) {
			$rent_allow_data = array(
				'allow'   => false,
				'message' => esc_html__( 'Maximum rental period is ', 'motors' ),
				'days'    => $max_days . esc_html__( ' days', 'motors' ),
			);
		}

		return $rent_allow_data;
	}

	function stm_add_order_date_info( $order_id, $data ) {
		$order_cookie       = stm_get_rental_order_fields_values();
		$order_car_class_id = 0;
		$order              = new WC_Order( $order_id );

		update_post_meta( $order_id, 'stm_order_datetime_format', apply_filters( 'motors_get_system_datetime_format', '' ) );

		foreach ( $order->get_items() as $product ) {
			$product_type = stm_wc_get_product_type( $product['product_id'] );
			if ( 'car_option' !== $product_type ) {
				$order_car_class_id = $product['product_id'];
			}
		}

		$order_car_class_id          = apply_filters( 'stm_get_wpml_product_parent_id', $order_car_class_id );
		$cars_stock_available        = (int) get_post_meta( $order_car_class_id, 'cars_qty', true );
		$check_order_available       = stm_check_order_available( $order_car_class_id, $order_cookie['calc_pickup_date'], $order_cookie['calc_return_date'] );
		$check_order_available_count = count( $check_order_available );
		if ( 0 !== $order_car_class_id && 0 === $check_order_available_count ) {
			$range_date           = stm_get_date_range( $order_cookie['calc_pickup_date'], $order_cookie['calc_return_date'] );
			$new_order_meta_dates = array();
			$date_range_count     = count( $range_date );

			for ( $q = 0; $q < $date_range_count; $q++ ) {

				$date_order_quantity = (int) get_post_meta( $order_car_class_id, $range_date[ $q ] . '_' . $order_car_class_id, true );
				if ( $cars_stock_available > 0 && $cars_stock_available > $date_order_quantity ) {
					$meta_id = update_post_meta( $order_car_class_id, $range_date[ $q ] . '_' . $order_car_class_id, $date_order_quantity + 1 );
					if ( $meta_id ) {
						$new_order_meta_dates[] = $range_date[ $q ] . '_' . $order_car_class_id;
					}
				}
			}

			$new_order_meta_dates_count = count( $new_order_meta_dates );
			if ( $new_order_meta_dates_count > 0 ) {
				update_post_meta( $order_car_class_id, 'order_meta_dates_' . $order_id, implode( ',', $new_order_meta_dates ) );
			}

			return true;
		} else {
			$formated_dates = array();
			foreach ( $check_order_available as $val ) {
				$formated_dates[] = stm_get_formated_date( $val, 'd M' );
			}
			throw new Exception( esc_html__( 'This Class is already booked in: ', 'motors' ) . "<span class='bold'>" . implode( ', ', $formated_dates ) . '</span>.' );
		}
	}

	add_action( 'woocommerce_checkout_update_order_meta', 'stm_add_order_date_info', 100, 2 );

	function stm_add_order_custom_post_meta( $order_id, $status ) {
		if ( 'completed' === $status ) {
			update_post_meta( $order_id, 'stm_order_status', 'in_rent' );
		} elseif ( 'cancelled' === $status ) {
			stm_remove_order_custom_post_meta_hard( $order_id );
		}
	}

	function stm_add_order_complete_post_meta( $order_id ) {
		update_post_meta( $order_id, 'stm_order_status', 'in_rent' );
	}

	function stm_remove_order_custom_post_meta_hard( $order_id ) {
		global $wpdb;

		$meta_keys = $wpdb->get_row( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", 'order_meta_dates_' . $order_id ) );
		$dates     = explode( ',', $meta_keys->meta_value );

		foreach ( $dates as $key => $val ) {
			$order_days = get_post_meta( $meta_keys->post_id, $val, true );
			if ( ! empty( $order_days ) && (int) $order_days > 0 ) {
				if ( '1' === (string) $order_days ) {
					delete_post_meta( $meta_keys->post_id, $val );
				} else {
					update_post_meta( $meta_keys->post_id, $val, $order_days - 1 );
				}
			}
		}

		delete_post_meta( $order_id, 'stm_order_status' );
		delete_post_meta( $meta_keys->post_id, 'order_meta_dates_' . $order_id );
	}

	add_action( 'woocommerce_order_edit_status', 'stm_add_order_custom_post_meta', 100, 2 );
	add_action( 'woocommerce_order_status_completed', 'stm_add_order_complete_post_meta', 100, 1 );
	add_action( 'woocommerce_order_status_cancelled', 'stm_remove_order_custom_post_meta_hard', 100, 1 );
	add_action( 'wp_trash_post', 'stm_remove_order_custom_post_meta_hard', 100, 1 );
	add_action( 'woocommerce_delete_order', 'stm_remove_order_custom_post_meta_hard', 100, 1 );

	function stm_get_default_variable_price( $product_id, $index = 0, $from = false ) {
		$price = get_post_meta( $product_id, '_price' );
		if ( ! empty( $price ) ) {
			return $price[ $index ];
		}
	}

	function stm_getInfoWindowPriceManip( $id ) {
		$id             = apply_filters( 'stm_get_wpml_product_parent_id', $id );
		$price_date     = PriceForDatePeriod::getVariationPriceView( $id );
		$discount       = ( class_exists( 'DiscountByDays' ) ) ? DiscountByDays::get_days_post_meta( $id ) : null;
		$fixed_price    = ( class_exists( 'PriceForQuantityDays' ) ) ? PriceForQuantityDays::get_sorted_fixed_price( $id ) : null;
		$price_per_hour = get_post_meta( $id, 'rental_price_per_hour_info', true );

		if ( ! empty( $price_date ) || ! empty( $discount ) || ! empty( $price_per_hour ) || ! empty( $fixed_price ) ) {
			?>
			<span class="price-date-info-icon">
			<i class="fas fa-info-circle"></i>
			<div class="price-date-info-popup">
				<ul>
					<?php if ( ! empty( $price_date ) ) : ?>
						<li class="padd-10"><?php echo esc_html__( 'Sessions:', 'motors' ); ?></li>
						<?php
						foreach ( $price_date as $dates ) :
							$date_format = get_option( 'date_format' );
							?>
							<li><?php echo esc_html__( 'From ', 'motors' ) . '<b>' . esc_html( gmdate( $date_format, $dates->starttime ) ) . '</b>' . esc_html__( ' to ', 'motors' ) . '<b>' . esc_html( gmdate( $date_format, $dates->endtime ) ) . '</b>: <b>' . sprintf( /* translators: %s price */esc_html__( '%s/Day', 'motors' ), wp_kses_post( wc_price( $dates->price ) ) ) . '</b>'; ?></li>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $fixed_price ) ) : ?>
						<li class="padd-10"><?php echo esc_html__( 'Fixed price:', 'motors' ); ?></li>
						<?php foreach ( $fixed_price as $k => $val ) : ?>
							<li><?php /* translators: %1$s days and %2$s price */ echo sprintf( esc_html__( '%1$s days and more: %2$s', 'motors' ), '<b>' . esc_html( $k ) . '</b>', '<b>' . wp_kses_post( wc_price( $val ) ) . '</b>' ); ?></li>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $discount ) ) : ?>
						<li class="padd-10"><?php echo esc_html__( 'Discounts:', 'motors' ); ?></li>
						<?php foreach ( $discount as $val ) : ?>
							<li><?php /* translators: %1$s days and %2$s sale */ echo sprintf( esc_html__( '%1$s days and more: %2$s sale', 'motors' ), '<b>' . esc_html( $val['days'] ) . '</b>', '<b>' . esc_html( $val['percent'] ) . '%</b>' ); ?></li>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php
					if ( ! empty( $price_per_hour ) ) :
						?>
						<li class="padd-10"><?php echo esc_html__( 'Per Hour:', 'motors' ); ?></li>
						<li><?php /* translators: %s price per hour */ echo sprintf( esc_html__( '%s Per hour', 'motors' ), '<b>' . wp_kses_post( wc_price( $price_per_hour ) ) . '</b>' ); ?></li>
						<?php
					endif;
					?>
				</ul>
			</div>
		</span>
			<?php
		}
	}

	function stm_get_popup_promo_price( $popup_id, $id, $price, $fields ) {
		$id             = apply_filters( 'stm_get_wpml_product_parent_id', $id );
		$tax_rates      = WC_Tax::get_rates();
		$price_date     = PriceForDatePeriod::getDescribeTotalByDays( $price, $id );
		$discount       = ( class_exists( 'DiscountByDays' ) ) ? DiscountByDays::get_days_post_meta( $id ) : null;
		$fixed_price    = ( class_exists( 'PriceForQuantityDays' ) ) ? PriceForQuantityDays::get_sorted_fixed_price( $id ) : null;
		$price_per_hour = get_post_meta( $id, 'rental_price_per_hour_info', true );
		$productPrice   = get_post_meta( $id, '_price', true );

		if ( ! empty( $price_date ) || ! empty( $discount ) || ! empty( $price_per_hour ) || ! empty( $fixed_price ) ) {
			$args = compact(
				'popup_id',
				'id',
				'tax_rates',
				'price_date',
				'discount',
				'fixed_price',
				'price_per_hour',
				'fields',
				'productPrice',
			);
			get_template_part( 'partials/rental/main-shop/promo', 'popup', $args );
		}
	}

	add_filter( 'product_type_options', 'stm_wc_hook_stm_product_type_options' );

	function stm_wc_hook_stm_product_type_options( $checkboxes ) {
		$checkboxes['car_option'] = array(
			'id'            => '_car_option',
			'wrapper_class' => 'show_if_car_option',
			'label'         => __( 'Single pay for the option', 'motors' ),
			'description'   => __( 'Payment amount doesn`t depend on rental days. Only one time payment for this option.', 'motors' ),
			'default'       => 'no',
		);

		return $checkboxes;
	}

	add_action( 'woocommerce_process_product_meta', 'stm_wc_save_car_option_meta', 10, 1 );

	function stm_wc_save_car_option_meta( $post_id ) {
		if ( isset( $_POST['product-type'] ) && 'car_option' === $_POST['product-type'] && isset( $_POST['_car_option'] ) ) {
			update_post_meta( $post_id, '_car_option', 'yes' );
		} else {
			delete_post_meta( $post_id, '_car_option' );
		}
	}

	function stm_wc_session() {

		$fields = stm_rental_order_fileds();

		if ( ! empty( WC()->session->get( $fields[5], '' ) ) && ! empty( WC()->session->get( $fields[6], '' ) ) && empty( $_COOKIE[ $fields[5] ] ) && empty( $_COOKIE[ $fields[6] ] ) ) {
			$_COOKIE[ $fields[0] ] = WC()->session->get( $fields[0], '' );
			$_COOKIE[ $fields[1] ] = WC()->session->get( $fields[1], '' );
			$_COOKIE[ $fields[2] ] = WC()->session->get( $fields[2], '' );
			$_COOKIE[ $fields[3] ] = WC()->session->get( $fields[3], '' );
			$_COOKIE[ $fields[4] ] = WC()->session->get( $fields[4], '' );
			$_COOKIE[ $fields[5] ] = WC()->session->get( $fields[5], '' );
			$_COOKIE[ $fields[6] ] = WC()->session->get( $fields[6], '' );
		}

		$pickup_location  = ! empty( $_COOKIE[ $fields[0] ] ) ? intval( $_COOKIE[ $fields[0] ] ) : '';
		$pickup_date      = ! empty( $_COOKIE[ $fields[1] ] ) ? sanitize_text_field( $_COOKIE[ $fields[1] ] ) : '';
		$return_date      = ! empty( $_COOKIE[ $fields[2] ] ) ? sanitize_text_field( $_COOKIE[ $fields[2] ] ) : '';
		$return_location  = ! empty( $_COOKIE[ $fields[3] ] ) ? intval( $_COOKIE[ $fields[3] ] ) : '';
		$return_same      = ! empty( $_COOKIE[ $fields[4] ] ) ? sanitize_text_field( $_COOKIE[ $fields[4] ] ) : 'on';
		$pickup_date_calc = ! empty( $_COOKIE[ $fields[5] ] ) ? sanitize_text_field( $_COOKIE[ $fields[5] ] ) : '';
		$return_date_calc = ! empty( $_COOKIE[ $fields[6] ] ) ? sanitize_text_field( $_COOKIE[ $fields[6] ] ) : '';

		WC()->session->set( $fields[0], $pickup_location );
		WC()->session->set( $fields[1], $pickup_date );
		WC()->session->set( $fields[2], $return_date );
		WC()->session->set( $fields[3], $return_location );
		WC()->session->set( $fields[4], $return_same );
		WC()->session->set( $fields[5], $pickup_date_calc );
		WC()->session->set( $fields[6], $return_date_calc );
	}

	add_action( 'woocommerce_cart_updated', 'stm_wc_session' );


	function motors_get_system_datetime_format() {
		return sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
	}

	add_filter( 'motors_get_system_datetime_format', 'motors_get_system_datetime_format' );
}
