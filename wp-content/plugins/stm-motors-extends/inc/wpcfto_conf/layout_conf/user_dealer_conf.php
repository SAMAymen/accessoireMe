<?php
add_filter(
	'mvl_user_dealer_options',
	function ( $conf ) {
		$user_conf = array(
			'user_sidebar'          =>
				array(
					'label'       => esc_html__( 'Default User Sidebar', 'stm_motors_extends' ),
					'type'        => 'select',
					'description' => 'Choose page for value user sidebar',
					'options'     => stm_me_wpcfto_sidebars(),
					'submenu'     => esc_html__( 'User', 'stm_motors_extends' ),
				),
			'user_sidebar_position' =>
				array(
					'label'   => esc_html__( 'User Sidebar Position', 'stm_motors_extends' ),
					'type'    => 'radio',
					'options' =>
						array(
							'left'  => 'Left',
							'right' => 'Right',
						),
					'value'   => 'right',
					'submenu' => esc_html__( 'User', 'stm_motors_extends' ),
				),
		);

		return array_merge( $conf, $user_conf );
	},
	9,
	1
);


add_filter(
	'mvl_user_dealer_options',
	function ( $global_conf ) {

		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			return $global_conf;
		}

		$conf = array(
			'dealer_sidebar'          =>
				array(
					'label'       => esc_html__( 'Default Dealer Sidebar', 'stm_motors_extends' ),
					'type'        => 'select',
					'description' => 'Choose page for value user sidebar',
					'options'     => stm_me_wpcfto_sidebars(),
					'submenu'     => esc_html__( 'Dealer', 'stm_motors_extends' ),
				),
			'dealer_sidebar_position' =>
				array(
					'label'      => esc_html__( 'Dealer Sidebar Position', 'stm_motors_extends' ),
					'type'       => 'radio',
					'options'    =>
						array(
							'left'  => 'Left',
							'right' => 'Right',
						),
					'value'      => 'right',
					'submenu'    => esc_html__( 'Dealer', 'stm_motors_extends' ),
				),
		);

		return array_merge( $global_conf, $conf );
	},
	40,
	1
);
