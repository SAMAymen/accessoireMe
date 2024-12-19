<?php

/**
 * Integrate with PPOM for WooCommerce by N-MEDIA - Najeeb Ahmad
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Product_Addon {
	protected static $settings;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		add_filter( 'ppom_product_price_on_cart', array( $this, 'ppom_product_price_on_cart' ), 10, 2 );
		add_filter( 'ppom_product_price', array( $this, 'ppom_product_price' ), 10, 2 );
		add_filter( 'ppom_option_price', array( $this, 'ppom_option_price' ), 10 );
		add_filter( 'ppom_cart_fixed_fee', array( $this, 'ppom_cart_fixed_fee' ) );
	}

	/**
	 * @param $product_price
	 * @param $product
	 *
	 * @return bool|float|int|string
	 */
	public function ppom_product_price( $product_price, $product ) {
		return wmc_revert_price( $product_price );
	}

	/**
	 * @param $fee_price
	 *
	 * @return float|int|mixed|void
	 */
	public function ppom_cart_fixed_fee( $fee_price ) {
		return wmc_get_price( $fee_price );
	}

	/**
	 * @param $option_price
	 *
	 * @return float|int|mixed|void
	 */
	public function ppom_option_price( $option_price ) {
		return wmc_get_price( $option_price );
	}

	/**
	 * @param $value
	 * @param $cart_item
	 *
	 * @return mixed
	 */
	public function ppom_product_price_on_cart( $value, $cart_item ) {
		$wc_product = $cart_item['data'];
		if ( $wc_product ) {
			$value = $wc_product->get_price( 'edit' );
		}

		return $value;
	}
}