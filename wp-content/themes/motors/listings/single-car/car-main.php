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
			if ( strpos( apply_filters( 'motors_vl_get_nuxy_mod', 'STYLE1', 'carguru_style' ), 'BANNER' ) !== false ) {
				do_action( 'stm_listings_load_template', 'single-car/car-gurus' );
			}

			the_content();

			/*<!--Calculator-->*/
			do_action( 'stm_listings_load_template', 'single-car/car-calculator' );
			?>
		</div>
	</div>

	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="stm-single-car-side">
			<?php
			if ( is_active_sidebar( 'stm_listing_car' ) ) {
				dynamic_sidebar( 'stm_listing_car' );
			} else {
				if ( get_theme_mod( 'show_vin_history_btn', false ) ) {
					do_action( 'stm_single_show_vin_history_btn' );
				}
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
