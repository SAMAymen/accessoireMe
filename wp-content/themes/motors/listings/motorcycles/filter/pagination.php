
<div class="row stm-ajax-pagination classic-filter-pagination">

	<div class="col-md-12">
		<div class="clearfix">
			<div class="stm-blog-pagination">
				<?php
				echo paginate_links( //phpcs:ignore
					array(
						'type'      => 'list',
						'prev_text' => '<i class="fas fa-angle-left"></i>',
						'next_text' => '<i class="fas fa-angle-right"></i>',
					)
				);
				?>
			</div>
			<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
				<div class="stm-motorcycle-per-page">
					<?php get_template_part( 'partials/listing-layout-parts/items-per', 'page' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
