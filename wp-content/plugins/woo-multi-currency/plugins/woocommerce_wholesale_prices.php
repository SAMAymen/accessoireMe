<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Wholesale_Prices
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( is_plugin_active( 'woocommerce-wholesale-prices/woocommerce-wholesale-prices.bootstrap.php' ) ) {
	class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Wholesale_Prices {
		protected $settings;

		public function __construct() {
			$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();

			add_action( 'woocommerce_product_options_pricing', array( $this, 'add_wholesale_price_fields' ), 12 );
			add_action( 'woocommerce_process_product_meta_simple', array(
				$this,
				'save_wholesale_price_fields'
			), 10, 1 );

			add_action( 'woocommerce_product_after_variable_attributes', array(
				$this,
				'add_wholesale_price_fields_variable'
			), 11, 3 );
			add_action( 'woocommerce_save_product_variation', array(
				$this,
				'save_wholesale_price_fields_variable'
			), 10, 2 );

			if ( $this->settings->get_enable() ) {
				add_filter( 'wwp_pass_wholesale_price_through_taxing', array(
					$this,
					'wwp_pass_wholesale_price_through_taxing'
				), 10, 3 );
				add_filter( 'wwp_filter_wholesale_price_cart', array(
					$this,
					'wwp_filter_wholesale_price_cart'
				), 10, 3 );
			}
		}

		public function save_wholesale_price_fields( $post_id ) {
			/*Check Permission*/
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}
			/*Check send from product edit page*/
			if ( ! isset( $_POST['_wmc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wmc_nonce'] ) ), 'wmc_save_simple_product_currency' ) ) {
				return;
			}
            $wc_product = wc_get_product( $post_id );
			if ( isset( $_POST['_wholesale_prices_wmcp'] ) ) {
				$_wholesale_prices_wmcp = wc_clean( $_POST['_wholesale_prices_wmcp'] );
				$wc_product->update_meta_data('_wholesale_prices_wmcp', wp_json_encode( $_wholesale_prices_wmcp ) );
				$wc_product->save_meta_data();
			}
		}


		public function add_wholesale_price_fields_variable( $loop, $variation_data, $variation ) {
			$currencies = $this->settings->get_list_currencies();

			$wholesale_roles_obj = new WWP_Wholesale_Roles();
			$all_wholesale_roles = $wholesale_roles_obj->getAllRegisteredWholesaleRoles();
			$variation_product = wc_get_product( $variation->ID );
			$wholesale_prices    = json_decode( $variation_product->get_meta('_wholesale_prices_wmcp', true ), true );
			if ( count( $currencies ) ) {
				foreach ( $currencies as $key => $value ) {
					if ( $this->settings->get_default_currency() == $key ) {
						continue;
					}
					?>

                    <div class="wholesale-prices-options-group options-group"
                         style="border-top: 1px solid #EEEEEE;">

                        <header>
                            <h4 style="padding-bottom: 10px;"><?php esc_html_e( 'Wholesale Prices', 'woocommerce-wholesale-prices' );
								echo esc_html( "($key)" ); ?></h4>
                        </header>

						<?php foreach ( $all_wholesale_roles as $role_key => $role ) {

							$currency_symbol = get_woocommerce_currency_symbol( $key );
							if ( array_key_exists( 'currency_symbol', $role ) && ! empty( $role['currency_symbol'] ) ) {
								$currency_symbol = $role['currency_symbol'];
							}

							$wholesale_price = isset( $wholesale_prices[ $key ][ $role_key ] ) ? $wholesale_prices[ $key ][ $role_key ] : '';
							$field_id        = "_wholesale_prices_wmcp_variable_{$loop}_{$key}_{$role_key}_";
							$field_name      = "_wholesale_prices_wmcp_variable[$loop][$key][$role_key]";
							$field_label     = $role['roleName'] . " (" . $currency_symbol . ")";
							/* translators: %1$s: user role name */
							$field_desc      = sprintf( esc_html__( 'Only applies to users with the role of %1$s', 'woocommerce-wholesale-prices' ), $role['roleName'] );

							woocommerce_wp_text_input( array(
								'id'          => $field_id,
								'name'        => $field_name,
								'class'       => $role_key . '_wholesale_price wholesale_price short',
								'label'       => $field_label,
								'placeholder' => '',
								'desc_tip'    => 'true',
								'description' => $field_desc,
								'data_type'   => 'price',
								'value'       => $wholesale_price
							) );

						} ?>

                    </div><!--.options_group-->

					<?php
				}
			}
		}

		public function save_wholesale_price_fields_variable( $variation_id, $i ) {

			/*Check Permission*/
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}
			/*Check send from product edit page*/
			if ( ! isset( $_POST['_wmc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wmc_nonce'] ) ), 'wmc_save_variable_product_currency' ) ) {
				return;
			}

			if ( isset( $_POST['_wholesale_prices_wmcp_variable'] ) ) {
				$_regular_price_wmcp = wc_clean( $_POST['_wholesale_prices_wmcp_variable'] );
				$variation_product = wc_get_product( $variation_id );
				$variation_product->update_meta_data('_wholesale_prices_wmcp', wp_json_encode( $_regular_price_wmcp[ $i ] ) );
				$variation_product->save_meta_data();
			}
		}

		public function add_wholesale_price_fields( $product_type = 'simple' ) {

			global $post;
			$product_id = $post->ID;
			$product    = wc_get_product( $product_id );
			if ( $product->get_type() == 'variable' ) {
				return;
			}
			$currencies = $this->settings->get_list_currencies();

			$wholesale_roles_obj = new WWP_Wholesale_Roles();
			$all_wholesale_roles = $wholesale_roles_obj->getAllRegisteredWholesaleRoles();
			$wholesale_prices    = json_decode( $product->get_meta('_wholesale_prices_wmcp', true ), true );
			if ( count( $currencies ) ) {
				foreach ( $currencies as $key => $value ) {
					if ( $this->settings->get_default_currency() == $key ) {
						continue;
					}
					?>

                    <div class="wholesale-prices-options-group options-group options_group"
                         style="border-top: 1px solid #EEEEEE;">

                        <header>
                            <h3 style="padding-bottom: 10px;"><?php esc_html_e( 'Wholesale Prices', 'woocommerce-wholesale-prices' );
								echo esc_html( "($key)" ); ?></h3>
                        </header>

						<?php foreach ( $all_wholesale_roles as $role_key => $role ) {

							$currency_symbol = get_woocommerce_currency_symbol( $key );
							if ( array_key_exists( 'currency_symbol', $role ) && ! empty( $role['currency_symbol'] ) ) {
								$currency_symbol = $role['currency_symbol'];
							}

							$wholesale_price = isset( $wholesale_prices[ $key ][ $role_key ] ) ? $wholesale_prices[ $key ][ $role_key ] : '';
							$field_id        = "_wholesale_prices_wmcp_{$key}_{$role_key}_";
							$field_name      = "_wholesale_prices_wmcp[$key][$role_key]";
							$field_label     = $role['roleName'] . " (" . $currency_symbol . ")";
							/* translators: %1$s: user role name */
							$field_desc      = sprintf( esc_html__( 'Only applies to users with the role of %1$s', 'woocommerce-wholesale-prices' ), $role['roleName'] );

							woocommerce_wp_text_input( array(
								'id'          => $field_id,
								'name'        => $field_name,
								'class'       => $role_key . '_wholesale_price wholesale_price short',
								'label'       => $field_label,
								'placeholder' => '',
								'desc_tip'    => 'true',
								'description' => $field_desc,
								'data_type'   => 'price',
								'value'       => $wholesale_price
							) );

						} ?>

                    </div><!--.options_group-->

					<?php
				}
			}


		}

		/**
		 * Integrate with WooCommerce Wholesales Prices
		 * @return bool
		 */
		public function wwp_pass_wholesale_price_through_taxing( $wholesale_price, $product_id, $user_wholesale_role ) {

			if ( ! $wholesale_price ) {
				return $wholesale_price;
			}
			if ( $this->settings->check_fixed_price() ) {
				$currenct_currency = $this->settings->get_current_currency();
				if ( $currenct_currency != $this->settings->get_default_currency() ) {
				    $wc_sale_product = wc_get_product( $product_id );
					$wholesale_prices = json_decode( $wc_sale_product->get_meta('_wholesale_prices_wmcp', true ), true );
					if ( is_array( $user_wholesale_role ) && count( $user_wholesale_role ) ) {
						foreach ( $user_wholesale_role as $key => $value ) {
							if ( isset( $wholesale_prices[ $currenct_currency ][ $value ] ) && $wholesale_prices[ $currenct_currency ][ $value ] > 0 ) {
								$wholesale_price = $wholesale_prices[ $currenct_currency ][ $value ];
							} else {
								$wholesale_price = wmc_get_price( $wholesale_price );
							}
						}
					}
				}

			} else {
				$wholesale_price = wmc_get_price( $wholesale_price );
			}


			return $wholesale_price;
		}

		public function wwp_filter_wholesale_price_cart( $result, $product_id, $user_wholesale_role ) {
			$wholesale_price = $result['wholesale_price'];
			if ( ! $wholesale_price ) {
				return $wholesale_price;
			}
			if ( $this->settings->check_fixed_price() ) {
				$currenct_currency = $this->settings->get_current_currency();
				if ( $currenct_currency != $this->settings->get_default_currency() ) {
					$wc_sale_product = wc_get_product( $product_id );
					$wholesale_prices = json_decode( $wc_sale_product->get_meta('_wholesale_prices_wmcp', true ), true );
					if ( is_array( $user_wholesale_role ) && count( $user_wholesale_role ) ) {
						foreach ( $user_wholesale_role as $key => $value ) {
							if ( isset( $wholesale_prices[ $currenct_currency ][ $value ] ) && $wholesale_prices[ $currenct_currency ][ $value ] > 0 ) {
								$wholesale_price = $wholesale_prices[ $currenct_currency ][ $value ] / $this->settings->get_list_currencies()[ $this->settings->get_current_currency() ]['rate'];;
							}
						}
					}
				}

			}
			$result['wholesale_price'] = $wholesale_price;

			return $result;
		}

	}
}
