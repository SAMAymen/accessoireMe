<?php
new STM_ME_Patcher();

class STM_ME_Patcher {
	private static $current_layout = '';

	private static $updates = array(
		'1.0.2' => array(
			'copyright_url',
			'remove_stm_links',
		),
	);

	public function __construct() {
		self::$current_layout = get_option( 'stm_motors_chosen_template' );

		add_action( 'init', array( self::class, 'init_patcher' ), 100, 1 );
	}

	public static function init_patcher() {
		if ( version_compare( get_option( 'motors_extends_version', '1.9.8' ), STM_MOTORS_EXTENDS_PLUGIN_VERSION, '<' ) ) {
			self::update_version();
		}
	}

	public static function get_updates() {
		return self::$updates;
	}

	public static function needs_to_update() {
		$current_db_version = get_option( 'motors_extends_db_version' );
		$update_versions    = array_keys( self::get_updates() );
		usort( $update_versions, 'version_compare' );

		return ! is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
	}

	private static function maybe_update_db_version() {
		if ( self::needs_to_update() ) {
			$current_db_version = get_option( 'motors_extends_db_version', '1.0.0' );
			$updates            = self::get_updates();

			foreach ( $updates as $version => $callback_arr ) {
				if ( version_compare( $current_db_version, $version, '<' ) ) {
					foreach ( $callback_arr as $callback ) {
						call_user_func( array( self::class, $callback ) );
					}
				}
			}
		}

		update_option( 'motors_extends_db_version', STM_MOTORS_EXTENDS_DB_VERSION, true );
	}

	public static function update_version() {
		update_option( 'motors_extends_version', STM_MOTORS_EXTENDS_PLUGIN_VERSION, true );
		self::maybe_update_db_version();
	}

	private static function copyright_url() {
		$layout         = get_option( 'stm_motors_chosen_template' );
		$theme_settings = get_option( "wpcfto_motors_{$layout}_settings" );
		$patterns       = array(
			'Motors - Electric Vehicle Dealer WordPress Theme'                                            => '<a href=\"https://stylemixthemes.com/motors/\" target=\"_blank\">Motors</a> Electric Vehicle Dealer WordPress Theme by <a href=\"https://stylemixthemes.com/\" target="_blank">StylemixThemes</a>.<br>Trademarks and brands are the property of their respective owners.',
			'Motors Auto Dealer Theme Trademarks and brands are the property of their respective owners.' => '<a href=\"https://stylemixthemes.com/motors/\" target=\"_blank\">Motors</a> Theme for WordPress by <a href=\"https://stylemixthemes.com/\" target="_blank">StylemixThemes</a>.<br>Trademarks and brands are the property of their respective owners.',
			'Motors – WordPress Theme by StylemixThemes'                                                  => '<a href=\"https://stylemixthemes.com/motors/\" target=\"_blank\">Motors</a> Theme for WordPress by <a href=\"https://stylemixthemes.com/\" target="_blank">StylemixThemes</a>',
		);

		foreach ( $patterns as $pattern_key => $pattern ) {
			if ( false !== strpos( html_entity_decode( wp_strip_all_tags( $theme_settings['footer_copyright_text'] ) ), $pattern_key ) ) {
				$theme_settings['footer_copyright_text'] = $pattern;
			}
		}

		update_option( "wpcfto_motors_{$layout}_settings", $theme_settings, false );
	}

	private static function remove_stm_links() {
		$page_titles = array(
			'Front page'      => 'page',
			'Home page three' => 'page',
			'Home'            => 'page',
			'Home page two'   => 'page',
			'Pricing'         => 'page',
		);

		foreach ( $page_titles as $title => $post_type ) {
			self::update_content( $post_type, $title );
		}
	}

	private static function update_content( $post_type, $title ) {
		$searches    = array(
			'motors.stylemixthemes.com',
		);
		$args        = array(
			'post_type'   => $post_type,
			'title'       => $title,
			'post_status' => 'publish',
		);
		$page_object = current( get_posts( $args ) );

		if ( $page_object ) {
			$page_content = $page_object->post_content;
			$page_id      = $page_object->ID;

			foreach ( $searches as $search ) {
				if ( false !== strpos( $page_content, $search ) ) {
					$new_content = str_replace( $search, '/', $page_content );

					global $wpdb;

					$wpdb->update(
						$wpdb->posts,
						array(
							'post_content' => $new_content,
						),
						array(
							'ID' => $page_id,
						),
						array(
							'%s',
						),
						array(
							'%d',
						)
					);
				}
			}
		}
	}
}
