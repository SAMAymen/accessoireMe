<?php
/*
* Plugin Name: STM Importer
* Plugin URI: https://stylemixthemes.com/
* Description: STM Importer
* Author: StylemixThemes
* Author URI: https://stylemixthemes.com/
* Text Domain: stm_importer
* Version: 5.3.6
*/

define( 'STM_CONFIGURATIONS_PATH', dirname( __FILE__ ) );

require_once STM_CONFIGURATIONS_PATH . '/helpers/set_hb_options.php';
require_once STM_CONFIGURATIONS_PATH . '/helpers/content.php';
require_once STM_CONFIGURATIONS_PATH . '/helpers/theme_options.php';
require_once STM_CONFIGURATIONS_PATH . '/helpers/slider.php';
require_once STM_CONFIGURATIONS_PATH . '/helpers/widgets.php';
require_once STM_CONFIGURATIONS_PATH . '/helpers/set_content.php';

// disable Woocommerce setup wizard after reload
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

add_action( 'wp_ajax_stm_demo_import_content', 'stm_demo_import_content' );
function stm_demo_import_content() {
	if ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) {
		$layout       = ! empty( $_GET['demo_template'] ) ? sanitize_title( $_GET['demo_template'] ) : 'car_dealer';
		$builder      = ! empty( $_GET['builder'] ) ? sanitize_title( $_GET['builder'] ) : 'js_composer';
		$import_data  = ! empty( $_GET['import_data'] ) ? sanitize_title( $_GET['import_data'] ) : '';
		$import_media = ! empty( $_GET['import_media'] ) ? ( 'true' === $_GET['import_media'] ) : false;

		update_option( 'stm_motors_chosen_template', $layout );

		// Run demo import parts
		$res = stm_demo_import_content_cli( $layout, $builder, $import_data, $import_media );
		if ( is_wp_error( $res ) ) {
			wp_send_json_error( $res, 400 );
		}

		if ( ! empty( $import_data ) ) {
			wp_send_json(
				array(
					'imported' => $import_data,
				)
			);
		} else {
			wp_send_json(
				array(
					'url'                 => get_home_url( '/' ),
					'title'               => esc_html__( 'View site', 'stm_domain' ),
					'theme_options_title' => esc_html__( 'Theme options', 'stm_domain' ),
					'theme_options'       => esc_url_raw( admin_url( '?page=wpcfto_motors_' . $layout . '_settings' ) ),
				)
			);
		}

		wp_send_json(
			array(
				'url'                 => get_home_url( '/' ),
				'title'               => esc_html__( 'View site', 'stm_domain' ),
				'theme_options_title' => esc_html__( 'Theme options', 'stm_domain' ),
				'theme_options'       => esc_url_raw( admin_url( '?page=wpcfto_motors_' . $layout . '_settings' ) ),
			)
		);
	}

	die();
}

/**
 * Run Demo Import
 *
 * @param $layout
 * @param $builder
 * @param $import_data
 * @param $import_media
 *
 * @return array|bool|string|\WP_Error
 */
function stm_demo_import_content_cli( $layout, $builder, $import_data, $import_media ) {

	if ( 'rental_two' === $layout ) {
		stm_importer_create_taxonomy();
	}
	switch ( $import_data ) {
		case 'content':
			stm_theme_before_import_content( $layout, $builder );

			/** Import content */
			return stm_theme_import_content( $layout, $builder, $import_media );
		case 'theme_options':
			/** Import theme options */
			stm_set_layout_options( $layout );
			/** Import header builder */
			stm_set_hb_options( $layout );
			break;
		case 'sliders':
			/** Import sliders */
			stm_theme_import_sliders( $layout );
			break;
		case 'widgets':
			/** Import Widgets */
			stm_theme_import_widgets( $layout );
			/** Set menu and pages */
			stm_set_content_options( $layout );
			break;
		default:
			do_action( 'stm_importer_done', $layout );
	}
}

function stm_theme_before_import_content( $layout, $builder ) {
	if ( 'elementor' === $builder ) {

		/*Update Options Elementor*/
		update_option( 'elementor_cpt_support', motors_get_post_types_for_elementor() );
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		update_option( 'elementor_load_fa4_shim', 'yes' );
		update_option( 'elementor_container_width', 1140 );
		update_option( 'elementor_space_between_widgets', 20 );
	}
}

function motors_get_post_types_for_elementor() {

	$field = array();

	$post_types_objects = get_post_types(
		array(
			'public' => true,
		),
		'objects'
	);

	foreach ( $post_types_objects as $cpt_slug => $post_type ) {

		$field[ $cpt_slug ] = $post_type->labels->name;

	}

	unset( $field['elementor_library'] );
	unset( $field['attachment'] );

	return array_keys( $field );
}

add_action( 'stm_importer_done', 'elementor_set_default_settings', 15 );
function elementor_set_default_settings() {
	$active_kit = intval( get_option( 'elementor_active_kit', 0 ) );
	$meta       = get_post_meta( $active_kit, '_elementor_page_settings', true );

	if ( ! empty( $active_kit ) ) {
		$meta                                  = ( ! empty( $meta ) ) ? $meta : array();
		$meta['container_width']               = array(
			'size'  => '1140',
			'unit'  => 'px',
			'sizes' => array(),
		);
		$meta['space_between_widgets']['size'] = array(
			'size'  => '20',
			'unit'  => 'px',
			'sizes' => array(),
		);
		update_post_meta( $active_kit, '_elementor_page_settings', $meta );
		if ( class_exists( 'Elementor\Core\Breakpoints' ) ) {
			Elementor\Core\Breakpoints\Manager::compile_stylesheet_templates();
		}

		update_option( 'elementor_experiment-container', 'inactive' );

	}

	global $wpdb;

	$from = trim( 'http://test.loc' );
	$to   = get_site_url();
	//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$rows_affected = $wpdb->query( "UPDATE {$wpdb->postmeta} SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" );
	//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$change_gutenberg = $wpdb->query( "UPDATE {$wpdb->posts} SET `post_content` = REPLACE(`post_content`, '" . $from . "', '" . $to . "') WHERE `post_type` = 'post' AND `post_status` = 'publish' ;" );

	if ( class_exists( 'Elementor\Core\Breakpoints' ) ) {
		Elementor\Core\Breakpoints\Manager::compile_stylesheet_templates();
	}
}

