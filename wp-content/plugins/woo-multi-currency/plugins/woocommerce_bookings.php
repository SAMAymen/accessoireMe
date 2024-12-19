<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Bookings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Bookings {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'woocommerce_bookings_calculated_booking_cost_success_output', array(
				$this,
				'woocommerce_bookings_calculated_booking_cost_success_output'
			), 9, 3 );
			add_filter( 'booking_form_params', array(
				$this,
				'booking_form_params'
			) );
			add_filter( 'booking_form_fields', array(
				$this,
				'booking_form_fields'
			) );
		}
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function booking_form_params( $params ) {
		add_filter( 'woocommerce_product_get_resource_block_costs', array(
			$this,
			'woocommerce_product_get_resource_block_costs'
		) );
		add_filter( 'woocommerce_product_get_resource_base_costs', array(
			$this,
			'woocommerce_product_get_resource_base_costs'
		) );

		return $params;
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function booking_form_fields( $fields ) {
		remove_filter( 'woocommerce_product_get_resource_block_costs', array(
			$this,
			'woocommerce_product_get_resource_block_costs'
		) );
		remove_filter( 'woocommerce_product_get_resource_base_costs', array(
			$this,
			'woocommerce_product_get_resource_base_costs'
		) );

		return $fields;
	}

	/**
	 * @param $resource_data
	 *
	 * @return array
	 */
	public function woocommerce_product_get_resource_block_costs( $resource_data ) {
		return $this->convert_resource_data( $resource_data );
	}

	/**
	 * @param $resource_data
	 *
	 * @return array
	 */
	public function woocommerce_product_get_resource_base_costs( $resource_data ) {
		return $this->convert_resource_data( $resource_data );
	}

	/**
	 * @param $resource_data
	 *
	 * @return array
	 */
	private function convert_resource_data( $resource_data ) {
		if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
			if ( is_array( $resource_data ) && count( $resource_data ) ) {
				$resource_data = array_map( 'wmc_get_price', $resource_data );
			}
		}

		return $resource_data;
	}

	/**
	 * @param $output
	 * @param $display_price
	 * @param $product
	 *
	 * @return mixed
	 */
	public function woocommerce_bookings_calculated_booking_cost_success_output( $output, $display_price, $product ) {
		$display_price = wmc_get_price( $display_price );
		if ( version_compare( WC_VERSION, '2.4.0', '>=' ) ) {
			$price_suffix = $product->get_price_suffix( $display_price, 1 );
		} else {
			$price_suffix = $product->get_price_suffix();
		}
		$output        = apply_filters( 'woocommerce_bookings_booking_cost_string', esc_html__( 'Booking cost', 'woocommerce-bookings' ), $product ) . ': <strong>' . wc_price( $display_price, array(
				'currency' => $this->settings->get_current_currency(),
			) ) . $price_suffix . '</strong>';

		return $output;
	}
}