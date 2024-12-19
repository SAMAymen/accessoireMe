<?php

namespace SEI\Classes;

class LoadIcons {

	private $custom_fonts = array();
	private $upload_paths = '';

	/**
	 * Init CustomIcons Class
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wpcfto_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'elementor/icons_manager/additional_tabs', array( $this, 'add_elementor_custom_icons' ), 100, 1 );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue' ) );
		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'enqueue' ) );

		add_filter( 'init', array( $this, 'add_vc_font_set' ), 40 );
		add_filter( 'vc_after_init', array( $this, 'add_vc_font_picker' ), 40 );
		add_filter( 'vc_iconpicker-type-customicons', array( $this, 'add_vc_custom_icons' ) );
		add_action( 'vc_backend_editor_enqueue_js_css', array( $this, 'enqueue' ) );
		add_action( 'vc_frontend_editor_enqueue_js_css', array( $this, 'enqueue' ) );

		$this->custom_fonts = get_option( 'stm_fonts' );
		$this->upload_paths = wp_upload_dir();
	}

	/**
	 * Enqueue Custom Icons
	 */
	public function enqueue() {
		if ( is_array( $this->custom_fonts ) ) {
			foreach ( $this->custom_fonts as $font => $info ) {
				if ( strpos( $info['style'], 'http://' ) !== false ) {
					wp_enqueue_style( 'stm-' . $font, $info['style'], null, '1.0', 'all' );
				} else {
					wp_enqueue_style( 'stm-' . $font, trailingslashit( $this->upload_paths['baseurl'] . '/stm_fonts/' ) . $info['style'], null, '1.0', 'all' );
				}
			}
		}
	}

	/**
	 * Include Custom Icons for Elementor
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_elementor_custom_icons( $tabs = array() ) {
		$new_icons = array();

		if ( ! empty( $this->custom_fonts ) ) {
			foreach ( $this->custom_fonts as $font_name => $info ) {
				$new_icons[ $font_name ] = array(
					'name'          => $font_name,
					'label'         => $font_name,
					'url'           => '',
					'enqueue'       => '',
					'prefix'        => '',
					'displayPrefix' => '',
					'labelIcon'     => 'fa fa-tools',
					'ver'           => '1.0.1',
					'fetchJson'     => $this->upload_paths['baseurl'] . '/stm_fonts/' . $font_name . '/charmap.json',
				);
			}
		}

		return array_merge( $tabs, $new_icons );
	}

	/**
	 * Add new custom font to Font Family selection in Icon box module
	 *
	 * @throws \Exception
	 */
	public function add_vc_font_set() {
		if ( class_exists( 'WPBMap' ) ) {
			$param = \WPBMap::getParam( 'vc_icon', 'type' );
			$param['value'][ esc_html__( 'Custom Icons', 'stm-elementor-icons' ) ] = 'customicons';

			vc_update_shortcode_param( 'vc_icon', $param );
		}
	}

	/**
	 * Add WP Bakery Font Picker
	 *
	 * @throws \Exception
	 */
	public function add_vc_font_picker() {
		vc_add_param(
			'vc_icon',
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'stm-elementor-icons' ),
				'param_name'  => 'icon_customicons',
				'settings'    => array(
					'emptyIcon'    => true,
					'type'         => 'customicons',
					'iconsPerPage' => 200,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'customicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'stm-elementor-icons' ),
				'weight'      => 1,
			)
		);
	}

	/**
	 * Add Custom Icons for WP Bakery
	 *
	 * @param $fonts
	 * @return mixed
	 */
	public function add_vc_custom_icons( $fonts ) {
		foreach ( $this->custom_fonts as $font_name => $info ) {
			$icon_set   = array();
			$icons      = array();
			$upload_dir = wp_upload_dir();
			$path       = trailingslashit( $upload_dir['basedir'] );
			$file       = $path . $info['include'] . '/' . $info['config'];

			if ( ! empty( $info['json'] ) ) {
				$json = $path . $info['include'] . '/' . $info['json'];
				if ( file_exists( $json ) ) {
					$json   = json_decode( file_get_contents( $json ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
					$prefix = $json['preferences']['fontPref']['prefix'];
				}
			} else {
				$prefix = $font_name . '-';
			}

			if ( file_exists( $file ) ) {
				include $file;
			}

			if ( ! empty( $icons ) ) {
				$icon_set = array_merge( $icon_set, $icons );
			}

			if ( ! empty( $icon_set ) ) {
				foreach ( $icon_set as $icons ) {
					foreach ( $icons as $icon ) {
						$fonts[ $font_name ][] = array(
							$prefix . $icon['class'] => $icon['class'],
						);
					}
				}
			}
		}

		return $fonts;
	}

}
