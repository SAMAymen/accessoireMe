<?php
class STM_Custom_Colors_Helper {

	private $css_list_for_custom    = '';
	private $css_list_for_custom_cf = '';
	private $wp_file_system;
	private $theme_css_directory;

	public function __construct() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$this->wp_file_system      = $wp_filesystem;
		$this->theme_css_directory = get_stylesheet_directory() . '/assets/css/dist/';

		$this->stm_theme_libs_styles();
		$this->stm_vehicle_listings_styles();
		$this->stm_cch_get_stylemix_elementor_widgets_css_list();
		$this->stm_cch_get_motors_elementor_widgets_css_list();
		$this->stm_cch_get_mega_menu_css_list();
		$this->stm_cch_get_vin_decodere_css_list();
		$this->stm_cch_get_vc_modules_css_list();
		$this->stm_cch_get_vc_modules_css_list_classified_five();
		$this->stm_cch_get_vc_modules_css_list_classified_six();
		$this->stm_cch_get_vc_modules_css_list_events();
		$this->stm_cch_get_vc_modules_css_list_review();
		$this->stm_cch_get_vc_modules_css_rental_two();
	}

	private function stm_theme_libs_styles() {
		$css_directory              = get_template_directory() . '/assets/css/';
		$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'stmdatetimepicker.css' );
		if ( defined( 'WPB_VC_VERSION' ) ) {
			$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'swiper-bundle.min.css' );
		}
	}

	private function stm_vehicle_listings_styles() {
		if ( defined( 'STM_LISTINGS_PATH' ) ) {
			$css_directory              = STM_LISTINGS_PATH . '/assets/css/';
			$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'frontend/select2.min.css' );
			$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'frontend/lightgallery.min.css' );
			$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'progress.css' );
			if ( defined( 'WPB_VC_VERSION' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( $css_directory . 'swiper-bundle.min.css' );
			}
		}
	}

	private function stm_cch_get_mega_menu_css_list() {
		if ( defined( 'STM_MM_DIR_NAME' ) ) {
			if ( file_exists( STM_MM_DIR_NAME . '/assets/css/megamenu.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MM_DIR_NAME . '/assets/css/megamenu.css' );
			}
			if ( file_exists( STM_MM_DIR_NAME . '/assets/css/megamenu_colors.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MM_DIR_NAME . '/assets/css/megamenu_colors.css' );
			}
		}

		if ( defined( 'STM_MWW_PATH' ) ) {
			if ( file_exists( STM_MWW_PATH . '/assets/css/stm_mm_top_vehicles.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MWW_PATH . '/assets/css/stm_mm_top_vehicles.css' );
			}

			if ( file_exists( STM_MWW_PATH . '/assets/css/stm_mm_top_makes_tab.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MWW_PATH . '/assets/css/stm_mm_top_makes_tab.css' );
			}

			if ( file_exists( STM_MWW_PATH . '/assets/css/stm_mm_top_categories.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MWW_PATH . '/assets/css/stm_mm_top_categories.css' );
			}
		}
	}

	private function stm_cch_get_vin_decodere_css_list() {
		if ( defined( 'STM_MOTORS_VIN_DECODERS_PATH' ) ) {
			if ( file_exists( STM_MOTORS_VIN_DECODERS_PATH . 'assets/css/vin-decoder.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_MOTORS_VIN_DECODERS_PATH . 'assets/css/vin-decoder.css' );
			}
		}
	}

	private function stm_cch_get_vc_modules_css_list() {
		if ( defined( 'STM_MWW_PATH' ) ) {
			$css_map = glob( STM_MWW_PATH . '/assets/css/*.css' );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	private function stm_cch_get_stylemix_elementor_widgets_css_list() {
		if ( defined( 'STM_ELEMENTOR_WIDGETS_PATH' ) ) {

			$css_map = glob( STM_ELEMENTOR_WIDGETS_PATH . '/assets/css/widget/*.css' );
			$css_map = array_merge( $css_map, glob( STM_ELEMENTOR_WIDGETS_PATH . '/assets/css/widget/**/*.css' ) );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	private function stm_cch_get_motors_elementor_widgets_css_list() {
		if ( defined( 'MOTORS_ELEMENTOR_WIDGETS_PATH' ) ) {

			$css_map = glob( MOTORS_ELEMENTOR_WIDGETS_PATH . '/assets/css/widget/*.css' );
			$css_map = array_merge( $css_map, glob( MOTORS_ELEMENTOR_WIDGETS_PATH . '/assets/css/widget/**/*.css' ) );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	private function stm_cch_get_vc_modules_css_list_classified_five() {
		if ( defined( 'STM_MOTORS_CLASSIFIED_FIVE' ) ) {

			$css_map = glob( STM_MOTORS_C_F_PATH . '/assets/css/vc_ss/*.css' );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	private function stm_cch_get_vc_modules_css_list_classified_six() {
		if ( defined( 'STM_MOTORS_CLASSIFIED_SIX' ) ) {

			$css_map = glob( STM_MOTORS_C_SIX_PATH . '/assets/css/vc_ss/*.css' );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	private function stm_cch_get_vc_modules_css_list_events() {
		if ( defined( 'STM_EVENTS' ) ) {
			if ( file_exists( STM_EVENTS_PATH . '/assets/css/style.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_EVENTS_PATH . '/assets/css/style.css' );
			}
		}
	}

	private function stm_cch_get_vc_modules_css_list_review() {
		if ( defined( 'STM_REVIEW' ) ) {
			if ( file_exists( STM_REVIEW_PATH . '/assets/css/style.css' ) ) {
				$this->css_list_for_custom .= $this->wp_file_system->get_contents( STM_REVIEW_PATH . '/assets/css/style.css' );
			}
		}
	}

	private function stm_cch_get_vc_modules_css_rental_two() {
		if ( defined( 'STM_MOTORS_CAR_RENTAL' ) ) {

			$css_map = glob( STM_MOTORS_CAR_RENTAL_PATH . '/assets/css/vc_ss/*.css' );

			foreach ( $css_map as $file ) {
				if ( is_file( $file ) ) {
					$this->css_list_for_custom .= $this->wp_file_system->get_contents( $file );
				}
			}
		}
	}

	public function stm_cch_get_css_modules() {
		return $this->css_list_for_custom;
	}
}
