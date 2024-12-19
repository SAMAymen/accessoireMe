<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Woo_Discount_Rules
 */
class WOOMULTI_CURRENCY_F_Plugin_Woo_Discount_Rules {
	protected $settings;
	protected $convert;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		$this->convert  = false;
		if ( $this->settings->get_enable() && is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) ) {
			add_action( 'admin_notices', array(
				$this,
				'admin_notices'
			) );
			add_filter( 'woo_discount_rules_on_display_discount_priced_in_cart_item_subtotal', array(
				$this,
				'woo_discount_rules_on_display_discount_priced_in_cart_item_subtotal'
			), 10, 4 );
			add_filter( 'advanced_woo_discount_rules_discounted_price_of_cart_item', array(
				$this,
				'advanced_woo_discount_rules_discounted_price_of_cart_item'
			), 10, 4 );
			add_filter( 'advanced_woo_discount_rules_converted_currency_value', array(
				$this,
				'advanced_woo_discount_rules_converted_currency_value'
			) );
			/*If using this filter, cart discount - fixed discount works properly but percentage discount not, and vice versa*/
//			add_filter( 'advanced_woo_discount_rules_additional_fee_amount', array(
//				$this,
//				'advanced_woo_discount_rules_additional_fee_amount'
//			) ,10,2);
			add_filter( 'advanced_woo_discount_rules_get_regular_price', array(
				$this,
				'advanced_woo_discount_rules_get_regular_price'
			), 10, 2 );
			add_filter( 'advanced_woo_discount_rules_bulk_table_ranges', array(
				$this,
				'convert_fixed_discount_table'
			), 10, 3 );
		}
	}

	public function convert_fixed_discount_table( $response_ranges, $rules, $product ) {
		if ( count( $response_ranges ) && ! empty( $rules ) && ! empty( $product ) ) {
			foreach ( $response_ranges as &$range ) {
				if ( isset( $range['discount_type'] ) ) {
					if ( $range['discount_type'] === 'flat' || $range['discount_type'] === 'fixed_price' ) {
						$range['discount_value'] = wmc_get_price( $range['discount_value'] );
					}
				}
			}
		}

		return $response_ranges;
	}

	public function advanced_woo_discount_rules_get_regular_price( $price, $product ) {
		if ( $product && $this->settings->check_fixed_price() ) {
			$current_currency = $this->settings->get_current_currency();
			if ( $current_currency !== $this->settings->get_default_currency() ) {
				$fixed_price = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_regular_price_wmcp', true ), true ) );
				if ( isset( $fixed_price[ $current_currency ] ) && $fixed_price[ $current_currency ] > 0 ) {
					$price = $fixed_price[ $current_currency ];
				}
			}
		}

		return $price;
	}

	public function admin_notices() {
		if ( class_exists( 'Wdr\App\Controllers\Configuration' ) ) {
			$advanced_config = new Wdr\App\Controllers\Configuration();
			if ( $advanced_config->getConfig( 'wdr_override_custom_price', 0 ) ) {
				?>
                <div class="notice notice-error">
                    <p>
						<?php /* translators: %s: Woo Discount Rule settings link */
                        printf( WOOMULTI_CURRENCY_F_Data::wp_kses_post( esc_html__( '<strong>Multi Currency for WooCommerce and Woo Discount Rules compatibility issue</strong>: To make the two plugins work properly, please go to <a href="%s">Woo Discount Rules/Settings/Third party plugin conflict fixes & options</a> and check the "No" checkbox next to "Do you have custom prices set using another plugin or custom code?"', 'woo-multi-currency' ) ), esc_url( admin_url( 'admin.php?page=woo_discount_rules&tab=settings#wdr_override_custom_price_1' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </p>
                </div>
				<?php
			}
		}
	}

	protected function getYouSavedContent( $total_discounted_price ) {
		$subtotal_additional_text = '<span class="wdr_you_saved_con">';
		$config                   = new FlycartWooDiscountBase();
		$display_you_saved_string = $config->getConfigData( 'display_you_saved_text_value', " You saved: {{total_discount_price}}" );
		$display_you_saved_string = str_replace( '{{total_discount_price}}', '%s', $display_you_saved_string );// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		$subtotal_additional_text .= sprintf( esc_html__( $display_you_saved_string, 'woo-discount-rules' ), $total_discounted_price );// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		$subtotal_additional_text .= '</span>';

		return $subtotal_additional_text;
	}

	public function advanced_woo_discount_rules_additional_fee_amount( $price, $cart ) {
		if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
			$price = wmc_get_price( $price );
		}

		return $price;
	}

	public function advanced_woo_discount_rules_converted_currency_value( $price ) {
		if ( is_numeric( $price ) && ! empty( $price ) ) {
			if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
				$price = wmc_get_price( $price );
			}
		}

		return $price;
	}

	public function advanced_woo_discount_rules_discounted_price_of_cart_item( $price, $cart_item, $cart_object, $discount_prices ) {
		$awdr_compatibility = get_option( 'awdr_compatibility' );
		if ( isset( $awdr_compatibility['compatible_cs_villatheme'] ) && $awdr_compatibility['compatible_cs_villatheme'] ) {
			return $price;
		}
		if ( ! empty( $discount_prices ) && isset( $discount_prices['discounted_price'] ) ) {
			if ( $this->settings->get_current_currency() !== $this->settings->get_default_currency() ) {
				$price = wmc_revert_price( $price );
			}
		}

		return $price;
	}

	public function woo_discount_rules_on_display_discount_priced_in_cart_item_subtotal( $subtotal_additional_text, $woo_discount, $cart_item, $subtotal ) {
		if ( ! empty( $woo_discount['discount_amount_total'] ) ) {
			$total_discounted_price   = FlycartWoocommerceProduct::wc_price( wmc_get_price( $woo_discount['discount_amount_total'] ) );
			$subtotal_additional_text = $this->getYouSavedContent( $total_discounted_price );
			$subtotal                 = $subtotal_additional_text;
		}

		return $subtotal;
	}
}