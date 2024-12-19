<?php

/**Subscriptions for WooCommerce from WebToffee
 * Class WOOMULTI_CURRENCY_F_Plugin_Subscriptions_For_WooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Subscriptions_For_WooCommerce {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'hf_subscription_product_price', array( $this, 'hf_subscription_product_price' ), 10, 2 );
			add_filter( 'hf_subscriptions_product_signup_fee', array( $this, 'hf_subscriptions_product_signup_fee' ) );
		}
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return float|int|mixed
	 */
	public function hf_subscription_product_price( $price, $product ) {
		if ( $product ) {
			if ( $this->settings->check_fixed_price() ) {
				$product_price    = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_regular_price_wmcp', true ), true ) );
				$sale_price       = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_sale_price_wmcp', true ), true ) );
				$current_currency = $this->settings->get_current_currency();
				if ( isset( $product_price[ $current_currency ] ) && ! $product->is_on_sale( 'edit' ) && $product_price[ $current_currency ] > 0 ) {
					return $product_price[ $current_currency ];
				} elseif ( isset( $sale_price[ $current_currency ] ) && $sale_price[ $current_currency ] > 0 ) {
					return $sale_price[ $current_currency ];
				}
			}
		}

		return wmc_get_price( $price );
	}

	/**
	 * @param $price
	 *
	 * @return float|int|mixed
	 */
	public function hf_subscriptions_product_signup_fee( $price ) {
		return wmc_get_price( $price );
	}
}