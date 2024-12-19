<?php
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );

$price      = get_post_meta( get_the_ID(), 'price', true );
$sale_price = get_post_meta( get_the_ID(), 'sale_price', true );

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

$data = array(
	'data_price'   => 0,
	'data_mileage' => 0,
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

$mileage = get_post_meta( get_the_ID(), 'mileage', true );

if ( ! empty( $mileage ) ) {
	$data['data_mileage'] = $mileage;
}

if ( ! empty( $args['custom_img_size'] ) ) {
	$data['custom_img_size'] = $args['custom_img_size'];
}

if ( ! empty( $args['quantity_per_row'] ) ) {
	$data['quantity_per_row'] = $args['quantity_per_row'];
}
?>

<?php do_action( 'stm_listings_load_template', 'loop/classified/grid/start', $data ); ?>

		<?php do_action( 'stm_listings_load_template', 'loop/classified/grid/image', $data ); ?>

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

			if ( has_action( 'stm_multilisting_load_template' ) ) :
				do_action( 'stm_multilisting_load_template', 'templates/grid-listing-data' );
			else :
				do_action( 'stm_listings_load_template', 'loop/classified/grid/data' );
			endif;
			?>

		</div>
	</a>
</div>
