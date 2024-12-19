<?php
if ( have_posts() ) :

	$view_type = sanitize_file_name( apply_filters( 'stm_listings_input', apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' ), 'view_type' ) );

	$template = 'partials/listing-cars/listing-aircrafts-' . $view_type;

	while ( have_posts() ) :
		the_post();
		get_template_part( $template );
	endwhile;
	wp_reset_postdata();

endif;
