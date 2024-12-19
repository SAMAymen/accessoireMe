<?php
if ( apply_filters( 'stm_is_motorcycle', false ) ) :
	do_action( 'stm_listings_load_template', 'motorcycles/filter/pagination' );
else :
	?>
	<div class="stm_ajax_pagination stm-blog-pagination">
		<?php
		echo paginate_links(//phpcs:ignore
			array(
				'type'      => 'list',
				'prev_text' => '<i class="fas fa-angle-left"></i>',
				'next_text' => '<i class="fas fa-angle-right"></i>',
			)
		);
		?>
	</div>
<?php endif; ?>
