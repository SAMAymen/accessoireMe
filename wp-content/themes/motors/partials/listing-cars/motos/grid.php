<?php
global $post, $wp_query;

$listing_id = get_the_ID();

$price                = $post->__get( 'price' );
$sale_price           = get_post_meta( $listing_id, 'sale_price', true );
$car_price_form_label = get_post_meta( $listing_id, 'car_price_form_label', true );
$mileage              = get_post_meta( $listing_id, 'mileage', true );
$taxonomies           = apply_filters( 'stm_get_taxonomies', array() );
$categories           = wp_get_post_terms( $listing_id, array_values( $taxonomies ) );
$classes              = array();
$show_compare         = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );
$cars_in_compare      = apply_filters( 'stm_get_compared_items', array() );
$in_compare           = '';
$car_compare_status   = esc_html__( 'Add to compare', 'motors' );
$sold_listing         = get_post_meta( $listing_id, 'car_mark_as_sold', true );
$quantity_listings    = get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true );

if ( ! empty( $args ) && ! empty( $args['quantity_per_row'] ) ) {
	$quantity_listings = $args['quantity_per_row'];
}

$col = ( ! empty( $quantity_listings ) ) ? 12 / $quantity_listings : 4;
if ( empty( $quantity_listings ) ) {
	$col = 6;
}

$col_class = ( ! empty( $class ) && strpos( $class[0], 'col-' ) !== false ) ? '' : 'col-md-' . $col . ' col-sm-' . $col . ' col-xs-12 col-xxs-12 ';

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$classes[] = $category->slug . '-' . $category->term_id;
	}
}

if ( ! empty( $cars_in_compare ) && in_array( $listing_id, $cars_in_compare, true ) ) {
	$in_compare         = 'active';
	$car_compare_status = esc_html__( 'Remove from compare', 'motors' );
}

// remove "special" if the listing is sold.
if ( ! empty( $sold_listing ) ) {
	delete_post_meta( $listing_id, 'special_car' );
}

/* is listing active or sold? */
if ( ! empty( $sold_listing ) && 'on' === $sold_listing ) {
	$classes[] = 'listing_is_sold';
} else {
	$classes[] = 'listing_is_active';
}

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

if ( ! empty( $mileage ) ) {
	$data['data_mileage'] = $mileage;
}

if ( ! empty( $args['custom_img_size'] ) ) {
	$data['custom_img_size'] = $args['custom_img_size'];
}
?>

<div
	class="<?php echo esc_attr( $col_class ); ?> stm-isotope-listing-item stm_moto_single_grid_item all <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-price="<?php echo esc_attr( $data['data_price'] ); ?>"
	data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
	data-mileage="<?php echo esc_attr( $data['data_mileage'] ); ?>"
>
	<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="rmv_txt_drctn">
		<div class="image">
			<?php
			get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
			get_template_part( 'partials/listing-cars/motos/image', '', $data );
			?>
			<div class="stm_moto_hover_unit">
				<!--Compare-->
				<?php
				if ( ! empty( $show_compare ) ) :
					?>
					<div
						class="stm-listing-compare heading-font stm-compare-directory-new <?php echo esc_attr( $in_compare ); ?>"
						data-post-type="<?php echo esc_attr( get_post_type( $listing_id ) ); ?>"
						data-id="<?php echo esc_attr( $listing_id ); ?>"
						data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id, false ) ); ?>"
					>
						<i class="stm-service-icon-compare-new"></i>
						<?php
						if ( 6 === $col ) {
							esc_html_e( 'Compare', 'motors' );
						}
						?>
					</div>
					<?php
				endif;

				stm_get_boats_image_hover( $listing_id );
				?>
				<div class="heading-font">
					<?php if ( empty( $car_price_form_label ) ) : ?>
						<?php if ( ! empty( $price ) && ! empty( $sale_price ) && $price !== $sale_price ) : ?>
							<div class="price discounted-price">
								<div class="regular-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
								<div class="sale-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></div>
							</div>
						<?php elseif ( ! empty( $price ) ) : ?>
							<div class="price">
								<div class="normal-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="price archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
					<div class="normal-price">
						<a href="#" class="rmv_txt_drctn">
							<span class="heading-font"><?php echo esc_html( $car_price_form_label ); ?></span>
						</a>
					</div>
				</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="listing-car-item-meta">
			<div class="car-meta-top heading-font clearfix">
				<div class="car-title">
					<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id, true ) ); ?>
				</div>
			</div>
			<?php get_template_part( 'partials/listing-cars/motos/data' ); ?>
		</div>
	</a>
</div>
