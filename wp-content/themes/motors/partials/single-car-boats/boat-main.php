<div class="row">
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="stm-single-car-content">

			<?php get_template_part( 'partials/single-car-boats/boat', 'top' ); ?>

			<div class="stm-boats-featured-image">
				<?php get_template_part( 'partials/single-car-boats/boat', 'image' ); ?>
			</div>

			<!--Data-->
			<div class="stm-boats-data">
				<?php get_template_part( 'partials/single-car-boats/boats', 'data' ); ?>
			</div>

			<!--Gallery-->
			<?php get_template_part( 'partials/single-car-boats/boats', 'gallery' ); ?>

			<?php the_content(); ?>
		</div>
	</div>

	<div class="col-md-3 col-sm-12 col-xs-12">
		<?php if ( is_active_sidebar( 'stm_boats_car' ) ) { ?>
			<div class="stm-single-listing-car-sidebar">
				<?php dynamic_sidebar( 'stm_boats_car' ); ?>
			</div>
		<?php } ?>
	</div>
</div>
