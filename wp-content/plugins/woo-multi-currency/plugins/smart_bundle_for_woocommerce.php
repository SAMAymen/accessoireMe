<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Smart_Bundle_For_WooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Smart_Bundle_For_WooCommerce {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			if ( is_plugin_active( 'smart-bundle-for-woocommerce/smart-bundle-woocommerce.php' ) ) {
				add_action( 'woocommerce_before_calculate_totals', array(
					$this,
					'wcbp_bundle_product_before_calculate_totals'
				), 11, 1 );
			}
		}
	}

	public function wcbp_bundle_product_before_calculate_totals( $cart_object ) {
		//  This is necessary for WC 3.0+
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( $this->settings->get_current_currency() === $this->settings->get_default_currency() ) {
			return;
		}
		foreach ( $cart_object->get_cart() as $cart_item_key => $cart_item ) {
			// child product price
			if ( isset( $cart_item['wcbp_bundle_product_parent_id'] ) && ( '' != $cart_item['wcbp_bundle_product_parent_id'] ) ) {
				$cart_item['data']->set_price( 0 );
			}

			// main product price
			if ( isset( $cart_item['wcbp_product_bundle_ids'] ) && ( '' != $cart_item['wcbp_product_bundle_ids'] ) ) {
				$wcbp_bundle_product_items = explode( ',', $cart_item['wcbp_product_bundle_ids'] );
				$wcbp_bundle_product_items = array_count_values( $wcbp_bundle_product_items );
				$product_id                = $cart_item['product_id'];
				//$cart_item['data']->set_tax_status('none');
				$wcbp_bundle_product_price = 0;
				$pricing_type              = get_post_meta( $product_id, 'wcbp_bundle_prod_pricing', true );

				if ( 'per_product_only' == $pricing_type || 'per_product_bundle' == $pricing_type ) {
					if ( is_array( $wcbp_bundle_product_items ) && count( $wcbp_bundle_product_items ) > 0 ) {
						foreach ( $wcbp_bundle_product_items as $wcbp_bundle_product_item => $qty ) {
							$wcbp_bundle_product_item_id      = absint( $wcbp_bundle_product_item ? $wcbp_bundle_product_item : 0 );
							$wcbp_bundle_product_item_qty     = absint( $qty ? $qty : 1 );
							$wcbp_bundle_product_item_product = wc_get_product( $wcbp_bundle_product_item_id );

							if ( ! $wcbp_bundle_product_item_product || $wcbp_bundle_product_item_product->is_type( 'bundle_product' ) ) {
								continue;
							}

							// Quantity issue calculation fixed  	
							foreach ( $cart_object->get_cart() as $cart_item_key_for_qty => $cart_item_for_qty ) {
								if ( $cart_item_for_qty['product_id'] == $wcbp_bundle_product_item_id ) {
									$cart_item_qty = $cart_item_for_qty['quantity'];
								}
							}
							$wcbp_bundle_product_price += wmc_revert_price( $wcbp_bundle_product_item_product->get_price() ) * $cart_item_qty;
							// Quantity issue calculation fixed  	
						}
					}
				} else {
					$bundle_product            = wc_get_product( $product_id );
					$box_price                 = wmc_revert_price( $bundle_product->get_price() );
					$wcbp_bundle_product_price = $box_price;
				}
				// per item + base price
				if ( ( 'per_product_bundle' == $pricing_type ) && is_numeric( $wcbp_bundle_product_price ) ) {
					$bundle_product            = wc_get_product( $product_id );
					$box_price                 = wmc_revert_price( $bundle_product->get_price() );
					$wcbp_bundle_product_price += $box_price;
				}

				$cart_item['data']->set_price( floatval( $wcbp_bundle_product_price ) );
			}
		}
	}
}