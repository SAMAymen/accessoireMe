<?php get_header(); ?>
	<?php
	$container_class = 'stm-single-post';

	// check for multilisting
	$is_multilisting_single = false;
	if ( stm_is_multilisting() ) {
		$types = apply_filters( 'stm_listings_multi_type', array() );
		if ( ! empty( $types ) && is_singular( $types ) ) {
			$is_multilisting_single = true;
			$container_class        = 'stm-single-car-page';
		}
	}

	if ( false === $is_multilisting_single ) {
		if ( apply_filters( 'stm_is_magazine', false ) ) {
			get_template_part( 'partials/magazine/content/breadcrumbs' );
		} else {
			get_template_part( 'partials/page_bg' );
			get_template_part( 'partials/title_box' );
		}
	}
	?>
	<div id="post-<?php get_the_ID(); ?>" <?php post_class(); ?>>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="container">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					if ( true === $is_multilisting_single ) {

						$vc_status = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );

						if ( class_exists( 'Elementor\Plugin' ) && class_exists( '\MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager' ) ) {
							\MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager::motors_display_template();
						} elseif ( 'true' === $vc_status ) {
							the_content();
						} else {
							$template = '';
							if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
								$template = 'partials/single-car-listing/car-main';
							} elseif ( apply_filters( 'stm_is_listing_four', false ) ) {
								$template = 'partials/single-car-listing/car-main-four';
							} elseif ( apply_filters( 'stm_is_boats', false ) ) {
								$template = 'partials/single-car-boats/boat-main';
							} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
								$template = 'partials/single-car-motorcycle/car-main';
							} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
								$template = 'partials/single-aircrafts/aircrafts-main';
							}

							if ( ! empty( $template ) ) {
								get_template_part( $template );
							} else {
								do_action( 'stm_listings_load_template', 'single-car/car-main' );
							}
						}
					} else {
						if ( ! apply_filters( 'stm_is_magazine', true ) ) {
							get_template_part( 'partials/blog/content' );
						} else {
							get_template_part( 'partials/magazine/main' );
						}
					}
				endwhile;
			endif;
			?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
