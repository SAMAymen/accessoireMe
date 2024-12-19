<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 21/06/2019
 * Time: 9:11 CH
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Yith_Add_On {
	public function __construct() {
		if ( is_plugin_active( 'yith-woocommerce-product-add-ons/init.php' ) || is_plugin_active( 'yith-woocommerce-advanced-product-options-premium/init.php' ) ) {
			add_filter( 'wapo_print_option_price', array( $this, 'compatible_yith_add_on' ) );

//			add_filter( 'yith_wapo_option_price', array( $this, 'compatible_yith_add_on_price' ) );
//			add_filter( 'yith_wapo_option_price_sale', array( $this, 'compatible_yith_add_on_price_sale' ) );
			add_filter( 'yith_wapo_get_addon_price', array( $this, 'yith_modify_addon_price' ), 10, 5 );
			add_filter( 'yith_wapo_get_addon_sale_price', array( $this, 'yith_modify_addon_price_sale' ), 10, 5 );
			add_filter( 'yith_wapo_convert_price', array( $this, 'yith_modify_addon_price' ), 10, 5 );
			add_filter( 'yith_wapo_total_item_price', array( $this, 'yith_modify_addon_revert_price' ) );

			add_action( 'woocommerce_before_calculate_totals', array( $this, 'woocommerce_before_calculate_totals' ) );
		}
	}

	public function compatible_yith_add_on( $price ) {
		return wmc_get_price( $price );
	}

	public function compatible_yith_add_on_price( $price ) {

		return wmc_get_price( $price );
	}

	public function compatible_yith_add_on_price_sale( $price ) {

		return '' == $price ? '' : wmc_get_price( $price );
	}

	public function yith_modify_addon_price( $price, $allow_modification = false, $price_method = 'free', $price_type = 'fixed', $index = 0 ) {
		if ( 'free' !== $price_method || $allow_modification ) {
			if ( 'percentage' !== $price_type || $allow_modification ) {
				$price = wmc_get_price( $price );
			}
		}

		return $price;
	}

	public function yith_modify_addon_price_sale( $price, $allow_modification = false, $price_method = 'free', $price_type = 'fixed', $index = 0 ) {
		if ( $price == '' ) {
			return $price;
		}
		if ( 'free' !== $price_method || $allow_modification ) {
			if ( 'percentage' !== $price_type || $allow_modification ) {
				$price = wmc_get_price( $price );
			}
		}

		return $price;
	}

	public function yith_modify_addon_revert_price( $price ) {
		if ( $price == '' ) {
			return $price;
		}

		return wmc_revert_price( $price );
	}

	public function woocommerce_before_calculate_totals( $data ) {
		$cart_contents = WC()->cart->get_cart_contents();
		foreach ( $cart_contents as $key => $content ) {
			if ( isset( $content['yith_wapo_options'] ) && is_array( $content['yith_wapo_options'] ) && count( $content['yith_wapo_options'] ) ) {
				foreach ( $content['yith_wapo_options'] as $sub_key => $option ) {
					if ( isset( $option['price_original'] ) && $option['price_original'] ) {
						$cart_contents[ $key ]['yith_wapo_options'][ $sub_key ]['price'] = wmc_get_price( $option['price_original'] );
					}
				}
			}
		}

		WC()->cart->set_cart_contents( $cart_contents );
	}
}