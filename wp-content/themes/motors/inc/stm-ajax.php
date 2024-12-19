<?php

// Ajax filter cars remove unfiltered cars
function stm_ajax_filter_remove_hidden() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$stm_listing_filter = stm_get_filter();

	$response            = array();
	$response['binding'] = $stm_listing_filter['binding'];
	$response['length']  = count( $stm_listing_filter['posts'] );

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );
add_action( 'wp_ajax_nopriv_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );

// Ajax add to compare
function stm_ajax_add_to_compare() {

	check_ajax_referer( 'stm_security_nonce', 'security' );

	if ( empty( $_POST['post_id'] ) ) {
		return false;
	}

	$response['response']    = '';
	$response['status']      = '';
	$response['empty']       = '';
	$response['empty_table'] = '';
	$response['add_to_text'] = esc_html__( 'Add to compare', 'motors' );
	$response['in_com_text'] = esc_html__( 'In compare list', 'motors' );
	$response['remove_text'] = esc_html__( 'Remove from list', 'motors' );

	$post_id        = intval( filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT ) );
	$post_title     = get_the_title( $post_id );
	$post_type      = get_post_type( $post_id );
	$compared_items = apply_filters( 'stm_get_compared_items', array(), $post_type );

	if ( ! empty( $_POST['post_action'] ) && 'remove' === $_POST['post_action'] ) {
		do_action( 'stm_remove_compared_item', $post_id );
		$response['status']   = 'success';
		$response['response'] = sprintf( esc_html__( '%s has been removed from compare', 'motors' ), $post_title );
	} else {
		if ( ! in_array( $post_id, $compared_items, true ) ) {
			if ( count( $compared_items ) < 3 ) {
				stm_set_compared_item( $post_id );
				$response['status']   = 'success';
				$response['response'] = sprintf( esc_html__( '%s has been added to compare!', 'motors' ), $post_title );
			} else {
				$response['status']   = 'danger';
				$response['response'] = sprintf( esc_html__( 'You have already added %d cars', 'motors' ), count( $compared_items ) );
			}
		} else {
			$response['status']   = 'warning';
			$response['response'] = sprintf( esc_html__( '%s has already been added', 'motors' ), $post_title );
		}
	}

	$all_compared_items = apply_filters( 'stm_get_compared_items', array() );
	$response['length'] = count( $all_compared_items );
	$response['ids']    = $all_compared_items;

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );

// Load more cars
function stm_ajax_load_more_cars() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response             = array();
	$response['button']   = '';
	$response['content']  = '';
	$response['appendTo'] = '#car-listing-category-' . sanitize_text_field( $_POST['category'] );
	$category             = sanitize_text_field( $_POST['category'] );
	$taxonomy             = sanitize_text_field( $_POST['taxonomy'] );
	$offset               = intval( filter_var( $_POST['offset'], FILTER_SANITIZE_NUMBER_INT ) );
	$per_page             = intval( filter_var( $_POST['per_page'], FILTER_SANITIZE_NUMBER_INT ) );
	$new_offset           = $offset + $per_page;
	$random_int           = intval( filter_var( $_POST['random_int'], FILTER_SANITIZE_NUMBER_INT ) );

	$args                = array(
		'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
		'post_status'    => 'publish',
		'offset'         => $offset,
		'posts_per_page' => $per_page,
	);
	$args['tax_query'][] = array(
		'taxonomy' => $taxonomy,
		'field'    => 'slug',
		'terms'    => array( $category ),
	);
	$listings            = new WP_Query( $args );
	if ( $listings->have_posts() ) {
		ob_start();
		while ( $listings->have_posts() ) {
			$listings->the_post();
			get_template_part( 'partials/car-filter', 'loop' );
		}
		$response['content'] = ob_get_contents();
		ob_end_clean();

		if ( $listings->found_posts > $new_offset ) {
			$response['button'] = 'stm_loadMoreCars(jQuery(this),\'' . esc_js( $category ) . '\',\'' . esc_js( $taxonomy ) . '\',' . esc_js( $new_offset ) . ', ' . esc_js( $per_page ) . ', \'' . esc_js( $random_int ) . '\'); return false;';

		} else {
			$response['button'] = '';
		}

		$response['test'] = $listings->found_posts . ' > ' . $new_offset;

		wp_reset_postdata();
	}

	echo wp_json_encode( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );


function stm_ajax_dealer_load_cars() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response = array();
	$user_id  = intval( filter_var( $_POST['user_id'], FILTER_SANITIZE_NUMBER_INT ) );
	$offset   = intval( filter_var( $_POST['offset'], FILTER_SANITIZE_NUMBER_INT ) );
	$popular  = false;

	$view_type = 'grid';
	if ( ! empty( $_POST['view_type'] ) && 'list' === $_POST['view_type'] ) {
		$view_type = 'list';
	}

	if ( ! empty( $_POST['popular'] ) && 'yes' === $_POST['popular'] ) {
		$popular = true;
	}

	$response['offset'] = $offset;

	$new_offset = 3 + $offset;

	$query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, 'publish', 3, $popular, $offset ) : null;

	$html = '';
	if ( ! empty( $query ) && $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( 'grid' === $view_type ) {
				get_template_part( 'partials/listing-cars/listing-grid-directory-loop', 'animate' );
			} else {
				get_template_part( 'partials/listing-cars/listing-list-directory-loop', 'animate' );
			}
		}
		$html = ob_get_clean();
	}

	$response['html'] = $html;

	$button = 'show';
	if ( $query->found_posts <= $new_offset ) {
		$button = 'hide';
	} else {
		$response['new_offset'] = $new_offset;
	}

	$response['button'] = $button;

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );

function stm_ajax_dealer_load_listings_by_type() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response       = array();
	$user_id        = intval( filter_var( $_POST['user_id'], FILTER_SANITIZE_NUMBER_INT ) );
	$listing_type   = ! empty( $_POST['listing_type'] ) ? sanitize_text_field( $_POST['listing_type'] ) : 'listings';
	$user_public    = ! empty( $_POST['user_public'] ) ? sanitize_text_field( $_POST['user_public'] ) : '';
	$user_private   = ! empty( $_POST['user_private'] ) ? sanitize_text_field( $_POST['user_private'] ) : '';
	$user_favourite = ! empty( $_POST['user_favourite'] ) ? sanitize_text_field( $_POST['user_favourite'] ) : '';
	$popular        = ! empty( $_POST['popular'] ) && 'yes' === sanitize_text_field( $_POST['popular'] );
	$favourites     = $user_favourite ? get_the_author_meta( 'stm_user_favourites', $user_id ) : null;
	$view_type      = ! empty( $_POST['view_type'] ) && 'list' === sanitize_text_field( $_POST['view_type'] ) ? 'list' : 'grid';

	$status   = $user_private || $user_favourite ? 'any' : 'publish';
	$per_page = 'all' !== $listing_type || $user_private || $user_public ? - 1 : 6;
	$get_all  = - 1 === $per_page;

	$query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, $status, $per_page, $popular, 0, false, $get_all, $listing_type, $favourites ) : null;

	$html = '';
	if ( ! empty( $query ) && $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( $user_favourite ) {
				$template_fav = 'grid' === $view_type ? 'listing-grid-directory-loop' : 'listing-list-directory-loop-fav';
				get_template_part( 'partials/listing-cars/' . $template_fav );
			} elseif ( $user_public ) {
				get_template_part( 'partials/listing-cars/listing-list-directory', 'loop' );
			} elseif ( $user_private ) {
				get_template_part( 'partials/listing-cars/listing-list-directory-edit', 'loop' );
			} else {
				$template_dealer = 'grid' === $view_type ? 'listing-grid-directory-loop' : 'listing-list-directory-loop';
				get_template_part( 'partials/listing-cars/' . $template_dealer, 'animate' );
			}
		}
		$html = ob_get_clean();
	}

	$response['html']   = $html;
	$response['button'] = 'all' !== $listing_type ? 'hide' : 'show';

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_listings_by_type', 'stm_ajax_dealer_load_listings_by_type' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_listings_by_type', 'stm_ajax_dealer_load_listings_by_type' );

function stm_ajax_dealer_load_reviews() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response = array();
	$user_id  = intval( filter_var( $_POST['user_id'], FILTER_SANITIZE_NUMBER_INT ) );
	$offset   = intval( filter_var( $_POST['offset'], FILTER_SANITIZE_NUMBER_INT ) );

	$response['offset'] = $offset;

	$new_offset = 6 + $offset;

	$query = stm_get_dealer_reviews( $user_id, 'publish', 6, $offset );

	$html = '';
	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'partials/user/dealer-single', 'review' );
		}
		$html = ob_get_clean();
	}

	$response['html'] = $html;

	$button = 'show';
	if ( $query->found_posts <= $new_offset ) {
		$button = 'hide';
	} else {
		$response['new_offset'] = $new_offset;
	}

	$response['button'] = $button;

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );


if ( ! function_exists( 'stm_submit_review' ) ) {
	function stm_submit_review() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response            = array();
		$response['message'] = '';
		$error               = false;
		$user_id             = 0;

		$demo = apply_filters( 'stm_site_demo_mode', false );

		if ( $demo ) {
			$error               = true;
			$response['message'] = esc_html__( 'Site is on demo mode.', 'motors' );
		}

		/*Post parts*/
		$title     = '';
		$content   = '';
		$recommend = 'yes';
		$ratings   = array();

		if ( ! empty( $_GET['stm_title'] ) ) {
			$title = sanitize_text_field( $_GET['stm_title'] );
		} else {
			$error               = true;
			$response['message'] = esc_html__( 'Please, enter review title.', 'motors' );
		}

		if ( empty( $_GET['stm_user_on'] ) ) {
			$error               = true;
			$response['message'] = esc_html__( 'Do not cheat!', 'motors' );
		} else {
			$user_on = intval( $_GET['stm_user_on'] );
		}

		if ( ! empty( $_GET['stm_content'] ) ) {
			$content = sanitize_text_field( $_GET['stm_content'] );
		} else {
			$error               = true;
			$response['message'] = esc_html__( 'Please, enter review text.', 'motors' );
		}

		if ( empty( $_GET['stm_required'] ) ) {
			$error               = true;
			$response['message'] = esc_html__( 'Please, check you are not a dealer.', 'motors' );
		} else {
			if ( 'on' !== $_GET['stm_required'] ) {
				$error               = true;
				$response['message'] = esc_html__( 'Please, check you are not a dealer.', 'motors' );
			}
		}

		if ( ! empty( $_GET['recommend'] ) && 'no' === $_GET['recommend'] ) {
			$recommend = 'no';
		}

		foreach ( $_GET as $get_key => $get_value ) {
			if ( strpos( $get_key, 'stm_rate' ) !== false ) {
				if ( empty( $get_value ) ) {
					$error               = true;
					$response['message'] = esc_html__( 'Please add rating', 'motors' );
				} else {
					if ( $get_value < 6 && $get_value > 0 ) {
						$ratings[ esc_attr( $get_key ) ] = intval( $get_value );
					}
				}
			}
		}

		/*Check if user already added comment*/
		$current_user = wp_get_current_user();
		if ( is_wp_error( $current_user ) ) {
			$error               = true;
			$response['message'] = esc_html__( 'You are not logged in', 'motors' );
		} else {
			if ( ! empty( $user_on ) ) {
				$user_id          = $current_user->ID;
				$get_user_reviews = stm_get_user_reviews( $user_id, $user_on );

				$response['q'] = $get_user_reviews;

				if ( ! empty( $get_user_reviews->posts ) ) {
					foreach ( $get_user_reviews->posts as $user_post ) {
						wp_delete_post( $user_post->ID, true );
					}
				}
			} else {
				$error               = true;
				$response['message'] = esc_html__( 'Do not cheat', 'motors' );
			}
		}

		if ( ! $error ) {

			if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_review_moderation' ) ) {
				$post_status = 'pending';
			} else {
				$post_status = 'publish';
			}

			$post_data = array(
				'post_type'    => 'dealer_review',
				'post_title'   => sanitize_text_field( $title ),
				'post_content' => sanitize_text_field( $content ),
				'post_status'  => $post_status,
			);

			$insert_post = wp_insert_post( $post_data, true );

			if ( is_wp_error( $insert_post ) ) {
				$response['message'] = $insert_post->get_error_message();
			} else {

				/*Ratings*/
				if ( ! empty( $ratings['stm_rate_1'] ) ) {
					update_post_meta( $insert_post, 'stm_rate_1', intval( $ratings['stm_rate_1'] ) );
				}
				if ( ! empty( $ratings['stm_rate_2'] ) ) {
					update_post_meta( $insert_post, 'stm_rate_2', intval( $ratings['stm_rate_2'] ) );
				}
				if ( ! empty( $ratings['stm_rate_3'] ) ) {
					update_post_meta( $insert_post, 'stm_rate_3', intval( $ratings['stm_rate_3'] ) );
				}

				/*Recommended*/
				update_post_meta( $insert_post, 'stm_recommended', $recommend );

				update_post_meta( $insert_post, 'stm_review_added_by', $user_id );
				update_post_meta( $insert_post, 'stm_review_added_on', $user_on );

				$response['updated'] = apply_filters( 'stm_get_author_link', $user_on );
			}
		}

		wp_send_json( $response );
		exit;
	}
}

add_action( 'wp_ajax_stm_submit_review', 'stm_submit_review' );
add_action( 'wp_ajax_nopriv_stm_submit_review', 'stm_submit_review' );

// Ajax filter cars remove unfiltered cars
function stm_restore_password() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response = array();

	$errors = array();

	if ( empty( $_POST['stm_user_login'] ) ) {
		$errors['stm_user_login'] = true;
	} else {
		$username = sanitize_text_field( $_POST['stm_user_login'] );
	}

	$stm_link_send_to = '';

	if ( ! empty( $_POST['stm_link_send_to'] ) ) {
		$stm_link_send_to = sanitize_text_field( $_POST['stm_link_send_to'] );
	}

	$login_page = apply_filters( 'motors_vl_get_nuxy_mod', 1718, 'login_page' );
	$page_link  = get_permalink( $login_page );
	if ( $page_link ) {
		$stm_link_send_to = $page_link;
	}

	$demo = apply_filters( 'stm_site_demo_mode', false );

	if ( $demo ) {
		$errors['demo'] = true;
	}

	if ( empty( $errors ) ) {
		if ( filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
			$user = get_user_by( 'email', $username );
		} else {
			$user = get_user_by( 'login', $username );
		}

		if ( ! $user ) {
			$response['message'] = esc_html__( 'User not found', 'motors' );
		} else {

			$hash    = apply_filters( 'stm_media_random_affix', 20 );
			$user_id = $user->ID;

			$stm_link_send_to = add_query_arg(
				array(
					'user_id'    => $user_id,
					'hash_check' => $hash,
				),
				$stm_link_send_to
			);

			update_user_meta( $user_id, 'stm_lost_password_hash', $hash );

			/*Sending mail*/
			$to = $user->data->user_email;

			$args = array(
				'password_content' => $stm_link_send_to,
			);

			$subject = apply_filters( 'get_generate_subject_view', '', 'password_recovery', $args );
			$body    = apply_filters( 'get_generate_template_view', '', 'password_recovery', $args );

			do_action( 'stm_wp_mail', $to, $subject, $body, '' );

			$response['message'] = esc_html__( 'Instructions send on your email', 'motors' );
		}
	} else {
		if ( $demo ) {
			$response['message'] = esc_html__( 'Site is on demo mode.', 'motors' );
		} else {
			$response['message'] = esc_html__( 'Please fill required fields', 'motors' );
		}
	}

	$response['errors'] = $errors;

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_restore_password', 'stm_restore_password' );
add_action( 'wp_ajax_nopriv_stm_restore_password', 'stm_restore_password' );

if ( ! function_exists( 'stm_report_review' ) ) {
	function stm_report_review() {

		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array();

		if ( ! empty( $_POST['id'] ) ) {
			$report_id = intval( filter_var( $_POST['id'], FILTER_SANITIZE_NUMBER_INT ) );

			$user_added_on = get_post_meta( $report_id, 'stm_review_added_on', true );
			if ( ! empty( $user_added_on ) ) {
				$user_added_on = get_user_by( 'id', $user_added_on );
			}

			$title = get_the_title( $report_id );

			if ( ! empty( $title ) && ! empty( $user_added_on ) ) {

				/*Sending mail */
				$to   = array();
				$to[] = get_bloginfo( 'admin_email' );
				$to[] = $user_added_on->data->user_email;

				$args = array(
					'report_id'      => $report_id,
					'review_content' => get_post_field( 'post_content', $report_id ),
				);

				$subject = apply_filters( 'get_generate_subject_view', '', 'report_review', $args );
				$body    = apply_filters( 'get_generate_template_view', '', 'report_review', $args );

				do_action( 'stm_wp_mail', $to, $subject, $body, '' );

				$response['message'] = esc_html__( 'Reported', 'motors' );

			}
		}

		wp_send_json( $response );
		exit;
	}
}

add_action( 'wp_ajax_stm_report_review', 'stm_report_review' );
add_action( 'wp_ajax_nopriv_stm_report_review', 'stm_report_review' );

function stm_load_dealers_list() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$response = array();

	$per_page = 12;

	$remove_button = '';
	$new_offset    = 0;

	if ( ! empty( $_GET['offset'] ) ) {
		$offset = intval( $_GET['offset'] );
	}

	if ( ! empty( $offset ) ) {
		$dealers = \MotorsVehiclesListing\User\UserController::get_dealers_data( array(), $offset, $per_page );
		if ( 'show' === $dealers['button'] ) {
			$new_offset = $offset + $per_page;
		} else {
			$remove_button = 'hide';
		}
		if ( ! empty( $dealers['users'] ) ) {
			ob_start();
			$user_list = $dealers['users'];
			if ( ! empty( $user_list ) ) {
				foreach ( $user_list as $user ) {
					apply_filters( 'stm_get_single_dealer', '', $user );
				}
			}
			$response['user_html'] = ob_get_clean();
		}
	}

	$response['remove']     = $remove_button;
	$response['new_offset'] = $new_offset;

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_load_dealers_list', 'stm_load_dealers_list' );
add_action( 'wp_ajax_nopriv_stm_load_dealers_list', 'stm_load_dealers_list' );

function stm_get_wpb_def_tmpl() {

	$temp_class = ( class_exists( 'Vc_Setting_Post_Type_Default_Template_Field' ) ) ? new Vc_Setting_Post_Type_Default_Template_Field( 'general', 'default_template_post_type' ) : false;

	if ( $temp_class ) {
		return $temp_class->getTemplateByPostType( 'listings' );
	}

	return false;
}

add_filter( 'stm_get_wpb_def_tmpl', 'stm_get_wpb_def_tmpl' );

// ADD A CAR
function stm_add_a_car() {
	$response['message'] = '';
	$error               = false;

	$demo = stm_is_site_demo_mode();

	if ( $demo ) {
		$error               = true;
		$response['message'] = esc_html__( 'Site is on demo mode', 'motors' );
		wp_send_json( $response );
	}

	check_ajax_referer( 'stm_security_nonce', 'security', false );

	$response     = array();
	$first_step   = array(); // needed fields
	$second_step  = array(); // secondary fields
	$car_features = array(); // array of features car
	$videos       = array(); /*videos links*/
	$notes        = esc_html__( 'N/A', 'motors' );
	$registered   = '';
	$vin          = '';

	$history = array(
		'label' => '',
		'link'  => '',
	);

	$location = array(
		'label'   => '',
		'lat'     => '',
		'lng'     => '',
		'address' => '',
	);

	if ( ! is_user_logged_in() ) {
		$response['message'] = esc_html__( 'Please, log in', 'motors' );
		wp_send_json( $response );
	} else {
		$user         = stm_get_user_custom_fields( '' );
		$restrictions = apply_filters(
			'stm_get_post_limits',
			array(
				'premoderation' => true,
				'posts_allowed' => 0,
				'posts'         => 0,
				'images'        => 0,
				'role'          => 'user',
			),
			$user['user_id']
		);
	}

	$update = false;
	if ( ! empty( $_POST['stm_current_car_id'] ) ) {
		$post_id  = intval( filter_var( $_POST['stm_current_car_id'], FILTER_SANITIZE_NUMBER_INT ) );
		$car_user = get_post_meta( $post_id, 'stm_car_user', true );
		$update   = true;

		/*Check if current user edits his car*/
		if ( intval( $car_user ) !== intval( $user['user_id'] ) ) {
			wp_die();
		}
	}

	/*Get first step*/
	$first_empty = '';
	if ( ! empty( $_POST['stm_f_s'] ) ) {
		foreach ( $_POST['stm_f_s'] as $post_key => $post_value ) {
			$post_value   = sanitize_text_field( urldecode( $post_value ) );
			$replaced_key = str_replace( '_pre_', '-', $post_key );
			if ( ! empty( $post_value ) ) {
				$first_step[ sanitize_title( $replaced_key ) ] = $post_value;
			} else {
				if ( empty( $first_empty ) ) {
					$first_empty = sanitize_title( $replaced_key );
				}

				$error               = true;
				$response['message'] = sprintf(
				/* translators: %s name field */
					esc_html__( 'Enter required %s field', 'motors' ),
					strtoupper( $first_empty )
				);
			}
		}
	}

	if ( empty( $first_step ) ) {
		$error               = true;
		$response['message'] = sprintf(
		/* translators: %s name field */
			esc_html__( 'Enter required %s field', 'motors' ),
			strtoupper( $first_empty )
		);
	}

	/*Getting second step*/
	foreach ( $_POST as $second_step_key => $second_step_value ) {
		if ( strpos( $second_step_key, 'stm_s_s_' ) !== false ) {
			if ( ! apply_filters( 'is_empty_value', $second_step_value ) && '' !== $second_step_value ) {
				$original_key                                   = str_replace( 'stm_s_s_', '', $second_step_key );
				$second_step[ sanitize_title( $original_key ) ] = sanitize_text_field( urldecode( $second_step_value ) );
			}
		}
	}

	/*Getting car features*/
	if ( ! empty( $_POST['stm_car_features_labels'] ) ) {
		foreach ( $_POST['stm_car_features_labels'] as $car_feature ) {
			$car_features[] = esc_attr( $car_feature );
		}
	}

	/*Videos*/
	if ( ! empty( $_POST['stm_video'] ) ) {
		foreach ( $_POST['stm_video'] as $video ) {

			if ( ( strpos( $video, 'youtu' ) ) > 0 ) {
				$is_youtube = array();
				parse_str( wp_parse_url( $video, PHP_URL_QUERY ), $is_youtube );
				if ( ! empty( $is_youtube['v'] ) ) {
					$video = 'https://www.youtube.com/embed/' . $is_youtube['v'];
				}
			}

			$videos[] = esc_url( $video );
			$videos   = array_filter( $videos );
		}
	}

	/*Note*/
	if ( ! empty( $_POST['stm_seller_notes'] ) ) {
		$notes = wp_kses_post( $_POST['stm_seller_notes'] );
	}

	/*Registration date*/
	if ( ! empty( $_POST['stm_registered'] ) ) {
		$registered = sanitize_text_field( $_POST['stm_registered'] );
	}
	/*Price label*/
	if ( ! empty( $_POST['price_label'] ) ) {
		$price_label = sanitize_text_field( $_POST['price_label'] );
	}

	/*Sale Price label*/
	if ( ! empty( $_POST['sale_price_label'] ) ) {
		$sale_price_label = sanitize_text_field( $_POST['sale_price_label'] );
	}

	/*Vin*/
	if ( ! empty( $_POST['stm_vin'] ) ) {
		$vin = sanitize_text_field( $_POST['stm_vin'] );
	}

	/*History*/
	if ( ! empty( $_POST['stm_history_label'] ) ) {
		$history['label'] = sanitize_text_field( $_POST['stm_history_label'] );
	}

	if ( ! empty( $_POST['stm_history_link'] ) ) {
		$history['link'] = sanitize_text_field( $_POST['stm_history_link'] );
	}

	/*Location*/
	if ( ! empty( $_POST['stm_location_text'] ) ) {
		$location['label'] = sanitize_text_field( $_POST['stm_location_text'] );
	}

	if ( ! empty( $_POST['stm_lat'] ) ) {
		$location['lat'] = sanitize_text_field( $_POST['stm_lat'] );
	}

	if ( ! empty( $_POST['stm_lng'] ) ) {
		$location['lng'] = sanitize_text_field( $_POST['stm_lng'] );
	}

	if ( ! empty( $_POST['stm_location_address'] ) ) {
		$location['address'] = wp_filter_nohtml_kses( $_POST['stm_location_address'] );
	}

	if ( empty( $_POST['stm_car_price'] ) ) {
		$error               = true;
		$response['message'] = esc_html__( 'Please add item price', 'motors' );
		$price               = '';
		$normal_price        = '';

	} else {
		$normal_price = floatval( filter_var( $_POST['stm_car_price'], FILTER_SANITIZE_NUMBER_FLOAT ) );
		$price        = ( function_exists( 'stm_convert_to_normal_price' ) ) ? stm_convert_to_normal_price( $normal_price ) : $normal_price;
	}

	if ( isset( $_POST['car_price_form_label'] ) && ! empty( $_POST['car_price_form_label'] ) ) {

		if ( empty( $_POST['stm_car_price'] ) ) {
			$error = false;
			unset( $response['message'] );
		}

		$location['car_price_form_label'] = sanitize_text_field( $_POST['car_price_form_label'] );
	} else {
		$location['car_price_form_label'] = '';
	}

	if ( isset( $_POST['stm_car_sale_price'] ) ) {
		$sale_price                     = floatval( filter_var( $_POST['stm_car_sale_price'], FILTER_SANITIZE_NUMBER_FLOAT ) );
		$location['stm_car_sale_price'] = ( function_exists( 'stm_convert_to_normal_price' ) ) ? stm_convert_to_normal_price( $sale_price ) : $sale_price;
	}

	$generic_title = '';
	if ( ! empty( $_POST['stm_car_main_title'] ) ) {
		$generic_title = sanitize_text_field( $_POST['stm_car_main_title'] );
	}

	$_id             = apply_filters( 'stm_listings_input', null, 'item_id' );
	$pey_per_listing = get_post_meta( $_id, 'pay_per_listing', true );

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_plans' ) && apply_filters( 'stm_is_multiple_plans', false ) && 'pay' !== $_POST['btn-type'] && ! empty( $pey_per_listing ) ) {
		if ( empty( $_POST['selectedPlan'] ) ) {
			$error               = true;
			$response['message'] = esc_html__( 'Please select plan', 'motors' );
		}
	}

	$validation = apply_filters( 'stm_add_car_validation', compact( 'error', 'response' ) );

	$error    = $validation['error'];
	$response = $validation['response'];

	/*Generating post*/
	if ( ! $error ) {

		if ( $restrictions['premoderation'] ) {
			$status = 'pending';
			$user   = stm_get_user_custom_fields( '' );
		} else {
			$status = 'publish';
		}

		if ( 'pay' === $_POST['btn-type'] ) {
			$status = 'pending';
		}

		$post_data = array(
			'post_type'   => apply_filters( 'stm_listings_post_type', 'listings' ),
			'post_title'  => '',
			'post_status' => $status,
		);

		if ( ! empty( $_POST['custom_listing_type'] ) ) {
			$post_data['post_type'] = sanitize_text_field( $_POST['custom_listing_type'] );
			$slug                   = sanitize_text_field( $_POST['custom_listing_type'] );
		}

		if ( ! $update && stm_get_wpb_def_tmpl() ) {
			$post_data['post_content'] = stm_get_wpb_def_tmpl();
		}

		foreach ( $first_step as $taxonomy => $title_part ) {
			$term                     = get_term_by( 'slug', $title_part, $taxonomy );
			$title                    = ( empty( $term ) ) ? $title_part : $term->name;
			$post_data['post_title'] .= $title . ' ';
		}

		if ( ! empty( $generic_title ) ) {
			$post_data['post_title'] = $generic_title;
		}

		if ( ! $update ) {
			$post_id = wp_insert_post( apply_filters( 'stm_listing_save_post_data', $post_data ), true );
			if ( ! is_wp_error( $post_id ) && stm_get_wpb_def_tmpl() ) {
				update_post_meta( $post_id, '_wpb_vc_js_status', 'true' );
			}
		}

		if ( ! is_wp_error( $post_id ) ) {

			if ( $update ) {

				$ppl         = get_post_meta( $post_id, 'pay_per_listing', true );
				$pp_order_id = get_post_meta( $post_id, 'pay_per_order_id', true );

				if ( ! empty( $ppl ) && ! empty( $pp_order_id ) ) {
					$order      = new WC_Order( $pp_order_id );
					$order_data = (object) $order->get_data();

					if ( 'completed' !== $order_data->status ) {
						$status = 'pending';
					}
				} elseif ( ! empty( $ppl ) && empty( $pp_order_id ) ) {
					$status = 'pending';
				}

				$post_data_update = array(
					'ID'          => $post_id,
					'post_status' => $status,
				);

				if ( ! empty( $generic_title ) ) {
					$post_data_update['post_title'] = $generic_title;
				}

				wp_update_post( apply_filters( 'stm_listing_save_post_data', $post_data_update ) );

			}

			$terms = array();

			/*Set categories*/
			foreach ( $first_step as $tax => $term ) {
				$tax_info = apply_filters( 'stm_vl_get_all_by_slug', array(), $tax );
				if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) {
					update_post_meta( $post_id, $tax, abs( sanitize_title( $term ) ) );
					$meta[ $tax ] = abs( sanitize_title( $term ) );
				} else {
					wp_delete_object_term_relationships( $post_id, $tax );
					wp_add_object_terms( $post_id, $term, $tax );
					$terms[ $tax ] = $term;
					$meta[ $tax ]  = sanitize_title( $term );
				}

				/**
				 *  add parent child connections if parent exist
				 *  !!! important - this part of code must be here not higher
				 */
				if ( array_key_exists( 'listing_taxonomy_parent', $tax_info ) && ! empty( $tax_info['listing_taxonomy_parent'] ) && array_key_exists( $tax_info['listing_taxonomy_parent'], $first_step ) ) {

					$term   = get_term_by( 'slug', $term, $tax );
					$parent = $first_step[ $tax_info['listing_taxonomy_parent'] ];
					if ( $term && ! empty( $parent ) ) {
						update_term_meta( $term->term_id, 'stm_parent', mb_convert_encoding( $parent, 'Windows-1251', 'utf-8' ) );
					}
				}
			}

			/*Set categories*/
			foreach ( $second_step as $tax => $term ) {

				$term = apply_filters( 'stm_change_value', $term );

				if ( ! empty( $tax ) ) {
					$tax_info = apply_filters( 'stm_vl_get_all_by_slug', array(), $tax );
					if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) {
						update_post_meta( $post_id, $tax, $term );
						$meta[ $tax ] = $term;
					} else {
						wp_delete_object_term_relationships( $post_id, $tax );
						wp_add_object_terms( $post_id, $term, $tax );
						$terms[ $tax ] = $term;
						$meta[ $tax ]  = $term;
					}
				}
			}

			$meta = array(
				'stock_number'      => $post_id,
				'stm_car_user'      => $user['user_id'],
				'price'             => $price,
				'stm_genuine_price' => $price,
				'title'             => 'hide',
				'breadcrumbs'       => 'show',
			);

			if ( ! empty( $videos ) ) {
				$meta['gallery_video'] = $videos[0];

				if ( count( $videos ) > 1 ) {
					array_shift( $videos );
					$meta['gallery_videos'] = array_filter( array_unique( $videos ) );
				}
			} else {
				$meta['gallery_video']  = '';
				$meta['gallery_videos'] = '';
			}

			$meta['vin_number']               = $vin;
			$meta['registration_date']        = $registered;
			$meta['history']                  = $history['label'];
			$meta['history_link']             = $history['link'];
			$meta['stm_car_location']         = $location['label'];
			$meta['stm_lat_car_admin']        = $location['lat'];
			$meta['stm_lng_car_admin']        = $location['lng'];
			$meta['stm_location_address']     = $location['address'];
			$meta['additional_features']      = implode( ',', $car_features );
			$terms['stm_additional_features'] = $car_features;

			stm_sanitize_location_address_update( $location['address'], $post_id );

			$regular_price_label = apply_filters( 'motors_vl_get_nuxy_mod', '', 'addl_price_label' );
			if ( ! empty( $regular_price_label ) ) {
				update_post_meta( $post_id, 'regular_price_label', $regular_price_label );
			}

			$special_price_label = apply_filters( 'motors_vl_get_nuxy_mod', '', 'addl_sale_price_label' );
			if ( ! empty( $special_price_label ) ) {
				update_post_meta( $post_id, 'special_price_label', $special_price_label );
			}

			$post_type = get_post_type( $post_id );
			if ( class_exists( 'STMMultiListing' ) && 'listings' !== $post_type ) {
				$options = get_option( 'stm_motors_listing_types', array() );
				if ( isset( $options[ $slug . '_addl_sale_price_label' ] ) && ! empty( $options[ $slug . '_addl_sale_price_label' ] ) ) {
					$mlt_sale_price_label = $options[ $slug . '_addl_sale_price_label' ];
					$mlt_price_label      = $options[ $slug . '_addl_price_label_text' ];
				}

				update_post_meta( $post_id, 'special_price_label', $mlt_sale_price_label ?? '' );

				update_post_meta( $post_id, 'regular_price_label', $mlt_price_label ?? '' );
			}

			update_post_meta( $post_id, 'price', $price );
			update_post_meta( $post_id, 'stm_genuine_price', $price );
			update_post_meta( $post_id, 'listing_seller_note', $notes );

			if ( ! empty( $price_label ) ) {
				update_post_meta( $post_id, 'regular_price_label', $price_label );
			}
			if ( ! empty( $sale_price_label ) ) {
				update_post_meta( $post_id, 'special_price_label', $sale_price_label );
			}

			if ( isset( $location['car_price_form_label'] ) ) {
				$meta['car_price_form_label'] = $location['car_price_form_label'];
			}

			if ( isset( $location['stm_car_sale_price'] ) && ! empty( $location['stm_car_sale_price'] ) ) {
				$meta['sale_price']        = $location['stm_car_sale_price'];
				$meta['stm_genuine_price'] = $location['stm_car_sale_price'];
			} else {
				$meta['sale_price'] = '';
			}

			foreach ( apply_filters( 'stm_listing_save_post_meta', $meta, $post_id, $update ) as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

			foreach ( apply_filters( 'stm_listing_save_post_terms', $terms, $post_id, $update ) as $tax => $term ) {
				wp_delete_object_term_relationships( $post_id, $tax );
				wp_add_object_terms( $post_id, $term, $tax );

				if ( ! empty( $term ) && is_string( $term ) ) {
					update_post_meta( $post_id, $tax, sanitize_title( $term ) );
				} else {
					delete_post_meta( $post_id, $tax );
				}
			}

			update_post_meta( $post_id, 'title', 'hide' );
			update_post_meta( $post_id, 'breadcrumbs', 'show' );
			update_post_meta( $post_id, 'car_mark_as_sold', '' );

			$response['post_id']       = $post_id;
			$response['redirect_type'] = sanitize_text_field( $_POST['btn-type'] );
			if ( ( $update ) ) {
				$response['message'] = esc_html__( 'Listing Updated, uploading photos', 'motors' );
			} else {
				$response['message'] = esc_html__( 'Listing Added, uploading photos', 'motors' );
			}

			if ( ! $update ) {
				$title_from = apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_directory_title_frontend' );
				if ( ! empty( $title_from ) ) {
					wp_update_post(
						array(
							'ID'         => $post_id,
							'post_title' => apply_filters( 'stm_generate_title_from_slugs', get_the_title( $post_id ), $post_id ),
						)
					);
				}
			}

			if ( apply_filters( 'stm_is_multiple_plans', false ) && 'pay' !== $_POST['btn-type'] ) {
				$plan_id = filter_var( $_POST['selectedPlan'], FILTER_SANITIZE_NUMBER_INT );

				if ( $update ) {
					\MotorsVehiclesListing\Features\MultiplePlan::updatePlanMeta( $plan_id, $post_id, 'active' );
				} else {
					\MotorsVehiclesListing\Features\MultiplePlan::addPlanMeta( $plan_id, $post_id, 'active' );
				}
			}

			do_action( 'stm_after_listing_saved', $post_id, $response, $update );

		} else {
			$response['message'] = $post_id->get_error_message();
		}
	}

	wp_send_json( apply_filters( 'stm_filter_add_a_car', $response ) );
}

remove_action( 'wp_ajax_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );
remove_action( 'wp_ajax_nopriv_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );
add_action( 'wp_ajax_stm_ajax_add_a_car', 'stm_add_a_car' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_a_car', 'stm_add_a_car' );

function stm_ajax_get_cars_for_inventory_map() {
	check_ajax_referer( 'stm_security_nonce', 'security' );
	wp_reset_postdata();
	$response = new WP_Query(
		apply_filters(
			'_stm_listings_build_query_args',
			array(
				'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
				'order'          => 'DESC',
				'orderby'        => 'date',
				'sold_car'       => 'off',
				'nopaging'       => true,
				'posts_per_page' => - 1,
			)
		)
	);

	$map_location_car = array();
	$cars_data        = array();
	$markers          = array();
	$i                = 0;
	$cars_info        = array();

	$placeholder_path = 'plchldr255.png';
	if ( apply_filters( 'stm_is_boats', false ) ) {
		$placeholder_path = 'boats-placeholders/Boat-small.jpg';
	} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
		$placeholder_path = 'Plane-small.jpg';
	} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
		$placeholder_path = 'Motor-small.jpg';
	}

	foreach ( apply_filters( 'stm_get_map_listings', array() ) as $k => $val ) {
		if ( isset( $val['use_on_map_page'] ) && true === boolval( $val['use_on_map_page'] ) ) {
			$cars_info[ count( $cars_info ) ] = array(
				'key'  => $val['slug'],
				'icon' => $val['font'],
			);
		}
	}

	foreach ( $response->get_posts() as $k => $val ) {

		if ( ! empty( get_post_meta( $val->ID, 'stm_lat_car_admin' ) ) && 'publish' === $val->post_status || 'private' === $val->post_status ) {
			$car_meta = get_post_meta( $val->ID, '' );
			$img      = "<img src='" . get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path . "'/>";

			if ( has_post_thumbnail( $val->ID ) ) {
				$img = ( ! empty( get_the_post_thumbnail( $val->ID, 'full' ) ) ) ? get_the_post_thumbnail( $val->ID, 'full' ) : '';
			}

			$price = ( isset( $car_meta['price'] ) ) ? apply_filters( 'stm_filter_price_view', '', $car_meta['price'][0] ) : 0 . apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) );
			if ( isset( $car_meta['sale_price'] ) && ! empty( $car_meta['sale_price'][0] ) && ! empty( $car_meta['sale_price'][0] ) ) {
				$price = apply_filters( 'stm_filter_price_view', '', $car_meta['sale_price'][0] );
			}

			$car_price_form_label = get_post_meta( $val->ID, 'car_price_form_label', true );
			if ( ! empty( $car_price_form_label ) ) {
				$price = $car_price_form_label;
			}

			$cars_data[ $i ]['id']                = $val->ID;
			$cars_data[ $i ]['link']              = get_the_permalink( $val->ID );
			$cars_data[ $i ]['title']             = urldecode( $val->post_title );
			$cars_data[ $i ]['image']             = $img;
			$cars_data[ $i ]['price']             = $price;
			$cars_data[ $i ]['year']              = ( isset( $car_meta['ca-year'] ) ) ? $car_meta['ca-year'][0] : '';
			$cars_data[ $i ]['condition']         = ( isset( $car_meta['condition'] ) ) ? urldecode( mb_strtoupper( str_replace( '-cars', '', $car_meta['condition'][0] ) ) ) : '';
			$cars_data[ $i ]['mileage']           = ( isset( $cars_info[0] ) && isset( $car_meta[ $cars_info[0]['key'] ] ) ) ? urldecode( $car_meta[ $cars_info[0]['key'] ][0] ) : '';
			$cars_data[ $i ]['engine']            = ( isset( $cars_info[1] ) && isset( $car_meta[ $cars_info[1]['key'] ] ) ) ? urldecode( $car_meta[ $cars_info[1]['key'] ][0] ) : '';
			$cars_data[ $i ]['transmission']      = ( isset( $cars_info[2] ) && isset( $car_meta[ $cars_info[2]['key'] ] ) ) ? urldecode( $car_meta[ $cars_info[2]['key'] ][0] ) : '';
			$cars_data[ $i ]['mileage_font']      = ( isset( $cars_info[0] ) && isset( $cars_info[0]['icon'] ) ) ? urldecode( $cars_info[0]['icon'] ) : '';
			$cars_data[ $i ]['engine_font']       = ( isset( $cars_info[1] ) && isset( $cars_info[1]['icon'] ) ) ? urldecode( $cars_info[1]['icon'] ) : '';
			$cars_data[ $i ]['transmission_font'] = ( isset( $cars_info[2] ) && isset( $cars_info[2]['icon'] ) ) ? urldecode( $cars_info[2]['icon'] ) : '';

			$markers[ $i ]['lat']      = round( floatval( $car_meta['stm_lat_car_admin'][0] ), 7 );
			$markers[ $i ]['lng']      = round( floatval( $car_meta['stm_lng_car_admin'][0] ), 7 );
			$markers[ $i ]['location'] = ( ! empty( $car_meta['stm_car_location'] ) ) ? $car_meta['stm_car_location'][0] : 'no location';

			$map_location_car[ (string) round( $markers[ $i ]['lat'], 7 ) ][] = $i;
			$i ++;
		}
	}

	wp_reset_postdata();

	$GLOBALS['listings_query'] = $response;
	$data                      = apply_filters(
		'stm_ajax_cars_for_map',
		array(
			'carsData'       => $cars_data,
			'markers'        => $markers,
			'mapLocationCar' => $map_location_car,
		)
	);
	echo wp_json_encode( $data );
	exit;
}
add_action( 'wp_ajax_stm_ajax_get_cars_for_inventory_map', 'stm_ajax_get_cars_for_inventory_map' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_cars_for_inventory_map', 'stm_ajax_get_cars_for_inventory_map' );

if ( ! function_exists( 'stm_ajax_get_seller_phone' ) ) {
	function stm_ajax_get_seller_phone() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$phone_number = get_user_meta( filter_var( $_GET['phone_owner_id'], FILTER_SANITIZE_NUMBER_INT ), 'stm_phone', true );

		if ( isset( $_GET['listing_id'] ) && ! empty( $_GET['listing_id'] ) && 0 !== $_GET['listing_id'] ) {

			$listing_id = intval( $_GET['listing_id'] );

			$cookies = '';

			if ( empty( $_COOKIE['stm_phone_revealed'] ) ) {
				$cookies = $listing_id;
				setcookie( 'stm_phone_revealed', $cookies, time() + ( 86400 * 30 ), '/' );

				// total reveals counter
				$total_reveals = intval( get_post_meta( $listing_id, 'stm_phone_reveals', true ) );
				if ( empty( $total_reveals ) ) {
					update_post_meta( $listing_id, 'stm_phone_reveals', 1 );
				} else {
					++ $total_reveals;
					update_post_meta( $listing_id, 'stm_phone_reveals', $total_reveals );
				}

				// date based counter for statistics.
				$reveals_today = intval( get_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), true ) );
				if ( empty( $reveals_today ) ) {
					update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), 1 );
				} else {
					++ $reveals_today;
					update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), $reveals_today );
				}
			} else {
				$cookies = sanitize_text_field( $_COOKIE['stm_phone_revealed'] );
				$cookies = explode( ',', $cookies );

				if ( ! in_array( $listing_id, $cookies, true ) ) {
					$cookies[] = $listing_id;

					$cookies = implode( ',', $cookies );

					setcookie( 'stm_phone_revealed', $cookies, time() + ( 86400 * 30 ), '/' );

					// total reveals counter
					$total_reveals = intval( get_post_meta( $listing_id, 'stm_phone_reveals', true ) );
					if ( empty( $total_reveals ) ) {
						update_post_meta( $listing_id, 'stm_phone_reveals', 1 );
					} else {
						++ $total_reveals;
						update_post_meta( $listing_id, 'stm_phone_reveals', $total_reveals );
					}

					// date based counter for statistics
					$reveals_today = intval( get_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), true ) );
					if ( empty( $reveals_today ) ) {
						update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), 1 );
					} else {
						++ $reveals_today;
						update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), $reveals_today );
					}
				}
			}
		}

		wp_send_json( $phone_number );
		exit;
	}

	add_action( 'wp_ajax_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );
	add_action( 'wp_ajax_nopriv_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );
}


function stm_ajax_rental_check_car_in_current_office() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$cart_items           = stm_get_cart_items();
	$car_rent             = $cart_items['car_class'];
	$pickup_location_meta = explode( ',', get_post_meta( $car_rent['id'], 'stm_rental_office', true ) );

	wp_send_json( ( array_search( $_GET['rental_office_id'], $pickup_location_meta, true ) === false && ! empty( $car_rent['id'] ) ) ? $responce['responce'] = 'EMPTY' : $responce['responce'] = 'INSTOCK' );
	exit;
}

add_action( 'wp_ajax_stm_ajax_rental_check_car_in_current_office', 'stm_ajax_rental_check_car_in_current_office' );
add_action( 'wp_ajax_nopriv_stm_ajax_rental_check_car_in_current_office', 'stm_ajax_rental_check_car_in_current_office' );

function stm_ajax_check_is_available_car_date() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$start_date = sanitize_text_field( $_GET['startDate'] );
	$end_date   = sanitize_text_field( $_GET['endDate'] );
	$cart_items = stm_get_cart_items();
	$car_rent   = $cart_items['car_class'];
	$id         = $car_rent['id'];

	if ( ! $id ) {
		wp_send_json( array() );
	}

	$check_order_available = stm_check_order_available( $id, $start_date, $end_date );

	$formated_dates = array();
	foreach ( $check_order_available as $val ) {
		$formated_dates[] = stm_get_formated_date( $val, 'd M' );
	}

	wp_send_json( ( count( $check_order_available ) > 0 ) ? $responce['responce'] = esc_html__( 'This Class is already booked in: ', 'motors' ) . '<span>' . implode( ', ', $formated_dates ) . '</span>.' : $responce['responce'] = '' );
}

add_action( 'wp_ajax_stm_ajax_check_is_available_car_date', 'stm_ajax_check_is_available_car_date' );
add_action( 'wp_ajax_nopriv_stm_ajax_check_is_available_car_date', 'stm_ajax_check_is_available_car_date' );

function stm_ajax_get_recent_posts_magazine() {
	check_ajax_referer( 'stm_ajax_get_recent_posts_magazine', 'security' );

	$posts_per_page = sanitize_text_field( $_GET['posts_per_page'] );

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $posts_per_page,
		'ignore_sticky_posts' => true,
	);

	if ( isset( $_GET['category'] ) && 'all' !== $_GET['category'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['category'] ),
			),
		);
	}

	$r = new WP_Query( $args );

	ob_start();

	if ( $r->have_posts() && class_exists( 'WPBMap' ) ) {
		WPBMap::addAllMappedShortcodes();
		while ( $r->have_posts() ) {
			$r->the_post();
			get_template_part( 'partials/blog/content-list-magazine-loop' );
		}
	}

	$result['html'] = ob_get_clean();
	wp_send_json( $result );
	exit;
}

add_action( 'wp_ajax_stm_ajax_get_recent_posts_magazine', 'stm_ajax_get_recent_posts_magazine' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_recent_posts_magazine', 'stm_ajax_get_recent_posts_magazine' );

function stm_ajax_sticky_posts_magazine() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$sticky = get_option( 'sticky_posts' );

	$args = array(
		'post_type'   => 'any',
		'post__in'    => $sticky,
		'post_status' => 'publish',
	);

	if ( isset( $_GET['category'] ) && 'all' !== $_GET['category'] ) {
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['category'] ),
			),
			array(
				'taxonomy' => 'review_category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['category'] ),
			),
			array(
				'taxonomy' => 'event_category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['category'] ),
			),
		);
	}

	$r = new WP_Query( $args );

	ob_start();
	if ( $r->have_posts() ) {
		$num = 0;
		while ( $r->have_posts() ) {
			$r->the_post();
			if ( 0 === $num ) {
				get_template_part( 'partials/vc_loop/features_posts_big_loop' );
			} else {
				if ( $_GET['adsense_position'] === $num && 'yes' === $_GET['use_adsense'] ) {
					?>
					<div class="adsense-200-200"></div>
					<?php
				}
				if ( $num > $_GET['hidenWrap'] ) {
					echo '<div class="features_hiden">';
				}
				get_template_part( 'partials/vc_loop/features_posts_small_loop' );
				if ( $num > $_GET['hidenWrap'] ) {
					echo '</div>';
				}
			}

			$num ++;
		}
	}
	$result['html'] = ob_get_clean();

	wp_reset_postdata();
	wp_send_json( $result );
	exit;
}

add_action( 'wp_ajax_stm_ajax_sticky_posts_magazine', 'stm_ajax_sticky_posts_magazine' );
add_action( 'wp_ajax_nopriv_stm_ajax_sticky_posts_magazine', 'stm_ajax_sticky_posts_magazine' );

function stm_ajax_get_events() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$id   = intval( $_GET['post_id'] );
	$date = get_the_date( 'd M Y', $id );

	$date_start      = get_post_meta( $id, 'date_start', true );
	$date_start_time = get_post_meta( $id, 'date_start_time', true );
	$date_end        = get_post_meta( $id, 'date_end', true );
	$date_end_time   = get_post_meta( $id, 'date_end_time', true );
	$address         = get_post_meta( $id, 'address', true );
	$participants    = get_post_meta( $id, 'cur_participants', true );
	$category        = event_get_terms_array( $id, 'event_category', 'name', false );

	if ( empty( $participants ) ) {
		$participants = 0;
	}

	$time           = '';
	$date_prev      = ( ! empty( $date_start ) ) ? apply_filters( 'stm_motors_get_formatted_date', $date_start, 'd M Y' ) : '';
	$time_format    = apply_filters( 'stm_motors_get_formatted_date', strtotime( $date_end_time ), 'H:i:s' );
	$countdown_date = apply_filters( 'stm_motors_get_formatted_date', $date_end, 'Y-m-d ' ) . $time_format;

	if ( ! empty( $date_start_time ) ) {
		$time .= $date_start_time;
	}
	if ( ! empty( $date_end_time ) ) {
		$time .= ' - ' . $date_end_time;
	}

	ob_start();

	$url = ( ! empty( get_the_post_thumbnail( $id, 'stm-img-690-410' ) ) ) ? get_the_post_thumbnail( $id, 'stm-img-690-410' ) : '';

	echo '
            <div class="title">
                <h3>' . esc_html( get_the_title( $id ) ) . '</h3>
            </div>
            <div class="event-data">
                <div class="address">
                    <i class="me-ico_event_pin"></i>
                    <div>' . wp_kses_post( $address ) . '</div>
                </div>
                <div class="date">
                    <i class="stm-icon-ico_mag_calendar"></i>
                    <div>' . wp_kses_post( $date_prev ) . '</div>
                </div>
                <div class="time">
                    <i class="me-ico_event_clock"></i>
                    <div>' . wp_kses_post( $time ) . '</div>
                </div>
            </div>
            <div class="event-single-wrap">
                <div class="img">
                    ' . wp_kses_post( $url ) . '
                </div>
                <div class="timer">
                    <div class="stm-countdown-wrapper">
                        <time class="heading-font" datetime="' . esc_attr( $countdown_date ) . '"  data-countdown="' . esc_attr( str_replace( '-', '/', $countdown_date ) ) . '" ></time>
                    </div>
                </div>
                <div class="timer timerFullHeight">
                    <div class="stm-countdown-wrapper">
                        <time class="heading-font" datetime="' . esc_attr( $countdown_date ) . '"  data-countdown="' . esc_attr( str_replace( '-', '/', $countdown_date ) ) . '" ></time>
                    </div>
                </div>
                <div class="participants">
                    <i class="me-ico_profile"></i>
                    <div class="prticipants_count heading-font">
                        ' . wp_kses_post( $participants ) . '
                    </div>
                </div>
                <div class="event_more_btn">
                    <a href="' . esc_url( get_the_permalink( $id ) ) . '" class="stm-button">' . esc_html__( 'More Details', 'motors' ) . '</a>
                </div>
            </div>
    ';

	$result['html'] = ob_get_clean();

	wp_send_json( $result );
	exit;
}

add_action( 'wp_ajax_stm_ajax_get_events', 'stm_ajax_get_events' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_events', 'stm_ajax_get_events' );

function stm_ajax_get_test_drive_modal() {
	$shortcode = sanitize_text_field( $_POST['shortCode'] );
	$car_name  = sanitize_text_field( $_POST['carName'] );
	ob_start();

	echo '
            <div class="modal leasing-modal" id="leasing-test-drive" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTestDrive">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-iconed">
                            <i class="stm-icon-steering_wheel"></i>
                            <h3 class="modal-title" id="myModalLabelTestDrive">' . esc_html__( 'Test Drive', 'motors' ) . '</h3>
                            <div class="test-drive-car-name">' . esc_html( $car_name ) . '</div>
                        </div>
                        <div class="modal-body">
                            ' . do_shortcode( stripslashes( wp_specialchars_decode( $shortcode ) ) ) . '
                        </div>
                    </div>
                </div>
            </div>
    ';

	$result['html'] = ob_get_clean();

	wp_send_json( $result );
	exit;
}

add_action( 'wp_ajax_stm_ajax_get_test_drive_modal', 'stm_ajax_get_test_drive_modal' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_test_drive_modal', 'stm_ajax_get_test_drive_modal' );

function stm_ajax_clear_data() {
	check_ajax_referer( 'stm_security_nonce', 'security' );
	WC()->cart->empty_cart();
	WC()->session->destroy_session();
}

add_action( 'wp_ajax_stm_ajax_clear_data', 'stm_ajax_clear_data' );
add_action( 'wp_ajax_nopriv_stm_ajax_clear_data', 'stm_ajax_clear_data' );


function stm_ajax_inventory_no_filter() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$args = array(
		'post_type'        => apply_filters( 'stm_listings_post_type', 'listings' ),
		'post_status'      => 'publish',
		'posts_per_page'   => sanitize_text_field( $_GET['posts_per_page'] ),
		'suppress_filters' => 0,
		'order_by'         => 'ID',
		'order'            => 'DESC',
		'paged'            => sanitize_text_field( $_GET['paged'] ),
	);

	if ( apply_filters( 'stm_sold_status_enabled', false ) ) {
		$args['meta_query'][] = array(
			'key'     => 'car_mark_as_sold',
			'value'   => '',
			'compare' => '=',
		);
	}

	$listings = new WP_Query( $args );

	if ( $listings->have_posts() ) {
		ob_start();
		while ( $listings->have_posts() ) {
			$listings->the_post();
			get_template_part( 'partials/vc_loop/inventory-no-filter-loop' );
		}
		$response['content'] = ob_get_contents();
		$response['pagina']  = paginate_links(
			array(
				'type'      => 'list',
				'current'   => sanitize_text_field( $_GET['paged'] ),
				'total'     => $listings->found_posts / intval( sanitize_text_field( $_GET['posts_per_page'] ) ),
				'prev_text' => '<i class="fas fa-angle-left"></i>',
				'next_text' => '<i class="fas fa-angle-right"></i>',
			)
		);
		ob_end_clean();

		wp_reset_postdata();
	}

	echo wp_json_encode( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_inventory_no_filter', 'stm_ajax_inventory_no_filter' );
add_action( 'wp_ajax_nopriv_stm_ajax_inventory_no_filter', 'stm_ajax_inventory_no_filter' );

if ( ! function_exists( 'stm_ajax_subscriptio_change_status' ) ) {
	function stm_ajax_subscriptio_change_status() {

		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array();

		try {
			$subscription = subscriptio_get_subscription( sanitize_text_field( $_POST['subs_id'] ) );
			$subscription->set_status( RightPress_Help::clean_wc_status( wc_clean( wp_unslash( $_POST['subs_status'] ) ) ), 'admin' );
			$response = array(
				'status' => 'success',
			);
		} catch ( Exception $e ) {
			$response = array(
				'status' => $e->getMessage(),
			);
		}

		wp_send_json( $response );
	}
}

add_action( 'wp_ajax_clear_woo_cart', 'clear_woo_cart' );
add_action( 'wp_ajax_nopriv_clear_woo_cart', 'clear_woo_cart' );

function clear_woo_cart() {
	WC()->cart->empty_cart();
	wp_send_json_success( array( 'message' => esc_html__( 'Clear woocommerce cart', 'motors' ) ) );
}

add_action( 'wp_ajax_stm_ajax_subscriptio_change_status', 'stm_ajax_subscriptio_change_status' );
add_action( 'wp_ajax_nopriv_stm_ajax_subscriptio_change_status', 'stm_ajax_subscriptio_change_status' );

// Listings List - Ajax Pagination
add_action( 'wp_ajax_stm_ajax_load_listings_list_items', 'stm_ajax_load_listings_list_items' );
add_action( 'wp_ajax_nopriv_stm_ajax_load_listings_list_items', 'stm_ajax_load_listings_list_items' );

function stm_ajax_load_listings_list_items() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	global $wp;

	$query_args          = array_map( 'sanitize_text_field', $_POST['query_args'] );
	$query_args['paged'] = sanitize_text_field( $_POST['paged'] );
	$img_size            = sanitize_text_field( $_POST['img_size'] );
	$template_args       = array();
	if ( ! empty( $img_size ) ) {
		$template_args = array(
			'custom_img_size' => $img_size,
		);
	}

	$query = new WP_Query( $query_args );

	$html       = '';
	$pagination = '';

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'partials/listing-cars/listing-list-directory', 'loop', $template_args );
		}
		$html = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		$pagination = paginate_links(
			array(
				'base'      => home_url( add_query_arg( array(), $wp->request ) ) . '/%_%',
				'type'      => 'list',
				'total'     => ceil( $query->found_posts / $query_args['posts_per_page'] ),
				'prev_text' => '<i class="fas fa-angle-left"></i>',
				'next_text' => '<i class="fas fa-angle-right"></i>',
				'current'   => $query_args['paged'],
			)
		);
	}

	$result = array(
		'html'       => $html,
		'pagination' => $pagination,
	);

	echo wp_json_encode( $result );
	exit;
}
