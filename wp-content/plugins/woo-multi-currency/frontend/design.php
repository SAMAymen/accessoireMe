<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Design
 */
class WOOMULTI_CURRENCY_F_Frontend_Design {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		add_action( 'wp_footer', array( $this, 'show_action' ) );
		if ( $this->settings->get_enable() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_script' ) );
			add_filter( 'body_class', array( $this, 'body_class' ) );
		}
	}

	public function body_class( $classes ) {
		if ( is_array( $classes ) ) {
			$classes[] = 'woocommerce-multi-currency-' . $this->settings->get_current_currency();
		}

		return $classes;
	}

	/**
	 * Public
	 */
	public function front_end_script() {
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'woo-multi-currency', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css', [], WOOMULTI_CURRENCY_F_VERSION );
			if ( is_rtl() ) {
				wp_enqueue_style( 'woo-multi-currency-rtl', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency-rtl.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			}
		} else {
			wp_enqueue_style( 'woo-multi-currency', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency.min.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css', [], WOOMULTI_CURRENCY_F_VERSION );
			if ( is_rtl() ) {
				wp_enqueue_style( 'woo-multi-currency-rtl', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency-rtl.min.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			}
		}

		/*Custom CSS*/
		$text_color                = $this->settings->get_text_color();
		$background_color          = $this->settings->get_background_color();
		$main_color                = $this->settings->get_main_color();
		$shortcode_bg_color        = $this->settings->get_param( 'shortcode_bg_color' );
		$shortcode_color           = $this->settings->get_param( 'shortcode_color' );
		$shortcode_active_bg_color = $this->settings->get_param( 'shortcode_active_bg_color' );
		$shortcode_active_color    = $this->settings->get_param( 'shortcode_active_color' );
		$links                     = $this->settings->get_links();
		$currency_qty              = count( $links ) - 1;

		$custom = '.woo-multi-currency .wmc-list-currencies .wmc-currency.wmc-active,.woo-multi-currency .wmc-list-currencies .wmc-currency:hover {background: ' . $main_color . ' !important;}
		.woo-multi-currency .wmc-list-currencies .wmc-currency,.woo-multi-currency .wmc-title, .woo-multi-currency.wmc-price-switcher a {background: ' . $background_color . ' !important;}
		.woo-multi-currency .wmc-title, .woo-multi-currency .wmc-list-currencies .wmc-currency span,.woo-multi-currency .wmc-list-currencies .wmc-currency a,.woo-multi-currency.wmc-price-switcher a {color: ' . $text_color . ' !important;}';

		$custom .= ".woo-multi-currency.wmc-shortcode .wmc-currency{background-color:{$shortcode_bg_color};color:{$shortcode_color}}";
		$custom .= ".woo-multi-currency.wmc-shortcode .wmc-currency.wmc-active,.woo-multi-currency.wmc-shortcode .wmc-current-currency{background-color:{$shortcode_active_bg_color};color:{$shortcode_active_color}}";
		$custom .= ".woo-multi-currency.wmc-shortcode.vertical-currency-symbols-circle:not(.wmc-currency-trigger-click) .wmc-currency-wrapper:hover .wmc-sub-currency,.woo-multi-currency.wmc-shortcode.vertical-currency-symbols-circle.wmc-currency-trigger-click .wmc-sub-currency{animation: height_slide {$currency_qty}00ms;}";
		$custom .= "@keyframes height_slide {0% {height: 0;} 100% {height: {$currency_qty}00%;} }";

		$custom .= $this->settings->get_custom_css();
		wp_add_inline_style( 'woo-multi-currency', $custom );
		/*Multi currency JS*/
		if ( WP_DEBUG ) {
			wp_enqueue_script( 'woo-multi-currency', WOOMULTI_CURRENCY_F_JS . 'woo-multi-currency.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION, false );
		} else {
			wp_enqueue_script( 'woo-multi-currency', WOOMULTI_CURRENCY_F_JS . 'woo-multi-currency.min.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION, false );
		}

		wp_localize_script( 'woo-multi-currency', 'wooMultiCurrencyParams', array(
			'enableCacheCompatible' => apply_filters( 'wmc_enable_cache_compatible_frontend', $this->settings->get_param( 'cache_compatible' ) ),
			'ajaxUrl'               => admin_url( 'admin-ajax.php' ),
			'extra_params'          => apply_filters( 'wmc_frontend_extra_params', array() ),
			'current_currency'      => $this->settings->get_current_currency(),
		) );
	}

	/**
	 * Show Currency converter
	 */
	public function show_action() {
		if ( ! $this->enable() ) {
			return;
		}
		$logic_value = $this->settings->get_conditional_tags();
		if ( $logic_value ) {
			if ( stristr( $logic_value, "return" ) === false ) {
				$logic_value = "return (" . $logic_value . ");";
			}
			try {
				if ( ! eval( $logic_value ) ) {// phpcs:ignore Generic.PHP.ForbiddenFunctions.Found
					return;
				}
			} catch ( Error $e ) {
				trigger_error( esc_html( $e->getMessage() ), E_USER_WARNING );

				return;
			} catch ( Exception $e ) {
				trigger_error( esc_html( $e->getMessage() ), E_USER_WARNING );

				return;
			}
		}
		$enable_checkout = $this->settings->get_enable_multi_payment();
		if ( ! $enable_checkout && is_checkout() ) {
			return;
		}
		$currency_selected = $this->settings->get_current_currency();
		$title             = $this->settings->get_design_title();
		$enable_collapse   = $this->settings->enable_collapse();
		$class             = array();
		/*Position left or right*/
		if ( ! $this->settings->get_design_position() ) {
			$class[] = 'wmc-left';
		} else {
			$class[] = 'wmc-right';
		}
		$class[] = 'style-1';
		switch ( $this->settings->get_sidebar_style() ) {
			case 1:
				$class[] = 'wmc-currency-symbol';
				break;
			case 2:
				$class[] = 'wmc-currency-flag';
				break;
			case 3:
				$class[] = 'wmc-currency-flag wmc-currency-code';
				break;
			case 4:
				$class[] = 'wmc-currency-flag wmc-currency-symbol';
				break;
		}
		if ( $enable_collapse ) {
			$class[] = 'wmc-collapse';
		}
		?>
        <div class="woo-multi-currency <?php echo esc_attr( implode( ' ', $class ) ); ?> wmc-bottom wmc-sidebar">
            <div class="wmc-list-currencies">
				<?php if ( $title ) { ?>
                    <div class="wmc-title">
						<?php echo esc_html( $title ) ?>
                    </div>
				<?php }
				$links         = $this->settings->get_links();
				$currency_name = get_woocommerce_currencies();
				foreach ( $links as $k => $link ) {
					$selected = '';
					if ( $currency_selected == $k ) {
						$selected = 'wmc-active';
					}
					?>
                    <div class="wmc-currency <?php echo esc_attr( $selected ) ?>"
                         data-currency="<?php echo esc_attr( $k ) ?>">
						<?php
						switch ( $this->settings->get_sidebar_style() ) {
							case 1:
								$symbol = get_woocommerce_currency_symbol( $k );
								break;
							case 2:
							case 3:
							case 4:
								$country = $this->settings->get_country_data( $k );
								$symbol  = '<i class="vi-flag-64 flag-' . strtolower( $country['code'] ) . '"></i>';
								break;
							default:
								$symbol = esc_html( $k );
						}
						switch ( $this->settings->get_sidebar_style() ) {
							case 3:
								$currency_code = $k;
								break;
							case 4:
								$currency_code = get_woocommerce_currency_symbol( $k );
								break;
							default:
								$currency_code = $currency_name[ $k ];

						}
						?>
                        <span class="wmc-currency-symbol"><?php echo wp_kses_post( $symbol ); ?></span>
						<?php
						if ( $selected ) {
							?>
                            <span class="wmc-active-title"><?php echo esc_html( $currency_code ); ?></span>
							<?php
						} else {
							?>
                            <a <?php echo esc_attr( WOOMULTI_CURRENCY_F_Data::get_rel_nofollow() ); ?>
                                    href="<?php echo esc_url( $link ) ?>"><?php echo esc_html( $currency_code ); ?></a>
							<?php
						}
						?>
                    </div>
					<?php
				}
				?>
                <div class="wmc-sidebar-open"></div>
            </div>
        </div>
		<?php
	}

	/**
	 * Check design enable
	 * @return bool
	 *
	 */
	protected function enable() {
		$enable = $this->settings->get_enable_design();
		if ( ! $enable ) {
			return false;
		}
		if ( $this->settings->is_checkout() ) {
			if ( is_checkout() ) {
				return false;
			}
		}
		if ( $this->settings->is_cart() ) {
			if ( is_cart() ) {
				return false;
			}
		}

		return true;
	}

}