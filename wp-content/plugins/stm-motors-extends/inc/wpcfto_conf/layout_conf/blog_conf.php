<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Blog page', 'stm_motors_extends' ),
			'fields' => array(
				'view_type'         =>
					array(
						'label'   => esc_html__( 'Blog Archive Page Layout', 'stm_motors_extends' ),
						'type'    => 'radio',
						'options' =>
							array(
								'grid' => 'Grid',
								'list' => 'List',
							),
						'value'   => 'grid',
					),
				'sidebar'           =>
					array(
						'label'   => esc_html__( 'Archive Page Sidebar', 'stm_motors_extends' ),
						'type'    => 'select',
						'options' => stm_me_wpcfto_sidebars(),
						'value'   => 'default',
					),
				'sidebar_blog'      =>
					array(
						'label'   => esc_html__( 'Blog Post Sidebar', 'stm_motors_extends' ),
						'type'    => 'select',
						'options' => stm_me_wpcfto_sidebars(),
						'value'   => 'default',
					),
				'sidebar_position'  =>
					array(
						'label'   => esc_html__( 'Sidebar Position', 'stm_motors_extends' ),
						'type'    => 'radio',
						'options' =>
							array(
								'left'  => 'Left',
								'right' => 'Right',
							),
						'value'   => 'right',
					),
				'blog_show_excerpt' =>
					array(
						'label'      => esc_html__( 'Show Excerpt (for Grid View)', 'stm_motors_extends' ),
						'type'       => 'checkbox',
						'dependency' => array(
							'key'     => 'header_current_layout',
							'value'   => 'aircrafts||boats||ev_dealer||car_dealer_elementor||car_dealer_elementor_rtl||car_dealer||car_dealer_two||car_dealer_two_elementor||equipment||listing||listing_one_elementor||listing_two||listing_two_elementor||listing_three||listing_three_elementor||listing_four||listing_four_elementor||motorcycle',
							'section' => 'general_tab',
						),
					),
			),
		);

		$global_conf['blog_conf'] = $conf;

		return $global_conf;
	},
	25,
	1
);
