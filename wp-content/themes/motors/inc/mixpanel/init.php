<?php
function init_mixpanel() {
	require_once 'class-mixpanel.php';
	require_once 'class-mixpanel-general.php';

	$data_classes = array(
		'Motors\Mixpanel\Mixpanel_General',
	);

	$mixpanel = new Motors\Mixpanel\Mixpanel( $data_classes );
	$mixpanel->execute();
}

add_filter( 'cron_schedules', 'mixpanel_cron_schedule' );

function mixpanel_cron_schedule( $schedules ) {
	if ( ! isset( $schedules['weekly'] ) ) {
		$schedules['weekly'] = array(
			'interval' => DAY_IN_SECONDS,
			'display'  => __( 'Once in a day' ),
		);
	}

	return $schedules;
}

if ( ! wp_next_scheduled( 'motors_init_mixpanel_cron' ) ) {
	wp_schedule_event( time(), 'daily', 'motors_init_mixpanel_cron' );
}

add_action( 'motors_init_mixpanel_cron', 'init_mixpanel' );
