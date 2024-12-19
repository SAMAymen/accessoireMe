<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Payments
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Payments {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			$list_currencies = $this->settings->get_list_currencies();
			if ( count( $list_currencies ) ) {
				foreach ( $list_currencies as $currency => $currency_info ) {
					add_filter( 'wcpay_' . strtolower( $currency ) . '_format', array(
						$this,
						'wcpay_currency_format'
					), PHP_INT_MAX );
				}
			}
			add_filter( 'wcpay_multi_currency_should_convert_product_price', '__return_false' );
		}
	}

	/**
	 * Override currency format
	 *
	 * @param $format
	 *
	 * @return mixed
	 */
	public function wcpay_currency_format( $format ) {
		$list_currencies  = $this->settings->get_list_currencies();
		$current_currency = $this->settings->get_current_currency();
		if ( isset( $list_currencies[ $current_currency ] ) ) {
			if ( $list_currencies[ $current_currency ]['pos'] ) {
				$format['currency_pos'] = $list_currencies[ $current_currency ]['pos'];
			}
			$format['thousand_sep'] = get_option( 'woocommerce_price_thousand_sep' );
			$format['decimal_sep']  = get_option( 'woocommerce_price_decimal_sep' );
			$format['num_decimals'] = $list_currencies[ $current_currency ]['decimals'];
		}

		return $format;
	}
}