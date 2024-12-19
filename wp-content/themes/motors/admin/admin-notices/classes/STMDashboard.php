<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * STM PLugin Notices class
 */
class STMDashboard {

	public $json_file_url = 'https://promo-dashboard.stylemixthemes.com/wp-content/dashboard-promo/';
	public $json_file_path;
	public $product_name ;
	public $json_data;

	/**
	 * Initializa building of admin notice
	 *
	 * @param array $plugin_data - data related to plugin.
	 * @return void
	 */
	public function __construct( $plugin_data ) {
		$this->product_name = $plugin_data['plugin_name'];

		$this->json_file_path = $this->get_prefix_product();
		$this->get_notice_data_from_json();

		add_action( 'admin_notices', array( $this, 'get_dashboard_popup' ) );

		STMHandler::getInstance();
		$this->add_notices_all();
	}

	public function add_notices_all() {
		if ( ! empty( $this->json_data ) ) {
			foreach ( $this->json_data['notices'] as $notice ) {
				$post_logo = $notice['product_logo'] ?? '';

				$post_logo_class = substr( $post_logo, 0, strrpos( $post_logo, '.' ) );
				$type_notices    = $notice['post_terms']['type_notices'][0]['slug'] ?? '';
				$notice_id       = $notice['post_id'] ?? '';

				$notices_data = $this->getNotificationData( 'notices_data' );
				$notice_status     = $notices_data[$notice_id]['notice_status'] ?? '';

				if ( $type_notices === 'notice' && $notice_status !== 'not-show-again' ) {
					$last_shown_time = $notices_data[$notice_id]['last_shown_time'] ?? 0;
					$impressions     = $notices_data[$notice_id]['impressions'] ?? 0;
					$current_time    = time();
					$next_show_time  = $last_shown_time + $this->intervalImpressions( $notice['interval_days'], $notice['interval_hours'], $notice['interval_minutes'] );
					$status_click    = $notices_data[$notice_id]['status_click'] ?? '';
					$status_views    = $notices_data[$notice_id]['status_views'] ?? '';
					if ( $current_time >= $next_show_time && $impressions < $notice['impressions_post'] ) {

						$init_data = array(
							'notice_id'            => 'notice_' . $notice_id,
							'id'                   => $notice_id,
							'status_click'         => $status_click,
							'status_views'         => $status_views,
							'notice_type'          => 'notice is-dismissible stm-notice stm-notice-' . $post_logo_class,
							'notice_logo'          => $post_logo,
							'notice_title'         => $notice['post_title'],
							'notice_desc'          => $notice['post_content'],
							'notice_btn_one'       => esc_url( $notice['button_url_post'] ),
							'notice_btn_one_title' => $notice['button_text_post'],
							'notice_btn_one_class' => 'notice-show-again',
						);
						stm_admin_notices_init( $init_data );
					}
				}
			}
		}
	}

	public function get_prefix_product () {
		if( ! empty( $this->product_name ) ) {
			return $this->json_file_url . $this->product_name . '_posts.json';
		}
	}

	public function get_notice_data_from_json() {
		if( empty( $this->json_data ) ) $this->json_data = array();
		if ( ! empty( $this->json_file_path ) ) {
			$transient_name  = ( ! empty( $this->product_name ) ) ? $this->product_name . '_notices' : 'product_notices';
			$this->json_data = get_transient( $transient_name );

			if ( false === $this->json_data || get_option('_transient_timeout_' . $transient_name ) < current_time( 'timestamp' ) ) {
				$json_response   = wp_remote_get( $this->json_file_path );
				$this->json_data = array();
				$json_data       = json_decode( wp_remote_retrieve_body( $json_response ), true );

				if ( ! empty( $json_data ) ) {
					$this->json_data = array_merge( $this->json_data, $json_data );
				}

				set_transient( $transient_name, $this->json_data, HOUR_IN_SECONDS * 12 );
			}
		}
	}

	public function get_dashboard_popup() {
		if ( ! empty( $this->json_data ) ) {
			foreach ( $this->json_data['notices'] as $notice ) {
				$type_category = $notice['post_terms']['type_category'][0]['slug'] ?? '';
				if ( $type_category === 'promo' ) {
					extract( $notice );
					$post_id           = $notice['post_id'];
					$notices_data      = $this->getNotificationData( 'notices_data' );
					$popup_data        = $this->getNotificationData( 'popup_data' );
					$post_status       = $notices_data[$post_id]['notice_status'] ?? '';

					$notice_cl_vi['status_click'] = $notices_data[$post_id]['status_click'] ?? '';
					$notice_cl_vi['status_views'] = $popup_data[$post_id]['status_views'] ?? '';
					extract( $notice_cl_vi );

					if ( $post_status !== 'not-show-again' ) {
						$last_shown_time = $notices_data[$post_id]['last_shown_time'] ?? 0;
						$impressions     = $notices_data[$post_id]['impressions'] ?? 0;
						$current_time    = time();
						$next_show_time  = $last_shown_time + $this->intervalImpressions( $notice['interval_days'], $notice['interval_hours'], $notice['interval_minutes'] );

						if ( $current_time >= $next_show_time && $impressions < $notice['impressions_post'] ) {

							$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] )
								    : ( isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] )
									: ( isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '' ) );

							$product_page = '';
							$where_show   = $this->json_data['pages'];
							if ( is_array( $where_show ) && in_array( $page, $where_show ) ) {
								$product_page = $where_show[$page] ?? '';
							} else if ( is_string( $where_show ) ) {
								$product_page = $where_show;
							} else {
								return;
							}

							$is_page_match      = isset( $_GET['page'] ) && str_contains( $_GET['page'], $product_page );
							$is_post_type_match = isset( $_GET['post_type'] ) && str_contains( $_GET['post_type'], $product_page );
							$is_taxonomy_match  = isset( $_GET['taxonomy'] ) && str_contains( $_GET['taxonomy'], $product_page );

							if ( $is_page_match || $is_post_type_match || $is_taxonomy_match ) {
								if ( file_exists( STM_ADMIN_NOTICES_PATH . '/templates/dashboard-popup.php' ) ) {
									require_once STM_ADMIN_NOTICES_PATH . '/templates/dashboard-popup.php';
								}
								$popup_data                           = $this->getNotificationData( 'popup_data' );
								$popup_data[$post_id]['status_views'] = 'viewed';
								update_option( 'popup_data', $popup_data, false );
							}
						}
					}
				}
			}
		}
	}

	public function getNotificationData( $key ) {
		$option_value = get_option( $key );
		if ( is_array( $option_value ) ) {
			return $option_value;
		} else {
			return array();
		}
	}

	public function intervalImpressions( $days, $hours, $minutes ) {
		$seconds_per_day    = 24 * 60 * 60;
		$seconds_per_hour   = 60 * 60;
		$seconds_per_minute = 60;

		return ( intval ( $days ) * $seconds_per_day ) + ( intval( $hours ) * $seconds_per_hour ) + ( intval( $minutes ) * $seconds_per_minute );
	}
}
