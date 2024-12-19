<?php

class STM_Theme_Plugins {

	public static function init() {
		add_action( 'wp_ajax_stm_install_plugin', [ self::class, 'install_plugin' ] );
	}

	public static function install_plugin() {
		check_ajax_referer( 'stm_install_plugin', 'security' );

		$result         = [];
		$layout_plugins = [];
		$layout         = sanitize_text_field( $_GET['layout'] );
		$builder        = sanitize_text_field( $_GET['builder'] );
		$plugin_slug    = sanitize_text_field( $_GET['plugin'] );
		$plugins        = apply_filters( 'stm_theme_plugins', [] );
		$layout_config  = apply_filters( 'stm_theme_layout_plugins', $layout );
		$layout_config  = self::get_builder( $layout_config, $builder );

		foreach ( $plugins as $plugin_name => $plugin_info ) {
			if ( in_array( $plugin_name, $layout_config ) ) {
				$layout_plugins[ $plugin_name ] = $plugin_info;
			}
		}

		$plugins = $layout_plugins;

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		/** if install demo */
		if ( $plugin_slug === 'import_demo' ) {
			$result['import_demo'] = true;
			wp_send_json( $result );
		}

		/** No plugin */
		if ( empty( $plugin_slug ) and ! empty( $plugins[ $plugin_slug ] ) ) {
			wp_send_json( [ 'error' => 'Error occured' ] );
			exit;
		}

		self::load_wp();

		$plugin_upgrader = new Plugin_Upgrader( new STM_Plugin_Upgrader_Skin( [ 'plugin' => $plugin_slug ] ) );
		$plugin_info     = $plugins[ $plugin_slug ];
		$source          = self::get_plugin_source( $plugin_info );
		$next            = self::get_next_plugin( $plugins, $plugin_slug );

		if ( ! empty( $source ) ) {
			$installed        = ( self::plugin_is_active( $plugin_slug ) ) ? true : $plugin_upgrader->install( $source );
			$result['source'] = $source;

			if ( is_wp_error( $installed ) ) {
				$result['error'] = $installed->get_error_message();
			} else {
				if ( ! empty( $next ) ) {
					$result['next'] = $next['slug'];
				}

				self::activate_plugin( $plugin_slug );

				$result['installed']   = true;
				$result['activated']   = true;
				$result['plugin_slug'] = $plugin_slug;
			}
		}

		if ( end( $plugins ) === $plugin_info ) {
			$result['import_demo'] = true;
		}

		apply_filters( 'after_install_plugin', $layout, $plugin_slug );

		wp_send_json( $result );
		exit;
	}

	public static function load_wp() {
		require_once ABSPATH . 'wp-load.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		require_once get_template_directory() . '/admin/classes/stm-plugin-upgrader-skin.php';
	}

	public static function get_builder( $plugins, $builder ) {
		if ( ! empty( $builder ) ) {
			$plugins[]     = $builder;
			$builder_addon = apply_filters( 'stm_theme_elementor_addon', '' );

			if ( $builder === 'elementor' && ! empty( $builder_addon ) ) {
				$plugins[] = $builder_addon;
			}
		}

		return $plugins;
	}

	public static function get_plugin_source( $plugin_info ) {
		$source = '';

		if ( ! empty( $plugin_info['source'] ) ) {
			$source = $plugin_info['source'];
		} else {
			$response = plugins_api( 'plugin_information', [ 'slug' => $plugin_info['slug'] ] );
			if ( ! is_wp_error( $response ) and ! empty( $response->download_link ) ) {
				$source = $response->download_link;
			}
		}

		return $source;
	}

	public static function get_next_plugin( $array, $key ) {
		$currentKey = key( $array );
		while ( $currentKey !== null && $currentKey != $key ) {
			next( $array );
			$currentKey = key( $array );
		}

		return next( $array );
	}

	public static function activate_plugin( $slug ) {
		activate_plugin( self::get_plugin_main_path( $slug ) );
	}

	public static function plugin_is_active( $slug ) {
		if ( strpos( $slug, '.php' ) === false ) {
			$slug = self::get_plugin_main_path( $slug );
		}

		return in_array( $slug, (array) get_option( 'active_plugins', [] ) ) || is_plugin_active_for_network( $slug );
	}

	public static function get_plugin_main_path( $slug ) {

		$plugin_data = ( is_dir( WP_PLUGIN_DIR . '/' . $slug ) ) ? get_plugins( '/' . $slug ) : array();

		if ( ! empty( $plugin_data ) ) {
			$plugin_file = array_keys( $plugin_data );
			$plugin_path = $slug . '/' . $plugin_file[0];
		} else {
			$plugin_path = false;
		}

		return $plugin_path;
	}

	public static function secondary_required_plugins() {
		return apply_filters( 'stm_theme_secondary_required_plugins', [] );
	}
}