<?php
use ElementorPro\Modules\ThemeBuilder\Module;
if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Classes\Theme_Support' ) ) {
	class Motors_Theme_Support extends ElementorPro\Modules\ThemeBuilder\Classes\Theme_Support {

		public function init() {
			parent::init();
			add_action( 'elementor/theme/register_locations', array( $this, 'after_register_locations' ), 100 );
		}

		public function after_register_locations( $location_manager ) {
			$core_locations            = $location_manager->get_core_locations();
			$overwrite_header_location = false;
			$overwrite_footer_location = false;

			foreach ( $core_locations as $location => $settings ) {
				if ( ! $location_manager->get_location( $location ) ) {
					if ( 'header' === $location ) {
						$overwrite_header_location = true;
					} elseif ( 'footer' === $location ) {
						$overwrite_footer_location = true;
					}
					$location_manager->register_core_location(
						$location,
						array(
							'overwrite' => true,
						)
					);
				}
			}

			if ( $overwrite_header_location || $overwrite_footer_location ) {
				/** @var Module $theme_builder_module */
				$theme_builder_module = Module::instance();

				$conditions_manager = $theme_builder_module->get_conditions_manager();

				$headers = $conditions_manager->get_documents_for_location( 'header' );
				$footers = $conditions_manager->get_documents_for_location( 'footer' );

				function wrapper_main_open_tags() {
					echo wp_kses_post( '<div id="wrapper"><div id="main">' );
				}

				function wrapper_main_close_tags() {
					echo wp_kses_post( '</div></div>' );
				}

				if ( ! empty( $headers ) && ! empty( $footers ) ) {

					add_action( 'get_header', 'wrapper_main_open_tags', 11 );

					add_action( 'get_header', array( $this, 'get_header' ) );

					add_action( 'get_footer', 'wrapper_main_close_tags', 9 );

					add_action( 'get_footer', array( $this, 'get_footer' ) );

				}
				if ( ! empty( $headers ) ) {

					add_action( 'get_header', 'wrapper_main_open_tags', 11 );

					add_action( 'get_header', array( $this, 'get_header' ) );
				}
				if ( ! empty( $footers ) ) {
					add_action( 'get_footer', 'wrapper_main_close_tags', 9 );

					add_action( 'get_footer', array( $this, 'get_footer' ) );
				}
			}
		}
	}
}

if ( class_exists( 'Motors_Theme_Support' ) ) {
	$your_theme_support_instance = new Motors_Theme_Support();
}
