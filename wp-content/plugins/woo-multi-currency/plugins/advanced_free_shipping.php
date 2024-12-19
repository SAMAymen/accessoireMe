<?php

/**
 * Class WOOMULTI_CURRENCY_F_F_Plugin_Advanced_Free_Shipping
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Advanced_Free_Shipping {
	protected $settings;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'wp-conditions\condition', array( $this, 'advanced_free_shipping' ) );
		}
	}

	/**
	 * WooCommerce Advanced Free Shipping
	 *
	 * @param $data
	 *
	 * @return mixed
	 */

	public function advanced_free_shipping( $data ) {

		if ( isset( $data['value'] ) && ( $data['condition'] == 'subtotal' || $data['condition'] == 'coupon' || $data['condition'] == 'tax' || $data['condition'] == 'subtotal_ex_tax' ) ) {
			$data['value'] = wmc_get_price( $data['value'] );
		}

		return $data;
	}
}