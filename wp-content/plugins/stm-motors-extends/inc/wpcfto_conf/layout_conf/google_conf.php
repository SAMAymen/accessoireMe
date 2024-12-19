<?php
add_filter(
	'motors_wpcfto_general_start_config',
	function ( $global_conf ) {
		$google_conf = array(
			'google_api_key'       =>
				array(
					'label'       => esc_html__( 'Google Maps API Key', 'stm_motors_extends' ),
					'type'        => 'text',
					'description' => 'Enable Google Maps Service. To obtain the keys please visit: <a href="https://cloud.google.com/maps-platform/">here</a>',
					'group'       => 'started',
				),
			'google_pin'           =>
				array(
					'label'       => esc_html__( 'Google Maps Pin Image', 'stm_motors_extends' ),
					'type'        => 'image',
					'description' => 'To change the Pin image in Google map, please remove the default image first through the page builder.',
					'group'       => 'ended',
				),
			'enable_recaptcha'     =>
				array(
					'label'       => esc_html__( 'reCaptcha (v3)', 'stm_motors_extends' ),
					'type'        => 'checkbox',
					'description' => 'Enable Google reCaptcha. To obtain the keys please visit: <a href="https://www.google.com/recaptcha/admin">here</a>',
					'group'       => 'started',
				),
			'recaptcha_public_key' =>
				array(
					'label'      => esc_html__( 'Public key', 'stm_motors_extends' ),
					'type'       => 'text',
					'dependency' => array(
						'key'   => 'enable_recaptcha',
						'value' => 'not_empty',
					),
				),
			'recaptcha_secret_key' =>
				array(
					'label'      => esc_html__( 'Secret key', 'stm_motors_extends' ),
					'type'       => 'text',
					'group'      => 'ended',
					'dependency' => array(
						'key'   => 'enable_recaptcha',
						'value' => 'not_empty',
					),
				),

		);

		return array_merge( $global_conf, $google_conf );
	},
	10,
	1
);
