<?php
if ( is_date() ) {
	get_template_part( 'index' );
} else {
	get_header();
	get_template_part( 'partials/title_box' );

	$recaptcha_enabled    = apply_filters( 'stm_me_get_nuxy_mod', 0, 'enable_recaptcha' );
	$recaptcha_public_key = apply_filters( 'stm_me_get_nuxy_mod', '', 'recaptcha_public_key' );
	$recaptcha_secret_key = apply_filters( 'stm_me_get_nuxy_mod', '', 'recaptcha_secret_key' );

	if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) {
		wp_enqueue_script( 'stm_grecaptcha' );
	}

	do_action( 'stm_listings_load_template', 'filter/inventory/main' );

	get_footer();
}
