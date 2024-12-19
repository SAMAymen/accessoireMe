<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Shop', 'stm_motors_extends' ),
			'fields' =>
				array(
					'shop_sidebar'          =>
						array(
							'label'   => esc_html__( 'Choose Shop Sidebar', 'stm_motors_extends' ),
							'type'    => 'select',
							'options' => stm_me_wpcfto_sidebars(),
							'value'   => '768',
						),
					'shop_sidebar_position' =>
						array(
							'label'   => esc_html__( 'Shop Sidebar Position', 'stm_motors_extends' ),
							'type'    => 'radio',
							'options' =>
								array(
									'left'  => 'Left',
									'right' => 'Right',
								),
							'value'   => 'left',
						),
				),
		);

		$global_conf['shop'] = $conf;

		return $global_conf;
	},
	45,
	1
);
