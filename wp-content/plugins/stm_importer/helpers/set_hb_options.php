<?php
function stm_set_hb_options( $layout ) {

	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$widgets = STM_CONFIGURATIONS_PATH . '/demos/' . $layout . '/header/stm_hb_settings.json';

	if ( file_exists( $widgets ) ) {
		$hb_options = $wp_filesystem->get_contents( $widgets );
		$hb_options = json_decode( $hb_options, true );
		update_option( 'stm_hb_settings', $hb_options );
	}
}
