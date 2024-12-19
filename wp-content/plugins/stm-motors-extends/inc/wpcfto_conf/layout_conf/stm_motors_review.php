<?php
if ( is_plugin_active( 'stm_motors_review/stm_motors_review.php' ) ) {
	add_filter(
		'motors_get_all_wpcfto_config',
		function ( $global_conf ) {

			$review_pagination = array(
				'pagination' => esc_html__( 'Pagination', 'stm_motors_extends' ),
				'load_more'  => esc_html__( 'Load More Button', 'stm_motors_extends' ),
			);

			$conf = array(
				'name'   => esc_html__( 'Stm Review Settings', 'stm_motors_extends' ),
				'fields' =>
					array(
						'review_archive_paginatin_style' => array(
							'label'   => esc_html__( 'Review pagination type', 'stm_motors_extends' ),
							'type'    => 'select',
							'options' => $review_pagination,
							'value'   => 'pagination',
						),
						'review_per_page'                => array(
							'label' => esc_html__( 'Review per page', 'stm_motors_extends' ),
							'type'  => 'text',
							'value' => 6,
						),
					),
			);

			$global_conf['stm_review_settings'] = $conf;
			return $global_conf;

		},
		29,
		1
	);
}
