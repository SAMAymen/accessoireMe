<?php
$price                = get_post_meta( get_the_id(), 'price', true );
$sale_price           = get_post_meta( get_the_id(), 'sale_price', true );
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );
$regular_price_label  = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label  = get_post_meta( get_the_ID(), 'special_price_label', true );

$data = array(
	'data_price' => 0,
);

if ( ! empty( $price ) ) {
	$data['data_price'] = $price;
}

if ( ! empty( $sale_price ) ) {
	$data['data_price'] = $sale_price;
}

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

if ( ! empty( $args['custom_img_size'] ) ) {
	$data['custom_img_size'] = $args['custom_img_size'];
}

$taxonomies = apply_filters( 'stm_get_taxonomies', array() );
foreach ( $taxonomies as $val ) {
	$tax_data = stm_get_taxonomies_with_type( $val );
	if ( ! empty( $tax_data['numeric'] ) && ! empty( $tax_data['slider'] ) ) {
		$value                       = get_post_meta( get_the_id(), $val, true );
		$replaced                    = str_replace( '-', '__', $val );
		$data[ 'data_' . $replaced ] = $value;
		$data['atts'][]              = $replaced;
	}
}

?>

<?php if ( ! apply_filters( 'stm_is_magazine', true ) ) : ?>
	<?php do_action( 'stm_listings_load_template', 'loop/classified/grid/start', $data ); ?>

		<?php do_action( 'stm_listings_load_template', 'loop/default/grid/image', $data ); ?>

		<div class="listing-car-item-meta">

			<?php
			do_action(
				'stm_listings_load_template',
				'loop/default/grid/title_price',
				array(
					'price'                => $price,
					'sale_price'           => $sale_price,
					'car_price_form_label' => $car_price_form_label,
				)
			);
			?>

			<?php
			if ( has_action( 'stm_multilisting_load_template' ) ) {
				do_action( 'stm_multilisting_load_template', 'templates/grid-listing-data' );
			} else {
				do_action( 'stm_listings_load_template', 'loop/default/grid/data' );
			}
			?>

		</div>
	</a>
</div>
<?php else :

	get_template_part( 'partials/listing-cars/listing-grid-loop-magazine' );

endif; ?>
