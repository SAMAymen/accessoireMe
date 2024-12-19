<?php
// phpcs:disable
// Declare Woo support
add_action( 'after_setup_theme', 'stm_woocommerce_support' );
function stm_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

// Remove Woo Breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_filter( 'filter_core_plugins', function ( $plug_data ) {
	if ( ! empty( $plug_data['core'] ) ) {
		if ( 'stm_vehicles_listing' === $plug_data['slug'] && apply_filters( 'stm_is_auto_parts', false ) || apply_filters( 'stm_is_rental_two', false ) ) {
			unset( $plug_data['core'] );
		}
	}

	return $plug_data;
}, 100, 1 );

if ( ! apply_filters( 'stm_is_auto_parts', true ) ) {
	add_filter( 'woocommerce_show_page_title', '__return_false' );
}

add_filter( 'loop_shop_per_page', 'stm_cols', 20, 1 );
function stm_cols( $cols ) {
	return 12;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'stm_woocommerce_header_add_to_cart_fragment' );
function stm_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	if ( 0 === $cart_count ) {
		if ( ! in_array( stm_get_header_layout(), array( 'boats', 'car_dealer_two', 'ev_dealer' ), true ) ) {
			$cart_count = '';
		}
	}

	ob_start();
	?>
	<span class="stm-current-items-in-cart">
		<?php echo esc_attr( $cart_count ); ?>
	</span>
	<?php
	$cart_count_html                             = ob_get_clean();
	$fragments['span.stm-current-items-in-cart'] = $cart_count_html;

	return $fragments;
}

add_filter( 'woocommerce_output_related_products_args', 'stm_related_products_args' );

function stm_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	return $args;
}

if ( apply_filters( 'stm_pricing_enabled', false ) ) {
	/*Remove Fields*/
	add_filter( 'woocommerce_checkout_fields', 'stm_override_checkout_fields' );

	if ( ! function_exists( 'stm_override_checkout_fields' ) ) {
		function stm_override_checkout_fields( $fields ) {
			( $fields['billing']['billing_address_1'] );
			( $fields['billing']['billing_address_2'] );
			( $fields['billing']['billing_city'] );
			( $fields['billing']['billing_postcode'] );
			( $fields['billing']['billing_country'] );
			( $fields['billing']['billing_state'] );

			return $fields;
		}
	}

	add_filter( 'woocommerce_add_to_cart_redirect', 'stm_woocommerce_add_to_cart_redirect' );
	function stm_woocommerce_add_to_cart_redirect( $url ) {
		if ( is_shop() ) {
			return $url;
		} else {
			return wc_get_checkout_url();
		}
	}

	if ( class_exists( 'STM_PostType' ) ) {
		STM_PostType::addMetaBox(
			'stm_pricing_plans',
			esc_html__( 'Pricing Plan Options (Works only with "Subscription" Product)', 'motors' ),
			array( 'product' ),
			'',
			'',
			'',
			array(
				'fields' =>
					array(
						'stm_price_plan_quota'       => array(
							'label' => __( 'Number of Slots', 'motors' ),
							'type'  => 'text',
						),
						'stm_price_plan_media_quota' => array(
							'label' => __( 'Number of Images per slot', 'motors' ),
							'type'  => 'text',
						),
						'stm_price_plan_role'        => array(
							'label'   => __( 'Price Plan User Role', 'motors' ),
							'type'    => 'select',
							'options' => array(
								'user'   => __( 'User', 'motors' ),
								'dealer' => __( 'Dealer', 'motors' ),
							),
						),
					),
			)
		);
	}

	add_action( 'init', 'stm_user_active_subscriptions' );
	add_action( 'subscriptio_status_changed', 'stm_move_draft_over_limit', 10, 3 );
	add_action( 'subscriptio_subscription_status_changed', 'stm_move_draft_over_limit', 10, 3 );

	add_action( 'subscriptio_subscription_status_changed', 'subscriptio_listing_status_change', 10, 3 );

	function subscriptio_listing_status_change( $subscription ) {
		$subscription_data  = $subscription->get_wc_object();
		$user_id = $subscription_data->get_customer_id();
		$user_subscriptions = subscriptio_get_customer_subscriptions( $user_id, true );

			foreach ( $user_subscriptions as $user_subscription ) {
				$listings = \MotorsVehiclesListing\Features\MultiplePlan::getListingIdsByPlanId( $user_subscription->get_id(), $user_id );

				foreach ( $listings as $listing ) {
					$post_data = array(
						'ID' => $listing->listing_id,
						'post_status' => $user_subscription->get_status() === 'active' ? 'publish' : 'pending',
						);
					wp_update_post( $post_data );
			}
		}
	}

	if ( ! function_exists( 'stm_user_active_subscriptions' ) ) {
		/**
		 * @param bool $get_paused
		 * @param int  $user_id
		 * @return array
		 */
		function stm_user_active_subscriptions( $get_paused = false, $user_id = 0 ) {
			/*
			 * TODO
			 * 'Subscriptio_User' will be removed
			*/
			$user_subscriptions  = ( class_exists( 'Subscriptio_User' ) ) ? Subscriptio_User::find_subscriptions( true, $user_id ) : subscriptio_get_customer_subscriptions( $user_id );
			$active_subscription = '';
			$has_active          = false;

			if ( $get_paused ) {
				$statuses = array( 'overdue', 'suspended' );
			} else {
				$statuses = array( 'active', 'trial' );
			}

			$status                   = '';
			$multi_plans_images_limit = [];

			foreach ( $user_subscriptions as $user_subscription ) {
				/*
				 * TODO
				 * 'Subscriptio_User' will be removed
				*/
				if ( ! $user_subscription || ( ! class_exists( 'Subscriptio_User' ) && empty( $user_subscription->get_initial_order() ) ) ) {
					continue;
				}

				$status = ( class_exists( 'Subscriptio_User' ) ) ? $user_subscription->status : $user_subscription->get_status();

				if ( in_array( $status, $statuses, true ) && ! $has_active ) {
					$active_subscription = $user_subscription;
					$has_active          = true;
				}
				if ( ! empty( $active_subscription ) && ( $active_subscription->get_id() !== $user_subscription->get_id() ) ) {
					$initial_order = $user_subscription->get_initial_order()->get_data();
					$key           = key( $initial_order['line_items'] );
					$order_data    = $initial_order['line_items'][ $key ]->get_data();
					$product_id    = $order_data['product_id'];
					$image_limit   = intval( get_post_meta( $product_id, 'stm_price_plan_media_quota', true ) );
					if ( $image_limit ) {
						$multi_plans_images_limit[$user_subscription->get_id()] = ["limit" => $image_limit];
					}
				}
			}

			$user_subscriptions      = $active_subscription;
			$user_subscription_quota = array();

			if ( ! empty( $user_subscriptions ) ) {
				/*
				 * TODO
				 * 'Subscriptio_User' will be removed
				 * */
				if ( class_exists( 'Subscriptio_User' ) ) {

					$plan_name     = ( ! empty( $user_subscriptions->products_multiple ) ) ? $user_subscriptions->products_multiple[0]['product_name'] : $user_subscriptions->product_name;
					$customer_id   = $user_subscriptions->user_id;
					$product_id    = $user_subscriptions->product_id;
					$last_order_id = $user_subscriptions->last_order_id;
					$expires       = $user_subscriptions->payment_due_readable;

					if ( empty( $product_id ) && ! empty( $user_subscriptions->products_multiple ) && is_array( $user_subscriptions->products_multiple ) ) {
						$products = $user_subscriptions->products_multiple;
						if ( ! empty( $products[0] ) && ! empty( $products[0]['product_id'] ) ) {
							$product_id = $products[0]['product_id'];
						}
					}
				} else {
					$initial_order = $user_subscriptions->get_initial_order()->get_data();
					$key           = key( $initial_order['line_items'] );
					$order_data    = $initial_order['line_items'][ $key ]->get_data();
					$plan_id       = $user_subscriptions->get_id();

					$plan_name     = $order_data['name'];
					$customer_id   = $user_subscriptions->get_customer_id();
					$product_id    = $order_data['product_id'];
					$last_order_id = $user_subscriptions->get_last_renewal_order_id();
					$expires       = ( ! empty( $user_subscriptions->get_scheduled_subscription_expire() ) ) ? $user_subscriptions->get_scheduled_subscription_expire()->format( 'm/d/Y H:i' ) : null;
				}

				$post_limit  = intval( get_post_meta( $product_id, 'stm_price_plan_quota', true ) );
				$image_limit = intval( get_post_meta( $product_id, 'stm_price_plan_media_quota', true ) );

				if ( ! empty( $image_limit ) ) {
					$multi_plans_images_limit[$user_subscriptions->get_id()] = ["limit" => $image_limit];
				}

				if ( ! empty( $post_limit ) && ! empty( $image_limit ) ) {
					$user_subscription_quota['user_id']                  = $customer_id;
					$user_subscription_quota['product_id']               = $product_id;
					$user_subscription_quota['plan_name']                = $plan_name;
					$user_subscription_quota['post_limit']               = $post_limit;
					$user_subscription_quota['image_limit']              = $image_limit;
					$user_subscription_quota['status']                   = $status;
					$user_subscription_quota['last_order_id']            = $last_order_id;
					$user_subscription_quota['expires']                  = $expires;
					$user_subscription_quota['plan_id']                  = $plan_id;
					$user_subscription_quota['multi_plans_images_limit'] = $multi_plans_images_limit;
				}
			}

			return $user_subscription_quota;
		}
	}




	if ( ! function_exists( 'stm_move_draft_over_limit' ) ) {
		/**
		 * @param $subscription
		 * @param $old_status
		 * @param $new_status
		 */
		function stm_move_draft_over_limit( $subscription, $old_status, $new_status ) {
			/*
			 * TODO
			 * 'Subscriptio_User' will be removed
			 *
			 * */
			if ( class_exists( 'Subscriptio_User' ) ) {
				$subs_id    = $subscription->id;
				$user_id    = $subscription->user_id;
				$product_id = $subscription->product_id;
			} else {
				$initial_order = ( $subscription->get_initial_order() ) ? $subscription->get_initial_order()->get_data() : null;
				$key           = ( $initial_order ) ? key( $initial_order['line_items'] ) : null;
				$order_data    = ( $initial_order ) ? $initial_order['line_items'][ $key ]->get_data() : null;

				$subs_id    = $subscription->get_id();
				$user_id    = $subscription->get_customer_id();
				$product_id = $order_data['product_id'];
			}

			$role = get_post_meta( $product_id, 'stm_price_plan_role', true );

			$listing_type = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

			if ( ! in_array( $new_status, array( 'active', 'trial' ), true ) ) {
				$user_limits = apply_filters(
					'stm_get_post_limits',
					array(
						'premoderation' => true,
						'posts_allowed' => 0,
						'posts'         => 0,
						'images'        => 0,
						'role'          => 'user',
					),
					$user_id
				);

				$posts_args = array(
					'orderby'        => 'post_date',
					'order'          => 'DESC',
					'post_type'      => $listing_type,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'key'     => 'stm_car_user',
							'value'   => $user_id,
							'compare' => '=',
						),
						array(
							'key'     => 'pay_per_listing',
							'compare' => 'NOT EXISTS',
							'value'   => '',
						),
					),
					'fields'         => 'ids',
				);

				$user_posts = get_posts( $posts_args );

				if ( count( $user_posts ) > $user_limits['posts_allowed'] ) {
					array_splice( $user_posts, 0, $user_limits['posts_allowed'] );
					foreach ( $user_posts as $user_post ) {
						$draft_post = array(
							'ID'          => $user_post,
							'post_status' => 'draft',
						);
						wp_update_post( $draft_post );
					}
				}

				/*Change user back to private if not admin*/
				if ( ! user_can( $user_id, 'manage_options' ) ) {
					wp_update_user(
						array(
							'ID'   => $user_id,
							'role' => 'subscriber',
						)
					);
				}
			} else {

				if ( 'active' === $new_status ) {

					$args = array(
						'post_type'      => $listing_type,
						'post_status'    => 'any',
						'posts_per_page' => -1,
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => 'stm_car_user',
								'value'   => $user_id,
								'compare' => '=',
							),
							array(
								'key'     => 'pay_per_listing',
								'compare' => 'NOT EXISTS',
								'value'   => '',
							),
						),
						'order'          => 'DESC',
						'orderby'        => 'ID',
					);

					$query = new WP_Query( $args );
					wp_reset_postdata();

					$post_limit = stm_user_active_subscriptions( false, $user_id );
					$post_limit = $post_limit['post_limit'];

					$posts = $query->posts;

					foreach ( $posts as $k => $val ) {
						if ( 'publish' === $val->post_status ) {
							wp_update_post(
								array(
									'ID'          => $val->ID,
									'post_status' => 'draft',
								)
							);
						}
					}

					foreach ( array_slice( $posts, 0, $post_limit ) as $k => $val ) {
						wp_update_post(
							array(
								'ID'          => $val->ID,
								'post_status' => 'publish',
							)
						);
					}
				}
			}

			if ( 'active' === $new_status && 'dealer' === $role ) {
				wp_update_user(
					array(
						'ID'   => $user_id,
						'role' => 'stm_dealer',
					)
				);
			}
		}
	}

	function stm_save_customer_note_meta( $subscription_id, $post ) {
		// If this isn't a 'subscription' post, don't update it.
		if ( 'subscription' !== $post->post_type ) {
			return;
		}

		if ( empty( get_post_meta( $subscription_id, 'renewal_customer_note', true ) ) ) {
			update_post_meta( $subscription_id, 'renewal_customer_note', '' );
		}
	}

	add_action( 'save_post', 'stm_save_customer_note_meta', 10, 2 );
}

add_action( 'after_setup_theme', 'stm_woo_setup' );

function stm_woo_setup() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

function stm_add_link_to_order( $item_id, $item ) {
	$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

    if ( ! $product ) {
        return;
    }

    $pay_per_listing = $item->get_meta( '_order_pay_per_listing' );
	$make_featured   = $item->get_meta( '_car_make_featured' );

	if ( 'yes' === $pay_per_listing ) {
		echo '<tbody><tr><td colspan="6"><b>' . esc_html__( 'Please publish this Listing manually', 'motors' ) . '</b> <a href="' . esc_url( admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' ) ) . '">' . esc_html( admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' ) ) . '</a></td></tr></tbody>';
	}

	if ( 'yes' === $make_featured ) {
		echo '<tbody><tr><td colspan="6"><b>' . esc_html__( 'Please make featured this Listing manually', 'motors' ) . '</b> <a href="' . esc_url( admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' ) ) . '">' . esc_html( admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' ) ) . '</a></td></tr></tbody>';
	}
}

add_action( 'woocommerce_order_item_line_item_html', 'stm_add_link_to_order', 10, 2 );



// change paid feature order status on post save
if ( ! function_exists( 'stm_motors_change_paid_feature_order_status' ) ) {

	add_action( 'save_post', 'stm_motors_change_paid_feature_order_status', 10, 2 );

	function stm_motors_change_paid_feature_order_status( $post_id, $post ) {

		$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return;
		}

		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'orderby'      => 'date',
				'order'        => 'DESC',
				'meta_key'     => '_car_make_featured',
				'meta_value'   => 'yes',
				'meta_compare' => '=',
			)
		);

		if ( ! empty( $orders ) ) {
			foreach ( $orders as $order ) {
				$order->update_status( 'cancelled' );
			}
		}
	}
}



add_action( 'woocommerce_order_status_changed', 'stm_make_featured_status_changed', 10, 3 );
function stm_make_featured_status_changed( $order_id, $old_status, $new_status ) {
	// Load order object
    $order = wc_get_order( $order_id );

	// Check if order was loaded
    if ( ! $order ) {
        return;
    }

	// Get customer ID
	$customer = $order->get_user();

	// Get order items
	$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

	// Iterate over order items and get product ids
    foreach ( $order_items as $order_item ) {
	    $pay_per_listing = $order_item->get_meta( '_order_pay_per_listing' );
	    $product         = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );

	    if ( ! $product ) {
		    continue;
	    }

	    $listing_id    = $product->get_id();
	    $date          = new DateTime();
		$premoderation = false;

		if ( $customer && $customer->exists() ) {
			$_option_name  = apply_filters( 'stm_get_user_role', false, $customer->ID ) ? 'dealer_premoderation' : 'user_premoderation';

			$premoderation = apply_filters( 'motors_vl_get_nuxy_mod', false, $_option_name );
		}

	    if ( 'yes' === $pay_per_listing  && 'completed' === $new_status ) {
			update_post_meta( $listing_id, 'pay_per_create_date', $date->getTimestamp() );

			if ( ! $premoderation ) {
				$listing = array(
					'ID'          => $listing_id,
					'post_status' => 'publish',
				);
				wp_update_post( $listing );
			}
	    } else {

            $make_feature = $order_item->get_meta( '_car_make_featured' );

            if ( 'yes' !== $make_feature ) {
	            continue;
            }

		    $featured_label_text = apply_filters( 'motors_vl_get_nuxy_mod', 'Featured', 'featured_listing_default_badge' );

		    // multilisting compatibility.
		    if ( stm_is_multilisting() ) {

			    $post_type = get_post_type( $listing_id );

			    if ( apply_filters( 'stm_listings_post_type', 'listings' ) !== $post_type ) {

				    $ml = new STMMultiListing();

				    if ( $ml->stm_get_listing_type_settings( 'inventory_custom_settings', $post_type ) === true ) {

					    $custom_feat_text = $ml->stm_get_listing_type_settings( 'featured_listing_default_badge', $post_type );
					    if ( ! empty( $custom_feat_text ) ) {
						    $featured_label_text = $custom_feat_text;
					    }
				    }
			    }
		    }

		    if ( ! empty( $listing_id ) && 'completed' === $new_status ) {
			    update_post_meta( $listing_id, 'car_make_featured_status', $new_status );
			    update_post_meta( $listing_id, 'special_car', 'on' );
			    update_post_meta( $listing_id, 'badge_text', $featured_label_text );
			    update_post_meta( $listing_id, 'pay_featured_create_date', $date->getTimestamp() );
		    } elseif ( ! empty( $listing_id ) && in_array( $new_status, array( 'pending', 'processing', 'on-hold', 'cancelled', 'refunded', 'failed' ), true ) ) {
			    update_post_meta( $listing_id, 'car_make_featured_status', $new_status );
			    delete_post_meta( $listing_id, 'special_car' );
			    delete_post_meta( $listing_id, 'badge_text' );
		    }
	    }
    }
}


// "cash on delivery", "bank transfer" or "cheque" payment methods set order status to "on hold" upon completion.
add_filter( 'woocommerce_cod_process_payment_order_status', 'stm_change_cod_payment_order_status', 10, 2 );
add_filter( 'woocommerce_bacs_process_payment_order_status', 'stm_change_cod_payment_order_status', 10, 2 );
add_filter( 'woocommerce_cheque_process_payment_order_status', 'stm_change_cod_payment_order_status', 10, 2 );
function stm_change_cod_payment_order_status( $order_status, $order ) {
	return 'on-hold';
}


add_action( 'woocommerce_add_to_cart_fragments', 'stm_cart_2_update_totals', 2000 );
function stm_cart_2_update_totals( $fragments ) {
	if ( function_exists( 'stm_hb_load_element' ) ) {
		global $wpdb;

		ob_start();
		stm_hb_load_element( 'cart', array(), 'quantity' );
		$quantity = ob_get_contents();
		ob_end_clean();

		$fragments['.cart__quantity-badge'] = $quantity;
	}
		$fragments['.cart-total-price']     = '<span class="cart-total-price">' . WC()->cart->get_cart_total() . '</span>';

	return $fragments;
}

if ( ! function_exists( 'stm_wc_get_product_type' ) ) {
	function stm_wc_get_product_type( $product_id ) {
		$product = wc_get_product( $product_id );

		return $product->get_type();
	}
}
