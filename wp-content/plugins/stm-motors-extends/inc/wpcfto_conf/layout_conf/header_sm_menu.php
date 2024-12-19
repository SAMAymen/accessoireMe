<?php
add_filter(
	'motors_wpcfto_header_end_config',
	function ( $conf ) {
		$font_settings_output = '
			body #wrapper .main-menu .header-menu > li > a,
			#wrapper #header .header-menu > li > a,
			#wrapper #header .listing-menu > li > a,
			#wrapper #header .header-listing .listing-menu > li > a,
			#wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a,
			#wrapper #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a,
			#wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu > li > a,
			.stm-layout-header-listing_five .header-menu > li > a,
			.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a,
			.stm-template-rental_two #wrapper .header-main .header-menu > li > a,
			body .stm-boats-mobile-menu .listing-menu li a
		';

		$font_family_output = '
			.main-menu .header-menu > li:not(.stm_megamenu) .sub-menu a,
			.header-menu > li:not(.stm_megamenu) .sub-menu a,
			.listing-menu > li:not(.stm_megamenu) .sub-menu a,
			.header-listing .listing-menu  > li:not(.stm_megamenu) .sub-menu a,
			.header-inner-content .listing-service-right .listing-menu > li:not(.stm_megamenu) .sub-menu a,
			#stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li:not(.stm_megamenu) .sub-menu a,
			.header-magazine .container .magazine-service-right ul.magazine-menu > li:not(.stm_megamenu) .sub-menu a,
			.stm-layout-header-listing #wrapper #header .header-listing .listing-menu > li:not(.stm_megamenu) .sub-menu a,
			.stm-layout-header-listing_five .header-menu > li:not(.stm_megamenu) .sub-menu a,
			.stm-layout-header-aircrafts .header-inner-content .listing-service-right .listing-menu > li:not(.stm_megamenu) .sub-menu a,
			.stm-layout-header-motorcycle .stm_motorcycle-header .stm_mc-nav .main-menu .inner .header-menu > li:not(.stm_megamenu) > .sub-menu li a,
			.stm-layout-header-car_rental #wrapper #header .header-rental .stm-opened-menu-listing .listing-menu-mobile > li:not(.stm_megamenu) .sub-menu li a,
			.stm-template-rental_two #wrapper .header-main .header-menu > li:not(.stm_megamenu) .sub-menu li a,
			body .stm-boats-mobile-menu .listing-menu > li:not(.stm_megamenu) .sub-menu li a
		';

		$item_padding_output = '
			#wrapper #header .main-menu .header-menu > li > a,
			#wrapper #header .header-listing .listing-menu > li > a,
			#wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu li > a,
			.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a,
			.stm-layout-header-boats #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a,
			.stm-template-rental_two .main-menu .header-menu > li > a,
			.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a,
			#header .header-service .header-menu > li > a,
			.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper ul.header-menu li a
		';

		$hover_background_output = '
			body #wrapper .main-menu .header-menu > li.current-menu-item > a,
			body #wrapper .main-menu .header-menu > li.current_page_item > a,
			body #wrapper .main-menu .header-menu li:hover > a,
			.stm-layout-header-listing #wrapper #header .header-listing .listing-menu > li > a,
			.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a,
			#wrapper #header .header-service .header-menu li a:hover,
			body #wrapper #header .header-main .stm-header-right .main-menu .header-menu > li:hover > a,
			.stm-template-rental_two #wrapper .header-main .header-menu li:hover a,
			.stm-template-rental_two #wrapper .header-main .header-menu > li:not(.stm_megamenu) .sub-menu li:hover > a,
		';

		$hover_text_color_output = '
			body #wrapper #header .main-menu .header-menu > li.current-menu-item > a,
			body #wrapper #header .main-menu .header-menu > li.current_page_item > a,
			body #wrapper .main-menu .header-menu li:hover > a,
			body #wrapper .main-menu .header-menu > li:hover > a,
			body #wrapper #header .main-menu .header-menu > li:hover > a,
			.stm-layout-header-listing #wrapper #header .header-listing .listing-menu > li > a:hover,
			.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a:hover,
			.stm-layout-header-car_magazine #wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu > li > a:hover,
			.stm-template-rental_two #wrapper .header-main .header-menu li:hover a,
			.stm-template-rental_two #wrapper .header-main .header-menu > li:not(.stm_megamenu) .sub-menu li:hover > a,
			#wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a:hover,
			#wrapper #header .header-service .header-menu li a:hover,
			body #header .header-main .stm-header-right .main-menu .header-menu > li:hover > a,
			#wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a:hover,
			.stm-template-auto_parts #wrapper #header .header-menu > li > a:hover,
			.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper ul.header-menu li .sub-menu li > a:hover
		';

		$config = array(
			'typography_main_menu_font_settings' =>
				array(
					'label'           => esc_html__( 'Main Menu Font Settings', 'stm_motors_extends' ),
					'type'            => 'typography',
					'output'          => $font_settings_output,
					'submenu'         => esc_html__( 'Menu', 'stm_motors_extends' ),
					'style_important' => true,
				),
			'typography_menu_font_family'        =>
				array(
					'label'           => esc_html__( 'SubMenu Font Family', 'stm_motors_extends' ),
					'type'            => 'typography',
					'output'          => $font_family_output,
					'submenu'         => esc_html__( 'Menu', 'stm_motors_extends' ),
					'excluded'        => array(
						'font-size',
						'font-weight',
						'font-style',
						'google-weight',
						'subset',
						'text-align',
						'word-spacing',
						'letter-spacing',
						'line-height',
						'text-transform',
					),
					'style_important' => true,
				),
			'menu_icon_top_margin'               =>
				array(
					'label'      => esc_html__( 'Menu&amp;Icons Area Margin', 'stm_motors_extends' ),
					'type'       => 'spacing',
					'units'      => array( 'px', 'em' ),
					'value'      => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
						'unit'   => 'px',
					),
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'aircrafts||boats||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six',
					),
					'submenu'    => esc_html__( 'Main', 'stm_motors_extends' ),
				),
			'menu_top_margin'                    =>
				array(
					'label'      => esc_html__( 'Margin', 'stm_motors_extends' ),
					'type'       => 'spacing',
					'units'      => array( 'px', 'em' ),
					'value'      => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
						'unit'   => 'px',
					),
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'aircrafts||boats||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||service',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_item_padding'                   =>
				array(
					'label'   => esc_html__( 'Item Padding', 'stm_motors_extends' ),
					'type'    => 'spacing',
					'mode'    => 'padding',
					'output'  => $item_padding_output,
					'units'   => array( 'px', 'em' ),
					'value'   => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
						'unit'   => 'px',
					),
					'submenu' => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_background_color'               =>
				array(
					'label'       => esc_html__( 'Background Color', 'stm_motors_extends' ),
					'description' => esc_html__( 'Does not work when the Transparent Header option for the page is enabled', 'stm_motors_extends' ),
					'type'        => 'color',
					'mode'        => 'background-color',
					'output'      => '
					#header-nav-holder .header-nav.header-nav-default,
					#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner .header-menu,
					#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner:before,
					#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner:after,
					.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper,
				',
					'value'       => '#eaedf0',
					'dependency'  => array(
						'key'   => 'header_layout',
						'value' => 'auto_parts||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||motorcycle',
					),
					'submenu'     => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_item_bg_color'                  =>
				array(
					'label'      => esc_html__( 'Item Background Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'background-color',
					'output'     => '
					body #wrapper .main-menu .header-menu > li.current-menu-item > a,
					body #wrapper .main-menu .header-menu > li > a,
					body #header .header-main .stm-header-right .main-menu .header-menu > li.current-menu-item > a,
				',
					'value'      => '#eaedf0',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||motorcycle||listing_five',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_hover_bg_color'                 =>
				array(
					'label'        => esc_html__( 'Item Hover Background Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'mode'         => 'background-color',
					'output'       => $hover_background_output,
					'dependency'   => array(
						array(
							'key'   => 'header_layout',
							'value' => 'ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||motorcycle||listing_four||listing_four_elementor||listing_five',
						),
						array(
							'key'     => 'header_current_layout',
							'value'   => 'service',
							'section' => 'general_tab',
						),
						array(
							'key'     => 'header_current_layout',
							'value'   => 'rental_two',
							'section' => 'general_tab',
						),
					),
					'dependencies' => '||',
					'submenu'      => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_hover_active_bg_color'          =>
				array(
					'label'        => esc_html__( 'Item Hover and Active Background Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'mode'         => 'background-color',
					'output'       => '
					.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper ul.header-menu li.current-menu-item,
					.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper ul.header-menu li:hover,
					.stm-template-auto_parts #header .header-wrapper .header-menu-wrapper ul.header-menu li .sub-menu li:hover
					',
					'dependency'   => array(
						array(
							'key'   => 'header_layout',
							'value' => 'auto_parts',
						),
					),
					'dependencies' => '||',
					'submenu'      => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_hover_text_color'               =>
				array(
					'label'           => esc_html__( 'Item Hover Text Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => $hover_text_color_output,
					'dependency'      => array(
						array(
							'key'   => 'header_layout',
							'value' => 'auto_parts||aircrafts||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_magazine||car_rental||car_rental_elementor||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_six||motorcycle||service',
						),
						array(
							'key'     => 'header_current_layout',
							'value'   => 'service',
							'section' => 'general_tab',
						),
						array(
							'key'     => 'header_current_layout',
							'value'   => 'rental_two',
							'section' => 'general_tab',
						),
					),
					'dependencies'    => '||',
					'submenu'         => esc_html__( 'Menu', 'stm_motors_extends' ),
					'style_important' => true,
				),
			'hma_underline'                      =>
				array(
					'label'      => esc_html__( 'Item Text Decoration Underline Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'border-bottom',
					'output'     => '
					.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li.current-menu-item > a,
					.stm-layout-header-car_magazine #wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu > li.current-menu-item > a
				',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'aircrafts',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_underline_2'                    =>
				array(
					'label'      => esc_html__( 'Item Text Decoration Underline Color 2', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'background-color',
					'output'     => '
					.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a:after,
					.stm-layout-header-boats #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a:after,
					.stm-layout-header-equipment #header .header-listing .header-inner-content .listing-service-right .listing-menu > li > a:after
				 ',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'car_dealer_two||car_dealer_two_elementor||boats||equipment',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_hover_underline'                =>
				array(
					'label'      => esc_html__( 'Item Hover Text Decoration Underline Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'border-bottom',
					'output'     => '
					.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a:hover,
					.stm-layout-header-aircrafts #wrapper #header .header-inner-content .listing-service-right .listing-menu > li.current-menu-item a:hover,
					.stm-layout-header-car_magazine #wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu > li > a:hover
				',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'aircrafts',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
			'hma_hover_underline_2'              =>
				array(
					'label'      => esc_html__( 'Item Hover Text Decoration Underline Color 2', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'background-color',
					'output'     => '
					.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a:hover:after,
					 .stm-layout-header-boats #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a:hover:after,
					 .stm-layout-header-equipment #header .header-listing .header-inner-content .listing-service-right .listing-menu > li > a:hover:after
					 ',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'car_dealer_two||car_dealer_two_elementor||boats||equipment',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_motors_extends' ),
				),
		);

		return array_merge( $conf, $config );
	},
	20,
	1
);
