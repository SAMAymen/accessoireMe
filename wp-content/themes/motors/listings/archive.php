<?php get_header(); ?>

<?php get_template_part( 'partials/title_box' ); ?>
	<div class="stm-single-car-page">
		<?php
		if ( apply_filters( 'stm_is_motorcycle', false ) ) {
			get_template_part( 'partials/single-car-motorcycle/tabs' );
		}
		?>

		<div class="container">
			<?php do_action( 'stm_listings_load_template', 'filter/inventory/main' ); ?>
		</div> <!--cont-->

		<?php
		$recaptcha_enabled    = apply_filters( 'motors_vl_get_nuxy_mod', 0, 'enable_recaptcha' );
		$recaptcha_public_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_public_key' );
		$recaptcha_secret_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_secret_key' );
		if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) {
			wp_enqueue_script( 'stm_grecaptcha' );
		}
		?>
	</div> <!--single car page-->
<?php
get_footer();
