<?php

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Symbol
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Frontend_Symbol {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			/*Add order information*/
			add_filter( 'woocommerce_thankyou_order_id', array( $this, 'add_order_currency_info' ), 9 );
			add_action( 'woocommerce_new_order', array( $this, 'add_order_currency_info' ) );
			add_filter( 'woocommerce_currency', array( $this, 'woocommerce_currency' ), 99 );
			/**
			 * Format price
			 */
			add_filter( 'wc_get_price_decimals', array( $this, 'set_decimals' ) );
			/**
			 * Symbol position
			 */
			add_filter( 'woocommerce_price_format', array( $this, 'price_format' ) );
			add_filter( 'woocommerce_currency_symbol', array( $this, 'custom_currency_symbol' ), 11, 2 );

			/*Custom Symbol*/
			add_action( 'init', array( $this, 'init' ), 1 );
		}
	}

	/**
	 *
	 */
	public function init() {
		if ( version_compare( WC_VERSION, '5.0', '<' ) ) {
			add_filter( 'wc_price', array( $this, 'custom_price' ), 10, 3 );
		} else {
			add_filter( 'wc_price', array( $this, 'custom_price_5' ), 10, 5 );
		}
	}

	/**
	 * @param $return
	 * @param $price
	 * @param $args
	 *
	 * @return mixed
	 */
	public function custom_price( $return, $price, $args ) {
		extract(
			wp_parse_args(
				$args, array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => wc_get_price_decimal_separator(),
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimals'           => wc_get_price_decimals(),
					'price_format'       => get_woocommerce_price_format(),
				)
			)
		);

		$unformatted_price = $price;
		$negative          = $price < 0;

		$currency_symbol = get_woocommerce_currency_symbol( $currency );
		$pos             = strpos( $currency_symbol, '#PRICE#' );
		if ( $pos === false ) {
			$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="woocommerce-Price-currencySymbol">' . $currency_symbol . '</span>', $price );
		} else {
			$formatted_price = str_replace( '#PRICE#', $price, $currency_symbol );

		}

		$return = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

		if ( $ex_tax_label && wc_tax_enabled() ) {
			$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return Price HTML markup.
		 * @param string $price Formatted price.
		 * @param array $args Pass on the args.
		 * @param float $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 */
		return apply_filters( 'wmc_wc_price', $return, $price, $args, $unformatted_price );
	}

	/**
	 * @param $return
	 * @param $price
	 * @param $args
	 * @param $unformatted_price
	 * @param $original_prices
	 *
	 * @return mixed|void
	 */
	public function custom_price_5( $return, $price, $args, $unformatted_price, $original_prices ) {
		extract(
			wp_parse_args(
				$args, array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => wc_get_price_decimal_separator(),
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimals'           => wc_get_price_decimals(),
					'price_format'       => get_woocommerce_price_format(),
				)
			)
		);
		$negative        = $original_prices < 0;
		$currency_symbol = get_woocommerce_currency_symbol( $currency );
		$pos             = strpos( $currency_symbol, '#PRICE#' );
		if ( $pos === false ) {
			$formatted_price = ( $negative ? '- ' : '' ) . sprintf( $price_format, '<span class="woocommerce-Price-currencySymbol">' . $currency_symbol . '</span>', $price );
		} else {
			$formatted_price = str_replace( '#PRICE#', $price, $currency_symbol );

		}

		$return = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

		if ( $ex_tax_label && wc_tax_enabled() ) {
			$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return Price HTML markup.
		 * @param string $price Formatted price.
		 * @param array $args Pass on the args.
		 * @param float $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 * @param float $original_prices Since WC 5.0.
		 */
		return apply_filters( 'wmc_wc_price', $return, $price, $args, $unformatted_price, $original_prices );
	}

	/**
	 * Custom current symbol
	 *
	 * @param $currency_symbol
	 * @param $currency
	 *
	 * @return mixed
	 */
	public function custom_currency_symbol( $currency_symbol, $currency ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $currency_symbol;
		}
		$selected_currencies = $this->settings->get_list_currencies();
		if ( is_account_page() ) {
			return $currency_symbol;
		} elseif ( isset( $selected_currencies[ $currency ] ) && isset( $selected_currencies[ $currency ]['custom'] ) && $selected_currencies[ $currency ]['custom'] != '' ) {

			$currency_symbol = $selected_currencies[ $currency ]['custom'];

		}

		return $currency_symbol;
	}

	/**
	 * @param $data
	 *
	 * @return mixed|string|void
	 */
	public function woocommerce_currency( $data ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return $data;
		}
		if ( $this->settings->get_current_currency() ) {
			$data = $this->settings->get_current_currency();
		}

		return $data;
	}

	/**
	 * Insert information about order after checkout
	 *
	 * @param $order_id
	 *
	 * @return mixed
	 */
	public function add_order_currency_info( $order_id ) {
		$wc_order = wc_get_product( $order_id );
		if ( $wc_order && ! $wc_order->get_meta('wmc_order_info', true ) ) {
			$wmc_order_info = $this->settings->get_list_currencies();

			$wmc_order_info[ $this->settings->get_default_currency() ]['is_main'] = 1;
			$wc_order->update_meta_data('wmc_order_info', $wmc_order_info );
			$wc_order->save_meta_data();
		}

		return $order_id;
	}

	/**
	 * @param $format
	 *
	 * @return string
	 */
	public function price_format( $format ) {
		$selected_currencies = $this->settings->get_list_currencies();
		$currencies          = $this->settings->get_currencies();
		if ( is_order_received_page() ) {
			global $wp;
			$order_id = $wp->query_vars['order-received'];
			$order    = wc_get_order( $order_id );
			if ( is_object( $order ) ) {
				$currency    = $order->get_currency();
				$current_pos = $selected_currencies[ $currency ]['pos'];
			} else {
				return $format;
			}

		} elseif ( in_array( $this->settings->get_current_currency(), $currencies ) ) {
			$current_pos = $selected_currencies[ $this->settings->get_current_currency() ]['pos'];
		} else {
			return $format;
		}

		switch ( $current_pos ) {
			case 'left' :
				$format = '%1$s%2$s';
				break;
			case 'right' :
				$format = '%2$s%1$s';
				break;
			case 'left_space' :
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;%1$s';
				break;
		}

		return $format;
	}

	/**
	 * @param $decimal
	 *
	 * @return int
	 */
	public function set_decimals( $decimal ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_symbol' ) ) {
			return (int) $decimal;
		}

		global $pagenow;
		if ( $pagenow === 'admin-ajax.php' && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'generate_wpo_wcpdf' && isset( $_REQUEST['order_ids'] ) && $_REQUEST['order_ids'] ) {
			$order_id       = intval( $_REQUEST['order_ids'] );
//			$wc_order       = wc_get_product( $order_id );
//			if ( $wc_order && is_object( $wc_order ) ) {
//				$order_currency = $wc_order->get_meta( '_order_currency', true );
//				$order_info     = $wc_order->get_meta( 'wmc_order_info', true );
//			} else {
				$order_currency = get_post_meta( $order_id, '_order_currency', true );
				$order_info     = get_post_meta( $order_id, 'wmc_order_info', true );
//			}
			if ( isset( $order_info[ $order_currency ]['decimals'] ) ) {
				return $order_info[ $order_currency ]['decimals'];
			}
		}
		$selected_currencies = $this->settings->get_list_currencies();
		$current_currency    = $this->settings->get_current_currency();
		$decimal             = isset( $selected_currencies[ $current_currency ]['decimals'] ) ? $selected_currencies[ $current_currency ]['decimals'] : 0;
		$decimal             = apply_filters( 'wmc_set_decimals', $decimal, $current_currency );

		return (int) $decimal;
	}
}