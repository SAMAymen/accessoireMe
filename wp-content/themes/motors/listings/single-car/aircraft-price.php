<?php
$price                     = get_post_meta( get_the_ID(), 'price', true );
$sale_price                = get_post_meta( get_the_ID(), 'sale_price', true );
$car_price_form            = get_post_meta( get_the_ID(), 'car_price_form', true );
$car_price_form_label      = get_post_meta( get_the_ID(), 'car_price_form_label', true );
$regular_price_description = ( ! empty( get_post_meta( get_the_ID(), 'regular_price_description', true ) ) ) ? get_post_meta( get_the_ID(), 'regular_price_description', true ) : esc_html__( 'Incl Taxes &amp; Checkup', 'motors' );
?>

<div class="aircraft-price-wrap">
	<?php if ( empty( $car_price_form_label ) ) : ?>
		<div class="left">
			<?php if ( empty( $sale_price ) ) : ?>
				<span class="h3"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
			<?php else : ?>
				<span class="h4"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
				<span class="h3"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
			<?php endif; ?>
		</div>
		<div class="right">
			<div class="price-description-single"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_description, 'Regular Price Description' ) ); ?></div>
		</div>
	<?php else : ?>
		<div class="custom-label">
			<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
				<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
			<?php endif; ?>
			<center>
				<span class="h3">
					<?php echo esc_html( $car_price_form_label ); ?>
				</span>
			</center>
			<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>


