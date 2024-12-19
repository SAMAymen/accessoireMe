<?php

class STMNoticesInit {
	public static function init($plugin_data) {
		if ( ! class_exists( 'STMDashboard' ) ) {
			require_once __DIR__ . '/STMDashboard.php';
		}

		if ( ! class_exists( 'STMHandler' ) ) {
			require_once __DIR__ . '/STMHandler.php';
		}

		if ( ! class_exists( 'STMNotices' ) ) {
			require_once __DIR__ . '/STMNotices.php';
		}

		if ( ! class_exists( 'STMBulkNotices' ) ) {
			require_once __DIR__ . '/STMBulkNotices.php';
		}

		add_action( 'admin_enqueue_scripts', array( self::class, 'admin_enqueue' ), 100 );

		new STMDashboard($plugin_data);
	}

	/**
	 * Enqueue admin notice scripts
	 *
	 * @return void
	 */
	public static function admin_enqueue() {
		wp_enqueue_style( 'stm-admin-notice-css', STM_ADMIN_NOTICES_URL . 'assets/css/admin.css', false ); // phpcs:ignore
		wp_enqueue_script( 'stm-admin-notice-js', STM_ADMIN_NOTICES_URL . 'assets/js/an-scripts.js', array( 'jquery' ), '1.0' );
		wp_localize_script( 'stm-admin-notice-js', 'stmNotices', array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'notices-nonce' ),
			'api_fetch_url' => defined('STM_DEV')  ? 'http://stylemixnotification.local' : 'https://promo-dashboard.stylemixthemes.com',
		));
	}
}
