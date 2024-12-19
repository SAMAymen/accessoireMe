<?php
add_filter(
	'motors_wpcfto_header_end_config',
	function ( $conf ) {
		$config = array(
			'logo'             =>
				array(
					'label'      => esc_html__( 'Logo', 'stm_motors_extends' ),
					'type'       => 'image',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'auto_parts||aircrafts||boats||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||rental_two||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||motorcycle||service',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
				),
			'logo_width'       =>
				array(
					'label'       => esc_html__( 'Logo Width (px)', 'stm_motors_extends' ),
					'description' => esc_html__( 'The height of the image will increase proportionately', 'stm_motors_extends' ),
					'type'        => 'number',
					'submenu'     => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
				),
			'logo_margin_top'  =>
				array(
					'label'      => esc_html__( 'Logo Margin', 'stm_motors_extends' ),
					'type'       => 'spacing',
					'units'      => array( 'px', 'em' ),
					'value'      => array(
						'top'    => '17',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
						'unit'   => 'px',
					),
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'auto_parts||aircrafts||boats||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||motorcycle||service',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
				),
			'logo_font_family' =>
				array(
					'label'       => esc_html__( 'Text Logo Font Family', 'stm_motors_extends' ),
					'type'        => 'typography',
					'description' => 'If you dont have logo, you can customize your brand name',
					'output'      => '#header .blogname h1',
					'submenu'     => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
				),
		);

		return array_merge( $conf, $config );
	},
	10,
	1
);
