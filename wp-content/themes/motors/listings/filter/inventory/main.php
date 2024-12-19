<div class="archive-listing-page">
	<div class="container">
		<?php
		$recaptcha_enabled    = apply_filters( 'stm_me_get_nuxy_mod', '', 'enable_recaptcha' );
		$recaptcha_public_key = apply_filters( 'stm_me_get_nuxy_mod', '', 'recaptcha_public_key' );
		$recaptcha_secret_key = apply_filters( 'stm_me_get_nuxy_mod', '', 'recaptcha_secret_key' );

		if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) {
			wp_enqueue_script( 'stm_grecaptcha' );
		}

		$boats_template = apply_filters( 'motors_vl_get_nuxy_mod', false, 'listing_filter_position' );
		$post_types     = apply_filters( 'stm_listings_multi_type', array() );

		if ( is_post_type_archive( $post_types ) || apply_filters( 'stm_is_dealer_two', false ) || boolval( apply_filters( 'is_listing', array( 'listing', 'listing_two', 'listing_three' ) ) ) ) {
			get_template_part( 'partials/listing-cars/listing-directory', 'archive', $__vars );
		} elseif ( apply_filters( 'stm_is_boats', false ) && 'horizontal' === $boats_template ) {
			get_template_part( 'partials/listing-cars/listing-boats', 'archive', $__vars );
		} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
			get_template_part( 'partials/listing-cars/motos/listing-motos', 'archive', $__vars );
		} else {
			get_template_part( 'partials/listing-cars/listing', 'archive', $__vars );
		}
		?>
	</div>
</div>
