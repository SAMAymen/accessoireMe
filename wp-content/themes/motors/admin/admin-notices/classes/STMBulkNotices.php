<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class STMBulkNotices
 */
class STMBulkNotices {

	private $bulk_data;
	private $theme_name;

	public function __construct( $bulk_data, $theme_name = '' ) {
		$this->bulk_data = $bulk_data;
		$this->theme_name = $theme_name;

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_notices_bulk_update_enqueue_admin' ) );
		add_action( 'wp_ajax_admin_notices_update_plugins', array( $this, 'admin_notices_update_plugins' ), 100 );
		add_action( 'wp_ajax_admin_notices_update_themes', array( $this, 'admin_notices_update_themes' ) );
		wp_update_plugins();
	}

	/**
	 * Enqueues scripts and initializes bulk update admin notices.
	 * Loads necessary scripts, merges plugin dependencies, checks for updates,
	 * and initializes admin notices if updates are available.
	 */
	public function admin_notices_bulk_update_enqueue_admin() {
		wp_enqueue_script( 'bulk-update', STM_ADMIN_NOTICES_URL . 'assets/js/bulk-update.js', array( 'jquery' ), '1.0', true );

		$themes_plugins_to_update = $this->get_plugins_to_update_for_theme( $this->theme_name );

		// Merge the dependency plugins and the plugins to update from theme dependencies.
		$this->bulk_data['dependency_plugins'] = array_merge( $this->bulk_data['dependency_plugins'], $themes_plugins_to_update );

		$plugin_update_status = $this->admin_notices_check_plugin_updates( $this->bulk_data['dependency_plugins'] );
		$theme_update_status  = $this->admin_notices_check_theme_updates();

		if ( $plugin_update_status['has_updates'] || ! empty( $theme_update_status['themes'] ) ) {
			$this->bulk_data['dependency_plugins'] = $plugin_update_status['plugins'];
			$this->bulk_data['dependency_themes']  = $theme_update_status['themes'];
			stm_admin_notices_init( $this->bulk_data );
		}

		wp_localize_script(
			'bulk-update',
			'adminNotices',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'admin_notices_nonce' ),
				'plugins'       => $this->bulk_data['dependency_plugins'] ?? [],
				'themes'        => $this->bulk_data['dependency_themes'] ?? [],
			)
		);
	}

	/**
	 * Retrieves plugins that require updates based on the active theme.
	 * Loads STM_TGM_Plugins class if not already loaded, fetches plugins data,
	 * and filters out plugins that are required and have updates available.
	 *
	 * @param string $theme_name The name of the theme to check for plugin updates.
	 * @return array Array of plugins that are required to be updated for the specified theme.
	 */
	public function get_plugins_to_update_for_theme( $theme_name ) {
		$plugin_path = plugin_dir_path( __FILE__ ) . 'includes/STM_TGM_Plugins.php';

		if ( ! class_exists( 'STM_TGM_Plugins' ) ) {
			if ( file_exists( $plugin_path ) ) {
				require_once $plugin_path;
			}
		}

		$plugins_to_update = array();

		if ( $theme_name === wp_get_theme()->get( 'Name' ) ) {
			$current_demo   = apply_filters( 'stm_theme_demo_layout', '' );
			$plugins_data   = STM_TGM_Plugins::get_plugins_data( $current_demo );

			foreach ( $plugins_data['all'] as $plugin ) {
				if ( isset( $plugin['required'] ) && $plugin['required'] && isset( $plugin['has_update'] ) && $plugin['has_update'] ) {
					$plugins_to_update[ $plugin['slug'] ] = $plugin['name'];
				}
			}
		}

		return $plugins_to_update;
	}

	/**
	 * General function to handle updates for plugins or themes.
	 * 
	 * @param string $type       Type of items to update ('plugin' or 'theme').
	 * @param string $items      Name of the POST parameter containing items to update.
	 * @param string $capability User capability required for the update.
	 * @param string $nonce      Nonce for security verification.
	 */
	private function admin_notices_update_items( $type, $items, $capability ) {
		check_ajax_referer( 'admin_notices_nonce', 'nonce' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( 'Insufficient rights to update ' . $type . 's' );
		}

		$items = isset( $_POST[ $items ] ) && is_array( $_POST[ $items ] ) ? wp_unslash( $_POST[ $items ] ) : array();

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		if ( 'plugin' === $type ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$upgrader = new Plugin_Upgrader();
			$update_transient = 'update_plugins';
		} else {
			include_once ABSPATH . 'wp-admin/includes/theme.php';
			$upgrader = new Theme_Upgrader();
			$update_transient = 'update_themes';
		}

		$original_update_items = get_site_transient( $update_transient );
		$custom_transient      = get_site_transient( 'custom_' . $update_transient );

		if ( false === $custom_transient ) {
			set_site_transient( 'custom_' . $update_transient, $original_update_items, 1 * HOUR_IN_SECONDS );
			$custom_transient = $original_update_items;
		}

		$results = array();

		$plugin_slug = array_keys( $items )[0];
		$path        = ( 'plugin' === $type ) ? $plugin_slug . '/' . $plugin_slug . '.php' : $plugin_slug;

		if ( ! isset( $original_update_items->response[ $path ] ) ) {
			if ( isset( $custom_transient->response[ $path ] ) ) {
				$original_update_items->response[ $path ] = $custom_transient->response[ $path ];
				set_site_transient( $update_transient, $original_update_items );
			} else {
				$results[ $plugin_slug ] = 'Item not found in update lists';
				wp_send_json_success( $results );
			}
		}

		$result = $upgrader->upgrade( $path, array( 'clear_update_cache' => false ) );

		if ( is_wp_error( $result ) ) {
			$results[ $plugin_slug ] = ucfirst( $type ) . ' Update failed: ' . $result->get_error_message();
		} elseif ( is_null( $result ) ) {
			$results[ $plugin_slug ] = false;
		} else {
			if ( 'plugin' === $type ) {
				$activation_result = activate_plugin( $path );
				if ( is_wp_error( $activation_result ) ) {
					$results[ $plugin_slug ] = ucfirst( $type ) . ' Updated but activation failed: ' . $activation_result->get_error_message();
				} else {
					$results[ $plugin_slug ] = true;
				}
			} else {
				$results[ $plugin_slug ] = true;
			}
		}

		wp_send_json_success( $results );
	}

	/**
	 * AJAX handler to update plugins based on received plugin slugs from the client-side.
	 */
	public function admin_notices_update_plugins() {
		$this->admin_notices_update_items( 'plugin', 'plugins', 'update_plugins' );
	}

	/**
	 * AJAX handler to update themes based on received theme slugs from the client-side.
	 */
	public function admin_notices_update_themes() {
		$this->admin_notices_update_items( 'theme', 'themes', 'update_themes' );
	}

	/**
	 * Check for active plugins and those requiring updates
	 *
	 * @param array $dependency_plugins Array of dependency plugins with plugin slugs as keys and plugin names as values.
	 *
	 * @return array Array containing boolean and list of plugins that are active and require updates.
	 */
	public function admin_notices_check_plugin_updates( $dependency_plugins ) {
		$update_plugins = get_site_transient( 'update_plugins' );

		$plugins_to_update = array_filter(
			$dependency_plugins,
			function ( $plugin_name, $plugin_slug ) use ( $update_plugins ) {
				return is_plugin_active( $plugin_slug . '/' . $plugin_slug . '.php' )
					&& isset( $update_plugins->response[ $plugin_slug . '/' . $plugin_slug . '.php'] );
			},
			ARRAY_FILTER_USE_BOTH
		);

		return array(
			'has_updates' => ! empty( $plugins_to_update ),
			'plugins'     => $plugins_to_update,
		);
	}

	/**
	 * Check for active themes and those requiring updates from Envato Market.
	 *
	 * Retrieves the list of active themes from Envato Market transient data,
	 * compares their current versions with the versions available on the market,
	 * and identifies themes that require updates.
	 *
	 * @return array Array containing themes that require updates, keyed by theme slug.
	 */
	public function admin_notices_check_theme_updates() {
		$envato_market_themes = get_option( '_site_transient_envato_market_themes' );
		$themes_to_update     = array();

		if ( isset( $envato_market_themes['active'] ) && is_array( $envato_market_themes['active'] ) ) {
			foreach ( $envato_market_themes['active'] as $theme_slug => $theme_data ) {
				$current_theme   = wp_get_theme( $theme_slug );
				$current_version = $current_theme->get( 'Version' );
				$market_version  = $theme_data['version'];

				if ( version_compare( $current_version, $market_version, '<' ) ) {
					$themes_to_update[$theme_slug] = $theme_data['name'];
				}
			}
		}

		return array(
			'themes' => $themes_to_update
		);
	}
}
