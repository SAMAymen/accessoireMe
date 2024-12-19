<div class="stm-listing-fav-loop">
	<?php if ( 'draft' === get_post_status( get_the_ID() ) ) { ?>
		<div class="stm-car-overlay-disabled"></div>
		<div class="stm_edit_pending_car">
			<h4><?php esc_html_e( 'Disabled', 'motors' ); ?></h4>
			<div class="stm-dots"><span></span><span></span><span></span></div>
		</div>
		<?php
	} elseif ( 'pending' === get_post_status( get_the_ID() ) ) {
		?>
		<div class="stm-car-overlay-disabled"></div>
		<div class="stm_edit_pending_car">
			<h4><?php esc_html_e( 'Under review', 'motors' ); ?></h4>
			<div class="stm-dots"><span></span><span></span><span></span></div>
		</div>
	<?php } get_template_part( 'partials/listing-cars/listing-list-directory', 'loop' ); ?>
</div>
