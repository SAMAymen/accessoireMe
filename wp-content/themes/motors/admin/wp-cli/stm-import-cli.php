<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Class STM_Import_CLI
 */
class STM_Import_CLI {

	/**
	 * Import Demo Content.
	 *
	 * ## OPTIONS
	 *
	 * <layout>
	 * : Layout Slug.
	 *
	 * [--builder=<builder>]
	 * : Choose Builder.
	 * ---
	 * default: js_composer
	 * options:
	 *   - js_composer
	 *   - elementor
	 * ---
	 *
	 * [--data=<theme_options,content,widgets>]
	 * : Choose Data to Import. Default: All.
	 *
	 * [--no-media]
	 * : Don't import media Attachments.
	 *
	 * ## EXAMPLES
	 *
	 *     wp stm import layout_1 --builder=elementor
	 *
	 * @when after_wp_load
	 */
	public function import( $args, $assoc_args ) {
		list( $layout ) = $args;
		$builder        = ( ! empty( $assoc_args['builder'] ) ) ? $assoc_args['builder'] : 'js_composer';
		$import_media   = empty($assoc_args['no-media']);
		$import_data    = [
			'theme_options',
			'content',
			'widgets'
		];

		if ( ! empty( $assoc_args['data'] ) ) {
			$import_data = explode(',', $assoc_args['data']);
		}

		if ( function_exists('stm_demo_import_content_cli') ) {
			foreach ( $import_data as $data) {
				stm_demo_import_content_cli( $layout, $builder, $data, $import_media );
				WP_CLI::success( "'$data' import done!"  );
			}

			// Finish Demo Import
			stm_demo_import_content_cli( $layout, $builder, '', $import_media );
			WP_CLI::success( 'Import finished!' );
		} else {
			WP_CLI::error( 'Import function not found!' );
		}
	}

	/**
	 * Install Layout Plugins.
	 *
	 * ## OPTIONS
	 *
	 * <layout>
	 * : Layout Slug.
	 *
	 * [--builder=<builder>]
	 * : Choose Builder.
	 * ---
	 * default: js_composer
	 * options:
	 *   - js_composer
	 *   - elementor
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp stm install layout_1 --builder=elementor
	 *
	 * @when after_wp_load
	 */
	public function install( $args, $assoc_args ) {
		list( $layout )     = $args;
		$builder            = ( ! empty( $assoc_args['builder'] ) ) ? $assoc_args['builder'] : 'js_composer';
		$plugins            = apply_filters( 'stm_theme_plugins', [] );
		$layout_plugins     = apply_filters( 'stm_theme_layout_plugins', $layout );
		$layout_plugins     = STM_Theme_Plugins::get_builder( $layout_plugins, $builder );

		STM_Theme_Plugins::load_wp();

		foreach ( $layout_plugins as $plugin_slug ) {
			$plugin_upgrader    = new Plugin_Upgrader( new STM_Plugin_Upgrader_Skin([ 'plugin' => $plugin_slug ]) );
			$plugin_info        = $plugins[ $plugin_slug ];
			$source             = STM_Theme_Plugins::get_plugin_source($plugin_info);

			if ( ! empty( $source ) ) {
				$installed = ( STM_Theme_Plugins::plugin_is_active($plugin_slug) ) ? true : $plugin_upgrader->install($source);

				if ( is_wp_error( $installed ) ) {
					WP_CLI::error( $installed->get_error_message() );
				} else {
					STM_Theme_Plugins::activate_plugin($plugin_slug);
					WP_CLI::success( "{$plugin_info['name']} plugin installed & activated!" );
				}
			}
		}

		WP_CLI::success( "Installation process finished!" );
	}
}

WP_CLI::add_command( 'stm', 'STM_Import_CLI' );