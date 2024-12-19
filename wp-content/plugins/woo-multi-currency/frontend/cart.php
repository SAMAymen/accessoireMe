<?php

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Cart
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Frontend_Cart {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			/*Fix round function with case default decimals = 0 and other currency decimal > 0*/
			add_filter( 'woocommerce_calculated_total', array( $this, 'woocommerce_calculated_total' ), 10, 2 );
		}
	}

	/**
	 * @param $total
	 * @param $cart WC_Cart
	 *
	 * @return string
	 */
	public function woocommerce_calculated_total( $total, $cart ) {
		$list_currencies  = $this->settings->get_list_currencies();
		$current_currency = $this->settings->get_current_currency();
		$default_currency = $this->settings->get_default_currency();
		if ( $list_currencies[ $default_currency ]['decimals'] > 0 ) {
			return $total;
		}

		if ( $list_currencies[ $current_currency ]['decimals'] ) {
			$new_total = $cart->get_cart_contents_total() + $cart->get_fee_total() + $cart->get_shipping_total() + $cart->get_total_tax();
			if ( $new_total > $total ) {
				$total = number_format( $new_total, $list_currencies[ $current_currency ]['decimals'], wc_get_price_decimal_separator(), '' );
			}
		}

		return $total;
	}
}