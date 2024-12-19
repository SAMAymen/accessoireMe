<?php
/**
 * Plugin Name: CURCY - Multi Currency for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woo-multi-currency/
 * Description: Allows you to display prices and accepts payments in multiple currencies. Working only with WooCommerce.
 * Version: 2.2.4
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Copyright 2016-2024 VillaTheme.com. All rights reserved.
 * Text Domain: woo-multi-currency
 * Requires Plugins: woocommerce
 * Tested up to: 6.7
 * WC requires at least: 7.0
 * WC tested up to: 9.4
 * Elementor tested up to: 3.6.5
 * Requires PHP: 7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WOOMULTI_CURRENCY_F_VERSION', '2.2.4' );
define( 'WOOMULTI_CURRENCY_F_FILE', __FILE__ );

/**
 * Class WOOMULTI_CURRENCY_F
 */
class WOOMULTI_CURRENCY_F {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
//		add_action( 'admin_notices', array( $this, 'global_note' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Compatible with High-Performance order storage (COT)
		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
	}

	public function init() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
			return;
		}

		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-multi-currency" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "support.php";
		}

		$environment = new VillaTheme_Require_Environment( [
				'plugin_name'     => 'CURCY - Multi Currency for WooCommerce',
				'php_version'     => '7.0',
				'wp_version'      => '6.0',
				'require_plugins' => [
					[
						'slug'    => 'woocommerce',
						'name'    => 'WooCommerce',
						'file'    => 'woocommerce/woocommerce.php',
						'version' => '7.0'
					],
				]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'woo-multi-currency' . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
		require_once $init_file;
	}

	/**
	 * Notify if WooCommerce is not activated
	 */
	function global_note() {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
            <div id="message" class="error">
                <p><?php esc_html_e( 'Please install and activate WooCommerce to use Multi Currency for WooCommerce plugin.', 'woo-multi-currency' ); ?></p>
            </div>
			<?php
		}
		if ( is_plugin_active( 'woo-multi-currency-pro/woo-multi-currency-pro.php' ) ) {
			deactivate_plugins( 'woo-multi-currency-pro/woo-multi-currency-pro.php' );
			unset( $_GET['activate'] );
		}
	}

	public function before_woocommerce_init() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
//			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
			return;
		}
		global $wp_version;
		if ( version_compare( $wp_version, "5.0", "<" ) ) {
//			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
//			wp_die( "This plugin requires WordPress version 5.0 or higher." );
		} else {

			$data_init = array(
				"auto_detect"          => "0",
				"enable_design"        => "1",
				"design_title"         => "Select your currency",
				"design_position"      => "1",
				"text_color"           => "#ffffff",
				"main_color"           => "#f78080",
				"background_color"     => "#212121",
				"is_checkout"          => "1",
				"is_cart"              => "1",
				"conditional_tags"     => "",
				"flag_custom"          => "",
				"custom_css"           => "",
				"enable_multi_payment" => "1",
				"update_exchange_rate" => "0",
				"finance_api"          => "0",
				"rate_decimals"        => "5",
				"key"                  => "",
			);
			if ( ! get_option( 'woo_multi_currency_params', '' ) ) {
				update_option( 'woo_multi_currency_params', $data_init );
			}
		}
	}
}

new WOOMULTI_CURRENCY_F();