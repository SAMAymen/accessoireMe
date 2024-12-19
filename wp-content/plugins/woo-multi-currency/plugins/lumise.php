<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Lumise
 * Plugin: Lumise
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Lumise {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() && is_plugin_active( 'lumise/lumise.php' ) ) {
			add_filter( 'lumise_product_base_price', array( $this, 'lumise_product_base_price' ), 20, 2 );
			add_filter( 'woocommerce_widget_cart_item_quantity', array(
				$this,
				'woocommerce_widget_cart_item_quantity'
			), 1000, 3 );
//			add_action( 'woocommerce_before_calculate_totals', array( $this, 'woo_calculate_price' ), 9, 1 );
//			add_action( 'woocommerce_before_calculate_totals', array( $this, 'woo_calculate_price_remove' ), 11, 1 );
		}
	}

	public function woo_calculate_price( $cart_object ) {
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'revert_price' ), 10, 2 );
	}

	public function woo_calculate_price_remove( $cart_object ) {
		remove_filter( 'woocommerce_product_variation_get_price', array( $this, 'revert_price' ), 10 );
	}

	public function revert_price( $price ) {
		return wmc_revert_price( $price );
	}

	public function lumise_product_base_price( $price, $product_id ) {
		if ( $this->settings->get_default_currency() !== $this->settings->get_current_currency() ) {
			$price = wmc_revert_price( $price );
		}

		return $price;
	}

	public function woocommerce_widget_cart_item_quantity( $html, $cart_item, $cart_item_key ) {
		if ( isset( $cart_item['lumise_data'] ) ) {
			foreach ( $cart_item['lumise_data']['attributes'] as $id => $attr ) {
				if ( $attr->type == 'quantity' && isset( $cart_item['lumise_data']['options']->{$id} ) ) {
					$total = $cart_item['lumise_data']['price']['total'];
					$total = wmc_get_price( $total );
					$qty   = @json_decode( $cart_item['lumise_data']['options']->{$id}, true );
					if ( json_last_error() === 0 && is_array( $qty ) ) {
						$qty = array_sum( $qty );
					} else {
						$qty = (Int) $cart_item['lumise_data']['options']->{$id};
					}
					$html = '<span class="quantity">' . sprintf( '%s &times; %s', $qty, wc_price( $total / $qty ) ) . '</span>';
				}
			}

		}

		return $html;
	}
}