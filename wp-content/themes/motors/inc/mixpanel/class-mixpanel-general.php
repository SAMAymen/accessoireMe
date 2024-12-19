<?php

namespace Motors\Mixpanel;

class Mixpanel_General extends Mixpanel {
	public static function register_data() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( defined( 'STM_LISTINGS_V' ) ) {
			self::add_data( 'Motors Free Plugin', STM_LISTINGS_V );
		}

		$theme_data = wp_get_theme();

		if ( $theme_data->parent() ) {
			$theme_data = $theme_data->parent();
		}

		self::add_data( 'Theme Name', $theme_data->get( 'Name' ) );
		self::add_data( 'Theme Version', $theme_data->get( 'Version' ) );
	}

	public static function is_dev( $domain ) {
		$devs = array( '.loc', '.local', 'stylemix', 'localhost', 'stm' );
		foreach ( $devs as $dev ) {
			if ( false !== stripos( $domain, $dev ) ) {
				return true;
			}
		}

		return false;
	}
}
