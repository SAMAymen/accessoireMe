<?php

Class STM_Theme_System_Status
{
	
	public static $notification = false;
	
	private static $srMySql = '5.6';
	private static $srPHPv = '7.4';
	private static $srPostMaxSize = '64 MB';
	private static $srMemoryLimit = '256 MB';
	private static $srTimeLimit = '300';
	private static $srMaxInputVars = '2004';
	private static $srMaxUploadSize = '64 MB';
	
	public static function init()
	{
	}
	
	private static function map_wp_env()
	{
		$upload_dir = wp_upload_dir();
		
		$mapWPEnv = [
			'home_url' => [
				'title' => 'Home Url',
				'recommend' => '',
				'system' => get_home_url(),
			],
			'site_url' => [
				'title' => 'Site Url',
				'recommend' => '',
				'system' => get_site_url()
			],
			'wp_version' => [
				'title' => 'WP Version',
				'recommend' => self::get_upgrade_version(),
				'system' => self::get_compare_wp_version()
			],
			'mulyisite' => [
				'title' => 'WP Multisite',
				'recommend' => '',
				'system' => ( is_multisite() ) ? 'On <i class="stmadmin-icon-imported-check"></i>' : 'Off'
			],
			'wp_debug' => [
				'title' => 'WP Debug',
				'recommend' => '',
				'system' => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'On <i class="stmadmin-icon-imported-check"></i>' : 'Off'
			],
			'lang' => [
				'title' => 'Language',
				'recommend' => '',
				'system' => get_locale()
			],
		];
		
		return $mapWPEnv;
	}
	
	private static function map_server_env()
	{
		$mapServerEnv = array(
			'mysql_version' => [
				'title' => 'MySQL Version',
				'recommend' => self::$srMySql,
				'system' => self::get_mysql_version()
			],
			'php_version' => [
				'title' => 'PHP Version',
				'recommend' => self::$srPHPv,
				'system' => self::get_php_version()
			],
			'php_post_max_size' => [
				'title' => 'PHP Post Max Size',
				'recommend' => self::$srPostMaxSize,
				'system' => self::get_post_max_size()
			],
			'php_memory_limit' => [
				'title' => 'PHP Memory Limit',
				'recommend' => self::$srMemoryLimit,
				'system' => self::get_memory_limit()
			],
			'max_execution_time' => [
				'title' => 'PHP Time Limit',
				'recommend' => self::$srTimeLimit,
				'system' => ( function_exists( 'ini_get' ) ) ? self::get_php_time_limit_system() : ''
			],
			'max_input_vars' => [
				'title' => 'PHP Max Input Vars',
				'recommend' => self::$srMaxInputVars,
				'system' => ( function_exists( 'ini_get' ) ) ? self::get_php_max_input_vars_system() : ''
			],
			'max_upload_size' => [
				'title' => 'Max Upload Size',
				'recommend' => self::$srMaxUploadSize,
				'system' => self::get_max_upload_size()
			],
			'ziparchive' => [
				'title' => 'ZipArchive',
				'recommend' => 'enabled',
				'system' => class_exists( 'ZipArchive' ) ? 'enabled <i class="stmadmin-icon-imported-check"></i>' : '<span class="ss-error">ZipArchive is not installed on your server, but is required if you need to import demo content.</span>'
			],
			'wp_remote_get' => [
				'title' => 'WP Remote Get',
				'recommend' => 'enabled',
				'system' => self::get_wp_remote_get()
			],
		);
		
		return $mapServerEnv;
	}
	
	public static function get_wp_env()
	{
		return self::map_wp_env();
	}
	
	public static function get_server_env()
	{
		return self::map_server_env();
	}
	
	private static function get_upgrade_version()
	{
		$updTrans = get_site_transient( 'update_core' );

		return (!empty($updTrans)) ? $updTrans->updates[0]->current : "";
	}
	
	private static function get_compare_wp_version()
	{
		$new = self::get_upgrade_version();
		$current = get_bloginfo( 'version' );
		
		if ( version_compare( $new, $current, '>' ) ) {
			self::$notification = true;
			return '<span class="plug_bold">' . $current . '</span><i class="stmadmin-icon-round-error"></i>';
		} else {
			return $current . '<i class="stmadmin-icon-imported-check"></i>';
		}
	}
	
	private static function get_memory_limit()
	{
		$memory = ini_get( 'memory_limit' );
		
		if ( !$memory || -1 === $memory ) {
			$memory = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
		}
		
		if ( !is_numeric( $memory ) ) {
			$memory = wp_convert_hr_to_bytes( $memory );
		}
		
		$memoryNum = intval( $memory );
		$memory = size_format( $memory );

		return ( version_compare( $memoryNum, '268435456', '>=' ) ) ? $memory . '<i class="stmadmin-icon-imported-check"></i>' : '<span class="plug_bold">' . $memory . '</span><i class="stmadmin-icon-round-error"></i>';
	}
	
	private static function get_php_version()
	{
		$php_version = null;
		
		if ( defined( 'PHP_VERSION' ) ) {
			$php_version = str_replace( PHP_EXTRA_VERSION, '', PHP_VERSION );
		} elseif ( function_exists( 'phpversion' ) ) {
			$php_version = phpversion();
		}
		
		if ( version_compare( '7.4', $php_version, '>=' ) ) {
			self::$notification = true;
			$php_version = '<span class="plug_bold">' . $php_version . '</span><i class="stmadmin-icon-round-error"></i>';
		} else {
			$php_version .= '<i class="stmadmin-icon-imported-check"></i>';
		}
		
		return $php_version;
	}
	
	private static function get_mysql_version()
	{
		global $wpdb;
		
		return ( version_compare( $wpdb->db_version(), '5.6', '>=' ) ) ? $wpdb->db_version() . '<i class="stmadmin-icon-imported-check"></i>' : '<span class="plug_bold">' . $wpdb->db_version() . '</span><i class="stmadmin-icon-round-error"></i>';
	}
	
	private static function get_post_max_size()
	{
		
		$pms = wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) );
		$pmsNum = size_format( $pms );

		return ( function_exists( 'ini_get' ) && version_compare( $pms, '67108864', '>=' ) ) ? $pmsNum . '<i class="stmadmin-icon-imported-check"></i>' : '<span class="plug_bold">' . $pmsNum . '</span><i class="stmadmin-icon-round-error"></i>';
	}
	
	private static function get_php_max_input_vars_system()
	{
		$registered_navs = get_nav_menu_locations();
		$menu_items_count = array( '0' => '0' );
		foreach ( $registered_navs as $handle => $registered_nav ) {
			$menu = wp_get_nav_menu_object( $registered_nav );
			if ( $menu ) {
				$menu_items_count[] = $menu->count;
			}
		}
		
		$max_items = max( $menu_items_count );

		$max_input_vars = ini_get( 'max_input_vars' );

		if ( $max_input_vars < self::$srMaxInputVars) {
			self::$notification = true;
			$inputVars = '<span class="plug_bold">' . apply_filters( 'stm_theme_esc_variable', $max_input_vars ) . '</span><i class="stmadmin-icon-round-error"></i>';
		} else {
			$inputVars = $max_input_vars . '<i class="stmadmin-icon-imported-check"></i>';
		}
		
		return $inputVars;
	}
	
	private static function get_max_upload_size()
	{
		$mus = size_format( wp_max_upload_size() );

		return ( version_compare( wp_max_upload_size(), '67108864', '>=' ) ) ? $mus . '<i class="stmadmin-icon-imported-check"></i>' : '<span class="plug_bold">' . $mus . '</span><i class="stmadmin-icon-round-error"></i>';
	}
	
	private static function get_php_time_limit_system()
	{
		$time_limit = ini_get( 'max_execution_time' );
		
		$limit = '';
		
		if ( 300 > $time_limit && 0 != $time_limit ) {
			self::$notification = true;
			$limit = '<span class="plug_bold">' . $time_limit . '</span><i class="stmadmin-icon-round-error"></i>';
		} else {
			$limit = $time_limit . '<i class="stmadmin-icon-imported-check"></i>';
		}
		
		return $limit;
	}
	
	private static function get_wp_remote_get()
	{
		$response = wp_safe_remote_get(
			'https://build.envato.com/api/',
			[
				'decompress' => false,
				'user-agent' => 'test-api',
			]
		);
		
		return ( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) ? 'enabled <i class="stmadmin-icon-imported-check"></i>' : '<span class="ss-error">wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that https://build.envato.com/api/ is not blocked.</span>';
	}
	
	public static function set_notification_transient()
	{
		if ( self::$notification ) {
			set_transient( 'system_status_notification', 'isset' );
			return;
		}
		
		delete_transient( 'system_status_notification' );
	}
}