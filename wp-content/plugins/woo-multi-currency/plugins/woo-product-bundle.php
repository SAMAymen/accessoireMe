<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPC Product Bundles by WPClever
 *
 * Class WOOMULTI_CURRENCY_F_Plugin_Woo_Product_Bundle
 */
class WOOMULTI_CURRENCY_F_Plugin_Woo_Product_Bundle {
	protected $settings;
	protected static $end_ob;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() && is_plugin_active( 'woo-product-bundle/wpc-product-bundles.php' ) ) {
			add_action( 'woocommerce_add_to_cart', array( $this, 'before_add_to_cart' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'after_add_to_cart' ), - 1 );
			add_action( 'woosb_before_table', array( $this, 'woosb_before_table' ) );
			add_action( 'woosb_before_table', array( $this, 'woosb_before_table' ) );
			add_action( 'woosb_after_table', array( $this, 'woosb_after_table' ) );
			add_filter( 'woocommerce_cart_item_price', array( $this, 'woosb_cart_item_price' ), 20, 2 );
			add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'woosb_cart_item_subtotal' ), 20, 2 );
			add_filter( 'woocommerce_get_price_html', array( $this, 'woosb_get_price_html' ), 199, 2 );
		}
	}

	public function woosb_before_table( $product ) {
		if ( $this->settings->get_current_currency() === $this->settings->get_default_currency() ) {
			return;
		}
		self::$end_ob = true;
		ob_start();
	}

	public function woosb_after_table( $product ) {
		if ( self::$end_ob ) {
			$discount_amount = $product->get_discount_amount();
			$html            = ob_get_clean();
			echo str_replace( 'data-discount-amount="' . esc_attr( $discount_amount ) . '"', 'data-discount-amount="' . esc_attr( wmc_get_price( $discount_amount ) ) . '"', $html );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			self::$end_ob = false;
		}
	}

	public function woosb_get_price_html( $price, $product ) {
		if ( $this->settings->get_current_currency() === $this->settings->get_default_currency() ) {
			return $price;
		}
		if ( $product->is_type( 'woosb' ) && ( $items = $product->get_items() ) ) {
			$product_id   = $product->get_id();
			$custom_price = stripslashes( $product->get_meta( 'woosb_custom_price', true ) );

			if ( ! empty( $custom_price ) ) {
				return $custom_price;
			}

			if ( ! $product->is_fixed_price() ) {
				$discount_amount = wmc_get_price( $product->get_discount_amount() );

				$discount_percentage = $product->get_discount();

				if ( $product->is_optional() ) {
					// min price
					$prices = array();

					foreach ( $items as $item ) {
						$_product = wc_get_product( $item['id'] );

						if ( $_product ) {
							$prices[] = self::get_price_to_display( $_product, 1, 'min' );
						}
					}

					if ( count( $prices ) > 0 ) {
						$min_price = min( $prices );
					} else {
						$min_price = 0;
					}

					// min whole
					$min_whole = (float) ( $product->get_meta( 'woosb_limit_whole_min', true ) ?: 1 );

					if ( $min_whole > 0 ) {
						$min_price *= $min_whole;
					}

					// min each
					$min_each = (float) ( $product->get_meta( 'woosb_limit_each_min', true ) ?: 0 );

					if ( $min_each > 0 ) {
						$min_price = 0;

						foreach ( $prices as $pr ) {
							$min_price += (float) $pr;
						}

						$min_price *= $min_each;
					}

					if ( $discount_amount ) {
						$min_price -= (float) $discount_amount;
					} elseif ( $discount_percentage ) {
						$min_price *= (float) ( 100 - $discount_percentage ) / 100;
					}

					switch ( get_option( '_woosb_price_format', 'from_min' ) ) {
						case 'min_only':
							return wc_price( $min_price ) . $product->get_price_suffix();
							break;
						case 'from_min':
							return esc_html__( 'From', 'woo-product-bundle' ) . ' ' . wc_price( $min_price ) . $product->get_price_suffix();
							break;
					}
				} elseif ( $product->has_variables() ) {
					$min_price = $max_price = 0;

					foreach ( $items as $item ) {
						$_product = wc_get_product( $item['id'] );

						if ( $_product ) {
							$min_price += self::get_price_to_display( $_product, $item['qty'], 'min' );
							$max_price += self::get_price_to_display( $_product, $item['qty'], 'max' );
						}
					}

					if ( $discount_amount ) {
						$min_price -= (float) $discount_amount;
						$max_price -= (float) $discount_amount;
					} elseif ( $discount_percentage ) {
						$min_price *= (float) ( 100 - $discount_percentage ) / 100;
						$max_price *= (float) ( 100 - $discount_percentage ) / 100;
					}

					switch ( get_option( '_woosb_price_format', 'from_min' ) ) {
						case 'min_only':
							return wc_price( $min_price ) . $product->get_price_suffix();
							break;
						case 'min_max':
							return wc_price( $min_price ) . ' - ' . wc_price( $max_price ) . $product->get_price_suffix();
							break;
						case 'from_min':
							return esc_html__( 'From', 'woo-product-bundle' ) . ' ' . wc_price( $min_price ) . $product->get_price_suffix();
							break;
					}
				} else {
					$price = $price_sale = 0;

					foreach ( $items as $item ) {
						$_product = wc_get_product( $item['id'] );

						if ( $_product ) {
							$_price = self::get_price_to_display( $_product, $item['qty'], 'min' );

							$price += $_price;

							if ( $discount_percentage ) {
								// if haven't discount_amount, apply discount percentage
								$price_sale += apply_filters( 'woosb_item_price_add_to_cart', $_price * ( 100 - $discount_percentage ) / 100, $item );
							}
						}
					}

					if ( $discount_amount ) {
						$price_sale = $price - $discount_amount;
					}

					if ( $price_sale ) {
						return wc_format_sale_price( wc_price( $price ), wc_price( $price_sale ) ) . $product->get_price_suffix();
					}

					return wc_price( $price ) . $product->get_price_suffix();
				}
			}
		}

		return $price;
	}

	public function woosb_cart_item_subtotal( $subtotal, $cart_item = null ) {
		$new_subtotal = false;

		if ( isset( $cart_item['woosb_ids'], $cart_item['woosb_price'], $cart_item['woosb_fixed_price'] ) && ! $cart_item['woosb_fixed_price'] ) {
			$new_subtotal = true;
			$subtotal     = wc_price( wmc_get_price( $cart_item['woosb_price'] ) * $cart_item['quantity'] );
		}

		if ( isset( $cart_item['woosb_parent_id'], $cart_item['woosb_price'], $cart_item['woosb_fixed_price'] ) && $cart_item['woosb_fixed_price'] ) {
			$new_subtotal = true;
			$subtotal     = wc_price( wmc_get_price( $cart_item['woosb_price'] ) * $cart_item['quantity'] );
		}

		if ( $new_subtotal && ( $cart_product = $cart_item['data'] ) ) {
			if ( $cart_product->is_taxable() ) {
				if ( WC()->cart->display_prices_including_tax() ) {
					if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
						$subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
					}
				} else {
					if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
						$subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
					}
				}
			}
		}

		return $subtotal;
	}

	public function woosb_cart_item_price( $price, $cart_item ) {
		if ( isset( $cart_item['woosb_ids'], $cart_item['woosb_price'], $cart_item['woosb_fixed_price'] ) && ! $cart_item['woosb_fixed_price'] ) {
			return wc_price( wmc_get_price( $cart_item['woosb_price'] ) );
		}

		if ( isset( $cart_item['woosb_parent_id'], $cart_item['woosb_price'], $cart_item['woosb_fixed_price'] ) && $cart_item['woosb_fixed_price'] ) {
			return wc_price( wmc_get_price( $cart_item['woosb_price'] ) );
		}

		return $price;
	}

	public function before_add_to_cart() {
		add_filter( 'woosb_get_price', array( $this, 'woosb_get_price' ), 10, 3 );
	}

	public function after_add_to_cart() {
		remove_filter( 'woosb_get_price', array( $this, 'woosb_get_price' ), 10 );
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 * @param $min_or_max
	 *
	 * @return mixed
	 */
	public function woosb_get_price( $price, $product, $min_or_max ) {
		if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
			$price = wmc_revert_price( $price );
		}

		return $price;
	}

	private static function get_price_to_display( $product, $qty, $min_or_max ) {
		return is_callable( array(
			'WPCleverWoosb_Helper',
			'woosb_get_price_to_display'
		) ) ? WPCleverWoosb_Helper::woosb_get_price_to_display( $product, $qty, $min_or_max ) : WPCleverWoosb_Helper::get_price_to_display( $product, $qty, $min_or_max );
	}
}