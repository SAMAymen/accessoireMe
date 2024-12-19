<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\Jetpack\Constants;

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Price
 */
class WOOMULTI_CURRENCY_F_Frontend_Price {
	protected static $settings;
	protected $price;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( self::$settings->get_enable() ) {
			/*Simple product*/
			add_filter(
				'woocommerce_product_get_regular_price', array(
				$this,
				'woocommerce_product_get_regular_price'
			), 99, 2 );
			add_filter(
				'woocommerce_product_get_sale_price', array(
				$this,
				'woocommerce_product_get_sale_price'
			), 99, 2
			);
			add_filter( 'woocommerce_product_get_price', array( $this, 'woocommerce_product_get_price' ), 99, 2 );
			//
			/*Variable price*/
			add_filter(
				'woocommerce_product_variation_get_price', array(
				$this,
				'woocommerce_product_variation_get_price'
			), 99, 2
			);
			add_filter(
				'woocommerce_product_variation_get_regular_price', array(
				$this,
				'woocommerce_product_variation_get_regular_price'
			), 99, 2
			);
			add_filter(
				'woocommerce_product_variation_get_sale_price', array(
				$this,
				'woocommerce_product_variation_get_sale_price'
			), 99, 2
			);

			/*Variable Parent min max price*/
			add_filter( 'woocommerce_variation_prices', array( $this, 'get_woocommerce_variation_prices' ), 99, 3 );

			/*Pay with Multi Currencies*/
			add_action( 'init', array( $this, 'init' ), 99 );

			/*Approximately*/
			add_filter( 'woocommerce_get_price_html', array( $this, 'add_approximately_price' ), 20, 2 );
			if ( self::$settings->get_price_switcher() ) {
				add_action( 'woocommerce_single_product_summary', array( $this, 'add_price_switcher' ),
					(int) self::$settings->get_params( 'price_switcher_position' ), 2 );
			}

			if ( is_plugin_active( 'woocommerce-bookings/woocommerce-bookings.php' ) ) {
				/*Fix wrong sale price added when changing currency for WooCommerce Bookings products*/
				add_filter( 'woocommerce_get_price_html', array( $this, 'woocommerce_get_price_html' ), 20, 2 );
			}
			/*Yith dynamic pricing and discount*/
			add_action( 'ywdpd_before_calculate_discounts', array( $this, 'remove_filters' ), 1 );
			add_action( 'ywdpd_after_calculate_discounts', array( $this, 'add_filters' ), 1 );
		}
	}

	public function remove_filters() {
		remove_filter( 'woocommerce_product_get_price', array( $this, 'woocommerce_product_get_price' ), 99 );
	}

	public function add_filters() {
		add_filter( 'woocommerce_product_get_price', array( $this, 'woocommerce_product_get_price' ), 99, 2 );
	}

	/**
	 * @param $price_html
	 * @param $product WC_Product_Booking
	 *
	 * @return string
	 */
	public function woocommerce_get_price_html( $price_html, $product ) {
		if ( $product && is_a( $product, 'WC_Product_Booking' ) && self::$settings->get_current_currency() !== self::$settings->get_default_currency() ) {
			$base_price = WC_Bookings_Cost_Calculation::calculated_base_cost( $product );
			if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
				if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
					$display_price = wc_get_price_including_tax( $product, array(
						'qty'   => 1,
						'price' => $base_price,
					) );
				} else {
					$display_price = $product->get_price_including_tax( 1, $base_price );
				}
			} else {
				if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
					$display_price = wc_get_price_excluding_tax( $product, array(
						'qty'   => 1,
						'price' => $base_price,
					) );
				} else {
					$display_price = $product->get_price_excluding_tax( 1, $base_price );
				}
			}
			remove_filter( 'woocommerce_product_get_price', array( $this, 'woocommerce_product_get_price' ), 99 );
			$display_price_suffix_comp  = wc_price( apply_filters( 'woocommerce_product_get_price', $display_price, $product ) ) . $product->get_price_suffix();
			$original_price_suffix_comp = wc_price( $display_price ) . $product->get_price_suffix();
			add_filter( 'woocommerce_product_get_price', array( $this, 'woocommerce_product_get_price' ), 99, 2 );
			$original_price_suffix = wc_price( $display_price ) . $product->get_price_suffix();
			$display_price         = apply_filters( 'woocommerce_product_get_price', $display_price, $product );
			$display_price_suffix  = wc_price( $display_price ) . $product->get_price_suffix();

			if ( $original_price_suffix_comp !== $display_price_suffix_comp ) {
				$price_html = "<del>{$original_price_suffix}</del><ins>{$display_price_suffix}</ins>";
			} elseif ( $display_price ) {
				if ( $product->has_additional_costs() || $product->get_display_cost() ) {
					/* translators: 1: display price */
					$price_html = sprintf( esc_html__( 'From: %s', 'woocommerce-bookings' ), wc_price( $display_price ) ) . $product->get_price_suffix();
				} else {
					$price_html = wc_price( $display_price ) . $product->get_price_suffix();
				}
			} elseif ( ! $product->has_additional_costs() ) {
				$price_html = esc_html__( 'Free', 'woocommerce-bookings' );
			} else {
				$price_html = '';
			}
		}

		return $price_html;
	}

	/**
	 *
	 */
	public function add_price_switcher() {
		if ( is_product() ) {
			echo do_shortcode( '[woo_multi_currency_product_price_switcher]' );
		}
	}

	/**
	 * Get Product variation price
	 *
	 * @param $product
	 *
	 * @return int|string
	 */
	public static function get_variation_max_price( $product, $currency_code = false, $raw = false ) {
		$variation_ids = $product->get_visible_children();
		$price_max     = 0;
		foreach ( $variation_ids as $variation_id ) {
			$variation = wc_get_product( $variation_id );

			if ( $variation ) {
				$price = 0;
				if ( ! $currency_code ) {
					$currenct_currency = self::$settings->get_current_currency();
				} elseif ( ! $raw ) {
					$currenct_currency = $currency_code;
				}
				if ( self::$settings->check_fixed_price() && ! $raw ) {
					$product_id    = $variation_id;
					$product_price = wmc_adjust_fixed_price( self::static_json_price_meta( $variation->get_meta('_regular_price_wmcp', true ) ) );
					$sale_price    = wmc_adjust_fixed_price( self::static_json_price_meta( $variation->get_meta('_sale_price_wmcp', true ) ) );
					if ( isset( $product_price[ $currenct_currency ] ) && ! $product->is_on_sale() && $product_price[ $currenct_currency ] > 0 ) {
						$price = $product_price[ $currenct_currency ];
					} elseif ( isset( $sale_price[ $currenct_currency ] ) && $sale_price[ $currenct_currency ] > 0 ) {
						$price = $sale_price[ $currenct_currency ];
					}
				}

				if ( ! $price ) {
					$price = $variation->get_price( 'edit' );
					if ( ! $raw ) {
						$price = wmc_get_price( $price, $currency_code );
					}
				}
				if ( $price > $price_max ) {
					$price_max = $price;
				}
			}
		}


		return $price_max;
	}

	/**
	 * @param $product WC_Product|WC_Product_Variable
	 * @param bool $currency_code
	 * @param bool $raw
	 *
	 * @return bool|float|int|mixed|string
	 */
	public static function get_variation_min_price( $product, $currency_code = false, $raw = false ) {
		$variation_ids     = $product->get_visible_children();
		$price_min         = false;
		$check_fixed_price = self::$settings->check_fixed_price();
		foreach ( $variation_ids as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( $variation ) {
				$price = 0;
				if ( ! $currency_code ) {
					$current_currency = self::$settings->get_current_currency();
				} elseif ( ! $raw ) {
					$current_currency = $currency_code;
				}
				if ( $check_fixed_price && ! $raw ) {
					$product_id    = $variation_id;
					$product_price = wmc_adjust_fixed_price( self::static_json_price_meta( $variation->get_meta('_regular_price_wmcp', true ) ) );
					$sale_price    = wmc_adjust_fixed_price( self::static_json_price_meta( $variation->get_meta('_sale_price_wmcp', true ) ) );
					if ( isset( $product_price[ $current_currency ] ) && ! $product->is_on_sale( 'edit' ) && $product_price[ $current_currency ] > 0 ) {
						$price = $product_price[ $current_currency ];
					} elseif ( isset( $sale_price[ $current_currency ] ) && $sale_price[ $current_currency ] > 0 ) {
						$price = $sale_price[ $current_currency ];
					}
				}

				if ( ! $price ) {
					$price = $variation->get_price( 'edit' );
					if ( ! $raw ) {
						$price = wmc_get_price( $price, $currency_code );
					}
				}
				if ( $price_min === false ) {
					$price_min = $price;
				}
				if ( $price < $price_min ) {
					$price_min = $price;
				}
			}
		}

		return $price_min;
	}

	/**
	 * @param $html_price
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function add_approximately_price( $html_price, $product ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return $html_price;
		}
		if ( self::$settings->get_auto_detect() == 2 ) {
			if ( '' === $product->get_price() || ! $product->is_in_stock() ) {
				return $html_price;
			}
			if ( ! self::$settings->getcookie( 'wmc_currency_rate' ) || ! self::$settings->getcookie( 'wmc_currency_symbol' ) || ! self::$settings->getcookie( 'wmc_ip_info' ) ) {
				return $html_price;
			}
			$geoplugin_arg = json_decode( base64_decode( self::$settings->getcookie( 'wmc_ip_info' ) ), true );

			$detect_currency_code = isset( $geoplugin_arg['currency_code'] ) ? $geoplugin_arg['currency_code'] : '';
			if ( $detect_currency_code == self::$settings->get_current_currency() ) {
				return $html_price;
			}
			$list_currencies    = self::$settings->get_list_currencies();
			$default_currency   = self::$settings->get_default_currency();
			$decimal_separator  = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();
			if ( $detect_currency_code && isset( $list_currencies[ $detect_currency_code ] ) ) {
				$decimals    = $list_currencies[ $detect_currency_code ]['decimals'];
				$current_pos = $list_currencies[ $detect_currency_code ]['pos'];
			} else {
				$decimals    = $list_currencies[ $default_currency ]['decimals'];
				$current_pos = $list_currencies[ $default_currency ]['pos'];
			}
			$rate   = self::$settings->getcookie( 'wmc_currency_rate' );
			$symbol = self::$settings->getcookie( 'wmc_currency_symbol' );
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


			$price = number_format( wc_get_price_to_display( $product, array(
					'qty'   => 1,
					'price' => $product->get_price( 'edit' )
				) ) * $rate, $decimals, $decimal_separator, $thousand_separator );
			$pos   = strpos( $symbol, '#PRICE#' );
			if ( $pos === false ) {
				$formatted_price = sprintf( $format, $symbol, $price );
			} else {
				$formatted_price = str_replace( '#PRICE#', $price, $symbol );
			}
			$max_price = '';
			if ( $product->get_type() == 'variable' ) {
				$price_max = self::get_variation_max_price( $product, false, true );
				if ( $price_max != $product->get_price( 'edit' ) ) {
					$price_max = number_format( wc_get_price_to_display( $product, array(
							'qty'   => 1,
							'price' => $price_max
						) ) * $rate, $decimals, $decimal_separator, $thousand_separator );
					if ( $pos === false ) {
						$max_price = ' - ' . sprintf( $format, $symbol, $price_max );
					} else {
						$max_price = ' - ' . str_replace( '#PRICE#', $price_max, $symbol );
					}
				}
			}
			$html_price .= '<span class="wmc-approximately">' . esc_html__( 'Approximately', 'woo-multi-currency' ) . ': ' . $formatted_price . $max_price . '</span>';

		}

		return $html_price;
	}

	/**
	 * Check on checkout page
	 */
	public function init() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_price' ) ) {
			return;
		}

		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}
		/*Fix UX Builder of Flatsome*/
		if ( isset( $_GET['uxb_iframe'] ) ) {
			return;
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return;
		}
		if ( ! ( wp_doing_ajax() && isset( $_POST['action'] ) && $_POST['action'] == 'wmc_get_products_price' || ! wp_doing_ajax() ) ) {
			return;
		}
		$allow_multi      = self::$settings->get_enable_multi_payment();
		$current_currency = self::$settings->get_current_currency();
		$old_currency     = self::$settings->getcookie( 'wmc_current_currency_old' );
		if ( ! $allow_multi ) {
			if ( $this->is_checkout() ) {
				self::$settings->set_current_currency( self::$settings->get_default_currency(), false );
			} elseif ( $old_currency && $old_currency != $current_currency ) {
				self::$settings->set_current_currency( $old_currency, false );
			}
		}
	}

	public function is_checkout() {
		if ( class_exists( 'Polylang' ) && function_exists( 'is_checkout' ) ) {
			return is_checkout();
		} else {
			return $this->has_shortcode( $this->get_post_from_url(), 'woocommerce_checkout' ) || strpos( $this->get_current_url(), wc_get_checkout_url() ) !== false || Constants::is_defined( 'WOOCOMMERCE_CHECKOUT' ) || apply_filters( 'woocommerce_is_checkout', false );
		}
	}

	public function get_post_from_url() {
		$current_url = $this->get_current_url();
		if ( class_exists( 'SitePress' ) ) {
			global $sitepress;
			remove_filter( 'url_to_postid', array( $sitepress, 'url_to_postid' ) );
			$id = url_to_postid( $current_url );
			add_filter( 'url_to_postid', array( $sitepress, 'url_to_postid' ) );
		} else {
			$id = url_to_postid( $current_url );
		}

		$post = get_post( $id );

		return $post;
	}

	public function has_shortcode( $post, $tag ) {
		return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $tag );
	}

	public function get_current_url() {
		global $wp;
		$current_url = site_url( add_query_arg( array(), $wp->request ) );

		$redirect_url = isset( $_SERVER['REDIRECT_URI'] ) ? $_SERVER['REDIRECT_URI'] : '';
		$redirect_url = ! empty( $_SERVER['REDIRECT_URL'] ) ? $_SERVER['REDIRECT_URL'] : $redirect_url;
		$redirect_url = ! empty( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : $redirect_url;

		return $current_url . $redirect_url;
	}

	/**Variable Parent min max price
	 *
	 * @param $price_arr
	 * @param $product
	 * @param $for_display
	 *
	 * @return array
	 */
	public function get_woocommerce_variation_prices( $price_arr, $product, $for_display ) {
		$temp_arr = $price_arr;
		if ( is_array( $price_arr ) && ! empty( $price_arr ) ) {
			$fixed_price = self::$settings->check_fixed_price();

			foreach ( $price_arr as $price_type => $values ) {
				foreach ( $values as $key => $value ) {
					if ( $fixed_price ) {
						$wc_variation_product = wc_get_product( $key );
						$current_currency = self::$settings->get_current_currency();
						if ( $temp_arr['regular_price'][ $key ] != $temp_arr['price'][ $key ] ) {
							if ( $price_type == 'regular_price' ) {
								$regular_price_wmcp = wmc_adjust_fixed_price( self::format_json_price_meta( $wc_variation_product->get_meta('_regular_price_wmcp', true ) ) );

								if ( isset( $regular_price_wmcp[ $current_currency ] ) && $regular_price_wmcp[ $current_currency ] > 0 ) {
									$price_arr[ $price_type ][ $key ] = $for_display ? $this->tax_handle( $regular_price_wmcp[ $current_currency ], $product ) : $regular_price_wmcp[ $current_currency ];
								} else {
									$price_arr[ $price_type ][ $key ] = wmc_get_price( $value );
								}
							}

							if ( $price_type == 'price' || $price_type == 'sale_price' ) {
								$sale_price_wmcp = wmc_adjust_fixed_price( self::format_json_price_meta( $wc_variation_product->get_meta('_sale_price_wmcp', true ) ) );

								if ( isset( $sale_price_wmcp[ $current_currency ] ) && $sale_price_wmcp[ $current_currency ] > 0 ) {
									$price_arr[ $price_type ][ $key ] = $for_display ? $this->tax_handle( $sale_price_wmcp[ $current_currency ], $product ) : $sale_price_wmcp[ $current_currency ];
								} elseif ( $temp_arr['regular_price'][ $key ] != $temp_arr['price'][ $key ] ) {
									$price_arr[ $price_type ][ $key ] = wmc_get_price( $value );
								} else {
									$price_arr[ $price_type ][ $key ] = wmc_get_price( $value );
								}
							}
						} else {
							$regular_price_wmcp = wmc_adjust_fixed_price( self::format_json_price_meta( $wc_variation_product->get_meta('_regular_price_wmcp', true ) ) );
							if ( isset( $regular_price_wmcp[ $current_currency ] ) && $regular_price_wmcp[ $current_currency ] > 0 ) {
								$price_arr[ $price_type ][ $key ] = $for_display ? $this->tax_handle( $regular_price_wmcp[ $current_currency ], $product ) : $regular_price_wmcp[ $current_currency ];
							} else {
								$price_arr[ $price_type ][ $key ] = wmc_get_price( $value );
							}
						}

					} else {
						$price_arr[ $price_type ][ $key ] = wmc_get_price( $value );
					}
				}
			}
		}


		return $price_arr;
	}

	/**
	 * @param $price
	 * @param $product
	 *
	 * @return float|string
	 */
	public function tax_handle( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}

		$data = array( 'qty' => 1, 'price' => $price, );

		return 'incl' === get_option( 'woocommerce_tax_display_shop' ) ? wc_get_price_including_tax( $product, $data ) : wc_get_price_excluding_tax( $product, $data );
	}

	/**Sale price with product variable
	 *
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_variation_get_sale_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && count( $changes ) < 1 ) {

			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_sale_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );
				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
		//Do nothing to remove prices hash to alway get live price.
	}

	/**Regular price with product variable
	 *
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_variation_get_regular_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && count( $changes ) < 1 ) {

			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_regular_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );

				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
		//Do nothing to remove prices hash to alway get live price.
	}


	/**Sale product variable price
	 *
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_variation_get_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && count( $changes ) < 1 ) {

			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_regular_price_wmcp', true ) ) );
			$sale_price        = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_sale_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) && ! $product->is_on_sale() ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );
				}
			} elseif ( isset( $sale_price[ $currenct_currency ] ) ) {
				if ( $sale_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $sale_price[ $currenct_currency ], $product_id, $price );

				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_get_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && count( $changes ) < 1 ) {
			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_regular_price_wmcp', true ) ) );
			$sale_price        = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_sale_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) && ! self::is_on_sale( $product ) ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );

				}
			} elseif ( isset( $sale_price[ $currenct_currency ] ) ) {
				if ( $sale_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $sale_price[ $currenct_currency ], $product_id, $price );

				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_get_sale_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && count( $changes ) < 1 ) {

			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_sale_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {
					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );

				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public function woocommerce_product_get_regular_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}
		$product_id = $product->get_id();
		if ( ! wp_doing_ajax() && isset( $this->price[ $product_id ][ $price ] ) ) {
			return $this->price[ $product_id ][ $price ];
		}
		$changes = $product->get_changes();

		if ( self::$settings->check_fixed_price() && ( is_array( $changes ) ) && ( count( $changes ) < 1 ||
		                                                                           ( count( $changes ) == 1 && isset( $changes['description'] ) ) ) ) {

			$currenct_currency = self::$settings->get_current_currency();
			$product_id        = $product->get_id();
			$product_price     = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_regular_price_wmcp', true ) ) );
			if ( isset( $product_price[ $currenct_currency ] ) ) {
				if ( $product_price[ $currenct_currency ] > 0 ) {

					return $this->set_cache( $product_price[ $currenct_currency ], $product_id, $price );
				}
			}
		}

		return $this->set_cache( wmc_get_price( $price ), $product_id, $price );
	}

	/**Set price to global. It will help more speedy.
	 *
	 * @param $price
	 * @param $id
	 * @param $key
	 *
	 * @return mixed
	 */
	protected function set_cache( $price, $id, $key ) {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_price' ) ) {
			return $price;
		}

		$ajax = isset( $_GET['wc-ajax'] ) ? sanitize_text_field( $_GET['wc-ajax'] ) : '';
		if ( $ajax === 'ppc-create-order' ) {
			/**
			 * Fix bug with WooCommerce PayPal Payments
			 *
			 * 'HUF', 'JPY' and 'TWD' do not support decimals, the rest must have 2 decimals
			 */
			$current_currency = self::$settings->get_current_currency();
			$args             = array( 'decimals' => 2 );
			if ( in_array( $current_currency, array( 'HUF', 'JPY', 'TWD' ) ) ) {
				$args['decimals'] = 0;
			}
			$price = WOOMULTI_CURRENCY_F_Data::convert_price_to_float( $price, $args );
		}
		if ( $price && $id && $key ) {
			/*Default decimal is "."*/
			$this->price[ $id ][ $key ] = str_replace( ',', '.', $price );

			return $this->price[ $id ][ $key ];
		} else {
			return $price;
		}
	}

	/**Fix error 500 with Subscriptions for WooCommerce plugin from WebToffee if using $product->is_on_sale('edit') for variable_subscription product
	 *
	 * @param $product WC_Product
	 *
	 * @return bool
	 */
	public static function is_on_sale( $product ) {
		$context = 'edit';
		if ( '' !== (string) $product->get_sale_price( $context ) && $product->get_regular_price( $context ) > $product->get_sale_price( $context ) ) {
			$on_sale = true;

			if ( $product->get_date_on_sale_from( $context ) && $product->get_date_on_sale_from( $context )->getTimestamp() > time() ) {
				$on_sale = false;
			}

			if ( $product->get_date_on_sale_to( $context ) && $product->get_date_on_sale_to( $context )->getTimestamp() < time() ) {
				$on_sale = false;
			}
		} else {
			$on_sale = false;
		}

		return $on_sale;
	}

	function format_json_price_meta( $price_meta ) {

		return is_string( $price_meta ) ? json_decode( $price_meta, true ) : $price_meta;
	}

	public static function static_json_price_meta( $price_meta ) {

		return is_string( $price_meta ) ? json_decode( $price_meta, true ) : $price_meta;
	}
}