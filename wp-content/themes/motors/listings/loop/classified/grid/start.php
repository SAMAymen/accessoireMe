<?php
/**
 * @var $data_price
 * @var array $class
*/

global $wp_query;

$categories = wp_get_post_terms( get_the_ID(), array_values( apply_filters( 'stm_get_taxonomies', array() ) ) );

$classes = array();
if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$classes[] = $category->slug . '-' . $category->term_id;
	}
}

/* is listing active or sold? */
$sold = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
if ( ! empty( $sold ) && 'on' === $sold ) {
	$classes[] = 'listing_is_sold';
} else {
	$classes[] = 'listing_is_active';
}

$quantity_listings = get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true );
if ( ! empty( $quantity_per_row ) ) {
	$quantity_listings = $quantity_per_row;
}

if ( ! empty( $wp_query->get( 'quantity_per_row' ) ) ) {
	$quantity_listings = $wp_query->get( 'quantity_per_row' );
}

$col = ( ! empty( $quantity_listings ) ) ? 12 / $quantity_listings : 4;

if ( apply_filters( 'stm_is_dealer_two', false ) && ! apply_filters( 'stm_is_elementor_dealer_two', false ) && empty( $quantity_listings ) ) {
	$col = 6;
}

$col_class = sprintf( 'col-md-%1$d col-sm-%1$d col-xs-12 col-xxs-12 ', $col );

if ( ! empty( $class ) && is_array( $class ) ) {
	if ( strpos( $class[0], 'col-' ) !== false ) {
		$col_class = '';
	}

	$classes = array_merge( $classes, $class );
}

?>
<div
	class="<?php echo esc_attr( $col_class ); ?> stm-directory-grid-loop stm-isotope-listing-item all <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-price="<?php echo esc_attr( $data_price ); ?>"
	data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
	data-mileage="
	<?php
	if ( ! empty( $data_mileage ) ) {
		echo esc_attr( $data_mileage );
	}
	?>
">
	<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="rmv_txt_drctn">
