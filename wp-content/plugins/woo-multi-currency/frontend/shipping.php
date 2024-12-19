<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Shipping
 */
class WOOMULTI_CURRENCY_F_Frontend_Shipping {
	protected $settings;
	protected $cache = array();

	function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			global $wpdb;
			$raw_methods_sql = "SELECT method_id, method_order, instance_id, is_enabled FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id = 'betrs_shipping' AND is_enabled = 1 order by instance_id ASC;";
			$raw_methods     = $wpdb->get_results( $raw_methods_sql );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
			if ( count( $raw_methods ) ) {
				foreach ( $raw_methods as $method ) {
					add_filter( 'option_betrs_shipping_options-' . intval( $method->instance_id ), array(
						$this,
						'table_rate_shipping'
					) );
				}
			}
			add_filter( 'woocommerce_package_rates', array( $this, 'woocommerce_package_rates' ), 10, 2 );
			add_filter( 'woocommerce_shipping_free_shipping_instance_option', array(
				$this,
				'woocommerce_shipping_free_shipping_instance_option'
			), 10, 3 );
		}
	}

	/**
	 * Handle min_amount of Free shipping method
	 *
	 * If enable fixed price and the value is not empty, use it. Otherwise, convert the min_amount of the default currency
	 *
	 * @param $value
	 * @param $key
	 * @param $instance
	 *
	 * @return float|int|mixed
	 */
	public function woocommerce_shipping_free_shipping_instance_option( $value, $key, $instance ) {
		if ( ! is_admin() || wp_doing_ajax() ) {
			if ( $key === 'min_amount' ) {
				$default_currency = $this->settings->get_default_currency();
				$currency         = $this->settings->get_current_currency();
				if ( $currency !== $default_currency ) {
					$value = wmc_get_price( $instance->instance_settings["min_amount"] );
				}
			}
		}

		return $value;
	}

	/**
	 * Table rate shipping
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public function table_rate_shipping( $options ) {
		$new_options = $options;
		if ( ! empty( $new_options ) ) {
			// step through each table rate row
			foreach ( $new_options['settings'] as $o_key => $option ) {
				foreach ( $option['rows'] as $r_key => $row ) {
					$costs = $row['costs'];
					if ( is_array( $costs ) ) {
						foreach ( $costs as $k => $cost ) {
							switch ( $cost['cost_type'] ) {
								case '%':
									break;
								default:
									$options['settings'][ $o_key ]['rows'][ $r_key ]['costs'][ $k ]['cost_value'] = wmc_get_price( $cost['cost_value'] );
							}
						}
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Convert shipping cost
	 *
	 * @param $methods
	 *
	 * @return mixed
	 */
	public function woocommerce_package_rates( $methods, $package ) {
		$currency = $this->settings->get_current_currency();
		if ( $currency === $this->settings->get_default_currency() ) {
			return $methods;
		}
		if ( count( array_filter( $methods ) ) ) {
			foreach ( $methods as $k => $method ) {
				if ( in_array( $method->method_id, apply_filters( 'wmc_excluded_shipping_methods_from_converting',
					array(
						'aramex',
						'free_shipping',
						'wf_shipping_ups',
						'betrs_shipping',
						'printful_shipping',
						'easyship',
						'printful_shipping_PRINTFUL_SLOW',
						'printful_shipping_STANDARD',
						'printful_shipping_PRINTFUL_MEDIUM',
						'myfatoorah_shipping',
						'checkout_envia_shipping',
						'envia_shipping',
						'fan_courier',
					) ) ) ) {
					continue;
				}
				if ( $method->method_id === 'flat_rate' ) {
					$shipping = new WC_Shipping_Flat_Rate( $method->instance_id );
					// Calculate the costs.
					$cost         = $shipping->get_option( 'cost' );
					$has_costs    = false; // True when a cost is set. False if all costs are blank strings.
					$rate['cost'] = 0;
					if ( '' !== $cost ) {
						$has_costs    = true;
						$rate['cost'] = $this->evaluate_cost(
							$cost, array(
							'qty'  => $shipping->get_package_item_qty( $package ),
							'cost' => wmc_revert_price( $package['contents_cost'] ),
						) );//, $shipping
					}

					// Add shipping class costs.
					$shipping_classes = WC()->shipping()->get_shipping_classes();
					if ( ! empty( $shipping_classes ) ) {
						$found_shipping_classes = $shipping->find_shipping_classes( $package );
						$highest_class_cost     = 0;

						foreach ( $found_shipping_classes as $shipping_class => $products ) {
							// Also handles BW compatibility when slugs were used instead of ids.
							$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
							$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $shipping->get_option( 'class_cost_' . $shipping_class_term->term_id, $shipping->get_option( 'class_cost_' . $shipping_class, '' ) ) : $shipping->get_option( 'no_class_cost', '' );
							if ( '' === $class_cost_string ) {
								continue;
							}
							$has_costs  = true;
							$class_cost = $this->evaluate_cost(
								$class_cost_string, array(
								'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
								'cost' => wmc_revert_price( array_sum( wp_list_pluck( $products, 'line_total' ) ) ),
							) );

							if ( 'class' === $shipping->type ) {
								$rate['cost'] += $class_cost;
							} else {
								$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
							}
						}

						if ( 'order' === $shipping->type && $highest_class_cost ) {
							$rate['cost'] += $highest_class_cost;
						}
					}

					if ( $has_costs ) {
						$cost = wmc_get_price( $rate['cost'] );
						$method->set_cost( $cost );
						if ( count( $method->get_taxes() ) ) {
							$new_tax = array();
							foreach ( $method->get_taxes() as $tax_k => $tax ) {
								$new_tax[ $tax_k ] = wmc_get_price( $tax, false, true );
							}
							$method->set_taxes( $new_tax );
						}
					}
				} else {
					if ( isset( $this->cache[ $k ] ) && $this->cache[ $k ] && $k ) {
						$method->set_cost( $this->cache[ $k ] );
					} else {
						$cost = wmc_get_price( $method->cost );
						$method->set_cost( $cost );
						if ( count( $method->get_taxes() ) ) {
							$new_tax = array();
							foreach ( $method->get_taxes() as $tax_k => $tax ) {
								$new_tax[ $tax_k ] = wmc_get_price( $tax, false, true );
							}
							$method->set_taxes( $new_tax );
						}
					}
				}
			}
		}

		return $methods;
	}

	protected function evaluate_cost( $sum, $args = array() ) {
		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum );
		$locale         = localeconv();
		$decimals       = array(
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
			','
		);
		$this->fee_cost = $args['cost'];
		// Expand shortcodes.
		add_shortcode( 'fee', array( $this, 'fee' ) );
		$sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			)
		);
		remove_shortcode( 'fee', array( $this, 'fee' ) );
		// Remove whitespace from string.
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string.
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters.
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math.
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
	}

	/**
	 * Work out fee (shortcode).
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts(
			array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			), $atts, 'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}
}