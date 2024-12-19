<?php

class STM_Theme_Downloads {

	public static function init() {
		add_filter( 'upgrader_package_options', [ self::class, 'stm_upgrader_package_options' ] );
		add_filter( 'upgrader_pre_download', [ self::class, 'stm_upgrader_pre_download' ], 10, 2 );
	}

	public static function api_request( $method, $path, $options = [] ) {
		$options = wp_parse_args( $options, [
			'token' => STM_Theme_Info::get_activation_token(),
		] );
		$res = wp_remote_request( STM_LICENSE_API_URL . '/' . ltrim( $path, '/' ), [
			'method' => $method,
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => 'Bearer ' . $options['token'],
				'X-Wordpress-Site' => get_site_url(),
				'X-Stylemix-Item' => get_template(),
			],
		] );

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		$code   = wp_remote_retrieve_response_code( $res );
		$return = json_decode( wp_remote_retrieve_body( $res ), true );

		if ( 200 !== $code ) {
			$message = ! empty( $return['message'] )
				? $return['message']
				: "HTTP error occurred with status: $code and no message from server.";
			return new WP_Error( 'http_error', $message );
		}

		return $return;
	}

	public static function stm_upgrader_package_options( $options ) {
		// this is not our case
		if ( ! isset( $options['package'] ) || strpos( $options['package'], 'downloads://' ) !== 0 ) {
			return $options;
		}

		$token = STM_Theme_Info::get_activation_token();

		if ( empty( $token ) ) {
			$options['package'] = new WP_Error( 'no_token', 'Theme activation is required for downloading premium content.' );
			return $options;
		}

		$file = str_replace( 'downloads://', '', $options['package'] );
		$res = wp_remote_get( sprintf( STM_LICENSE_API_URL . '/download-url?file=%s' , $file ), [
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
				'X-Wordpress-Site' => get_site_url(),
				'X-Stylemix-Item' => get_template(),
			],
		] );

		$code = wp_remote_retrieve_response_code( $res );
		$body = wp_remote_retrieve_body( $res );
		if ( $code === 200) {
			$options['package'] = $body;
		} elseif ( is_wp_error( $res ) ) {
			$options['package'] = $res;
		} else {
			$json = json_decode( $body, true );
			$options['package'] = new WP_Error(
				$code,
				! empty( $json['message'] ) ? $json['message'] : "HTTP error occurred with status: $code and no message while resolving download URL."
			);
		}

		return $options;
	}

	public static function stm_upgrader_pre_download( $res, $package ) {
		if ( is_wp_error( $package ) ) {
			return $package;
		}

		return $res;
	}

}