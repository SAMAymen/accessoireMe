<?php
if ( apply_filters( 'stm_is_dealer_two', false ) ) {
	$selling_online_global = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' );
	$sell_online           = ( $selling_online_global ) ? ! empty( get_post_meta( get_the_ID(), 'car_mark_woo_online', true ) ) : false;
}
?>
<div class="car-meta-top heading-font clearfix">
	<?php if ( apply_filters( 'stm_is_dealer_two', false ) && $sell_online && empty( $car_price_form_label ) ) : ?>
		<?php
		if ( ! empty( $sale_price ) ) {
			$price = $sale_price;
		}
		?>
		<div class="sell-online-wrap price">
			<div class="normal-price">
				<span class="normal_font"><?php echo esc_html__( 'BUY ONLINE', 'motors' ); ?></span>
				<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
			</div>
		</div>
	<?php else : ?>
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
				<div class="normal-price"><?php echo esc_html( $car_price_form_label ); ?></div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php
	if ( empty( $title_max_length ) ) {
		$title_max_length = apply_filters( 'motors_vl_get_nuxy_mod', 44, 'grid_title_max_length' );
	}
	?>
	<div class="car-title" data-max-char="<?php echo esc_attr( $title_max_length ); ?>">
		<?php
		if ( ! apply_filters( 'stm_is_listing_three', false ) ) {
			echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) );
		} else {
			echo wp_kses_post( trim( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), true ) ) );
		}
		?>
	</div>
</div>
