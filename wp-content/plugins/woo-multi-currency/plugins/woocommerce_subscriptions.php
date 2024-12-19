<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Subscriptions
 * Author: WooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Subscriptions {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() && is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			add_filter( 'woocommerce_subscriptions_product_price', array( $this, 'get_price' ) );
			add_filter( 'woocommerce_subscriptions_product_sale_price', array( $this, 'revert_sale_price' ), 10, 2 );
			add_filter( 'woocommerce_subscriptions_product_sign_up_fee', array(
				$this,
				'woocommerce_subscriptions_product_sign_up_fee'
			) );
			/*Use fixed price if enabled*/
			add_filter( 'woocommerce_subscriptions_product_price', array(
				$this,
				'woocommerce_subscriptions_product_price'
			), 10, 2 );
			/*Convert renewal cart to default currency*/
			add_action( 'woocommerce_load_cart_from_session', array(
				$this,
				'woocommerce_load_cart_from_session'
			) );
			add_action( 'woocommerce_cart_loaded_from_session', array(
				$this,
				'woocommerce_cart_loaded_from_session'
			) );
		}
	}

	/**
	 * @param $price
	 *
	 * @return float|int|mixed
	 */
	public function get_price( $price ) {
		return wmc_get_price( $price );
	}

	/**
	 * @param $sale_price
	 * @param $product
	 *
	 * @return mixed
	 */
	public function revert_sale_price( $sale_price, $product ) {
		$sale_price = $product->get_sale_price( 'edit' );

		return $sale_price;
	}

	/**
	 * Simple subscription
	 *
	 * @param $price
	 *
	 * @return mixed
	 */
	public function woocommerce_subscriptions_product_sign_up_fee( $price ) {
		return wmc_get_price( $price );
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_subscriptions_product_price( $price, $product ) {
		if ( $product ) {
			if ( $this->settings->check_fixed_price() ) {
				$current_currency = $this->settings->get_current_currency();
				if ( $current_currency !== $this->settings->get_default_currency() ) {
					$product_price = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_regular_price_wmcp', true ), true ) );
					$sale_price    = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_sale_price_wmcp', true ), true ) );
					if ( isset( $product_price[ $current_currency ] ) && ! $product->is_on_sale( 'edit' ) && $product_price[ $current_currency ] > 0 ) {
						$price = $product_price[ $current_currency ];
					} elseif ( isset( $sale_price[ $current_currency ] ) && $sale_price[ $current_currency ] > 0 ) {
						$price = $sale_price[ $current_currency ];
					}
				}
			}
		}

		return $price;
	}

	/**
	 *
	 */
	public function woocommerce_load_cart_from_session() {
		add_filter( 'woocommerce_order_get_items', array(
			$this,
			'woocommerce_order_get_items'
		), 10, 2 );
	}

	/**
	 *
	 */
	public function woocommerce_cart_loaded_from_session() {
		remove_filter( 'woocommerce_order_get_items', array( $this, 'woocommerce_order_get_items' ) );
	}

	/**
	 * @param $items
	 * @param $order WC_Order
	 *
	 * @return array
	 */
	public function woocommerce_order_get_items( $items, $order ) {
		if ( ! wcs_is_subscription( $order ) ) {
			return $items;
		}
		$renewal_order_id = $order->get_id();
		$related_order_id = wp_get_post_parent_id( $renewal_order_id );
		if ( $related_order_id ) {
			$order_currency = get_post_meta( $related_order_id, '_order_currency', true );
			$wmc_order_info = get_post_meta( $related_order_id, 'wmc_order_info', true );
		} else {
			$order_currency = $order->get_meta('_order_currency', true );
			$wmc_order_info = $order->get_meta('wmc_order_info', true );
		}
		$default_currency = $this->settings->get_default_currency();
		$list_currencies  = $this->settings->get_list_currencies();
		/*Skip if base currency is different*/
		if ( $wmc_order_info && ( ! isset( $wmc_order_info[ $default_currency ] ) || ! isset( $wmc_order_info[ $default_currency ]['is_main'] ) || $wmc_order_info[ $default_currency ]['is_main'] != 1 ) ) {
			return $items;
		}
		/*Skip if order currency does not exist*/
		if ( ! isset( $list_currencies[ $order_currency ] ) ) {
			return $items;
		}
		$return_items = array();

		foreach ( $items as $item_id => $item ) {
			if ( $item && is_a( $item, 'WC_Order_Item_Product' ) ) {
				$item = clone $item;
				$item->set_subtotal( wmc_revert_price( $item->get_subtotal(), $order_currency ) );
				$item->set_total( wmc_revert_price( $item->get_total(), $order_currency ) );
			}
			$return_items[ $item_id ] = $item;
		}

		return $return_items;
	}
}