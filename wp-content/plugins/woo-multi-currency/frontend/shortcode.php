<?php

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Frontend_Shortcode {
	protected $settings;
	protected $price_args;
	protected $current_url;

	public function __construct() {
		$this->settings   = WOOMULTI_CURRENCY_F_Data::get_ins();
		$this->price_args = array();
		add_action( 'init', array( $this, 'shortcode_init' ) );
		add_filter( 'wmc_shortcode', array( $this, 'replace_shortcode' ), 10, 2 );

		if ( ! isset( $_REQUEST['_woo_multi_currency_nonce'] ) || wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_shortcode' ) ) {
			$this->current_url = ! empty( $_POST['wmc_current_url'] ) ? sanitize_text_field( $_POST['wmc_current_url'] ) : remove_query_arg( 'wmc-currency' );
		}
	}

	public static function get_shortcode_id() {
		global $wmc_shortcode_id;
		if ( $wmc_shortcode_id === null ) {
			$wmc_shortcode_id = 1;
		} else {
			$wmc_shortcode_id ++;
		}

		return "woocommerce-multi-currency-{$wmc_shortcode_id}";
	}

	public function shortcode_init() {
		$items = $this->settings->get_list_shortcodes();
		foreach ( $items as $k => $item ) {
			if ( $k ) {
				add_shortcode( 'woo_multi_currency_' . $k, array( $this, 'shortcode_' . $k ) );
			}
		}
		add_shortcode( 'woo_multi_currency', array( $this, 'shortcode_woo_multi_currency' ) );
		add_shortcode( 'woo_multi_currency_exchange', array( $this, 'woo_multi_currency_exchange' ) );
		add_shortcode( 'woo_multi_currency_rates', array( $this, 'woo_multi_currency_rates' ) );
		add_shortcode( 'woo_multi_currency_product_price_switcher', array( $this, 'product_price_switcher' ) );
	}

	public function product_price_switcher( $atts ) {
		$args = shortcode_atts( array(
			'product_id' => '',
		), $atts );
		global $post;
		$product_id = ! empty( $args['product_id'] ) ? absint( $args['product_id'] ) : '';
		if ( ! $product_id ) {
			if ( is_object( $post ) && $post->ID && $post->post_type == 'product' && $post->post_status == 'publish' ) {
				$product_id = $post->ID;
			}
		}
		$price_switcher = '';
		if ( $product_id ) {
			$product          = wc_get_product( $product_id );
			$links            = $this->settings->get_links();
			$current_currency = $this->settings->get_current_currency();
			$country          = $this->settings->get_country_data( $current_currency );
			$list_currencies  = $this->settings->get_list_currencies();
			$class            = array( 'wmc-price-switcher' );
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css', [], WOOMULTI_CURRENCY_F_VERSION );
			ob_start();
			?>
            <div class="woo-multi-currency <?php echo esc_attr( implode( ' ', $class ) ) ?>"
                 id="<?php echo esc_attr( self::get_shortcode_id() ) ?>"
                 title="<?php esc_attr_e( 'Please select your currency', 'woo-multi-currency' ) ?>">
                <div class="wmc-currency-wrapper">
                        <span class="wmc-current-currency">
                          <i style="transform: scale(0.8);"
                             class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "></i>
                        </span>
                    <div class="wmc-sub-currency">
						<?php
						foreach ( $links as $k => $link ) {
							$sub_class = array( 'wmc-currency' );
							if ( $k === $current_currency ) {
								$sub_class[] = 'wmc-sub-currency-current';
							}
							$country = $this->settings->get_country_data( $k );
							?>
                            <div class="<?php echo esc_attr( implode( ' ', $sub_class ) ) ?>"
                                 data-currency="<?php echo esc_attr( $k ) ?>">
                                <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                        title="<?php echo esc_attr( $country['name'] ) ?>"
                                        href="<?php echo esc_url( $link ) ?>"
                                        class="wmc-currency-redirect" data-currency="<?php echo esc_attr( $k ) ?>">
                                    <i style="transform: scale(0.8);"
                                       class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "></i>
									<?php
									switch ( $this->settings->get_price_switcher() ) {
										case 2:
											echo '<span class="wmc-price-switcher-code">' . esc_html( $k ) . '</span>';
											break;
										case 3:
											$decimals           = (int) $list_currencies[ $k ]['decimals'];
											$decimal_separator  = wc_get_price_decimal_separator();
											$thousand_separator = wc_get_price_thousand_separator();
											$symbol             = $list_currencies[ $k ]['custom'];
											$symbol             = $symbol ? $symbol : get_woocommerce_currency_symbol( $k );
											$format             = self::get_price_format( $list_currencies[ $k ]['pos'] );
											$price              = 0;
											$max_price          = '';
											$custom_symbol      = strpos( $symbol, '#PRICE#' );
											if ( $product->get_type() === 'variable' ) {
												$price     = WOOMULTI_CURRENCY_F_Frontend_Price::get_variation_min_price( $product, $k );
												$price_max = WOOMULTI_CURRENCY_F_Frontend_Price::get_variation_max_price( $product, $k );
												if ( $price_max > $price ) {
													$price_max = number_format( wc_get_price_to_display( $product, array(
														'qty'   => 1,
														'price' => $price_max
													) ), $decimals, $decimal_separator, $thousand_separator );
													if ( $custom_symbol === false ) {
														$max_price = ' - ' . sprintf( $format, $symbol, $price_max );
													} else {
														$max_price = ' - ' . str_replace( '#PRICE#', $price_max, $symbol );
													}
												}
											} else {
												if ( $this->settings->check_fixed_price() ) {
													$product_id    = $product->get_id();
													$product_price = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_regular_price_wmcp', true ) ) );
													$sale_price    = wmc_adjust_fixed_price( self::format_json_price_meta( $product->get_meta('_sale_price_wmcp', true ) ) );
													if ( isset( $product_price[ $k ] ) && ! $product->is_on_sale( 'edit' ) && $product_price[ $k ] > 0 ) {
														$price = $product_price[ $k ];
													} elseif ( isset( $sale_price[ $k ] ) && $sale_price[ $k ] > 0 ) {
														$price = $sale_price[ $k ];
													}
												}
											}
											if ( ! $price && $product->get_price( 'edit' ) ) {
												$price = $product->get_price( 'edit' );
												$price = number_format( wmc_get_price( wc_get_price_to_display( $product, array(
													'qty'   => 1,
													'price' => $price
												) ), $k ), $decimals, $decimal_separator, $thousand_separator );
											} else {
												$price = number_format( wc_get_price_to_display( $product, array(
													'qty'   => 1,
													'price' => $price
												) ), $decimals, $decimal_separator, $thousand_separator );
											}

											if ( $custom_symbol === false ) {
												$formatted_price = sprintf( $format, $symbol, $price );
											} else {
												$formatted_price = str_replace( '#PRICE#', $price, $symbol );
											}
											echo '<span class="wmc-price-switcher-price">' . wp_kses_post( $formatted_price ) . wp_kses_post( $max_price ) . '</span>';
									}
									?>
                                </a>
                            </div>
							<?php
						}
						?>
                    </div>
                </div>
            </div>
			<?php
			$price_switcher = ob_get_clean();
		}

		return $price_switcher;
	}

	/**
	 * Shortcode Currency selector
	 */
	public function shortcode_woo_multi_currency() {
		$args = array( 'settings' => WOOMULTI_CURRENCY_F_Data::get_ins(), 'shortcode' => 'default' );
		ob_start();
		wmc_get_template( 'woo-multi-currency-selector.php', $args );

		return ob_get_clean();
	}

	/**
	 * Replace shortcode
	 *
	 * @param $shortcode
	 * @param $data
	 *
	 * @return string
	 */
	public function replace_shortcode( $shortcode, $data ) {
		$layout    = isset( $data['layout'] ) ? $data['layout'] : '';
		$flag_size = isset( $data['flag_size'] ) ? $data['flag_size'] : '';
		$attr      = '';
		if ( $flag_size ) {
			$attr = 'flag_size =1';
		}
		if ( $layout ) {
			$shortcode = '[woo_multi_currency_' . $layout . ' ' . $attr . ']';
		}

		return $shortcode;
	}

	/**
	 * Shortcode show list currency rates
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return float|int|string
	 */
	public function woo_multi_currency_rates( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'currencies' => '',
				), $atts
			)
		);
		if ( $currencies ) {
			$currencies = array_map( 'strtoupper', array_map( 'trim', array_filter( explode( ',', $currencies ) ) ) );
		} else {
			$currencies = array();
		}
		$list_currencies  = $this->settings->get_list_currencies();
		$currency_default = $this->settings->get_default_currency();
		ob_start(); ?>
        <div class="woo-multi-currency wmc-shortcode wmc-list-currency-rates">
			<?php
			if ( count( $currencies ) ) {
				foreach ( $currencies as $currency ) {
					if ( array_key_exists( $currency, $list_currencies ) ) {
						if ( $currency == $currency_default ) {
							continue;
						} ?>
                        <div class="wmc-currency-rate">
							<?php echo esc_html( $currency_default . '/' . $currency ) ?> = <?php

							echo esc_html( $list_currencies[ $currency ]['rate'] );
							?>
                        </div>
					<?php }
				}
			} else {
				foreach ( $list_currencies as $key => $currency ) {
					if ( $key == $currency_default ) {
						continue;
					} ?>
                    <div class="wmc-currency-rate">
						<?php echo esc_html( $currency_default . '/' . $key ) ?> = <?php
						echo esc_html( $currency['rate'] );
						?>
                    </div>
				<?php }
			} ?>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Shortcode exchange
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return float|int|string
	 */
	public function woo_multi_currency_exchange( $atts ) {
		global $product;
		extract(
			shortcode_atts(
				array(
					'price'          => '',
					'original_price' => '',
					'currency'       => '',
					'product_id'     => '',
					'keep_format'    => 1,
				), $atts
			)
		);
		if ( $product_id ) {
			$product_obj = wc_get_product( $product_id );
		} elseif ( ! $price ) {
			$product_obj = $product;
		}
		if ( isset( $product_obj ) && is_a( $product_obj, 'WC_Product' ) ) {
			if ( $keep_format && ! $currency || $currency === $this->settings->get_current_currency() ) {
				$price = $product_obj->get_price_html();

				return $price;
			} else {
				$price = $product_obj->get_price( 'edit' );
				if ( $product_obj->is_on_sale() ) {
					$original_price = $product_obj->get_regular_price( 'edit' );
				}
			}
		}
		if ( $price ) {
			$product_id = esc_attr( $product_id );
			$keep_format = esc_attr( $keep_format );
			$price = esc_attr( $price );
			$original_price = esc_attr( $original_price );
			$currency = esc_attr( $currency );
			$selected_currencies = $this->settings->get_list_currencies();
			if ( $currency && isset( $selected_currencies[ $currency ] ) && is_array( $selected_currencies[ $currency ] ) ) {
				$data   = $selected_currencies[ $currency ];
				$format = self::get_price_format( $data['pos'] );
				$args   = array(
					'currency'     => $currency,
					'price_format' => $format
				);
				if ( $data['decimals'] ) {
					$args['decimals'] = $data['decimals'];
				}

				if ( $original_price && $original_price > $price ) {
					$this->price_args = $args;
					add_filter( 'wc_price_args', array(
						$this,
						'change_price_format_by_specific_currency'
					), PHP_INT_MAX );
					$price_html = wc_format_sale_price( wmc_get_price( $original_price, $currency ), wmc_get_price( $price, $currency ) );
					remove_filter( 'wc_price_args', array(
						$this,
						'change_price_format_by_specific_currency'
					), PHP_INT_MAX );
					$this->price_args = array();

					return "<span class='wmc-cache-value' data-product_id='{$product_id}' data-keep_format='{$keep_format}' data-price='{$price}' data-original_price='{$original_price}' data-currency='{$currency}' >" . $price_html . '</span>';
				} else {
					return "<span class='wmc-cache-value' data-product_id='{$product_id}' data-keep_format='{$keep_format}' data-price='{$price}' data-original_price='{$original_price}' data-currency='{$currency}' >" . wc_price( wmc_get_price( $price, $currency ), $args ) . '</span>';
				}

			} else {
				if ( $original_price && $original_price > $price ) {
					return "<span class='wmc-cache-value' data-product_id='{$product_id}' data-keep_format='{$keep_format}' data-price='{$price}' data-original_price='{$original_price}' data-currency='{$currency}' >" . wc_format_sale_price( wmc_get_price( $original_price ), wmc_get_price( $price ) ) . '</span>';
				} else {
					return "<span class='wmc-cache-value' data-product_id='{$product_id}' data-keep_format='{$keep_format}' data-price='{$price}' data-original_price='{$original_price}' data-currency='{$currency}' >" . wc_price( wmc_get_price( $price ) ) . '</span>';
				}
			}
		} else {
			return '';
		}
	}

	public function change_price_format_by_specific_currency( $args ) {
		if ( count( $this->price_args ) ) {
			$args = wp_parse_args(
				$this->price_args,
				$args );
		}

		return $args;
	}

	public function shortcode_plain_vertical_2( $atts, $content = null ) {
		$args = array( 'settings' => WOOMULTI_CURRENCY_F_Data::get_ins(), 'shortcode' => 'listbox_code' );
		ob_start();
		wmc_get_template( 'woo-multi-currency-selector.php', $args );

		return ob_get_clean();
	}

	/**
	 * Shortcode plain horizontal
	 * @return string
	 */
	public function shortcode_plain_horizontal( $atts, $content = null ) {

		extract(
			shortcode_atts(
				array(
					'title' => ''
				), $atts
			)
		);
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$current_currency = $this->settings->get_current_currency();
		$links            = $this->settings->get_links();
		?>
        <div class="woo-multi-currency wmc-shortcode plain-horizontal" data-layout="plain_horizontal">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
			<?php foreach ( $links as $k => $link ) {
				if ( $current_currency ) {
					if ( $current_currency == $k ) {
						$class = "wmc-active";
					} else {
						$class = '';
					}
				}

				/*End override*/
				?>
                <div class="wmc-currency <?php echo esc_attr( $class ) ?>">

                    <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                            href="<?php echo $class ? '#' : esc_url( $link ) ?>">

						<?php echo esc_html( $k ) ?>
                    </a>
                </div>
			<?php } ?>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Plain vertical
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function shortcode_plain_vertical( $atts, $content = null ) {

		extract(
			shortcode_atts(
				array(
					'title' => '',
				), $atts
			)
		);
		$links            = $this->settings->get_links();
		$current_currency = $this->settings->get_current_currency();
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		?>
        <div class="woo-multi-currency wmc-shortcode plain-vertical" data-layout="plain_vertical">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper" onclick="">
				<span class="wmc-current-currency">
					<?php echo esc_html( $current_currency ) ?>
                    <span class="wmc-current-currency-arrow"></span>
				</span>
                <div class="wmc-sub-currency">
					<?php foreach ( $links as $k => $link ) {
						if ( $current_currency == $k ) {
							continue;
						}
						?>
                        <div class="wmc-currency">

                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    href="<?php echo esc_url( $link ) ?>">

								<?php echo esc_html( $k ) ?></a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * List Flag Horizontal
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function shortcode_layout3( $atts, $content = null ) {
		$this->enqueue_flag_css();
		extract(
			shortcode_atts(
				array(
					'title'     => '',
					'flag_size' => 0.5
				), $atts
			)
		);

		$current_currency = $this->settings->get_current_currency();
		$links            = $this->settings->get_links();
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$class = '';
		?>
        <div class="woo-multi-currency wmc-shortcode plain-horizontal layout3 <?php echo esc_attr( $class ) ?>"
             data-layout="layout3">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
			<?php foreach ( $links as $k => $link ) {
				if ( $current_currency ) {
					if ( $current_currency == $k ) {
						$class = "wmc-active";
					} else {
						$class = '';
					}
				}
				/*End override*/
				$country = $this->settings->get_country_data( $k );
				?>
                <div class="wmc-currency <?php echo esc_attr( $class ) ?>">

                    <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                            title="<?php echo esc_attr( $country['name'] ) ?>"
                            href="<?php echo $class ? '#' : esc_url( $link ) ?>">
                        <i style="<?php echo esc_attr( $this->fix_style( $flag_size ) ) ?>"
                           class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "></i>
                    </a>
                </div>
			<?php } ?>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	public function enqueue_flag_css() {
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.css', [], WOOMULTI_CURRENCY_F_VERSION );
		} else {
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css', [], WOOMULTI_CURRENCY_F_VERSION );
		}
	}

	public function fix_style( $flag_size ) {
		$margin_width = ( 60 - 60 * $flag_size ) / 2;
		$margin_heigh = ( 40 - 40 * $flag_size ) / 2;
		$style        = "transform: scale({$flag_size}); margin: -{$margin_heigh}px -{$margin_width}px";

		return $style;
	}

	/**
	 * List Flags vertical
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function shortcode_layout4( $atts, $content = null ) {
		$this->enqueue_flag_css();
		extract(
			shortcode_atts(
				array(
					'title'     => '',
					'flag_size' => 0.5
				), $atts
			)
		);
		$links            = $this->settings->get_links();
		$current_currency = $this->settings->get_current_currency();
		$country          = $this->settings->get_country_data( $current_currency );
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$class = '';
		?>
        <div class="woo-multi-currency wmc-shortcode plain-vertical layout4 <?php echo esc_attr( $class ) ?>"
             data-layout="layout4" data-flag_size="<?php echo esc_attr( $flag_size ) ?>">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper" onclick="">
				<span class="wmc-current-currency">
				  <i style="<?php echo esc_attr( $this->fix_style( $flag_size ) ) ?>"
                     data-flag_size="<?php echo esc_attr( $flag_size ) ?>"
                     class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "> </i>
                    <span class="wmc-current-currency-arrow"></span>
				</span>
                <div class="wmc-sub-currency">
					<?php foreach ( $links as $k => $link ) {
						if ( $current_currency == $k ) {
							continue;
						}
						/*End override*/
						$country = $this->settings->get_country_data( $k );
						?>
                        <div class="wmc-currency">

                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    title="<?php echo esc_attr( $country['name'] ) ?>"
                                    href="<?php echo esc_url( $link ) ?>">

                                <i style="<?php echo esc_attr( $this->fix_style( $flag_size ) ) ?>"
                                   alt="<?php echo esc_attr( $country['name'] ) ?>"
                                   class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "> </i>
                            </a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * List Flags + Currency code
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function shortcode_layout5( $atts, $content = null ) {
		$this->enqueue_flag_css();
		extract(
			shortcode_atts(
				array(
					'title'     => '',
					'flag_size' => 0.5
				), $atts
			)
		);
		$links            = $this->settings->get_links();
		$current_currency = $this->settings->get_current_currency();
		$country          = $this->settings->get_country_data( $current_currency );
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$class = '';
		?>
        <div class="woo-multi-currency wmc-shortcode plain-vertical layout5 <?php echo esc_attr( $class ) ?>"
             data-layout="layout5">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper" onclick="">
				<span class="wmc-current-currency" style="line-height: <?php echo esc_attr( $flag_size * 40 ) ?>px">
                       <i style="<?php echo esc_attr( $this->fix_style( $flag_size ) ) ?>"
                          class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "> </i>
                      <span>
                        <?php echo esc_html( $current_currency ) ?>
                    </span>
                    <span class="wmc-current-currency-arrow"></span>
				</span>
                <div class="wmc-sub-currency">
					<?php foreach ( $links as $k => $link ) {
						if ( $current_currency == $k ) {
							continue;
						}

						/*End override*/
						$country = $this->settings->get_country_data( $k );

						?>
                        <div class="wmc-currency">

                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    title="<?php echo esc_attr( $country['name'] ) ?>"
                                    href="<?php echo esc_url( $link ) ?>">
                                <i style="<?php echo esc_attr( $this->fix_style( $flag_size ) ) ?>"
                                   class="vi-flag-64 flag-<?php echo esc_attr( strtolower( $country['code'] ) ) ?> "> </i>
                                <span>
									<?php echo esc_html( $k ) ?>
								</span>
                            </a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Shortcode pain horizontal currencies
	 * @return string
	 */
	public function shortcode_layout6( $atts, $content = null ) {

		extract(
			shortcode_atts(
				array(
					'title' => '',
				), $atts
			)
		);
		$links            = $this->settings->get_links();
		$current_currency = $this->settings->get_current_currency();
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		?>
        <div class="woo-multi-currency wmc-shortcode plain-horizontal layout6" data-layout="layout6">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
			<?php
			foreach ( $links as $k => $link ) {
				if ( $current_currency ) {
					if ( $current_currency == $k ) {
						$class = "wmc-active";
					} else {
						$class = '';
					}
				}
				?>
                <div class="wmc-currency <?php echo esc_attr( $class ) ?>">

                    <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                            href="<?php echo $class ? '#' : esc_url( $link ) ?>">

						<?php echo wp_kses_post( get_woocommerce_currency_symbol( $k ) ) ?></a>
                </div>
			<?php } ?>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Pain vertical currency symbols
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function shortcode_layout7( $atts, $content = null ) {

		extract(
			shortcode_atts(
				array(
					'title' => '',
				), $atts
			)
		);
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$current_currency = $this->settings->get_current_currency();
		$symbol           = get_woocommerce_currency_symbol( $current_currency );
		$links            = $this->settings->get_links();
		?>
        <div class="woo-multi-currency wmc-shortcode plain-vertical vertical-currency-symbols" data-layout="layout7">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper" onclick="">
				<span class="wmc-current-currency">
					<?php echo wp_kses_post( $symbol ) ?>
                    <span class="wmc-current-currency-arrow"></span>
				</span>
                <div class="wmc-sub-currency">
					<?php foreach ( $links as $k => $link ) {

						if ( $current_currency == $k ) {
							continue;
						}
						?>
                        <div class="wmc-currency">

                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    href="<?php echo esc_url( $link ) ?>">

								<?php echo esc_attr( get_woocommerce_currency_symbol( $k ) ); ?></a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php

		$html = ob_get_clean();

		return $html;
	}

	public function shortcode_layout8( $atts, $content = null ) {
		ob_start();
		$current_currency = $this->settings->get_current_currency();
		$symbol           = get_woocommerce_currency_symbol( $current_currency );
		$links            = $this->settings->get_links();
		$fix_class        = ctype_alpha( substr( $symbol, 0, 2 ) ) && strlen( $symbol ) >= 3 ? 'wmc-fix-font' : '';
		?>
        <div class="woo-multi-currency wmc-shortcode vertical-currency-symbols-circle" data-layout="layout8">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper" onclick="">
				<span class="wmc-current-currency <?php echo esc_attr( $fix_class ) ?>">
					<?php echo wp_kses_post( $symbol ) ?>
				</span>

                <div class="wmc-sub-currency">
					<?php foreach ( $links as $k => $link ) {
						if ( $current_currency == $k ) {
							continue;
						}
						?>
                        <div class="wmc-currency">
							<?php
							$symbol    = get_woocommerce_currency_symbol( $k );
							$fix_class = ctype_alpha( substr( $symbol, 0, 2 ) ) && strlen( $symbol ) >= 3 ? 'wmc-fix-font' : '';
							?>
                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    class="<?php echo esc_attr( $fix_class ) ?>"
                                    href="<?php echo esc_url( $link ) ?>"><?php echo wp_kses_post( $symbol ) ?></a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	public function shortcode_layout9( $atts, $content = null ) {
		$current_currency     = $this->settings->get_current_currency();
		$links                = $this->settings->get_links();
		$current_currency_pos = array_search( $current_currency, array_keys( $links ), true );
		$left_arr             = array_slice( $links, 0, $current_currency_pos );
		$right_arr            = array_slice( $links, $current_currency_pos );
		ob_start();
		?>
        <div class="woo-multi-currency wmc-shortcode layout9 " data-layout="layout9">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_url( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper">
				<?php
				if ( is_array( $left_arr ) && count( $left_arr ) ) {
					$i = 0;
					foreach ( $left_arr as $code => $link ) {
						$symbol = get_woocommerce_currency_symbol( $code );
						?>
                        <div class="wmc-currency wmc-left" style="z-index: <?php echo esc_attr( $i ++ ) ?>">
                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    href="<?php echo esc_url( $link ) ?>"><?php echo wp_kses_post( $symbol ) ?></a>
                        </div>
						<?php
					}
				}

				if ( is_array( $right_arr ) && $i = count( $right_arr ) ) {
					foreach ( $right_arr as $code => $link ) {
						$active           = $current_currency == $code ? 'wmc-active' : '';
						$z_index          = $current_currency == $code ? 999 : $i --;
						$align            = $current_currency == $code ? 'wmc-current-currency' : 'wmc-right';
						$symbol           = get_woocommerce_currency_symbol( $code );
						$current_currency = $current_currency == $code && $current_currency != $symbol ? $current_currency : '';
						?>
                        <div class="wmc-currency <?php echo esc_attr( $align ) . ' ' . esc_attr( $active ) ?>"
                             style="z-index: <?php echo esc_attr( $z_index ) ?>">
                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    href="<?php echo esc_url( $link ) ?>"><?php echo wp_kses_post( "{$current_currency} {$symbol}" ) ?></a>
                        </div>
						<?php
					}
				}
				?>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	public function shortcode_layout10( $atts, $content = null ) {
		$this->enqueue_flag_css();

		extract(
			shortcode_atts(
				array(
					'title'     => '',
					'flag_size' => 0.4,
					'symbol'    => '',
				), $atts
			)
		);

		$links            = $this->settings->get_links();
		$current_currency = $this->settings->get_current_currency();
		ob_start();
		if ( $title ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}
		$data_flag_size = $flag_size;
		$line_height    = ( $flag_size * 40 ) . 'px';

		$countries = get_woocommerce_currencies();
		$flag_size = $this->fix_style( $flag_size );
		?>
        <div id="<?php echo esc_attr( self::get_shortcode_id() ) ?>"
             class="woo-multi-currency wmc-shortcode plain-vertical layout10"
             data-layout="layout10" data-flag_size="<?php echo esc_attr( $data_flag_size ) ?>">
            <input type="hidden" class="wmc-current-url" value="<?php echo esc_attr( $this->current_url ) ?>">
            <div class="wmc-currency-wrapper">
				<span class="wmc-current-currency" style="line-height: <?php echo esc_attr( $line_height ) ?>">
                    <?php
                    $country_data = $this->settings->get_country_data( $current_currency );
                    $country_code = strtolower( $country_data['code'] );
                    $symbol       = get_woocommerce_currency_symbol( $current_currency );
                    ?>
                    <span>
                        <i style="<?php echo esc_attr( $flag_size ) ?>"
                           class="vi-flag-64 flag-<?php echo esc_attr( $country_code ) ?>"> </i>
                        <span class="wmc-text wmc-text-<?php echo esc_attr( $current_currency ) ?>"><span
                                    class="wmc-text-currency-text">(<?php echo esc_html( $current_currency ) ?>) </span><?php echo wp_kses_post( $symbol ) ?></span>
                    </span>
                    <span class="wmc-current-currency-arrow"></span>
                </span>
                <div class="wmc-sub-currency">
					<?php
					foreach ( $links as $k => $link ) {
						$sub_class = array( 'wmc-currency' );
						if ( $current_currency == $k ) {
							continue;
						}
						$country = $this->settings->get_country_data( $k );
						?>
                        <div class="<?php echo esc_attr( implode( ' ', $sub_class ) ) ?>"
                             data-currency="<?php echo esc_attr( $k ) ?>">
							<?php
							$html   = '';
							$symbol = get_woocommerce_currency_symbol( $k );
							$html   .= sprintf( "<a rel='nofollow' class='wmc-currency-redirect' href='%1s' style='line-height:%2s' data-currency='%3s' data-currency_symbol='%4s'>", esc_url( $link ), esc_attr( $line_height ), esc_attr( $k ), esc_attr( $symbol ) );
							$html   .= sprintf( "<i style='%1s' class='vi-flag-64 flag-%2s'></i>", esc_attr( $flag_size ), esc_attr( strtolower( $country['code'] ) ) );
							$html   .= sprintf( "<span class='wmc-sub-currency-name'>%1s</span>", esc_html( $countries[ $k ] ) );
							$html   .= sprintf( "<span class='wmc-sub-currency-symbol'>(%1s)</span>", esc_html( $symbol ) );
							$html   .= '</a>';
							echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( $html );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	private static function get_price_format( $pos ) {
		switch ( $pos ) {
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
			default:
				$format = '%2$s&nbsp;%1$s';
				break;
		}

		return $format;
	}

	function format_json_price_meta( $price_meta ) {

		return is_string( $price_meta ) ? json_decode( $price_meta, true ) : $price_meta;
	}

}