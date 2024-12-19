<?php

Class STM_Theme_Support
{
	private static $genDevAccessBtnText = 'Generate Access Enabled';
	private static $enableDevAccessBtnText = 'Developer Access Enabled';
	private static $genDevAccessBtnDesc = 'Generates admin access to the website.';
	private static $enableDevAccessBtnDesc = 'Click here to review the access link';
	
	public static function init()
	{
		if ( is_admin() ) {
			add_action( 'wp_ajax_stm_generate_developer_access', [ self::class, 'generate_developer_access' ] );
			add_action( 'wp_ajax_stm_revoke_developer_access', [ self::class, 'revoke_developer_access' ] );
		}
		
		add_action( 'init', [ self::class, 'check_dev_access_query' ], 99999 );
	}
	
	public static function generate_developer_access()
	{
		check_ajax_referer( 'stm_action_developer_access', 'security' );
		
		if ( !self::get_developer_access() ) {
			$token = substr( md5( mt_rand() ), 0, 34 );
			
			set_transient( 'stm_developer_access_token', $token );
		}
		
		wp_send_json( array( 'access_url' => self::get_developer_access_link(), 'btn_text' => self::$enableDevAccessBtnText, 'btn_desc' => self::$enableDevAccessBtnDesc ) );
	}
	
	public static function revoke_developer_access()
	{
		check_ajax_referer( 'stm_action_developer_access', 'security' );
		
		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json( array( 'error' => 'No access', 'code' => 401 ) );
		}
		
		if ( delete_transient( 'stm_developer_access_token' ) ) {
			wp_send_json( array( 'btn_text' => self::$genDevAccessBtnText, 'btn_desc' => self::$genDevAccessBtnDesc ) );
		}
		
		wp_send_json( array( 'error' => '403 Forbidden', 'code' => 403 ) );
	}
	
	public static function get_developer_access()
	{
		return get_transient( 'stm_developer_access_token' );
	}
	
	public static function get_developer_access_link()
	{
		if ( !current_user_can( 'manage_options' ) || !self::get_developer_access() ) {
			return;
		}
		
		$admins = get_users( array( 'fields' => 'role', 'role' => 'administrator' ) );
		
		$link = add_query_arg( array( 'dev_access_token' => self::get_developer_access(), 'dev_access_id' => $admins[0] ), admin_url() );
		
		return $link;
	}
	
	public static function check_dev_access_query()
	{
		if ( isset( $_GET['dev_access_token'] ) && isset( $_GET['dev_access_id'] ) ) {
			$token = sanitize_text_field( $_GET['dev_access_token'] );
			$accessId = abs( $_GET['dev_access_id'] );
			
			self::signin_developer_access( $token, $accessId );
		}
	}
	
	public static function signin_developer_access( $token, $accessId )
	{
		if ( self::get_developer_access() === $token ) {
			if ( $user = get_user_by( 'id', $accessId ) ) {
				wp_clear_auth_cookie();
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				
				if ( is_user_logged_in() ) {
					wp_safe_redirect( admin_url() );
					exit;
				} else {
					die;
				}
			}
		}
	}
}