<?php
function motors_get_demos() {
	$demos = array(
		'ev_dealer'                => array(
			'label'    => esc_html__( 'ELECTRIC VEHICLE DEALERSHIP', 'motors' ),
			'slug'     => 'car_dealer',
			'live_url' => 'ev-dealership/',
			'builder'  => 'js_composer',
		),
		'car_dealer'               => array(
			'label'    => esc_html__( 'CAR DEALERSHIP ONE', 'motors' ),
			'slug'     => 'car_dealer',
			'live_url' => '',
			'builder'  => 'js_composer',
		),
		'car_dealer_two'           => array(
			'label'    => esc_html__( 'CAR DEALERSHIP TWO', 'motors' ),
			'slug'     => 'car_dealer_two',
			'live_url' => 'dealer-two/',
			'builder'  => 'js_composer',
		),
		'listing'                  => array(
			'label'    => esc_html__( 'CLASSIFIED LISTING', 'motors' ),
			'slug'     => 'classified_listing',
			'live_url' => 'classified/',
			'builder'  => 'js_composer',
		),
		'listing_two'              => array(
			'label'    => esc_html__( 'CLASSIFIED LISTING 2', 'motors' ),
			'slug'     => 'classified_listing_two',
			'live_url' => 'classified-two/',
			'builder'  => 'js_composer',
		),
		'listing_three'            => array(
			'label'    => esc_html__( 'CLASSIFIED LISTING 3', 'motors' ),
			'slug'     => 'classified_listing_three',
			'live_url' => 'classified-three/',
			'builder'  => 'js_composer',
		),
		'car_rental'               => array(
			'label'    => esc_html__( 'RENT A CAR SERVICE', 'motors' ),
			'slug'     => 'car_rental',
			'live_url' => 'rent-a-car/',
			'builder'  => 'js_composer',
		),
		'car_rental_elementor'     => array(
			'label'    => esc_html__( 'RENT A CAR SERVICE ELEMENTOR', 'motors' ),
			'slug'     => 'car_rental_elementor',
			'live_url' => 'elementor-rent-a-car/',
			'builder'  => 'elementor',
		),
		'motorcycle'               => array(
			'label'    => esc_html__( 'MOTORCYCLES DEALERS', 'motors' ),
			'slug'     => 'motorcycle',
			'live_url' => 'motorcycles/',
			'builder'  => 'js_composer',
		),
		'boats'                    => array(
			'label'    => esc_html__( 'BOATS DEALERSHIP', 'motors' ),
			'slug'     => 'boats',
			'live_url' => 'boats/',
			'builder'  => 'js_composer',
		),
		'service'                  => array(
			'label'    => esc_html__( 'CAR REPAIR SERVICE', 'motors' ),
			'slug'     => 'car_repair_service',
			'live_url' => 'car-repair-service/',
			'builder'  => 'js_composer',
		),
		'car_magazine'             => array(
			'label'    => esc_html__( 'CAR MAGAZINE', 'motors' ),
			'slug'     => 'car_magazine',
			'live_url' => 'magazine/',
			'builder'  => 'js_composer',
		),
		'auto_parts'               => array(
			'label'    => esc_html__( 'AUTO PARTS', 'motors' ),
			'slug'     => 'auto_parts',
			'live_url' => 'auto-parts/',
			'builder'  => 'js_composer',
		),
		'listing_four'             => array(
			'label'    => esc_html__( 'CLASSIFIED LISTING 4', 'motors' ),
			'slug'     => 'classified_listing_four',
			'live_url' => 'classified-four/',
			'builder'  => 'js_composer',
		),
		'aircrafts'                => array(
			'label'    => esc_html__( 'AIRCRAFTS', 'motors' ),
			'slug'     => 'aircrafts',
			'live_url' => 'aircrafts',
			'builder'  => 'js_composer',
		),
		'rental_two'               => array(
			'label'    => esc_html__( 'RENT A CAR TWO', 'motors' ),
			'slug'     => 'car_rental_two',
			'live_url' => 'rental-two/',
			'builder'  => 'js_composer',
		),
		'equipment'                => array(
			'label'    => esc_html__( 'EQUIPMENT', 'motors' ),
			'slug'     => 'equipment',
			'live_url' => 'equipment/',
			'builder'  => 'js_composer',
		),
		'listing_five'             => array(
			'label'    => esc_html__( 'LISTING FIVE', 'motors' ),
			'slug'     => 'classified_listing_five',
			'live_url' => 'classified-five/',
			'builder'  => 'js_composer',
		),
		'listing_five_elementor'   => array(
			'label'    => esc_html__( 'ELEMENTOR LISTING FIVE', 'motors' ),
			'slug'     => 'listing_five_elementor',
			'live_url' => 'elementor-classified-five/',
			'builder'  => 'elementor',
		),
		'car_dealer_elementor'     => array(
			'label'    => esc_html__( 'ELEMENTOR CAR DEALERSHIP ONE', 'motors' ),
			'slug'     => 'car_dealer_elementor',
			'live_url' => 'elementor-dealer-one/',
			'builder'  => 'elementor',
		),
		'listing_one_elementor'    => array(
			'label'    => esc_html__( 'ELEMENTOR CLASSIFIED ONE', 'motors' ),
			'slug'     => 'listing_one_elementor',
			'live_url' => 'elementor-classified-one/',
			'builder'  => 'elementor',
		),
		'car_dealer_two_elementor' => array(
			'label'    => esc_html__( 'ELEMENTOR CAR DEALERSHIP TWO', 'motors' ),
			'slug'     => 'car_dealer_two_elementor',
			'live_url' => 'elementor-dealer-two/',
			'builder'  => 'elementor',
		),
		'listing_four_elementor'   => array(
			'label'    => esc_html__( 'ELEMENTOR CLASSIFIED FOUR', 'motors' ),
			'slug'     => 'classified_listing_four_elementor',
			'live_url' => 'elementor-classified-four/',
			'builder'  => 'elementor',
		),
		'listing_three_elementor'  => array(
			'label'    => esc_html__( 'ELEMENTOR CLASSIFIED THREE', 'motors' ),
			'slug'     => 'listing_three_elementor',
			'live_url' => 'elementor-classified-three/',
			'builder'  => 'elementor',
		),
		'listing_two_elementor'    => array(
			'label'    => esc_html__( 'ELEMENTOR CLASSIFIED TWO', 'motors' ),
			'slug'     => 'listing_two_elementor',
			'live_url' => 'elementor-classified-two/',
			'builder'  => 'elementor',
		),
		'car_dealer_elementor_rtl' => array(
			'label'    => esc_html__( 'ELEMENTOR CAR DEALERSHIP ONE RTL', 'motors' ),
			'slug'     => 'car_dealer_elementor_rtl',
			'live_url' => 'elementor-dealer-one-rtl/',
			'builder'  => 'elementor',
		),
	);

	return $demos;
}

function get_stm_theme_layout_plugins( $layout ) {
	return motors_layout_plugins( $layout );
}

function motors_layout_plugins( $layout, $get_layouts = false ) {
	$required = array(
		'envato-market',
		'stm-motors-extends',
		'stm_importer',
		'breadcrumb-navxt',
		'contact-form-7',
		'mailchimp-for-wp',
	);

	$plugins = array(
		'car_magazine'             => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'stm_motors_events',
			'add-to-any',
			'stm_motors_review',
			'stm-elementor-icons',
			'revslider',
		),
		'service'                  => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'spotlight-social-photo-feeds',
			'bookly-responsive-appointment-booking-tool',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'listing'                  => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
		),
		'listing_two'              => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'woocommerce',
			'add-to-any',
			'stm_motors_review',
			'stm-elementor-icons',
			'revslider',
		),
		'listing_three'            => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'woocommerce',
			'add-to-any',
			'stm_motors_review',
			'stm-elementor-icons',
			'revslider',
		),
		'listing_four'             => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'listing_four_elementor'   => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'listing_five'             => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'subscriptio',
			'woocommerce',
			'motors-listing-types',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'listing_five_elementor'   => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'subscriptio',
			'woocommerce',
			'motors-listing-types',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'ev_dealer'                => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'car_dealer'               => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'car_dealer_two'           => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'motorcycle'               => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'woocommerce',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'boats'                    => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'woocommerce',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'car_rental'               => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'spotlight-social-photo-feeds',
			'woocommerce',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'car_rental_elementor'     => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'spotlight-social-photo-feeds',
			'woocommerce',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'auto_parts'               => array(
			'stm-woocommerce-motors-auto-parts',
			'motors-wpbakery-widgets',
			'woo-multi-currency',
			'yith-woocommerce-compare',
			'yith-woocommerce-wishlist',
			'woocommerce',
			'add-to-any',
			'stm-elementor-icons',
			'revslider',
		),
		'aircrafts'                => array(
			'motors-car-dealership-classified-listings',
			'motors-wpbakery-widgets',
			'stm-megamenu',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'rental_two'               => array(
			'stm-motors-car-rental',
			'motors-wpbakery-widgets',
			'woocommerce',
			'stm-elementor-icons',
			'revslider',
		),
		'equipment'                => array(
			'spotlight-social-photo-feeds',
			'motors-wpbakery-widgets',
			'motors-car-dealership-classified-listings',
			'stm-motors-equipment',
			'stm-megamenu',
			'stm-elementor-icons',
			'add-to-any',
			'revslider',
		),
		'car_dealer_elementor'     => array(
			'stm-megamenu',
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'woocommerce',
			'stm-elementor-icons',
			'add-to-any',
			'revslider',
		),
		'car_dealer_two_elementor' => array(
			'stm-megamenu',
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'woocommerce',
			'stm-elementor-icons',
			'add-to-any',
			'revslider',
		),
		'listing_one_elementor'    => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
		),
		'listing_two_elementor'    => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'stm_motors_review',
		),
		'listing_three_elementor'  => array(
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'stm-megamenu',
			'spotlight-social-photo-feeds',
			'subscriptio',
			'add-to-any',
			'woocommerce',
			'stm-elementor-icons',
			'stm_motors_review',
		),
		'car_dealer_elementor_rtl' => array(
			'stm-megamenu',
			'motors-car-dealership-classified-listings',
			'motors-elementor-widgets',
			'woocommerce',
			'stm-elementor-icons',
			'add-to-any',
			'revslider',
		),
	);

	// compatibility for old users with active uListing plugin.
	if ( defined( 'ULISTING_VERSION' ) ) {
		$plugins['listing_five'] = array(
			'stm-motors-classified-five',
		);
	}

	if ( $get_layouts ) {
		return $plugins;
	}

	return array_merge( $required, $plugins[ $layout ] );
}

add_filter(
	'after_install_plugin',
	function ( $layout, $plugin_slug ) {
		if ( 'rental_two' === $layout && 'woocommerce' === $plugin_slug ) {
			do_action( 'stm_importer_create_taxonomy' );
			wp_send_json_error( null, 503 );
		}

		if ( 'equipment' === $layout && 'stm-motors-equipment' === $plugin_slug ) {
			stm_update_equipment_listings_options();
			wp_send_json_error( null, 503 );
		}

		// reload page after installing Motors Listing Types plugin.
		if ( ( 'listing_five' === $layout || 'listing_five_elementor' === $layout ) && 'motors-listing-types' === $plugin_slug && empty( get_option( 'stm_motors_listing_types' ) ) ) {
			stm_import_multilisting_data_classified_five( 'listing_five_elementor' === $layout );
			wp_send_json_error( null, 503 );
		}
	},
	2,
	50
);

function stm_import_multilisting_data_classified_five( $is_elementor ) {
	// multilisting settings.
	if ( $is_elementor ) {
		$multilisting_json = '{"multilisting_repeater":[{"label":"Motos","slug":"moto","inventory_page":"92624","add_page":"92640","icon":{"icon":"stm-moto-icon-motorcycle","color":"#000","size":15}},{"label":"Aircraft","slug":"aircraft","inventory_page":"92618","add_page":"92641","icon":{"icon":"fa fa-plane","color":"#000","size":15}}],"multilisting_current_motors_layout":"listing_five","moto_inventory_custom_settings":true,"moto_classic_listing_title":"Moto Archive","moto_hide_price_labels":true,"moto_listing_directory_title_default":"Motorcycles for sale","moto_listing_view_type":"list","moto_listing_view_type_mobile":"grid","moto_grid_title_max_length":"44","moto_enable_features_search":true,"moto_enable_favorite_items":true,"moto_multilisting_sort_options":["moto-price","moto-mileage","price"],"moto_multilisting_default_sort_by":"date_high","moto_enable_location":true,"moto_distance_measure_unit":"miles","moto_distance_search":"","moto_listing_directory_title_frontend":"","moto_show_generated_title_as_label":"","moto_listing_directory_enable_dealer_info":"","moto_show_listing_stock":"","moto_show_listing_test_drive":"","moto_show_listing_compare":true,"moto_show_listing_share":true,"moto_show_listing_pdf":true,"moto_show_listing_certified_logo_1":"","moto_show_listing_certified_logo_2":"","moto_sidebar_filter_bg":"","moto_show_sold_listings":true,"moto_sold_badge_bg_color":"#fc4e4e","moto_dealer_pay_per_listing":true,"moto_pay_per_listing_price":"","moto_pay_per_listing_period":"30","moto_dealer_payments_for_featured_listing":true,"moto_featured_listing_default_badge":"","moto_featured_listing_price":"","moto_featured_listing_period":"30","moto_single_custom_settings":false,"moto_addl_group_ds_title":"","moto_addl_title":"Build Your Ad","moto_addl_description":"<p>You are currently building a Free Ad.<\/p><p><strong>Items marked with * are required fields.<\/strong><\/p>","moto_addl_show_slots":true,"moto_addl_slots_title":"Slots available","moto_addl_group_lt_title":"","moto_addl_car_title":true,"moto_addl_group_details_title":"","moto_addl_required_fields":["moto-condition","moto-body","moto-make","moto-model"],"moto_addl_number_as_input":true,"moto_addl_show_registered":true,"moto_addl_show_vin":true,"moto_addl_show_history":true,"moto_addl_history_report":"Carfax, AutoCheck","moto_addl_details_location":true,"moto_addl_group_features_title":"","moto_addl_group_gallery_title":"","moto_addl_gallery_content":"<h4>RECOMMENDATION &amp; GUIDES<\/h4><p><span style=\"color: rgb(34, 34, 34);\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam mollis convallis semper. Proin risus felis, mollis at augue non, tincidunt eleifend ipsum.<\/span><\/p><p>Recomended Image Resolution: 800&nbsp;x 470 px or higher<\/p><p><span style=\"color: rgb(136, 136, 136);\">Pellentesque non lorem auctor sem commodo blandit. Vestibulum posuere sit amet mi quis luctus. Proin placerat purus metus, vel consequat tellus efficitur ve<\/span>l.<\/p><p>User can upload up to 5 photos, but dealer can upload up to 10 photos.<\/p><p><span style=\"color: rgb(136, 136, 136);\">elementum, in vulputate nunc maximus. Nulla egestas blandit laoreet. Nam egestas odio non nisi ullamcorper convallis. Sed eu consectetur orci. Cras vulputate nisl sapien, vitae rhoncus est aliquet sit amet.<\/span><\/p><p><strong>Maximum image size 4096 kb<\/strong><\/p>","moto_addl_group_video_title":"","moto_addl_video_content":"<p><span style=\"background-color: rgb(246, 249, 252);\">If you don\'t have the videos handy, don\'t worry. You can add or edit them after you complete your ad using the \"Manage Your Ad\" page.<\/span><span class=\"ql-cursor\">\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff\ufeff<\/span><\/p>","moto_addl_group_seller_note_title":"","moto_addl_seller_note_content":"<p><span style=\"background-color: rgb(246, 249, 252);\">Excellent condition, Always garaged, No accidents, Satellite radio, Must see, Factory GPS system<\/span><\/p>","moto_addl_group_plans_title":"","moto_addl_group_price_title":"","moto_addl_price_title":"Price your vehicle competitively.","moto_addl_price_label_text":"","moto_addl_show_sale_price_label":"","moto_addl_sale_price_label":"","moto_addl_show_custom_label":"","moto_addl_price_desc":"<p><span style=\"background-color: rgb(246, 249, 252);\">Determine a competitive price by comparing your vehicle\'s information and mileage to similar vehicles for sale by dealers and private sellers in your area. Then consider pricing your vehicle within range. Be sure to provide Seller\'s Comments and photos to highlight the best features of your vehicle, especially if your asking price is above average.<\/span><\/p>","moto_addl_group_reg_login_title":"","moto_sorted_steps":"","moto_fs_user_features":[{"tab_title_single":"Comfort","tab_title_selected_labels":[{"label":"Bluetooth","value":"bluetooth"},{"label":"Wiper Tinted Glass","value":"wiper-tinted-glass"},{"label":"Aerodynamic Efficiency","value":"aerodynamic-efficiency"},{"label":"Flat Repair Kit","value":"flat-repair-kit"}]},{"closed_tab":true,"tab_title_single":"Technical","tab_title_selected_labels":[{"label":"ABS","value":"abs"},{"label":"Dis-Tronic","value":"dis-tronic"},{"label":"Fog Lights","value":"fog-lights"},{"label":"Traction Control","value":"traction-control"}]},{"closed_tab":true,"tab_title_single":"Safety","tab_title_selected_labels":[{"label":"Safer helmets","value":"safer-helmets"},{"label":"Modern Disc Brakes","value":"modern-disc-brakes"},{"label":"Tank Bag","value":"tank-bag"},{"label":"Liquid Cooling","value":"liquid-cooling"}]},{"closed_tab":true,"tab_title_single":"Other","tab_title_selected_labels":[{"label":"Sports suspension","value":"sports-suspension"},{"label":"Sports package","value":"sports-package"},{"label":"Urban kit","value":"urban-kit"},{"label":"Performance Tyres","value":"performance-tyres"}]}],"aircraft_inventory_custom_settings":true,"aircraft_classic_listing_title":"Inventory","aircraft_hide_price_labels":"","aircraft_listing_directory_title_default":"Airs for sale","aircraft_listing_view_type":"list","aircraft_listing_view_type_mobile":"grid","aircraft_grid_title_max_length":"44","aircraft_enable_features_search":"","aircraft_enable_favorite_items":"","aircraft_multilisting_sort_options":["price","max-speed","maximum-range","tt"],"aircraft_multilisting_default_sort_by":"date_high","aircraft_enable_location":"","aircraft_distance_measure_unit":"","aircraft_distance_search":"","aircraft_listing_directory_title_frontend":"","aircraft_show_generated_title_as_label":"","aircraft_listing_directory_enable_dealer_info":"","aircraft_show_listing_stock":"","aircraft_show_listing_test_drive":"","aircraft_show_listing_compare":true,"aircraft_show_listing_share":"","aircraft_show_listing_pdf":"","aircraft_show_listing_certified_logo_1":"","aircraft_show_listing_certified_logo_2":"","aircraft_sidebar_filter_bg":"","aircraft_show_sold_listings":"","aircraft_sold_badge_bg_color":"#fc4e4e","aircraft_dealer_pay_per_listing":true,"aircraft_pay_per_listing_price":"","aircraft_pay_per_listing_period":"30","aircraft_dealer_payments_for_featured_listing":false,"aircraft_featured_listing_default_badge":"","aircraft_featured_listing_price":"","aircraft_featured_listing_period":"30","aircraft_single_custom_settings":false,"aircraft_addl_group_ds_title":"","aircraft_addl_title":"Build Your Ad","aircraft_addl_description":"<p>You are currently building a Free Ad.<\/p><p><strong>Items marked with * are required fields.<\/strong><\/p>","aircraft_addl_show_slots":true,"aircraft_addl_slots_title":"Slots available","aircraft_addl_group_lt_title":"","aircraft_addl_car_title":true,"aircraft_addl_group_details_title":"","aircraft_addl_required_fields":["aircraft-body","aircraft-condition"],"aircraft_addl_number_as_input":true,"aircraft_addl_show_registered":true,"aircraft_addl_show_vin":true,"aircraft_addl_show_history":true,"aircraft_addl_history_report":"Carfax, AutoCheck","aircraft_addl_details_location":true,"aircraft_addl_group_features_title":"","aircraft_addl_group_gallery_title":"","aircraft_addl_gallery_content":"<h4>RECOMMENDATION &amp; GUIDES<\/h4><p><span style=\"color: rgb(34, 34, 34);\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam mollis convallis semper. Proin risus felis, mollis at augue non, tincidunt eleifend ipsum.<\/span><\/p><p><strong>Recomended Image Resolution: 800&nbsp;x 470 px or higher<\/strong><\/p><p><span style=\"color: rgb(136, 136, 136);\">Pellentesque non lorem auctor sem commodo blandit. Vestibulum posuere sit amet mi quis luctus. Proin placerat purus metus, vel consequat tellus efficitur ve<\/span>l.<\/p><p><strong>User can upload up to 5 photos, but dealer can upload up to 10 photos.<\/strong><\/p><p><span style=\"color: rgb(136, 136, 136);\">elementum, in vulputate nunc maximus. Nulla egestas blandit laoreet. Nam egestas odio non nisi ullamcorper convallis. Sed eu consectetur orci. Cras vulputate nisl sapien, vitae rhoncus est aliquet sit amet.<\/span><\/p><p><strong>Maximum image size 4096 kb<\/strong><\/p>","aircraft_addl_group_video_title":"","aircraft_addl_video_content":"<p>If you don\'t have the videos handy, don\'t worry. You can add or edit them after you complete your ad using the \"Manage Your Ad\" page.<\/p>","aircraft_addl_group_seller_note_title":"","aircraft_addl_seller_note_content":"<p><span style=\"background-color: rgb(246, 249, 252);\">Excellent condition, Always garaged, No accidents, Satellite radio, Must see, Factory GPS system<\/span><\/p>","aircraft_addl_group_plans_title":"","aircraft_addl_group_price_title":"","aircraft_addl_price_title":"Price your vehicle competitively.","aircraft_addl_price_label_text":"","aircraft_addl_show_sale_price_label":"","aircraft_addl_sale_price_label":"","aircraft_addl_show_custom_label":"","aircraft_addl_price_desc":"<p>Determine a competitive price by comparing your vehicle\'s information and mileage to similar vehicles for sale by dealers and private sellers in your area. Then consider pricing your vehicle within range. Be sure to provide Seller\'s Comments and photos to highlight the best features of your vehicle, especially if your asking price is above average.<\/p>","aircraft_addl_group_reg_login_title":"","aircraft_sorted_steps":"","aircraft_fs_user_features":[{"tab_title_single":"Systems","tab_title_selected_labels":[{"label":"228 Gallon Fuel System","value":"228-gallon-fuel-system"},{"label":"Emergency Vision Assurance System (EVAS)","value":"emergency-vision-assurance-system-evas"},{"label":"Steam\/convection oven","value":"steam-convection-oven"},{"label":"Runway Awareness Advisory System (RAAS)","value":"runway-awareness-advisory-system-raas"}]},{"closed_tab":true,"tab_title_single":"Sensors","tab_title_selected_labels":[{"label":"ADS-B out","value":"ads-b-out"},{"label":"Left and Right Landing Lights","value":"left-and-right-landing-lights"},{"label":"WAAS\/LPV","value":"waas-lpv"},{"label":"Spare Face Shield\/Visor","value":"spare-face-shield-visor"}]},{"closed_tab":true,"tab_title_single":"Safety","tab_title_selected_labels":[{"label":"Runway Awareness Advisory System (RAAS)","value":"runway-awareness-advisory-system-raas"},{"label":"Wingtip Nav and Strobe Lights","value":"wingtip-nav-and-strobe-lights"},{"label":"Security System","value":"security-system"},{"label":"Quartz polymer countertops in galley and lavatories","value":"quartz-polymer-countertops-in-galley-and-lavatories"}]},{"closed_tab":true,"tab_title_single":"Comfort","tab_title_selected_labels":[{"label":"DVD System","value":"dvd-system"},{"label":"Heated Seats","value":"heated-seats"},{"label":"Head-up display","value":"head-up-display"},{"label":"Premium Audio","value":"premium-audio"}]}]}';
	} else {
		$multilisting_json = '{"multilisting_repeater":[{"label":"Motos","slug":"moto","inventory_page":"887","add_page":"1034","icon":{"icon":"stm-moto-icon-motorcycle","color":"#000","size":15}},{"label":"Aircraft","slug":"aircraft","inventory_page":"891","add_page":"944","icon":{"icon":"fa fa-plane","color":"#000","size":15}}],"multilisting_current_motors_layout":"listing_five","moto_inventory_custom_settings":true,"moto_classic_listing_title":"Moto Archive","moto_hide_price_labels":false,"moto_listing_directory_title_default":"Motorcycles for sale","moto_listing_sidebar":"no_sidebar","moto_listing_view_type":"list","moto_grid_title_max_length":"44","moto_enable_search":"","moto_enable_features_search":true,"moto_enable_favorite_items":true,"moto_multilisting_sort_options":["moto-mileage","moto-price"],"moto_multilisting_default_sort_by":"date_high","moto_listing_filter_position":"left","moto_enable_location":"","moto_distance_measure_unit":"","moto_distance_search":"","moto_listing_directory_title_frontend":"","moto_show_generated_title_as_label":"","moto_listing_directory_enable_dealer_info":"","moto_show_listing_stock":"","moto_show_listing_test_drive":"","moto_show_listing_compare":true,"moto_show_listing_share":true,"moto_show_listing_pdf":true,"moto_show_listing_certified_logo_1":"","moto_show_listing_certified_logo_2":"","moto_sidebar_filter_bg":"","moto_show_sold_listings":true,"moto_sold_badge_bg_color":"#fc4e4e","moto_dealer_pay_per_listing":"","moto_pay_per_listing_price":"","moto_pay_per_listing_period":"30","moto_dealer_payments_for_featured_listing":"","moto_featured_listing_default_badge":"","moto_featured_listing_price":"","moto_featured_listing_period":"30","moto_single_custom_settings":false,"moto_show_trade_in":"","moto_show_offer_price":"","moto_stm_car_link_quote":"#1578032597180-dca29e61-e895","moto_show_calculator":"","moto_show_added_date":"","moto_show_print_btn":"","moto_show_test_drive":"","moto_show_stock":"","moto_show_compare":"","moto_show_share":"","moto_show_pdf":"","moto_show_certified_logo_1":"","moto_show_certified_logo_2":"","moto_show_featured_btn":"","moto_show_vin":"","moto_show_registered":"","moto_show_history":"","moto_stm_show_number":"","aircraft_inventory_custom_settings":"","aircraft_classic_listing_title":"Inventory","aircraft_hide_price_labels":"","aircraft_listing_directory_title_default":"Cars for sale","aircraft_listing_sidebar":"no_sidebar","aircraft_listing_view_type":"list","aircraft_grid_title_max_length":"44","aircraft_enable_search":"","aircraft_enable_features_search":"","aircraft_enable_favorite_items":"","aircraft_multilisting_sort_options":"","aircraft_multilisting_default_sort_by":"date_high","aircraft_listing_filter_position":"left","aircraft_enable_location":"","aircraft_distance_measure_unit":"","aircraft_distance_search":"","aircraft_listing_directory_title_frontend":"","aircraft_show_generated_title_as_label":"","aircraft_listing_directory_enable_dealer_info":"","aircraft_show_listing_stock":"","aircraft_show_listing_test_drive":"","aircraft_show_listing_compare":"","aircraft_show_listing_share":"","aircraft_show_listing_pdf":"","aircraft_show_listing_certified_logo_1":"","aircraft_show_listing_certified_logo_2":"","aircraft_sidebar_filter_bg":"","aircraft_show_sold_listings":"","aircraft_sold_badge_bg_color":"#fc4e4e","aircraft_dealer_pay_per_listing":"","aircraft_pay_per_listing_price":"","aircraft_pay_per_listing_period":"30","aircraft_dealer_payments_for_featured_listing":"","aircraft_featured_listing_default_badge":"","aircraft_featured_listing_price":"","aircraft_featured_listing_period":"30","aircraft_single_custom_settings":"","aircraft_show_trade_in":"","aircraft_show_offer_price":"","aircraft_stm_car_link_quote":"#1578032597180-dca29e61-e895","aircraft_show_calculator":"","aircraft_show_added_date":"","aircraft_show_print_btn":"","aircraft_show_test_drive":"","aircraft_show_stock":"","aircraft_show_compare":"","aircraft_show_share":"","aircraft_show_pdf":"","aircraft_show_certified_logo_1":"","aircraft_show_certified_logo_2":"","aircraft_show_featured_btn":"","aircraft_show_vin":"","aircraft_show_registered":"","aircraft_show_history":"","aircraft_stm_show_number":""}';
	}
	$multilisting_array = json_decode( $multilisting_json, true );
	update_option( 'stm_motors_listing_types', $multilisting_array );

	// moto category settings.
	$moto_json  = '[{"single_name":"Condition","plural_name":"Conditions","slug":"moto-condition","font":"fas fa-air-freshener","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Body","plural_name":"Bodies","slug":"moto-body","font":"stm-moto-icon-helm","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Make","plural_name":"Makes","slug":"moto-make","font":"stm-moto-icon-motorcycle","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Model","plural_name":"Models","slug":"moto-model","font":"stm-boats-icon-helm","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"moto-make","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Mileage","plural_name":"Mileages","slug":"moto-mileage","font":"stm-boats-icon-remove-from-compare","numeric":1,"number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":"","use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Fuel type","plural_name":"Fuel types","slug":"moto-fuel","font":"stm-boats-icon-fuel","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Engine","plural_name":"Engines","slug":"moto-engine","font":"stm-boats-icon-performance","numeric":1,"number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":"","use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Year","plural_name":"Years","slug":"moto-year","font":"fas fa-calendar-alt","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":"","use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Price","plural_name":"Prices","slug":"moto-price","font":"stm-service-icon-cash_dollar","numeric":1,"number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":1,"use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":"","use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Color","plural_name":"Colors","slug":"moto-color","font":"fas fa-palette","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Drive","plural_name":"Drives","slug":"moto-drive","font":"stm-boats-icon-shackle","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Transmission","plural_name":"Transmissions","slug":"moto-transmission","font":"stm-boats-icon-shackle","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1}]';
	$moto_array = json_decode( $moto_json, true );
	update_option( 'stm_moto_options', $moto_array );

	// aircraft category settings.
	$aircraft_json  = '[{"single_name":"Condition","plural_name":"Conditions","slug":"aircraft-condition","font":"fas fa-air-freshener","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Body","plural_name":"Bodies","slug":"aircraft-body","font":"fas fa-fighter-jet","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Make","plural_name":"Makes","slug":"aircraft-make","font":"stm-boats-icon-fan","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Model","plural_name":"Models","slug":"aircraft-model","font":"stm-boats-icon-engine-repairs","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"aircraft-make","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Fuel type","plural_name":"Fuel types","slug":"aircraft-fuel","font":"stm-boats-icon-fuel","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":"","use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Engine","plural_name":"Engines","slug":"aircraft-engine","font":"stm-service-icon-cog","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Year","plural_name":"Years","slug":"aircraft-year","font":"fas fa-hourglass-end","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Price","plural_name":"Prices","slug":"aircraft-price","font":"fas fa-dollar-sign","numeric":1,"number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":1,"use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Color","plural_name":"Colors","slug":"aircraft-color","font":"fas fa-palette","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":1,"use_on_car_archive_listing_page":1,"use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Passenger","plural_name":"Passengers","slug":"aircraft-passenger","font":"fas fa-users","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1},{"single_name":"Cockpit","plural_name":"Cockpits","slug":"aircraft-cockpit","font":"fas fa-plane-departure","numeric":"","number_field_affix":"","slider_in_tabs":"","slider":"","slider_step":"10","use_delimiter":"","listing_price_field":"","use_on_car_listing_page":"","use_on_car_archive_listing_page":"","use_on_single_car_page":1,"use_on_car_filter":1,"use_on_tabs":1,"use_on_car_modern_filter":1,"use_on_car_modern_filter_view_images":"","use_on_car_filter_links":"","filter_links_default_expanded":"open","use_in_footer_search":"","use_on_directory_filter_title":"","use_on_single_listing_page":"","listing_taxonomy_parent":"","listing_rows_numbers_enable":"","listing_rows_numbers":"","enable_checkbox_button":"","listing_rows_numbers_default_expanded":"open","show_in_admin_column":"","use_on_map_page":1}]';
	$aircraft_array = json_decode( $aircraft_json, true );
	update_option( 'stm_aircraft_options', $aircraft_array );

	// subscriptio settings.
	$subscriptio_json  = '{"1":{"add_to_cart_label":"","multiple_product_checkout":"multiple_subscriptions","display_empty_subscription_list":"1","sale_price_is_recurring":"1","cart_discounts_are_recurring":"0","checkout_fees_are_recurring":"0","shipping_is_recurring":"1","signup_fees_per_item":"0","subscription_limit":"no_limit","trial_limit":"no_limit","customer_pausing":"not_allowed","customer_pausing_number_limit":null,"customer_pausing_duration_limit":null,"customer_cancelling":"not_allowed","payment_retries":"","renewal_order_offset":1,"payment_reminders":"","overdue_period":"","overdue_payment_reminders":"","suspension_period":"","suspend_payment_reminders":"","add_paused_days":"0","add_suspended_days":"0","paypal_ec_enabled":"0","settings_import":null,"settings_export":null}}';
	$subscriptio_array = json_decode( $subscriptio_json, true );
	update_option( 'rp_sub_settings', $subscriptio_array );
}

// assign a fake ID to current visitor and save it to cookie. We will use this in saving transients for individual search results.
add_action( 'init', 'stm_current_visitor_fake_id' );
function stm_current_visitor_fake_id() {
	$blog_id = get_current_blog_id();

	if ( ! isset( $_COOKIE[ 'stm_visitor_' . $blog_id ] ) && ! headers_sent() ) {
		$fake_id = wp_rand( 10, 99 ) . gmdate( 'is' ) . wp_rand( 10, 99 );
		setcookie( 'stm_visitor_' . $blog_id, $fake_id, strtotime( '+30 days' ), '/' );
	}
}

add_action( 'after_setup_theme', 'stm_local_theme_setup' );
function stm_local_theme_setup() {
	// Adding user role.
	if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
		$exist_dealer_role = get_role( 'dealer' );
		if ( empty( $exist_dealer_role ) ) {
			add_role(
				'stm_dealer',
				'STM Dealer',
				array(
					'read'    => true,
					'level_0' => true,
				)
			);
		}

		remove_action( 'template_redirect', 'wc_disable_author_archives_for_customers' );
	}

	add_editor_style();
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'video' ) );
	add_post_type_support( 'page', 'excerpt' );

	add_image_size( 'stm-img-1110-577', 1110, 577, true );
	add_image_size( 'stm-img-796-466', 798, 466, true );
	add_image_size( 'stm-img-350-356', 350, 356, true );
	add_image_size( 'stm-img-190-132', 190, 132, true );

	/* Listing image sizes */
	add_image_size( 'stm-img-255', 255 );
	add_image_size( 'stm-img-255-x-2', 510 );
	add_image_size( 'stm-img-280', 280 );
	add_image_size( 'stm-img-280-x-2', 560 );
	add_image_size( 'stm-img-350', 350 );
	add_image_size( 'stm-img-350-x-2', 700 );
	add_image_size( 'stm-img-398', 398 );
	add_image_size( 'stm-img-398-x-2', 796 );

	$current_layout = get_option( 'stm_motors_chosen_template' );

	if ( 'car_dealer_two' === $current_layout || 'car_dealer_two_elementor' === $current_layout ) {
		add_image_size( 'stm-img-275', 275 );
		add_image_size( 'stm-img-275-x-2', 550 );
	} elseif ( 'ev_dealer' === $current_layout ) {
		add_image_size( 'stm-img-770-417', 770, 417, true );
		add_image_size( 'stm-img-536-382', 536, 382, true );
		add_image_size( 'stm-img-275', 275 );
		add_image_size( 'stm-img-275-x-2', 550 );
	} elseif ( 'car_magazine' === $current_layout ) {
		add_image_size( 'stm-img-350-205', 350, 205, true );
		add_image_size( 'stm-img-690-410', 690, 410, true );
		add_image_size( 'stm-img-200-200', 200, 200, true );
		add_image_size( 'stm-mag-img-472-265', 472, 265, true );
	} elseif ( 'boats' === $current_layout || 'motorcycle' === $current_layout ) {
		add_image_size( 'stm-img-350-205', 350, 205, true );
	}

	remove_image_size( '2048x2048' );
	remove_image_size( '1536x1536' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	load_theme_textdomain( 'motors', get_template_directory() . '/languages' );
}

add_filter( 'intermediate_image_sizes', 'stm_intermediate_image_sizes' );
function stm_intermediate_image_sizes( $sizes ) {
	return array_diff( $sizes, array( 'medium_large' ) );
}

function stm_register_sidebars() {
	register_nav_menus(
		array(
			'primary'     => __( 'Top primary menu', 'motors' ),
			'top_bar'     => __( 'Top bar menu', 'motors' ),
			'bottom_menu' => __( 'Bottom menu', 'motors' ),
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Primary Sidebar', 'motors' ),
			'id'            => 'default',
			'description'   => __( 'Main sidebar that appears on the right or left.', 'motors' ),
			'before_widget' => '<aside id="%1$s" class="widget widget-default %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="widget-title"><h4>',
			'after_title'   => '</h4></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'motors' ),
			'id'            => 'footer',
			'description'   => __( 'Footer Widgets Area', 'motors' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-wrapper">',
			'after_widget'  => '</div></aside>',
			'before_title'  => '<div class="widget-title"><h6>',
			'after_title'   => '</h6></div>',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => __( 'Shop', 'motors' ),
				'id'            => 'shop',
				'description'   => __( 'Woocommerce pages sidebar', 'motors' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<div class="widget_title"><h3>',
				'after_title'   => '</h3></div>',
			)
		);
	}

	if ( ! apply_filters( 'stm_is_auto_parts', true ) ) {
		register_sidebar(
			array(
				'name'          => __( 'STM Listing Car Sidebar', 'motors' ),
				'id'            => 'stm_listing_car',
				'description'   => __( 'Default sidebar for Single Car Page (Listing layout)', 'motors' ),
				'before_widget' => '<aside id="%1$s" class="single-listing-car-sidebar-unit %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<div class="stm-border-bottom-unit"><div class="title heading-font">',
				'after_title'   => '</div></div>',
			)
		);

		if ( apply_filters( 'stm_is_boats', false ) ) {
			register_sidebar(
				array(
					'name'          => __( 'STM Single Boat Sidebar', 'motors' ),
					'id'            => 'stm_boats_car',
					'description'   => __( 'Default sidebar for Single Boat Page (Boats layout)', 'motors' ),
					'before_widget' => '<aside id="%1$s" class="single-listing-car-sidebar-unit %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<div class="stm-border-bottom-unit"><h4 class="title heading-font">',
					'after_title'   => '</h4></div>',
				)
			);
		}
	}
}

add_action( 'widgets_init', 'stm_register_sidebars' );
