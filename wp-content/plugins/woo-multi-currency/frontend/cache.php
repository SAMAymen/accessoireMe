<?php

/**
 * Class WOOMULTI_CURRENCY_Frontend_Update
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Frontend_Cache {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'init', array( $this, 'clear_browser_cache' ) );
			add_action( 'wp_ajax_wmc_get_products_price', array( $this, 'get_products_price' ) );
			add_action( 'wp_ajax_nopriv_wmc_get_products_price', array( $this, 'get_products_price' ) );
			if ( $this->settings->get_param( 'cache_compatible' ) ) {
				add_filter( 'woocommerce_get_price_html', array( $this, 'compatible_cache_plugin' ), PHP_INT_MAX, 2 );
			}
		}
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function compatible_cache_plugin( $price, $product ) {
		if ( wp_doing_ajax() ) {
			return $price;
		}

		$wrap = 'span';
		if ( strpos( $price, '<div' ) !== false || strpos( $price, '<p' ) !== false ) {
			$wrap = 'div';
		}

		$loading = $this->settings->get_param( 'loading_price_mask' ) ? 'wmc-cache-loading' : '';

		return "<{$wrap} class='wmc-cache-pid {$loading}' data-wmc_product_id='{$product->get_id()}'>" . $price . "</{$wrap}>";
	}

	/**
	 * Clear cache browser
	 */
	public function clear_browser_cache() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_cache' ) ) {
			return;
		}
		if ( isset( $_GET['wmc-currency'] ) ) {
			header( "Cache-Control: no-cache, must-revalidate" );
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Content-Type: application/xml; charset=utf-8" );
		}
	}

	public function get_products_price() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_cache' ) ) {
			return;
		}
		do_action( 'wmc_get_products_price_ajax_handle_before' );
		$pids             = ! empty( $_POST['pids'] ) ? wc_clean( $_POST['pids'] ) : [];
		$shortcodes       = ! empty( $_POST['shortcodes'] ) ? wc_clean( $_POST['shortcodes'] ) : array();
		$result           = array(
			'shortcodes' => array()
		);
		$current_currency = $this->settings->get_current_currency();
		if ( ! empty( $pids ) ) {
			foreach ( $pids as $pid ) {
				$product = wc_get_product( $pid );
				if ( $product ) {
					$result['prices'][ $pid ] = $product->get_price_html();
				}
			}
		}

		$result['current_currency'] = $current_currency;
		$result['current_country']  = strtolower( $this->settings->get_country_data( $current_currency )['code'] );
		$shortcodes_list            = $this->settings->get_list_shortcodes();
		if ( count( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcode ) {
				if ( isset( $shortcodes_list[ $shortcode['layout'] ] ) ) {
					$flag_size     = isset( $shortcode['flag_size'] ) ? $shortcode['flag_size'] : '';
					$dropdown_icon = isset( $shortcode['dropdown_icon'] ) ? $shortcode['dropdown_icon'] : '';
					$custom_format = isset( $shortcode['custom_format'] ) ? $shortcode['custom_format'] : '';
					if ( $flag_size ) {
						$flag_size = "flag_size='{$flag_size}'";
					}
					$result['shortcodes'][] = do_shortcode( "[woo_multi_currency_{$shortcode['layout']} {$flag_size} dropdown_icon='{$dropdown_icon}' custom_format='{$custom_format}']" );
				} else {
					$result['shortcodes'][] = do_shortcode( "[woo_multi_currency]" );
				}
			}
		}

		if ( ! empty( $_POST['exchange'] ) ) {
			$exchange_sc  = [];
			$exchange_arr = wc_clean( $_POST['exchange'] );
			foreach ( $exchange_arr as $ex ) {
				$exchange_sc[] = array_merge( $ex, [ 'shortcode' => do_shortcode( "[woo_multi_currency_exchange product_id='{$ex['product_id']}' keep_format='{$ex['keep_format']}' price='{$ex['price']}' original_price='{$ex['original_price']}' currency='{$ex['currency']}']" ) ] );
			}
			$result['exchange'] = $exchange_sc;
		}
		do_action( 'wmc_get_products_price_ajax_handle_after' );
		wp_send_json_success( apply_filters( 'wmc_get_products_price_ajax_handle_response', $result ) );
	}
}