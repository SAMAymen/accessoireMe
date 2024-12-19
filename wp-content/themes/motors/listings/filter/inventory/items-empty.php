<?php
$args = apply_filters(
	'stm_listings_query',
	array(
		'enable_distance_search' => true,
		'stm_location_address'   => null,
	)
);
$args = ( is_object( $args ) ) ? $args->query : array();

$args['posts_per_page'] = 3;

$listings = new WP_Query( $args );

$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );
if ( wp_is_mobile() ) {
	$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
}
$view_type = apply_filters( 'stm_listings_input', $nuxy_mod_option, 'view_type' );
$url_args  = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( isset( $url_args['ajax_action'] ) ) {
	unset( $url_args['ajax_action'] );
}
if ( isset( $url_args['posttype'] ) && 'undefined' === $url_args['posttype'] ) {
	unset( $url_args['posttype'] );
}

$template_args = array();
if ( ! empty( $args['custom_img_size'] ) ) {
	$template_args['custom_img_size'] = $args['custom_img_size'];
}

if ( $listings->have_posts() ) : ?>

	<div class="stm-location-top-listings-title">
		<div class="heading-font">
			<span class="stm-icon-search-items"></span>
			<?php esc_html_e( 'Cars found in other locations', 'motors' ); ?>
		</div>
	</div>

	<?php if ( ! apply_filters( 'stm_listings_input', null, 'featured_top' ) ) : ?>
		<?php if ( 'grid' === $view_type ) : ?>
			<div class="row row-3 car-listing-row car-listing-modern-grid">
		<?php endif; ?>

		<div class="stm-isotope-sorting stm-isotope-sorting-featured-top">

			<?php
			$template = 'partials/listing-cars/listing-' . $view_type . '-directory-loop';
			while ( $listings->have_posts() ) :
				$listings->the_post();
				if ( apply_filters( 'stm_is_listing_four', false ) ) {
					get_template_part( 'partials/listing-cars/listing-four-' . $view_type . '-loop', null, $template_args );
				} else {
					get_template_part( 'partials/listing-cars/listing-' . $view_type . '-directory-loop', null, $template_args );
				}
			endwhile;
			?>

		</div>

		<?php if ( 'grid' === $view_type ) : ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php
endif;
