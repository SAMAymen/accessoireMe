<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_TM_Extra_Product_Options
 * WooCommerce TM Extra Product Options By ThemeComplete
 */
class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_TM_Extra_Product_Options {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			if ( $this->settings->check_fixed_price() ) {
				add_filter( 'wc_epo_enabled_currencies', array( $this, 'wc_epo_enabled_currencies' ) );
			}
			add_filter( 'wc_epo_option_price_correction', array( $this, 'revert_price' ) );
			add_filter( 'wc_epo_get_current_currency_price', array(
				$this,
				'wc_epo_get_current_currency_price'
			), 10, 6 );
			add_filter( 'wc_epo_convert_to_currency', array( $this, 'wc_epo_convert_to_currency' ), 10, 3 );
			add_filter( 'wc_epo_remove_current_currency_price', array(
				$this,
				'wc_epo_remove_current_currency_price'
			), 10, 9 );
			add_filter( 'wc_epo_get_currency_price', array( $this, 'tm_wc_epo_get_currency_price' ), 10, 7 );

			add_filter( 'wc_epo_price_on_cart', array( $this, 'wc_epo_price_on_cart' ), 10, 2 );

//			add_filter( 'wc_epo_add_cart_item_calculated_price1', array( $this, 'revert_price' ) );
//			add_filter( 'wc_epo_add_cart_item_calculated_price2', array( $this, 'change_price' ) );

			/*These hooks may be removed*/
			add_filter( 'woocommerce_tm_epo_price_on_cart', array( $this, 'change_price' ) );
			add_filter( 'wc_epo_calculate_price', array( $this, 'wc_epo_calculate_price' ), 10, 13 );
			add_filter( 'wc_epo_cart_options_prices', array( $this, 'change_price' ), 10, 2 );
		}
	}

	/**
	 * @param $price
	 * @param $cart_item
	 *
	 * @return float|int|mixed|void
	 */
	public function wc_epo_price_on_cart( $price, $cart_item ) {
		if ( ! empty( $cart_item['tm_epo_product_original_price'] ) && floatval( $cart_item['tm_epo_product_original_price'] ) === floatval( $price ) ) {
			$price = $this->change_price( $price );
		}

		return $price;
	}

	/**
	 * @param string $price
	 * @param bool $currency
	 * @param string $price_type
	 * @param bool $current_currency
	 * @param null $price_per_currencies
	 * @param null $key
	 * @param null $attribute
	 *
	 * @return array|float|int|mixed|string|void
	 */
	public function tm_wc_epo_get_currency_price( $price = '', $currency = false, $price_type = '', $current_currency = false, $price_per_currencies = null, $key = null, $attribute = null ) {
		if ( ! $currency ) {
			return $this->wc_epo_get_current_currency_price( $price, $price_type, $currency );
		}
		$tc_get_default_currency = apply_filters( 'tc_get_default_currency', $this->settings->get_default_currency() );

		if ( $current_currency && $current_currency === $currency && $current_currency === $tc_get_default_currency ) {
			return $price;
		}

		$price = $this->get_price_in_currency( $price, $currency, null, $price_per_currencies, $price_type, $key, $attribute );

		return $price;

	}

	/**
	 * @param string $price
	 * @param string $type
	 * @param null $to_currency
	 * @param null $from_currency
	 * @param null $currencies
	 * @param null $key
	 * @param null $attribute
	 * @param array $cart_item
	 *
	 * @return array|mixed|string|void
	 */
	public function wc_epo_remove_current_currency_price( $price = '', $type = '', $to_currency = null, $from_currency = null, $currencies = null, $key = null, $attribute = null, $cart_item = [] ) {
		$price = $this->get_price_in_currency( $price, $to_currency, $from_currency, $currencies, $type, $key, $attribute );

		return $price;
	}

	/**
	 * @param string $price
	 * @param bool $from_currency
	 * @param bool $to_currency
	 *
	 * @return array|mixed|string|void
	 */
	public function wc_epo_convert_to_currency( $price = '', $from_currency = false, $to_currency = false ) {
		if ( ! $from_currency || ! $to_currency || $from_currency === $to_currency ) {
			return $price;
		}

		// todo: if needed extend this as the whole method is only used for fixed conversions.
		$price = $this->get_price_in_currency( $price, $to_currency, $from_currency );

		return $price;

	}

	/**
	 * @param $price
	 * @param null $to_currency
	 * @param null $from_currency
	 * @param null $currencies
	 * @param null $type
	 * @param null $key
	 * @param null $attribute
	 *
	 * @return array|mixed|void
	 */
	protected function get_price_in_currency( $price, $to_currency = null, $from_currency = null, $currencies = null, $type = null, $key = null, $attribute = null ) {
		if ( empty( $from_currency ) ) {
			$from_currency = $this->settings->get_default_currency();
		}
		if ( empty( $to_currency ) ) {
			$to_currency = $this->settings->get_current_currency();
		}
		if ( $from_currency === $to_currency ) {
			return $price;
		}
		if ( null !== $type && in_array( (string) $type, [
				'',
				'word',
				'wordnon',
				'char',
				'step',
				'intervalstep',
				'charnofirst',
				'charnospaces',
				'charnon',
				'charnonnospaces',
				'fee',
				'stepfee',
				'subscriptionfee'
			], true ) && is_array( $currencies ) && isset( $currencies[ $to_currency ] ) ) {
			$v = $currencies[ $to_currency ];
			if ( null !== $key && isset( $v[ $key ] ) ) {
				$v = $v[ $key ];
			}
			if ( is_array( $v ) ) {
				$v = array_values( $v );
				$v = $v[0];
				if ( is_array( $v ) ) {
					$v = array_values( $v );
					$v = $v[0];
				}
			}

			if ( '' !== $v ) {
				return $v;
			}
		}

		$default_currency = $this->settings->get_default_currency();
		if ( $default_currency && $from_currency != $default_currency ) {
			$price = wmc_revert_price( $price, $from_currency );
		}

		return apply_filters( 'wmc_change_3rd_plugin_price', $price, $from_currency, $to_currency );
	}

	/**
	 * @return mixed|void
	 */
	public function wc_epo_enabled_currencies() {
		return $this->settings->get_currencies();
	}

	/**
	 * @param $_price
	 * @param $post_data
	 * @param $element
	 * @param $key
	 * @param $attribute
	 * @param $per_product_pricing
	 * @param $cpf_product_price
	 * @param $variation_id
	 * @param $price_default_value
	 * @param $currency
	 * @param $current_currency
	 * @param $price_per_currencies
	 * @param $_price_type
	 *
	 * @return bool|float|int|string
	 */
	public function wc_epo_calculate_price( $_price, $post_data, $element, $key, $attribute, $per_product_pricing, $cpf_product_price, $variation_id, $price_default_value, $currency, $current_currency, $price_per_currencies, $_price_type ) {
		if ( in_array( $_price_type, array( 'percent', 'percentcurrenttotal' ) ) ) {
			$wmc_current_currency = $this->settings->get_current_currency();
			$default_currency     = $this->settings->get_default_currency();
			if ( $current_currency !== false ) {
				if ( $wmc_current_currency === $default_currency ) {
					$_price = wmc_revert_price( $_price, $current_currency );
				}
			}
		}

		return $_price;
	}

	/**
	 * @param $price_raw
	 *
	 * @return float|int|mixed|void
	 */
	public function change_price( $price_raw ) {
		return $price_raw ? wmc_get_price( $price_raw ) : $price_raw;
	}

	/**
	 * @param string $price
	 * @param string $type
	 * @param null $currencies
	 * @param bool $currency
	 * @param bool $product_price
	 * @param bool $tc_added_in_currency
	 *
	 * @return array|float|int|mixed|string|void
	 */
	public function wc_epo_get_current_currency_price( $price = '', $type = '', $currencies = null, $currency = false, $product_price = false, $tc_added_in_currency = false ) {
		if ( is_array( $type ) ) {
			$type = '';
		}
		// Check if the price should be processed only once.
		if ( in_array( (string) $type, [
			'',
			'math',
			'fixedcurrenttotal',
			'word',
			'wordnon',
			'char',
			'step',
			'intervalstep',
			'charnofirst',
			'charnospaces',
			'charnon',
			'charnonnospaces',
			'fee',
			'stepfee',
			'subscriptionfee'
		], true ) ) {

			$price = $this->get_price_in_currency( $price, $currency, null, $currencies, $type );

		} elseif ( false !== $product_price && false !== $tc_added_in_currency && 'percent' === (string) $type ) {

			$product_price = $this->get_price_in_currency( $product_price, $tc_added_in_currency, null, $currencies, '' );
			$price         = $product_price * ( $price / 100 );

		}

		return $price;
	}

	/**
	 * @param $price
	 *
	 * @return bool|float|int|string
	 */
	public function revert_price( $price ) {
		return wmc_revert_price( $price );
	}
}
