<?php
/**
 * Plugin Name: STM Elementor Icons
 * Plugin URI: http://stylemixthemes.com/
 * Description: STM Elementor Icons is a free WordPress plugin allowing its users to upload unlimited custom icons to their websites.
 * Author: StylemixThemes
 * Author URI: https://stylemixthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: stm-elementor-icons
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SEI_VERSION', '1.0.0' );
define( 'SEI_FILE', __FILE__ );
define( 'SEI_PATH', dirname( SEI_FILE ) );
define( 'SEI_INCLUDES_PATH', SEI_PATH . '/includes/' );
define( 'SEI_CLASSES_PATH', SEI_INCLUDES_PATH . 'classes/' );
define( 'SEI_URL', plugin_dir_url( SEI_FILE ) );

require_once( SEI_INCLUDES_PATH . '/autoload.php' );

if ( is_admin() ) {
	require_once SEI_INCLUDES_PATH . '/item-announcements.php';
}

function sei_deactivate_undesirable_plugins() {
	deactivate_plugins( 'custom-elementor-icons/custom-elementor-icons.php' );
}

register_activation_hook( __FILE__, 'sei_deactivate_undesirable_plugins' );
