<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Socials', 'stm_motors_extends' ),
			'fields' =>
				array(
					'socials_link' =>
						array(
							'label'   => esc_html__( 'Socials Links', 'stm_motors_extends' ),
							'type'    => 'multi_input',
							'options' => stm_me_wpcfto_kv_socials(),
						),
				),
		);

		$global_conf['socials'] = $conf;

		return $global_conf;
	},
	55,
	1
);
