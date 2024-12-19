<?php

class STM_Theme_Admin_Pages extends STM_Theme_Admin_Templates {

	public static function init() {
		add_action( 'admin_enqueue_scripts', [ self::class, 'load_admin_styles' ] );
		add_action( 'admin_menu', [ self::class, 'register_pages' ] );
	}

	public static function load_admin_styles() {
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/admin/assets/js/isotope.pkgd.min.js', 'jQuery', null, true );
		wp_enqueue_style( 'stm-startup_icons_css', get_template_directory_uri() . '/admin/assets/css/stm-admin-icon.css', null, STM_ADMIN_VERSION, 'all' );
		wp_enqueue_style( 'stm-startup_css', get_template_directory_uri() . '/admin/assets/css/style.css', null, STM_ADMIN_VERSION, 'all' );
		wp_register_script( 'stm-admin', get_template_directory_uri() . '/admin/assets/js/stm-admin.js', 'jQuery', null, true );
		wp_localize_script( 'stm-admin', 'stm_ajax_dashboard', array(
			'stm_install_plugin'          => wp_create_nonce( 'stm_install_plugin' ),
			'stm_actions_plugin'          => wp_create_nonce( 'stm_actions_plugin' ),
			'stm_actions_plugin_info'     => wp_create_nonce( 'stm_actions_plugin_info' ),
			'stm_action_developer_access' => wp_create_nonce( 'stm_action_developer_access' ),

		) );

		wp_enqueue_script( 'stm-admin' );
	}

	public static function localize_vars() {

		$localizeVars = array(
			'stm_install_plugin'      => wp_create_nonce( 'stm_install_plugin' ),
			'stm_actions_plugin'      => wp_create_nonce( 'stm_actions_plugin' ),
			'stm_actions_plugin_info' => wp_create_nonce( 'stm_actions_plugin_info' ),
		);

		return $localizeVars;
	}

	public static function register_pages() {
		$theme                = STM_Theme_Info::get_theme_info();
		$theme_name           = $theme['name'];
		$theme_name_sanitized = 'stm-admin';

		/**
		 * Item Registration
		 */
		add_menu_page(
			$theme_name,
			$theme_name,
			'manage_options',
			$theme_name_sanitized,
			[ self::class, 'startup' ],
			get_template_directory_uri() . '/assets/admin/images/icon.png',
			'2.11'
		);

		/**
		 * Demo Import
		 */
		add_submenu_page(
			$theme_name_sanitized,
			'Demo import',
			'Demo import',
			'manage_options',
			$theme_name_sanitized . '-demos',
			[ self::class, 'install_demo' ]
		);

		/**
		 * Plugins
		 */
		add_submenu_page(
			$theme_name_sanitized,
			'Plugins',
			'Plugins',
			'manage_options',
			$theme_name_sanitized . '-plugins',
			[ self::class, 'plugins' ]
		);

		/**
		 * System status
		 */
		add_submenu_page(
			$theme_name_sanitized,
			'System status',
			'System status',
			'manage_options',
			$theme_name_sanitized . '-system-status',
			[ self::class, 'system_status' ]
		);
	}

	public static function get_admin_tabs( $screen = 'welcome' ) {
		$theme_name_sanitized = 'stm-admin';
		$screen               = $screen ?: $theme_name_sanitized;
		?>
		<?php if ( $notice = get_site_transient( 'stm_auth_notice' ) ) : ?>
			<div class="stm-admin-message"><strong>Activation
					alert:</strong> <?php echo sanitize_text_field( $notice ); ?></div><br>
		<?php endif; ?>
		<div class="stm-nav-tab-wrapper">
			<a href="<?php echo ( 'welcome' === $screen ) ? '#' : esc_url_raw( admin_url( 'admin.php?page=' . $theme_name_sanitized ) ); ?>"
			   class="<?php echo ( 'welcome' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab">Dashboard</a>
			<a href="<?php echo ( 'demos' === $screen ) ? '#' : esc_url_raw( admin_url( 'admin.php?page=' . $theme_name_sanitized . '-demos' ) ); ?>"
			   class="<?php echo ( 'demos' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab">Install Demos</a>
			<a href="<?php echo ( 'plugins' === $screen ) ? '#' : esc_url_raw( admin_url( 'admin.php?page=' . $theme_name_sanitized . '-plugins' ) ); ?>"
			   class="<?php echo ( 'plugins' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab">Plugins</a>
			<a href="<?php echo ( 'system-status' === $screen ) ? '#' : esc_url_raw( admin_url( 'admin.php?page=' . $theme_name_sanitized . '-system-status' ) ); ?>"
			   class="system-status-tab-nav <?php echo ( 'system-status' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab <?php if ( ! empty( get_transient( 'system_status_notification' ) ) ) {
				   echo 'has_error';
			   } ?>">System Status</a>
			<?php if ( ! apply_filters( 'dashboard_hide_theme_options', false ) ) : ?>
			<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=' . STM_THEME_SETTINGS_URL ) ); ?>"
			   class="nav-tab">Theme Options</a>
			<?php endif; ?>
		</div>
		<?php
	}

}