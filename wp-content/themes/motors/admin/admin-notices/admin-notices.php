<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'stm_admin_notices_init' ) ) {
	define( 'STM_ADMIN_NOTICES_VERSION', '1.1.1' );
	define( 'STM_ADMIN_NOTICES_PATH', dirname( __FILE__ ) );
	define( 'STM_ADMIN_NOTICES_URL', ( false !== strpos( STM_ADMIN_NOTICES_PATH, 'plugins' ) ) ? plugin_dir_url( __FILE__ ) : get_template_directory_uri() . '/admin/admin-notices/' );

	function stm_admin_notices_init( $plugin_data ) {
		if ( ! is_admin() ) {
			return;
		}
		STMNotices::init( $plugin_data );
	}
}

$path         = dirname( __FILE__ );
$position     = strstr( $path, 'wp-content/plugins/' );
$product_name = array();

if ( $position !== false ) {
	$plugin_path                 = substr( $position, strlen( 'wp-content/plugins/' ) );
	$parts                       = explode( '/', $plugin_path );
	$product_name['plugin_name'] = $parts[0];
} else {
	$product_name['plugin_name'] = wp_get_theme( get_template() )->get( 'TextDomain' );
}

if ( ! class_exists( 'STMNoticesInit' ) ) {
	require_once __DIR__ . '/classes/STMNoticesInit.php';
}
STMNoticesInit::init( $product_name );
