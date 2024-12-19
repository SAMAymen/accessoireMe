<?php

class STM_Theme_Info {

	public static function get_theme_info() {
		$theme      = wp_get_theme();
		$theme_name = $theme->get('Name');
		$theme_v    = $theme->get('Version');

		$theme_info = [
			'name' => $theme_name,
			'slug' => sanitize_file_name(strtolower($theme_name)),
			'v'    => $theme_v
		];

		return $theme_info;
	}

	public static function get_activation_url() {
		return sprintf( STM_ACTIVATION_URL, STM_ENVATO_ID, site_url() );
	}

	public static function get_activation_info() {
		return get_site_option( STM_TOKEN_OPTION );
	}

	public static function get_activation_token() {
		$token_info = self::get_activation_info();

		return isset( $token_info['token'] ) ? $token_info['token'] : null;
	}

	public static function get_image_url( $image ) {
		return esc_url(get_template_directory_uri() . '/admin/assets/img/' . $image);
	}

	public static function is_dev_mode() {
		return defined( 'STM_DEV_MODE' ) && STM_DEV_MODE;
	}

	public static function get_convert_memory( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );

		if ( in_array(strtoupper( $l ), ['P', 'T', 'G', 'M', 'K']) ) {
			$ret *= 1024;
		}

		return $ret;
	}

}