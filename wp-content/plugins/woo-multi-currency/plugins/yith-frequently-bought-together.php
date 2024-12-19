<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Yith_Frequently_Bought_Together
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Yith_Frequently_Bought_Together {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'woocommerce_coupon_get_amount', array( $this, 'woocommerce_coupon_get_amount' ), 20, 2 );
			add_filter( 'yith_wfbt_total_html', array(
				$this,
				'yith_wfbt_total_html'
			), 10, 5 );
			add_filter( 'yith_wfbt_discount_html', array(
				$this,
				'yith_wfbt_discount_html'
			), 10, 3 );
		}
	}

	/**
	 * @param $data
	 * @param $obj WC_Coupon
	 *
	 * @return float|int|mixed|void
	 */
	public function woocommerce_coupon_get_amount( $data, $obj ) {
		if ( is_plugin_active( 'yith-woocommerce-frequently-bought-together-premium/init.php' ) ) {
			$yith_coupon = yith_wfbt_discount_code_validation( get_option( 'yith-wfbt-discount-name', 'frequently-bought-discount' ) );
			$coupon_code = $obj->get_code();
			if ( ( $yith_coupon && $coupon_code === $yith_coupon ) || $coupon_code === 'frequently-bought-discount' ) {
				$data = wmc_revert_price( $data );
			}
		}

		return $data;
	}

	/**
	 * @param $total_html
	 * @param $total
	 * @param $total_discount
	 * @param $product_id
	 * @param $products
	 *
	 * @return string
	 */
	public function yith_wfbt_total_html( $total_html, $total, $total_discount, $product_id, $products ) {
		$total_html = ! is_null( $total_discount ) ? do_shortcode( "[woo_multi_currency_exchange price=" . wmc_revert_price( $total_discount ) . " original_price=" . wmc_revert_price( $total ) . "]" ) : do_shortcode( "[woo_multi_currency_exchange price=" . wmc_revert_price( $total ) . "]" );

		return $total_html;
	}

	public function yith_wfbt_discount_html( $discount, $product_id, $products ) {

		return do_shortcode( "[woo_multi_currency_exchange price=" . wmc_revert_price( $discount ) . "]" );
	}
}