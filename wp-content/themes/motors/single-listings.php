<?php
if ( apply_filters( 'stm_equip_single', false ) ) {
	do_action( 'stm_equip_single_template' );
} else {
	get_header();

	if ( ! apply_filters( 'stm_is_aircrafts', true ) ) :

		get_template_part( 'partials/page_bg' );
		get_template_part( 'partials/title_box' );
		?>
		<div class="stm-single-car-page single-listings-template">
			<?php
			if ( apply_filters( 'stm_is_motorcycle', false ) ) {
				get_template_part( 'partials/single-car-motorcycle/tabs' );
			}

			$recaptcha_enabled    = apply_filters( 'motors_vl_get_nuxy_mod', 0, 'enable_recaptcha' );
			$recaptcha_public_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_public_key' );
			$recaptcha_secret_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_secret_key' );
			if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) {
				wp_enqueue_script( 'stm_grecaptcha' );
			}
			?>

			<div class="container">
				<?php
				if ( have_posts() ) :
					$template = '';
					if ( boolval( apply_filters( 'is_listing', array( 'listing', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_five' ) ) ) ) {
						$template = 'partials/single-car-listing/car-main';
					} elseif ( apply_filters( 'stm_is_listing_four', false ) ) {
						$template = 'partials/single-car-listing/car-main-four';
					} elseif ( apply_filters( 'stm_is_boats', false ) ) {
						$template = 'partials/single-car-boats/boat-main';
					} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
						$template = 'partials/single-car-motorcycle/car-main';
					}

					while ( have_posts() ) :
						the_post();

						$vc_status = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );

						if ( class_exists( 'Elementor\Plugin' ) && class_exists( '\MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager' ) ) {
							\MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager::motors_display_template();
						} elseif ( 'true' === $vc_status ) {
							the_content();
						} else {
							if ( ! empty( $template ) ) {
								get_template_part( $template );
							} else {
								do_action( 'stm_listings_load_template', 'single-car/car-main' );
							}
						}

					endwhile;

				endif;
				?>
			</div> <!-- container -->

		</div> <!--single car page-->

		<?php
	else :
		echo '<div class="container">';
		get_template_part( 'partials/page_bg' );
		get_template_part( 'partials/single-aircrafts/title' );
		echo '</div>';

		get_template_part( 'partials/single-aircrafts/gallery' );
		?>
		<div class="stm-single-car-page">
			<div class="container">
				<?php
				if ( have_posts() ) :

					$template = 'partials/single-aircrafts/aircrafts-main';

					while ( have_posts() ) :
						the_post();

						$vc_status = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );

						if ( 'true' === $vc_status ) {
							the_content();
						} else {
							get_template_part( $template );
						}

					endwhile;

				endif;
				?>
			</div>
		</div>
		<?php
	endif;

	get_footer();
}
