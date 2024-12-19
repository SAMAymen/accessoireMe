<?php

namespace SEI\Classes\Admin;

class AdminMenu {

	/**
	 * Init AdminMenu Class
	 */
	public static function init() {
		add_action( 'admin_menu', [self::class, 'admin_menu_pages'] );
		add_filter( 'plugin_action_links_' . plugin_basename(SEI_FILE), [self::class, 'plugin_action_links'] );
	}

	/**
	 * Register Admin Menu pages
	 */
	public static function admin_menu_pages() {
		add_menu_page(
			esc_html__( 'Custom Icons', 'stm-elementor-icons' ),
			esc_html__( 'Custom Icons', 'stm-elementor-icons' ),
			'administrator',
			'stm-elementor-icons',
			'SEI\Classes\Admin\DashboardController::render',
			'dashicons-art',
			30
		);
	}

	/**
	 * Add Custom Links to Plugins page
	 * @param $links
	 * @return mixed
	 */
	public static function plugin_action_links($links)
	{
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=stm-elementor-icons' ), esc_html__( 'Custom Icons', 'stm-elementor-icons' ) );
		array_unshift( $links, $settings_link );

		return $links;
	}
}