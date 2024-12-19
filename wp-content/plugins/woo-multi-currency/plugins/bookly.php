<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Bookly
 * Author: Bookly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Bookly\Lib as BooklyLib;
class WOOMULTI_CURRENCY_F_Plugin_Bookly {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( is_plugin_active( 'bookly-responsive-appointment-booking-tool/main.php' ) ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'woocommerce_cart_item_price' ), 20, 3 );
		}
	}

	public function woocommerce_cart_item_price( $product_price, $wc_item, $cart_item_key ) {
		if ( isset ( $wc_item['bookly'] ) ) {
			$userData = new BooklyLib\UserBookingData( null );
			$userData->fillData( $wc_item['bookly'] );
			$userData->cart->setItemsData( $wc_item['bookly']['items'] );
			$cart_info = $userData->cart->getInfo();
			if ( 'excl' === get_option( 'woocommerce_tax_display_cart' ) && BooklyLib\Config::taxesActive() ) {
				$product_price = wc_price( wmc_get_price( $cart_info->getPayNow() - $cart_info->getPayTax() ) );
			} else {
				$product_price = wc_price( wmc_get_price( $cart_info->getPayNow() ) );
			}
		}

		return $product_price;
	}
}