<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {

		$positions = array(
			'none'  => esc_html__( 'None', 'stm_motors_extends' ),
			'left'  => esc_html__( 'Left', 'stm_motors_extends' ),
			'right' => esc_html__( 'Right', 'stm_motors_extends' ),
		);

		$archive_page = array(
			'list' => esc_html__( 'List', 'stm_motors_extends' ),
			'grid' => esc_html__( 'Grid', 'stm_motors_extends' ),
		);

		$events_pagination = array(
			'pagination' => esc_html__( 'Pagination', 'stm_motors_extends' ),
			'load_more'  => esc_html__( 'Load More Button', 'stm_motors_extends' ),
		);

		$conf = array(
			'name'   => esc_html__( 'Stm Events Settings', 'stm_motors_extends' ),
			'fields' =>
				array(
					'events_archive'                  => array(
						'label'       => esc_html__( 'Events archive', 'stm_motors_extends' ),
						'type'        => 'select',
						'options'     => $archive_page,
						'value'       => 'list',
						'description' => esc_html__( 'Choose Events Page View', 'stm_motors_extends' ),
					),
					'events_archive_sidebar_position' => array(
						'label'   => esc_html__( 'Events page sidebar position', 'stm_motors_extends' ),
						'type'    => 'select',
						'options' => $positions,
						'value'   => 'right',
					),
					'events_block_title_bg'           => array(
						'label' => esc_html__( 'Title background', 'stm_motors_extends' ),
						'type'  => 'image',
					),
					'events_subtitle'                 => array(
						'label'   => esc_html__( 'Subtitle', 'stm_motors_extends' ),
						'type'    => 'text',
						'default' => esc_html__( 'Find interesting trade shows & conferences to attend', 'stm_motors_extends' ),
					),
					'events_archive_paginatin_style'  => array(
						'label'   => esc_html__( 'Events pagination type', 'stm_motors_extends' ),
						'type'    => 'select',
						'options' => $events_pagination,
						'value'   => 'pagination',
					),
					'events_per_page'                 => array(
						'label' => esc_html__( 'Events per page', 'stm_motors_extends' ),
						'type'  => 'text',
						'value' => 6,
					),
				),
		);

		$global_conf['stm_events_settings'] = $conf;

		return $global_conf;
	},
	28,
	1
);
