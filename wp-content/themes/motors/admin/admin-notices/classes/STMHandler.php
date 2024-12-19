<?php

class STMHandler
{
	private static $instances = [];

	protected function __construct() {
		add_action( 'wp_ajax_stm_notice_status', array( $this, 'updateNoticeAction' ) );
	}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}

	public function updateNoticeAction() {
		check_ajax_referer('notices-nonce', 'nonce');

		if ( isset( $_POST['notice_id'] ) && isset( $_POST['notice_status'] ) ) {
			$notices_data = $this->getNotificationData('notices_data');
			$notice_id         = sanitize_text_field( $_POST['notice_id'] );
			$impressions       = $notices_data[$notice_id]['impressions'] ?? 0;

			$notices_data[$notice_id]['notice_status']   = sanitize_text_field( $_POST['notice_status'] );
			$notices_data[$notice_id]['last_shown_time'] = time();
			$notices_data[$notice_id]['impressions']     = $impressions + 1;
			$notices_data[$notice_id]['status_click']    = 'clicked';

			update_option( 'notices_data', $notices_data, false );
			return 'success';
		} else {
			return 'error';
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

	public static function getInstance(): STMHandler
	{
		$cls = static::class;
		if (!isset(self::$instances[$cls])) {
			self::$instances[$cls] = new static();
		}
		return self::$instances[$cls];
	}

}
