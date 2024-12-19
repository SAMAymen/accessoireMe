<?php
add_action( 'admin_enqueue_scripts', 'mvl_theme_install_base_scripts' );
function mvl_theme_install_base_scripts() {
	wp_enqueue_script( 'stm-admin-base', get_template_directory_uri() . '/assets/admin/js/admin-base.js', array( 'jquery' ), 1.0, true );

	$js_vars = array( 'mvl_theme_install_base' => wp_create_nonce( 'mvl_theme_install_base' ) );

	wp_localize_script( 'stm-admin-base', 'mvl_nonces', $js_vars );
}

add_action( 'wp_ajax_mvl_theme_install_base', 'mvl_theme_install_base' );

function mvl_theme_install_base() {
	check_ajax_referer( 'mvl_theme_install_base', 'nonce' );

	$response = array();

	$plugin_url  = sanitize_text_field( $_GET['plugin'] );
	$plugin_slug = 'motors-car-dealership-classified-listings';

	ob_start();
	require_once ABSPATH . 'wp-load.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
	require_once get_template_directory() . '/inc/install_plugin/stm_upgrader_skin.php';

	$plugin_upgrader = new Plugin_Upgrader( new Motors_Theme_Plugin_Upgrader_Skin( array( 'plugin' => $plugin_slug ) ) );

	$installed = ( mvl_theme_check_plugin_active( $plugin_slug ) ) ? true : $plugin_upgrader->install( $plugin_url );
	mvl_theme_activate_plugin( $plugin_slug );

	$response['message'] = ob_get_clean();
	$response['url']     = admin_url( 'admin.php?page=mvl_plugin_settings' );

	wp_send_json( $response );
}

function mvl_theme_check_plugin_active( $slug ) {
	return is_plugin_active( mvl_theme_get_plugin_main_path( $slug ) );
}

function mvl_theme_activate_plugin( $slug ) {
	activate_plugin( mvl_theme_get_plugin_main_path( $slug ) );
}

function mvl_theme_get_plugin_main_path( $slug ) {
	$plugin_data = get_plugins( '/' . $slug );

	if ( ! empty( $plugin_data ) ) {
		$plugin_file = array_keys( $plugin_data );
		$plugin_path = $slug . '/' . $plugin_file[0];
	} else {
		$plugin_path = false;
	}

	return $plugin_path;
}
