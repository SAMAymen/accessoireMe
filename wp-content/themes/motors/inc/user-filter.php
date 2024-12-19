<?php

/*Sort by popularity*/
function stm_sort_reviews_dealers( $a, $b ) {
	return $b['ratings']['count'] - $a['ratings']['count'];
}

/*Sort by distance*/
function stm_sort_distance_dealers( $a, $b ) {
	return floatval( $a['fields']['distance'] ) - floatval( $b['fields']['distance'] );
}

function stm_sort_cars_dealers( $a, $b ) {
	return floatval( $b['cars_count'] ) - floatval( $a['cars_count'] );
}

function stm_sort_watches_dealers( $a, $b ) {
	return floatval( $b['car_views'] ) - floatval( $a['car_views'] );
}

function stm_sort_date_dealers( $a, $b ) {
	$t1 = strtotime( $a['registered'] );
	$t2 = strtotime( $b['registered'] );
	return $t1 - $t2;
}


if ( ! function_exists( 'stm_get_filtered_dealers' ) ) {
	function stm_get_filtered_dealers( $dealer_data = array(), $offset = 0, $per_page = 12 ) {

		$offset   = intval( $offset );
		$per_page = intval( $per_page );

		$title = esc_html__( 'Displaying Local Car Dealerships', 'motors' );

		/*Get only dealers*/
		$user_args = array(
			'role'   => 'stm_dealer',
			'fields' => 'all',
		);

		if ( ! empty( $_GET['dealer_keyword'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$user_args['meta_query'][] = array(
				'meta_key' => 'stm_company_name',
				'value'    => sanitize_text_field( $_GET['dealer_keyword'] ), //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'compare'  => 'LIKE',
			);
		}

		if ( isset( $_GET['stm_sort_by'] ) && 'alphabet' === $_GET['stm_sort_by'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$user_args['order']    = 'ASC';
			$user_args['meta_key'] = 'stm_company_name';
			$user_args['orderby']  = 'meta_value';

		}

		$user_query = new WP_User_Query( $user_args );
		$user_query = $user_query->get_results();

		/*Get cars from get: model, etc*/
		$filter_accept = array();
		$users_cars    = array();

		if ( ! empty( $_GET ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
			foreach ( $_GET as $tax => $term ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( term_exists( sanitize_title( $term ), sanitize_title( $tax ) ) ) {
					$filter_accept[ sanitize_title( $tax ) ] = sanitize_title( $term );
				}
			}
		}

		if ( ! empty( $filter_accept ) ) {
			$car_args_tax = array(
				'relation' => 'AND',
			);
			foreach ( $filter_accept as $filter_tax => $filter_term ) {
				$car_args_tax[] = array(
					'taxonomy' => $filter_tax,
					'field'    => 'slug',
					'terms'    => array( $filter_term ),
				);
			}

			$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

			$car_args = array(
				'post_type'      => $post_types,
				'posts_per_page' => '-1',
				'tax_query'      => $car_args_tax,
			);

			$cars = new WP_Query( $car_args );

			if ( $cars->have_posts() ) {
				while ( $cars->have_posts() ) {
					$cars->the_post();
					$stm_car_user = get_post_meta( get_the_ID(), 'stm_car_user', true );
					$users_cars[] = (int) $stm_car_user;
				}
				wp_reset_postdata();
			}
		}

		$users_cars = array_unique( $users_cars );

		$user_list = array();

		/*Generate output array*/
		if ( ! empty( $user_query ) ) {
			foreach ( $user_query as $user ) {
				$user_id = $user->ID;
				if ( ! empty( $user_id ) ) {
					if ( ! empty( $filter_accept ) ) {
						if ( in_array( $user_id, $users_cars, true ) ) {
							$user_data = get_userdata( $user_id );
							if ( ! empty( $user_data->data->user_registered ) ) {
								$user_list[ $user_id ] ['registered'] = $user_data->data->user_registered;
							}

							$dealer_cars = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id ) : null;

							/*Get views*/
							$total = 0;
							if ( null !== $dealer_cars && $dealer_cars->have_posts() ) {
								while ( $dealer_cars->have_posts() ) {
									$dealer_cars->the_post();
									$views = get_post_meta( get_the_id(), 'stm_car_views', true );
									if ( ! empty( $views ) ) {
										$total += $views;
									}
								}
								wp_reset_postdata();
							}

							$user_list[ $user_id ]['car_views'] = $total;

							$user_list[ $user_id ]['id']         = $user_id;
							$user_list[ $user_id ]['cars']       = ( function_exists( 'stm_user_listings_query' ) ) ? $dealer_cars : array();
							$user_list[ $user_id ]['cars_count'] = ( function_exists( 'stm_user_listings_query' ) ) ? $dealer_cars->found_posts : 0;
							$user_list[ $user_id ]['fields']     = apply_filters( 'stm_get_user_custom_fields', $user_id );
							$user_list[ $user_id ]['ratings']    = stm_get_dealer_marks( $user_id );

							/*Add distance away*/
							if ( ! empty( $_GET['ca_location'] ) && ! empty( $_GET['stm_lng'] ) && ! empty( $_GET['stm_lat'] ) && ! empty( $user_list[ $user_id ]['fields']['location_lat'] ) && ! empty( $user_list[ $user_id ]['fields']['location_lng'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$distance                                    = apply_filters( 'stm_calculate_distance_between_two_points', '', floatval( $_GET['stm_lat'] ), floatval( floatval( $_GET['stm_lng'] ) ), $user_list[ $user_id ]['fields']['location_lat'], $user_list[ $user_id ]['fields']['location_lng'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$user_list[ $user_id ]['fields']['distance'] = $distance;
								$current_location                            = explode( ',', sanitize_text_field( $_GET['ca_location'] ) );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$current_location                            = $current_location[0];
								$user_list[ $user_id ]['fields']['user_location'] = $current_location;
							}
						}
					} else {
						$user_data = get_userdata( $user_id );
						if ( ! empty( $user_data->data->user_registered ) ) {
							$user_list[ $user_id ] ['registered'] = $user_data->data->user_registered;
						}

						$dealer_cars = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id ) : null;

						/*Get views*/
						$total = 0;
						if ( $dealer_cars != null && $dealer_cars->have_posts() ) {//phpcs:ignore
							while ( $dealer_cars->have_posts() ) {
								$dealer_cars->the_post();
								$views = get_post_meta( get_the_id(), 'stm_car_views', true );
								if ( ! empty( $views ) ) {
									$total += $views;
								}
							}
							wp_reset_postdata();
						}

						$user_list[ $user_id ]['car_views']  = $total;
						$user_list[ $user_id ]['id']         = $user_id;
						$user_list[ $user_id ]['cars']       = ( function_exists( 'stm_user_listings_query' ) ) ? $dealer_cars : array();
						$user_list[ $user_id ]['cars_count'] = ( function_exists( 'stm_user_listings_query' ) ) ? $dealer_cars->found_posts : 0;
						$user_list[ $user_id ]['fields']     = apply_filters( 'stm_get_user_custom_fields', $user_id );
						$user_list[ $user_id ]['ratings']    = stm_get_dealer_marks( $user_id );

						/*Add distance away*/
						if ( ! empty( $_GET['ca_location'] ) && ! empty( $_GET['stm_lng'] ) && ! empty( $_GET['stm_lat'] ) && ! empty( $user_list[ $user_id ]['fields']['location_lat'] ) && ! empty( $user_list[ $user_id ]['fields']['location_lng'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$distance                                    = apply_filters( 'stm_calculate_distance_between_two_points', '', floatval( $_GET['stm_lat'] ), floatval( floatval( $_GET['stm_lng'] ) ), $user_list[ $user_id ]['fields']['location_lat'], $user_list[ $user_id ]['fields']['location_lng'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$user_list[ $user_id ]['fields']['distance'] = $distance;
							$current_location                            = explode( ',', sanitize_text_field( $_GET['ca_location'] ) );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$current_location                            = $current_location[0];
							$user_list[ $user_id ]['fields']['user_location'] = $current_location;
						}
					}
				}
			}
		}

		$location_pretty = '';
		if ( ! empty( $_GET['ca_location'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$location_pretty = explode( ',', $_GET['ca_location'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $location_pretty[0] ) ) {
				$location_pretty = $location_pretty[0];
			}
		}

		/*Sort by popularity*/
		if ( ! empty( $_GET['ca_location'] ) && ! empty( $_GET['stm_lng'] ) && ! empty( $_GET['stm_lat'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			usort( $user_list, 'stm_sort_distance_dealers' );
			$title = esc_html__( 'Displaying Dealerships near', 'motors' ) . ' <span class="green">' . $location_pretty . '</span>';
		} else {
			if ( ! empty( $_GET['stm_sort_by'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$sort_type = sanitize_title( $_GET['stm_sort_by'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( function_exists( 'stm_sort_' . $sort_type . '_dealers' ) ) {
					usort( $user_list, 'stm_sort_' . $sort_type . '_dealers' );
				}
			} else {
				usort( $user_list, 'stm_sort_reviews_dealers' );
			}
		}

		if ( ! empty( $filter_accept ) ) {
			$i = 0;
			foreach ( $filter_accept as $filter_tax => $filter_term ) {
				$i++;
				if ( 1 === $i ) {
					$name = get_term_by( 'slug', $filter_term, $filter_tax );
					if ( ! empty( $name->name ) ) {
						if ( ! empty( $_GET['ca_location'] ) && ! empty( $_GET['stm_lng'] ) && ! empty( $_GET['stm_lat'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$title = esc_html__( 'Displaying', 'motors' ) . ' <span class="green">' . sanitize_text_field( $name->name ) . '</span> ' . esc_html__( 'Dealerships near', 'motors' ) . ' <span class="green">' . $location_pretty . '</span>';

						} else {
							$title = esc_html__( 'Displaying', 'motors' ) . ' <span class="green">' . sanitize_text_field( $name->name ) . '</span> ' . esc_html__( 'Dealerships', 'motors' );
						}
					}
				}
			}
		}

		$output = array_slice( $user_list, $offset, $per_page );
		$button = 'hide';

		if ( intval( count( $user_list ) ) > intval( ( $offset + $per_page ) ) ) {
			$button = 'show';
		}

		$dealer_data = array(
			'user_list' => $output,
			'title'     => $title,
			'button'    => $button,
		);

		return $dealer_data;
	}

	add_filter( 'stm_get_filtered_dealers', 'stm_get_filtered_dealers', 10, 3 );
}
