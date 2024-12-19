<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Checkout
 */
class WOOMULTI_CURRENCY_F_Frontend_Checkout {
	public $settings;
	public $rate;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'woocommerce_checkout_update_order_review', array(
				$this,
				'woocommerce_checkout_update_order_review'
			), 99 );

			//Set order currency correctly
			add_filter( 'woocommerce_paypal_args', array( $this, 'woocommerce_paypal_args' ), 10, 2 );
			add_filter( 'woocommerce_twoco_args', array( $this, 'woocommerce_twoco_args' ) );
		}
	}

	/**
	 * Change currency to default and reload page if the payment method is the new PayPal gateway
	 *
	 * @param $data
	 */
	public function woocommerce_checkout_update_order_review( $data ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_checkout' ) ) {
			return;
		}
		$payment_method   = isset( $_POST['payment_method'] ) ? wc_clean( wp_unslash( $_POST['payment_method'] ) ) : '';
		$current_currency = $this->settings->get_current_currency();
		if ( $payment_method === 'ppcp-gateway' ) {
			$default_currency = $this->settings->get_default_currency();
			if ( ! $this->settings->get_enable_multi_payment() && ( $current_currency !== $default_currency || $this->settings->getcookie( 'wmc_current_currency_old' ) !== $default_currency ) ) {
				$this->settings->set_current_currency( $default_currency, true );
				$this->reload_after_update_order_review( true );
			}
		} elseif ( ! $this->settings->get_enable_multi_payment() ) {
			$default_currency = $this->settings->get_default_currency();
			if ( $current_currency !== $default_currency ) {
				$this->settings->set_current_currency( $default_currency, false );
			}
		}
	}

	/**
	 * @param bool $reload
	 * @param bool $update_checkout
	 */
	public function reload_after_update_order_review( $reload = false, $update_checkout = false ) {
		WC()->cart->calculate_shipping();
		WC()->cart->calculate_totals();
		// Get order review fragment
		ob_start();
		woocommerce_order_review();
		$woocommerce_order_review = ob_get_clean();

		// Get checkout payment fragment
		ob_start();
		woocommerce_checkout_payment();
		$woocommerce_checkout_payment = ob_get_clean();
		$args                         = array(
			'result'              => 'success',
			'messages'            => '',
			'reload'              => $reload,
			'wmc_update_checkout' => $update_checkout,
			'fragments'           => apply_filters(
				'woocommerce_update_order_review_fragments', array(
					'.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
					'.woocommerce-checkout-payment'            => $woocommerce_checkout_payment,
				)
			),
		);
		if ( is_plugin_active( 'checkout-for-woocommerce/checkout-for-woocommerce.php' ) ) {
			$_cfw__settings = get_option( '_cfw__settings' );
			if ( isset( $_cfw__settings['enable'] ) && $_cfw__settings['enable'] === 'yes' ) {
				$args['redirect'] = wc_get_checkout_url();
			}
		}

		unset( WC()->session->refresh_totals, WC()->session->reload_checkout );
		wp_send_json( $args );
	}

	/**
	 * PayPal args
	 *
	 * @param $payment_args
	 * @param $order WC_Order
	 *
	 * @return mixed
	 */
	public function woocommerce_paypal_args( $payment_args, $order ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_checkout' ) ) {
			return $payment_args;
		}
		if ( ! empty( $_GET['pay_for_order'] ) ) {
			$payment_args['currency_code'] = $order->get_currency();
		}

		return $payment_args;
	}

	/**
	 * WooCommerce 2Checkout Payment Gateway
	 *
	 * @param $payment_args
	 *
	 * @return mixed
	 */
	public function woocommerce_twoco_args( $payment_args ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_checkout' ) ) {
			return $payment_args;
		}
		if ( ! empty( $_GET['pay_for_order'] ) ) {
			$order_id = isset( $payment_args['merchant_order_id'] ) ? $payment_args['merchant_order_id'] : '';
			if ( $order_id ) {
				$order = wc_get_order( $order_id );
				if ( $order ) {
					$payment_args['currency_code'] = $order->get_currency();
				}
			}
		}

		return $payment_args;
	}
}
