<?php
function stm_theme_import_content( $layout, $builder, $import_media ) {
	set_time_limit( 0 );

	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', true );
	}

	require_once STM_CONFIGURATIONS_PATH . '/wordpress-importer/class-stm-wp-import.php';

	$wp_import                    = new STM_WP_Import();
	$wp_import->theme             = 'motors';
	$wp_import->layout            = $layout;
	$wp_import->builder           = $builder;
	$wp_import->fetch_attachments = true;

	if ( defined( 'STM_DEV_MODE' ) ) {
		$ready = STM_CONFIGURATIONS_PATH . '/demos/' . $layout . '/xml/demo.xml';
	} else {
		$ready = stm_importer_download_demo( $layout );
	}

	if ( $ready ) {
		ob_start();
		$wp_import->import( $ready );
		ob_end_clean();
	}

	return true;
}

function stm_importer_download_demo( $layout ) {
	if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	}

	$upgrader = new WP_Upgrader( new Automatic_Upgrader_Skin() );

	$result = $upgrader->run(
		array(
			'package'                     => "downloads://motors/demos/{$layout}.zip",
			'destination'                 => apply_filters( 'stm_get_temp_path', '' ),
			'clear_destination'           => false,
			'abort_if_destination_exists' => false,
			'clear_working'               => false,
		)
	);

	if ( false === $result ) {
		$result = new WP_Error( '', 'WP_Upgrader returned "false" when downloading demo ZIP.' );
	}

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return $result['destination'] . "{$layout}.xml";
}

// used to extract taxonomy meta
function stm_get_complete_meta( $term_id, $meta_key ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->termmeta WHERE term_id = %d AND meta_key = %s", $term_id, $meta_key ) );
	if ( ! empty( $result ) ) {
		return $result;
	}

	return false;
}

add_action( 'init', 'stm_developers_extract_demo_data' );
function stm_developers_extract_demo_data() {
	if ( ! defined( 'STM_DEV_MODE' ) || true !== STM_DEV_MODE ) {
		return false;
	}

	// exports model parent meta array
	if ( isset( $_GET['stm_dev_model_parents'] ) ) {
		$models = get_terms(
			array(
				'taxonomy'   => 'serie',
				'hide_empty' => false,
			)
		);

		$array = array();

		if ( ! empty( $models ) && ! is_wp_error( $models ) ) {
			foreach ( $models as $model ) {
				$data = stm_get_complete_meta( $model->term_id, 'stm_parent' );
				if ( false !== $data ) {
					$array = array_merge( $array, $data );
				}
			}
		}

		echo '<pre>';
		echo wp_json_encode( $array );
		echo '</pre>';
		exit;
	}

	if ( function_exists( 'wpcfto_print_settings' ) && isset( $_GET['stm_dev_print_settings'] ) ) {
		/**
		 * Displays current active demo theme settings if no argument provided.
		 * Outputs NUXY settings json data for given settings name.
		 */
		wpcfto_print_settings();
	}
}

function consulting_importer_get_placeholder() {
	$placeholder_id    = 0;
	$placeholder_array = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_attachment_image_alt',
			'meta_value'     => 'motors_placeholder',
		)
	);

	if ( $placeholder_array ) {
		foreach ( $placeholder_array as $val ) {
			$placeholder_id = $val->ID;
		}
	}

	return $placeholder_id;
}

function consulting_import_rebuilder_elementor_data( &$data ) {

	if ( ! empty( $data ) ) {
		$data = maybe_unserialize( $data );
		if ( ! is_array( $data ) ) {
			if ( consulting_import_is_elementor_data_unslash_required() ) {
				$data = wp_unslash( $data );
			}
			$data = json_decode( $data, true );
		}
		consulting_import_rebuilder_elementor_data_walk( $data );
		$data = wp_slash( wp_json_encode( $data ) );
	}

}

function consulting_import_is_elementor_data_unslash_required() {
	// No elementor plugin is active - so no unslash is required
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return false;
	}

	// before version 2.9.10 it was required
	if ( version_compare( ELEMENTOR_VERSION, '2.9.10', '<' ) ) {
		return true;
	}

	// otherwise not required
	return false;
}

function consulting_import_rebuilder_elementor_data_walk( &$data_arg ) {

	if ( is_array( $data_arg ) ) {

		foreach ( $data_arg as &$args ) {

			if ( ! empty( $args['url'] ) && empty( $args['id'] ) ) {
				$localhost   = 'http://test.loc';
				$host        = get_bloginfo( 'url' );
				$args['url'] = str_replace( $localhost, $host, $args['url'] );
			}

			consulting_import_rebuilder_elementor_data_walk( $args );
		}
	}
}


add_action( 'stm_wp_import_after_insert_attachment', 'lms_pt_wp_import_after_insert_attachment_action', 100, 2 );

function lms_pt_wp_import_after_insert_attachment_action( $post_id, $builder ) {
	if ( 'elementor' === $builder ) {
		update_post_meta( $post_id, '_wp_attachment_image_alt', 'motors_placeholder' );
	}
}

if ( ! function_exists( 'stm_get_temp_path' ) ) {
	function stm_get_temp_path() {
		$upload_dir = wp_upload_dir();
		$dir        = $upload_dir['basedir'] . '/tmp-stm/';

		if ( ! is_dir( $dir ) ) {
			mkdir( $dir, 0777, true );
		}

		return $dir;
	}

	add_filter( 'stm_get_temp_path', 'stm_get_temp_path' );
}
