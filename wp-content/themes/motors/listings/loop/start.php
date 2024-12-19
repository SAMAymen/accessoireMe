<?php
if ( ! empty( $modern ) ) :
	$classes           = array();
	$taxonomies        = array();
	$number_taxonomies = array();
	$number_data       = array();

	foreach ( apply_filters( 'stm_get_taxonomies', array() ) as $taxonomy_name => $taxonomy_slug ) {
		$tax_data   = apply_filters( 'stm_vl_get_all_by_slug', array(), $taxonomy_slug );
		$is_numeric = ( ! empty( $tax_data['numeric'] ) );

		if ( ! $is_numeric ) {
			$taxonomies[ $taxonomy_name ] = $taxonomy_slug;
		} else {
			$number_taxonomies[ $taxonomy_name ] = $taxonomy_slug;
		}
	}

	$categories = wp_get_post_terms( get_the_ID(), array_values( $taxonomies ) );

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$classes[] = $category->slug . '-' . $category->term_id;
		}
	}

	foreach ( $number_taxonomies as $number_taxonomy_name => $number_taxonomy_slug ) {

		$tax_data = apply_filters( 'stm_vl_get_all_by_slug', array(), $number_taxonomy_slug );
		$prefix   = ( empty( $tax_data['slider'] ) ) ? '-numeric' : '';
		$value    = get_post_meta( get_the_ID(), $number_taxonomy_slug, true );
		$value    = ( ! empty( $value ) && is_numeric( $value ) ) ? $value : 0;

		$number_data[] = "data{$prefix}-{$number_taxonomy_slug}={$value}";
		$classes[]     = "data-numeric-{$number_taxonomy_slug}{$value}";

	}

	/* is listing active or sold? */
	$sold = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
	if ( ! empty( $sold ) && 'on' === $sold ) {
		$classes[] = 'listing_is_sold';
	} else {
		$classes[] = 'listing_is_active';
	}

	/*Price*/
	$price      = get_post_meta( get_the_id(), 'price', true );
	$sale_price = get_post_meta( get_the_id(), 'sale_price', true );
	$data_price = '0';

	if ( ! empty( $price ) ) {
		$data_price = $price;
	}

	if ( ! empty( $sale_price ) ) {
		$data_price = $sale_price;
	}

	/*Mileage*/
	$mileage = get_post_meta( get_the_id(), 'mileage', true );

	$data_mileage = '0';

	if ( ! empty( $mileage ) ) {
		$data_mileage = $mileage;
	}

	// Lat lang location
	$stm_to_lng = get_post_meta( get_the_ID(), 'stm_lng_car_admin', true );
	$stm_to_lat = get_post_meta( get_the_ID(), 'stm_lat_car_admin', true );

	$distance = '';
	if ( stm_location_validates() ) {
		$stm_from_lng = floatval( $_GET['stm_lng'] ); //phpcs:ignore
		$stm_from_lat = floatval( $_GET['stm_lat'] ); //phpcs:ignore
		if ( ! empty( $stm_to_lng ) && ! empty( $stm_to_lat ) ) {
			$distance = apply_filters( 'stm_calculate_distance_between_two_points', '', $stm_from_lat, $stm_from_lng, $stm_to_lat, $stm_to_lng );
		}
	}

	if ( ! empty( $listing_classes ) ) {
		$classes = array_merge( $classes, $listing_classes );
	}

	?>
	<div
	class="listing-list-loop stm-listing-directory-list-loop stm-isotope-listing-item all <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-price="<?php echo esc_attr( $data_price ); ?>"
	data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
	data-mileage="<?php echo esc_attr( $data_mileage ); ?>"
	<?php echo esc_attr( implode( ' ', $number_data ) ); ?>
	<?php if ( $distance ) : ?>
	data-distance="<?php echo esc_attr( floatval( $distance ) ); ?>"
<?php endif; ?>
	>

<?php else : ?>

	<div class="listing-list-loop stm-listing-directory-list-loop stm-isotope-listing-item">

	<?php
endif;
