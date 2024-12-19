<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Coupon
 */
class WOOMULTI_CURRENCY_F_Frontend_Coupon {
	protected $settings;
	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'woocommerce_coupon_get_amount', array( $this, 'woocommerce_coupon_get_amount' ), 10, 2 );
			add_filter( 'woocommerce_coupon_get_minimum_amount', array(
				$this,
				'woocommerce_coupon_get_minimum_amount'
			) );
			add_filter( 'woocommerce_coupon_get_maximum_amount', array(
				$this,
				'woocommerce_coupon_get_maximum_amount'
			) );
			add_filter( 'woocommerce_boost_sales_coupon_amount_price', array(
				$this,
				'woocommerce_boost_sales_coupon_amount_price'
			) );
		}
	}

	/**
	 * Apply with percent
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function woocommerce_coupon_get_amount( $data, $obj ) {
		if ( $obj->is_type( array( 'percent', 'recurring_percent', 'sign_up_fee_percent' ) ) ) {
			return $data;
		}

		return wmc_get_price( $data );
	}


	public function woocommerce_boost_sales_coupon_amount_price( $data ) {
		return wmc_get_price( $data );
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function woocommerce_coupon_get_minimum_amount( $data ) {

		return wmc_get_price( $data, false, false, true );
	}

	public function woocommerce_coupon_get_maximum_amount( $data ) {
		return wmc_get_price( $data, false, false, true );
	}
}
