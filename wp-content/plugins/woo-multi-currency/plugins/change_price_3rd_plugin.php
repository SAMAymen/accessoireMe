<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Change_Price_3rd_Plugin {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
//			add_filter( 'woocommerce_product_addons_option_price_raw', array( $this, 'change_price' ) );
			add_filter( 'wmc_change_3rd_plugin_price', array( $this, 'change_price' ) );
			add_filter( 'wmc_change_raw_price', array( $this, 'change_price' ) );

			// Advanced shipping
			add_filter( 'wcml_shipping_price_amount', array( $this, 'change_price' ), 10 );
//		add_filter( 'wcml_shipping_price_amount', array( $this, 'number_format' ), 11 );

			/*Flexible shipping*/
			add_filter( 'flexible_shipping_value_in_currency', array( $this, 'flexible_shipping_value_in_currency' ) );

			// Discussion on RnB - WooCommerce Booking & Rental Plugin
			add_filter( 'redq_pickup_locations', array( $this, 'redq_change_price' ) );
			add_filter( 'redq_dropoff_locations', array( $this, 'redq_change_price' ) );
			add_filter( 'redq_payable_resources', array( $this, 'redq_change_price' ) );
			add_filter( 'redq_payable_security_deposite', array( $this, 'redq_change_price' ) );
			add_filter( 'redq_rnb_cat_categories', array( $this, 'redq_change_price' ) );
			add_filter( 'redq_payable_person', array( $this, 'redq_person_change_price' ) );
			add_filter( 'wmc_product_get_price_condition', array( $this, 'rnb_plugin_condition' ), 10, 3 );

			/*VillaTheme discount plugin*/
			add_filter( 'viredis_change_3rd_plugin_price', array( $this, 'change_price' ) );

			/*WooCommerce Boost Sales dynamic price*/
			add_filter( 'wbs_crossell_recalculated_price_in_cart', array( $this, 'revert_price' ) );

			/*Bopo – Woo Product Bundle Builder*/
			add_filter( 'bopobb_get_original_price', array( $this, 'bopobb_get_original_price' ) );
			add_filter( 'bopobb_convert_currency_price', array( $this, 'change_price' ) );

			/*Sumo subscriptions*/
			add_filter( 'sumosubscriptions_get_line_total', array( $this, 'revert_price' ), 10 );

			// WooCommerce PDF Vouchers - WordPress Plugin
			add_filter( 'woo_vou_get_product_price', array( $this, 'woo_vou_reverse_price' ), 10, 2 );

			/*WooFunnels Funnel Builder*/
//			add_filter( 'wfob_product_raw_data', array( $this, 'wfob_product_raw_data' ), 10, 3 );
		}
	}

	/**
	 * @param $raw_data
	 * @param $product WC_Product
	 * @param $cart_item_key
	 *
	 * @return mixed
	 */
	public function wfob_product_raw_data( $raw_data, $product, $cart_item_key ) {
		if ( '' == $cart_item_key ) {
			$raw_data['regular_price'] = $product->get_regular_price();
			$raw_data['price']         = $product->get_price();
		}

		return $raw_data;
	}

	/**
	 * @param $value
	 *
	 * @return float|int|mixed|void
	 */
	public function flexible_shipping_value_in_currency( $value ) {
		if ( $value && $value !== INF ) {
			$value = wmc_get_price( $value );
		}

		return $value;
	}


	public function bopobb_get_original_price( $price ) {
		if ( ! empty( $price['product_price'] ) ) {
			$price['product_price'] = wmc_revert_price( $price['product_price'] );
		}

		return $price;
	}

	public function rnb_plugin_condition( $condition, $price, $product ) {
		if ( is_a( $product, 'WC_Product_Redq_Rental' ) ) {
			$condition = false;
		}

		return $condition;
	}

	public function change_price( $price_raw ) {
		return $price_raw ? wmc_get_price( $price_raw ) : $price_raw;
	}

	/**
	 * @param $price
	 *
	 * @return string
	 */
	public function number_format( $price ) {
		$current_currency = $this->settings->get_current_currency();
		$currencies_list  = $this->settings->get_list_currencies();

		return number_format( $price, $currencies_list[ $current_currency ]['decimals'] );
	}

	/**
	 * @param $price
	 *
	 * @return bool|float|int|string
	 */
	public function revert_price( $price ) {
		return wmc_revert_price( $price );
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function redq_person_change_price( $data ) {
		$new_data = $data;
		if ( is_array( $data ) && count( $data ) ) {
			foreach ( $data as $key => $value ) {
				$new_data[ $key ] = $this->redq_change_price( $value );
			}
		}

		return $new_data;
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function redq_change_price( $data ) {
		$new_data = $data;

		if ( is_array( $data ) && count( $data ) ) {
			foreach ( $data as $el_key => $element ) {
				if ( is_array( $element ) && count( $element ) ) {
					foreach ( $element as $key => $value ) {
						if ( substr( $key, - 4 ) == 'cost' && is_numeric( $value ) ) {
							$new_data[ $el_key ][ $key ] = $this->change_price( $value );
						}
					}
				}
			}
		}

		return $new_data;
	}

	/**
	 * @param $subtotal
	 * @param $order_id
	 *
	 * @return float|int|string
	 */
	public function woo_vou_reverse_price( $subtotal, $order_id ) {
		$order          = wc_get_order( $order_id );
		$wmc_order_info = $order->get_meta('wmc_order_info', true );
		$order_currency = $order->get_currency();
		$rate           = ! empty( $wmc_order_info[ $order_currency ]['rate'] ) ? $wmc_order_info[ $order_currency ]['rate'] : '';
		$decimals       = ! empty( $wmc_order_info[ $order_currency ]['decimals'] ) ? $wmc_order_info[ $order_currency ]['decimals'] : '';

		$subtotal = $rate ? $subtotal / $rate : $subtotal;
		$subtotal = $decimals ? number_format( $subtotal, $decimals ) : $subtotal;

		return $subtotal;
	}
}
