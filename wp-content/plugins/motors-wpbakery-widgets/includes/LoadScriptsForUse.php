<?php

class LoadScriptsForUse {

	public $scripts_map;

	public function __construct() {
		$this->scripts_map();
		add_action( 'do_shortcode_tag', array( $this, 'load_script' ), 2, 100 );
	}

	public function scripts_map() {
		$classic_filter = array(
			'uniform',
			'uniform-init',
			'lazyload',
			'jquery-effects-slide',
			'stmselect2',
			'app-select2',
			'stm_classic_filter',
			'addtoany-core',
		);

		if ( 'boats' === get_option( 'stm_motors_chosen_template', '' ) ) {
			$classic_filter[] = 'isotope';
		}

		$this->scripts_map = array(
			'stm_car_listing_tabbed'             => array(
				'stmselect2',
				'app-select2',
				'stm_car_listing_tabbed',
				'stm_call_to_action_2',
			),
			'stm_listing_search'                 => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_gmap',
				'stm-google-places',
				'stm_listing_search',
			),
			'stm_listing_types_classic_filter'   => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_listing_types_classic_filter',
				'stm_gmap',
				'stm_classic_filter',
				'stm-google-places',
			),
			'stm_listing_two_search'             => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_gmap',
				'stm-google-places',
				'stm_listing_two_search',
			),
			'stm_listing_types_search_tabs'      => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_gmap',
				'stm-google-places',
			),
			'stm_listing_search_with_car_rating' => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_listing_search_with_car_rating',
			),
			'stm_listing_search_without_tabs'    => array(
				'stm-cascadingdropdown',
				'stmselect2',
				'app-select2',
				'stm_gmap',
				'stm-google-places',
				'stm_listing_search_without_tabs',
			),
			'stm_filter_selects'                 => array( 'stmselect2', 'app-select2', 'stm_filter_selects' ),
			'stm_modern_filter'                  => array(
				'uniform',
				'uniform-init',
				'isotope',
				'stmselect2',
				'app-select2',
				'addtoany-core',
			),
			'stm_icon_box'                       => array( 'vc_font_awesome_5', 'stm_icon_box' ),
			'stm_special_offers'                 => array( 'stm_special_offers' ),
			'stm_classic_filter'                 => $classic_filter,
			'stm_sold_cars'                      => $classic_filter,
			'stm_inventory_on_map'               => array(
				'uniform',
				'uniform-init',
				'jquery-effects-slide',
				'stmselect2',
				'app-select2',
				'stm_marker_cluster',
				'stm_gmap',
				'stm-google-places',
				'stm_inventory_on_map',
			),
			'stm_listing_map_by_my_location'     => array(
				'stm_marker_cluster',
				'stm_gmap',
				'stm-google-places',
				'stm_inventory_on_map',
			),
			'stm_inventory_with_filter'          => array(
				'uniform',
				'uniform-init',
				'jquery-effects-slide',
				'stmselect2',
				'app-select2',
				'stm_inventory_with_filter',
			),
			'stm_call_to_action'                 => array( 'stm_call_to_action' ),
			'stm_single_car_title'               => array( 'stm_single_car_title' ),
			'stm_single_car_actions'             => array( 'stm_single_car_actions' ),
			'stm_dealer_list'                    => array( 'stm-cascadingdropdown', 'stmselect2', 'app-select2', 'stm-dealer-list' ),
			'stm_rent_car_form'                  => array(
				'stm_gmap',
				'stmselect2',
				'app-select2',
				'uniform',
				'uniform-init',
				'stmdatetimepicker',
				'app-datetime',
			),
			'stm_rental_two_car_form'            => array(
				'uniform',
				'uniform-init',
				'stmselect2',
				'app-select2',
				'stmdatetimepicker',
			),
			'stm_add_a_car'                      => array(
				'load-image',
				'stm-cascadingdropdown',
				'uniform',
				'uniform-init',
				'jquery-ui-droppable',
				'stmselect2',
				'stm_gmap',
				'stm-google-places',
				'app-select2',
				'progress',
				'progressbar-layui',
				'progressbar',
			),
			'stm_listing_types_add_form'         => array(
				'load-image',
				'stm-cascadingdropdown',
				'uniform',
				'uniform-init',
				'jquery-ui-droppable',
				'stmselect2',
				'stm_gmap',
				'stm-google-places',
				'app-select2',
				'progress',
				'progressbar-layui',
				'progressbar',
			),
			'stm_compare_cars'                   => array( 'jquery-effects-slide' ),
			'stm_listing_types_compare'          => array( 'jquery-effects-slide' ),
			'stm_equip_search'                   => array( 'stm-cascadingdropdown', 'stmselect2', 'app-select2' ),
			//equip plugin
			'stm_wcmap_parts_search'             => array( 'stmselect2', 'app-select2' ),
			//auto-parts plugin
			'stm_events'                         => array( 'jquery.countdown.js' ),
			//stm-motors-events plugin
			'stm_aircraft_data_table'            => array( 'stm_aircraft_data_table' ),
			'stm_call_to_action_2'               => array( 'stm_call_to_action_2' ),
			'stm_car_leasing'                    => array( 'stm_car_leasing' ),
			'stm_cars_on_top'                    => array( 'cars_on_top' ),
			'stm_category_info_box'              => array( 'stm_category_info_box' ),
			'stm_color_separator'                => array( 'stm_color_separator' ),
			'stm_contact_form'                   => array( 'uniform', 'uniform-init', 'stm_contact_form' ),
			'stm_icon_counter'                   => array( 'stm-countUp.min.js', 'stm_icon_counter' ),
			'stm_icon_filter'                    => array( 'stm_icon_filter' ),
			'stm_image_filter_by_type'           => array( 'stm_image_filter_by_type' ),
			'stm_info_block_animate'             => array( 'stm_info_block_animate' ),
			'stm_info_box'                       => array( 'stm_info_box' ),
			'stm_inventory_no_filter'            => array( 'stm_inventory_no_filter', 'addtoany-core' ),
			'stm_inventory_search_results'       => array( 'stm_inventory_search_results' ),
			'stm_listing_banner'                 => array( 'stm_listing_banner' ),
			'stm_listing_categories_grid'        => array( 'stm_listing_categories_grid' ),
			'stm_listings_tabs_2'                => array( 'stm_listing_tabs_2' ),
			'stm_mm_top_categories'              => array( 'stm_mm_top_categories' ),
			'stm_mm_top_makes_tab'               => array( 'stm_mm_top_makes_tab' ),
			'stm_mm_top_vehicles'                => array( 'stm_mm_top_vehicles' ),
			'stm_our_team'                       => array( 'stm_our_team' ),
			'stm_popular_makes'                  => array( 'stm_popular_makes' ),
			'stm_popular_search'                 => array( 'stm_popular_searches' ),
			'stm_reduced_cars'                   => array( 'cars_on_top' ),
			'stm_testimonials'                   => array( 'stm_testimonials_carousel' ),
			'stm_login_register'                 => array(
				'uniform',
				'uniform-init',
			),
			'stm_sell_a_car'                     => array( 'stm_sell_a_car' ),
			'stm_single_car_data'                => array( 'stm_sell_a_car' ),
			'stm_moto_links'                     => array( 'stm_sell_a_car' ),
			'stm_equip_links'                    => array( 'stm_sell_a_car' ),
			'stm_ev_single_car_price_block'      => array( 'stm_sell_a_car' ),
			'stm_hero_slider'                    => array( 'stm_hero_slider', 'swiper-slider' ),
			'contact-form-7'                     => array( 'stmselect2', 'app-select2' ),
		);
	}

	public function get_scripts_map( $tag ) {
		if ( empty( $tag ) ) {
			return false;
		}

		if ( ! empty( $this->scripts_map[ $tag ] ) && is_array( $this->scripts_map[ $tag ] ) ) {
			return $this->scripts_map[ $tag ];
		} else {
			return false;
		}
	}

	public function load_script( $output, $tag ) {
		if ( $this->get_scripts_map( $tag ) ) {
			foreach ( $this->get_scripts_map( $tag ) as $file_name ) {

				if ( ( empty( get_transient( 'site_style_transient' ) ) || 'site_style_default' === get_transient( 'site_style_transient' ) ) && wp_style_is( $file_name, 'registered' ) ) {
					wp_enqueue_style( $file_name );
				}

				if ( wp_script_is( $file_name, 'registered' ) ) {
					wp_enqueue_script( $file_name );
				}
			}
		}

		return $output;
	}
}

new LoadScriptsForUse();
