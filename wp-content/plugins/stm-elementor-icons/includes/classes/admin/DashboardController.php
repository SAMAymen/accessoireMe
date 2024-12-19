<?php

namespace SEI\Classes\Admin;

use SEI\Classes\LoadIcons;
use SEI\Classes\Template;
use SEI\Classes\Nonces;

class DashboardController {

	/**
	 * Enqueue Admin Styles & Scripts
	 */
	public static function enqueue_styles_scripts() {
		if ( file_exists( SEI_PATH . '/assets/css/admin.css' ) ) {
			wp_enqueue_style( 'stm-elementor-icons-css', SEI_URL . 'assets/css/admin.css', array(), SEI_VERSION );
		}

		wp_enqueue_script( 'media-upload' );
		wp_enqueue_media();

		wp_enqueue_script( 'stm-elementor-icons-js', SEI_URL . 'assets/js/admin.js', array(), SEI_VERSION );

		$ajax_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonces'   => Nonces::get_nonces(),
		);

		wp_localize_script( 'stm-elementor-icons-js', 'cei_window', $ajax_data );

		$load_icons = new LoadIcons();
		$load_icons->enqueue();
	}

	/**
	 * Display Rendered Template
	 *
	 * @return bool|string
	 */
	public static function render() {
		self::enqueue_styles_scripts();

		return Template::load_template(
			'icon-manager',
			array( 'fonts' => get_option( 'stm_fonts' ) ),
			true
		);
	}

}
