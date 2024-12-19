<?php

class NotificationInit {
	public static function init( $init_data ) {

		if ( ! class_exists( 'RateNotification' ) ) {
			require_once __DIR__ . '/RateNotification.php';
		}

		if ( ! class_exists( 'ApiNotifications' ) ) {
			require_once  __DIR__ . '/ApiNotifications.php';
		}

		if ( ! class_exists( 'STMHandlerNotification' ) ) {
			require_once __DIR__ . '/STMHandlerNotification.php';
		}

		RateNotification::init( $init_data );
		ApiNotifications::init( $init_data );
	}
}
