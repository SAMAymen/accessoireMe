<?php
if ( ! function_exists( 'stm_listings_register_manager_rental' ) ) {
	add_action( 'butterbean_register', 'stm_listings_register_manager_rental', 10, 2 );
	function stm_listings_register_manager_rental( $butterbean, $post_type ) {
		$offices = 'stm_office';
		// Register managers, sections, controls, and settings here.
		if ( $post_type !== $offices ) {
			return;
		}

		$butterbean->register_manager(
			'stm_rent_manager',
			array(
				'label'     => esc_html__( 'Office Info', 'motors' ),
				'post_type' => $offices,
				'context'   => 'normal',
				'priority'  => 'high',
			)
		);

		$manager = $butterbean->get_manager( 'stm_rent_manager' );

		/*Register sections*/
		$manager->register_section(
			'stm_info',
			array(
				'label' => esc_html__( 'Details', 'motors' ),
				'icon'  => 'fa fa-reorder',
			)
		);

		/*Register controls*/
		$manager->register_control(
			'address',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Office address', 'motors' ),
				'attr'    => array(
					'class' => 'widefat',
				),
			)
		);

		$manager->register_control(
			'latitude',
			array(
				'type'        => 'text',
				'section'     => 'stm_info',
				'label'       => esc_html__( 'Office latitude', 'motors' ),
				'description' => esc_html__( 'You can find latitude on http://www.latlong.net/', 'motors' ),
				'attr'        => array(
					'class' => 'widefat',
				),
			)
		);

		$manager->register_control(
			'longitude',
			array(
				'type'        => 'text',
				'section'     => 'stm_info',
				'label'       => esc_html__( 'Office longitude', 'motors' ),
				'description' => esc_html__( 'You can find longitude on http://www.latlong.net/', 'motors' ),
				'attr'        => array(
					'class' => 'widefat',
				),
			)
		);

		if ( apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_office_location_fee' ) ) {
			$manager->register_control(
				'office_fee',
				array(
					'type'    => 'number',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Office Fee', 'motors' ),
					'attr'    => array(
						'class' => 'widefat',
					),
				)
			);
		}

		$manager->register_control(
			'phone',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Phone', 'motors' ),
				'attr'    => array(
					'class' => 'widefat',
				),
			)
		);

		$manager->register_control(
			'fax',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Fax', 'motors' ),
				'attr'    => array(
					'class' => 'widefat',
				),
			)
		);

		$manager->register_control(
			'work_hours',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Work hours', 'motors' ),
				'attr'    => array(
					'class' => 'widefat',
				),
			)
		);

		/*Registering Setting*/
		$manager->register_setting(
			'address',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'latitude',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'longitude',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'phone',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'office_fee',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'fax',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$manager->register_setting(
			'work_hours',
			array(
				'sanitize_callback' => 'stm_listings_no_validate',
			)
		);
	}
}

if ( ! function_exists( 'stm_listings_register_manager_product' ) ) {
	add_action( 'butterbean_register', 'stm_listings_register_manager_product', 10, 2 );
	function stm_listings_register_manager_product( $butterbean, $post_type ) {

		$offices = 'product';
		// Register managers, sections, controls, and settings here.
		if ( $post_type !== $offices ) {
			return;
		}

		$butterbean->register_manager(
			'stm_product_manager',
			array(
				'label'     => esc_html__( 'Car rent Info', 'motors' ),
				'post_type' => $offices,
				'context'   => 'normal',
				'priority'  => 'high',
			)
		);

		$manager = $butterbean->get_manager( 'stm_product_manager' );

		/*Register sections*/
		$manager->register_section(
			'stm_info',
			array(
				'label' => esc_html__( 'Details', 'motors' ),
				'icon'  => 'fa fa-reorder',
			)
		);

		/*Control*/
		$manager->register_control(
			'cars_qty',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Stock quantity', 'motors' ),
				'attr'    => array( 'class' => 'widefat' ),
			)
		);

		$manager->register_control(
			'cars_info',
			array(
				'type'    => 'text',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Cars included', 'motors' ),
				'attr'    => array( 'class' => 'widefat' ),
			)
		);

		/*Settings*/
		$manager->register_setting(
			'cars_info',
			array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
		);

		$manager->register_setting(
			'cars_qty',
			array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
		);

		$manager->register_setting(
			'max_rent_days',
			array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
		);

		$manager->register_setting(
			'min_rent_days',
			array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
		);

		/*Features*/
		$options = get_option( 'stm_vehicle_listing_options' );

		if ( ! empty( $options ) ) {
			$args = array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
				'fields'     => 'all',
				'pad_counts' => false,
			);

			/*Add multiselects*/
			foreach ( $options as $key => $option ) {
				$terms = get_terms( $option['slug'], $args );

				$single_term = array(
					'' => 'None',
				);

				foreach ( $terms as $tax_key => $taxonomy ) {
					if ( ! empty( $taxonomy ) ) {
						$single_term[ $taxonomy->name ] = $taxonomy->name;
					}
				}

				if ( empty( $option['numeric'] ) ) {
					$manager->register_control(
						$option['slug'],
						array(
							'type'    => 'multiselect',
							'section' => 'stm_info',
							'label'   => $option['plural_name'],
							'choices' => $single_term,
						)
					);

					$manager->register_setting(
						$option['slug'],
						array(
							'sanitize_callback' => 'stm_listings_multiselect',
						)
					);

				}
			}

			/*Add number fields*/
			foreach ( $options as $key => $option ) {
				if ( ! empty( $option['numeric'] ) ) {
					$manager->register_control(
						$option['slug'],
						array(
							'type'    => 'text',
							'section' => 'stm_info',
							'label'   => $option['single_name'],
							'attr'    => array( 'class' => 'widefat' ),
						)
					);

					$manager->register_setting(
						$option['slug'],
						array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						)
					);
				}
			}
		}

		$manager->register_control(
			'min_rent_days',
			array(
				'type'    => 'number',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Minimum rent days', 'motors' ),
				'description' => esc_html__( 'Enter number of minimum days for rent', 'motors' ),
				'attr'    => array( 'class' => 'widefat' ),
			)
		);

		$manager->register_control(
			'max_rent_days',
			array(
				'type'    => 'number',
				'section' => 'stm_info',
				'label'   => esc_html__( 'Maximum rent days', 'motors' ),
				'description' => esc_html__( 'Enter number of maximum days for rent', 'motors' ),
				'attr'    => array( 'class' => 'widefat' ),
			)
		);

		do_action( 'stm_add_rental_offices', $manager );
	}
}

if ( ! function_exists( 'stm_listings_register_manager_order' ) ) {
	add_action( 'butterbean_register', 'stm_listings_register_manager_order', 10, 2 );
	function stm_listings_register_manager_order( $butterbean, $post_type ) {
		$offices = 'shop_order';
		// Register managers, sections, controls, and settings here.
		if ( $post_type !== $offices ) {
			return;
		}

		if ( apply_filters( 'stm_is_rental', false ) ) {
			$butterbean->register_manager(
				'stm_order_manager',
				array(
					'label'     => esc_html__( 'Car rent Info', 'motors' ),
					'post_type' => $offices,
					'context'   => 'normal',
					'priority'  => 'high',
				)
			);

			$manager = $butterbean->get_manager( 'stm_order_manager' );

			/*Register sections*/
			$manager->register_section(
				'stm_info',
				array(
					'label' => esc_html__( 'Details', 'motors' ),
					'icon'  => 'fa fa-reorder',
				)
			);

			/*Control*/
			$manager->register_control(
				'order_car',
				array(
					'type'    => 'hidden',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Car ordered', 'motors' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);

			$manager->register_control(
				'order_car_date',
				array(
					'type'    => 'hidden',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Options ordered', 'motors' ),
					'attr'    => array( 'class' => 'widefat' ),
				)
			);

			$manager->register_control(
				'order_pickup_date',
				array(
					'type'    => 'text',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Pickup Date', 'motors' ),
					'attr'    => array(
						'class'    => 'widefat',
						'readonly' => 'readonly',
					),
				)
			);

			$manager->register_control(
				'order_pickup_location',
				array(
					'type'    => 'text',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Pickup location', 'motors' ),
					'attr'    => array(
						'class'    => 'widefat',
						'readonly' => 'readonly',
					),
				)
			);

			$manager->register_control(
				'order_drop_date',
				array(
					'type'    => 'text',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Return date', 'motors' ),
					'attr'    => array(
						'class'    => 'widefat',
						'readonly' => 'readonly',
					),
				)
			);

			$manager->register_control(
				'order_drop_location',
				array(
					'type'    => 'text',
					'section' => 'stm_info',
					'label'   => esc_html__( 'Return location', 'motors' ),
					'attr'    => array(
						'class'    => 'widefat',
						'readonly' => 'readonly',
					),
				)
			);

			/*Settings*/
			$manager->register_setting(
				'order_car',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);

			$manager->register_setting(
				'order_car_date',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);

			$manager->register_setting(
				'order_pickup_date',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);

			$manager->register_setting(
				'order_pickup_location',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);

			$manager->register_setting(
				'order_drop_date',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);

			$manager->register_setting(
				'order_drop_location',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
			);
		}
	}
}

add_filter(
	'butterbean_to_json_filter',
	function ( $json, $post_id ) {
		if ( apply_filters( 'stm_get_wpml_product_parent_id', $post_id ) !== $post_id && 'auto-draft' !== get_post_status() ) {
			if ( 'cars_qty' === $json['name'] ) {
				$json['label'] .= ' (This field are not editable.)';
				$json['attr']  .= sprintf( '%s="%s" ', 'disabled', 'disabled' );
				$carsQty        = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', 'product', $post_id ), 'cars_qty', true );
				$json['value']  = $carsQty;
				$json['attr']  .= sprintf( '%s="%s" ', 'disabled', 'disabled' );
			}

			if ( 'cars_info' !== $json['name'] && 'address' !== $json['name'] ) {
				$json['value'] = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', $post_id ), $json['name'], true );
			}

			if ( 'stm_rental_office' === $json['name'] ) {
				$json['label'] .= ' (This field are not editable.)';
				$json['attr']  .= sprintf( '%s="%s" ', 'disabled', 'disabled' );
			}

			if ( 'multiselect' === $json['type'] && 'stm_rental_office' !== $json['name'] ) {
				$json['value'] = get_post_meta( apply_filters( 'stm_motors_wpml_binding', $post_id, 'product' ), $json['name'], true );
			}

			if ( ! apply_filters( 'stm_get_wpml_product_parent_id', $post_id ) ) {
				$json['value'] = isset( $json->settings[ $json->setting ] ) ? $json->get_value( $json->setting ) : '';
			}

			update_post_meta( $post_id, $json['name'], $json['value'] );
		}

		return $json;
	},
	10,
	2
);

add_filter(
	'butterbean_multiselect_to_json_filter',
	function ( $value, $post_id, $field_name ) {
		if ( 'stm_rental_office' === $field_name ) {
			$selectedOffices = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', $post_id ), 'stm_rental_office', true );
			$value           = $selectedOffices;
		}

		return $value;
	},
	10,
	3
);

add_action( 'stm_create_butterbean_meta', 'stm_create_butterbean_meta', 10, 3 );

function stm_create_butterbean_meta( $id, $manager, $new_value ) {
	if ( 'stm_rental_office' === $manager ) {
		$offices_ids = array_map( 'intval', explode( ',', $new_value ) );
		update_post_meta( $id, 'offices', $offices_ids );
	}
}
