<?php

class STM_Theme_Activation {

	public static function init() {
		add_action( 'admin_init', [ self::class, 'activation_redirect' ] );
		add_action( 'admin_init', [ self::class, 'handle_activation' ] );
		add_action( 'admin_init', [ self::class, 'handle_deactivation' ] );
	}

	public static function activation_redirect() {
		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
			wp_redirect( admin_url( "admin.php?page=stm-admin" ) );
		}
	}

	public static function handle_activation() {
		if ( isset( $_GET['stylemixthemes-token'] ) && current_user_can( 'manage_options' ) ) {
			$token  = sanitize_text_field( $_GET['stylemixthemes-token'] );
			$return = STM_Theme_Downloads::api_request( 'GET', 'check', compact( 'token' ) );

			if ( is_wp_error( $return ) ) {
				set_site_transient( 'stm_auth_notice', $return->get_error_message(), HOUR_IN_SECONDS );
			} else {
				set_site_transient( STM_TOKEN_CHECKED_OPTION, true, DAY_IN_SECONDS );
				delete_site_transient( 'stm_auth_notice' );
				update_site_option( STM_TOKEN_OPTION, $return );
			}

			wp_safe_redirect( admin_url( 'admin.php?page=stm-admin' ) );
			exit;
		}
	}

	public static function handle_deactivation() {
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'stm-deactivate' && current_user_can( 'manage_options' ) ) {
			STM_Theme_Downloads::api_request( 'DELETE', 'deactivate' );
			delete_site_transient( STM_TOKEN_CHECKED_OPTION );
			delete_site_transient( 'stm_auth_notice' );

			do_action( 'stm_remove_theme_site_meta' );

			if ( ! STM_Theme_Info::is_dev_mode() ) {
				/** @var \WP_Filesystem_Base $wp_filesystem */
				global $wp_filesystem;
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}
				$wp_filesystem->delete( get_template_directory(), true );
			}

			update_option('template', '');
			update_option('stylesheet', '');
			update_option('current_theme', '');

			wp_safe_redirect( admin_url() );
			exit;
		}
	}

	public static function check_token() {
		$token = STM_Theme_Info::get_activation_token();
		if ( ! $token ) {
			return false;
		}

		$activated = get_site_transient( STM_TOKEN_CHECKED_OPTION );

		if ( false === $activated ) {
			$response = STM_Theme_Downloads::api_request( 'GET', 'check', compact( 'token' ) );
			if ( is_wp_error( $response ) ) {
				set_site_transient( 'stm_auth_notice', $response->get_error_message(), DAY_IN_SECONDS );
				$activated = false;
			}
			else {
				delete_site_transient( 'stm_auth_notice' );
				$activated = true;
			}

			set_site_transient( STM_TOKEN_CHECKED_OPTION, $activated, DAY_IN_SECONDS );
		}

		return (bool) $activated;
	}

}