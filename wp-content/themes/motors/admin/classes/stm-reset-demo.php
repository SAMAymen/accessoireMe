<?php

class STM_Reset_Demo {

	public static function init() {
		add_action( 'wp_ajax_stm_reset_demo', [ self::class, 'reset_demo' ] );
	}

	public static function reset_demo() {
		global $wpdb;

		check_ajax_referer('stm_reset_demo', 'nonce');

		$wpdb->query( "TRUNCATE TABLE $wpdb->posts" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->postmeta" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->comments" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->commentmeta" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->terms" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->termmeta" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->term_taxonomy" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->term_relationships" );
		$wpdb->query( "TRUNCATE TABLE $wpdb->links" );

		update_option( 'show_on_front', 'posts' );

		// Delete Sliders
		self::delete_revolution_sliders();

		// Delete Widgets
		delete_option( 'sidebars_widgets' );

		// Delete Theme Options
		do_action( 'stm_reset_theme_options' );

		// Deactivate Plugins
		$required_plugins   = apply_filters( 'stm_theme_plugins', [] );
		$plugins            = [];

		foreach ( $required_plugins as $plugin ) {
			$plugins[] = STM_Theme_Plugins::get_plugin_main_path( $plugin['slug'] );
		}

		deactivate_plugins( $plugins );

		wp_send_json( 'Database was reset successfully!' );
	}

	public static function delete_revolution_sliders() {
		if ( class_exists('RevSlider') ) {
			$rev_slider = new RevSlider();
			$sliders    = $rev_slider->get_sliders();
			foreach ( $sliders as $slider ) {
				$slider->delete_slider();
			}
		}
		return true;
	}

}