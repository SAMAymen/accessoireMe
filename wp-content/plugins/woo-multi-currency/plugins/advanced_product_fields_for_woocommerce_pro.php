<?php

use SW_WAPF_PRO\Includes\Classes\Cart;

class WOOMULTI_CURRENCY_F_Plugin_Advanced_Product_Fields_For_Woocommerce_Pro {
	protected $settings;

	public function __construct() {
		if ( is_plugin_active( 'advanced-product-fields-for-woocommerce-pro/advanced-product-fields-for-woocommerce-pro.php' ) ) {
			$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
			add_action( 'woocommerce_before_calculate_totals', array( $this, 'recalculate_pricing' ), 9 );
			add_filter( 'wapf/pricing/addon', array( $this, 'convert_product_price' ), 10, 2 );
//
//			add_filter( 'wapf/html/pricing_hint/amount', array( $this, 'convert_pricing_hint' ), 10, 3 );
//
//			add_filter( 'wapf/html/product_totals/data', array( $this, 'add_raw_price' ), 10, 2 );
//
			add_filter( 'wapf/pricing/cart_item_base', array( $this, 'convert_back' ), 10, 3 );
//
//			add_action( 'wp_footer', array( $this, 'add_footer_script' ), 100 );
//
//			add_filter( 'wapf/pricing/mini_cart_item_price', array( $this, 'change_mini_cart_item_price' ), 10, 3 );
		}
	}

	public function change_mini_cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( ! empty( $cart_item['wapf_item_price'] ) && wp_doing_ajax() ) {
			if ( ! $this->is_default_currency() ) {
				$price = wmc_get_price( $price );
			}
		}

		return $price;
	}

	public function convert_back( $price ) {
		if ( ! $this->is_default_currency() ) {
			return wmc_revert_price( $price );
		}

		return $price;
	}

	public function add_raw_price( $data, $product ) {
		if ( $this->is_default_currency() ) {
			$data['wmc-price'] = $data['product-price'];
		} else {
			$data['wmc-price'] = wmc_revert_price( $data['product-price'] );
		}

		return $data;
	}

	public function add_footer_script() {
		if ( $this->is_default_currency() ) {
			return;
		}
		?>
        <script>
            var wapf_wmc_rate = <?php echo esc_html( $this->get_current_currency_rate() ); ?>;

            jQuery(document).on('wapf/pricing', function (e, productTotal, optionsTotal, total, $parent) {
                var rawBase = jQuery('.wapf-product-totals').data('wmc-price') * WAPF.Util.selectedQuantity($parent);
                jQuery('.wapf-product-total').html(WAPF.Util.formatMoney(rawBase * wapf_wmc_rate, window.wapf_config.display_options));
                jQuery('.wapf-options-total').html(WAPF.Util.formatMoney(optionsTotal * wapf_wmc_rate, window.wapf_config.display_options));
                jQuery('.wapf-grand-total').html(WAPF.Util.formatMoney((optionsTotal + rawBase) * wapf_wmc_rate, window.wapf_config.display_options));
            });

            WAPF.Filter.add('wapf/fx/hint', function (price) {
                return price * wapf_wmc_rate;
            });
        </script>
		<?php
	}

	public function convert_pricing_hint( $amount, $product, $type ) {
		if ( ! $this->is_default_currency() && ! $this->product_has_fixed_price( $product ) ) {
			return wmc_get_price( $amount );
		}

		return $amount;
	}

	public function convert_product_price( $price, $product ) {

		if ( ! $this->is_default_currency() ) {
			return wmc_get_price( $price );
		}

		return $price;
	}


	public function convert_addon_price( $amount, $product, $type, $for_page ) {

		return $this->convert_back( $amount );
	}

	public function recalculate_pricing( $cart_obj ) {
		foreach ( $cart_obj->get_cart() as $key => $item ) {
			$cart_item = WC()->cart->cart_contents[ $key ];
			if ( empty( $cart_item['wapf'] ) ) {
				continue;
			}

			$pricing = Cart::calculate_cart_item_options_total( $cart_item );

			if ( $pricing !== false ) {
				$pricing['base']                                     = wmc_revert_price( $pricing['base'] );
				WC()->cart->cart_contents[ $key ]['wapf_item_price'] = $pricing;
			}
		}
	}


	private function is_default_currency() {
		$current_currency = $this->settings->get_current_currency();
		$default_currency = $this->settings->get_default_currency();

		return $current_currency === $default_currency;
	}

	private function product_has_fixed_price( $product ) {
		if ( ! $this->settings->check_fixed_price() ) {
			return false;
		}
		$current_currency = $this->settings->get_current_currency();
		$product_price    = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_regular_price_wmcp', true ), true ) );
		$sale_price       = wmc_adjust_fixed_price( json_decode( $product->get_meta( '_sale_price_wmcp', true ), true ) );
		if ( isset( $product_price[ $current_currency ] ) && ! WOOMULTI_CURRENCY_F_Frontend_Price::is_on_sale( $product ) ) {
			if ( $product_price[ $current_currency ] > 0 ) {
				return true;
			}
		} elseif ( isset( $sale_price[ $current_currency ] ) ) {
			if ( $sale_price[ $current_currency ] > 0 ) {
				return true;
			}
		}

		return false;
	}

	private function get_current_currency_rate() {
		$wmc_currencies   = $this->settings->get_currencies();
		$current_currency = $this->settings->get_current_currency();

		return floatval( $wmc_currencies[ $current_currency ]['rate'] );
	}
}