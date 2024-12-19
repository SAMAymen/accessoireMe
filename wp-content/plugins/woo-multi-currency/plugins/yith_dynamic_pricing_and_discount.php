<?php
/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Yith_Dynamic_Pricing_And_Discount
 * Author: Yith
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Yith_Dynamic_Pricing_And_Discount {
	public function __construct() {
		add_filter( 'ywdpd_change_dynamic_price', array( $this, 'ywdpd_change_dynamic_price' ) );
//		add_filter( 'ywdpd_maybe_should_be_converted', array( $this, 'ywdpd_maybe_should_be_converted' ) );

		add_filter( 'ywdpd_price_rule_get_gift_subtotal', array( $this, 'convert_amount' ) );
		add_filter( 'ywdpd_maybe_should_be_converted', array( $this, 'convert_amount' ), 99, 1 );
		add_filter( 'ywdpd_cart_item_display_price', array( $this, 'convert_amount' ), 99, 1 );
		add_filter( 'ywdpd_cart_item_adjusted_price', array( $this, 'convert_amount' ), 99, 1 );
		add_filter( 'ywdpd_cart_rule_get_minimum_subtotal', array( $this, 'convert_amount' ), 99, 1 );
		add_filter( 'ywdpd_cart_rule_get_maximum_subtotal', array( $this, 'convert_amount' ), 99, 1 );
//		add_filter( 'yith_wcmcs_apply_currency_filters', array( $this, 'apply_currency_filter' ), 20 );

		/*ywdpd_before_calculate_discounts and ywdpd_after_calculate_discounts hooks called in frontend/price.php*/
	}

	public function convert_amount( $price ) {
		return wmc_get_price( $price );
	}

	public function ywdpd_change_dynamic_price( $price ) {
		return wmc_revert_price( $price );
	}

	public function ywdpd_maybe_should_be_converted( $price ) {
		return wmc_get_price( $price );
	}
}