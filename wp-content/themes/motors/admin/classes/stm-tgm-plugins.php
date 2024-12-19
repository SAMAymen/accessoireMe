<?php

class STM_TGM_Plugins {

	public static $themePlugins;
	protected static $plugData;
	protected static $pluginsTransient;

	public static function init() {
		if ( ! wp_doing_ajax() ) {
			add_action( 'admin_init', array( self::class, 'set_theme_plugins' ) );
		}

		add_filter( 'pre_set_site_transient_update_plugins', [ self::class, 'stm_add_own_package_url' ], 100 );

		add_action( 'wp_ajax_stm_actions_plugin', [ self::class, 'actions_plugin' ] );
		add_action( 'wp_ajax_stm_get_plugin_info', [ self::class, 'get_plugin_data_from_api' ] );
	}

	public static function set_theme_plugins() {
		self::$themePlugins     = apply_filters( 'stm_theme_plugins', '' );
		self::$pluginsTransient = get_site_transient( 'update_plugins' );

		self::create_plugin_map();
		self::set_plugins_addtionals_info();
		if ( ! empty( STM_Theme_Info::get_activation_token() ) && ! empty( apply_filters( 'stm_theme_demo_layout', '' ) ) ) {
			self::add_core_plugins_notify_item();
		}
	}

	public static function get_theme_plugins() {
		return self::$themePlugins;
	}

	private static function create_plugin_map() {
		self::$plugData = array(
			'all'        => [],
			'active'     => [],
			'inactive'   => [],
			'premium'    => [],
			'free'       => [],
			'required'   => [],
			'has_update' => []
		);
	}

	private static function set_plugins_addtionals_info() {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		foreach ( self::$themePlugins as $k => $plugin ) {
			$plugFilePath        = STM_Theme_Plugins::get_plugin_main_path( $plugin['slug'] );
			$plugin['file_path'] = ( $plugFilePath ) ? $plugFilePath : $k . '/' . $plugin['slug'] . '.php';
			$plugin['sort'][]    = 'all';

			$plugin                  = self::plug_is_required( $plugin );
			$plugin                  = self::plug_is_core( $plugin );
			$plugin                  = self::plug_is_active( $plugin );
			$plugin                  = self::plug_is_premium( $plugin );
			$plugin                  = self::plug_has_update( $plugin );
			$plugin                  = self::plug_info( $plugin );
			self::$plugData['all'][] = $plugin;
		}
	}

	public static function get_plugins_navigate_view( $layout = '' ) {
		$navItems = array();

		$labels = array(
			'all'        => 'All',
			'active'     => 'Active',
			'inactive'   => 'Inactive',
			'premium'    => 'Premium',
			'free'       => 'Free',
			'required'   => 'Required',
			'has_update' => 'Update Available',
			'core'       => 'Core'
		);

		foreach ( self::get_plugins_data( $layout ) as $k => $items ) {
			if ( count( $items ) > 0 ) {
				$navItems[ $k ] = array(
					'label' => $labels[ $k ],
					'count' => count( $items )
				);
			}
		}

		return $navItems;
	}

	public static function get_plugins_data( $layout = '' ) {
		return ( ! $layout ) ? self::$plugData : self::filter_plugins_by_layout( $layout, self::$plugData );
	}

	private static function plug_is_premium( $plug_data ) {
		if ( ! empty( $plug_data['premium'] ) ) {
			$plug_data['is_premium']     = true;
			$plug_data['sort'][]         = 'premium';
			self::$plugData['premium'][] = $plug_data;
		} else {
			$plug_data['is_free']     = true;
			$plug_data['sort'][]      = 'free';
			self::$plugData['free'][] = $plug_data;
		}

		return $plug_data;
	}

	/*
	 * Will add to plugin info it's active or no
	 * */
	private static function plug_is_active( $plug_data ) {
		if ( STM_Theme_Plugins::plugin_is_active( $plug_data['file_path'] ) ) {
			$plug_data['is_active']     = true;
			$plug_data['sort'][]        = 'active';
			self::$plugData['active'][] = $plug_data;
		} else {
			$plug_data['is_inactive']     = true;
			$plug_data['sort'][]          = 'inactive';
			self::$plugData['inactive'][] = $plug_data;
		}

		return $plug_data;
	}

	private static function plug_has_update( $plug_data ) {
		if ( ! is_object( self::$pluginsTransient ) || ! property_exists( self::$pluginsTransient, 'response' ) || ! is_array( self::$pluginsTransient->response ) ) {
			return $plug_data;
		}

		if ( isset( self::$pluginsTransient->response[ $plug_data['file_path'] ] ) ) {
			$plug_data['has_update']        = true;
			$plug_data['update_version']    = self::$pluginsTransient->response[ $plug_data['file_path'] ]->new_version;
			$plug_data['sort'][]            = 'has_update';
			self::$plugData['has_update'][] = $plug_data;
		} else if ( file_exists( str_replace( 'themes', 'plugins', get_theme_root() ) . '/' . $plug_data['file_path'] ) ) {
			if ( isset( self::$themePlugins[ $plug_data['slug'] ]['version'] ) ) {
				$plugInfo = get_plugin_data( str_replace( 'themes', 'plugins', get_theme_root() ) . '/' . $plug_data['file_path'] );

				if ( version_compare( $plugInfo['Version'], self::$themePlugins[ $plug_data['slug'] ]['version'], '<' ) ) {
					$plug_data['has_update']        = true;
					$plug_data['update_version']    = self::$themePlugins[ $plug_data['slug'] ]['version'];
					$plug_data['sort'][]            = 'has_update';
					self::$plugData['has_update'][] = $plug_data;

					$slug = $plug_data['file_path'];

					if ( empty( self::$pluginsTransient->response[ $slug ] ) ) {
						self::$pluginsTransient->response[ $slug ] = new stdClass;
					}

					self::$pluginsTransient->response[ $slug ]->slug   = $plug_data['slug'];
					self::$pluginsTransient->response[ $slug ]->plugin = $slug;

					set_site_transient( 'update_plugins', self::$pluginsTransient );
				}
			}
		}

		return $plug_data;
	}

	private static function plug_is_required( $plug_data ) {
		if ( ! empty( $plug_data['required'] ) ) {
			$plug_data['sort'][]          = 'required';
			self::$plugData['required'][] = $plug_data;
		}

		return $plug_data;
	}

	private static function plug_is_core( $plug_data ) {
		$plug_data = apply_filters( 'filter_core_plugins', $plug_data );

		if ( ! empty( $plug_data['core'] ) ) {
			$plug_data['sort'][]      = 'core';
			self::$plugData['core'][] = $plug_data;
		}

		return $plug_data;
	}

	private static function plug_info( $plug_data ) {
		if ( file_exists( str_replace( 'themes', 'plugins', get_theme_root() ) . '/' . $plug_data['file_path'] ) ) {
			$plugInfo             = get_plugin_data( str_replace( 'themes', 'plugins', get_theme_root() ) . '/' . $plug_data['file_path'] );
			$plug_data['author']  = str_replace( '">', '" target="_blank">', $plugInfo['Author'] );
			$plug_data['version'] = $plugInfo['Version'];
		} else {
			$plug_data['not_installed'] = true;
		}

		if ( isset( $plug_data['source'] ) ) {
			$plug_data['download_link'] = $plug_data['source'];
		}

		return $plug_data;
	}

	private static function filter_plugins_by_layout( $layout, $pluginsAll ) {
		$layout_plugins = apply_filters( 'stm_theme_layout_plugins', $layout );

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$secondaryReqPlugins = apply_filters( 'stm_theme_secondary_required_plugins', [] );

			$key = array_search( 'js_composer', $secondaryReqPlugins );

			unset( $secondaryReqPlugins[ $key ] );

			$layout_plugins = array_merge( $layout_plugins, $secondaryReqPlugins );
		}

		if ( defined( 'WPB_VC_VERSION' ) ) {
			$layout_plugins = array_merge( $layout_plugins, array( 'js_composer' ) );
		}

		foreach ( $pluginsAll as $k => $plugins ) {
			$filteredPlugins[ $k ] = array_filter( $plugins, function ( $plug ) use ( $layout_plugins ) {
				return in_array( $plug['slug'], $layout_plugins );
			} );
		}

		return $filteredPlugins;
	}

	private static function load_wp() {
		require_once ABSPATH . 'wp-load.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		require_once get_template_directory() . '/admin/classes/stm-plugin-upgrader-skin.php';
	}

	public static function actions_plugin() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		check_ajax_referer( 'stm_actions_plugin', 'security' );

		$action   = sanitize_text_field( $_GET['plugin_action'] );
		$slug     = sanitize_text_field( $_GET['plugin_slug'] );
		$filePath = sanitize_text_field( $_GET['plugin_file_path'] );
		$source   = sanitize_text_field( $_GET['plugin_source'] );

		self::load_wp();

		$responce = call_user_func( array( self::class, $action . '_plugin' ), $slug, $source, $filePath );

		wp_send_json( $responce );
	}

	public static function install_plugin( $slug, $source, $filePath ) {

		if ( empty( $source ) ) {
			wp_send_json( array( 'error' => 'Plugin source can not be empty' ) );
		}

		$result = [];

		$plugin_upgrader = new Plugin_Upgrader( new STM_Plugin_Upgrader_Skin( [ 'plugin' => $slug ] ) );

		$installed = $plugin_upgrader->install( $source );

		if ( is_wp_error( $installed ) ) {
			$result['error'] = $installed->get_error_message();
		} else {
			$result['installed']  = true;
			$result['activated']  = activate_plugin( $filePath );
			$result['btn_text']   = 'Deactivate';
			$result['btn_action'] = 'deactivate';
		}

		return $result;
	}

	public static function activate_plugin( $slug, $source, $filePath ) {
		$result = [];

		$activate = activate_plugin( $filePath );

		if ( is_wp_error( $activate ) ) {
			$result['error'] = $activate->get_error_message();
		} else {
			$result['activated']  = true;
			$result['btn_text']   = 'Deactivate';
			$result['btn_action'] = 'deactivate';
		}

		return $result;
	}

	public static function update_plugin( $slug, $source, $filePath ) {
		$result          = [];
		$plugin_upgrader = new Plugin_Upgrader( new STM_Plugin_Upgrader_Skin( [ 'plugin' => $slug ] ) );

		$upgrade = $plugin_upgrader->upgrade( $filePath );

		if ( is_wp_error( $upgrade ) ) {
			$result['error'] = $upgrade->get_error_message();
		} else {
			self::activate_plugin( $slug, $source, $filePath );

			$plugInfo             = get_plugin_data( str_replace( 'themes', 'plugins', get_theme_root() ) . '/' . $filePath );
			$result['upgrade']    = true;
			$result['btn_text']   = 'Deactivate';
			$result['btn_action'] = 'deactivate';
			$result['version']    = $plugInfo['Version'];
		}

		return $result;
	}

	public static function deactivate_plugin( $slug, $source, $filePath ) {
		$result = [];

		if ( deactivate_plugins( $filePath ) ) {
			$result['error'] = 'error';
		} else {
			$result['deactivated'] = true;
			$result['btn_text']    = 'Activate';
			$result['btn_action']  = 'activate';
		}

		return $result;
	}

	public static function get_plugin_data_from_api() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		check_ajax_referer( 'stm_actions_plugin_info', 'security' );

		self::load_wp();

		$response = plugins_api( 'plugin_information', [ 'slug' => sanitize_text_field( $_GET['plugin_slug'] ) ] );

		if ( is_wp_error( $response ) ) {
			wp_send_json( $response->errors );
		}

		unset( $response->sections );

		if ( ! empty( $response->author ) && empty( $plug_data['author'] ) ) {
			$plug_data['author'] = str_replace( '">', '" target="_blank">', $response->author );
		}
		if ( ! empty( $response->version ) && empty( $plug_data['version'] ) ) {
			$plug_data['version'] = $response->version;
		}
		if ( ! empty( $response->download_link ) && empty( $plug_data['download_link'] ) ) {
			$plug_data['download_link'] = $response->download_link;
		}

		wp_send_json( $plug_data );
	}

	public static function stm_add_own_package_url( $transient ) {
		$themePlugins = self::$themePlugins;

		if ( ! empty( $transient ) && property_exists( $transient, 'response' ) ) {
			foreach ( $transient->response as $slug => $plugin ) {
				if ( isset( $themePlugins[ $plugin->slug ] ) ) {
					$currPlugin = $themePlugins[ $plugin->slug ];

					$transient->response[ $slug ]->slug   = $currPlugin['slug'];
					$transient->response[ $slug ]->plugin = $slug;
					if ( empty( $transient->response[ $slug ]->new_version ) && ! empty( $currPlugin['version'] ) ) {
						$transient->response[ $slug ]->new_version = $currPlugin['version'];
					}
					if ( empty( $transient->response[ $slug ]->package ) && ! empty( $currPlugin['source'] ) ) {
						$transient->response[ $slug ]->package = $currPlugin['source'];
					}
					if ( empty( $transient->response[ $slug ]->url ) && ! empty( $currPlugin['external_url'] ) ) {
						$transient->response[ $slug ]->url = $currPlugin['external_url'];
					}
				}
			}
		}

		return $transient;
	}

	public static function add_core_plugins_notify_item() {

		if ( empty( self::$plugData['core'] ) ) {
			return;
		}

		$corePlugins = array();

		foreach ( self::$plugData['core'] as $plug ) {
			if(is_plugin_inactive($plug['file_path'])) $corePlugins[] = $plug['name'];
		}

		$init_data = array(
			'notice_type'          => STM_THEME_SLUG . '-notice',
			'notice_logo'          => STM_THEME_SLUG . '.svg',
			'notice_title'         => 'The following required plugins are currently inactive: ' . implode( ', ', $corePlugins ),
			'notice_btn_one'       => admin_url( 'admin.php?page=stm-admin-plugins#core' ),
			'notice_btn_one_title' => 'Begin activation plugins',
		);

		if ( function_exists( 'stm_admin_notices_init' ) && count( $corePlugins ) > 0 ) {
			stm_admin_notices_init( $init_data );
		}
	}
}
