<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Woo_Wallet
 * Plugin: TeraWallet https://wordpress.org/plugins/woo-wallet/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Woo_Wallet {
	protected $settings;
	protected $skip_convert_credit = false;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( is_plugin_active( 'woo-wallet/woo-wallet.php' ) ) {
			add_filter( 'woo_wallet_current_balance', array( $this, 'woo_wallet_current_balance' ), 10, 2 );
			add_filter( 'woo_wallet_amount', array( $this, 'woo_wallet_amount' ), 10, 2 );
			add_filter( 'woo_wallet_rechargeable_amount', array( $this, 'revert_amount' ) );
			add_filter( 'woo_wallet_cashback_notice_text', array( $this, 'woo_wallet_cashback_notice_text' ), 10, 2 );
			add_filter( 'woo_wallet_new_user_registration_credit_amount', array(
				$this,
				'convert_price'
			) );
			add_filter( 'woo_wallet_get_option__wallet_settings_general_max_topup_amount', array(
				$this,
				'convert_price'
			) );
			add_filter( 'woo_wallet_get_option__wallet_settings_general_min_topup_amount', array(
				$this,
				'convert_price'
			) );
			add_action( 'woocommerce_new_order', array(
				$this,
				'maybe_skip_woo_wallet_credit_purchase_amount'
			), 10, 2 );
			add_filter( 'woo_wallet_credit_purchase_amount', array(
				$this,
				'woo_wallet_credit_purchase_amount'
			), 10, 2 );
			add_filter( 'woo_wallet_form_cart_cashback_amount', array(
				$this,
				'revert_amount'
			) );
			add_action( 'woocommerce_admin_order_item_headers', array(
				$this,
				'woocommerce_admin_order_item_headers'
			) );
			/*Set current currency by order currency so that credit is recorded in correct currency*/
			add_action( 'woocommerce_api_wc_gateway_uddoktapay', array(
				$this,
				'woocommerce_api_wc_gateway_uddoktapay'
			), 1 );
		}
	}

	public function woocommerce_api_wc_gateway_uddoktapay() {
		$payload = file_get_contents( 'php://input' );
		if ( ! empty( $payload ) ) {
			$data = json_decode( $payload );
			if ( isset( $data->metadata->order_id ) ) {
				$order_id       = $data->metadata->order_id;
				$order_currency = get_post_meta( $order_id, '_order_currency', true );
				if ( $order_currency ) {
					$this->settings->set_current_currency( $order_currency );
				}
			}
		}
	}

	public function woocommerce_admin_order_item_headers() {
		villatheme_remove_object_filter( 'woocommerce_admin_order_totals_after_tax', 'Woo_Wallet_Admin', 'add_wallet_payment_amount', 10 );
		add_action( 'woocommerce_admin_order_totals_after_tax', array(
			$this,
			'add_wallet_payment_amount'
		), 10, 1 );
	}

	public function add_wallet_payment_amount( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $total_cashback_amount = get_total_order_cashback_amount( $order_id ) ) {
			$rate = '';
			if ( $order ) {
				$order_currency = $order->get_currency();
				$wmc_order_info = $order->get_meta('wmc_order_info', true );
				if ( $wmc_order_info ) {
					foreach ( $wmc_order_info as $key => $value ) {
						if ( isset( $value['is_main'] ) && $value['is_main'] ) {
							if ( $key !== $order_currency ) {
								if ( isset( $wmc_order_info[ $order_currency ]['rate'] ) && $wmc_order_info[ $order_currency ]['rate'] ) {
									$rate = $wmc_order_info[ $order_currency ]['rate'];
								}
							}
							break;
						}
					}
				}
			}
			?>
            <tr>
                <td class="label"><?php esc_html_e( 'Cashback', 'woo-wallet' ); ?>:</td>
                <td width="1%"></td>
                <td class="via-wallet">
					<?php
					if ( $rate ) {
						echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( wc_price( $total_cashback_amount * $rate, array( 'currency' => $order->get_currency() ) ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( wc_price( $total_cashback_amount, woo_wallet_wc_price_args( $order->get_customer_id() ) ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
                </td>
            </tr>
			<?php
		}
	}

	/**
	 * @param $order_id
	 * @param $order
	 */
	public function maybe_skip_woo_wallet_credit_purchase_amount( $order_id, $order ) {
		$this->skip_convert_credit = true;
	}

	/**
	 * Only convert if order status is manually switched to paid statuses
	 *
	 * @param $amount
	 * @param $order_id
	 *
	 * @return float|int
	 */
	public function woo_wallet_credit_purchase_amount( $amount, $order_id ) {
		if ( ! apply_filters( 'wmc_woo_wallet_skip_convert_credit', $this->skip_convert_credit, $order_id ) ) {
			$order_currency   = get_post_meta( $order_id, '_order_currency', true );
			$default_currency = $this->settings->get_default_currency();
			if ( $order_currency !== $default_currency ) {
				$currency_info = get_post_meta( $order_id, 'wmc_order_info', true );
				if ( isset( $currency_info[ $default_currency ]['is_main'] ) && $currency_info[ $default_currency ]['is_main'] ) {
					if ( isset( $currency_info[ $order_currency ]['rate'] ) && $currency_info[ $order_currency ]['rate'] ) {
						$amount = $amount / (float) $currency_info[ $order_currency ]['rate'];
						$amount = WOOMULTI_CURRENCY_F_Data::convert_price_to_float( $amount, array( 'decimals' => absint( $currency_info[ $order_currency ]['decimals'] ) ) );
					}
				}
			}
			$this->skip_convert_credit = true;
		}

		return $amount;
	}

	/**
	 * @param $amount
	 *
	 * @return float|int|mixed|void
	 */
	public function convert_price( $amount ) {
		if ( ! $amount ) {
			return $amount;
		}

		return wmc_get_price( $amount );
	}

	/**
	 * @param $wallet_balance
	 * @param $user_id
	 *
	 * @return float|int|mixed|void
	 */
	public function woo_wallet_current_balance( $wallet_balance, $user_id ) {
		if ( $user_id ) {
			$is_admin_setting_wallet = false;
			if ( is_admin() && ! is_ajax() ) {
				$page_id = get_current_screen()->id;
				if ( in_array( $page_id, array( 'toplevel_page_woo-wallet', 'admin_page_woo-wallet-transactions' ) ) ) {
					$is_admin_setting_wallet = true;
				}
			}
			$wallet_balance = 0;
			foreach ( $this->settings->get_list_currencies() as $currency => $currency_data ) {
				$credit_amount  = array_sum( wp_list_pluck( get_wallet_transactions( array(
					'user_id' => $user_id,
					'where'   => array(
						array(
							'key'   => 'type',
							'value' => 'credit'
						),
						array(
							'key'   => 'currency',
							'value' => $currency
						)
					)
				) ), 'amount' ) );
				$debit_amount   = array_sum( wp_list_pluck( get_wallet_transactions( array(
					'user_id' => $user_id,
					'where'   => array(
						array(
							'key'   => 'type',
							'value' => 'debit'
						),
						array(
							'key'   => 'currency',
							'value' => $currency
						)
					)
				) ), 'amount' ) );
				$balance        = $credit_amount - $debit_amount;
				$wallet_balance += $is_admin_setting_wallet ? $balance : ( $balance / (float) $currency_data['rate'] );
			}
			$wallet_balance = wmc_get_price( $wallet_balance );
		}

		return $wallet_balance;
	}

	public function revert_amount( $amount ) {
		if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
			$amount = wmc_revert_price( $amount );
		}

		return $amount;
	}

	public function woo_wallet_amount( $amount, $currency ) {
		$default_currency = $this->settings->get_default_currency();
		if ( is_admin() && ! wp_doing_ajax() ) {
			$is_admin_setting_wallet = false;
			$page_id = get_current_screen()->id;
			if ( in_array( $page_id, array( 'admin_page_woo-wallet-transactions' ) ) ) {
				$is_admin_setting_wallet = true;
			}
			$list_currencies = $this->settings->get_list_currencies();
			if ( ! empty( $list_currencies[ $currency ]['rate'] ) ) {
				if ( $currency !== $default_currency ) {
					$amount = $is_admin_setting_wallet ? $amount : $amount / (float) $list_currencies[ $currency ]['rate'];
				}
			}
		} else {
			$wmc_current_currency = $this->settings->get_current_currency();
			if ( $currency !== $default_currency ) {
				$amount = wmc_revert_price( $amount, $currency );
			}
			if ( $wmc_current_currency !== $default_currency ) {
				$amount = wmc_get_price( $amount );
			}
		}

		return $amount;
	}

	public function woo_wallet_cashback_notice_text( $text, $cashback_amount ) {
		$cashback_amount = wmc_get_price( $cashback_amount );
		if ( is_user_logged_in() ) {
			/* translators: %s: cashback value */
			$text = sprintf( esc_html__( 'Upon placing this order a cashback of %s will be credited to your wallet.', 'woo-wallet' ), wc_price( $cashback_amount, woo_wallet_wc_price_args() ) );
		} else {
			/* translators: %1$s: login url, %2$s: cashback value */
			$text = sprintf( esc_html__( 'Please <a href="%1$s">log in</a> to avail %2$s cashback from this order.', 'woo-wallet' ), esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ), wc_price( $cashback_amount, woo_wallet_wc_price_args() ) );
		}

		return $text;
	}
}