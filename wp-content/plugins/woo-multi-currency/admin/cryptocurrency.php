<?php

/**
 * Class WOOMULTI_CURRENCY_F_Admin_Cryptocurrency
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Admin_Cryptocurrency {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		add_filter( 'woocommerce_currencies', array( $this, 'woocommerce_currencies' ) );
		add_filter( 'woocommerce_currency_symbols', array( $this, 'woocommerce_currency_symbols' ) );
	}

	/**
	 * @param $currency
	 *
	 * @return mixed
	 */
	public function woocommerce_currencies( $currency ) {
		if ( is_admin() || $this->settings->get_enable() ) {
			$currency['LTC'] = esc_html__( 'Litecoin', 'woo-multi-currency' );
			$currency['ETH'] = esc_html__( 'Ethereum', 'woo-multi-currency' );
			$currency['ZWL'] = esc_html__( 'Zimbabwe', 'woo-multi-currency' );
		}

		return $currency;
	}

	/**
	 * @param $symbols
	 *
	 * @return mixed
	 */
	public function woocommerce_currency_symbols( $symbols ) {
		if ( is_admin() || $this->settings->get_enable() ) {
			$symbols['LTC'] = "LTC";
			$symbols['ETH'] = "ETH";
			$symbols['ZWL'] = "ZWL";
		}

		return $symbols;
	}
}