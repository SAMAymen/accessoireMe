<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Service Layout', 'stm_motors_extends' ),
			'fields' =>
				array(
					'service_header_label' =>
						array(
							'label' => esc_html__( 'Header Button label', 'stm_motors_extends' ),
							'type'  => 'text',
							'value' => 'Make an Appointment',
						),
					'service_header_link'  =>
						array(
							'label' => esc_html__( 'Header Button Link', 'stm_motors_extends' ),
							'type'  => 'text',
							'value' => '#appointment-form',
						),
				),
		);

		$global_conf['service_layout'] = $conf;

		return $global_conf;
	},
	26,
	1
);
