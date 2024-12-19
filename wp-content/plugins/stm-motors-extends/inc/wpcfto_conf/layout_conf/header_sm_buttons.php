<?php
add_filter(
	'motors_wpcfto_header_end_config',
	function ( $conf ) {
		$config = array(
			'header_wpml_switcher'                 =>
				array(
					'label'       => esc_html__( 'Language Switcher', 'stm_motors_extends' ),
					'type'        => 'checkbox',
					'value'       => true,
					'description' => 'WPML plugin is required',
					'dependency'  => array(
						'key'   => 'header_layout',
						'value' => 'ev_dealer',
					),
					'submenu'     => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_compare_show'                  =>
				array(
					'label'      => esc_html__( 'Show Compare', 'stm_motors_extends' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'aircrafts||boats||motorcycle||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_compare_icon'                  =>
				array(
					'label'      => esc_html__( 'Compare Icon', 'stm_motors_extends' ),
					'type'       => 'icon_picker',
					'group'      => 'ended',
					'dependency' => array(
						'key'   => 'header_compare_show',
						'value' => 'not_empty',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_cart_show'                     =>
				array(
					'label'       => esc_html__( 'Show Cart', 'stm_motors_extends' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Woocommerce needed', 'stm_motors_extends' ),
					'group'       => 'started',
					'dependency'  => array(
						'key'     => 'header_current_layout',
						'value'   => 'aircrafts||boats||motorcycle||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						'section' => 'general_tab',
					),
					'submenu'     => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_cart_icon'                     =>
				array(
					'label'      => esc_html__( 'Cart Icon', 'stm_motors_extends' ),
					'type'       => 'icon_picker',
					'group'      => 'ended',
					'dependency' => array(
						'key'   => 'header_cart_show',
						'value' => 'not_empty',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_cart_compare_hover_bg'         =>
				array(
					'label'           => esc_html__( 'Compare & Cart Buttons Hover Background Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'background-color',
					'output'          => 'body #wrapper .header-help-bar > ul > li > a:hover',
					'value'           => '',
					'dependency'      => array(
						'key'   => 'header_layout',
						'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer',
					),
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
					'style_important' => true,
				),
			'header_cart_compare_hover_text_color' =>
				array(
					'label'           => esc_html__( 'Compare & Cart Buttons Hover Text Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => 'body #wrapper .header-help-bar > ul li a:hover .list-label,
							body #wrapper .header-help-bar > ul li a:hover .list-icon,
							.stm-layout-header-listing #wrapper #header .header-listing .listing-right-actions .pull-right a:hover i,
							.stm-layout-header-ev_dealer #wrapper #header .stm-header-right .lOffer-compare:hover i,
							.stm-layout-header-ev_dealer #wrapper #header .stm-header-right .lOffer-compare:hover i,
							.stm-layout-header-ev_dealer #wrapper #header .stm-header-right .help-bar-shop a:hover i,
							.stm-layout-header-car_magazine #header .header-magazine .container .magazine-service-right .magazine-right-actions .pull-right a:hover,
							.stm-layout-header-car_magazine #header .header-magazine .container .magazine-service-right .magazine-right-actions .pull-right a:hover i,
							.stm-layout-header-equipment #wrapper #header .header-inner-content .lOffer-compare:hover i,
							#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions a:hover .heading-font,
							#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions a:hover i,
							.stm-layout-header-motorcycle #header .right-right .pull-right a:hover i,
							#header .listing-service-right .listing-right-actions .pull-right a:hover i,
							#header .listing-service-right .listing-right-actions .pull-right a:hover,
							body #wrapper #header .header-main .stm-header-right a:hover i
							',
					'style_important' => true,
					'dependency'      => array(
						array(
							'key'   => 'header_compare_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'header_cart_show',
							'value' => 'not_empty',
						),
					),
					'dependencies'    => '||',
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_show_profile'                  =>
				array(
					'label'      => esc_html__( 'Show Profile', 'stm_motors_extends' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_profile_icon'                  =>
				array(
					'label'      => esc_html__( 'Profile Icon', 'stm_motors_extends' ),
					'type'       => 'icon_picker',
					'dependency' => array(
						'key'   => 'header_show_profile',
						'value' => 'not_empty',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_profile_hover_bg'              =>
				array(
					'label'      => esc_html__( 'Profile Button Hover Background Color', 'stm_motors_extends' ),
					'type'       => 'color',
					'mode'       => 'background-color',
					'output'     => '
						 .stm-layout-header-listing #wrapper #header .header-listing .listing-right-actions .lOffer-account:hover,
						 #wrapper #stm-boats-header #header .header-inner-content .listing-right-actions .lOffer-account-unit a:hover,
						 .stm-layout-header-ev_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-template-listing.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-template-listing_two.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-template-listing_three.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-template-listing_four.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-template-listing_five.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover,
						 .stm-layout-header-motorcycle #header .stm_motorcycle-header .lOffer-account-unit .lOffer-account:hover,
						 .stm-layout-header-equipment #header .lOffer-account-unit .lOffer-account:hover,
						 .stm-layout-header-car_rental #header .is-listing .lOffer-account-unit .lOffer-account:hover,
						 .stm-layout-header-aircrafts #wrapper #header .is-listing .lOffer-account-unit .lOffer-account:hover,
						 .stm-layout-header-listing_five #wrapper #header .lOffer-account:hover
						',
					'dependency' => array(
						'key'   => 'header_show_profile',
						'value' => 'not_empty',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_profile_hover_text_color'      =>
				array(
					'label'           => esc_html__( 'Profile Button Hover Text Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => '
						.stm-layout-header-listing #wrapper #header .header-listing .listing-right-actions .pull-right .lOffer-account-unit .lOffer-account:hover i:before,
						 #wrapper #stm-boats-header #header .header-inner-content .listing-right-actions .lOffer-account-unit a:hover i,
						 .stm-layout-header-ev_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-template-listing.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-template-listing_two.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-template-listing_three.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-template-listing_four.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-template-listing_five.stm-layout-header-car_dealer .lOffer-account-unit .lOffer-account:hover i,
						 .stm-layout-header-equipment #header .lOffer-account-unit .lOffer-account:hover i,
						 .stm-layout-header-motorcycle #header .stm_motorcycle-header .lOffer-account-unit .lOffer-account:hover i,
						 .stm-layout-header-car_rental #header .is-listing .lOffer-account-unit .lOffer-account:hover i,
						 .stm-layout-header-aircrafts #wrapper #header .is-listing .lOffer-account-unit .lOffer-account:hover i,
						 .stm-layout-header-listing_five #wrapper #header .header-main .stm-header-right .lOffer-account:hover i
						 ',
					'style_important' => true,
					'dependency'      => array(
						'key'   => 'header_show_profile',
						'value' => 'not_empty',
					),
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
					'group'           => 'ended',
				),
			'header_show_add_car_button'           =>
				array(
					'label'      => esc_html__( 'Show Add a Car Button', 'stm_motors_extends' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_text'              =>
				array(
					'label'        => esc_html__( 'Label', 'stm_motors_extends' ),
					'type'         => 'text',
					'value'        => 'Add your item',
					'dependency'   => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||motorcycle',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_link'              =>
				array(
					'label'        => esc_html__( 'Link', 'stm_motors_extends' ),
					'type'         => 'text',
					'value'        => '/add-car',
					'dependency'   => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_bg_color'          =>
				array(
					'label'        => esc_html__( 'Background Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'mode'         => 'background-color',
					'output'       => '.stm-layout-header-listing #wrapper #header .header-listing .listing_add_cart > div,
								body #wrapper .header-help-bar > ul li a.listing_add_cart,
								.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .is-listing .listing_add_cart > div,
								.stm-layout-header-boats #stm-boats-header #header .is-listing .listing_add_cart > div,
								.stm-layout-header-car_rental #header .is-listing .listing_add_cart > div,
								.stm-layout-header-equipment #wrapper #header .listing_add_cart > div,
								.stm-layout-header-aircrafts #wrapper #header .is-listing .listing_add_cart > div,
							#wrapper #header .stm-header-right .stm-c-f-add-btn-wrap .add-listing-btn',
					'value'        => '#1bc744',
					'dependency'   => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_hover_bg_color'    =>
				array(
					'label'        => esc_html__( 'Hover Background Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'mode'         => 'background-color',
					'output'       => '.stm-layout-header-listing #wrapper #header .header-listing .listing_add_cart:hover > div,
								body #wrapper .header-help-bar > ul li a.listing_add_cart:hover,
								.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .is-listing .listing_add_cart > div:hover,
								.stm-layout-header-boats #stm-boats-header #header .is-listing .listing_add_cart > div:hover,
								.stm-layout-header-car_rental #header .is-listing .listing_add_cart > div:hover,
								.stm-layout-header-equipment #wrapper #header .listing_add_cart > div:hover,
								.stm-layout-header-aircrafts #wrapper #header .is-listing .listing_add_cart > div:hover,
							#wrapper #header .stm-header-right .stm-c-f-add-btn-wrap .add-listing-btn:hover',
					'value'        => '#1bc744',
					'dependency'   => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_text_color'        =>
				array(
					'label'           => esc_html__( 'Text Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => '.stm-layout-header-listing #wrapper #header .header-listing .listing_add_cart > div,
							.stm-layout-header-car_dealer .header-help-bar > ul li a .list-label,
							.stm-layout-header-car_dealer #wrapper #header .header-main .listing_add_cart,
							#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions a.listing_add_cart div .heading-font,
							.stm-layout-header-equipment #wrapper #header .listing_add_cart > div .list-label,
							.stm-layout-header-aircrafts #wrapper #header .is-listing .listing_add_cart > div .list-label,
							#wrapper #header .stm-header-right .stm-c-f-add-btn-wrap .add-listing-btn
							',
					'value'           => '#ffffff',
					'dependency'      => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies'    => '&&',
					'style_important' => true,
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),

			'header_listing_btn_hover_text_color'  =>
				array(
					'label'           => esc_html__( 'Hover Text Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => '.stm-layout-header-listing #wrapper #header .header-listing .listing_add_cart:hover > div,
						.stm-layout-header-listing #wrapper #header .header-listing .listing_add_cart:hover > div i,
						.stm-layout-header-car_dealer .header-help-bar > ul li a:hover .list-label,
						.stm-layout-header-car_dealer #wrapper #header .header-main .listing_add_cart:hover,
						body #wrapper .header-help-bar > ul > li > a:hover,
						body #wrapper .header-help-bar > ul > li > a:hover i,
						body #wrapper .header-help-bar > ul > li > a.listing_add_cart:hover .list-label,
						#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions a.listing_add_cart:hover .heading-font,
						#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions a.listing_add_cart:hover i,
						.stm-layout-header-equipment #wrapper #header .listing_add_cart > div:hover i,
						.stm-layout-header-equipment #wrapper #header .listing_add_cart > div:hover .list-label,
						.stm-layout-header-car_rental #header .is-listing .listing_add_cart > div:hover i,
						.stm-layout-header-aircrafts #wrapper #header .is-listing .listing_add_cart > div:hover i,
						.stm-layout-header-aircrafts #wrapper #header .is-listing .listing_add_cart > div:hover .list-label,
						#wrapper #header .stm-header-right .stm-c-f-add-btn-wrap .add-listing-btn:hover,
						#wrapper #header .stm-header-right .stm-c-f-add-btn-wrap .add-listing-btn:hover i',
					'value'           => '#ffffff',
					'dependency'      => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies'    => '&&',
					'style_important' => true,
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'header_listing_btn_icon'              =>
				array(
					'label'        => esc_html__( 'Icon', 'stm_motors_extends' ),
					'type'         => 'icon_picker',
					'group'        => 'ended',
					'dependency'   => array(
						array(
							'key'     => 'header_current_layout',
							'value'   => 'listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
							'section' => 'general_tab',
						),
						array(
							'key'   => 'header_layout',
							'value' => 'aircrafts||boats||equipment||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||car_magazine||car_rental||car_rental_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||listing_five||listing_five_elementor||listing_six',
						),
						array(
							'key'   => 'header_show_add_car_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'hma_search_button'                    =>
				array(
					'label'      => esc_html__( 'Show Search Button', 'stm_motors_extends' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'hma_search_button_icon'               =>
				array(
					'label'        => esc_html__( 'Search Button Icon', 'stm_motors_extends' ),
					'type'         => 'icon_picker',
					'dependency'   => array(
						array(
							'key'   => 'header_layout',
							'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer',
						),
						array(
							'key'   => 'hma_search_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'hma_search_button_hover_color'        =>
				array(
					'label'        => esc_html__( 'Search Button Hover Background Color', 'stm_motors_extends' ),
					'type'         => 'color',
					'mode'         => array( 'background-color', 'border-color' ),
					'output'       => '
							#header-nav-holder .header-help-bar > ul li.nav-search:hover > a,
							#header-nav-holder .header-help-bar > ul li.nav-search > a:hover
							',
					'dependency'   => array(
						array(
							'key'   => 'header_layout',
							'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer',
						),
						array(
							'key'   => 'hma_search_button',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'hma_search_button_hover_icon_color'   =>
				array(
					'label'           => esc_html__( 'Search Button Hover Icon Color', 'stm_motors_extends' ),
					'type'            => 'color',
					'mode'            => 'color',
					'output'          => '
							#header-nav-holder .header-help-bar > ul li.nav-search:hover > a i,
							#header-nav-holder .header-help-bar > ul li.nav-search > a:hover i
							',
					'style_important' => true,
					'value'           => '#ffffff',
					'group'           => 'ended',
					'dependency'      => array(
						array(
							'key'   => 'header_layout',
							'value' => 'car_dealer_elementor||car_dealer_elementor_rtl||car_dealer',
						),
						array(
							'key'   => 'hma_search_button',
							'value' => 'not_empty',
						),
					),
					'dependencies'    => '&&',
					'submenu'         => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'service_header_label'                 =>
				array(
					'label'      => esc_html__( 'Header Button label', 'stm_motors_extends' ),
					'type'       => 'text',
					'value'      => 'Make an Appointment',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'service',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'service_header_link'                  =>
				array(
					'label'      => esc_html__( 'Header Button Link', 'stm_motors_extends' ),
					'type'       => 'text',
					'value'      => '#appointment-form',
					'dependency' => array(
						'key'   => 'header_layout',
						'value' => 'service',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
			'rental_btns_empty_notice'             =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_motors_extends' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'     => 'header_current_layout',
						'value'   => 'car_rental||car_rental_elementor',
						'section' => 'general_tab',
					),
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_motors_extends' ),
				),
		);

		return array_merge( $conf, $config );
	},
	25,
	1
);
