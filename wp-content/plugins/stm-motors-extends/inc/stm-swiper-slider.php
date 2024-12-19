<?php
// register config for STM Swiper Slider.
add_filter(
	'wpcfto_options_page_setup',
	function ( $setups ) {
		// get listings.
		$listings = array();

		$listings_post_type = 'listings';

		if ( function_exists( 'stm_listings_multi_type' ) ) {
			$listings_post_type = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
		}

		$posts = get_posts(
			array(
				'post_type'   => $listings_post_type,
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$listings[ $post->ID ] = esc_html( $post->post_title );
			}
		}

		// get listing attributes.
		$attributes = array();

		$listing_attrs = get_option( 'stm_vehicle_listing_options', array() );

		// multilisting.
		if ( defined( 'MULTILISTING_PATH' ) && class_exists( 'STMMultiListing' ) ) {
			$opts  = array();
			$slugs = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				foreach ( $slugs as $slug ) {
					$type_options = get_option( "stm_{$slug}_options", array() );
					if ( ! empty( $type_options ) ) {
						$opts = array_merge( $opts, $type_options );
					}
				}
			}

			if ( ! empty( $opts ) ) {
				$listing_attrs = array_merge( $listing_attrs, $opts );
			}
		}

		if ( ! empty( $listing_attrs ) ) {
			foreach ( $listing_attrs as $attr ) {
				if ( ! apply_filters( 'stm_is_listing_price_field', true, $attr['slug'] ) ) {
					$attributes[ $attr['slug'] ] = esc_html( $attr['single_name'] );
				}
			}
		}

		// slide animation.
		$animation_types = array(
			'slide'     => esc_html__( 'Slide', 'stm_motors_extends' ),
			'fade'      => esc_html__( 'Fade', 'stm_motors_extends' ),
			'cube'      => esc_html__( 'Cube', 'stm_motors_extends' ),
			'coverflow' => esc_html__( 'Coverflow', 'stm_motors_extends' ),
			'flip'      => esc_html__( 'Flip', 'stm_motors_extends' ),
			'cards'     => esc_html__( 'Cards', 'stm_motors_extends' ),
		);

		$motors_favicon = false;
		$motors_thumb   = false;
		if ( defined( 'STM_THEME_NAME' ) && 'Motors' === STM_THEME_NAME ) {
			$motors_favicon = get_template_directory_uri() . '/assets/admin/images/icon.png';
			$motors_thumb   = get_template_directory_uri() . '/assets/admin/images/logo.png';
		}

		$setups[] = array(

			'option_name' => 'stm_swiper_slider',

			'title'       => esc_html__( 'STM Swiper Slider', 'stm_motors_extends' ),
			'sub_title'   => esc_html__( 'by StylemixThemes', 'stm_motors_extends' ),
			'logo'        => $motors_thumb,

			'page'        => array(
				'page_title' => esc_html__( 'STM Swiper Slider', 'stm_motors_extends' ),
				'menu_title' => esc_html__( 'Swiper Slider', 'stm_motors_extends' ),
				'menu_slug'  => 'stm-listing-slider',
				'icon'       => $motors_favicon,
				'position'   => 4,
			),

			'fields'      => array(
				'general_settings'  => array(
					'name'   => esc_html__( 'General Settings', 'stm_motors_extends' ),
					'icon'   => 'fas fa-cogs',
					'fields' => array(
						'height'        => array(
							'type'        => 'number',
							'label'       => esc_html__( 'Slider height', 'stm_motors_extends' ),
							'description' => esc_html__( 'Height of the slider in pixels', 'stm_motors_extends' ),
							'value'       => '600',
						),
						'autoplay'      => array(
							'type'        => 'checkbox',
							'label'       => esc_html__( 'Autoplay', 'stm_motors_extends' ),
							'description' => esc_html__( 'Start slider on page load', 'stm_motors_extends' ),
							'value'       => true,
						),
						'duration'      => array(
							'type'        => 'number',
							'label'       => esc_html__( 'Duration', 'stm_motors_extends' ),
							'description' => esc_html__( 'Each slide visible (milliseconds)', 'stm_motors_extends' ),
							'value'       => 3500,
							'dependency'  => array(
								'key'   => 'autoplay',
								'value' => 'not_empty',
							),
						),
						'loop'          => array(
							'type'        => 'checkbox',
							'label'       => esc_html__( 'Loop slider', 'stm_motors_extends' ),
							'description' => esc_html__( 'Start over when slides end', 'stm_motors_extends' ),
							'value'       => true,
							'dependency'  => array(
								'key'   => 'autoplay',
								'value' => 'not_empty',
							),
						),
						'animation'     => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Animation type', 'stm_motors_extends' ),
							'description' => esc_html__( 'Slide transition animation', 'stm_motors_extends' ),
							'value'       => 'simple',
							'options'     => $animation_types,
						),
						'listing_attrs' => array(
							'type'        => 'multi_checkbox',
							'label'       => esc_html__( 'Listing attributes', 'stm_motors_extends' ),
							'description' => esc_html__( 'Select listing categories to show on slides', 'stm_motors_extends' ),
							'options'     => $attributes,
						),
					),
				),
				'stm_swiper_slides' => array(
					'name'   => esc_html__( 'Swiper Slides', 'stm_motors_extends' ),
					'icon'   => 'far fa-images',
					'fields' => array(
						'stm_swiper_slides_repeater' => array(
							'type'        => 'repeater',
							'label'       => esc_html__( 'Swiper Slides', 'stm_motors_extends' ),
							'load_labels' => array(
								'add_label' => esc_html__( 'Add Slide', 'stm_motors_extends' ),
							),
							'fields'      => array(
								'background' => array(
									'type'        => 'image',
									'label'       => esc_html__( 'Background', 'stm_motors_extends' ),
									'description' => esc_html__( 'High resolution photo (1920x967px recommended)', 'stm_motors_extends' ),
								),
								'listing'    => array(
									'type'        => 'select',
									'label'       => esc_html__( 'Listing', 'stm_motors_extends' ),
									'description' => esc_html__( 'Item to show on slide', 'stm_motors_extends' ),
									'options'     => $listings,
								),
								'text'       => array(
									'type'        => 'textarea',
									'label'       => esc_html__( 'Text', 'stm_motors_extends' ),
									'description' => esc_html__( 'HTML escaped, only allows <br> tag', 'stm_motors_extends' ),
								),
							),
						),
					),
				),
			),
		);

		return $setups;
	}
);
