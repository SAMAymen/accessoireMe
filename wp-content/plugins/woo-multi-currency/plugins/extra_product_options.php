<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Extra_Product_Options
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Extra_Product_Options {
	protected $settings;

	public function __construct() {
		if ( ! is_plugin_active( 'woocommerce-extra-product-options-pro/woocommerce-extra-product-options-pro.php' ) ) {
			return;
		}
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'thwepo_extra_option_display_price', array(
				$this,
				'thwepo_extra_option_display_price'
			), 10, 4 );
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woocommerce_add_cart_item_data' ), 20, 4 );
			add_filter( 'thwepo_product_price', array( $this, 'thwepo_product_price' ), 10, 3 );
			add_filter( 'thwepo_product_field_price', array( $this, 'thwepo_product_field_price' ), 10, 5 );
		}
	}

	/**
	 * @param $price
	 * @param $price_type
	 * @param $name
	 * @param $price_info
	 * @param $index
	 *
	 * @return float|int|mixed|void
	 */
	public function thwepo_product_field_price( $price, $price_type, $name, $price_info, $index ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_plugin' ) ) {
			return $price;
		}
		if ( $price && wp_doing_ajax() ) {
			$action = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';
			if ( $action === 'thwepo_calculate_extra_cost' ) {
				if ( $price_type === 'normal' ) {
					$price = wmc_get_price( $price );
				}
			}
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 * @param $is_default
	 *
	 * @return mixed
	 */
	public function thwepo_product_price( $price, $product, $is_default ) {
		if ( $product ) {
			$price = $is_default ? $product->get_price_html() : $product->get_price();
		}

		return $price;
	}

	public function woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
		$current_currency = $this->settings->get_current_currency();
		$default_currency = $this->settings->get_default_currency();
		if ( is_array( $cart_item_data['thwepo_options'] ) && count( $cart_item_data['thwepo_options'] ) ) {
			foreach ( $cart_item_data['thwepo_options'] as $key => $value ) {
				if ( $value['price_type'] == 'custom' ) {
					if ( $current_currency != $default_currency ) {
						$cart_item_data['thwepo_options'][ $key ]['value'] *= 1 / wmc_get_price( 1, $current_currency );
					}
				}
			}
		}

		return $cart_item_data;
	}


	public function thwepo_extra_option_display_price( $return, $price, $unformatted_price, $field, $args = array() ) {
		return wc_price( wmc_get_price( $price ) );
	}
}