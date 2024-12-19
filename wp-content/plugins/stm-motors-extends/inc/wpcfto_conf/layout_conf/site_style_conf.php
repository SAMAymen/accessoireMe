<?php
add_filter(
	'motors_wpcfto_general_start_config',
	function ( $global_conf ) {

		$site_styles = array(
			'site_style_default' => 'Default',
			'site_style_custom'  => 'Custom Colors',
		);

		$site_style_layouts = array( 'car_dealer', 'listing', 'service' );

		if ( in_array( stm_me_get_current_layout(), $site_style_layouts, true ) ) {
			$site_styles = array(
				'site_style_default'    => 'Default',
				'site_style_blue'       => 'Blue',
				'site_style_light_blue' => 'Light Blue',
				'site_style_orange'     => 'Green',
				'site_style_red'        => 'Red',
				'site_style_yellow'     => 'Yellow',
				'site_style_custom'     => 'Custom Colors',
			);
		}

		if ( 'boats' === stm_me_get_current_layout() ) {
			$site_styles = array(
				'site_style_default'    => 'Default',
				'site_style_blue'       => 'Corall',
				'site_style_light_blue' => 'Turquoise',
				'site_style_orange'     => 'Green',
				'site_style_red'        => 'Red',
				'site_style_custom'     => 'Custom Colors',
			);
		}

		$conf = array(
			'header_current_layout'              => array(
				'label' => '',
				'type'  => 'stm-hidden',
				'value' => stm_me_get_current_layout(),
			),
			'site_style'                         =>
				array(
					'label'   => esc_html__( 'Theme Skin', 'stm_motors_extends' ),
					'type'    => 'select',
					'options' => $site_styles,
					'value'   => 'site_style_default',
					'group'   => 'started',
				),
			'site_style_base_color'              =>
				array(
					'label'      => esc_html__( 'Custom Base Car Dealer Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'value'      => '#cc6119',
					'dependency' => array(
						'key'   => 'site_style',
						'value' => 'site_style_custom',
					),
				),
			'site_style_secondary_color'         =>
				array(
					'label'      => esc_html__( 'Custom Secondary Car Dealer Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'value'      => '#6c98e1',
					'dependency' => array(
						'key'   => 'site_style',
						'value' => 'site_style_custom',
					),
				),
			'site_style_base_color_listing'      =>
				array(
					'label'        => esc_html__( 'Custom Base Listing Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'value'        => '#1bc744',
					'dependency'   => array(
						array(
							'key'   => 'site_style',
							'value' => 'site_style_custom',
						),
						array(
							'key'   => 'header_current_layout',
							'value' => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||car_magazine',
						),
					),
					'dependencies' => '&&',
				),
			'site_style_secondary_color_listing' =>
				array(
					'label'        => esc_html__( 'Custom Secondary Listing Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'value'        => '#153e4d',
					'group'        => 'ended',
					'dependency'   => array(
						array(
							'key'   => 'site_style',
							'value' => 'site_style_custom',
						),
						array(
							'key'   => 'header_current_layout',
							'value' => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||car_magazine',
						),
					),
					'dependencies' => '&&',
				),
			'site_bg_color'                      =>
				array(
					'label'  => esc_html__( 'Site Background Color', 'stm_motors_extends' ),
					'type'   => 'color',
					'value'  => false,
					'mode'   => 'background-color',
					'output' => 'body',
				),
			'site_boxed'                         =>
				array(
					'label' => esc_html__( 'Enable Boxed Layout', 'stm_motors_extends' ),
					'type'  => 'checkbox',
					'value' => false,
					'group' => 'started',
				),
			'bg_image'                           =>
				array(
					'label'      => esc_html__( 'Background Image', 'stm_motors_extends' ),
					'type'       => 'image_select',
					'width'      => 50,
					'height'     => 50,
					'dependency' => array(
						'key'   => 'site_boxed',
						'value' => 'not_empty',
					),
					'options'    =>
						array(
							'/assets/images/tmp/box_img_5_preview.png' => array(
								'alt' => '',
								'img' => get_template_directory_uri() . '/assets/images/tmp/box_img_5_preview.png',
							),
							'/assets/images/tmp/box_img_1_preview.png' => array(
								'alt' => '',
								'img' => get_template_directory_uri() . '/assets/images/tmp/box_img_1_preview.png',
							),
							'/assets/images/tmp/box_img_2_preview.png' => array(
								'alt' => '',
								'img' => get_template_directory_uri() . '/assets/images/tmp/box_img_2_preview.png',
							),
							'/assets/images/tmp/box_img_3_preview.jpg' => array(
								'alt' => '',
								'img' => get_template_directory_uri() . '/assets/images/tmp/box_img_3_preview.jpg',
							),
							'/assets/images/tmp/box_img_4_preview.jpg' => array(
								'alt' => '',
								'img' => get_template_directory_uri() . '/assets/images/tmp/box_img_4_preview.jpg',
							),
						),
				),
			'custom_bg_image'                    =>
				array(
					'label'      => esc_html__( 'Custom Background Image', 'stm_motors_extends' ),
					'type'       => 'image',
					'dependency' => array(
						'key'   => 'site_boxed',
						'value' => 'not_empty',
					),
					'group'      => 'ended',
				),
			'enable_preloader'                   =>
				array(
					'label' => esc_html__( 'Enable Preloader', 'stm_motors_extends' ),
					'type'  => 'checkbox',
					'value' => false,
				),
			'preloader_timer'                    =>
				array(
					'label'       => esc_html__( 'Preloader timer', 'stm_motors_extends' ),
					'type'        => 'text',
					'value'       => '1000',
					'dependency'  => array(
						'key'   => 'enable_preloader',
						'value' => 'not_empty',
					),
					'description' => esc_html__( 'Define time to turn off the preloader; if the field is empty, it will be turned off after loading the page.', 'stm_motors_extends' ),
					'placeholder' => 'Enter timer in milliseconds',
				),
			'smooth_scroll'                      =>
				array(
					'label' => esc_html__( 'Site smooth scroll', 'stm_motors_extends' ),
					'type'  => 'checkbox',
					'value' => false,
				),
			'inventory_layout'                   =>
				array(
					'label'      => esc_html__( 'Inventory Layout Mode', 'stm_motors_extends' ),
					'type'       => 'radio',
					'options'    =>
						array(
							'light' => 'Light',
							'dark'  => 'Dark',
						),
					'value'      => 'dark',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'car_dealer_two||car_dealer_two_elementor',
						'section' => 'general_tab',
					),
				),
		);

		return $conf;
	}
);



