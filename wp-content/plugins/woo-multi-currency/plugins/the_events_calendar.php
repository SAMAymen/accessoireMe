<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_The_Events_Calendar
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_The_Events_Calendar {
	protected $settings;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'tribe_get_cost', array( $this, 'tribe_get_cost' ), 10, 3 );
		}
	}

	public function tribe_get_cost( $cost, $post_id, $with_currency_symbol ) {
		$current_currency = $this->settings->get_current_currency();
		if ( $this->settings->get_default_currency() == $current_currency ) {
			return $cost;
		}
		$cost = get_post_meta( $post_id, '_EventCost' );
		if ( $this->settings->check_fixed_price() ) {
			$args      = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'meta_key'       => '_tribe_wooticket_for_event',// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $post_id,// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
			);
			$the_query = new WP_Query( $args );
			$prs       = array();
			if ( $the_query->have_posts() ) {
				$count = 0;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$product_id = get_the_ID();
					$product    = wc_get_product( $product_id );
					if ( $product ) {
						$product_price = wmc_adjust_fixed_price( json_decode( $product->get_meta('_regular_price_wmcp', true ), true ) );
						$sale_price    = wmc_adjust_fixed_price( json_decode( $product->get_meta('_sale_price_wmcp', true ), true ) );
						if ( $product->is_on_sale() ) {
							if ( isset( $sale_price[ $current_currency ] ) && $sale_price[ $current_currency ] ) {
								if ( $with_currency_symbol ) {
									$prs[] = $this->wc_price( $sale_price[ $current_currency ] );

								} else {
									$prs[] = ( $sale_price[ $current_currency ] );

								}
							} else {
								if ( $with_currency_symbol ) {
									$prs[] = $this->wc_price( wmc_get_price( $cost[ $count ] ) );
								} else {
									$prs[] = wmc_get_price( $cost[ $count ] );
								}
							}
						} else {
							if ( isset( $product_price[ $current_currency ] ) && $product_price[ $current_currency ] ) {
								if ( $with_currency_symbol ) {
									$prs[] = $this->wc_price( $product_price[ $current_currency ] );

								} else {
									$prs[] = ( $product_price[ $current_currency ] );

								}
							} else {

								if ( $with_currency_symbol ) {
									$prs[] = $this->wc_price( wmc_get_price( $cost[ $count ] ) );
								} else {
									$prs[] = wmc_get_price( $cost[ $count ] );
								}
							}
						}
					}
					$count ++;
				}
			}

			wp_reset_postdata();
			if ( count( $prs ) ) {
				return implode( ' - ', $prs );
			} else {
				return $cost;
			}
		}
		if ( is_array( $cost ) ) {
			$return = array();
			foreach ( $cost as $c ) {
				if ( $with_currency_symbol ) {
					$return[] = $this->wc_price( wmc_get_price( $c ) );
				} else {
					$return[] = wmc_get_price( $c );
				}
			}

			return implode( ' - ', $return );

		} else {
			if ( $with_currency_symbol ) {
				return $this->wc_price( wmc_get_price( $cost ) );
			} else {
				return wmc_get_price( $cost );
			}
		}

	}

	public function wc_price( $price, $args = array() ) {
		extract(
			apply_filters(
				'wc_price_args', wp_parse_args(
					$args, array(
						'ex_tax_label'       => false,
						'currency'           => get_woocommerce_currency_symbol(),
						'decimal_separator'  => wc_get_price_decimal_separator(),
						'thousand_separator' => wc_get_price_thousand_separator(),
						'decimals'           => wc_get_price_decimals(),
						'price_format'       => get_woocommerce_price_format(),
					)
				)
			)
		);

		$negative = $price < 0;
		$price    = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price    = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, $currency, $price );

		return $formatted_price;
	}
}