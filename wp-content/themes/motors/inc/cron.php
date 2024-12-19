<?php
function checkPayPerListings() {
	global $wpdb;

	$pay_per_listing_period = apply_filters( 'motors_vl_get_nuxy_mod', '30', 'pay_per_listing_period' );

	if ( 0 === $pay_per_listing_period ) {
		return;
	}

	$today   = new DateTime();
	$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'pay_per_create_date'" );

	if ( $results ) {
		foreach ( $results as $val ) {
			$datetime1 = new DateTime( date( 'Y-m-d', $val->meta_value ) );
			$datetime2 = new DateTime( date( 'Y-m-d', $today->getTimestamp() ) );

			$diff = (array) $datetime2->diff( $datetime1 );

			if ( $pay_per_listing_period < $diff['days'] ) {
				$listing = array(
					'ID'          => $val->post_id,
					'post_status' => 'pending',
				);
				delete_post_meta( $val->post_id, 'pay_per_create_date' );
				wp_update_post( $listing );
			}
		}
	}
}

function checkPayFeaturedListings() {
	global $wpdb;

	$pay_per_listing_period = apply_filters( 'motors_vl_get_nuxy_mod', '30', 'featured_listing_period' );

	if ( 0 === $pay_per_listing_period ) {
		return;
	}

	$today   = new DateTime();
	$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'pay_featured_create_date'" );

	if ( $results ) {
		foreach ( $results as $val ) {
			$listing_id = $val->post_id;
			$datetime1  = new DateTime( date( 'Y-m-d', $val->meta_value ) );
			$datetime2  = new DateTime( date( 'Y-m-d', $today->getTimestamp() ) );

			$diff = (array) $datetime2->diff( $datetime1 );

			if ( $pay_per_listing_period < $diff['days'] ) {
				delete_post_meta( $listing_id, 'car_make_featured_status', '' );
				delete_post_meta( $listing_id, 'pay_featured_create_date', '' );
				delete_post_meta( $listing_id, 'special_car', '' );
				delete_post_meta( $listing_id, 'badge_text', '' );
			}
		}
	}
}

if ( ! function_exists( 'stm_checkPayPerFeatured' ) ) {
	function stm_checkPayPerFeatured() {
		checkPayPerListings();
		checkPayFeaturedListings();
	}
}

function stm_start_listings_cron() {
	if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
		if ( ! wp_next_scheduled( 'checkPayPerFeatured' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'checkPayPerFeatured' );
		}
	}
}

add_action( 'checkPayPerFeatured', 'stm_checkPayPerFeatured' );
add_action( 'init', 'stm_start_listings_cron' );


function stm_check_is_past_date_and_del_meta() {

	global $wpdb;
	$orders = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = 'in_rent'" );

	if ( $orders ) {
		foreach ( $orders as $order ) {
			$order_post_id = $order->post_id;

			$order_car_data = get_post_meta( $order_post_id, 'order_car_date', true );

			if ( is_array( $order_car_data ) ) {

				$current_datetime = current_datetime();
				$check_date       = $order_car_data['calc_return_date'];

				if ( ( strtotime( $check_date ) - strtotime( $current_datetime->format( 'Y-m-d H:i:s' ) ) ) < 0 ) {
					$meta_keys = $wpdb->get_row( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", 'order_meta_dates_' . $order_post_id ) );

					$dates = explode( ',', $meta_keys->meta_value );

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
					delete_post_meta( $meta_keys->post_id, 'order_meta_dates_' . $order_post_id );
					delete_post_meta( $order_post_id, 'stm_order_status' );
				}
			}
		}
	}
}

if ( ! function_exists( 'stm_rentalCheckPastOrders' ) ) {
	function stm_rentalCheckPastOrders() {
		stm_check_is_past_date_and_del_meta();
	}
}

function stm_check_past_orders_cron() {
	$listing = get_stm_theme_demo_layout();
	if ( ! empty( $listing ) && ( 'car_rental' === $listing || 'rental_two' === $listing ) ) {
		if ( ! wp_next_scheduled( 'rentalCheckPastOrders' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'rentalCheckPastOrders' );
		}
	}
}

add_action( 'rentalCheckPastOrders', 'stm_rentalCheckPastOrders' );
add_action( 'init', 'stm_check_past_orders_cron' );
