<?php


if ( class_exists( 'YITH_WCWL_Shortcode' ) ) {
	?>
	<div class="prod-wishlist heading-font">
		<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
	</div>
	<?php
}
