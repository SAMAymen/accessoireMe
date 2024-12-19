<?php
$hide_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'hide_price_labels' );

if ( $hide_labels ) {
	$classes[] = 'stm-listing-no-price-labels';
}

$classes = array();

$classes[] = 'stm-special-car-top-' . get_post_meta( get_the_ID(), 'special_car', true );

if ( empty( $modern_filter ) ) {
	$modern_filter = false;
}

do_action(
	'stm_listings_load_template',
	'loop/start',
	array(
		'modern'          => $modern_filter,
		'listing_classes' => $classes,
	)
);

?>
<?php do_action( 'stm_listings_load_template', 'loop/classified/list/image-inventory-no-filter' ); ?>

<div class="content">
	<?php do_action( 'stm_listings_load_template', 'loop/classified/list/title_price', array( 'hide_labels' => $hide_labels ) ); ?>

	<?php do_action( 'stm_listings_load_template', 'loop/classified/list/options' ); ?>

	<div class="meta-bottom">
		<?php get_template_part( 'partials/listing-cars/listing-directive-list-loop', 'actions' ); ?>
	</div>

</div>
</div>
