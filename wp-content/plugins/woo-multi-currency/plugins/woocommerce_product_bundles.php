<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Product_Bundles
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Product_Bundles {
	protected $settings;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'woocommerce_bundle_front_end_params', array( $this, 'woocommerce_bundle_front_end_params' ) );
		}
	}

	/**
	 * Integrate with Yith Product Bundles
	 * @return bool
	 */
	public function woocommerce_bundle_front_end_params( $data ) {
		if ( isset( $data['currency_symbol'] ) ) {
			preg_match( '/#PRICE#/i', $data['currency_symbol'], $result );
			if ( count( array_filter( $result ) ) ) {
				$data['currency_symbol'] = str_replace( '#PRICE#', '', $data['currency_symbol'] );
			}
		}

		return $data;
	}
}