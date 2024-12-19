<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Visual_Product_Builder
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Visual_Product_Builder {
	protected $settings;
	protected static $vpc_options_price = array();

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			if ( class_exists( 'Vpc' ) ) {
				add_filter( 'vpc_options_price', array( $this, 'vpc_options_price' ), 10, 4 );
				villatheme_remove_object_filter( 'woocommerce_before_calculate_totals', 'VPC_Public', 'get_cart_item_price', 10 );
				add_action( 'woocommerce_before_calculate_totals', array( $this, 'get_cart_item_price' ) );
			}
		}
	}

	public function get_cart_item_price( $cart ) {
		if ( ! get_option( 'vpc-license-key' ) ) {
			return;
		}
		// This is necessary for WC 3.0+
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		// Avoiding hook repetition (when using price calculations for example)
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		global $vpc_settings;
		$hide_secondary_product_in_cart = get_proper_value( $vpc_settings, 'hide-wc-secondary-product-in-cart', 'Yes' );

		if ( is_array( $cart->cart_contents ) ) {
			foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
				if ( $cart_item['variation_id'] ) {
					$product_id = $cart_item['variation_id'];
				} else {
					$product_id = $cart_item['product_id'];
				}

				$recap = get_recap_from_cart_item( $cart_item );
				if ( isset( $cart_item['vpc-is-secondary-product'] ) && $cart_item['vpc-is-secondary-product'] && $hide_secondary_product_in_cart == 'Yes' ) {
					if ( vpc_woocommerce_version_check() ) {
						$cart_item['data']->price = 0;
					} else {
						$cart_item['data']->set_price( 0 );
					}
				}
				$product = wc_get_product( $product_id );

				if ( vpc_woocommerce_version_check() ) {
					$price = $cart_item['data']->price;
				} else {
					$price = $cart_item['data']->get_price();
				}

				if ( $price ) {
					$price = wmc_revert_price( $price );
				}

				if ( vpc_woocommerce_version_check() ) {
					$tax_status = $cart_item['data']->tax_status;
				} else {
					$tax_status = $cart_item['data']->get_tax_status();
				}

				$a_price = 0;
				if ( ! empty( $recap ) ) {
					$a_price = self::get_config_price( $product_id, $recap, $cart_item, true );
					if ( isset( $tax_status ) && $tax_status != 'taxable' ) {
						$a_price = vpc_apply_taxes_on_price_if_needed( $a_price, $cart_item['data'] );
					}
				}
				if ( class_exists( 'Ofb' ) ) {
					if ( isset( $cart_item['form_data'] ) && ! empty( $cart_item['form_data'] ) ) {
						$form_data = $cart_item['form_data'];
						if ( isset( $form_data['id_ofb'] ) ) {
							$a_price += get_form_data( $form_data['id_ofb'], $form_data, $product_id, true );
						}
					}
				}
				if ( function_exists( 'vpc_get_price_before_discount' ) ) {
					$total = wmc_get_price( vpc_get_price_before_discount( $product_id, $price ) ) + $a_price;
					$total = wmc_revert_price( $total );
				} else {
					$total = $price + $a_price;
				}

				if ( vpc_woocommerce_version_check() ) {
					$cart_item['data']->price = $total;
				} else {
					$cart_item['data']->set_price( $total );
				}
			}
		}
	}

	private static function get_config_price( $product_id, $config, $cart_item, $statut, $apply_wad_discount = true ) {
		if ( ! get_option( 'vpc-license-key' ) ) {
			return;
		}
		$original_config = get_product_config( $product_id );
		$total_price     = 0;
		$product         = wc_get_product( $product_id );
		if ( is_array( $config ) ) {
			foreach ( $config as $component => $raw_options ) {
				$options_arr = $raw_options;
				if ( ! is_array( $raw_options ) ) {
					$options_arr = array( $raw_options );
				}
				foreach ( $options_arr as $option ) {
					$linked_product = self::extract_option_field_from_config( $option, $component, $original_config->settings, 'product' );
					$option_price   = self::extract_option_field_from_config( $option, $component, $original_config->settings, 'price' );

					if ( strpos( $option_price, ',' ) ) {
						$option_price = floatval( str_replace( ',', '.', $option_price ) );
					}
					if ( $linked_product ) {
						$option_price = self::get_product_linked_price( $linked_product );
						if ( function_exists( 'vpc_get_opt_price_before_dicount_in_cart' ) ) {
							$option_price = vpc_get_opt_price_before_dicount_in_cart( $product_id, $linked_product, $option_price, $statut );
						}
					} else {
						/*only convert price if it's not linked to a product*/
						$option_price = wmc_get_price( $option_price );
					}

					// We make sure we're not handling any empty priced option
					if ( empty( $option_price ) ) {
						if ( ! $linked_product ) {
							$option_price = self::extract_option_field_from_config( $option, $component, $original_config->settings, 'price' );
							if ( $option_price !== false and $apply_wad_discount ) {
								$option_price = 0;
								if ( function_exists( 'vpc_get_wad_discount_for_opt_in_cart' ) ) {
									$option_price = vpc_get_wad_discount_for_opt_in_cart( $product_id, $option_price );
								}
							} else {
								$option_price = 0;
							}
						} else {
							$option_price = 0;
						}
					} else {
						if ( ! $linked_product && $apply_wad_discount ) {
							if ( function_exists( 'vpc_get_wad_discount_for_opt_in_cart' ) ) {
								$option_price = vpc_get_wad_discount_for_opt_in_cart( $product_id, $option_price );
							}
						}
					}
					$total_price += $option_price;
				}
			}
		}

		return apply_filters( 'vpc_config_price', $total_price, $product_id, $config, $cart_item, $statut );
	}

	private static function get_product_linked_price( $linked_product ) {
		global $vpc_settings;
		$hide_secondary_product_in_cart = get_proper_value( $vpc_settings, 'hide-wc-secondary-product-in-cart', 'Yes' );
		if ( $hide_secondary_product_in_cart == 'Yes' ) {
			$_product = wc_get_product( $linked_product );
			if ( function_exists( 'wad_get_product_price' ) ) {
				$option_price = wad_get_product_price( $_product );
			} else {
				$option_price = $_product->get_price();
				if ( strpos( $option_price, ',' ) ) {
					$option_price = floatval( str_replace( ',', '.', $option_price ) );
				}
			}
		} else {
			$option_price = 0;
		}

		return $option_price;
	}

	public static function extract_option_field_from_config( $searched_option, $searched_component, $config, $field = "icon" ) {
		$unslashed_searched_option    = vpc_remove_special_characters( $searched_option );
		$unslashed_searched_component = vpc_remove_special_characters( $searched_component );
		$field                        = apply_filters( 'extracted_option_field_from_config', $field, $config );
		if ( ! is_array( $config ) ) {
			$config = unserialize( $config );
		}
		if ( isset( $config['components'] ) ) {
			foreach ( $config['components'] as $i => $component ) {
				if ( vpc_remove_special_characters( $component['cname'], '"' ) == $unslashed_searched_component ) {
					foreach ( $component['options'] as $component_option ) {
						if ( vpc_remove_special_characters( $component_option['name'], '"' ) == $unslashed_searched_option ) {
							if ( isset( $component_option[ $field ] ) ) {
								return $component_option[ $field ];
							}
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param $price
	 * @param $option
	 * @param $component
	 * @param $vpc VPC_Default_Skin
	 *
	 * @return float|int|mixed|void
	 */
	public function vpc_options_price( $price, $option, $component, $vpc ) {
		if ( ! empty( $option['product'] ) ) {//do not convert an option if it's linked to a product
			return $price;
		}
		if ( ! isset( self::$vpc_options_price[ $option['option_id'] ] ) ) {
			self::$vpc_options_price[ $option['option_id'] ] = wmc_get_price( $price );
		}

		return self::$vpc_options_price[ $option['option_id'] ];
	}
}