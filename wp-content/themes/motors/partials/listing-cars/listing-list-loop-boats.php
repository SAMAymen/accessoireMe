<?php
$taxonomies = apply_filters( 'stm_get_taxonomies', array() );

$categories = wp_get_post_terms( get_the_ID(), array_values( $taxonomies ) );

$classes = array();
$datas   = array();

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$classes[] = $category->slug . '-' . $category->term_id;
		$datas[]   = 'data-' . $category->taxonomy . '="' . $category->name . '"';
	}

	$loc = get_post_meta( get_the_id(), 'stm_car_location', true );
	if ( empty( $loc ) ) {
		$loc = '';
	}
	$datas[] = 'data-stm_car_location="' . $loc . '"';
}

$datas_num_arr = array();
$datas_num     = apply_filters( 'stm_get_car_archive_listings', array() );

if ( ! empty( $datas_num ) ) {
	foreach ( $datas_num as $data_num ) {
		if ( ! empty( $data_num['numeric'] ) && $data_num['numeric'] ) {
			$val = get_post_meta( get_the_id(), $data_num['slug'], true );
			if ( empty( $val ) ) {
				$val = '';
			}

			$datas_num_arr[] = 'data-' . $data_num['slug'] . '="' . intval( $val ) . '"';
		}
	}
}

$mileage = get_post_meta( get_the_id(), 'mileage', true );

$data_mileage = '0';

if ( ! empty( $mileage ) ) {
	$data_mileage = $mileage;
}

/*Price*/
$price      = get_post_meta( get_the_id(), 'price', true );
$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

$data_price = '0';

if ( ! empty( $price ) ) {
	$data_price = $price;
}

if ( ! empty( $sale_price ) ) {
	$data_price = $sale_price;
}

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

$prices = array(
	'price'                => $price,
	'sale_price'           => $sale_price,
	'car_price_form_label' => $car_price_form_label,
);

?>

<div
		class="listing-list-loop stm-isotope-listing-item all <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		data-price="<?php echo esc_attr( $data_price ); ?>"
		data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
		data-mileage="<?php echo esc_attr( $data_mileage ); ?>"
	<?php echo esc_attr( implode( ' ', $datas ) ); ?>
	<?php echo esc_attr( implode( ' ', $datas_num_arr ) ); ?>
>
	<?php do_action( 'stm_listings_load_template', 'loop/boats/list/image' ); ?>

	<div class="content">
		<?php do_action( 'stm_listings_load_template', 'loop/boats/list/title_price', $prices ); ?>

		<?php do_action( 'stm_listings_load_template', 'loop/boats/list/options' ); ?>
	</div>

	<a href="<?php the_permalink(); ?>" class="stm-car-view-more button visible-xs">
		<?php esc_html_e( 'View more', 'motors' ); ?>
	</a>
</div>
