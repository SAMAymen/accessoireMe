<?php
if ( function_exists( 'vc_map' ) ) {
	add_action( 'init', 'vc_stm_elements' );
}

function vc_stm_elements() {
	$order_by_values = array(
		'',
		__( 'Date', 'motors-wpbakery-widgets' )          => 'date',
		__( 'ID', 'motors-wpbakery-widgets' )            => 'ID',
		__( 'Author', 'motors-wpbakery-widgets' )        => 'author',
		__( 'Title', 'motors-wpbakery-widgets' )         => 'title',
		__( 'Modified', 'motors-wpbakery-widgets' )      => 'modified',
		__( 'Random', 'motors-wpbakery-widgets' )        => 'rand',
		__( 'Comment count', 'motors-wpbakery-widgets' ) => 'comment_count',
		__( 'Menu order', 'motors-wpbakery-widgets' )    => 'menu_order',
	);

	$order_way_values = array(
		'',
		__( 'Descending', 'motors-wpbakery-widgets' ) => 'DESC',
		__( 'Ascending', 'motors-wpbakery-widgets' )  => 'ASC',
	);

	// Get all filter options from STM listing plugin - Listing - listing categories.
	$attributes_including_multilisting = apply_filters( 'stm_get_all_listing_attributes', array(), 'all' );
	$listing_multilisting_attributes   = array();
	if ( ! empty( $attributes_including_multilisting ) ) {
		foreach ( $attributes_including_multilisting as $option ) {
			$listing_multilisting_attributes[ $option['single_name'] . ' (' . $option['slug'] . ')' ] = $option['slug'];
		}
	}

	// get attributes used on filter.
	$filter_options = apply_filters( 'stm_get_car_filter', array() );

	$only_use_on_car_filter_options = array();

	$stm_filter_options = array(
		__( 'Please, select', 'motors-wpbakery-widgets' ) => '',
	);

	if ( ! empty( $filter_options ) ) {
		foreach ( $filter_options as $filter_option ) {
			$stm_filter_options[ $filter_option['single_name'] . ' (' . $filter_option['slug'] . ')' ]             = $filter_option['slug'];
			$only_use_on_car_filter_options[ $filter_option['single_name'] . ' (' . $filter_option['slug'] . ')' ] = $filter_option['slug'];
		}
	}

	$stm_all_attributes = ( function_exists( 'stm_listings_attributes' ) ) ? stm_listings_attributes() : null;

	$stm_all_options = array();

	if ( ! empty( $stm_all_attributes ) ) {
		foreach ( $stm_all_attributes as $filter_option ) {
			$stm_all_options[ $filter_option['single_name'] . ' (' . $filter_option['slug'] . ')' ] = $filter_option['slug'];
		}
	}

	if ( function_exists( 'stm_get_car_filter_checkboxes' ) ) {
		$stm_get_car_filter_checkboxes = stm_get_car_filter_checkboxes();
	} else {
		$stm_get_car_filter_checkboxes = array();
	}

	if ( ! empty( $stm_get_car_filter_checkboxes ) ) {
		foreach ( $stm_get_car_filter_checkboxes as $filter_option ) {
			$stm_filter_options[ $filter_option['single_name'] . ' (' . $filter_option['slug'] . ')' ] = $filter_option['slug'];
		}
	}

	$category_list = array();
	foreach (
		get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
			)
		) as $cat
	) {
		$category_list[ $cat->name ] = $cat->term_id;
	}

	$body_categories = get_terms(
		'body',
		array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
			'fields'     => 'all',
		)
	);

	$body_items = array();

	if ( ! is_wp_error( $body_categories ) && ! empty( $body_categories ) ) {
		foreach ( $body_categories as $cat ) {
			$body_items[ $cat->name . ' (' . $cat->count . ')' ] = $cat->slug;
		}
	}

	/*Products*/
	$plan_args = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => ( class_exists( 'Subscriptio' ) ) ? '_subscriptio' : '_rp_sub:subscription_product',
				'value'   => 'yes',
				'compare' => '=',
			),
		),
	);

	$products       = new WP_Query( $plan_args );
	$products_array = array( __( 'Choose plan', 'motors-wpbakery-widgets' ) => '' );
	if ( $products->have_posts() ) {
		while ( $products->have_posts() ) {
			$products->the_post();
			$title                    = get_the_title();
			$id                       = get_the_ID();
			$products_array[ $title ] = $id;
		}
	}

	// Colored Sep.
	$colored = array(
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Separator Color', 'motors-wpbakery-widgets' ),
			'param_name' => 'color',
			'value'      => '',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Align', 'motors-wpbakery-widgets' ),
			'param_name' => 'align',
			'value'      => array(
				__( 'Left', 'motors-wpbakery-widgets' )   => 'text-left',
				__( 'Center', 'motors-wpbakery-widgets' ) => 'text-center',
				__( 'Right', 'motors-wpbakery-widgets' )  => 'text-right',
			),
			'std'        => 'text-center',
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
			'param_name' => 'css',
			'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
		),
	);

	// C2A 2.
	$cta_2 = array(
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Call to action label', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_label',
		),
		array(
			'type'       => 'iconpicker',
			'heading'    => __( 'Call to action label icon', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_icon',
			'value'      => '',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Label icon color', 'motors-wpbakery-widgets' ),
			'param_name' => 'label_icon_color',
			'value'      => '',
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Call to action label right', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_label_right',
		),
		array(
			'type'       => 'iconpicker',
			'heading'    => __( 'Call to action label icon right', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_icon_right',
			'value'      => '',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Right icon color', 'motors-wpbakery-widgets' ),
			'param_name' => 'right_icon_color',
			'value'      => '',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Call to action background color', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_color',
			'value'      => '#fab637',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Call to action label first part text color', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_text_color',
			'value'      => '#273f44',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Call to action label second part text color', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_text_color_second_part',
			'value'      => '#273f44',
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => __( 'Call to action label right text color', 'motors-wpbakery-widgets' ),
			'param_name' => 'call_to_action_text_color_right',
			'value'      => '#273f44',
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
			'param_name' => 'css',
			'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
		),
	);

	if ( apply_filters( 'stm_is_rental', false ) || apply_filters( 'stm_is_dealer_two', false ) ) {
		$rental_cta2 = array(
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Call to action button Icon', 'motors-wpbakery-widgets' ),
				'param_name' => 'cta_icon',
				'value'      => '',
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Button icon color', 'motors-wpbakery-widgets' ),
				'param_name' => 'button_icon_color',
				'value'      => '',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Button params', 'motors-wpbakery-widgets' ),
				'param_name' => 'link',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Call to action label first part', 'motors-wpbakery-widgets' ),
				'param_name' => 'call_to_action_label_2',
			),
		);

		$cta_2 = array_merge( $rental_cta2, $cta_2 );
	}

	$image_sizes   = wp_get_registered_image_subsizes();
	$sizes_options = array(
		esc_html__( 'Theme Default', 'motors-wpbakery-widgets' ) => '',
	);
	foreach ( $image_sizes as $key => $image_size ) {
		if ( 'stm-img' === substr( $key, 0, 7 ) && '-x-2' !== substr( $key, - 4, 4 ) ) {
			$sizes_options[ "{$image_size['width']} x {$image_size['height']}" ] = $key;
		}
	}

	$stm_sidebars_array = get_posts(
		array(
			'post_type'      => 'sidebar',
			'posts_per_page' => - 1,
		)
	);

	$stm_sidebars = array( __( 'Select', 'motors-wpbakery-widgets' ) => 0 );
	if ( $stm_sidebars_array ) {
		foreach ( $stm_sidebars_array as $val ) {
			$stm_sidebars[ get_the_title( $val ) ] = $val->ID;
		}
	}

	$args = array(
		'post_type'      => 'wpcf7_contact_form',
		'posts_per_page' => - 1,
	);

	$cf7_forms = get_posts( $args );

	$available_cf7 = array();

	if ( ! empty( $cf7_forms ) ) {
		foreach ( $cf7_forms as $contact_form ) {
			$available_cf7[ $contact_form->post_title ] = $contact_form->ID;
		}
	} else {
		$available_cf7['No CF7 forms found'] = 'none';
	}

	/*STM GENERAL VC MODULES*/
	vc_map(
		array(
			'name'     => __( 'STM Auto Loan Calculator', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_auto_loan_calculator',
			'icon'     => 'stm_auto_loan_calculator',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Calculator Heading', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Currency symbol', 'motors-wpbakery-widgets' ),
					'param_name' => 'currency_symbol',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Labels font size', 'motors-wpbakery-widgets' ),
					'param_name' => 'label_font_size',
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Wide version', 'motors-wpbakery-widgets' ),
					'param_name' => 'wide_version',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// Icon box.
	vc_map(
		array(
			'name'     => __( 'STM Icon Box', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_icon_box',
			'icon'     => 'stm_icon_box',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'textfield',
					'holder'     => 'div',
					'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Title Holder', 'motors-wpbakery-widgets' ),
					'param_name' => 'title_holder',
					'value'      => array(
						'H1' => 'h1',
						'H2' => 'h2',
						'H3' => 'h3',
						'H4' => 'h4',
						'H5' => 'h5',
						'H6' => 'h6',
					),
					'std'        => 'h3',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Style', 'motors-wpbakery-widgets' ),
					'param_name' => 'style_layout',
					'value'      => array(
						esc_html__( 'Car dealer', 'motors-wpbakery-widgets' ) => 'car_dealer',
						esc_html__( 'Boats', 'motors-wpbakery-widgets' )      => 'boats',
					),
					'std'        => 'car_dealer',
				),
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
					'param_name' => 'link',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box background color', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_bg_color',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box text color', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_text_color',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box text color on hover', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_text_color_hover',
					'value'      => '',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Icon background color', 'motors-wpbakery-widgets' ),
					'param_name'  => 'icon_bg_color',
					'value'       => '',
					'description' => __( 'Don\'t forget to add paddings in Icon design options tab', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show bottom triangle', 'motors-wpbakery-widgets' ),
					'param_name' => 'bottom_triangle',
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'value'      => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Icon Size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'icon_size',
					'description' => __( 'Enter icon size in px', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Content font size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'content_font_size',
					'description' => __( 'Enter font size in px', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Content line height', 'motors-wpbakery-widgets' ),
					'param_name'  => 'line_height',
					'description' => __( 'Enter line height in px', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Content font weight', 'motors-wpbakery-widgets' ),
					'param_name'  => 'content_font_weight',
					'description' => __( 'Enter font weight', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Button text', 'motors-wpbakery-widgets' ),
					'param_name' => 'btn_text',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Button color', 'motors-wpbakery-widgets' ),
					'param_name' => 'btn_color',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Button hover color', 'motors-wpbakery-widgets' ),
					'param_name' => 'btn_hover_color',
					'value'      => '',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'Icon Css', 'motors-wpbakery-widgets' ),
					'param_name' => 'css_icon',
					'group'      => __( 'Icon Design options', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Colored Separator', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_color_separator',
			'icon'     => 'stm_color_separator',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => $colored,
		)
	);

	// Carousel.
	vc_map(
		array(
			'name'     => __( 'STM Carousel', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_carousel',
			'icon'     => 'stm_carousel',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'attach_images',
					'heading'    => __( 'Images', 'motors-wpbakery-widgets' ),
					'param_name' => 'images',
				),
				array(
					'type'       => 'checkbox',
					'param_name' => 'fullwidth',
					'value'      => array(
						esc_html__( 'Enable Fullwidth', 'motors-wpbakery-widgets' ) => 'enable',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => 'thumbnail',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Number of slides per row', 'motors-wpbakery-widgets' ),
					'param_name' => 'slides_per_row',
					'value'      => array(
						'6' => '6',
						'5' => '5',
						'4' => '4',
						'3' => '3',
						'2' => '2',
						'1' => '1',
					),
					'std'        => '4',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// Inventory search results in single listing.
	if ( function_exists( 'stm_search_results_enabled' ) && stm_search_results_enabled() ) {
		vc_map(
			array(
				'name'     => __( 'STM Inventory Search Results', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_inventory_search_results',
				'icon'     => 'stm_inventory_search_results',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
	}

	// Testimonials.
	vc_map(
		array(
			'name'      => __( 'STM Testimonials', 'motors-wpbakery-widgets' ),
			'base'      => 'stm_testimonials',
			'as_parent' => array( 'only' => 'stm_testimonial' ),
			'category'  => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'    => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Columns number', 'motors-wpbakery-widgets' ),
					'param_name' => 'slides_per_row',
					'value'      => array(
						'6' => '6',
						'5' => '5',
						'4' => '4',
						'3' => '3',
						'2' => '2',
						'1' => '1',
					),
					'std'        => '1',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
			'js_view'   => 'VcColumnView',
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Testimonial', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_testimonial',
			'as_child' => array( 'only' => 'stm_testimonials' ),
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => '213x142',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Style View', 'motors-wpbakery-widgets' ),
					'param_name' => 'style_view',
					'value'      => array(
						__( 'Style 1', 'motors-wpbakery-widgets' ) => 'style_1',
						__( 'Style 2', 'motors-wpbakery-widgets' ) => 'style_2',
					),
					'std'        => 'style_1',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Author name', 'motors-wpbakery-widgets' ),
					'param_name' => 'author',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Author Position', 'motors-wpbakery-widgets' ),
					'param_name' => 'author_position',
					'dependency' => array(
						'element' => 'style_view',
						'value'   => array( 'style_2' ),
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon',
					'dependency' => array(
						'element' => 'style_view',
						'value'   => array( 'style_2' ),
					),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'dependency' => array(
						'element' => 'style_view',
						'value'   => array( 'style_2' ),
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Author car model', 'motors-wpbakery-widgets' ),
					'param_name' => 'author_car',
					'dependency' => array(
						'element' => 'style_view',
						'value'   => array( 'style_1' ),
					),
				),
			),
		)
	);

	// OUR TEAM.
	vc_map(
		array(
			'name'     => __( 'STM Our team', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_our_team',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => '257x170',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Team member Name', 'motors-wpbakery-widgets' ),
					'param_name' => 'name',
					'holder'     => 'div',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Team member position', 'motors-wpbakery-widgets' ),
					'param_name' => 'position',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Team member email', 'motors-wpbakery-widgets' ),
					'param_name' => 'email',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Team member phone', 'motors-wpbakery-widgets' ),
					'param_name' => 'phone',
				),
			),
		)
	);

	// OUR Partners.
	vc_map(
		array(
			'name'     => __( 'STM Our partners', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_our_partners',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'attach_images',
					'heading'    => __( 'Partners Images', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number to show', 'motors-wpbakery-widgets' ),
					'param_name' => 'number_to_show',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => '150x50',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// OUR Partners.
	vc_map(
		array(
			'name'     => __( 'STM Services Archive', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_service_archive',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Services per page', 'motors-wpbakery-widgets' ),
					'param_name' => 'per_page',
					'value'      => '6',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => '350x205',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// Tech info.
	vc_map(
		array(
			'name'      => __( 'STM Technical informations', 'motors-wpbakery-widgets' ),
			'base'      => 'stm_tech_infos',
			'as_parent' => array( 'only' => 'stm_tech_info' ),
			'category'  => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'    => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
					'holder'     => 'div',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Icon size (px)', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_size',
					'value'      => '27',
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'value'      => '',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
			'js_view'   => 'VcColumnView',
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Technical information', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_tech_info',
			'as_child' => array( 'only' => 'stm_tech_infos' ),
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Show Subtitle', 'motors-wpbakery-widgets' ),
					'param_name'  => 'show_sub_title',
					'description' => esc_html__( 'Enable subtitle. (Note: displays only subtitle text)', 'motors-wpbakery-widgets' ),
					'value'       => array(
						__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
					),
					'std'         => '',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Sub title', 'motors-wpbakery-widgets' ),
					'param_name' => 'subtitle',
					'holder'     => 'div',
					'dependency' => array(
						'element' => 'show_sub_title',
						'value'   => 'yes',
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Technical parameter', 'motors-wpbakery-widgets' ),
					'param_name' => 'name',
					'holder'     => 'div',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Technical value', 'motors-wpbakery-widgets' ),
					'param_name' => 'value',
					'holder'     => 'div',
				),
			),
		)
	);

	// GMAP.
	vc_map(
		array(
			'name'     => esc_html__( 'Google Map', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_gmap',
			'icon'     => 'stm_gmap',
			'category' => esc_html__( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Map Width', 'motors-wpbakery-widgets' ),
					'param_name'  => 'map_width',
					'value'       => '100%',
					'description' => esc_html__( 'Enter map width in px or %', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Map Height', 'motors-wpbakery-widgets' ),
					'param_name'  => 'map_height',
					'value'       => '460px',
					'description' => esc_html__( 'Enter map height in px', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'attach_images',
					'heading'    => __( 'Pin image', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Latitude', 'motors-wpbakery-widgets' ),
					'param_name'  => 'lat',
					'description' => wp_kses( __( '<a href="http://www.latlong.net/convert-address-to-lat-long.html">Here is a tool</a> where you can find Latitude & Longitude of your location', 'motors-wpbakery-widgets' ), array( 'a' => array( 'href' => array() ) ) ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Longitude', 'motors-wpbakery-widgets' ),
					'param_name'  => 'lng',
					'description' => wp_kses( __( '<a href="http://www.latlong.net/convert-address-to-lat-long.html">Here is a tool</a> where you can find Latitude & Longitude of your location', 'motors-wpbakery-widgets' ), array( 'a' => array( 'href' => array() ) ) ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Map Zoom', 'motors-wpbakery-widgets' ),
					'param_name' => 'map_zoom',
					'value'      => 18,
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'InfoWindow text', 'motors-wpbakery-widgets' ),
					'param_name' => 'infowindow_text',
				),
				array(
					'type'       => 'checkbox',
					'param_name' => 'disable_mouse_whell',
					'value'      => array(
						esc_html__( 'Disable map zoom on mouse wheel scroll', 'motors-wpbakery-widgets' ) => 'disable',
					),
				),
				array(
					'type'       => 'checkbox',
					'param_name' => 'disable_control_tools',
					'value'      => array(
						esc_html__( 'Disable controls (rotate, scale, zoom, street view, full screen, map type)', 'motors-wpbakery-widgets' ) => 'disable',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'motors-wpbakery-widgets' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'textarea_raw_html',
					'heading'    => esc_html__( 'Style Code', 'motors-wpbakery-widgets' ),
					'param_name' => 'gmap_style',
					'group'      => esc_html__( 'Map Style', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'Css', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	/*Button*/
	vc_map(
		array(
			'name'     => __( 'STM Icon Button', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_icon_button',
			'icon'     => 'stm_icon_button',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
					'param_name' => 'link',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Alignment', 'motors-wpbakery-widgets' ),
					'param_name' => 'align',
					'value'      => array(
						__( 'Left', 'motors-wpbakery-widgets' )   => 'left',
						__( 'Right', 'motors-wpbakery-widgets' )  => 'right',
						__( 'Center', 'motors-wpbakery-widgets' ) => 'center',
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box background color', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_bg_color',
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box text color', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_text_color',
					'value'      => '',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// C2A.
	vc_map(
		array(
			'name'     => __( 'STM Call to Action', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_call_to_action',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
					'param_name' => 'link',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box color', 'motors-wpbakery-widgets' ),
					'param_name' => 'box_color',
					'value'      => '',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Call to action background', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Attach icon or image', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_or_image',
					'value'      => array(
						'Icon'  => 'icon',
						'Image' => 'image',
					),
					'std'        => 'image',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Text image', 'motors-wpbakery-widgets' ),
					'param_name' => 'text_image',
					'dependency' => array(
						'element' => 'icon_or_image',
						'value'   => array( 'image' ),
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Text icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'text_icon',
					'dependency' => array(
						'element' => 'icon_or_image',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'value'      => '',
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => esc_html__( 'Call to action text', 'motors-wpbakery-widgets' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	// C2A.
	vc_map(
		array(
			'name'     => __( 'STM Call to Action 2', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_call_to_action_2',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => $cta_2,
		)
	);

	// Working days.
	vc_map(
		array(
			'name'     => __( 'STM Working days', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_working_days',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Sunday', 'motors-wpbakery-widgets' ),
					'param_name' => 'sunday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Monday', 'motors-wpbakery-widgets' ),
					'param_name' => 'monday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Tuesday', 'motors-wpbakery-widgets' ),
					'param_name' => 'tuesday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Wednesday', 'motors-wpbakery-widgets' ),
					'param_name' => 'wednesday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Thursday', 'motors-wpbakery-widgets' ),
					'param_name' => 'thursday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Friday', 'motors-wpbakery-widgets' ),
					'param_name' => 'friday',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Saturday', 'motors-wpbakery-widgets' ),
					'param_name' => 'saturday',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Sidebar', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_sidebar',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Choose sidebar', 'motors-wpbakery-widgets' ),
					'param_name' => 'sidebar',
					'value'      => $stm_sidebars,
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Contact form', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_contact_form',
			'icon'     => 'icon-wpb-contactform7',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Choose form', 'motors-wpbakery-widgets' ),
					'param_name' => 'form',
					'value'      => $available_cf7,
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Sidebar call to action', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_sidebar_call_to_action',
			'icon'     => 'stm_sidebar_call_to_action',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
					'param_name' => 'link',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Attach icon or image', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_or_image',
					'value'      => array(
						'Icon'  => 'icon',
						'Image' => 'image',
					),
					'std'        => 'image',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Text image', 'motors-wpbakery-widgets' ),
					'param_name' => 'text_image',
					'dependency' => array(
						'element' => 'icon_or_image',
						'value'   => array( 'image' ),
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Text image width', 'motors-wpbakery-widgets' ),
					'param_name' => 'text_image_width',
					'dependency' => array(
						'element' => 'icon_or_image',
						'value'   => array( 'image' ),
					),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Text icon', 'motors-wpbakery-widgets' ),
					'param_name' => 'text_icon',
					'dependency' => array(
						'element' => 'icon_or_image',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
					'param_name' => 'icon_color',
					'value'      => '',
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => esc_html__( 'Call to action text', 'motors-wpbakery-widgets' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Image size', 'motors-wpbakery-widgets' ),
					'param_name'  => 'image_size',
					'value'       => '253x233',
					'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'motors-wpbakery-widgets' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Info Box', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_info_box',
			'icon'     => 'stm_info_box',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
					'param_name' => 'image',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
					'param_name' => 'title_color',
					'value'      => '',
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Use counter for title', 'motors-wpbakery-widgets' ),
					'param_name' => 'title_counter',
					'value'      => array(
						__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
					),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Content color', 'motors-wpbakery-widgets' ),
					'param_name' => 'content_color',
					'value'      => '',
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'     => __( 'STM Spacer', 'motors-wpbakery-widgets' ),
			'base'     => 'stm_spacer',
			'icon'     => 'stm_spacer',
			'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
			'params'   => array(),
		)
	);

	/*STM GENERAL END*/

	if ( ! defined( 'STM_MOTORS_CAR_RENTAL' ) ) {
		// Spec offers.
		vc_map(
			array(
				'name'     => __( 'STM Special Offers', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_special_offers',
				'icon'     => 'stm_special_offers',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Show link to all specials', 'motors-wpbakery-widgets' ),
						'param_name' => 'show_all_link_specials',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Colored first word', 'motors-wpbakery-widgets' ),
						'param_name' => 'colored_first_word',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'View Style', 'motors-wpbakery-widgets' ),
						'param_name' => 'view_style',
						'value'      => array(
							__( 'Style 1', 'motors-wpbakery-widgets' ) => 'style_1',
							__( 'Style 2', 'motors-wpbakery-widgets' ) => 'style_2',
						),
						'std'        => 'style_1',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'View Type', 'motors-wpbakery-widgets' ),
						'param_name' => 'view_type',
						'value'      => array(
							__( 'Carousel', 'motors-wpbakery-widgets' ) => 'carousel',
							__( 'Grid', 'motors-wpbakery-widgets' )     => 'grid',
						),
						'std'        => 'carousel',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Car listing Tabs.
		vc_map(
			array(
				'name'     => __( 'STM Car listing tabs', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_car_listing_tabbed',
				'icon'     => 'stm_car_listing_tabbed',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Top part background color', 'motors-wpbakery-widgets' ),
						'param_name' => 'top_part_bg',
						'value'      => '#232628',
					),
					array(
						'type'        => 'stm_autocomplete_vc',
						'heading'     => __( 'Select category', 'motors-wpbakery-widgets' ),
						'param_name'  => 'taxonomy',
						'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Tab affix', 'motors-wpbakery-widgets' ),
						'param_name'  => 'tab_affix',
						'value'       => __( 'cars', 'motors-wpbakery-widgets' ),
						'description' => __( 'This will appear after category name', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Tab preffix', 'motors-wpbakery-widgets' ),
						'param_name'  => 'tab_preffix',
						'value'       => '',
						'description' => __( 'This will appear before category name', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Cars per load', 'motors-wpbakery-widgets' ),
						'param_name'  => 'per_page',
						'description' => __( '-1 will show all cars from category', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Enable ajax loading', 'motors-wpbakery-widgets' ),
						'param_name' => 'enable_ajax_loading',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Found cars prefix', 'motors-wpbakery-widgets' ),
						'param_name'  => 'found_cars_prefix',
						'value'       => __( 'cars', 'motors-wpbakery-widgets' ),
						'description' => __( 'This will appear after found cars count', 'motors-wpbakery-widgets' ),
					),
					// Search tab Start.
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Enable search', 'motors-wpbakery-widgets' ),
						'param_name' => 'enable_search',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Search label', 'motors-wpbakery-widgets' ),
						'param_name' => 'search_label',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Search label icon', 'motors-wpbakery-widgets' ),
						'param_name' => 'search_icon',
						'value'      => '',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Search label icon color', 'motors-wpbakery-widgets' ),
						'param_name' => 'search_icon_color',
						'value'      => '',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Number of filter columns', 'motors-wpbakery-widgets' ),
						'param_name' => 'filter_columns_number',
						'value'      => array(
							'6' => '6',
							'4' => '4',
							'3' => '3',
							'2' => '2',
							'1' => '1',
						),
						'std'        => '2',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Select Filter options', 'motors-wpbakery-widgets' ),
						'param_name' => 'filter_selected',
						'value'      => $only_use_on_car_filter_options,
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					// Call to action.
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Enable call-to-action', 'motors-wpbakery-widgets' ),
						'param_name' => 'enable_call_to_action',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Call to action label', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_label',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Call to action label icon', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_icon',
						'value'      => '',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Call to action label icon color', 'motors-wpbakery-widgets' ),
						'param_name' => 'action_icon_color',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Call to action label right', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_label_right',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Call to action label icon right', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_icon_right',
						'value'      => '',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Call to action label right icon color', 'motors-wpbakery-widgets' ),
						'param_name' => 'right_icon_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Call to action background color', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_color',
						'value'      => '#fab637',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Call to action text color', 'motors-wpbakery-widgets' ),
						'param_name' => 'call_to_action_text_color',
						'value'      => '#fff',
						'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
					),
					// Search tab End.

					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Single car elements.
		// whatsapp button.
		vc_map(
			array(
				'name'     => __( 'Social buttons (WhatsApp)', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_social_buttons',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Button default width', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'ignored if width specified below', 'motors-wpbakery-widgets' ),
						'param_name'  => 'button_default_width',
						'value'       => array(
							'Full width'  => 'full-width',
							'Fit content' => 'fit-content',
						),
						'std'         => 'fit-content',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Message text', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'Enter text with shortcodes like', 'motors-wpbakery-widgets' ) . ' <strong>[listing_url]</strong> ' . esc_html__( 'and', 'motors-wpbakery-widgets' ) . ' <strong>[listing_title]</strong>',
						'param_name'  => 'whatsapp_massage',
						'value'       => esc_html__( 'Hi. I have visited your site. And I would like to rent [listing_url]. Is it available for rent?', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Button width', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'in pixels, leave empty for default value', 'motors-wpbakery-widgets' ),
						'param_name'  => 'button_width',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Button height', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'in pixels, leave empty for default value', 'motors-wpbakery-widgets' ),
						'param_name'  => 'button_height',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Button text font size', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'in pixels, leave empty for default value', 'motors-wpbakery-widgets' ),
						'param_name'  => 'button_font_size',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Button text line height', 'motors-wpbakery-widgets' ),
						'description' => esc_html__( 'in pixels, leave empty for default value', 'motors-wpbakery-widgets' ),
						'param_name'  => 'button_line_height',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Button container', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Button element', 'motors-wpbakery-widgets' ),
						'param_name' => 'button_css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Title.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Title', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_title',
				'icon'     => 'stm_single_car_title',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Actions.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Actions', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_actions',
				'icon'     => 'stm_single_car_actions',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		// Gallery.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Gallery', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_gallery',
				'icon'     => 'stm_single_car_gallery',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		// Price.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Price', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_price',
				'icon'     => 'stm_single_car_price',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		// Data.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Data', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_data',
				'icon'     => 'stm_single_car_data',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		// MPG.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing MPG', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_mpg',
				'icon'     => 'stm_single_car_mpg',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		// Calculator.
		vc_map(
			array(
				'name'     => __( 'STM Single Listing Calculator', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_single_car_calculator',
				'icon'     => 'stm_single_car_calculator',
				'category' => __( 'STM Single Listing', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Compare cars.
		vc_map(
			array(
				'name'     => __( 'STM Compare Cars', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_compare_cars',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Widgets.
		// Media library.
		vc_map(
			array(
				'name'     => __( 'STM Media Library', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_media_library',
				'icon'     => 'stm_media_library',
				'category' => __( 'STM Widgets', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Widget title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'attach_images',
						'heading'    => __( 'Images', 'motors-wpbakery-widgets' ),
						'param_name' => 'images',
					),
				),
			)
		);
		// Recent posts.
		vc_map(
			array(
				'name'     => __( 'STM Recent Posts', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_recent_posts',
				'icon'     => 'stm_recent_posts',
				'category' => __( 'STM Widgets', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Widget title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Number of posts', 'motors-wpbakery-widgets' ),
						'param_name' => 'number_of_posts',
					),
				),
			)
		);

		// Post partials.
		// Stm post title/image.
		vc_map(
			array(
				'name'     => __( 'STM Post title', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_title',
				'icon'     => 'stm_post_title',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		vc_map(
			array(
				'name'     => __( 'STM Post image', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_image',
				'icon'     => 'stm_post_image',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		vc_map(
			array(
				'name'     => __( 'STM Post Info', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_info',
				'icon'     => 'stm_post_info',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);
		vc_map(
			array(
				'name'     => __( 'STM Post Meta Bottom(share, tags, categories)', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_meta_bottom',
				'icon'     => 'stm_post_meta_bottom',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css_share',
						'group'      => __( 'Share this css', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Post Author Box', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_author_box',
				'icon'     => 'stm_post_author_box',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Post Comments', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_comments',
				'icon'     => 'stm_post_comments',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Post FullWidth Info', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_fullwidth_info',
				'icon'     => 'stm_post_fullwidth_info',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Post Animated Image', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_post_animated_image',
				'icon'     => 'stm_post_animated_image',
				'category' => __( 'STM Post Partials', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Subtitle', 'motors-wpbakery-widgets' ),
						'param_name' => 'subtitle',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Service Contact form', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_service_contact_form',
				'icon'     => 'icon-wpb-contactform7',
				'category' => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Choose form', 'motors-wpbakery-widgets' ),
						'param_name' => 'form',
						'value'      => $available_cf7,
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		$filter_params = array(
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Quantity of listing per row on grid view', 'motors-wpbakery-widgets' ),
				'param_name' => 'quant_listing_on_grid',
				'value'      => array(
					'2' => '2',
					'3' => '3',
				),
				'std'        => '3',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image size on grid view', 'motors-wpbakery-widgets' ),
				'param_name' => 'grid_thumb_img_size',
				'value'      => $sizes_options,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image size on list view', 'motors-wpbakery-widgets' ),
				'param_name' => 'list_thumb_img_size',
				'value'      => $sizes_options,
			),
			array(
				'type'       => 'css_editor',
				'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
				'param_name' => 'css',
				'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
			),
		);

		$classic_filter = array_merge(
			array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Posts per page on list view', 'motors-wpbakery-widgets' ),
					'param_name' => 'ppp_on_list',
					'value'      => '10',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Posts per page on grid view', 'motors-wpbakery-widgets' ),
					'param_name' => 'ppp_on_grid',
					'value'      => '9',
				),
			),
			$filter_params,
		);

		/*Modern filter*/
		vc_map(
			array(
				'name'     => __( 'STM Modern Filter', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_modern_filter',
				'icon'     => 'stm_modern_filter',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => $filter_params,
			)
		);

		/*Classic filter*/
		vc_map(
			array(
				'name'     => __( 'STM Classic Filter', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_classic_filter',
				'icon'     => 'stm_classic_filter',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => $classic_filter,
			)
		);

		/*Sell a car*/
		vc_map(
			array(
				'name'     => __( 'STM Sell a car', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_sell_a_car',
				'icon'     => 'stm_sell_a_car',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		// Service layout related modules, but still they can be used everywhere.
		vc_map(
			array(
				'name'     => __( 'STM Service Icon Box', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_service_icon_box',
				'icon'     => 'stm_service_icon_box',
				'category' => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
						'param_name' => 'icon',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
						'param_name' => 'icon_color',
						'value'      => '',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Content center', 'motors-wpbakery-widgets' ),
						'param_name' => 'vertical_a_m',
						'value'      => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Icon size(px)', 'motors-wpbakery-widgets' ),
						'param_name' => 'icon_size',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Service Info Box', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_service_info_box',
				'icon'     => 'stm_service_info_box',
				'category' => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Box background color', 'motors-wpbakery-widgets' ),
						'param_name' => 'box_bg_color',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_color',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Price label', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_label',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Price value', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_value',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Price color', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Price background color', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_background_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Content color', 'motors-wpbakery-widgets' ),
						'param_name' => 'content_color',
						'value'      => '',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Stats Counter', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_stats_counter',
				'icon'     => 'stm_stats_counter',
				'category' => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Counter Value', 'motors-wpbakery-widgets' ),
						'param_name' => 'counter_value',
						'value'      => '1000',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Append plus sign to number?', 'motors-wpbakery-widgets' ),
						'param_name' => 'append_plus',
						'std'        => '',
						'value'      => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Duration', 'motors-wpbakery-widgets' ),
						'param_name' => 'duration',
						'value'      => '2.5',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'      => __( 'STM Image links', 'motors-wpbakery-widgets' ),
				'base'      => 'stm_image_links',
				'as_parent' => array( 'only' => 'stm_image_link' ),
				'icon'      => 'stm_image_links',
				'category'  => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'    => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Image per row', 'motors-wpbakery-widgets' ),
						'param_name' => 'row_number',
						'value'      => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
						'std'        => '4',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
				'js_view'   => 'VcColumnView',
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Image Link', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_image_link',
				'as_child' => array( 'only' => 'stm_image_links' ),
				'category' => __( 'STM Service Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'images',
					),
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image @2x', 'motors-wpbakery-widgets' ),
						'param_name' => 'retina_images',
					),
					array(
						'type'       => 'vc_link',
						'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
						'param_name' => 'link',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'      => __( 'STM Popular Searches', 'motors-wpbakery-widgets' ),
				'base'      => 'stm_popular_searches',
				'as_parent' => array( 'only' => 'stm_popular_search' ),
				'icon'      => 'stm_popular_searches',
				'category'  => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'    => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
				'js_view'   => 'VcColumnView',
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Popular Search', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_popular_search',
				'as_child' => array( 'only' => 'stm_popular_searches' ),
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'        => 'stm_autocomplete_vc',
						'heading'     => __( 'Taxonomy', 'motors-wpbakery-widgets' ),
						'param_name'  => 'taxonomy_list',
						'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions. Note, only one taxonomy will be used as tab). This parameter will be used as default filter for this tab.', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Hero Banner', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_hero_banner',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Info Block Style', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_style',
						'value'      => array(
							'Style 1' => 'style_1',
							'Style 2' => 'style_2',
							'Style 3' => 'style_3',
						),
						'std'        => 'style_1',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Info Block Position', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_position',
						'value'      => array(
							'Left'  => 'left',
							'Right' => 'right',
						),
						'std'        => 'right',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price', 'motors-wpbakery-widgets' ),
						'param_name' => 'price',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Per Month Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'per_month',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period', 'motors-wpbakery-widgets' ),
						'param_name' => 'period',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Description', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Link', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_link',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_title',
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Button Icon', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon',
						'value'      => '',
					),
					//GENERAL STYLE START
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Info Block Border Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_border_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => 'style_1',
						),
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Info Block Background', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_bg',
						'value'      => '',
						'std'        => '',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_3' ),
						),
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size',
						'value'      => '20',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height',
						'value'      => '',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Title font weight', 'motors-wpbakery-widgets' ),
						'param_name'  => 'title_font_weight',
						'description' => __( 'Enter font weight', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'value'       => array(
							'100',
							'200',
							'300',
							'400',
							'500',
							'600',
							'700',
							'800',
							'900',
						),
						'std'         => '500',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Info Block Style', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_style',
						'value'      => array(
							'Default'           => 'style_2',
							'Rounded content'   => 'style_1',
							'Rectangle content' => 'style_3',
						),
						'std'        => 'style_1',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title Two First Words Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'two_first_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Price Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size',
						'value'      => '106',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Month Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size',
						'value'      => '16',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Description Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size',
						'value'      => '11',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height',
						'value'      => '14',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Button icon Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size',
						'value'      => '23',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Button Text Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_text_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size',
						'value'      => '16',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height',
						'value'      => '14',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					), //GENERAL STYLE END
					//TABLET STYLE START
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size_tablet',
						'value'      => '20',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height_tablet',
						'value'      => '',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t_tablet',
						'value'       => '26',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b_tablet',
						'value'       => '26',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size_tablet',
						'value'      => '50',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size_tablet',
						'value'      => '106',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size_tablet',
						'value'      => '50',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size_tablet',
						'value'      => '42',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t_tablet',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b_tablet',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size_tablet',
						'value'      => '11',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size_tablet',
						'value'      => '23',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size_tablet',
						'value'      => '16',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					), //TABLET STYLE END
					//MOBILE STYLE START
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height_mobile',
						'value'      => '',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t_mobile',
						'value'       => '16',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b_mobile',
						'value'       => '16',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size_mobile',
						'value'      => '40',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size_mobile',
						'value'      => '70',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size_mobile',
						'value'      => '29',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size_mobile',
						'value'      => '32',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size_mobile',
						'value'      => '12',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t_mobile',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b_mobile',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size_mobile',
						'value'      => '10',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size_mobile',
						'value'      => '23',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size_mobile',
						'value'      => '16',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					), //MOBILE STYLE END
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'      => __( 'STM Hero Slider', 'motors-wpbakery-widgets' ),
				'base'      => 'stm_hero_slider',
				'as_parent' => array( 'only' => 'stm_hero_slider_item' ),
				'category'  => __( 'STM General', 'motors-wpbakery-widgets' ),
				'params'    => array(
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Previous/Next Buttons', 'motors-wpbakery-widgets' ),
						'param_name' => 'navigation',
						'value'      => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
						'std'        => 'yes',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Infinite Loop', 'motors-wpbakery-widgets' ),
						'param_name' => 'loop',
						'value'      => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
						'std'        => 'yes',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Animaton Speed', 'motors-wpbakery-widgets' ),
						'param_name'  => 'transition_speed',
						'std'         => '400',
						'description' => 'Speed of slide animation in milliseconds',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Autoplay', 'motors-wpbakery-widgets' ),
						'param_name' => 'autoplay',
						'value'      => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
						'std'        => 'yes',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Slide Duration', 'motors-wpbakery-widgets' ),
						'param_name'  => 'delay',
						'std'         => '3000',
						'description' => 'Delay between transitions in milliseconds',
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => 'yes',
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => __( 'Pause on Mouseover', 'motors-wpbakery-widgets' ),
						'param_name'  => 'pause_on_mouseover',
						'value'       => array(
							__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
						),
						'description' => 'When enabled autoplay will be paused on mouse enter over carousel container',
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => 'yes',
						),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
				'js_view'   => 'VcColumnView',
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Hero Slider Item', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_hero_slider_item',
				'as_child' => array( 'only' => 'stm_hero_slider' ),
				'category' => __( 'STM General', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Info Block Style', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_style',
						'value'      => array(
							'Default'           => 'style_2',
							'Rounded content'   => 'style_1',
							'Rectangle content' => 'style_3',
						),
						'std'        => 'style_1',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Info Block Position', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_position',
						'value'      => array(
							'Left'  => 'left',
							'Right' => 'right',
						),
						'std'        => 'right',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'holder'     => 'div',
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price', 'motors-wpbakery-widgets' ),
						'param_name' => 'price',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Per Month Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'per_month',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period', 'motors-wpbakery-widgets' ),
						'param_name' => 'period',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Description', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Link', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_link',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_title',
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Button Icon', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon',
						'value'      => '',
					),
					//GENERAL STYLE START
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Info Block Border Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_border_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => 'style_1',
						),
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Info Block Background', 'motors-wpbakery-widgets' ),
						'param_name' => 'info_block_bg',
						'value'      => '',
						'std'        => '',
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_3' ),
						),
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size',
						'value'      => '20',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height',
						'value'      => '',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Title font weight', 'motors-wpbakery-widgets' ),
						'param_name'  => 'title_font_weight',
						'description' => __( 'Enter font weight', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'value'       => array(
							'100',
							'200',
							'300',
							'400',
							'500',
							'600',
							'700',
							'800',
							'900',
						),
						'std'         => '500',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title Two First Words Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'two_first_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Price Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_color',
						'value'      => '#cc6119',
						'std'        => '#cc6119',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size',
						'value'      => '106',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Month Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size',
						'value'      => '50',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size',
						'value'      => '16',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Description Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size',
						'value'      => '11',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height',
						'value'      => '14',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Button icon Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size',
						'value'      => '23',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Button Text Color', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_text_color',
						'value'      => '#ffffff',
						'std'        => '#ffffff',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size',
						'value'      => '16',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height',
						'value'      => '14',
						'group'      => __( 'General Style', 'motors-wpbakery-widgets' ),
					), //GENERAL STYLE END
					//TABLET STYLE START
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size_tablet',
						'value'      => '20',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height_tablet',
						'value'      => '',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t_tablet',
						'value'       => '26',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b_tablet',
						'value'       => '26',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size_tablet',
						'value'      => '50',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size_tablet',
						'value'      => '106',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size_tablet',
						'value'      => '50',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size_tablet',
						'value'      => '42',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t_tablet',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b_tablet',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size_tablet',
						'value'      => '11',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size_tablet',
						'value'      => '23',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size_tablet',
						'value'      => '16',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height_tablet',
						'value'      => '14',
						'group'      => __( 'Tablet Style', 'motors-wpbakery-widgets' ),
					), //TABLET STYLE END
					//MOBILE STYLE START
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_font_size_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'title_line_height_mobile',
						'value'      => '',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_t_mobile',
						'value'       => '16',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Price Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'price_block_m_b_mobile',
						'value'       => '16',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Currency Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'currency_font_size_mobile',
						'value'      => '40',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'price_font_size_mobile',
						'value'      => '70',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Delimiter Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'delimiter_month_font_size_mobile',
						'value'      => '29',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Month Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'month_font_size_mobile',
						'value'      => '32',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Period Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'period_font_size_mobile',
						'value'      => '12',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_1', 'style_2' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Top', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_t_mobile',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Description Block Margin Bottom', 'motors-wpbakery-widgets' ),
						'param_name'  => 'desc_block_m_b_mobile',
						'value'       => '36',
						'description' => __( 'Value in px', 'motors-wpbakery-widgets' ),
						'group'       => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency'  => array(
							'element' => 'info_block_style',
							'value'   => 'style_3',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_font_size_mobile',
						'value'      => '10',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Description Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'description_line_height_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
						'dependency' => array(
							'element' => 'info_block_style',
							'value'   => array( 'style_2', 'style_3' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Icon Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_icon_size_mobile',
						'value'      => '23',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Font Size', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_font_size_mobile',
						'value'      => '16',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Line Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'btn_line_height_mobile',
						'value'      => '14',
						'group'      => __( 'Mobile Style', 'motors-wpbakery-widgets' ),
					), //MOBILE STYLE END
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),

			)
		);

		if ( true === apply_filters( 'stm_sold_status_enabled', false ) ) {
			vc_map(
				array(
					'name'     => __( 'STM Sold Cars', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_sold_cars',
					'icon'     => 'stm_classic_filter',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Posts per page on list view', 'motors-wpbakery-widgets' ),
							'param_name' => 'ppp_on_list',
							'value'      => '10',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Posts per page on grid view', 'motors-wpbakery-widgets' ),
							'param_name' => 'ppp_on_grid',
							'value'      => '9',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Quantity of listing per row on grid view', 'motors-wpbakery-widgets' ),
							'param_name' => 'quant_listing_on_grid',
							'value'      => array(
								'2' => '2',
								'3' => '3',
							),
							'std'        => '3',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

		}

		if ( apply_filters( 'stm_mm_is_active', false ) && function_exists( 'stm_get_taxonomies' ) ) {

			vc_map(
				array(
					'name'     => __( 'STM MegaMenu Top Categories', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_mm_top_categories',
					'category' => __( 'STM MegaMenu', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),

						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Select Listing Category', 'motors-wpbakery-widgets' ),
							'param_name' => 'main_category',
							'value'      => apply_filters( 'stm_get_taxonomies', array() ),
						),
						array(
							'type'        => 'stm_mm_top_terms_vc',
							'heading'     => __( 'Select Listing Category Taxonomies', 'motors-wpbakery-widgets' ),
							'param_name'  => 'child_category',
							'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM MegaMenu Top Vehicles', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_mm_top_vehicles',
					'category' => __( 'STM MegaMenu', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			$top_vehicles = stm_mm_get_top_vehicles();

			vc_map(
				array(
					'name'     => __( 'STM MegaMenu Top Makes Tabs', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_mm_top_makes_tab',
					'category' => __( 'STM MegaMenu', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Check makes for tabs', 'motors-wpbakery-widgets' ),
							'param_name' => 'top_makes',
							'value'      => $top_vehicles,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
		}

		if ( ! in_array( apply_filters( 'stm_theme_demo_layout', '' ), array( 'car_magazine', 'car_rental', 'rental_two', 'service', 'auto_parts' ), true ) ) {
			vc_map(
				array(
					'name'     => __( 'STM Car Dealer Info', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_car_dealer_info',
					'icon'     => 'stm_car_dealer_info',
					'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show WhatsApp button', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_whatsapp',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show Email button', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_email',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
		}

		// Listing shortcodes.
		if ( false === apply_filters( 'stm_is_motorcycle', false ) ) {
			if ( false === apply_filters( 'stm_is_boats', false ) ) {
				vc_map(
					array(
						'name'     => __( 'STM Listing banner', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_listing_banner',
						'icon'     => 'stm_listing_banner',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'attach_image',
								'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
								'param_name' => 'image',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Show SVG arrow', 'motors-wpbakery-widgets' ),
								'param_name' => 'show_svg_arrow',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textarea_html',
								'heading'    => __( 'Content', 'motors-wpbakery-widgets' ),
								'param_name' => 'content',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Icon Filter', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_icon_filter',
						'icon'     => 'stm_icon_filter',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Select Icon Filter taxonomy', 'motors-wpbakery-widgets' ),
								'param_name' => 'filter_selected',
								'value'      => $stm_filter_options,
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'As Carousel', 'motors-wpbakery-widgets' ),
								'param_name' => 'as_carousel',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
									__( 'No', 'motors-wpbakery-widgets' )  => 'no',
								),
								'std'        => 'no',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Limit', 'motors-wpbakery-widgets' ),
								'param_name' => 'limit',
								'dependency' => array(
									'element' => 'as_carousel',
									'value'   => 'no',
								),
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Per row', 'motors-wpbakery-widgets' ),
								'param_name' => 'per_row',
								'value'      => array(
									'1'  => 1,
									'2'  => 2,
									'3'  => 3,
									'4'  => 4,
									'6'  => 6,
									'9'  => 9,
									'12' => 12,
								),
								'std'        => 4,
								'dependency' => array(
									'element' => 'as_carousel',
									'value'   => 'no',
								),
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Items Align', 'motors-wpbakery-widgets' ),
								'param_name' => 'align',
								'value'      => array(
									__( 'Left', 'motors-wpbakery-widgets' )   => 'left',
									__( 'Center', 'motors-wpbakery-widgets' ) => 'center',
									__( 'Right', 'motors-wpbakery-widgets' )  => 'right',
								),
								'std'        => 'left',
								'dependency' => array(
									'element' => 'as_carousel',
									'value'   => 'no',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => __( 'Carousel Items Count', 'motors-wpbakery-widgets' ),
								'param_name'  => 'items_count',
								'std'         => 8,
								'description' => __( 'Number of items to show in carousel', 'motors-wpbakery-widgets' ),
								'dependency'  => array(
									'element' => 'as_carousel',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'textarea_html',
								'heading'    => __( 'Content', 'motors-wpbakery-widgets' ),
								'param_name' => 'content',
							),
							array(
								'type'        => 'textfield',
								'heading'     => __( '"Show all" label text', 'motors-wpbakery-widgets' ),
								'param_name'  => 'duration',
								'description' => __( 'If you want to show only important types, other will be hidden, till user click on this label', 'motors-wpbakery-widgets' ),
								'value'       => 'Show all',
								'dependency'  => array(
									'element' => 'as_carousel',
									'value'   => 'no',
								),
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Image Filter By Body Type', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_image_filter_by_type',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textarea_html',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'content',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Items Per Row', 'motors-wpbakery-widgets' ),
								'param_name' => 'row_number',
								'value'      => array(
									'3' => '3',
									'4' => '4',
									'5' => '5',
								),
								'std'        => '4',
							),
							array(
								'type'       => 'param_group',
								'heading'    => __( 'Items', 'motors-wpbakery-widgets' ),
								'param_name' => 'items',
								'params'     => array(
									array(
										'type'       => 'attach_image',
										'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
										'param_name' => 'images',
									),
									array(
										'type'       => 'attach_image',
										'heading'    => __( 'Image @2x', 'motors-wpbakery-widgets' ),
										'param_name' => 'retina_images',
									),
									array(
										'type'       => 'checkbox',
										'heading'    => __( 'Set Body Type', 'motors-wpbakery-widgets' ),
										'param_name' => 'body_type',
										'value'      => $body_items,
									),
								),
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Popular Makes', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_popular_makes',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'        => 'stm_autocomplete_vc_taxonomies',
								'heading'     => __( 'Select Taxonomy', 'motors-wpbakery-widgets' ),
								'param_name'  => 'taxonomy',
								'description' => __( 'Type slug of the taxonomy (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Limit', 'motors-wpbakery-widgets' ),
								'param_name' => 'limit',
							),
							array(
								'type'       => 'textarea_html',
								'heading'    => __( 'Description', 'motors-wpbakery-widgets' ),
								'param_name' => 'content',
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Listing tabs style 2', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_listings_tabs_2',
						'icon'     => 'stm_listings_tabs_2',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Number of cars to show in tab', 'motors-wpbakery-widgets' ),
								'param_name' => 'per_page',
								'std'        => '8',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Include recent items', 'motors-wpbakery-widgets' ),
								'param_name' => 'recent',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Recent tabs label', 'motors-wpbakery-widgets' ),
								'param_name' => 'recent_label',
								'std'        => __( 'Recent items', 'motors-wpbakery-widgets' ),
								'dependency' => array(
									'element' => 'recent',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Include popular items', 'motors-wpbakery-widgets' ),
								'param_name' => 'popular',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Popular tabs label', 'motors-wpbakery-widgets' ),
								'param_name' => 'popular_label',
								'std'        => __( 'Popular items', 'motors-wpbakery-widgets' ),
								'dependency' => array(
									'element' => 'popular',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Include featured items', 'motors-wpbakery-widgets' ),
								'param_name' => 'featured',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Featured tabs label', 'motors-wpbakery-widgets' ),
								'param_name' => 'featured_label',
								'std'        => __( 'Featured items', 'motors-wpbakery-widgets' ),
								'dependency' => array(
									'element' => 'featured',
									'value'   => 'yes',
								),
							),
							array(
								'type'        => 'stm_autocomplete_vc',
								'heading'     => __( 'Select category', 'motors-wpbakery-widgets' ),
								'param_name'  => 'taxonomy',
								'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Category tabs affix', 'motors-wpbakery-widgets' ),
								'param_name' => 'tab_affix',
								'std'        => __( 'items', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Show "Show more" button in tabs', 'motors-wpbakery-widgets' ),
								'param_name' => 'show_more',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Blog grid', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_blog_grid',
						'icon'     => 'stm_blog_grid',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'        => 'textfield',
								'heading'     => __( 'Number of posts to show', 'motors-wpbakery-widgets' ),
								'param_name'  => 'per_page',
								'std'         => '2',
								'description' => __( 'Sticky posts are not counted here, so if you want to show 3 posts, and you have one sticky post, type "2" in this field', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Show Advertisement', 'motors-wpbakery-widgets' ),
								'param_name' => 'show_advert',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Advertisement Link', 'motors-wpbakery-widgets' ),
								'param_name' => 'advert_link',
								'dependency' => array(
									'element' => 'show_advert',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'attach_image',
								'heading'    => __( 'Advertisement image', 'motors-wpbakery-widgets' ),
								'param_name' => 'advert_image',
								'dependency' => array(
									'element' => 'show_advert',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				$stm_filter_options_location             = array_filter( $stm_filter_options );
				$stm_filter_options_location['Location'] = 'location';

				if ( false === apply_filters( 'stm_is_magazine', false ) ) {
					vc_map(
						array(
							'name'     => __( 'STM Listing Search (tabs)', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_listing_search',
							'icon'     => 'stm_listing_search',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Show All', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_all',
									'value'      => array(
										__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
									),
									'std'        => 'yes',
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Show Category Listings amount', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_amount',
									'value'      => array(
										__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
									),
									'std'        => 'yes',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'All tab label', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_all_label',
									'std'        => __( 'All conditions', 'motors-wpbakery-widgets' ),
									'dependency' => array(
										'element' => 'show_all',
										'value'   => 'yes',
									),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Search button postfix', 'motors-wpbakery-widgets' ),
									'param_name' => 'search_button_postfix',
									'std'        => __( 'Cars', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors-wpbakery-widgets' ),
									'param_name' => 'filter_all',
									'value'      => $stm_filter_options_location,
									'dependency' => array(
										'element' => 'show_all',
										'value'   => 'yes',
									),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Select prefix', 'motors-wpbakery-widgets' ),
									'param_name' => 'select_prefix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Select affix', 'motors-wpbakery-widgets' ),
									'param_name' => 'select_affix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Number Select prefix', 'motors-wpbakery-widgets' ),
									'param_name' => 'number_prefix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Number Select affix', 'motors-wpbakery-widgets' ),
									'param_name' => 'number_affix',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Inactive Tab BG Color', 'motors-wpbakery-widgets' ),
									'param_name' => 'inactive_tab_bg_color',
									'value'      => '#11323e',
									'std'        => '#11323e',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Active Tab BG Color', 'motors-wpbakery-widgets' ),
									'param_name' => 'active_tab_bg_color',
									'value'      => '#153e4d',
									'std'        => '#153e4d',
								),
								array(
									'type'        => 'param_group',
									'heading'     => __( 'Items', 'motors-wpbakery-widgets' ),
									'param_name'  => 'items',
									'description' => __( 'Enter values for items - title, sub title.', 'motors-wpbakery-widgets' ),
									'value'       => rawurlencode(
										wp_json_encode(
											array(
												array(
													'label' => __( 'Taxonomy', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
												array(
													'label' => __( 'Tab Title', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
												array(
													'label' => __( 'Tab ID', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
												array(
													'label' => __( 'Filters', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
											)
										)
									),
									'params'      => array(
										array(
											'type'        => 'stm_autocomplete_vc',
											'heading'     => __( 'Taxonomy', 'motors-wpbakery-widgets' ),
											'param_name'  => 'taxonomy_tab',
											'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions. Note, only one taxonomy will be used as tab). This parameter will be used as default filter for this tab.', 'motors-wpbakery-widgets' ),
										),
										array(
											'type'        => 'textfield',
											'heading'     => __( 'Tab title', 'motors-wpbakery-widgets' ),
											'param_name'  => 'tab_title_single',
											'admin_label' => true,
										),
										array(
											'type'        => 'textfield',
											'heading'     => __( 'Tab ID', 'motors-wpbakery-widgets' ),
											'param_name'  => 'tab_id_single',
											'admin_label' => true,
										),
										array(
											'type'       => 'checkbox',
											'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors-wpbakery-widgets' ),
											'param_name' => 'filter_selected',
											'value'      => $stm_filter_options_location,
										),
									),
								),
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);
				}

				if ( function_exists( 'stm_review_archive_link' ) ) {
					vc_map(
						array(
							'name'     => __( 'STM Listing Two Search (tabs)', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_listing_two_search',
							'icon'     => 'stm_listing_search',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Show Category Listings amount', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_amount',
									'value'      => array(
										__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
									),
									'std'        => 'yes',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'First Tab Label', 'motors-wpbakery-widgets' ),
									'param_name' => 'first_tab_label',
									'std'        => __( 'Find a car', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Second Tab Label', 'motors-wpbakery-widgets' ),
									'param_name' => 'second_tab_label',
									'std'        => __( 'Car reviews', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Third Tab Label', 'motors-wpbakery-widgets' ),
									'param_name' => 'third_tab_label',
									'std'        => __( 'Value my car', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Search button postfix', 'motors-wpbakery-widgets' ),
									'param_name' => 'search_button_postfix',
									'std'        => __( 'Cars', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Select Taxonomies, which will be in first tab as filter', 'motors-wpbakery-widgets' ),
									'param_name' => 'first_tab_tax',
									'value'      => $stm_filter_options_location,
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Select Taxonomies, which will be in second tab as filter', 'motors-wpbakery-widgets' ),
									'param_name' => 'second_tab_tax',
									'value'      => $stm_filter_options_location,
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Select Taxonomies, for Value My Car', 'motors-wpbakery-widgets' ),
									'param_name' => 'third_tab_tax',
									'value'      => stm_get_value_my_car_options(),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Select prefix', 'motors-wpbakery-widgets' ),
									'param_name' => 'select_prefix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Select affix', 'motors-wpbakery-widgets' ),
									'param_name' => 'select_affix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Number Select prefix', 'motors-wpbakery-widgets' ),
									'param_name' => 'number_prefix',
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Number Select affix', 'motors-wpbakery-widgets' ),
									'param_name' => 'number_affix',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Inactive Tab BG Color', 'motors-wpbakery-widgets' ),
									'param_name' => 'inactive_tab_bg_color',
									'value'      => '#11323e',
									'std'        => '#11323e',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Active Tab BG Color', 'motors-wpbakery-widgets' ),
									'param_name' => 'active_tab_bg_color',
									'value'      => '#153e4d',
									'std'        => '#153e4d',
								),
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);
				}

				vc_map(
					array(
						'name'     => __( 'STM Listing Search Without Tabs', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_listing_search_without_tabs',
						'icon'     => 'stm_listing_search_without_tabs',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
								'std'        => __( 'Search Inventory', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Show Category Listings amount', 'motors-wpbakery-widgets' ),
								'param_name' => 'show_amount',
								'value'      => array(
									__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
								),
								'std'        => 'yes',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Search button postfix', 'motors-wpbakery-widgets' ),
								'param_name' => 'search_button_postfix',
								'std'        => __( 'Cars', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'checkbox',
								'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors-wpbakery-widgets' ),
								'param_name' => 'filter_all',
								'value'      => $stm_filter_options_location,
								'dependency' => array(
									'element' => 'show_all',
									'value'   => 'yes',
								),
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Select prefix', 'motors-wpbakery-widgets' ),
								'param_name' => 'select_prefix',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Select affix', 'motors-wpbakery-widgets' ),
								'param_name' => 'select_affix',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Number Select prefix', 'motors-wpbakery-widgets' ),
								'param_name' => 'number_prefix',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Number Select affix', 'motors-wpbakery-widgets' ),
								'param_name' => 'number_affix',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Heading Title', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_heading_title',
						'icon'     => 'stm_heading_title',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Top Info (Title, price)', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_top_info',
						'icon'     => 'stm_car_top_info',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Gallery', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_gallery',
						'icon'     => 'stm_car_listing_gallery',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Details', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_details',
						'icon'     => 'stm_car_listing_details',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Features', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_features',
						'icon'     => 'stm_car_listing_features',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Seller Note', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_seller_note',
						'icon'     => 'stm_car_seller_note',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Car Listing Contact Form', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_contact_form',
						'icon'     => 'stm_car_listing_contact_form',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Choose form', 'motors-wpbakery-widgets' ),
								'param_name' => 'form',
								'value'      => $available_cf7,
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Similar cars', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_similar',
						'icon'     => 'stm_car_listing_similar',
						'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
					// Pricing.
					$stm_pt_params = array();

					$stm_pt_params[] = array(
						'type'       => 'dropdown',
						'heading'    => __( 'Tables', 'motors-wpbakery-widgets' ),
						'param_name' => 'pricing_tables_count',
						'value'      => array(
							__( 'Three', 'motors-wpbakery-widgets' ) => 'three',
							__( 'Two', 'motors-wpbakery-widgets' )   => 'two',
							__( 'One', 'motors-wpbakery-widgets' )   => 'one',
						),
						'std'        => 'three',
					);

					for ( $i = 1; $i <= 3; $i ++ ) {
						$stm_pt_params[] = array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_title',
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'param_group',
							'heading'    => __( 'Period', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_periods',
							'value'      => rawurlencode(
								wp_json_encode(
									array(
										array(
											'label' => __( 'Period', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Price', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Period Text', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Period link', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
									)
								)
							),
							'params'     => array(
								array(
									'type'        => 'dropdown',
									'heading'     => __( 'Period', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_periods_period',
									'value'       => array(
										esc_html__( 'Month', 'motors-wpbakery-widgets' )  => 'month',
										esc_html__( 'Yearly', 'motors-wpbakery-widgets' ) => 'yearly',
									),
									'admin_label' => true,
								),
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Price', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_periods_price',
									'admin_label' => true,
								),
								array(
									'type'       => 'dropdown',
									'heading'    => __( 'Plan add to cart (Plan ID)', 'motors-wpbakery-widgets' ),
									'param_name' => 'pt_' . $i . '_periods_link',
									'value'      => $products_array,
									/* translators: table name */
									'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
								),
							),
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'param_group',
							'heading'    => __( 'Features', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_features',
							'value'      => rawurlencode(
								wp_json_encode(
									array(
										array(
											'label' => __( 'Title', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Check', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Text', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
									)
								)
							),
							'params'     => array(
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Title', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_feature_title',
									'admin_label' => true,
								),
								array(
									'type'        => 'checkbox',
									'heading'     => __( 'Check', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_feature_check',
									'admin_label' => true,
								),
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Text', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_feature_text',
									'admin_label' => true,
								),
							),
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'param_group',
							'heading'    => __( 'Labels', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_labels',
							'value'      => rawurlencode(
								wp_json_encode(
									array(
										array(
											'label' => __( 'Label text', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
										array(
											'label' => __( 'Label color', 'motors-wpbakery-widgets' ),
											'value' => '',
										),
									)
								)
							),
							'params'     => array(
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Label text', 'motors-wpbakery-widgets' ),
									'param_name'  => 'pt_' . $i . '_label_text',
									'admin_label' => true,
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => __( 'Label background color', 'motors-wpbakery-widgets' ),
									'param_name' => 'pt_' . $i . '_label_color',
									'value'      => '',
								),
							),
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'textfield',
							'heading'    => __( 'Link text', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_link_text',
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'vc_link',
							'heading'    => __( 'Link', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_link',
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);

						$stm_pt_params[] = array(
							'type'       => 'dropdown',
							'heading'    => __( 'Plan add to cart (Plan ID)', 'motors-wpbakery-widgets' ),
							'param_name' => 'pt_' . $i . '_add_to_cart',
							'value'      => $products_array,
							/* translators: table name */
							'group'      => sprintf( __( 'Table %s', 'motors-wpbakery-widgets' ), $i ),
						);
					}

					$stm_pt_params[] = array(
						'type'       => 'textfield',
						'heading'    => __( 'Price label', 'motors-wpbakery-widgets' ),
						'param_name' => 'stm_motors_price_label',
					);

					$stm_pt_params[] = array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					);

					// Pricing Tables.
					vc_map(
						array(
							'name'     => __( 'STM Pricing Tables', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_pricing_tables',
							'category' => __( 'STM Classified Single Listing', 'motors-wpbakery-widgets' ),
							'params'   => $stm_pt_params,
						)
					);

					// Account.
					vc_map(
						array(
							'name'     => __( 'STM User login/register', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_login_register',
							'icon'     => 'stm_login_register',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'vc_link',
									'heading'    => __( 'Link to Terms of service page', 'motors-wpbakery-widgets' ),
									'param_name' => 'link',
								),
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);

					// Add a car available.
					vc_map(
						array(
							'name'     => __( 'STM Posts Available', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_posts_available',
							'icon'     => 'stm_posts_available',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);

					/*Add car*/
					vc_map(
						array(
							'name'     => __( 'STM Add a car', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_add_a_car',
							'icon'     => 'stm_add_a_car',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'dropdown',
									'heading'    => __( 'Include car title', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_car_title',
									'std'        => 'no',
									'value'      => array(
										esc_html__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
										esc_html__( 'No', 'motors-wpbakery-widgets' )  => 'no',
									),
								),
								array(
									'type'        => 'stm_autocomplete_vc_taxonomies',
									'heading'     => __( 'Main taxonomies to fill', 'motors-wpbakery-widgets' ),
									'param_name'  => 'taxonomy',
									'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Show number fields as input instead of dropdown', 'motors-wpbakery-widgets' ),
									'param_name' => 'use_inputs',
									'value'      => array(
										__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
									),
								),
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Allowed histories', 'motors-wpbakery-widgets' ),
									'param_name'  => 'stm_histories',
									'description' => esc_html__( 'Enter allowed histories, separated by comma without spaces. Example - (Carfax, AutoCheck, Carfax 1 Owner, etc)', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'param_group',
									'heading'    => __( 'Items', 'motors-wpbakery-widgets' ),
									'param_name' => 'items',
									'value'      => rawurlencode(
										wp_json_encode(
											array(
												array(
													'label' => __( 'Car feature title', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
												array(
													'label' => __( 'Car features', 'motors-wpbakery-widgets' ),
													'value' => '',
												),
											)
										)
									),
									'params'     => array(
										array(
											'type'        => 'textfield',
											'heading'     => __( 'Car feature section title', 'motors-wpbakery-widgets' ),
											'param_name'  => 'tab_title_single',
											'admin_label' => true,
										),
										array(
											'type'        => 'textfield',
											'heading'     => __( 'Car feature section features', 'motors-wpbakery-widgets' ),
											'param_name'  => 'tab_title_labels',
											'description' => esc_html__( 'Enter features, separated by comma without spaces. Example - (Bluetooth,DVD Player,etc)', 'motors-wpbakery-widgets' ),
										),
									),
									'group'      => esc_html__( 'Step 2 features', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textarea_html',
									'heading'    => __( 'Media gallery notification text', 'motors-wpbakery-widgets' ),
									'param_name' => 'content',
									'group'      => esc_html__( 'Step 3 gallery', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'        => 'textfield',
									'heading'     => __( 'Seller template phrases', 'motors-wpbakery-widgets' ),
									'param_name'  => 'stm_phrases',
									'description' => esc_html__( 'Enter phrases, separated by comma without spaces. Example - (Excellent condition, Always garaged, etc)', 'motors-wpbakery-widgets' ),
									'group'       => esc_html__( 'Step 5 phrases', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Title for new users', 'motors-wpbakery-widgets' ),
									'param_name' => 'stm_title_user',
									'group'      => esc_html__( 'Register/Login User', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textarea',
									'heading'    => __( 'Text for new users', 'motors-wpbakery-widgets' ),
									'param_name' => 'stm_text_user',
									'group'      => esc_html__( 'Register/Login User', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'vc_link',
									'heading'    => __( 'Agreement page', 'motors-wpbakery-widgets' ),
									'param_name' => 'link',
									'group'      => esc_html__( 'Register/Login User', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Price title', 'motors-wpbakery-widgets' ),
									'param_name' => 'stm_title_price',
									'group'      => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Price label', 'motors-wpbakery-widgets' ),
									'param_name' => 'price_label',
									'group'      => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'dropdown',
									'heading'    => __( 'Show Sale Price label', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_sale_price_label',
									'group'      => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
									'std'        => 'no',
									'value'      => array(
										esc_html__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
										esc_html__( 'No', 'motors-wpbakery-widgets' )  => 'no',
									),
								),
								array(
									'type'       => 'textfield',
									'heading'    => __( 'Sale Price label', 'motors-wpbakery-widgets' ),
									'param_name' => 'sale_price_label',
									'group'      => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
									'dependency' => array(
										'element' => 'show_sale_price_label',
										'value'   => 'yes',
									),
								),
								array(
									'type'       => 'dropdown',
									'heading'    => __( 'Show Custom label', 'motors-wpbakery-widgets' ),
									'param_name' => 'show_custom_label',
									'group'      => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
									'std'        => 'no',
									'value'      => array(
										esc_html__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
										esc_html__( 'No', 'motors-wpbakery-widgets' )  => 'no',
									),
								),
								array(
									'type'        => 'textarea',
									'heading'     => __( 'Price description', 'motors-wpbakery-widgets' ),
									'param_name'  => 'stm_title_desc',
									'group'       => esc_html__( 'Price', 'motors-wpbakery-widgets' ),
									'description' => esc_html__( 'The Price description appears only when the Sale Price label and Show Custom label are disabled', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);

					// Add a car available.
					vc_map(
						array(
							'name'     => __( 'STM Dealer List', 'motors-wpbakery-widgets' ),
							'base'     => 'stm_dealer_list',
							'icon'     => 'stm_dealer_list',
							'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
							'params'   => array(
								array(
									'type'       => 'checkbox',
									'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors-wpbakery-widgets' ),
									'param_name' => 'stm_filter_dealers_by',
									'value'      => $stm_filter_options_location,
								),
								array(
									'type'        => 'stm_autocomplete_vc',
									'heading'     => __( 'Show dealer category fields', 'motors-wpbakery-widgets' ),
									'param_name'  => 'taxonomy',
									'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
								),
								array(
									'type'       => 'css_editor',
									'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
									'param_name' => 'css',
									'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
								),
							),
						)
					);
				}

				vc_map(
					array(
						'name'     => __( 'STM Icon Counter', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_icon_counter',
						'icon'     => 'stm_icon_counter',
						'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
								'param_name' => 'icon',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
								'param_name' => 'icon_color',
								'value'      => '',
							),
							array(
								'type'        => 'textfield',
								'heading'     => __( 'Icon size(px)', 'motors-wpbakery-widgets' ),
								'param_name'  => 'stm_icon_size',
								'description' => __( 'Just type a number.', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Icon Box Text Color', 'motors-wpbakery-widgets' ),
								'param_name' => 'box_bg_color',
								'value'      => '',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Counter Text Align', 'motors-wpbakery-widgets' ),
								'param_name' => 'counter_text_align',
								'value'      => array(
									__( 'Left', 'motors-wpbakery-widgets' )   => 'left',
									__( 'Center', 'motors-wpbakery-widgets' ) => 'center',
									__( 'Right', 'motors-wpbakery-widgets' )  => 'right',
								),
								'std'        => 'left',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count to number', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_value',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count Number Font Size', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_value_font_size',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count time (.s)', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_time',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Counter Affix', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_affix',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count label', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_label',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count Label Font Size', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_label_font_size',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
			} else {
				vc_map(
					array(
						'name'     => __( 'STM Car Features', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_car_listing_features',
						'icon'     => 'stm_car_listing_features',
						'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
				vc_map(
					array(
						'name'     => __( 'STM Icon Counter', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_icon_counter_boats',
						'icon'     => 'stm_icon_counter_boats',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'iconpicker',
								'heading'    => __( 'Icon', 'motors-wpbakery-widgets' ),
								'param_name' => 'icon',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
								'param_name' => 'icon_color',
								'value'      => '',
							),
							array(
								'type'        => 'textfield',
								'heading'     => __( 'Icon size(px)', 'motors-wpbakery-widgets' ),
								'param_name'  => 'stm_icon_size',
								'description' => __( 'Just type a number.', 'motors-wpbakery-widgets' ),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => __( 'Icon Box Text Color', 'motors-wpbakery-widgets' ),
								'param_name' => 'box_bg_color',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count to number', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_value',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count time (.s)', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_time',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Counter Affix', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_affix',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Count label', 'motors-wpbakery-widgets' ),
								'param_name' => 'stm_counter_label',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
				vc_map(
					array(
						'name'     => __( 'STM Featured Boats', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_featured_boats',
						'icon'     => 'stm_featured_boats',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Display number', 'motors-wpbakery-widgets' ),
								'param_name' => 'per_page',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
				vc_map(
					array(
						'name'     => __( 'STM Row Icons', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_row_icons',
						'icon'     => 'stm_row_icons',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'dropdown',
								'heading'    => __( 'Select Icon Filter taxonomy', 'motors-wpbakery-widgets' ),
								'param_name' => 'filter_selected',
								'value'      => $stm_filter_options,
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
				vc_map(
					array(
						'name'     => __( 'STM Video', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boats_video',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'attach_image',
								'heading'    => __( 'Video poster', 'motors-wpbakery-widgets' ),
								'param_name' => 'image',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Iframe Height', 'motors-wpbakery-widgets' ),
								'param_name' => 'height',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Video Link', 'motors-wpbakery-widgets' ),
								'param_name' => 'link',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				// Testimonials Boats.
				vc_map(
					array(
						'name'      => __( 'STM Testimonials Boats', 'motors-wpbakery-widgets' ),
						'base'      => 'stm_testimonials_boats',
						'as_parent' => array( 'only' => 'stm_testimonial_boats' ),
						'category'  => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'    => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
						'js_view'   => 'VcColumnView',
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Testimonial Boats', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_testimonial_boats',
						'as_child' => array( 'only' => 'stm_testimonials_boats' ),
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'attach_image',
								'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
								'param_name' => 'image',
							),
							array(
								'type'       => 'textarea_html',
								'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
								'param_name' => 'content',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Author name', 'motors-wpbakery-widgets' ),
								'param_name' => 'author',
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Latest News', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_latest_news',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Number of news to display', 'motors-wpbakery-widgets' ),
								'param_name' => 'number_of_posts',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Colors', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_colors',
						'icon'     => 'stm_colors',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'param_group',
								'heading'    => __( 'Colors', 'motors-wpbakery-widgets' ),
								'param_name' => 'items',
								'value'      => rawurlencode(
									wp_json_encode(
										array(
											array(
												'label' => __( 'Color name', 'motors-wpbakery-widgets' ),
												'value' => '',
											),
											array(
												'label' => __( 'Color', 'motors-wpbakery-widgets' ),
												'value' => '',
											),
										)
									)
								),
								'params'     => array(
									array(
										'type'        => 'textfield',
										'heading'     => __( 'Color name', 'motors-wpbakery-widgets' ),
										'param_name'  => 'color_name',
										'admin_label' => true,
									),
									array(
										'type'       => 'colorpicker',
										'heading'    => __( 'Color', 'motors-wpbakery-widgets' ),
										'param_name' => 'color',
										'value'      => '',
									),
								),
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Boat Title&Price', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boat_title',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Boat Image', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boat_image',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Boat Data', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boat_data',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Boat Gallery', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boat_gallery',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Boat Videos', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_boat_videos',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Contact Information', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_contact_information',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Address', 'motors-wpbakery-widgets' ),
								'param_name' => 'address',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Phone', 'motors-wpbakery-widgets' ),
								'param_name' => 'phone',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Mail', 'motors-wpbakery-widgets' ),
								'param_name' => 'mail',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Hours', 'motors-wpbakery-widgets' ),
								'param_name' => 'hours',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);

				// Tech info.
				vc_map(
					array(
						'name'      => __( 'STM Contacts', 'motors-wpbakery-widgets' ),
						'base'      => 'stm_contacts_boat',
						'as_parent' => array( 'only' => 'stm_contact_boat' ),
						'category'  => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'    => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
						'js_view'   => 'VcColumnView',
					)
				);
				vc_map(
					array(
						'name'     => __( 'STM Contact', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_contact_boat',
						'as_child' => array( 'only' => 'stm_contacts_boat' ),
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'attach_images',
								'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
								'param_name' => 'images',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Name', 'motors-wpbakery-widgets' ),
								'param_name' => 'name',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Phone', 'motors-wpbakery-widgets' ),
								'param_name' => 'phone',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Mail', 'motors-wpbakery-widgets' ),
								'param_name' => 'mail',
							),
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Skype', 'motors-wpbakery-widgets' ),
								'param_name' => 'skype',
							),
						),
					)
				);

				vc_map(
					array(
						'name'     => __( 'STM Featured Boats Widget', 'motors-wpbakery-widgets' ),
						'base'     => 'stm_featured_boats_side',
						'category' => __( 'STM Boats Layout', 'motors-wpbakery-widgets' ),
						'params'   => array(
							array(
								'type'       => 'textfield',
								'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
								'param_name' => 'title',
							),
							array(
								'type'       => 'css_editor',
								'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
								'param_name' => 'css',
								'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
							),
						),
					)
				);
			}
		}

		if ( apply_filters( 'stm_is_magazine', false ) ) {
			// Recent posts magazine.

			vc_map(
				array(
					'name'     => __( 'STM Recent Posts Magazine', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_recent_posts_magazine',
					'icon'     => 'stm_recent_posts_magazine',
					'category' => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Number of posts', 'motors-wpbakery-widgets' ),
							'param_name' => 'number_of_posts',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Category', 'motors-wpbakery-widgets' ),
							'param_name' => 'category_selected',
							'value'      => $category_list,
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Listing Search With Car Review Rating', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_listing_search_with_car_rating',
					'category' => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
							'std'        => __( 'All conditions', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Quantity Of Cars In Result', 'motors-wpbakery-widgets' ),
							'param_name' => 'cars_quantity',
							'std'        => __( '8', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show Category Listings amount', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_amount',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'        => 'stm_autocomplete_vc_taxonomies',
							'heading'     => __( 'Select Taxonomy', 'motors-wpbakery-widgets' ),
							'param_name'  => 'taxonomy',
							'description' => __( 'Type slug of the taxonomy (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Select prefix', 'motors-wpbakery-widgets' ),
							'param_name' => 'select_prefix',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Select affix', 'motors-wpbakery-widgets' ),
							'param_name' => 'select_affix',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number Select prefix', 'motors-wpbakery-widgets' ),
							'param_name' => 'number_prefix',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number Select affix', 'motors-wpbakery-widgets' ),
							'param_name' => 'number_affix',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Magazine Excerption', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_excerption_item',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textarea_html',
							'heading'    => esc_html__( 'Excerption', 'motors-wpbakery-widgets' ),
							'param_name' => 'content',
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Popular Posts', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_popular_posts',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'popular_title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of posts', 'motors-wpbakery-widgets' ),
							'param_name' => 'number_of_posts',
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Recent Video Posts', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_recent_video_posts',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'recent_video_title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of posts', 'motors-wpbakery-widgets' ),
							'param_name' => 'number_of_posts',
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Social Follow Counter', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_social_follow_counter',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'ata_title',
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Mazagine MailChimp Form', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_magazine_mailchimp_form',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'mc_title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Enter MailChimp Form Shortcode', 'motors-wpbakery-widgets' ),
							'param_name' => 'mc_shortcode',
						),
					),
				)
			);

			vc_map(
				array(
					'name'            => esc_html__( 'Stm Features Posts', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_features_posts',
					'content_element' => true,
					'category'        => __( 'STM Magazine', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'features_title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of items to show (min number 4)', 'motors-wpbakery-widgets' ),
							'param_name' => 'posts_per_page',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Use Google AdSense', 'motors-wpbakery-widgets' ),
							'param_name' => 'use_adsense',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'AdSense Position (Use 1,2,3,4)', 'motors-wpbakery-widgets' ),
							'param_name' => 'adsense_position',
							'dependency' => array(
								'element' => 'use_adsense',
								'value'   => 'yes',
							),
						),
						array(
							'type'       => 'textarea_html',
							'heading'    => esc_html__( 'Google AdSense Code', 'motors-wpbakery-widgets' ),
							'param_name' => 'content',
							'dependency' => array(
								'element' => 'use_adsense',
								'value'   => 'yes',
							),
						),
					),
				)
			);
		}

		/*MOTOS*/
		if ( apply_filters( 'stm_is_motorcycle', false ) ) {
			vc_map(
				array(
					'name'     => __( 'STM Filter Selects', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_filter_selects',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Filter options', 'motors-wpbakery-widgets' ),
							'param_name' => 'filter_selected',
							'value'      => array_filter( $stm_filter_options ),
							'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Number of filter columns', 'motors-wpbakery-widgets' ),
							'param_name' => 'filter_columns_number',
							'value'      => array(
								'6' => '6',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',
							),
							'std'        => '3',
							'group'      => __( 'Search Options', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Inventory Category', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_inventory_categories',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'        => 'stm_autocomplete_vc',
							'heading'     => __( 'Select main category (Only one category will be selected)', 'motors-wpbakery-widgets' ),
							'param_name'  => 'taxonomy_main',
							'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'        => 'stm_autocomplete_vc',
							'heading'     => __( 'Select subcategories', 'motors-wpbakery-widgets' ),
							'param_name'  => 'taxonomy',
							'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'attach_image',
							'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
							'param_name' => 'image',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Listing tabs style 2', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_listings_tabs_2',
					'icon'     => 'stm_listings_tabs_2',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of cars to show in tab', 'motors-wpbakery-widgets' ),
							'param_name' => 'per_page',
							'std'        => '8',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Include recent items', 'motors-wpbakery-widgets' ),
							'param_name' => 'recent',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Recent tabs label', 'motors-wpbakery-widgets' ),
							'param_name' => 'recent_label',
							'std'        => __( 'Recent items', 'motors-wpbakery-widgets' ),
							'dependency' => array(
								'element' => 'recent',
								'value'   => 'yes',
							),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Include popular items', 'motors-wpbakery-widgets' ),
							'param_name' => 'popular',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Popular tabs label', 'motors-wpbakery-widgets' ),
							'param_name' => 'popular_label',
							'std'        => __( 'Popular items', 'motors-wpbakery-widgets' ),
							'dependency' => array(
								'element' => 'popular',
								'value'   => 'yes',
							),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Include featured items', 'motors-wpbakery-widgets' ),
							'param_name' => 'featured',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Featured tabs label', 'motors-wpbakery-widgets' ),
							'param_name' => 'featured_label',
							'std'        => __( 'Featured items', 'motors-wpbakery-widgets' ),
							'dependency' => array(
								'element' => 'featured',
								'value'   => 'yes',
							),
						),
						array(
							'type'        => 'stm_autocomplete_vc',
							'heading'     => __( 'Select category', 'motors-wpbakery-widgets' ),
							'param_name'  => 'taxonomy',
							'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Category tabs affix', 'motors-wpbakery-widgets' ),
							'param_name' => 'tab_affix',
							'std'        => __( 'items', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show "Show more" button in tabs', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_more',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
							'std'        => 'yes',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
			vc_map(
				array(
					'name'     => __( 'STM Video', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_boats_video',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'attach_image',
							'heading'    => __( 'Video poster', 'motors-wpbakery-widgets' ),
							'param_name' => 'image',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Iframe Height', 'motors-wpbakery-widgets' ),
							'param_name' => 'height',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Video Link', 'motors-wpbakery-widgets' ),
							'param_name' => 'link',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
			vc_map(
				array(
					'name'     => __( 'STM Row Icons', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_row_icons',
					'icon'     => 'stm_row_icons',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Select Icon Filter taxonomy', 'motors-wpbakery-widgets' ),
							'param_name' => 'filter_selected',
							'value'      => $stm_filter_options,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Car Features', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_car_listing_features',
					'icon'     => 'stm_car_listing_features',
					'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Moto Gallery', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_boat_gallery',
					'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Moto Top (Title, Price, Featured Photo)', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_moto_top',
					'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Moto Data', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_moto_data',
					'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Moto Links', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_moto_links',
					'category' => __( 'STM Single Motorcycle', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Contact Information', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_contact_information',
					'category' => __( 'STM Motos Layout', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Address', 'motors-wpbakery-widgets' ),
							'param_name' => 'address',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Phone', 'motors-wpbakery-widgets' ),
							'param_name' => 'phone',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Mail', 'motors-wpbakery-widgets' ),
							'param_name' => 'mail',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Hours', 'motors-wpbakery-widgets' ),
							'param_name' => 'hours',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
		}

		/*Rental*/
		if ( apply_filters( 'stm_is_rental', false ) ) {
			vc_map(
				array(
					'name'     => __( 'STM Text Baloon', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_text_baloon',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textarea_html',
							'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
							'param_name' => 'content',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Offices Map', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_offices_map',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Map height (px)', 'motors-wpbakery-widgets' ),
							'param_name' => 'map_height',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Zoom', 'motors-wpbakery-widgets' ),
							'param_name' => 'map_zoom',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => __( 'Pin', 'motors-wpbakery-widgets' ),
							'param_name' => 'pin',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => __( 'Pin on hover', 'motors-wpbakery-widgets' ),
							'param_name' => 'pin_2',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Mobile styles', 'motors-wpbakery-widgets' ),
							'param_name' => 'css_mobile',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Products Grid', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_car_class_grid',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of items to show', 'motors-wpbakery-widgets' ),
							'param_name' => 'posts_per_page',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Rent Car Form', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_rent_car_form',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Set working hours. example: 9-18', 'motors-wpbakery-widgets' ),
							'param_name' => 'office_working_hours',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Style', 'motors-wpbakery-widgets' ),
							'param_name' => 'style',
							'value'      => array(
								__( 'Style 1', 'motors-wpbakery-widgets' ) => 'style_1',
								__( 'Style 2', 'motors-wpbakery-widgets' ) => 'style_2',
							),
							'std'        => 'style_1',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Align', 'motors-wpbakery-widgets' ),
							'param_name' => 'align',
							'value'      => array(
								__( 'Left', 'motors-wpbakery-widgets' )   => 'text-left',
								__( 'Center', 'motors-wpbakery-widgets' ) => 'text-center',
								__( 'Right', 'motors-wpbakery-widgets' )  => 'text-right',
							),
							'std'        => 'text-right',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Reservation navigation', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_reservation_navigation',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			vc_map(
				array(
					'name'     => __( 'STM Reservation Info', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_reservation_order_information',
					'category' => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

		}

		vc_map(
			array(
				'name'     => __( 'STM Inventory On Map', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_inventory_on_map',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'inv_on_map_title',
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Inventory No Filter', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_inventory_no_filter',
				'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Order By', 'motors-wpbakery-widgets' ),
						'param_name' => 'order_by',
						'value'      => array(
							'DESC' => 'desc',
							'ASC'  => 'asc',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Posts Per Page', 'motors-wpbakery-widgets' ),
						'param_name' => 'posts_per_page',
						'std'        => 4,
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Inventory With Filter', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_inventory_with_filter',
				'category' => __( 'STM Classified Layout', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'inventory_title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Posts Per Page', 'motors-wpbakery-widgets' ),
						'param_name' => 'posts_per_page',
						'std'        => 4,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Navigation', 'motors-wpbakery-widgets' ),
						'param_name' => 'navigation',
						'value'      => array(
							__( 'Pagination', 'motors-wpbakery-widgets' )       => 'pagination',
							__( 'Load More Button', 'motors-wpbakery-widgets' ) => 'load_more',
						),
						'std'        => 'load_more',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors-wpbakery-widgets' ),
						'param_name' => 'filter_all',
						'value'      => array_filter( $stm_filter_options ),
						'dependency' => array(
							'element' => 'show_all',
							'value'   => 'yes',
						),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Category Info Box', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_category_info_box',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Select Taxonomy', 'motors-wpbakery-widgets' ),
						'param_name' => 'cat_slug',
						'value'      => $stm_all_options,
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Video Button', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_video_button',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Youtube video url', 'motors-wpbakery-widgets' ),
						'param_name' => 'video_url',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Play button color', 'motors-wpbakery-widgets' ),
						'param_name' => 'color',
						'value'      => '',
					),
				),
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Car Leasing', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_car_leasing',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'c_l_title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price', 'motors-wpbakery-widgets' ),
						'param_name' => 'c_l_price',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price affix', 'motors-wpbakery-widgets' ),
						'param_name' => 'c_l_price_affix',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Price subtitle', 'motors-wpbakery-widgets' ),
						'param_name' => 'c_l_price_subtitle',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Contact Form Shortcode', 'motors-wpbakery-widgets' ),
						'param_name' => 'c_l_shortcode',
					),
				),
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Cars On Top', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_cars_on_top',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Numbers of Cars', 'motors-wpbakery-widgets' ),
						'param_name' => 'on_top_numbers',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Reduced Cars', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_reduced_cars',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Numbers of Cars', 'motors-wpbakery-widgets' ),
						'param_name' => 'reduced_numbers',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Info Block Animate', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_info_block_animate',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'i_b_title',
					),
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'motors-wpbakery-widgets' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		if ( defined( 'STM_MOTORS_VIN_DECODERS_PATH' ) && ( apply_filters( 'stm_is_listing', false ) || apply_filters( 'stm_is_listing_two', false ) || apply_filters( 'stm_is_listing_three', false ) || apply_filters( 'stm_is_listing_four', false ) || apply_filters( 'stm_is_car_dealer', false ) || apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_motorcycle', false ) || apply_filters( 'stm_is_equipment', false ) ) ) {
			vc_map(
				array(
					'name'            => esc_html__( 'Stm Vehicle VIN Check', 'motors-wpbakery-widgets' ),
					'base'            => 'stm_vehicle_vin_check',
					'content_element' => true,
					'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
					'params'          => array(
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Vin Button Background color', 'motors-wpbakery-widgets' ),
							'param_name' => 'vin_button_bg_color',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Vin Button Text color', 'motors-wpbakery-widgets' ),
							'param_name' => 'vin_button_text_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Vin Button Hover Background color', 'motors-wpbakery-widgets' ),
							'param_name' => 'vin_button_bg_hover_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Vin Button Text Hover color', 'motors-wpbakery-widgets' ),
							'param_name' => 'vin_button_text_hover_color',
							'value'      => '',
						),
					),
				)
			);
		}

		vc_map(
			array(
				'name'            => esc_html__( 'Stm Listing Map by My Location', 'motors-wpbakery-widgets' ),
				'base'            => 'stm_listing_map_by_my_location',
				'content_element' => true,
				'category'        => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'          => array(
					array(
						'type'       => 'stm_autocomplete_vc_location',
						'heading'    => esc_html__( 'Address', 'motors-wpbakery-widgets' ),
						'param_name' => 'address',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Latitude', 'motors-wpbakery-widgets' ),
						'param_name' => 'lat',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Longitude', 'motors-wpbakery-widgets' ),
						'param_name' => 'lng',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Search Radius', 'motors-wpbakery-widgets' ),
						'param_name' => 'search_radius',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Map Height', 'motors-wpbakery-widgets' ),
						'param_name' => 'map_height',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Map Zoom', 'motors-wpbakery-widgets' ),
						'param_name' => 'map_zoom',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Map ScrollWheel', 'motors-wpbakery-widgets' ),
						'param_name' => 'map_scrollwheel',
					),
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Marker Image', 'motors-wpbakery-widgets' ),
						'param_name' => 'marker',
					),
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Cluster Image (60x60)', 'motors-wpbakery-widgets' ),
						'param_name' => 'cluster',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'     => __( 'STM Listing Categories Grid', 'motors-wpbakery-widgets' ),
				'base'     => 'stm_listing_categories_grid',
				'icon'     => 'stm_listing_categories_grid',
				'category' => __( 'STM', 'motors-wpbakery-widgets' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
						'param_name' => 'title',
					),
					array(
						'type'        => 'stm_autocomplete_vc',
						'heading'     => __( 'Taxonomy', 'motors-wpbakery-widgets' ),
						'param_name'  => 'taxonomy_list',
						'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions. Note, only one taxonomy will be used as tab). This parameter will be used as default filter for this tab.', 'motors-wpbakery-widgets' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
					),
				),
			)
		);

		if ( apply_filters( 'stm_is_aircrafts', false ) ) {
			vc_map(
				array(
					'name'     => __( 'STM Aircraft Data Table', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_aircraft_data_table',
					'icon'     => 'stm_aircraft_data_table',
					'category' => __( 'STM Aircraft', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Taxonomies', 'motors-wpbakery-widgets' ),
							'param_name' => 'taxonomy_list_col_one',
							'value'      => $stm_all_options,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
		}

		if ( apply_filters( 'stm_is_ev_dealer', false ) ) {
			// Main slider.
			vc_map(
				array(
					'name'     => __( 'EV Swiper Slider', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_swiper_slider',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'stm_slider_notice',
							'heading'    => __( 'Slider Settings', 'motors-wpbakery-widgets' ),
							'param_name' => 'stm_slider_notice',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Featured vehicles slider.
			vc_map(
				array(
					'name'     => __( 'EV Featured Vehicles', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_featured_vehicles',
					'icon'     => 'stm_ev_featured_vehicles',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
							'param_name' => 'title_color',
							'value'      => '',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Enable "Details" button', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_details_btn',
							'std'        => 'yes',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show price', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_price',
							'std'        => 'yes',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Recently added listings.
			vc_map(
				array(
					'name'     => __( 'EV Recent Listings', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_recent_listings',
					'icon'     => 'stm_ev_recent_listings',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
							'param_name' => 'title_color',
							'value'      => '',
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Number of listings', 'motors-wpbakery-widgets' ),
							'param_name'  => 'per_page',
							'description' => __( '-1 will show all recent items', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show "View all" button', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_view_all_btn',
							'std'        => 'yes',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( '"View all" button link', 'motors-wpbakery-widgets' ),
							'param_name' => 'view_all_link',
							'std'        => esc_url( apply_filters( 'stm_filter_listing_link', '' ) ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// A different kind of stats counter for EV dealership.
			vc_map(
				array(
					'name'     => __( 'EV Stats Counter', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_stats_counter',
					'icon'     => 'stm_ev_stats_counter',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
							'param_name' => 'title_color',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Counter Value', 'motors-wpbakery-widgets' ),
							'param_name' => 'counter_value',
							'value'      => '1000',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Counter color', 'motors-wpbakery-widgets' ),
							'param_name' => 'counter_color',
							'value'      => '',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Append plus sign to number?', 'motors-wpbakery-widgets' ),
							'param_name' => 'append_plus',
							'std'        => '',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Duration', 'motors-wpbakery-widgets' ),
							'param_name' => 'duration',
							'value'      => '2.5',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Woocommerce products.
			vc_map(
				array(
					'name'     => __( 'EV Shop Products', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_shop_products',
					'icon'     => 'stm_ev_shop_products',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Title color', 'motors-wpbakery-widgets' ),
							'param_name' => 'title_color',
							'value'      => '',
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Number of products', 'motors-wpbakery-widgets' ),
							'param_name'  => 'per_page',
							'description' => __( '-1 will show all recent items', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Show "Online Shop" button', 'motors-wpbakery-widgets' ),
							'param_name' => 'show_view_all_btn',
							'std'        => 'yes',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Social icons, shows icons selected in theme options.
			vc_map(
				array(
					'name'     => __( 'EV Social Icons', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_social_icons',
					'icon'     => 'stm_ev_social_icons',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Align icons', 'motors-wpbakery-widgets' ),
							'param_name' => 'align_icons',
							'value'      => array(
								'Left'   => 'left',
								'Center' => 'center',
								'Right'  => 'right',
							),
							'std'        => 'left',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'heading'     => __( 'Icons size', 'motors-wpbakery-widgets' ),
							'param_name'  => 'icons_size',
							'description' => __( 'Icon size in pixels', 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icons color', 'motors-wpbakery-widgets' ),
							'param_name' => 'icons_color',
							'value'      => '',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Open links in new window?', 'motors-wpbakery-widgets' ),
							'param_name' => 'target_blank',
							'std'        => 'yes',
							'value'      => array(
								__( 'Yes', 'motors-wpbakery-widgets' ) => 'yes',
							),
						),

						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Social icons, shows icons selected in theme options.
			vc_map(
				array(
					'name'     => __( 'EV Photo Collage', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_photo_collage',
					'icon'     => 'stm_ev_photo_collage',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'        => 'attach_images',
							'heading'     => __( 'Images', 'motors-wpbakery-widgets' ),
							'param_name'  => 'images',
							'description' => __( "There's no auto resize, so please upload appropriately sized images.", 'motors-wpbakery-widgets' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Front images left offset, in pixels', 'motors-wpbakery-widgets' ),
							'param_name' => 'offset_left',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Front images frame color', 'motors-wpbakery-widgets' ),
							'param_name' => 'frame_color',
							'std'        => '#ffffff',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'CSS', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// single listing page modules.
			// Attribute boxes in a row.
			vc_map(
				array(
					'name'     => __( 'EV Single Attribute Boxes', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_car_attribute_boxes',
					'icon'     => 'stm_ev_single_car_attribute_boxes',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Taxonomies', 'motors-wpbakery-widgets' ),
							'param_name' => 'boxes',
							'value'      => $listing_multilisting_attributes,
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Icon color', 'motors-wpbakery-widgets' ),
							'param_name' => 'icon_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Text color', 'motors-wpbakery-widgets' ),
							'param_name' => 'text_color',
							'value'      => '',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Box background color', 'motors-wpbakery-widgets' ),
							'param_name' => 'box_bg_color',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// Title with attributes.
			vc_map(
				array(
					'name'     => __( 'EV Single Title with Attributes', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_car_title_attributes',
					'icon'     => 'stm_ev_single_car_title_attributes',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Taxonomies to show below title', 'motors-wpbakery-widgets' ),
							'param_name' => 'attributes',
							'value'      => $listing_multilisting_attributes,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// all in one price block.
			vc_map(
				array(
					'name'     => __( 'EV Single Price Block', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_car_price_block',
					'icon'     => 'stm_ev_single_car_price_block',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Contact Info Anchor Link', 'motors-wpbakery-widgets' ),
							'param_name' => 'contact_info_link',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Block background color', 'motors-wpbakery-widgets' ),
							'param_name' => 'block_bg_color',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// gallery with buttons.
			vc_map(
				array(
					'name'     => __( 'EV Single Gallery with Buttons', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_car_gallery_with_buttons',
					'icon'     => 'stm_ev_single_car_gallery_with_buttons',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// selectable attributes table.
			vc_map(
				array(
					'name'     => __( 'EV Single Selectable Attributes Table', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_selectable_attributes_table',
					'icon'     => 'stm_ev_single_selectable_attributes_table',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select attributes to list', 'motors-wpbakery-widgets' ),
							'param_name' => 'attributes',
							'value'      => $listing_multilisting_attributes,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// selectable attributes table.
			vc_map(
				array(
					'name'     => __( 'EV Single Highlight Photos', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_single_highlight_photos',
					'icon'     => 'stm_ev_single_highlight_photos',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// electric mpg.
			vc_map(
				array(
					'name'     => __( 'EV MPG Electric', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_car_mpge',
					'icon'     => 'stm_ev_car_mpge',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// battery charging time.
			vc_map(
				array(
					'name'     => __( 'EV Battery Charging Time', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_battery_charging_time',
					'icon'     => 'stm_ev_battery_charging_time',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// single VC module with multi purpose link.
			vc_map(
				array(
					'name'     => __( 'EV Multipurpose Link', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_multipurpose_link',
					'icon'     => 'stm_ev_multipurpose_link',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Link function', 'motors-wpbakery-widgets' ),
							'param_name' => 'link_function',
							'value'      => array(
								__( 'Permalink', 'motors-wpbakery-widgets' )      => 'permalink',
								__( 'Open Gallery', 'motors-wpbakery-widgets' )   => 'open_gallery',
								__( 'Add to Compare', 'motors-wpbakery-widgets' ) => 'add_to_compare',
							),
							'std'        => 'permalink',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Title', 'motors-wpbakery-widgets' ),
							'param_name' => 'link_title',
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Link (href)', 'motors-wpbakery-widgets' ),
							'param_name' => 'link_href',
							'dependency' => array(
								'element' => 'link_function',
								'value'   => 'permalink',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Link target', 'motors-wpbakery-widgets' ),
							'param_name' => 'link_target',
							'value'      => array(
								__( 'On same window (tab)', 'motors-wpbakery-widgets' ) => '_self',
								__( 'On new window (tab)', 'motors-wpbakery-widgets' )  => '_blank',
							),
							'std'        => '_self',
							'dependency' => array(
								'element' => 'link_function',
								'value'   => 'permalink',
							),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __( 'Icon after link', 'motors-wpbakery-widgets' ),
							'param_name' => 'link_icon',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);

			// simple message box.
			vc_map(
				array(
					'name'     => __( 'EV Message Box', 'motors-wpbakery-widgets' ),
					'base'     => 'stm_ev_message_box',
					'icon'     => 'stm_ev_message_box',
					'category' => __( 'STM EV Dealer', 'motors-wpbakery-widgets' ),
					'params'   => array(
						array(
							'type'       => 'textarea_html',
							'heading'    => __( 'Text', 'motors-wpbakery-widgets' ),
							'param_name' => 'content',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __( 'Left border color', 'motors-wpbakery-widgets' ),
							'param_name' => 'left_border_color',
							'value'      => '',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => __( 'Css', 'motors-wpbakery-widgets' ),
							'param_name' => 'css',
							'group'      => __( 'Design options', 'motors-wpbakery-widgets' ),
						),
					),
				)
			);
		} // if layout is Electric vehicle.
	} // if !defined STM_MOTORS_CAR_RENTAL.
}

// phpcs:disable
// needed for creating container module for child modules. Example: STM Testimonials (parent) and STM Testimonial (child).
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Stm_Testimonials extends WPBakeryShortCodesContainer {
	}

	class WPBakeryShortCode_Stm_Hero_Slider extends WPBakeryShortCodesContainer {
	}

	class WPBakeryShortCode_Stm_Tech_Infos extends WPBakeryShortCodesContainer {
	}

	class WPBakeryShortCode_Stm_Image_Links extends WPBakeryShortCodesContainer {
	}

	class WPBakeryShortCode_Stm_Popular_Searches extends WPBakeryShortCodesContainer {
	}

	$current_layout = get_option( 'stm_motors_chosen_template' );

	if ( 'boats' === $current_layout ) {
		class WPBakeryShortCode_Stm_Testimonials_Boats extends WPBakeryShortCodesContainer {
		}

		class WPBakeryShortCode_Stm_Contacts_Boat extends WPBakeryShortCodesContainer {
		}
	}
}
// phpcs:enable
