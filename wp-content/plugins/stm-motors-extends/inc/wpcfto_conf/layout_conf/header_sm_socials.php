<?php
add_filter(
	'motors_wpcfto_header_end_config',
	function ( $conf ) {
		$config = array(
			'header_socials_enable' =>
				array(
					'label'      => esc_html__( 'Header Socials', 'stm_motors_extends' ),
					'type'       => 'multi_checkbox',
					'options'    => stm_me_wpcfto_socials(),
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_magazine||listing_four||listing_four_elementor||motorcycle',
					),
					'submenu'    => esc_html__( 'Socials', 'stm_motors_extends' ),
				),
			'soc_empty_notice'      =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_motors_extends' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'ev_dealer||car_dealer_two||car_dealer_two_elementor||aircrafts||boats||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_five||car_rental||car_rental_elementor||service',
					),
					'submenu'    => esc_html__( 'Socials', 'stm_motors_extends' ),
				),
		);

		return array_merge( $conf, $config );
	},
	30,
	1
);
