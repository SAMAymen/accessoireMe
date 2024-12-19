<div class="row">
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="stm-single-car-content">
			<h1 class="title h2"><?php the_title(); ?></h1>

			<!--Actions-->
			<?php do_action( 'stm_listings_load_template', 'single-car/car-actions' ); ?>

			<!--Gallery-->
			<?php do_action( 'stm_listings_load_template', 'single-car/car-gallery' ); ?>

			<!--Car Gurus if is style BANNER-->
			<?php
			if ( strpos( apply_filters( 'motors_vl_get_nuxy_mod', 'STYLE1', 'carguru_style' ), 'BANNER' ) !== false ) {//phpcs:ignore
				do_action( 'stm_listings_load_template', 'single-car/car-gurus' );}
			?>

			<?php
			//CAR DATA
			$data = apply_filters( 'stm_get_single_car_listings', array() );
			if ( ! empty( $data ) ) :
				?>
				<div class="stm-car-listing-data-single stm-border-top-unit">
					<div class="title heading-font"><?php esc_html_e( 'Car Details', 'motors' ); ?></div>
				</div>

				<?php get_template_part( 'partials/single-car-listing/car-data' ); ?>
			<?php endif; ?>


			<?php
			$features = get_post_meta( get_the_id(), 'additional_features', true );
			if ( ! empty( $features ) ) :
				?>
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e( 'Features', 'motors' ); ?></div>
				</div>
				<?php get_template_part( 'partials/single-car-listing/car-features' ); ?>

				<?php
			endif;

			/*<!--Calculator-->*/
			do_action( 'stm_listings_load_template', 'single-car/car-calculator' );

			?>

			<div class="stm-car-listing-data-single stm-border-top-unit">
				<div class="title heading-font"><?php echo esc_html__( 'Seller Note', 'motors' ); ?></div>
			</div>
			<?php echo apply_filters( 'stm_get_listing_seller_note', get_the_ID() );//phpcs:ignore ?>
		</div>
	</div>

	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="stm-single-car-side">
			<?php
			if ( is_active_sidebar( 'stm_listing_car' ) ) {
				dynamic_sidebar( 'stm_listing_car' );
			} else {
				/*<!--Prices-->*/
				do_action( 'stm_listings_load_template', 'single-car/car-price' );

				/*<!--Data-->*/
				do_action( 'stm_listings_load_template', 'single-car/car-data' );

				/*<!--Rating Review-->*/
				do_action( 'stm_listings_load_template', 'single-car/car-review_rating' );

				/*<!--MPG-->*/
				do_action( 'stm_listings_load_template', 'single-car/car-mpg' );
			}
			?>

		</div>
	</div>
</div>
