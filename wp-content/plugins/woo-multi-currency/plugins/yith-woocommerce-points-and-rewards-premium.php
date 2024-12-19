<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Yith_WooCommerce_Points_And_Rewards_Premium {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'ywpar_rewards_conversion_rate', array( $this, 'ywpar_rewards_conversion_rate' ) );
			add_filter( 'ywpar_conversion_points_rate', array( $this, 'ywpar_conversion_points_rate' ) );
		}
	}

	public function ywpar_conversion_points_rate( $conversion ) {
		$currency         = $this->settings->get_default_currency();
		$current_currency = $this->settings->get_current_currency();

		if ( $currency !== $current_currency ) {
			$list_currencies = $this->settings->get_list_currencies();
			if ( ! empty( $list_currencies[ $current_currency ]['rate'] ) ) {
				$conversion = array( 'points' => 1, 'money' => $list_currencies[ $current_currency ]['rate'] );
			}
		}

		return $conversion;
	}

	public function get_conversion_method() {
		return apply_filters( 'ywpar_conversion_method', ywpar_get_option( 'conversion_rate_method' ) );
	}

	public function ywpar_rewards_conversion_rate( $conversion_rate ) {
		$currency = $this->settings->get_default_currency();
		if ( $currency !== $this->settings->get_current_currency() ) {
			$customer    = ywpar_get_customer( null );
			$valid_rules = YITH_WC_Points_Rewards_Helper::get_valid_redeeming_rules( $customer );
			$conversion  = array();
			if ( $valid_rules ) {
				foreach ( $valid_rules as $rule ) {
					$rule = ywpar_get_redeeming_rule( $rule );
					if ( 'conversion_rate' === $rule->get_type() ) {
						$conversions = 'fixed' === $this->get_conversion_method() ? $rule->get_conversion_rate() : $rule->get_percentage_conversion_rate();
						if ( isset( $conversions[ $currency ] ) ) {
							$conversion = $conversions[ $currency ];
							break;
						}
					}
				}
			}
			if ( empty( $conversion ) ) {
				$conversion = $this->get_main_conversion_rate( $currency );
			}
			if ( ! empty( $conversion['money'] && ! empty( $conversion['points'] ) ) ) {
				$conversion['money'] = wmc_get_price( $conversion['money'] );
				$conversion_rate     = $conversion;
			}
		}

		return $conversion_rate;
	}

	public function get_main_conversion_rate( $currency ) {
		$currency = ywpar_get_currency( $currency );
		if ( 'fixed' === $this->get_conversion_method() ) {
			$conversions = ywpar_get_option( 'rewards_conversion_rate' );
			$conversion  = isset( $conversions[ $currency ] ) ? $conversions[ $currency ] : array(
				'money'  => 0,
				'points' => 0,
			);

			$conversion['money']  = ( empty( $conversion['money'] ) ) ? 1 : $conversion['money'];
			$conversion['points'] = ( empty( $conversion['points'] ) ) ? 1 : $conversion['points'];
		} else {
			$conversions = ywpar_get_option( 'rewards_percentual_conversion_rate' );
			$conversion  = isset( $conversions[ $currency ] ) ? $conversions[ $currency ] : array(
				'points'   => 0,
				'discount' => 0,
			);
			$conversion  = apply_filters( 'ywpar_rewards_percentual_conversion_rate', $conversion );

			$conversion['points']   = ( empty( $conversion['points'] ) ) ? 1 : $conversion['points'];
			$conversion['discount'] = ( empty( $conversion['discount'] ) ) ? 1 : $conversion['discount'];
		}

		return $conversion;
	}
}
