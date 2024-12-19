<?php
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );
$price                = get_post_meta( get_the_ID(), 'price', true );
$sale_price           = get_post_meta( get_the_ID(), 'sale_price', true );
if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

?>

<div class="price_wrap">
	<?php
	if ( ! empty( $car_price_form_label ) ) :
		?>
		<div class="price">
			<div class="normal-price"><?php echo esc_html( $car_price_form_label ); ?></div>
		</div>
	<?php else : ?>
		<?php if ( ! empty( $price ) && ! empty( $sale_price ) && $price !== $sale_price ) : ?>
			<div class="price discounted-price">
				<div class="regular-price"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
				<div class="sale-price"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></div>
			</div>
		<?php elseif ( ! empty( $price ) ) : ?>
			<div class="price">
				<div class="normal-price"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
