<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Name_Your_Price
 * WooCommerce TM Extra Product Options By ThemeComplete
 */
class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Name_Your_Price {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() && is_plugin_active( 'woocommerce-name-your-price/woocommerce-name-your-price.php' ) ) {
			add_action( 'init', array( $this, 'init_hooks' ) );
		}
	}

	public function init_hooks() {
		// Compatible with WC Name Your Price
		if ( is_callable( array(
				'WC_Name_Your_Price_Compatibility',
				'is_nyp_gte'
			) ) && WC_Name_Your_Price_Compatibility::is_nyp_gte( '3.0' ) ) {
			add_filter( 'wc_nyp_raw_minimum_variation_price', array( $this, 'change_price' ) );
			add_filter( 'wc_nyp_raw_minimum_price', array( $this, 'change_price' ) );
			add_filter( 'wc_nyp_raw_suggested_price', array( $this, 'change_price' ) );
			add_filter( 'wc_nyp_raw_maximum_price', array( $this, 'change_price' ) );
			add_filter( 'wc_nyp_get_posted_price', array( $this, 'wc_nyp_get_posted_price' ), 10, 3 );
			add_filter( 'wc_nyp_price_input_attributes', array( $this, 'wc_nyp_price_input_attributes' ), 10, 3 );
		} else {
			add_filter( 'woocommerce_raw_minimum_variation_price', array( $this, 'change_price' ) );
			add_filter( 'woocommerce_raw_minimum_price', array( $this, 'change_price' ) );
			add_filter( 'woocommerce_raw_suggested_price', array( $this, 'change_price' ) );
			add_filter( 'woocommerce_raw_maximum_price', array( $this, 'change_price' ) );
		}
	}

	/**
	 * @param $price_raw
	 *
	 * @return float|int|mixed|void
	 */
	public function change_price( $price_raw ) {
		return $price_raw ? wmc_get_price( $price_raw ) : $price_raw;
	}

	/**
	 * @param $args
	 * @param $product
	 * @param $suffix
	 *
	 * @return mixed
	 */
	public function wc_nyp_price_input_attributes( $args, $product, $suffix ) {
		if ( $args['input_value'] ) {
			$args['input_value'] = wmc_get_price( $args['input_value'] );
		}

		return $args;
	}

	/**
	 * @param $posted_price
	 * @param $product
	 * @param $suffix
	 *
	 * @return bool|float|int|string
	 */
	public function wc_nyp_get_posted_price( $posted_price, $product, $suffix ) {
		$posted_price = wmc_revert_price( $posted_price );

		return $posted_price;
	}
}
