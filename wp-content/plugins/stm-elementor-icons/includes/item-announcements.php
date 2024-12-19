<?php

add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'stm-item-announcements-styles', 'https://stylemixthemes.com/item-announcements/css/app.css', [], null );
	wp_enqueue_script( 'stm-item-announcements-app', 'https://stylemixthemes.com/item-announcements/js/app.js', [], null, true );
	wp_localize_script( 'stm-item-announcements-app', 'stmItemAnnouncements', [
		'installedPlugins' => array_values( array_filter( scandir( WP_PLUGIN_DIR ), function ( $name ) {
			return strpos( $name, '.' ) !== 0 && $name !== 'index.php';
		} ) ),
		'installedThemes' => array_values( array_filter( scandir( WP_CONTENT_DIR . '/themes' ), function ( $name ) {
			return strpos( $name, '.' ) !== 0 && $name !== 'index.php';
		} ) ),
	] );
} );

add_action( 'all_admin_notices', function () {
	echo '<div data-mount="stm-item-announcements-notice" data-slug="stm-elementor-icons"></div>';
} );
