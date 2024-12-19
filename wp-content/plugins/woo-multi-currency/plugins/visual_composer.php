<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Visual_Composer
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Visual_Composer {
	protected $settings;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'init', array( $this, 'init' ) );
		}
	}

	/**
	 * Integrate with Visual Composer
	 * @return bool
	 */
	public function init() {
		if ( ! function_exists( 'vc_map' ) ) {
			return false;
		}
		//Pain Horizontal
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_pain_horizontal",
			"description" => "Currency with layout pain horizontal",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		//Pain Vertical
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_pain_vertical",
			"description" => "Currency with layout pain vertical",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		//List Flag Horizontal
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_layout3",
			"description" => "Currency with layout List Flag Horizontal",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		//List Flag Vertical
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_layout4",
			"description" => "Currency with layout List Flag Vertical",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		//List Flag + Currency Code
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_layout5",
			"description" => "Currency with layout List Flag + Currency Code",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_layout6",
			"description" => "Horizontal Currency Symbols",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
		/*Vertical Currency Symbols*/
		vc_map( array(
			"name"        => esc_html__( "Multi Currency for WooCommerce", 'woo-multi-currency' ),
			"icon"        => "icon-ui-splitter-horizontal",
			"base"        => "woo_multi_currency_layout7",
			"description" => "Vertical Currency Symbols",
			"category"    => esc_html__( "WooCommerce", 'woo-multi-currency' ),
			"params"      => array(
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title", 'woo-multi-currency' ),
					"param_name" => "title",
				),
			)
		) );
	}
}