<?php

class STMHandlerNotification
{
	private static $instances = [];

	protected function __construct() {
		add_action( 'wp_ajax_stm_notification_status', array( $this, 'updateNoticeAction' ) );
	}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}

	public function updateNoticeAction() {
		check_ajax_referer( 'anp_nonce', 'security');

		if ( isset( $_POST['notice_id'] ) && isset( $_POST['notice_status'] ) ) {
			$notification_data = $this->getNotificationData('notification_data');
			$notice_id         = $_POST['notice_id'];
			$impressions       = $notification_data[$notice_id]['impressions'] ?? 0;

			$notification_data[sanitize_key( $notice_id )]['notice_status'] = sanitize_text_field( $_POST['notice_status'] );
			$notification_data[$notice_id]['last_shown_time']               = time();
			$notification_data[$notice_id]['impressions']                   = $impressions + 1;
			$notification_data[$notice_id]['status_click']                  = 'clicked';

			update_option( 'notification_data', $notification_data, false );
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

	public static function getInstance(): STMHandlerNotification
	{
		$cls = static::class;
		if (!isset(self::$instances[$cls])) {
			self::$instances[$cls] = new static();
		}
		return self::$instances[$cls];
	}

}
