<?php
/**
 * Plugin Name: STM Motors Extends
 * Plugin URI:  https://stylemixthemes.com/
 * Description: STM Motors Extends WordPress Plugin
 * Version:     2.4.4
 * Author:      StylemixThemes
 * Author URI:  https://stylemixthemes.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: stm_motors_extends
 * Domain Path: /languages
 */

define( 'STM_MOTORS_EXTENDS_PLUGIN_VERSION', '2.4.4' );
define( 'STM_MOTORS_EXTENDS_DB_VERSION', '1.0.2' );
define( 'STM_MOTORS_EXTENDS_ROOT_FILE', __FILE__ );
define( 'STM_MOTORS_EXTENDS_PATH', dirname( __FILE__ ) );
define( 'STM_MOTORS_EXTENDS_INC_PATH', dirname( __FILE__ ) . '/inc/' );
define( 'STM_MOTORS_EXTENDS_URL', plugins_url( '', __FILE__ ) );

if ( ! function_exists( 'motors_extends_active_pro' ) ) {
	function motors_extends_active_pro() {
		return true;
	}
}
add_filter( 'is_mvl_pro', 'motors_extends_active_pro', 100, 1 );
add_filter( 'wpcfto_check_is_pro_field', 'motors_extends_active_pro', 100, 1 );

if ( ! is_textdomain_loaded( 'stm_motors_extends' ) ) {
	load_plugin_textdomain( 'stm_motors_extends', false, 'stm-motors-extends/languages' );
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

require_once STM_MOTORS_EXTENDS_INC_PATH . 'layout-checker.php';

add_action(
	'plugins_loaded',
	function() {
		if ( ! is_plugin_active( 'stm-post-type/stm-post-type.php' ) && ! class_exists( 'STM_PostType' ) ) {
			require_once dirname( __FILE__ ) . '/stm-post-type/stm-post-type.php';
		}
	}
);

require_once dirname( __FILE__ ) . '/nuxy/NUXY.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'butterbean_hooks.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'PluginSettingsDemoDependency.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'vehicles_listing_extends.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'wpcfto_conf.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'helpers.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'patcher.php';

if ( 'ev_dealer' === stm_me_get_current_layout() ) {
	require_once STM_MOTORS_EXTENDS_INC_PATH . 'stm-swiper-slider.php';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( is_admin() && isset( $_GET['page'] ) && 'stm-listing-slider' === $_GET['page'] ) {
		add_action( 'admin_footer', 'stm_swiper_slider_admin_footer_styles' );
	}
}

function stm_swiper_slider_admin_footer_styles() {
	?>
	<style>
		.stm_swiper_slides_repeater .wpcfto-field-content, .stm_swiper_slides_repeater .wpcfto-field-content .wpcfto_multi_checkbox label {
			width: 100% !important;
	</style>
	<?php
}

$widgets_path = STM_MOTORS_EXTENDS_INC_PATH . 'widgets';

// Widgets.
require_once $widgets_path . '/text-widget.php';
require_once $widgets_path . '/contacts.php';
require_once $widgets_path . '/socials.php';
require_once $widgets_path . '/latest-posts.php';
require_once $widgets_path . '/address.php';
require_once $widgets_path . '/dealer_info.php';
require_once $widgets_path . '/car_location.php';
require_once $widgets_path . '/similar_cars.php';
require_once $widgets_path . '/car-contact-form.php';
require_once $widgets_path . '/classified-four-price-view.php';
require_once $widgets_path . '/classified-four-car-data.php';
require_once $widgets_path . '/schedule_showing.php';
require_once $widgets_path . '/car_calculator.php';
require_once $widgets_path . '/car_trade_offer_btns.php';

if ( apply_filters( 'stm_is_magazine', false ) ) {
	require_once $widgets_path . '/recomended_for_you.php';
}

if ( is_admin() ) {
	require_once STM_MOTORS_EXTENDS_INC_PATH . 'announcement/main.php';
}

