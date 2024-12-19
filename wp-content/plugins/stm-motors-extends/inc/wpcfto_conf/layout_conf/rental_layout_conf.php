<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {
		$stm_me_wpcfto_pages_list = stm_me_wpcfto_pages_list();
		$conf                     = array(
			'name'   => esc_html__( 'Rental Layout Settings', 'stm_motors_extends' ),
			'fields' =>
				array(
					'rental_datepick'              =>
						array(
							'label'       => esc_html__( 'Reservation Date Page', 'stm_motors_extends' ),
							'type'        => 'select',
							'options'     => $stm_me_wpcfto_pages_list,
							'description' => 'Choose page for reservation date',
						),
					'order_received'               =>
						array(
							'label'       => esc_html__( 'Order Received Endpoint Page', 'stm_motors_extends' ),
							'type'        => 'select',
							'options'     => $stm_me_wpcfto_pages_list,
							'description' => 'Choose a page to display content from, on order received endpoint.',
						),
					'enable_fixed_price_for_days'  =>
						array(
							'label' => esc_html__( 'Enable Fixed Price for Quantity Days', 'stm_motors_extends' ),
							'type'  => 'checkbox',
						),
					'discount_program_desc'        =>
						array(
							'label'      => esc_html__( 'Popup Discount Program Description', 'stm_motors_extends' ),
							'type'       => 'textarea',
							'dependency' => array(
								'key'   => 'enable_fixed_price_for_days',
								'value' => 'empty',
							),
						),
					'working_hours'       =>
						array(
							'label'      => esc_html__( 'Working hours', 'stm_motors_extends' ),
							'type'       => 'text',
							'description' => 'Set working hours. example: 9-18',
						),
					'enable_office_location_fee'   =>
						array(
							'label' => esc_html__( 'Return Location Fee', 'stm_motors_extends' ),
							'type'  => 'checkbox',
						),
					'enable_fee_for_same_location' =>
						array(
							'label'      => esc_html__( 'Fee for the Same Pickup and Return Location', 'stm_motors_extends' ),
							'type'       => 'checkbox',
							'dependency' => array(
								'key'   => 'enable_office_location_fee',
								'value' => 'not_empty',
							),
							'description' => esc_html__( 'When this setting is on, the fee will be added even if the return location is the same as the pickup one.', 'stm_motors_extends' ),
						),
					'enable_car_option_office' =>
						array(
							'label'      => esc_html__( 'Show Car options by pickup location', 'stm_motors_extends' ),
							'type'       => 'checkbox',
							'description' => esc_html__( 'Show car options based on the selected pickup location when this setting is enabled.', 'stm_motors_extends' ),
						),
				),
		);

		$global_conf['rental_layout_settings'] = $conf;

		return $global_conf;
	},
	27,
	1
);
