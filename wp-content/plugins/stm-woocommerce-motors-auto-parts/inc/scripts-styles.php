<?php
function stm_wcmap_enqueue_scripts_styles( $handle, $name ) {
	global $wp_version;

	wp_enqueue_style( 'stm-wcmap-' . $handle, STM_WCMAP_URL . '/assets/css/' . $name . '.css', array(), $wp_version, 'all' );
	wp_enqueue_script( 'stm-wcmap-' . $handle, STM_WCMAP_URL . '/assets/js/' . $name . '.js', array( 'jquery' ), $wp_version, true );
	wp_enqueue_style( 'owl.carousel', STM_WCMAP_URL . '/assets/css/owl.carousel.css', array(), $wp_version, 'all' );
	wp_enqueue_script( 'owl.carousel', STM_WCMAP_URL . '/assets/js/owl.carousel.js', array( 'jquery' ), $wp_version, true );

	wp_enqueue_style( 'stm_wcmap_yith_compate', STM_WCMAP_URL . '/assets/css/stm_wcmap_yith_compate.css', array(), $wp_version, 'all' );
}

function stm_wcmap_admin_scripts_styles() {
	global $wp_version;

	wp_enqueue_style( 'stm-datetimepicker', STM_WCMAP_URL . '/assets/css/jquery.stmdatetimepicker.css', null, $wp_version, 'all' );
	wp_enqueue_script( 'stm-datetimepicker', STM_WCMAP_URL . '/assets/js/jquery.stmdatetimepicker.js', array( 'jquery' ), $wp_version, true );

	wp_enqueue_script( 'jquery-ui-datepicker' );

	wp_enqueue_style( 'stm-listings-timepicker', STM_WCMAP_URL . '/assets/css/jquery.timepicker.css', null, $wp_version, 'all' );
	wp_enqueue_script( 'stm-listings-timepicker', STM_WCMAP_URL . '/assets/js/jquery.timepicker.js', array( 'jquery' ), $wp_version, true );

	wp_enqueue_style( 'stm-theme-etm-style', STM_WCMAP_URL . '/assets/css/admin-style.css', null, $wp_version, 'all' );
}

add_action( 'admin_enqueue_scripts', 'stm_wcmap_admin_scripts_styles' );
