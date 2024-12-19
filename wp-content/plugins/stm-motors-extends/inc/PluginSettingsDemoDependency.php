<?php

namespace STM_M_E;

class PluginSettingsDemoDependency {

	/**
	 * Default keys for unset on all demos
	 **/
	public $fields_to_hide = array();

	public $choosen_template;

	public function __construct() {
		$this->choosen_template = get_option( 'stm_motors_chosen_template' );

		add_action( 'plugins_loaded', array( $this, 'set_layout_type' ), 1000, 1 );

		if ( ! empty( $this->choosen_template ) && method_exists( $this, $this->choosen_template . '_conf' ) ) {
			add_filter( 'mvl_google_services_config', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'listing_settings_conf', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'pages_settings_main', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'search_settings_conf', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'mvl_shortcodes_config', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'single_listing_conf', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
			add_filter( 'mvl_user_dealer_options', array( $this, $this->choosen_template . '_conf' ), 1000, 1 );
		}

		add_filter( 'search_settings_conf', array( $this, 'remove_drop_down_listings_per_page_conf' ), 1000, 1 );
		add_filter( 'motors_filter_position', array( $this, 'unset_horizontal_filter' ), 1000, 1 );
		add_filter( 'disable_monetization_subscription', array( $this, 'remove_monetization' ), 1000, 1 );
		add_filter( 'disable_monetization_featured', array( $this, 'remove_monetization' ), 1000, 1 );
		add_filter( 'disable_monetization_paid_submission', array( $this, 'remove_monetization' ), 1000, 1 );
		add_filter( 'disable_monetization_paypal', array( $this, 'remove_monetization' ), 1000, 1 );
		add_filter( 'disable_monetization_sell_online', array( $this, 'remove_monetization_sell_online' ), 1000, 1 );
		add_filter( 'enable_loan_calculator', array( $this, 'remove_loan_calculator' ), 1000, 1 );
	}

	/*Example for copy*/
	public function for_copy_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'',
			)
		);
		return $this->unset_fields( $config );
	}

	public function car_dealer_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'listing_directory_title_default',
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'show_featured_btn',
				'pricing_link',
				'dealer_list_page',
				'show_listing_trade',
				'show_listing_quote',
				'show_listing_calculate',
				'show_listing_vin',
				'listing_grid_choices',
				'user_image_crop_optimized',
				'user_image_crop_checkbox',
				'user_image_crop_width',
				'user_image_crop_height',
				'user_image_size_limit',
				'allow_dealer_add_new_category',
				'stm_car_link_quote',
			)
		);

		return $this->unset_fields( $config );
	}

	public function car_dealer_elementor_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'listing_directory_title_default',
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'show_featured_btn',
				'pricing_link',
				'dealer_list_page',
				'show_listing_trade',
				'show_listing_quote',
				'show_listing_calculate',
				'show_listing_vin',
				'listing_grid_choices',
				'user_image_crop_optimized',
				'user_image_crop_checkbox',
				'user_image_crop_width',
				'user_image_crop_height',
				'user_image_size_limit',
				'allow_dealer_add_new_category',
				'stm_car_link_quote',
			)
		);

		return $this->unset_fields( $config );
	}

	public function car_dealer_two_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'show_featured_btn',
				'pricing_link',
				'dealer_list_page',
				'show_listing_trade',
				'show_listing_quote',
				'show_listing_calculate',
				'show_listing_vin',
				'listing_grid_choices',
				'user_image_crop_optimized',
				'user_image_crop_checkbox',
				'user_image_crop_width',
				'user_image_crop_height',
				'user_image_size_limit',
				'allow_dealer_add_new_category',
				'stm_car_link_quote',
				'show_quote_phone',
			)
		);

		return $this->unset_fields( $config );
	}

	public function car_dealer_two_elementor_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'show_featured_btn',
				'pricing_link',
				'dealer_list_page',
				'show_listing_trade',
				'show_listing_quote',
				'show_listing_calculate',
				'show_listing_vin',
				'listing_grid_choices',
				'user_image_crop_optimized',
				'user_image_crop_checkbox',
				'user_image_crop_width',
				'user_image_crop_height',
				'user_image_size_limit',
				'allow_dealer_add_new_category',
				'stm_car_link_quote',
				'show_quote_phone',
			)
		);

		return $this->unset_fields( $config );
	}

	public function motorcycle_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'show_listing_share',
				'show_listing_pdf',
				'show_listing_certified_logo_1',
				'show_listing_certified_logo_2',
				'show_featured_btn',
				'featured_listings_grid_amount',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'pricing_link',
				'dealer_list_page',
				'featured_listings_list_amount',
				'allow_dealer_add_new_category',
				'show_quote_phone',
			)
		);
		return $this->unset_fields( $config );
	}

	public function boats_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'show_listing_calculate',
				'show_certified_logo_1',
				'show_listing_certified_logo_1',
				'show_listing_certified_logo_2',
				'show_certified_logo_2',
				'pricing_link',
				'dealer_list_page',
				'listing_directory_enable_dealer_info',
				'enable_location',
				'enable_distance_search',
				'recommend_items_empty_result',
				'distance_measure_unit',
				'distance_search',
				'recommend_distance_measure_unit',
				'recommend_distance_search',
				'listing_grid_choices',
				'show_listing_pdf',
				'show_generated_title_as_label',
				'enable_favorite_items',
				'enable_carguru',
				'carguru_style',
				'carguru_min_rating',
				'carguru_default_height',
				'show_trade_in',
				'show_featured_btn',
				'show_offer_price',
				'show_pdf',
				'show_stock',
				'show_test_drive',
				'show_added_date',
				'show_vin',
				'show_registered',
				'show_listing_quote',
				'show_listing_trade',
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'listing_directory_title_default',
			)
		);
		return $this->unset_fields( $config );
	}

	public function ev_dealer_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'pricing_link',
				'dealer_list_page',
				'show_featured_btn',
				'enable_favorite_items',
				'show_listing_trade',
				'show_listing_calculate',
			)
		);
		return $this->unset_fields( $config );
	}

	public function aircrafts_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'pricing_link',
				'dealer_list_page',
				'show_listing_stock',
				'show_listing_trade',
				'show_listing_calculate',
				'enable_location',
				'enable_distance_search',
				'recommend_items_empty_result',
				'distance_measure_unit',
				'distance_search',
				'recommend_distance_measure_unit',
				'recommend_distance_search',
				'listing_grid_choices',
				'allow_dealer_add_new_category',
				'show_stock',
				'show_vin',
				'show_registered',
				'enable_carguru',
				'carguru_style',
				'carguru_min_rating',
				'carguru_default_height',
				'show_test_drive',
				'show_added_date',
				'show_listing_quote',
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
				'enable_favorite_items',
				'show_featured_btn',
			)
		);

		return $this->unset_fields( $config );
	}

	public function equipment_conf( $config ) {
		$this->fields_to_hide = array_merge(
			$this->fields_to_hide,
			array(
				'pricing_link',
				'dealer_list_page',
				'show_listing_certified_logo_1',
				'show_listing_certified_logo_2',
				'allow_dealer_add_new_category',
				'listing_directory_enable_dealer_info',
				'enable_favorite_items',
				'show_featured_btn',
				'show_listing_quote',
				'show_listing_calculate',
				'show_offer_price',
				'show_stock',
				'show_added_date',
				'show_registered',
				'enable_carguru',
				'carguru_style',
				'carguru_min_rating',
				'carguru_default_height',
				'listing_view_type',
				'listing_view_type_mobile',
				'show_generated_title_as_label',
				'listing_directory_enable_dealer_info',
				'show_listing_stock',
				'show_listing_test_drive',
				'show_listing_share',
				'show_listing_pdf',
				'show_listing_quote',
				'show_listing_trade',
				'enable_location',
				'enable_distance_search',
				'distance_measure_unit',
				'distance_search',
				'recommend_items_empty_result',
				'recommend_distance_measure_unit',
				'recommend_distance_search',
				'show_pdf',
				'featured_listings_list_amount',
				'featured_listings_grid_amount',
			)
		);
		return $this->unset_fields( $config );
	}

	public function unset_horizontal_filter( $filters ) {
		if ( 'boats' !== $this->choosen_template ) {
			unset( $filters['horizontal'] );
		}

		return $filters;
	}

	public function remove_monetization() {
		$layouts = array(
			'car_dealer',
			'motorcycle',
			'boats',
			'ev_dealer',
			'aircrafts',
			'equipment',
			'car_dealer_elementor',
			'car_dealer_elementor_rtl',
			'service',
			'car_magazine',
			'car_dealer_two',
			'car_dealer_two_elementor',
		);

		if ( in_array( $this->choosen_template, $layouts, true ) ) {
			return true;
		}

		return false;
	}

	public function set_layout_type() {
		$layouts = array(
			'car_dealer',
			'car_dealer_elementor',
			'car_dealer_elementor_rtl',
			'car_dealer_two',
			'car_dealer_two_elementor',
			'motorcycle',
			'boats',
			'ev_dealer',
			'aircrafts',
			'equipment',
			'service',
			'car_magazine',
			'car_rental',
			'car_rental_elementor',
			'rental_two',
		);

		if ( empty( get_option( 'motors_layout_type', '' ) ) ) {
			if ( in_array( $this->choosen_template, $layouts, true ) ) {
				update_option( 'motors_layout_type', 'dealership' );
			} else {
				update_option( 'motors_layout_type', 'classified' );
			}
		}
	}

	public function remove_monetization_sell_online() {
		$layouts = array(
			'car_dealer_two',
			'car_dealer_two_elementor',
		);

		if ( in_array( $this->choosen_template, $layouts, true ) ) {
			return false;
		}

		return true;
	}

	public function remove_drop_down_listings_per_page_conf( $config ) {
		$layouts = array(
			'listing',
			'listing_two',
			'listing_three',
			'listing_four',
			'listing_five',
			'listing_one_elementor',
			'listing_two_elementor',
			'listing_three_elementor',
			'listing_four_elementor',
			'listing_five_elementor',
		);

		if ( in_array( $this->choosen_template, $layouts, true ) ) {
			$this->fields_to_hide = array_merge(
				$this->fields_to_hide,
				array(
					'listing_grid_choices',
				)
			);

			return $this->unset_fields( $config );
		}

		return $config;
	}

	public function remove_loan_calculator() {
		return apply_filters( 'stm_is_motorcycle', false );
	}

	public function unset_fields( $config ) {
		foreach ( $this->fields_to_hide as $field ) {
			unset( $config[ $field ] );
		}

		return $config;
	}
}

new PluginSettingsDemoDependency();
