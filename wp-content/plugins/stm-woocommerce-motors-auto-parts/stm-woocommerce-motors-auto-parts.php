<?php
/**
Plugin Name: Motors - WooCommerce Auto Parts
Plugin URI: http://stylemixthemes.com/
Description: Woocommerce Motors Auto Parts Shop
Author: StylemixThemes
Author URI: https://stylemixthemes.com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: stm-woocommerce-motors-auto-parts
Version: 1.1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	return false;
}

define( 'STM_WCMAP_PATH', dirname( __FILE__ ) );
define( 'STM_WCMAP_INC_PATH', dirname( __FILE__ ) . '/inc/' );
define( 'STM_WCMAP_URL', plugins_url( '', __FILE__ ) );
define( 'STM_WCMAP', 'stm-woocommerce-category-megamenu' );

if ( ! is_textdomain_loaded( 'stm-woocommerce-motors-auto-parts' ) ) {
	load_plugin_textdomain( 'stm-woocommerce-motors-auto-parts', false, 'stm-woocommerce-motors-auto-parts/languages' );
}

require_once STM_WCMAP_INC_PATH . 'setup.php';
require_once STM_WCMAP_INC_PATH . 'scripts-styles.php';
require_once STM_WCMAP_INC_PATH . 'woo-action-filter-calls.php';
require_once STM_WCMAP_INC_PATH . 'woo-attr-image.php';
require_once STM_WCMAP_INC_PATH . 'woo-cat-advert-banner.php';
require_once STM_WCMAP_INC_PATH . 'class-wcmap-search-filter.php';
require_once STM_WCMAP_INC_PATH . 'functions.php';
require_once STM_WCMAP_INC_PATH . 'visual_composer.php';

if ( is_admin() ) {
	require_once STM_WCMAP_INC_PATH . 'delete-attributes.php';
}
