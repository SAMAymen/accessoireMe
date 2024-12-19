<?php
if ( true === apply_filters( 'stm_is_ev_dealer', false ) ) {
	add_action(
		'listing_settings_register_controls',
		function ( $manager ) {
			$manager->register_control(
				'home_charge_time',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'Home charge time', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);

			$manager->register_control(
				'fast_charge_time',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'Fast charge time', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);
		}
	);
}

if ( apply_filters( 'stm_is_layout', 'car_dealer_elementor' ) || apply_filters( 'stm_is_layout', 'car_dealer_two_elementor' ) || apply_filters( 'stm_is_layout', 'car_dealer_elementor_rtl' ) ) {
	add_action(
		'listing_settings_register_controls_end',
		function ( $manager ) {
			$manager->register_control(
				'listing_specifications',
				array(
					'type'        => 'repeater-info',
					'section'     => 'motors_listing_info',
					'label'       => esc_html__( 'Specifications', 'stm_motors_extends' ),
					'description' => __( 'Learn more about the Listing Manager Specification <a href="" target="_blank">here</a>', 'stm_motors_extends' ),
					'preview'     => 'specifications',
					'attr'        => array(
						'class' => 'widefat',
					),
				)
			);
		}
	);
}

if ( apply_filters( 'stm_is_equipment', false ) ) {
	add_action(
		'listing_price_register_control',
		function ( $manager ) {
			/*Price*/
			$manager->register_control(
				'rent_price',
				array(
					'type'    => 'number',
					'section' => 'stm_price',
					'label'   => esc_html__( 'Rent Price', 'stm_motors_extends' ),
					'preview' => '',
					'attr'    => array(
						'class' => 'widefat',
					),
				)
			);

			$manager->register_control(
				'sale_rent_price',
				array(
					'type'    => 'number',
					'section' => 'stm_price',
					'preview' => '',
					'label'   => esc_html__( 'Sale Rent Price', 'stm_motors_extends' ),
					'attr'    => array(
						'class' => 'widefat',
					),
				)
			);

			$manager->register_control(
				'stm_genuine_rent_price',
				array(
					'type'    => 'hidden',
					'section' => 'stm_price',
					'preview' => '',
					'label'   => esc_html__( 'Genuine Rent Price', 'stm_motors_extends' ),
					'attr'    => array(
						'class' => 'widefat',
					),
				)
			);

			$manager->register_setting( 'rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );

			$manager->register_setting( 'sale_rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );

			$manager->register_setting( 'stm_genuine_rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );
		}
	);
}

add_action(
	'listing_stock_number_register_control',
	function ( $manager ) {
		if ( true === apply_filters( 'stm_is_aircrafts', false ) ) {
			if ( isset( $manager->controls['stock_number'] ) ) {
				unset( $manager->controls['stock_number'] );
			}
			$manager->register_control(
				'serial_number',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'Serial number', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);
			$manager->register_control(
				'registration_number',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'Registration number', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);
		} else {
			$manager->register_control(
				'stock_number',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'preview' => 'stockid',
					'label'   => esc_html__( 'Stock number', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);
		}
	}
);
