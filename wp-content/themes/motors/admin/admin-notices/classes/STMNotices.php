<?php // phpcs:ignore

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly.

/**
 * STM PLugin Notices class
 */
class STMNotices {

	/**
	 * Initializa building of admin notice
	 *
	 * @param array $plugin_data - data related to plugin.
	 * @return void
	 */
	public static function init( $plugin_data ) {

		if ( ! isset( $plugin_data['notice_title'] ) || ! isset( $plugin_data['notice_logo'] ) ) {
			return;
		}

		add_filter(
			'stm_admin_notices_data',
			function ( $notices ) use ( $plugin_data ) {
				if (isset( $plugin_data['notice_id'] ) ) {
					$notices[$plugin_data['notice_id']] = $plugin_data;
				} else {
					$notices[] = $plugin_data;
				}

				return $notices;
			}
		);

		add_action( 'admin_notices', array( self::class, 'stm_admin_notices' ) );

		add_action( 'wp_ajax_stm_discard_admin_notice', array( self::class, 'discard_admin_notice' ) );
		add_action( 'add_admin_notice', array( self::class, 'build_notice' ) );
	}

	/**
	 * Admin notices
	 *
	 * @return void
	 */
	public static function stm_admin_notices() {

		$notice_data = apply_filters( 'stm_admin_notices_data', array() );
		foreach ( $notice_data as $data ) {
			self::build_notice( $data );
			$notices_data = self::getNotificationData( 'notices_data' );
			if ( isset( $data['id'] ) ) {
				$notices_data[$data['id']]['status_views'] = 'viewed';
				update_option( 'notices_data', $notices_data, false );
			}
		}
	}

	/**
	 * Discard Admin notices
	 *
	 * @return void
	 */
	public static function discard_admin_notice() {
		if ( isset( $_POST['pluginName'] ) ) {
			$plugin_name = sanitize_text_field( $_POST['pluginName'] );
			set_transient( 'stm_' . $plugin_name . '_notice_setting', 1, 0 );
		}
	}

	/**
	 * Builds admin notice
	 *
	 * @param array $plugin_data - data related to plugin.
	 * @return void
	 */
	public static function build_notice( $plugin_data ) {

		$btn_one_class      = ( ! empty( $plugin_data['notice_btn_one_class'] ) ) ? ' ' . $plugin_data['notice_btn_one_class'] : '';
		$btn_one_update     = ( ! empty( $plugin_data['notice_btn_one_update'] ) ) ? ' ' . $plugin_data['notice_btn_one_update'] : '';
		$btn_two_class      = ( ! empty( $plugin_data['notice_btn_two_class'] ) ) ? ' ' . $plugin_data['notice_btn_two_class'] : '';
		$btn_three_class    = ( ! empty( $plugin_data['notice_btn_three_class'] ) ) ? ' ' . $plugin_data['notice_btn_three_class'] : '';
		$btn_one_attrs      = ( ! empty( $plugin_data['notice_btn_one_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_one_attrs'] : '';
		$btn_two_attrs      = ( ! empty( $plugin_data['notice_btn_two_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_two_attrs'] : '';
		$btn_three_attrs    = ( ! empty( $plugin_data['notice_btn_three_attrs'] ) ) ? ' ' . $plugin_data['notice_btn_three_attrs'] : '';
		$status_click       = ( ! empty( $plugin_data['status_click'] ) ? $plugin_data['status_click'] : '');
		$status_views       = ( ! empty( $plugin_data['status_views'] ) ? $plugin_data['status_views'] : '');
		$notice_id          = ( ! empty( $plugin_data['id'] ) ? $plugin_data['id'] : '');
		$logo_exists        = file_exists( STM_ADMIN_NOTICES_PATH . '/assets/img/' . esc_attr( $plugin_data['notice_logo'] ) );
		$post_logo_class    = ! empty( $logo_exists ) ? $plugin_data['notice_type'] : '';
		$dependency_plugins = $plugin_data['dependency_plugins'] ?? [];
		$dependency_themes  = $plugin_data['dependency_themes'] ?? [];
		$error_message      = ( ! empty( $plugin_data['notice_error_message'] ) ) ? $plugin_data['notice_error_message'] : '';

		$html  = '<div class="notice is-dismissible stm-notice stm-notice-' . esc_attr( $post_logo_class ) . '" data-status-click="'. esc_attr( $status_click ) .'" data-id="'. esc_attr( $notice_id ) .'" data-status-views="'. esc_attr( $status_views ) .'">';
		$html .= ! empty( $logo_exists ) ? '<div class="img"><img src="' . STM_ADMIN_NOTICES_URL . 'assets/img/' . esc_attr( $plugin_data['notice_logo'] ) . '" /></div>' : '';
		$html .= '<div class="text-wrap">';
		$html .= '<h4>' . wp_kses_post( $plugin_data['notice_title'] ) . '</h4>';
		$html .= ( ! empty( $plugin_data['notice_desc'] ) ) ? '<h5>' . wp_kses_post( $plugin_data['notice_desc'] ) . '</h5>' : '';
		if ( ! empty( $dependency_plugins ) || ! empty( $dependency_themes ) ) {
			$html .= '<ul>';
			if ( ! empty( $dependency_plugins ) ) {
				foreach ( $dependency_plugins as $plugin_slug => $plugin_name ) {
					$html .= '<li data-plugin-slug="' . esc_attr( $plugin_slug ) .'"><span class="bulk-update-plugin-indicator"></span>' . esc_html( $plugin_name ) . '</li>';
				}
			}
			if ( ! empty( $dependency_themes ) ) {
				foreach ( $dependency_themes as $theme_slug => $theme_name ) {
					$html .= '<li data-theme-slug="' . esc_attr( $theme_slug ) .'"><span class="bulk-update-plugin-indicator"></span>' . esc_html( $theme_name ) . '</li>';
				}
			}
			$html .= '</ul>';
		}
		if ( ! empty( $error_message ) ) {
			$html .= '<div class="notices-error-message">' .  esc_attr( $error_message ) . '</div>';
		}
		$html .= '</div>';
		$html .= '<p class="notices-right"></p>';
		$html .= ( ! empty( $plugin_data['notice_btn_one_title'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_one'] ) . '" target="_blank" data-id="'. esc_attr( $notice_id ) .'" data-updating="' . esc_attr( $btn_one_update ) . '" data-original-text="' . esc_html( $plugin_data['notice_btn_one_title'] ) . '" class="button btn-first' . esc_attr( $btn_one_class ) . '" ' . esc_attr( $btn_one_attrs ) . '>' . esc_html( $plugin_data['notice_btn_one_title'] ) . '</a>' : '';
		$html .= ( ! empty( $plugin_data['notice_btn_two'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_two'] ) . '" class="button btn-second' . esc_attr( $btn_two_class ) . '" ' . esc_attr( $btn_two_attrs ) . '>' . esc_html( $plugin_data['notice_btn_two_title'] ) . '</a>' : '';
		$html .= ( ! empty( $plugin_data['notice_btn_three'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_three'] ) . '" class="button btn-second' . esc_attr( $btn_three_class ) . '" ' . esc_attr( $btn_three_attrs ) . '>' . esc_html( $plugin_data['notice_btn_three_title'] ) . '</a>' : '';
		$html .= '</div>';

		echo wp_kses_post( $html );
	}

	public static function getNotificationData( $key ) {
		$option_value = get_option( $key );
		if ( is_array( $option_value ) ) {
			return $option_value;
		} else {
			return array();
		}
	}
}
