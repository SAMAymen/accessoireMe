<?php


if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
	global $product;
	$compare = new YITH_Woocompare_Frontend();

	?>
	<div class="prod-compare">
		<?php $compare->add_compare_link( $product->get_id() ); ?>
	</div>
<?php } ?>
