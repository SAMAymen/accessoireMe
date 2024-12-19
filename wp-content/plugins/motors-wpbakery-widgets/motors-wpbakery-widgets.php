<?php
/**
 * Plugin Name: Motors WPBakery Widgets
 * Plugin URI: http://stylemixthemes.com/
 * Description: Enables WPBakery Page Builder plugin support in Motors theme.
 * Author: StylemixThemes
 * Author URI: https://stylemixthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: motors-wpbakery-widgets
 * Version: 1.6.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'STM_MWW_VERSION', '1.6.5' );
define( 'STM_MWW_PATH', dirname( __FILE__ ) );
define( 'STM_MWW_URL', plugins_url( '', __FILE__ ) );

if ( ! is_textdomain_loaded( 'motors-wpbakery-widgets' ) ) {
	load_plugin_textdomain( 'motors-wpbakery-widgets', false, 'motors-wpbakery-widgets/languages' );
}

if ( defined( 'WPB_VC_VERSION' ) && 'motors' === get_template() ) {
	require_once __DIR__ . '/includes/functions.php';
	require_once __DIR__ . '/includes/register-widgets.php';
	require_once __DIR__ . '/includes/LoadScriptsForUse.php';
}
