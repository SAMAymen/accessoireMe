<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Yith_Product_Bundles
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Yith_Product_Bundles {
	protected $settings;
	protected $decimal_separator;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'woocommerce_process_product_meta_yith_bundle', array( $this, 'product_price_fields' ) );
		}
	}

	/**
	 * Integrate with Yith Product Bundles
	 * @return bool
	 */
	public function product_price_fields() {
		global $post;
		$currencies    = $this->settings->get_currencies();
		$yi_product    = wc_get_product( $post->ID );
		$regular_price = $this->adjust_fixed_price( json_decode( $yi_product->get_meta('_regular_price_wmcp', true ), true ) );
		$sale_price    = $this->adjust_fixed_price( json_decode( $yi_product->get_meta('_sale_price_wmcp', true ), true ) );
		foreach ( $currencies as $currency ) {
			if ( $currency != $this->settings->get_default_currency() ) {
				?>
                <div style="border-left: 5px solid #f78080;">
                    <p class="form-field ">
                        <label for="_regular_price_wmcp_<?php echo esc_attr( $currency ); ?>"><?php echo esc_html__( 'Regular Price', 'woo-multi-currency' ) . ' (' . esc_html( $currency ) . ')'; ?></label>
                        <input id="_regular_price_wmcp_<?php echo esc_attr( $currency ); ?>"
                               class="short wc_input_price" type="text"
                               value="<?php echo isset( $regular_price[ $currency ] ) ? esc_attr( $regular_price[ $currency ] ) : ''; ?>"
                               name="_regular_price_wmcp[<?php echo esc_attr( $currency ); ?>]">
                    </p>
                    <p class="form-field ">
                        <label for="_sale_price_wmcp_<?php echo esc_attr( $currency ); ?>"><?php echo esc_html__( 'Sale Price', 'woo-multi-currency' ) . ' (' . esc_html( $currency ) . ')'; ?></label>
                        <input id="_sale_price_wmcp_<?php echo esc_attr( $currency ); ?>" class="short wc_input_price"
                               type="text"
                               value="<?php echo isset( $sale_price[ $currency ] ) ? esc_attr( $sale_price[ $currency ] ) : ''; ?>"
                               name="_sale_price_wmcp[<?php echo esc_attr( $currency ); ?>]">
                    </p>
                </div>
				<?php
			}
		}
		wp_nonce_field( 'wmc_save_simple_product_currency', '_wmc_nonce' );
	}

	private function adjust_fixed_price( $fixed_price ) {
		if ( ! $this->decimal_separator ) {
			$this->decimal_separator = stripslashes( get_option( 'woocommerce_price_decimal_sep', '.' ) );
		}
		if ( $this->decimal_separator !== '.' && is_array( $fixed_price ) && count( $fixed_price ) ) {
			foreach ( $fixed_price as $key => $value ) {
				$fixed_price[ $key ] = str_replace( '.', $this->decimal_separator, $value );
			}
		}

		return $fixed_price;
	}
}