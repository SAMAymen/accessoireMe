<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_Admin_Reports
 */
class WOOMULTI_CURRENCY_F_Admin_Reports {
	protected $settings;
	protected $currencies;
	protected $currency;
	protected $results;
	protected $chart_interval;
	protected $where_meta;
	protected $total_sales;
	protected $order_items;
	protected $current_range;
	protected $default_currency;
	protected $is_dashboard;

	function __construct() {
		$this->settings     = WOOMULTI_CURRENCY_F_Data::get_ins();
		$this->is_dashboard = false;
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'woocommerce_reports_get_order_report_data_args', array( $this, 'report_by_currency' ) );
		add_filter( 'woocommerce_reports_get_order_report_data', array(
			$this,
			'woocommerce_reports_get_order_report_data_for_dashboard'
		), 10, 2 );
		add_filter( 'woocommerce_currency', array( $this, 'woocommerce_currency' ), 99 );
		add_filter( 'wc_get_price_decimals', array( $this, 'wc_get_price_decimals' ), 99 );
		$this->default_currency = $this->settings->get_default_currency();
		$this->currencies       = $this->settings->get_list_currencies();
	}

	/**Get current range to recalculate report data
	 * @return string
	 */
	public function get_current_range() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return '7day';
		}
		if ( $this->current_range === null ) {
			$this->current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( wp_unslash( $_GET['range'] ) ) : '7day';
			if ( ! in_array( $this->current_range, array(
				'custom',
				'year',
				'last_month',
				'month',
				'7day'
			), true ) ) {
				$this->current_range = '7day';
			}
		}

		return $this->current_range;
	}

	public function admin_enqueue_scripts() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return;
		}
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if ( 'woocommerce_page_wc-reports' == $screen_id ) {
			$tab          = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'orders';
			$report       = isset( $_GET['report'] ) ? sanitize_text_field( wp_unslash( $_GET['report'] ) ) : '';
			$currency     = isset( $_GET['wmc-currency'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) ) : '';
			$view_default = isset( $_GET['wmc-view-default-currency'] ) ? sanitize_text_field( wp_unslash( $_GET['wmc-view-default-currency'] ) ) : '';
			if ( ( $tab === 'orders' && $report !== 'coupon_usage' ) || ( $tab === 'customers' && $report === 'customers' ) ) {
				wp_enqueue_style( 'woocommerce-multi-currency-admin-reports', WOOMULTI_CURRENCY_F_CSS . 'reports.css', '', WOOMULTI_CURRENCY_F_VERSION );
				wp_enqueue_script( 'woocommerce-multi-currency-admin-reports', WOOMULTI_CURRENCY_F_JS . 'reports.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION, false );
				wp_localize_script( 'woocommerce-multi-currency-admin-reports', 'woocommerce_multi_currency_admin_reports', array(
					'currency' => $currency ? $currency : 'all-currencies'
				) );
				add_action( 'admin_footer', array( $this, 'admin_footer' ) );
			}
			if ( ! $currency ) {
				if ( $tab === 'orders' ) {
					if ( ! $report || $report === 'sales_by_date' ) {
						add_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'add_return_order_id'
						) );
						add_filter( 'woocommerce_reports_get_order_report_data', array(
							$this,
							'woocommerce_reports_get_order_report_data_1'
						), 10, 2 );
					} elseif ( $report === 'sales_by_product' && ! empty( $_REQUEST['product_ids'] ) ) {
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-product.php' );
						$current_range = $this->get_current_range();
						$results       = array();
						if ( count( $this->currencies ) ) {
							foreach ( $this->currencies as $currency_code => $currency_data ) {
								$this->currency = $currency_code;
								add_filter( 'woocommerce_reports_get_order_report_data_args', array(
									$this,
									'report_by_currency_for_all'
								) );
								$report = new WC_Report_Sales_By_Product();
								$report->check_current_range_nonce( $current_range );
								$report->calculate_current_range( $current_range );
								$total_sales = $report->get_order_report_data(
									array(
										'data'         => array(
											'_line_total' => array(
												'type'            => 'order_item_meta',
												'order_item_type' => 'line_item',
												'function'        => 'SUM',
												'name'            => 'order_item_amount',
											),
										),
										'where_meta'   => array(
											'relation' => 'OR',
											array(
												'type'       => 'order_item_meta',
												'meta_key'   => array( '_product_id', '_variation_id' ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
												'meta_value' => $report->product_ids, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
												'operator'   => 'IN',
											),
										),
										'query_type'   => 'get_var',
										'filter_range' => true,
										'order_status' => array( 'completed', 'processing', 'on-hold', 'refunded' ),
									)
								);

								$results[] = self::convert_to_default_currency( $total_sales, $currency_data['rate'] );
								remove_filter( 'woocommerce_reports_get_order_report_data_args', array(
									$this,
									'report_by_currency_for_all'
								) );
							}
						}
						add_filter( 'woocommerce_reports_get_order_report_data', array(
							$this,
							'woocommerce_reports_get_order_report_data'
						), 10, 2 );
						$this->total_sales = array_sum( $results );
					} elseif ( $report === 'sales_by_category' && ! empty( $_GET['show_categories'] ) ) {
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-category.php' );
						$current_range = $this->get_current_range();
						$results       = array();
						if ( count( $this->currencies ) ) {
							foreach ( $this->currencies as $currency_code => $currency_data ) {
								$this->currency = $currency_code;
								add_filter( 'woocommerce_reports_get_order_report_data_args', array(
									$this,
									'report_by_currency_for_all'
								) );
								$report = new WC_Report_Sales_By_Category();
								$report->check_current_range_nonce( $current_range );
								$report->calculate_current_range( $current_range );
								$order_items = $report->get_order_report_data(
									array(
										'data'         => array(
											'_product_id' => array(
												'type'            => 'order_item_meta',
												'order_item_type' => 'line_item',
												'function'        => '',
												'name'            => 'product_id',
											),
											'_line_total' => array(
												'type'            => 'order_item_meta',
												'order_item_type' => 'line_item',
												'function'        => 'SUM',
												'name'            => 'order_item_amount',
											),
											'post_date'   => array(
												'type'     => 'post_data',
												'function' => '',
												'name'     => 'post_date',
											),
										),
										'group_by'     => 'ID, product_id, post_date',
										'query_type'   => 'get_results',
										'filter_range' => true,
									)
								);
								if ( is_array( $order_items ) && count( $order_items ) ) {
									foreach ( $order_items as $order_item ) {
										$order_item->order_item_amount = self::convert_to_default_currency( $order_item->order_item_amount, $currency_data['rate'] );
									}
								}
								$results = array_merge( $results, $order_items );
								remove_filter( 'woocommerce_reports_get_order_report_data_args', array(
									$this,
									'report_by_currency_for_all'
								) );
							}
						}
						add_filter( 'woocommerce_reports_get_order_report_data', array(
							$this,
							'woocommerce_reports_get_order_report_data'
						), 10, 2 );
						$this->order_items = $results;
					}
				}
			} elseif ( $view_default === 'yes' && $this->default_currency !== $currency ) {
				if ( ! $report || $report === 'sales_by_date' ) {
					$this->currency = $currency;
					if ( isset( $this->currencies[ $this->currency ] ) ) {
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-date.php' );
						$current_range = $this->get_current_range();
						$results       = array();
						add_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
						$report = new WC_Report_Sales_By_Date();
						$report->check_current_range_nonce( $current_range );
						$report->calculate_current_range( $current_range );
						$result               = $report->get_report_data();
						$this->chart_interval = $report->chart_interval;
						if ( isset( $result->orders ) && is_array( $result->orders ) && count( $result->orders ) ) {
							foreach ( $result->orders as $order ) {
								$order->total_sales        = self::convert_to_default_currency( $order->total_sales, $this->currencies[ $this->currency ]['rate'] );
								$order->total_shipping     = self::convert_to_default_currency( $order->total_shipping, $this->currencies[ $this->currency ]['rate'] );
								$order->total_tax          = self::convert_to_default_currency( $order->total_tax, $this->currencies[ $this->currency ]['rate'] );
								$order->total_shipping_tax = self::convert_to_default_currency( $order->total_shipping_tax, $this->currencies[ $this->currency ]['rate'] );
							}
						}
						if ( isset( $result->refunded_orders ) && is_array( $result->refunded_orders ) && count( $result->refunded_orders ) ) {
							foreach ( $result->refunded_orders as $refunded_orders ) {
								$refunded_orders->total_refund       = self::convert_to_default_currency( $refunded_orders->total_refund, $this->currencies[ $this->currency ]['rate'] );
								$refunded_orders->total_shipping     = self::convert_to_default_currency( $refunded_orders->total_shipping, $this->currencies[ $this->currency ]['rate'] );
								$refunded_orders->total_tax          = self::convert_to_default_currency( $refunded_orders->total_tax, $this->currencies[ $this->currency ]['rate'] );
								$refunded_orders->total_shipping_tax = self::convert_to_default_currency( $refunded_orders->total_shipping_tax, $this->currencies[ $this->currency ]['rate'] );
								$refunded_orders->net_refund         = self::convert_to_default_currency( $refunded_orders->net_refund, $this->currencies[ $this->currency ]['rate'] );
							}
						}
						if ( isset( $result->refund_lines ) && is_array( $result->refund_lines ) && count( $result->refund_lines ) ) {
							foreach ( $result->refund_lines as $refund_lines ) {
								$refund_lines->total_refund       = self::convert_to_default_currency( $refund_lines->total_refund, $this->currencies[ $this->currency ]['rate'] );
								$refund_lines->total_shipping     = self::convert_to_default_currency( $refund_lines->total_shipping, $this->currencies[ $this->currency ]['rate'] );
								$refund_lines->total_tax          = self::convert_to_default_currency( $refund_lines->total_tax, $this->currencies[ $this->currency ]['rate'] );
								$refund_lines->total_shipping_tax = self::convert_to_default_currency( $refund_lines->total_shipping_tax, $this->currencies[ $this->currency ]['rate'] );
								$refund_lines->total_sales        = self::convert_to_default_currency( $refund_lines->total_sales, $this->currencies[ $this->currency ]['rate'] );
							}
						}
						$result->total_tax                   = self::convert_to_default_currency( $result->total_tax, $this->currencies[ $this->currency ]['rate'] );
						$result->total_shipping              = self::convert_to_default_currency( $result->total_shipping, $this->currencies[ $this->currency ]['rate'] );
						$result->total_shipping_tax          = self::convert_to_default_currency( $result->total_shipping_tax, $this->currencies[ $this->currency ]['rate'] );
						$result->total_sales                 = self::convert_to_default_currency( $result->total_sales, $this->currencies[ $this->currency ]['rate'] );
						$result->net_sales                   = self::convert_to_default_currency( $result->net_sales, $this->currencies[ $this->currency ]['rate'] );
						$result->total_tax_refunded          = self::convert_to_default_currency( $result->total_tax_refunded, $this->currencies[ $this->currency ]['rate'] );
						$result->total_shipping_refunded     = self::convert_to_default_currency( $result->total_shipping_refunded, $this->currencies[ $this->currency ]['rate'] );
						$result->total_shipping_tax_refunded = self::convert_to_default_currency( $result->total_shipping_tax_refunded, $this->currencies[ $this->currency ]['rate'] );
						$result->total_refunds               = self::convert_to_default_currency( $result->total_refunds, $this->currencies[ $this->currency ]['rate'] );
						$results[]                           = $result;
						remove_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
						add_filter( 'woocommerce_admin_report_data', array( $this, 'woocommerce_admin_report_data' ) );
						$this->results = $results;
					}
				} elseif ( $report === 'sales_by_product' && ! empty( $_REQUEST['product_ids'] ) ) {
					$this->currency = $currency;
					if ( isset( $this->currencies[ $this->currency ] ) ) {
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-product.php' );
						$current_range = $this->get_current_range();
						add_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
						$report = new WC_Report_Sales_By_Product();
						$report->check_current_range_nonce( $current_range );
						$report->calculate_current_range( $current_range );
						$total_sales = $report->get_order_report_data(
							array(
								'data'         => array(
									'_line_total' => array(
										'type'            => 'order_item_meta',
										'order_item_type' => 'line_item',
										'function'        => 'SUM',
										'name'            => 'order_item_amount',
									),
								),
								'where_meta'   => array(
									'relation' => 'OR',
									array(
										'type'       => 'order_item_meta',
										'meta_key'   => array( '_product_id', '_variation_id' ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
										'meta_value' => $report->product_ids, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
										'operator'   => 'IN',
									),
								),
								'query_type'   => 'get_var',
								'filter_range' => true,
								'order_status' => array( 'completed', 'processing', 'on-hold', 'refunded' ),
							)
						);
						remove_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
						add_filter( 'woocommerce_reports_get_order_report_data', array(
							$this,
							'woocommerce_reports_get_order_report_data'
						), 10, 2 );
						$this->total_sales = self::convert_to_default_currency( $total_sales, $this->currencies[ $this->currency ]['rate'] );
					}
				} elseif ( $report === 'sales_by_category' && ! empty( $_GET['show_categories'] ) ) {
					$this->currency = $currency;
					if ( isset( $this->currencies[ $this->currency ] ) ) {
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
						include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-category.php' );
						$current_range = $this->get_current_range();
						add_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
						$report = new WC_Report_Sales_By_Category();
						$report->check_current_range_nonce( $current_range );
						$report->calculate_current_range( $current_range );
						$order_items = $report->get_order_report_data(
							array(
								'data'         => array(
									'_product_id' => array(
										'type'            => 'order_item_meta',
										'order_item_type' => 'line_item',
										'function'        => '',
										'name'            => 'product_id',
									),
									'_line_total' => array(
										'type'            => 'order_item_meta',
										'order_item_type' => 'line_item',
										'function'        => 'SUM',
										'name'            => 'order_item_amount',
									),
									'post_date'   => array(
										'type'     => 'post_data',
										'function' => '',
										'name'     => 'post_date',
									),
								),
								'group_by'     => 'ID, product_id, post_date',
								'query_type'   => 'get_results',
								'filter_range' => true,
							)
						);

						if ( is_array( $order_items ) && count( $order_items ) ) {
							foreach ( $order_items as $order_item ) {
								$order_item->order_item_amount = self::convert_to_default_currency( $order_item->order_item_amount, $this->currencies[ $this->currency ]['rate'] );
							}

							$this->order_items = $order_items;
							add_filter( 'woocommerce_reports_get_order_report_data', array(
								$this,
								'woocommerce_reports_get_order_report_data'
							), 10, 2 );
						}
						remove_filter( 'woocommerce_reports_get_order_report_data_args', array(
							$this,
							'report_by_currency_for_all'
						) );
					}
				}
			}
		}
	}

	/**
	 * @param $price
	 * @param $rate
	 *
	 * @return float|int
	 */
	public static function convert_to_default_currency( $price, $rate ) {
		return floatval( $price ) / floatval( $rate );
	}

	/**
	 *
	 */
	public function admin_footer() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return;
		}
		$currency          = isset( $_GET['wmc-currency'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) ) : '';
		$view_default      = isset( $_GET['wmc-view-default-currency'] ) ? sanitize_text_field( wp_unslash( $_GET['wmc-view-default-currency'] ) ) : '';
		$request_uri       = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$view_default_link = $view_default === 'yes' ? remove_query_arg( 'wmc-view-default-currency', $request_uri ) : add_query_arg( array( 'wmc-view-default-currency' => 'yes' ), $request_uri );
		$links             = $this->settings->get_links();
		$currency_name     = get_woocommerce_currencies();
		?>
        <li class="wmc-reports-currency-selector">
            <div class="woocommerce-multi-currency shortcode">
                <div class="wmc-currency">
                    <select class="wmc-nav"
                            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        <option value="<?php echo esc_url( remove_query_arg( 'wmc-currency', $request_uri ) ) ?>"><?php esc_html_e( 'All currencies', 'woo-multi-currency' ) ?></option>
						<?php
						foreach ( $links as $code => $link ) {
							?>
                            <option <?php selected( $currency, $code ) ?>
                                    value="<?php echo esc_url( $link ) ?>"><?php echo esc_html( $currency_name[ $code ] ) ?></option>
							<?php
						}
						?>
                    </select>
                </div>
            </div>
        </li>
		<?php
		if ( $currency && $currency !== $this->default_currency ) {
			?>
            <li class="wmc-view-default-currency-container">
                <input id="wmc-view-default-currency" type="checkbox"
                       value="yes" <?php checked( $view_default, 'yes' ) ?>
                       data-report_link="<?php echo esc_url( $view_default_link ) ?>"><label
                        for="wmc-view-default-currency"><?php esc_html_e( 'View in default currency', 'woo-multi-currency' ) ?></label>
            </li>
			<?php
		}
	}

	/**Filter orders by each currency
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function report_by_currency_for_all( $args ) {
		$args['where_meta'][]           = array(
			'meta_key'   => '_order_currency',// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value' => $this->currency,// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'operator'   => '=',
		);
		$args['where_meta']['relation'] = 'AND';

		$args['nocache'] = true;

		return $args;
	}

	public function add_return_order_id( $args ) {
		if ( ! isset( $args['data']['ID'] ) ) {
			if ( ! empty( $args['data']['_order_total'] ) ) {
				$args['data']['ID']                       = array(
					'type'     => 'post_data',
					'function' => '',
					'name'     => 'order_id',
				);
				$args['group_by']                         = 'order_id';
				$args['data']['_order_total']['function'] = '';
			} elseif ( ! empty( $args['data']['_line_total'] ) ) {
				$args['data']['ID']                      = array(
					'type'     => 'post_data',
					'function' => '',
					'name'     => 'order_id',
				);
				$args['group_by']                        = 'order_id';
				$args['data']['_line_total']['function'] = '';
			}

		}
		$args['nocache'] = true;

		return $args;
	}

	/**Convert to default currency for WooCommerce Status in WP dashboard
	 *
	 * @param $result
	 * @param $data
	 *
	 * @return mixed
	 */
	public function woocommerce_reports_get_order_report_data_for_dashboard( $result, $data ) {
		if ( $this->is_dashboard ) {
			if ( count( array_keys( $data ) ) === 3 && count( array_intersect( array_keys( $data ), array(
					'_order_total',
					'post_date',
					'ID'
				) ) ) === 3 ) {
				if ( is_array( $result ) && count( $result ) ) {
					foreach ( $result as $key => $value ) {
						$order_id        = $value->order_id;
						$line_product    = wc_get_order( $order_id );
						$_order_currency = $line_product->get_meta('_order_currency', true );
						$wmc_order_info  = $line_product->get_meta('wmc_order_info', true );
						$rate            = 0;
						if ( isset( $wmc_order_info[ $this->default_currency ] ) && isset( $wmc_order_info[ $this->default_currency ]['is_main'] ) && $wmc_order_info[ $this->default_currency ]['is_main'] == 1 ) {
							if ( isset( $wmc_order_info[ $_order_currency ] ) ) {
								$rate = $wmc_order_info[ $_order_currency ]['rate'];
							}
							if ( isset( $this->currencies[ $_order_currency ] ) ) {
								$rate = $this->currencies[ $_order_currency ]['rate'];
							}

						} elseif ( isset( $this->currencies[ $_order_currency ] ) ) {
							$rate = $this->currencies[ $_order_currency ]['rate'];
						}
						if ( $rate > 0 ) {
							$value->sparkline_value = self::convert_to_default_currency( $value->sparkline_value, $rate );
						}
					}
				}
			} else {
				$result = $this->woocommerce_reports_get_order_report_data_1( $result, $data );
			}
			$this->is_dashboard = false;
		}

		return $result;
	}

	/**
	 * @param $result
	 * @param $data
	 *
	 * @return mixed
	 */
	public function woocommerce_reports_get_order_report_data_1( $result, $data ) {
		if ( isset( $data['ID'] ) && ( ! empty( $data['_order_total'] ) || ! empty( $data['_line_total'] ) ) ) {
			foreach ( $result as $data_k => $data_obj ) {
				$order_id = isset( $data_obj->order_id ) ? $data_obj->order_id : ( isset( $data_obj->refund_id ) ? $data_obj->refund_id : '' );
				if ( $order_id ) {
				    $line_order = wc_get_order( $order_id );
					$_order_currency = $line_order->get_meta('_order_currency', true );
					if ( $this->default_currency !== $_order_currency ) {
						$wmc_order_info = $line_order->get_meta('wmc_order_info', true );
						$rate           = 0;
						if ( isset( $wmc_order_info[ $this->default_currency ] ) && isset( $wmc_order_info[ $this->default_currency ]['is_main'] ) && $wmc_order_info[ $this->default_currency ]['is_main'] == 1 ) {
							if ( isset( $wmc_order_info[ $_order_currency ] ) ) {
								$rate = $wmc_order_info[ $_order_currency ]['rate'];
							}
							if ( isset( $this->currencies[ $_order_currency ] ) ) {
								$rate = $this->currencies[ $_order_currency ]['rate'];
							}

						} elseif ( isset( $this->currencies[ $_order_currency ] ) ) {
							$rate = $this->currencies[ $_order_currency ]['rate'];
						}
						if ( $rate > 0 ) {
							foreach ( $data_obj as $data_obj_k => $data_obj_v ) {
								if ( ! in_array( $data_obj_k, array( 'post_date', 'order_id', 'refund_id' ) ) ) {
									$data_obj->{$data_obj_k} = self::convert_to_default_currency( $data_obj_v, $rate );
								}
							}
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * @param $result
	 * @param $data
	 *
	 * @return mixed
	 */
	public function woocommerce_reports_get_order_report_data( $result, $data ) {
		if ( isset( $data['_line_total'] ) ) {
			if ( $this->total_sales !== null && ! is_array( $result ) ) {
				$result = $this->total_sales;
			} elseif ( $this->order_items !== null && is_array( $result ) ) {
				$result = $this->order_items;
			}
		}

		return $result;
	}

	/**Recalculate report data after converting all to default currency
	 *
	 * @param $report_data
	 *
	 * @return mixed
	 */
	public function woocommerce_admin_report_data( $report_data ) {
		if ( is_array( $this->results ) && count( $this->results ) ) {
			$report_data->orders          = array();
			$report_data->refund_lines    = array();
			$report_data->refunded_orders = array();
			$report_data->full_refunds    = array();
			foreach ( $this->results as $result ) {
				if ( isset( $result->orders ) && is_array( $result->orders ) && count( $result->orders ) ) {
					$report_data->orders = array_merge( $report_data->orders, $result->orders );
				}
				if ( isset( $result->refund_lines ) && is_array( $result->refund_lines ) && count( $result->refund_lines ) ) {
					$report_data->refund_lines = array_merge( $report_data->refund_lines, $result->refund_lines );
				}
				if ( isset( $result->refunded_orders ) && is_array( $result->refunded_orders ) && count( $result->refunded_orders ) ) {
					$report_data->refunded_orders = array_merge( $report_data->refunded_orders, $result->refunded_orders );
				}
				if ( isset( $result->full_refunds ) && is_array( $result->full_refunds ) && count( $result->full_refunds ) ) {
					$report_data->full_refunds = array_merge( $report_data->full_refunds, $result->full_refunds );
				}
			}
			$report_data->total_tax_refunded          = 0;
			$report_data->total_shipping_refunded     = 0;
			$report_data->total_shipping_tax_refunded = 0;
			$report_data->total_refunds               = 0;

			$report_data->refunded_orders = array_merge( $report_data->partial_refunds, $report_data->full_refunds );

			foreach ( $report_data->refunded_orders as $key => $value ) {
				$report_data->total_tax_refunded          += floatval( $value->total_tax < 0 ? $value->total_tax * - 1 : $value->total_tax );
				$report_data->total_refunds               += floatval( $value->total_refund );
				$report_data->total_shipping_tax_refunded += floatval( $value->total_shipping_tax < 0 ? $value->total_shipping_tax * - 1 : $value->total_shipping_tax );
				$report_data->total_shipping_refunded     += floatval( $value->total_shipping < 0 ? $value->total_shipping * - 1 : $value->total_shipping );

				// Only applies to parial.
				if ( isset( $value->order_item_count ) ) {
					$report_data->refunded_order_items += floatval( $value->order_item_count < 0 ? $value->order_item_count * - 1 : $value->order_item_count );
				}
			}
			// Totals from all orders - including those refunded. Subtract refunded amounts.
			$report_data->total_tax          = wc_format_decimal( array_sum( wp_list_pluck( $report_data->orders, 'total_tax' ) ) - $report_data->total_tax_refunded, 2 );
			$report_data->total_shipping     = wc_format_decimal( array_sum( wp_list_pluck( $report_data->orders, 'total_shipping' ) ) - $report_data->total_shipping_refunded, 2 );
			$report_data->total_shipping_tax = wc_format_decimal( array_sum( wp_list_pluck( $report_data->orders, 'total_shipping_tax' ) ) - $report_data->total_shipping_tax_refunded, 2 );

			// Total the refunds and sales amounts. Sales subract refunds. Note - total_sales also includes shipping costs.
			$report_data->total_sales = wc_format_decimal( array_sum( wp_list_pluck( $report_data->orders, 'total_sales' ) ) - $report_data->total_refunds, 2 );
			$report_data->net_sales   = wc_format_decimal( $report_data->total_sales - $report_data->total_shipping - max( 0, $report_data->total_tax ) - max( 0, $report_data->total_shipping_tax ), 2 );

			// Calculate average based on net.
			$report_data->average_sales       = wc_format_decimal( $report_data->net_sales / ( $this->chart_interval + 1 ), 2 );
			$report_data->average_total_sales = wc_format_decimal( $report_data->total_sales / ( $this->chart_interval + 1 ), 2 );

			// Total orders and discounts also includes those which have been refunded at some point.
			$report_data->total_coupons         = number_format( array_sum( wp_list_pluck( $report_data->coupons, 'discount_amount' ) ), 2, '.', '' );
			$report_data->total_refunded_orders = absint( count( $report_data->full_refunds ) );

			// Total orders in this period, even if refunded.
			$report_data->total_orders = absint( array_sum( wp_list_pluck( $report_data->order_counts, 'count' ) ) );

			// Item items ordered in this period, even if refunded.
			$report_data->total_items = absint( array_sum( wp_list_pluck( $report_data->order_items, 'order_item_count' ) ) );
			$this->results            = array();
		}

		return $report_data;
	}

	/**Set price decimal for each currency for report
	 *
	 * @param $decimal
	 *
	 * @return int
	 */
	public function wc_get_price_decimals( $decimal ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return $decimal;
		}
		$view_default = isset( $_GET['wmc-view-default-currency'] ) ? sanitize_text_field( wp_unslash( $_GET['wmc-view-default-currency'] ) ) : '';
		if ( is_admin() && ! empty( $_GET['wmc-currency'] ) && $view_default !== 'yes' ) {
			$currency = strtoupper( sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) );
			if ( $currency !== $this->default_currency ) {
				$decimal = isset( $this->currencies[ $currency ]['decimals'] ) ? $this->currencies[ $currency ]['decimals'] : 0;
			}
		}

		return (int) $decimal;
	}

	/**Set currency for report
	 *
	 * @param $woocommerce_currency
	 *
	 * @return string
	 */
	public function woocommerce_currency( $woocommerce_currency ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return $woocommerce_currency;
		}
		$view_default = isset( $_GET['wmc-view-default-currency'] ) ? sanitize_text_field( wp_unslash( $_GET['wmc-view-default-currency'] ) ) : '';
		$currency     = isset( $_GET['wmc-currency'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) ) : '';
		if ( is_admin() && ! empty( $_GET['wmc-currency'] ) && $view_default !== 'yes' ) {
			if ( $currency !== $this->default_currency ) {
				$woocommerce_currency = $currency;
			}
		}

		return $woocommerce_currency;
	}

	/**Filter orders by selected currency
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function report_by_currency( $args ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_reports' ) ) {
			return $args;
		}
		global $pagenow;
		if ( $pagenow === 'index.php' ) {
			/**
			 * For WooCommerce Status in WP dashboard
			 */
			if ( ( ! empty( $args['data']['_order_total'] ) || ( ! empty( $args['data']['_product_id'] ) && ! empty( $args['data']['_line_total'] ) ) ) && empty( $args['data']['ID'] ) ) {
				$args['data']['ID'] = array(
					'type'     => 'post_data',
					'function' => '',
					'name'     => 'order_id',
				);
				$args['group_by']   = 'order_id';
				$args['nocache']    = true;
				$this->is_dashboard = true;
			}
		} else {
			$report   = isset( $_GET['report'] ) ? sanitize_text_field( wp_unslash( $_GET['report'] ) ) : '';
			$tab      = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'orders';
			$currency = isset( $_GET['wmc-currency'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) ) : '';
			if ( $currency ) {
				if ( $currency !== $this->default_currency ) {
					$args['nocache'] = true;
				}
				if ( ( $tab === 'orders' && $report !== 'coupon_usage' ) || ( $tab === 'customers' && $report === 'customers' ) ) {
					$args['where_meta'][]           = array(
						'meta_key'   => '_order_currency',// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						'meta_value' => $currency,// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
						'operator'   => '=',
					);
					$args['where_meta']['relation'] = 'AND';
				}
			}
		}

		return $args;
	}
}