<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Catna
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Catna {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		/*Name your price*/
		add_action( 'vicatna_nyp_admin_product_after_name_your_price', array(
			$this,
			'vicatna_nyp_admin_product_after_name_your_price'
		) );
		$product_type = apply_filters( 'vicatna_applicanle_product_type', [ 'simple' ] );
		foreach ( $product_type as $type ) {
			add_action( 'woocommerce_process_product_meta_' . $type, array(
				$this,
				'vicatna_woocommerce_process_product_meta_simple'
			) );
		}
		add_filter( 'vicatna_woocommerce_get_price', array( $this, 'vicatna_woocommerce_get_price' ), 10, 2 );
		add_filter( 'vicatna_nyp_check_get_price_min', array( $this, 'vicatna_nyp_check_get_price_min' ), 10, 3 );
		add_filter( 'vicatna_nyp_check_get_price_max', array( $this, 'vicatna_nyp_check_get_price_max' ), 10, 3 );
		/*Smart offer*/
		add_filter( 'vicatna_so_check_get_price_min', array( $this, 'vicatna_so_check_get_price_min' ) );
	}

	/**
	 * @param $price_min
	 *
	 * @return float|int|mixed|void
	 */
	public function vicatna_so_check_get_price_min( $price_min ) {
		return wmc_get_price( $price_min );
	}

	/**
	 * @param $post_id
	 */
	public function vicatna_woocommerce_process_product_meta_simple( $post_id ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_catna' ) ) {
			return;
		}

		$type = isset( $_POST['vicatna_type'] ) ? wc_clean( $_POST['vicatna_type'] ) : '';
		if ( $type === '' ) {
			return;
		}
		$currencies       = $this->settings->get_list_currencies();
		$default_currency = $this->settings->get_default_currency();
		$wc_product       = wc_get_product( $post_id );
		foreach ( $currencies as $currency => $currency_data ) {
			if ( $default_currency !== $currency ) {
				$key_min = '_wmc_vicatna_nyp_min_' . $currency;
				$key_max = '_wmc_vicatna_nyp_max_' . $currency;
				$wc_product->update_meta_data( $key_min, isset( $_POST[ $key_min ] ) ? wc_clean( $_POST[ $key_min ] ) : '' );
				$wc_product->update_meta_data( $key_max, isset( $_POST[ $key_max ] ) ? wc_clean( $_POST[ $key_max ] ) : '' );
				$wc_product->save_meta_data();
			}
		}
	}

	/**
	 * Fixed price fields for min price/max price of name your price
	 */
	public function vicatna_nyp_admin_product_after_name_your_price() {
		global $thepostid;
		if ( $this->settings->check_fixed_price() ) {
			$currencies       = $this->settings->get_list_currencies();
			$default_currency = $this->settings->get_default_currency();
			foreach ( $currencies as $currency => $currency_data ) {
				if ( $default_currency !== $currency ) {
					woocommerce_wp_text_input(
						array(
							'id'                => '_wmc_vicatna_nyp_min_' . $currency,
//							'name'        => '_wmc_vicatna_nyp_min_' . $currency,
							'custom_attributes' => array( 'data-name' => '_wmc_vicatna_nyp_min_' . $currency ),
							'value'             => get_post_meta( $thepostid, '_wmc_vicatna_nyp_min_' . $currency, true ),
							'desc_tip'          => true,
							'description'       => esc_html__( 'Minimum acceptable price', 'catna-woocommerce-name-your-price-and-offers' ),
							'label'             => esc_html__( 'Minimum price', 'catna-woocommerce-name-your-price-and-offers' ) . ' (' . $currency . ')',
							'data_type'         => 'price',
						)
					);
					woocommerce_wp_text_input(
						array(
							'id'                => '_wmc_vicatna_nyp_max_' . $currency,
//							'name'        => '_wmc_vicatna_nyp_max_' . $currency,
							'custom_attributes' => array( 'data-name' => '_wmc_vicatna_nyp_max_' . $currency ),
							'value'             => get_post_meta( $thepostid, '_wmc_vicatna_nyp_max_' . $currency, true ),
							'placeholder'       => esc_html__( 'Leave blank to not limit that', 'catna-woocommerce-name-your-price-and-offers' ),
							'label'             => esc_html__( 'Maximum price', 'catna-woocommerce-name-your-price-and-offers' ) . ' (' . $currency . ')',
							'data_type'         => 'price',
						)
					);
				}
			}
		}
	}

	/**
	 * Handle min price
	 *
	 * @param $price
	 * @param $rule
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function vicatna_nyp_check_get_price_min( $price, $rule, $product ) {
		$fixed_price = $this->get_fixed_price( $product->get_id() );
		if ( $fixed_price !== '' ) {
			$price = wc_format_decimal( floatval( $fixed_price ), wc_get_price_decimals() );
		} elseif ( $price ) {
			$price = wc_format_decimal( wmc_get_price( $price ), wc_get_price_decimals() );
		}

		return $price ? wc_format_decimal( $price, wc_get_price_decimals() ) : $price;
	}

	/**
	 * Handle max price
	 *
	 * @param $price
	 * @param $rule
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function vicatna_nyp_check_get_price_max( $price, $rule, $product ) {
		$fixed_price = $this->get_fixed_price( $product->get_id(), false );
		if ( $fixed_price !== '' ) {
			$price = wc_format_decimal( floatval( $fixed_price ), wc_get_price_decimals() );
		} elseif ( $price ) {
			$price = wc_format_decimal( wmc_get_price( $price ), wc_get_price_decimals() );
		}

		return $price;
	}

	/**
	 * Get fixed min/max price
	 *
	 * @param $product_id
	 * @param bool $is_min
	 *
	 * @return mixed|string
	 */
	public function get_fixed_price( $product_id, $is_min = true ) {
		$price = '';
		if ( $this->settings->check_fixed_price() ) {
			$current_currency = $this->settings->get_current_currency();
			$default_currency = $this->settings->get_default_currency();
			$wc_product = wc_get_product( $product_id );
			if ( $current_currency !== $default_currency ) {
				$catna_settings = $wc_product->get_meta('vicatna_settings', true );
				if ( $catna_settings['type'] === '0' ) {
					$price = $wc_product->get_meta('_wmc_vicatna_nyp_' . ( $is_min ? 'min' : 'max' ) . '_' . $current_currency, true );
				}
			}
		}

		return $price;
	}

	/**
	 * Convert price in cart
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return bool|float|int|mixed|string|void
	 */
	public function vicatna_woocommerce_get_price( $price, $product ) {
		$data = array();
		if ( ! empty( $product->vicatna_nyp ) ) {
			$data = $product->vicatna_nyp;
		} elseif ( ! empty( $product->vicatna_so ) ) {
			$data = $product->vicatna_so;
		}
		if ( count( $data ) ) {
			$current_currency = $this->settings->get_current_currency();
			$currency         = isset( $data['currency'] ) ? sanitize_text_field( $data['currency'] ) : '';
			$currencies       = $this->settings->get_list_currencies();
			$default_currency = $this->settings->get_default_currency();
			if ( $currency && $currency !== $current_currency && ! empty( $currencies[ $currency ] ) ) {
				$price = wmc_revert_price( $price, $currency );
				if ( $current_currency !== $default_currency ) {
					$price = wmc_get_price( $price );
				}
			}
		}

		return $price;
	}
}