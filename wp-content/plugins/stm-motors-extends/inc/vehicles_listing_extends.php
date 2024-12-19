<?php
add_filter(
	'stm_listings_page_options_group_3',
	function ( $options ) {
		$options = array_merge(
			$options,
			array(
				'use_on_car_modern_filter' => array(
					'label'       => esc_html__( 'Use on car modern filter', 'stm_motors_extends' ),
					'description' => esc_html__( 'Check if you want to see this category in modern filter', 'stm_motors_extends' ),
					'value'       => '',
					'preview'     => 'modern.png',
					'type'        => 'checkbox',
				),
				'use_in_footer_search'     => array(
					'label'   => esc_html__( 'Use in footer search', 'stm_motors_extends' ),
					'value'   => '',
					'preview' => 'footer.jpg',
					'type'    => 'checkbox',
				),
			)
		);

		return $options;
	}
);

if ( get_option( 'stm_motors_chosen_template', false ) === 'motorcycle' ) {
	add_filter(
		'stm_listings_page_options_group_3',
		function ( $options ) {
			$options = array_merge(
				$options,
				array(
					'use_on_tabs' => array(
						'label'       => esc_html__( 'Use in tabs', 'stm_motors_extends' ),
						'description' => esc_html__( 'Check if you want to see this in Archive Page and Single Motorcycle Page', 'stm_motors_extends' ),
						'value'       => '',
						'preview'     => 'tabs.jpg',
						'type'        => 'checkbox',
					),
				)
			);

			return $options;
		}
	);
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	add_filter(
		'stm_listings_page_options_group_4',
		function ( $options ) {
			$options = array_merge(
				$options,
				array(
					'use_on_map_page' => array(
						'label'       => esc_html__( 'Use on map page', 'stm_motors_extends' ),
						'description' => esc_html__( 'Check if you want to see this category on map page', 'stm_motors_extends' ),
						'value'       => '',
						'preview_url' => STM_MOTORS_EXTENDS_URL . '/assets/images/',
						'preview'     => 'map_infowindow.png',
						'type'        => 'checkbox',
					),
				)
			);

			return $options;
		}
	);
}

if ( true === apply_filters( 'stm_is_aircrafts', false ) ) {
	add_filter(
		'stm_listings_page_options_group_4',
		function ( $options ) {
			$options = array_merge(
				$options,
				array(
					'use_on_single_header_search' => array(
						'label'       => esc_html__( 'Use on Single Listing Header Search', 'stm_motors_extends' ),
						'value'       => '',
						'preview_url' => STM_MOTORS_EXTENDS_URL . '/assets/images/',
						'preview'     => 'header_search.jpg',
						'type'        => 'checkbox',
					),
				)
			);

			return $options;
		}
	);
}

if ( in_array( get_option( 'stm_motors_chosen_template', '' ), array( 'car_dealer_elementor', 'car_dealer_two_elementor', 'car_dealer_elementor_rtl' ), true ) ) {
	add_action(
		'listing_settings_register_section',
		function ( $manager ) {
			$manager->register_section(
				'motors_listing_info',
				array(
					'label' => esc_html__( 'Specifications', 'stm_motors_extends' ),
					'icon'  => 'fa fa-th-list',
				)
			);
		}
	);

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

add_action(
	'listing_settings_register_controls',
	function ( $manager ) {
		if ( true === apply_filters( 'stm_is_aircrafts', false ) ) {
			if ( isset( $manager->controls['vin_number'] ) ) {
				unset( $manager->controls['vin_number'] );
			}
			if ( isset( $manager->controls['city_mpg'] ) ) {
				unset( $manager->controls['city_mpg'] );
			}
			if ( isset( $manager->controls['highway_mpg'] ) ) {
				unset( $manager->controls['highway_mpg'] );
			}
		} else {
			$manager->register_control(
				'vin_number',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'preview' => 'vin',
					'label'   => esc_html__( 'VIN number', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);

			$manager->register_control(
				'city_mpg',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'City MPG', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
					'preview' => 'mpg',
				)
			);

			$manager->register_control(
				'highway_mpg',
				array(
					'type'    => 'text',
					'section' => 'stm_options',
					'label'   => esc_html__( 'Highway MPG', 'stm_motors_extends' ),
					'attr'    => array( 'class' => 'widefat' ),
					'preview' => 'mpg',
				)
			);
		}
	}
);

add_action(
	'add_classified_fields',
	function ( $manager ) {
		if ( true === apply_filters( 'stm_is_aircrafts', false ) ) {
			if ( isset( $manager->controls['vin_number'] ) ) {
				unset( $manager->controls['vin_number'] );
			}
			if ( isset( $manager->controls['city_mpg'] ) ) {
				unset( $manager->controls['city_mpg'] );
			}
			if ( isset( $manager->controls['highway_mpg'] ) ) {
				unset( $manager->controls['highway_mpg'] );
			}
		}
	}
);

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

add_action(
	'special_tab_options',
	function ( $manager ) {
		$manager->register_control(
			'special_text',
			array(
				'type'    => 'text',
				'section' => 'special_offers',
				'preview' => 'special-txt',
				'label'   => esc_html__( 'Special offer text', 'stm_motors_extends' ),
				'attr'    => array(
					'class'      => 'widefat',
					'data-dep'   => 'special_car',
					'data-value' => 'true',
				),
			)
		);

		$manager->register_control(
			'special_image',
			array(
				'type'        => 'image',
				'section'     => 'special_offers',
				'preview'     => 'special-bnr',
				'label'       => 'Special Offer Banner',
				'description' => esc_html__( 'Banner will appear instead of listing image under \'special offers carousel\' module.', 'stm_motors_extends' ),
				'size'        => 'thumbnail',
				'attr'        => array(
					'data-dep'   => 'special_car',
					'data-value' => 'true',
				),
			)
		);
	},
	10,
	1
);

add_filter(
	'motors_plugin_setting_classified_show',
	function ( $show ) {
		if ( ! apply_filters( 'is_listing', array() ) ) {
			return false;
		}

		return $show;
	}
);

add_filter(
	'motors_vl_demo_dependency',
	function ( $dependency ) {
		return ( apply_filters( 'is_listing', array() ) || apply_filters( 'stm_is_boats', false ) || apply_filters( 'stm_is_car_dealer', false ) || apply_filters( 'stm_is_motorcycle', false ) );
	}
);
