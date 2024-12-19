<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {
		$sidebars = array( 'no_sidebar' => 'No Sidebar' );
		$query    = get_posts(
			array(
				'post_type'      => 'sidebar',
				'posts_per_page' => -1,
			)
		);

		if ( $query ) {
			foreach ( $query as $post ) {
				$sidebars[ $post->ID ] = get_the_title( $post->ID );
			}
		}

		$conf = array(
			'name'   => esc_html__( 'Auto Parts Layout Settings', 'stm_motors_extends' ),
			'fields' =>
				array(
					'wcmap_best_selling_ico'        =>
						array(
							'label' => esc_html__( 'Icon For Best Selling Products', 'stm_motors_extends' ),
							'type'  => 'image',
						),
					'wcmap_best_sell_min'           =>
						array(
							'label' => esc_html__( 'Best Seller Product Minimum Amount', 'stm_motors_extends' ),
							'type'  => 'text',
							'value' => 10,
						),
					'wcmap_top_rate_ico'            =>
						array(
							'label' => esc_html__( 'Icon For Top Rated Products', 'stm_motors_extends' ),
							'type'  => 'image',
						),
					'wcmap_best_rate_min'           => array(
						'label' => esc_html__( 'Best Rated Minimum Average', 'stm_motors_extends' ),
						'type'  => 'text',
						'value' => 4.5,
					),
					'wcmap_sale_ico'                =>
						array(
							'label' => esc_html__( 'Icon For Sale Product', 'stm_motors_extends' ),
							'type'  => 'image',
						),
					'wcmap_single_product_template' => array(
						'label'   => esc_html__( 'Single Product Template', 'stm_motors_extends' ),
						'type'    => 'select',
						'options' => array(
							'template_sidebar' => esc_html__( 'Template With Sidebar', 'stm_motors_extends' ),
							'template_1'       => esc_html__( 'Template Without Sidebar', 'stm_motors_extends' ),
						),
						'default' => 'template_sidebar',
					),
					'wcmap_single_product_sidebar'  =>
						array(
							'label'   => esc_html__( 'Choose Single Product Sidebar', 'stm_motors_extends' ),
							'type'    => 'select',
							'options' => stm_me_wpcfto_sidebars(),
							'value'   => '768',
						),
					'wcmap_single_product_sidebar_position' =>
						array(
							'label'   => esc_html__( 'Single Product Sidebar Position', 'stm_motors_extends' ),
							'type'    => 'radio',
							'options' =>
								array(
									'left'  => esc_html__( 'Left', 'stm_motors_extends' ),
									'right' => esc_html__( 'Right', 'stm_motors_extends' ),
								),
							'value'   => 'left',
						),
					'prefooter_sb'                  => array(
						'label'   => esc_html__( 'Shop and Single Product page Pre-Footer', 'stm-woocommerce-motors-auto-parts' ),
						'type'    => 'select',
						'options' => $sidebars,
						'value'   => 'no_sidebar',
					),
				),
		);

		$global_conf['auto_parts_layout_settings'] = $conf;

		return $global_conf;
	},
	26,
	1
);
