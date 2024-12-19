<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function ( $global_conf ) {
		$conf                       = apply_filters( 'motors_wpcfto_general_start_config', $global_conf );
		$general_conf               = array(
			'404_page'                  =>
				array(
					'type'  => 'checkbox',
					'label' => esc_html__( '404 Page', 'stm_motors_extends' ),
					'value' => false,
					'group' => 'started',
				),
			'404_page_dropdown'         =>
				array(
					'type'       => 'select',
					'label'      => esc_html__( 'Choose Custom 404 Page', 'stm_motors_extends' ),
					'options'    => stm_me_get_all_pages(),
					'dependency' => array(
						'key'   => '404_page',
						'value' => 'not_empty',
					),
					'group'      => 'ended',
				),
			'coming_soon_page'          =>
				array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Under Construction Mode', 'stm_motors_extends' ),
					'value' => false,
					'group' => 'started',
				),
			'coming_soon_page_dropdown' =>
				array(
					'type'        => 'select',
					'label'       => esc_html__( 'Choose Under Construction Page', 'stm_motors_extends' ),
					'description' => 'Select a page for the Under Construction Mode setting to work.',
					'options'     => stm_me_get_all_pages(),
					'dependency'  => array(
						'key'   => 'coming_soon_page',
						'value' => 'not_empty',
					),
					'group'       => 'ended',
				),
		);

		if ( defined( 'STM_LISTINGS_V' ) && version_compare( STM_LISTINGS_V, '1.4.23' ) > 0 ) {
			$general_conf['fonts_download_settings'] = array(
				'label' => esc_html__( 'Fonts download settings', 'stm_motors_extends' ),
				'type'  => 'fonts_download_settings',
			);
		}

		$conf                       = array_merge( $conf, $general_conf );
		$conf                       = array(
			'name'   => esc_html__( 'General', 'stm_motors_extends' ),
			'fields' => $conf,
		);
		$global_conf['general_tab'] = $conf;

		return $global_conf;
	},
	10,
	1
);
