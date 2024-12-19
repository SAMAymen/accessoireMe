<div class="row">
	<div class="col-md-9 col-md-push-3 col-sm-push-0 col-sm-12 col-xs-12">
		<div class="stm-single-car-content">
			<!--Title and price-->
			<?php get_template_part('partials/single-car-motorcycle/car-price', 'title'); ?>

			<!--Gallery-->
			<?php get_template_part('partials/single-car-motorcycle/car', 'gallery'); ?>

			<?php the_content(); ?>
		</div>
	</div>

	<div class="col-md-3 col-md-pull-9 col-sm-pull-0 col-sm-12 col-xs-12">
		<div class="stm-single-car-side">
			<!--Data-->
			<?php get_template_part('partials/single-car-motorcycle/car', 'data'); ?>

			<!--Links-->
			<?php get_template_part('partials/single-car-motorcycle/car', 'links'); ?>
		</div>
	</div>
</div>