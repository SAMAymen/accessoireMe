<?php

/**
 * Class WOOMULTI_CURRENCY_Plugin_WooCommerce_Smart_COD
 * Plugin WooCommerce Smart COD
 * Author: woosmartcod.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Smart_COD {
	protected static $settings;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( self::$settings->get_enable() ) {
			add_filter( 'wc_smart_cod_fee', array( $this, 'wc_smart_cod_fee' ) );
		}
	}

	/**
	 * WooCommerce Advanced Free Shipping
	 *
	 * @param $data
	 *
	 * @return mixed
	 */

	public function wc_smart_cod_fee( $extra_fee ) {
		if ( is_numeric( $extra_fee ) ) {
			return wmc_get_price( $extra_fee );
		}

		return $extra_fee;
	}
}