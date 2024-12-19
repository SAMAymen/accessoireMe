<?php
add_filter(
	'motors_get_all_wpcfto_config',
	function( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Footer', 'stm_motors_extends' ),
			'fields' =>
				array(
					'footer_bg_color'        =>
						array(
							'label'           => esc_html__( 'Background Color', 'stm_motors_extends' ),
							'type'            => 'color',
							'output'          => '#footer-main, body.page-template-home-service-layout #footer #footer-main',
							'mode'            => 'background-color',
							'value'           => '#232628',
							'style_important' => true,
						),
					'footer_sidebar_count'   =>
						array(
							'label'   => esc_html__( 'Widget Areas', 'stm_motors_extends' ),
							'type'    => 'select',
							'value'   => 4,
							'options' =>
								array(
									0 => esc_html__( 'Disable Widget Area', 'stm_motors_extends' ),
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
								),
						),
					'footer_copyright_color' =>
						array(
							'label'           => esc_html__( 'Copyright Area Background Color', 'stm_motors_extends' ),
							'type'            => 'color',
							'output'          => '#footer-copyright, body.page-template-home-service-layout #footer #footer-copyright',
							'mode'            => 'background-color',
							'value'           => '#232628',
							'style_important' => true,
						),
					'footer_copyright'       =>
						array(
							'label' => esc_html__( 'Copyright Enable', 'stm_motors_extends' ),
							'type'  => 'checkbox',
							'value' => true,
						),
					'footer_copyright_text'  =>
						array(
							'label'      => esc_html__( 'Copyright', 'stm_motors_extends' ),
							'value'      => '<a href="https://stylemixthemes.com/motors/" target="_blank">Motors</a> – Theme for WordPress by <a href="https://stylemixthemes.com/" target="_blank">StylemixThemes</a><span class=\"divider\"></span>Trademarks and brands are the property of their respective owners.',
							'type'       => 'text',
							'dependency' => array(
								'key'   => 'footer_copyright',
								'value' => 'not_empty',
							),
						),
					'footer_socials_enable'  =>
						array(
							'label'   => esc_html__( 'Socials', 'stm_motors_extends' ),
							'type'    => 'multi_checkbox',
							'options' => stm_me_wpcfto_socials(),
						),
				),
		);

		$global_conf['footer'] = $conf;

		return $global_conf;
	},
	60,
	1
);
