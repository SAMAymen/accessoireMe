<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Admin_Analytics
 */
class WOOMULTI_CURRENCY_F_Admin_Analytics {
	protected $settings;
	protected $args;
	protected $default_currency;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		$this->default_currency = $this->settings->get_default_currency();
		/*Orders*/
//		add_filter( 'woocommerce_analytics_orders_select_query', array(
//			$this,
//			'woocommerce_analytics_orders_select_query'
//		) );
//		/*Orders stats*/
//		add_filter( 'woocommerce_analytics_orders_stats_query_args', array(
//			$this,
//			'woocommerce_analytics_query_args'
//		) );
//		add_filter( 'woocommerce_analytics_orders_stats_select_query', array(
//			$this,
//			'convert_orders_stats'
//		) );
//		/*Revenue*/
//		add_filter( 'woocommerce_analytics_revenue_query_args', array(
//			$this,
//			'woocommerce_analytics_query_args'
//		) );
//		add_filter( 'woocommerce_analytics_revenue_select_query', array(
//			$this,
//			'convert_orders_stats'
//		) );
//		/*Products*/
//		add_filter( 'woocommerce_analytics_products_query_args', array(
//			$this,
//			'woocommerce_analytics_query_args'
//		) );
//		add_filter( 'woocommerce_analytics_products_select_query', array(
//			$this,
//			'convert_orders_stats'
//		) );
//		/*Products stats*/
//		add_filter( 'woocommerce_analytics_products_stats_query_args', array(
//			$this,
//			'woocommerce_analytics_query_args'
//		) );
//		add_filter( 'woocommerce_analytics_products_stats_select_query', array(
//			$this,
//			'convert_orders_stats'
//		) );
//		/*Categories*/
//		add_filter( 'woocommerce_analytics_categories_query_args', array(
//			$this,
//			'woocommerce_analytics_query_args'
//		) );
//		add_filter( 'woocommerce_analytics_categories_select_query', array(
//			$this,
//			'convert_orders_stats'
//		) );

		add_filter( 'woocommerce_analytics_update_order_stats_data', [ $this, 'import_order_to_order_stats_table' ], 10, 2 );
		add_action( 'woocommerce_analytics_update_product', [ $this, 'import_order_product_to_order_product_lookup_table' ], 10, 2 );
		add_action( 'woocommerce_analytics_update_coupon', [ $this, 'import_order_coupon_to_order_coupon_lookup_table' ], 10, 2 );
		add_action( 'woocommerce_analytics_update_tax', [ $this, 'import_order_tax_to_order_tax_lookup_table' ], 10, 2 );
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return float|int
	 */
	public function get_rate( $order ) {
		$order = $order->get_parent_id() ? wc_get_order( $order->get_parent_id() ) : $order;

		$rate             = 1;
		$order_id         = $order->get_id();
		$order_currency   = $order->get_currency();
		$default_currency = $this->default_currency;
		$order_info       = $order->get_meta('wmc_order_info', true );

		if ( isset( $order_info[ $order_currency ], $order_info[ $default_currency ], $order_info[ $default_currency ]['is_main'] )
		     && $order_info[ $default_currency ]['is_main'] == 1
		     && $order_info[ $order_currency ]['rate'] > 0 ) {

			$rate = floatval( $order_info[ $order_currency ]['rate'] );
		}

		return $rate;
	}

	public function import_order_to_order_stats_table( $order_data, $order ) {
		$rate = $this->get_rate( $order );

		if ( $rate && $rate != 1 ) {
			$order_data['total_sales']    = $order_data['total_sales'] / $rate;
			$order_data['tax_total']      = $order_data['tax_total'] / $rate;
			$order_data['shipping_total'] = $order_data['shipping_total'] / $rate;
			$order_data['net_total']      = $order_data['net_total'] / $rate;
		}

		return $order_data;
	}

	public function import_order_product_to_order_product_lookup_table( $order_item_id, $order_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wc_order_product_lookup';
		$order      = wc_get_order( $order_id );
		$rate       = $this->get_rate( $order );

		if ( $rate && $rate != 1 ) {
			$items      = $order->get_items();
			$order_item = $items[ $order_item_id ];
			$decimals   = wc_get_price_decimals();
			$round_tax  = 'no' === get_option( 'woocommerce_tax_round_at_subtotal' );

			// Tax amount.
			$tax_amount  = 0;
			$order_taxes = $order->get_taxes();
			$tax_data    = $order_item->get_taxes();
			foreach ( $order_taxes as $tax_item ) {
				$tax_item_id = $tax_item->get_rate_id();
				$tax_amount  += isset( $tax_data['total'][ $tax_item_id ] ) ? (float) $tax_data['total'][ $tax_item_id ] : 0;
			}

			$net_revenue = round( $order_item->get_total( 'edit' ) / $rate, $decimals );

			if ( $round_tax ) {
				$tax_amount = round( $tax_amount, $decimals );
			}

			$tax_amount          = $tax_amount / $rate;
			$coupon_amount       = $order->get_item_coupon_amount( $order_item ) / $rate;
			$shipping_amount     = $order->get_item_shipping_amount( $order_item ) / $rate;
			$shipping_tax_amount = $order->get_item_shipping_tax_amount( $order_item ) / $rate;

			$wpdb->update( $table_name,// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				[
					'product_net_revenue'   => $net_revenue,
					'coupon_amount'         => $coupon_amount,
					'tax_amount'            => $tax_amount,
					'shipping_amount'       => $shipping_amount,
					'shipping_tax_amount'   => $shipping_tax_amount,
					'product_gross_revenue' => $net_revenue + $tax_amount + $shipping_amount + $shipping_tax_amount,
				],
				[ 'order_item_id' => $order_item_id ],
				[
					'%f', // product_net_revenue.
					'%f', // coupon_amount.
					'%f', // tax_amount.
					'%f', // shipping_amount.
					'%f', // shipping_tax_amount.
					'%f', // product_gross_revenue.
				]
			);
		}
	}

	public function import_order_coupon_to_order_coupon_lookup_table( $coupon_id, $order_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wc_order_coupon_lookup';
		$order      = wc_get_order( $order_id );
		$rate       = $this->get_rate( $order );

		if ( $rate && $rate != 1 ) {
			$coupon_items    = $order->get_items( 'coupon' );
			$discount_amount = 0;

			foreach ( $coupon_items as $coupon_item ) {
				$c_data = $coupon_item->get_meta( 'coupon_data', true );
				$c_id   = $c_data['id'] ?? wc_get_coupon_id_by_code( $coupon_item->get_code() );
				if ( $c_id == $coupon_id ) {
					$discount_amount = $coupon_item->get_discount();
				}
			}

			$discount_amount = $discount_amount / $rate;

			$wpdb->update( $table_name, [ 'discount_amount' => $discount_amount, ], [ 'order_id' => $order_id ], [ '%f', ] );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		}
	}

	public function import_order_tax_to_order_tax_lookup_table( $tax_rate_id, $order_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wc_order_tax_lookup';
		$order      = wc_get_order( $order_id );
		$rate       = $this->get_rate( $order );

		if ( $rate && $rate != 1 ) {
			$tax_items = $order->get_items( 'tax' );

			$shipping_tax = 0;
			$order_tax    = 0;
			$total_tax    = 0;

			foreach ( $tax_items as $tax_item ) {
				if ( $tax_item->get_rate_id() == $tax_rate_id ) {
					$shipping_tax = $tax_item->get_shipping_tax_total() / $rate;
					$order_tax    = $tax_item->get_tax_total() / $rate;
					$total_tax    = ( (float) $tax_item->get_tax_total() + (float) $tax_item->get_shipping_tax_total() ) / $rate;
				}
			}

			$wpdb->update( $table_name,// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				[
					'shipping_tax' => $shipping_tax,
					'order_tax'    => $order_tax,
					'total_tax'    => $total_tax
				],
				[ 'order_id' => $order_id ], [ '%f', '%f', '%f', ] );
		}
	}

	/**
	 * Only to save args for later use
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function woocommerce_analytics_query_args( $args ) {
		$this->args = $args;

		return $args;
	}

	/**
	 * Convert order stats based on rate stored in order meta wmc_order_info
	 *
	 * @param $order_data
	 * @param $default_currency
	 *
	 * @return array
	 */
	private static function get_converted_order( $order_data, $default_currency ) {
		$order_id   = $order_data['order_id'];
		$parent_id  = $order_data['parent_id'] ?? '';
		$order = wc_get_order( $order_id );
		if ( $parent_id ) {
			$order_info = get_post_meta( $parent_id, 'wmc_order_info', true );
		} else {
			$order_info = $order->get_meta('wmc_order_info', true );
		}
		$currency   = $order->get_meta( $order_id, '_order_currency', true );
		$rate       = 1;
		if ( isset( $order_info[ $currency ], $order_info[ $default_currency ], $order_info[ $default_currency ]['is_main'] ) && $order_info[ $default_currency ]['is_main'] == 1 && $order_info[ $currency ]['rate'] > 0 ) {
			$rate = floatval( $order_info[ $currency ]['rate'] );
		}
		$converted_order = array(
			'date_created' => strtotime( $order_data['date_created'] ),
			'gross_sales'  => 0,
			'refunds'      => 0,
			'net_revenue'  => self::format_price( floatval( $order_data['net_total'] ) / $rate ),
			'coupons'      => 0,
			'taxes'        => 0,
			'shipping'     => 0,
			'total_sales'  => self::format_price( floatval( $order_data['total_sales'] ) / $rate ),
		);

		if ( $order ) {
			if ( $order_data['net_total'] < 0 ) {
				$converted_order['refunds'] = abs( $converted_order['net_revenue'] );
			}
			$converted_order['coupons']  = self::format_price( floatval( $order->get_total_discount() ) / $rate );
			$converted_order['taxes']    = self::format_price( floatval( $order->get_total_tax() ) / $rate );
			$converted_order['shipping'] = self::format_price( floatval( $order->get_shipping_total() ) / $rate );
		}
		$converted_order['gross_sales'] = $converted_order['total_sales'] + $converted_order['coupons'] - $converted_order['taxes'] - $converted_order['shipping'] + $converted_order['refunds'];

		return $converted_order;
	}

	/**
	 * Convert orders stats
	 *
	 * @param $results
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function convert_orders_stats( $results ) {
		if ( $this->args !== null ) {
			$args           = $this->args;
			$args['fields'] = '';
//			$args['extended_info'] = 1;
			$data_store     = \WC_Data_Store::load( 'report-orders' );
			$results_orders = $data_store->get_data( $args );
			$converted      = array(
				'gross_sales' => 0,
				'refunds'     => 0,
				'net_revenue' => 0,
				'coupons'     => 0,
				'taxes'       => 0,
				'shipping'    => 0,
				'total_sales' => 0,
			);
			if ( count( $results_orders->data ) ) {
				$orders_data = $results_orders->data;
				if ( $results_orders->pages > 1 ) {
					for ( $i = 2; $i <= $results_orders->pages; $i ++ ) {
						$args['page']   = $i;
						$results_orders = $data_store->get_data( $args );
						if ( count( $results_orders->data ) ) {
							$orders_data = array_merge( $orders_data, $results_orders->data );
						}
					}
				}

				$default_currency = $this->settings->get_default_currency();
				$converted_orders = array();
				foreach ( $orders_data as $order_data ) {
					if ( ! empty( $order_data['order_id'] ) ) {
						$converted_order = self::get_converted_order( $order_data, $default_currency );
						foreach ( $converted_order as $converted_order_k => $converted_order_v ) {
							if ( isset( $converted[ $converted_order_k ] ) ) {
								$converted[ $converted_order_k ] += $converted_order_v;
							}
						}
						$converted_orders[] = $converted_order;
					}
				}
				foreach ( $converted as $key => $value ) {
					if ( isset( $results->totals->{$key} ) ) {
						$results->totals->{$key} = $value;
					}
				}
				if ( isset( $results->totals->avg_order_value, $results->totals->orders_count ) && $results->totals->orders_count > 0 ) {
					$results->totals->avg_order_value = $results->totals->net_revenue / $results->totals->orders_count;
				}
				if ( isset( $results->intervals ) && count( $results->intervals ) && count( $converted_orders ) ) {
					foreach ( $results->intervals as $key => $interval ) {
						if ( isset( $interval['subtotals'] ) && isset( $interval['subtotals']->gross_sales, $interval['subtotals']->total_sales, $interval['subtotals']->net_revenue ) && ( $interval['subtotals']->gross_sales > 0 || $interval['subtotals']->net_revenue > 0 || $interval['subtotals']->gross_sales > 0 ) ) {
							$subtotals = array(
								'gross_sales'     => 0,
								'total_sales'     => 0,
								'coupons'         => 0,
								'refunds'         => 0,
								'taxes'           => 0,
								'shipping'        => 0,
								'net_revenue'     => 0,
								'avg_order_value' => 0,
							);
							$found     = false;
							foreach ( $converted_orders as $converted_order ) {
								if ( $converted_order['date_created'] >= strtotime( $interval['date_start'] ) && $converted_order['date_created'] <= strtotime( $interval['date_end'] ) ) {
									$found                    = true;
									$subtotals['net_revenue'] += $converted_order['net_revenue'];
									$subtotals['total_sales'] += $converted_order['total_sales'];
									$subtotals['refunds']     += $converted_order['refunds'];
									$subtotals['coupons']     += $converted_order['coupons'];
									$subtotals['taxes']       += $converted_order['taxes'];
									$subtotals['shipping']    += $converted_order['shipping'];
									$subtotals['gross_sales'] += $converted_order['gross_sales'];
								} elseif ( $found ) {
									break;
								}
							}
							if ( $found ) {
								if ( isset( $interval['subtotals']->avg_order_value, $interval['subtotals']->orders_count ) && $interval['subtotals']->orders_count > 0 ) {
									$subtotals['avg_order_value'] = $subtotals['net_revenue'] / $interval['subtotals']->orders_count;
								}
								foreach ( $subtotals as $subtotals_k => $subtotals_v ) {
									if ( isset( $interval['subtotals']->{$subtotals_k} ) ) {
										$results->intervals[ $key ]['subtotals']->{$subtotals_k} = $subtotals_v;
									}
								}
							}
						}
					}
				}
			}
			$this->args = null;
		}

		return $results;
	}

	/**
	 * Convert net_total and total_sales of every order
	 *
	 * @param $results
	 *
	 * @return mixed
	 */
	public function woocommerce_analytics_orders_select_query( $results ) {
		$default_currency = $this->settings->get_default_currency();
		foreach ( $results->data as $key => $order_data ) {
			if ( ! empty( $order_data['order_id'] ) ) {
				$converted_order                      = self::get_converted_order( $order_data, $default_currency );
				$results->data[ $key ]['net_total']   = $converted_order['net_revenue'];
				$results->data[ $key ]['total_sales'] = $converted_order['total_sales'];
			}
		}

		return $results;
	}

	/**
	 * Format price after converting
	 *
	 * @param $price
	 *
	 * @return float
	 */
	private static function format_price( $price ) {
		return $price > 0 ? floatval( str_replace( ',', '', number_format( $price, wc_get_price_decimals(), '.', ',' ) ) ) : $price;
	}
}