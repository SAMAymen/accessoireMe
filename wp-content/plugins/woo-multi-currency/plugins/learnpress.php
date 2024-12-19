<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_LearnPress
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_LearnPress {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
				if ( version_compare( learn_press_get_current_version(), '4.1.5', '>=' ) ) {
					add_filter( 'learn-press/course/regular-price', array(
						$this,
						'learn_press_course_regular_price'
					), 99, 2 );
					add_filter( 'learn_press_course_price_html', array(
						$this,
						'learn_press_course_price_html_new'
					), 99, 3 );
					add_filter( 'learn_press_get_cart_subtotal', array(
						$this,
						'learn_press_get_cart_subtotal'
					) );
					add_filter( 'learn_press_get_cart_total', array(
						$this,
						'learn_press_get_cart_total'
					) );
					add_filter( 'learn-press/cart/item-subtotal', array(
						$this,
						'learn_press_cart_item_subtotal'
					), 10, 4 );
					/*LearnPress – WooCommerce Payment Methods Integration*/
					add_filter( 'learn-press/woo-course-price', array(
						$this,
						'learn_press_woo_course_price'
					), 10, 2 );
				} else {
					add_filter( 'learn-press/course-price', array( $this, 'learn_press_course_price' ), 99, 2 );
					add_filter( 'learn_press_course_price_html', array(
						$this,
						'learn_press_course_price_html'
					), 99, 2 );
					add_filter( 'learn_press_course_origin_price_html', array(
						$this,
						'learn_press_course_origin_price_html'
					), 99, 2 );
				}
			}
		}
	}

	public function learn_press_woo_course_price( $price, $course ) {
		return wmc_get_price( $price );
	}

	/**
	 * @param $course_subtotal
	 * @param $course LP_Course
	 * @param $quantity
	 * @param $lp_cart
	 *
	 * @return string
	 */
	public function learn_press_cart_item_subtotal( $course_subtotal, $course, $quantity, $lp_cart ) {
		if ( ! $this->is_default_currency() ) {
			$price           = $course->get_price();
			$row_price       = $price * $quantity;
			$course_subtotal = $this->wc_price( wmc_get_price( $row_price ) );
		}

		return $course_subtotal;
	}

	public function learn_press_get_cart_total( $price ) {
		if ( ! $this->is_default_currency() ) {
			$price = $this->wc_price( wmc_get_price( $GLOBALS['LearnPress']->get_cart()->total ) );
		}

		return $price;
	}

	public function learn_press_get_cart_subtotal( $price ) {
		if ( ! $this->is_default_currency() ) {
			$price = $this->wc_price( wmc_get_price( $GLOBALS['LearnPress']->get_cart()->subtotal ) );
		}

		return $price;
	}

	public function learn_press_course_regular_price( $price, $course_id ) {
		if ( is_float( $price ) ) {
//				$price = wmc_get_price( $price );
		} elseif ( is_string( $price ) ) {
			$course = learn_press_get_course( $course_id );
			if ( $course ) {
				$price = wmc_get_price( $course->get_regular_price() );
				$price = $this->wc_price( $price );
			}
		}

		return $price;
	}

	public function learn_press_course_price_html_new( $price_html, $has_sale, $course_id ) {
		$course = learn_press_get_course( $course_id );
		if ( $course ) {
			$price_html = '';
			if ( $has_sale ) {
				$price_html .= sprintf( '<span class="origin-price">%s</span>', $course->get_regular_price_html() );
			}
			$price_html .= sprintf( '<span class="price">%s</span>', $this->wc_price( wmc_get_price( $course->get_price() ) ) );
		}

		return $price_html;
	}

	public function learn_press_course_price( $price, $product_id ) {

		return wmc_get_price( $price );
	}

	/**
	 * @param $price
	 * @param $course LP_Course
	 *
	 * @return string
	 */
	public function learn_press_course_price_html( $price, $course ) {
		return $this->wc_price( $course->get_price() );
	}

	/**
	 * @param $sale_price
	 * @param $course LP_Course
	 *
	 * @return string
	 */
	public function learn_press_course_origin_price_html( $sale_price, $course ) {
		if ( $course ) {
			if ( $course->has_sale_price() ) {
				$sale_price = $this->wc_price( wmc_get_price( $course->get_origin_price() ) );
			}
		}

		return $sale_price;
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

	private function is_default_currency() {
		return $this->settings->get_current_currency() === $this->settings->get_default_currency();
	}
}