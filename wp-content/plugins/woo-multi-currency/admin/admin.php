<?php

/*
Class Name: WOOMULTI_CURRENCY_F_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Admin_Admin {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		add_filter(
			'plugin_action_links_woo-multi-currency/woo-multi-currency.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
		add_filter( 'woocommerce_general_settings', array( $this, 'woocommerce_general_settings' ) );
	}

	/**
	 * Remove currency, decimal, posistion, setting in backend
	 *
	 * @param $datas
	 *
	 * @return mixed
	 */
	public function woocommerce_general_settings( $datas ) {
		foreach ( $datas as $k => $data ) {
			if ( isset( $data['id'] ) ) {
				if ( $data['id'] == 'woocommerce_currency' || $data['id'] == 'woocommerce_price_num_decimals' || $data['id'] == 'woocommerce_currency_pos' ) {
					unset( $datas[ $k ] );
				}
				if ( $data['id'] == 'pricing_options' ) {
					$datas[ $k ]['desc'] = esc_html__( 'The following options affect how prices are displayed on the frontend. Multi Currency for WooCommerce is working. Please go to ', 'woo-multi-currency' ) . '<a href="' . admin_url( '?page=woo-multi-currency' ) . '">' . esc_html__( 'Multi Currency for WooCommerce setting page', 'woo-multi-currency' ) . '</a>' . esc_html__( ' to set default currency.', 'woo-multi-currency' );
				}
			}
		}

		return $datas;
	}

	/*Check Auto update*/
	public function admin_init() {

		$old_data = get_option( 'wmc_selected_currencies', array() );
		if ( count( $old_data ) ) {
			$currency         = $currency_rate = $currency_decimals = $currency_custom = $currency_pos = array();
			$currency_default = '';
			$by_countries     = json_decode( get_option( 'wmc_currency_by_country', array() ), true );

			/*Move Data Currency*/

			foreach ( $old_data as $k => $data ) {
				if ( $data['is_main'] == 1 ) {
					$currency_default = $k;
				}
				$currency[]          = $k;
				$currency_rate[]     = $data['rate'];
				$currency_decimals[] = $data['num_of_dec'];
				$currency_pos[]      = $data['pos'];
				if ( strpos( $data['custom_symbol'], '#PRICE#' ) === false ) {
					$currency_custom[] = '';
				} else {
					$currency_custom[] = $data['custom_symbol'];
				}
			}
			$by_country_args = array();
			/*Move Data Currency By Country*/
			foreach ( $by_countries as $code => $by_country ) {
				$by_country_args[ $code . '_by_country' ] = $by_country;
			}
			/*Move Key data*/
			if ( get_option( 'wmc_oder_id' ) && get_option( 'wmc_email' ) ) {
				$key = trim( get_option( 'wmc_oder_id' ) ) . ',' . trim( get_option( 'wmc_email' ) );
			} else {
				$key = '';
			}

			$args = array(
				'enable'                     => 1,
				'enable_fixed_price'         => 0,
				'currency_default'           => $currency_default,
				'currency'                   => $currency,
				'currency_rate'              => $currency_rate,
				'currency_decimals'          => $currency_decimals,
				'currency_custom'            => array(),
				'currency_pos'               => $currency_pos,
				'auto_detect'                => get_option( 'wmc_enable_approxi' ) ? get_option( 'wmc_enable_approxi' ) : 0,
				'enable_currency_by_country' => get_option( 'wmc_price_by_currency' ) == 'yes' ? 1 : 0,
				'enable_multi_payment'       => get_option( 'wmc_allow_multi' ) == 'yes' ? 1 : 0,
				'key'                        => $key,
				'update_exchange_rate'       => get_option( 'wmc_price_by_currency' ) == 'yes' ? 2 : 0,
				'enable_design'              => 0,
				'title'                      => '',
				'design_position'            => 0,
				'text_color'                 => '#fff',
				'background_color'           => '#212121',
				'main_color'                 => '#f78080',
				'flag_custom'                => ''
			);

			$args = array_merge( $args, $by_country_args );
			update_option( 'woo_multi_currency_params', $args );
			update_option( 'woo_multi_currency_old_version', 1 );
			delete_option( 'wmc_selected_currencies' );
		}
		/*Set currency again in backend*/
		if ( ! wp_doing_ajax() ) {
			$frontend_call_admin = false;
			//Fix with Jetpack stats request from frontend
			if ( ! isset( $_GET['wmc_admin_nonce'] ) || wp_verify_nonce( wc_clean( wp_unslash( $_GET['wmc_admin_nonce'] ) ), 'wmc_admin_nonce' ) ) {
				if ( isset( $_GET['page'], $_GET['chart'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'stats' && in_array( sanitize_text_field( $_GET['chart'] ), array(
						'admin-bar-hours-scale',
						'admin-bar-hours-scale-2x'
					) ) ) {
					$frontend_call_admin = true;
				}
				if ( ! $frontend_call_admin ) {
					$current_currency = get_option( 'woocommerce_currency' );
					$this->settings->set_current_currency( $current_currency );
				}
			}
		}
	}


	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_settings' ) ) {
			return;
		}
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		if ( $page == 'woo-multi-currency' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					if ( $script->handle == 'query-monitor' ) {
						continue;
					}
					wp_dequeue_script( $script->handle );
				}
			}
			wp_dequeue_style( 'eopa-admin-css' );
			/*Stylesheet*/
			wp_enqueue_style( 'semantic-ui-button', WOOMULTI_CURRENCY_F_CSS . 'button.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-table', WOOMULTI_CURRENCY_F_CSS . 'table.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-transition', WOOMULTI_CURRENCY_F_CSS . 'transition.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-form', WOOMULTI_CURRENCY_F_CSS . 'form.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-icon', WOOMULTI_CURRENCY_F_CSS . 'icon.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-dropdown', WOOMULTI_CURRENCY_F_CSS . 'dropdown.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-checkbox', WOOMULTI_CURRENCY_F_CSS . 'checkbox.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-segment', WOOMULTI_CURRENCY_F_CSS . 'segment.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-menu', WOOMULTI_CURRENCY_F_CSS . 'menu.min.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-tab', WOOMULTI_CURRENCY_F_CSS . 'tab.css', [], '2.1.7' );
			wp_enqueue_style( 'semantic-ui-input', WOOMULTI_CURRENCY_F_CSS . 'input.min.css', [], '2.2.12' );
			wp_enqueue_style( 'semantic-ui-popup', WOOMULTI_CURRENCY_F_CSS . 'popup.min.css', [], '2.3.1' );
			wp_enqueue_style( 'semantic-ui-message', WOOMULTI_CURRENCY_F_CSS . 'message.min.css', [], '2.3.1' );
			wp_enqueue_style( 'woo-multi-currency', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency-admin.css', [], WOOMULTI_CURRENCY_F_VERSION );
			wp_enqueue_style( 'select2', WOOMULTI_CURRENCY_F_CSS . 'select2.min.css', [], '4.0.3' );

			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'semantic-ui-transition', WOOMULTI_CURRENCY_F_JS . 'transition.min.js', array( 'jquery' ), '2.1.7', false );
			wp_enqueue_script( 'semantic-ui-dropdown', WOOMULTI_CURRENCY_F_JS . 'dropdown.js', array( 'jquery' ), '2.1.7', false );
			wp_enqueue_script( 'semantic-ui-checkbox', WOOMULTI_CURRENCY_F_JS . 'checkbox.js', array( 'jquery' ), '2.1.7', false );
			wp_enqueue_script( 'semantic-ui-tab', WOOMULTI_CURRENCY_F_JS . 'tab.js', array( 'jquery' ), '2.4.2', false );
			wp_enqueue_script( 'woo-multi-currency-address', WOOMULTI_CURRENCY_F_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), '1.6', false );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'woo-multi-currency', WOOMULTI_CURRENCY_F_JS . 'woo-multi-currency-admin.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION, false );
			/*Color picker*/
			wp_enqueue_script( 'iris' );

			wp_localize_script( 'woo-multi-currency', 'wmcParams', [ 'nonce' => wp_create_nonce( 'wmc_ajax_nonce' ) ] );
		}
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=woo-multi-currency" title="' . esc_html__( 'Settings', 'woo-multi-currency' ) . '">' . esc_html__( 'Settings', 'woo-multi-currency' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Function init when run plugin+
	 */
	function init() {
		/*Register post type*/

//		load_plugin_textdomain( 'woo-multi-currency' );
		$this->load_plugin_textdomain();
	}


	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale   = apply_filters( 'plugin_locale', get_locale(), 'woo-multi-currency' );
		$basename = 'woo-multi-currency';
		unload_textdomain( 'woo-multi-currency' );

		// Global + Frontend Locale
		load_textdomain( 'woo-multi-currency', WP_LANG_DIR . "/{$basename}/{$basename}-{$locale}.mo" );
		load_plugin_textdomain( 'woo-multi-currency', false, $basename . '/languages' );
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_menu_page(
			esc_html__( 'Multi Currency for WooCommerce', 'woo-multi-currency' ), esc_html__( 'Multi Currency', 'woo-multi-currency' ), 'manage_woocommerce', 'woo-multi-currency', array(
			'WOOMULTI_CURRENCY_F_Admin_Settings',
			'page_callback'
		), WOOMULTI_CURRENCY_F_IMAGES . 'icon.svg', 2
		);

	}

}

?>