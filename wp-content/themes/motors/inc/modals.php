<?php

//modals loading function
add_action( 'wp_footer', 'motors_modals' );
function motors_modals() {
	global $wp_query;

	$elementor_exists = defined( 'MOTORS_ELEMENTOR_WIDGETS_PLUGIN_VERSION' );

	if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'hma_search_button' ) ) {
		// search form with possible results in inventory page.
		get_template_part( 'partials/modals/searchform' );
	}

	if ( ! $elementor_exists && ! apply_filters( 'stm_is_auto_parts', true ) && ! apply_filters( 'stm_is_rental', true ) ) {

		if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_trade_in' ) ) {
			do_action( 'stm_listings_load_template', 'modals/trade-in' );
		}

		if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_offer_price' ) ) {
			do_action( 'stm_listings_load_template', 'modals/trade-offer' );
		}

		if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_calculator' ) ) {
			do_action( 'stm_listings_load_template', 'modals/car-calculator' );
		}
	}

	if ( apply_filters( 'stm_is_motorcycle', false ) ) {
		if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_offer_price' ) || ( $elementor_exists && $wp_query->get( 'show_offer_price' ) ) ) {
			do_action( 'stm_listings_load_template', 'modals/trade-offer' );
		}
	}

	$show_listing_test_drive = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' );
	$show_test_drive         = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_test_drive' ) || ( $elementor_exists && $wp_query->get( 'show_test_drive' ) );

	$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

	if ( stm_is_multilisting() ) {
		$listings = STMMultiListing::stm_get_listings();
		if ( ! empty( $listings ) ) {
			foreach ( $listings as $listing ) {
				$post_types[] = $listing['slug'];
			}
		}
	}

	if ( ( $show_listing_test_drive && ! is_single() ) || ( is_singular( $post_types ) && $show_test_drive ) ) {
		do_action( 'stm_listings_load_template', 'modals/test-drive' );
	}

	do_action( 'stm_listings_load_template', 'modals/get-car-price' );

	if ( apply_filters( 'stm_pricing_enabled', false ) ) {
		get_template_part( 'partials/modals/subscription_ended' );
	}

	if ( ! defined( 'ULISTING_VERSION' ) && ( apply_filters( 'stm_is_listing', false ) || apply_filters( 'stm_is_listing_two', false ) || apply_filters( 'stm_is_listing_three', false ) || apply_filters( 'stm_is_listing_four', false ) || apply_filters( 'stm_is_listing_five', false ) ) && is_author() ) {
		$user = wp_get_current_user();
		$vars = get_queried_object();

		if ( $user->ID === $vars->ID ) {
			get_template_part( 'partials/modals/statistics-modal' );
		}
	}

	if ( apply_filters( 'stm_is_rental', false ) ) {
		get_template_part( 'partials/modals/rental-notification-choose-another-class' );
		echo '<div class="stm-rental-overlay"></div>';
	}
}

add_action( 'wp_footer', 'comapre_notification_bar' );
function comapre_notification_bar() {
	if ( ! apply_filters( 'stm_is_rental_two', true ) && ! apply_filters( 'stm_is_auto_parts', true ) ) {
		get_template_part( 'partials/compare-notification' );
	}
}

add_action( 'wp_footer', 'value_my_car_notification' );
function value_my_car_notification() {
	?>
	<?php if ( defined( 'STM_VALUE_MY_CAR' ) ) : ?>
		<div class="notification-wrapper">
			<div class="notification-wrap">
				<div class="message-container">
					<span class="message"></span>
				</div>
				<div class="btn-container">
					<button class="notification-close">
						<?php echo esc_html__( 'Close', 'motors' ); ?>
					</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
}
