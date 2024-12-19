<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function( $global_conf ) {

		$excluded = array(
			'font-family',
			'backup-font',
		);

		if ( class_exists( 'Motors_E_W\MotorsApp' ) ) {
			$excluded[] = 'text-align';
		}

		$conf = array(
			'name'   => esc_html__( 'Typography', 'stm_motors_extends' ),
			'fields' =>
				array(
					'typography_body_font_family'    =>
						array(
							'label'    => esc_html__( 'Body Font Family', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'body, .normal_font',
							'excluded' => array(
								'font-weight',
								'font-style',
								'google-weight',
								'subset',
								'text-align',
								'word-spacing',
								'letter-spacing',
							),
						),
					'typography_heading_font_family' =>
						array(
							'label'    => esc_html__( 'Headings Font Settings', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6,.heading-font,.button,.event-head,
						.load-more-btn,.vc_tta-panel-title,.page-numbers li > a,.page-numbers li > span,
						.vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab a span,.stm_auto_loan_calculator input,
						.post-content blockquote,.contact-us-label,.stm-shop-sidebar-area .widget.widget_product_categories > ul,
						#main .stm-shop-sidebar-area .widget .product_list_widget li .product-title,
						#main .stm-shop-sidebar-area .widget .product_list_widget li a,
						.woocommerce ul.products li.product .onsale,
						.woocommerce div.product p.price, .woocommerce div.product span.price,
						.woocommerce div.product .woocommerce-tabs ul.tabs li a,
						.woocommerce table.shop_attributes td,
						.woocommerce table.shop_table td.product-name > a,
						.woocommerce-cart table.cart td.product-price,
						.woocommerce-cart table.cart td.product-subtotal,
						.stm-shop-sidebar-area .widget button,
						.stm-list-style-counter li:before,
						.ab-booking-form .ab-nav-steps .ab-btn,
						body.stm-template-motorcycle .stm_motorcycle-header .stm_mc-main.header-main .stm_top-menu li .sub-menu a,
						.wpb_tour_tabs_wrapper.ui-tabs ul.wpb_tabs_nav > li > a,
						.stm_post_comments .form-submit input[type="submit"],
						.review-comment-form .form-submit input[type=submit]',
							'excluded' => array(
								'font-weight',
								'font-style',
								'google-weight',
								'font-size',
								'subset',
								'text-align',
								'line-height',
								'word-spacing',
								'letter-spacing',
								'color',
							),
						),
					'typography_h1_font_size'        =>
						array(
							'label'    => esc_html__( 'H1 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h1, .h1, h1.heading-font',
							'excluded' => $excluded,
						),
					'typography_h2_font_size'        =>
						array(
							'label'    => esc_html__( 'H2 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h2, .h2, h2.heading-font',
							'excluded' => $excluded,
						),
					'typography_h3_font_size'        =>
						array(
							'label'    => esc_html__( 'H3 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h3, .h3, h3.heading-font',
							'excluded' => $excluded,
						),
					'typography_h4_font_size'        =>
						array(
							'label'    => esc_html__( 'H4 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h4, .h4, h4.heading-font',
							'excluded' => $excluded,
						),
					'typography_h5_font_size'        =>
						array(
							'label'    => esc_html__( 'H5 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h5, .h5, h5.heading-font',
							'excluded' => array(
								'font-family',
								'backup-font',
							),
						),
					'typography_h6_font_size'        =>
						array(
							'label'    => esc_html__( 'H6 Font', 'stm_motors_extends' ),
							'type'     => 'typography',
							'output'   => 'h6, .h6, h6.heading-font',
							'excluded' => $excluded,
						),
				),
		);

		$global_conf['typography'] = $conf;

		return $global_conf;
	},
	50,
	1
);
