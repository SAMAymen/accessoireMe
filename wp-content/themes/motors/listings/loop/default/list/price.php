<?php
// Price.
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );

$price      = get_post_meta( get_the_id(), 'price', true );
$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

if ( empty( $car_price_form_label ) ) {
	if ( ! empty( $price ) && ! empty( $sale_price ) && $price !== $sale_price ) :?>
		<div class="price discounted-price">
			<div class="regular-price">
				<?php if ( ! empty( $regular_price_label ) ) : ?>
					<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
				<?php endif; ?>
				<?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
			</div>

			<div class="sale-price">
				<?php if ( ! empty( $special_price_label ) ) : ?>
					<span class="label-price"><?php echo esc_attr( $special_price_label ); ?></span>
				<?php endif; ?>
				<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
			</div>
		</div>
	<?php elseif ( ! empty( $price ) ) : ?>
		<div class="price">
			<div class="normal-price">
				<?php if ( ! empty( $regular_price_label ) ) : ?>
					<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
				<?php endif; ?>
				<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
			</div>
		</div>
	<?php else : ?>
		<div class="price-holder"></div>
	<?php endif; ?>
<?php } else { ?>
	<div class="price">
		<span class="rmv_txt_drctn archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_html( get_the_title( get_the_ID() ) ); ?>" data-id="<?php echo get_the_ID(); ?>">
			<span class="heading-font price-form-label"><?php echo esc_attr( $car_price_form_label ); ?></span>
		</span>
	</div>
<?php } ?>
