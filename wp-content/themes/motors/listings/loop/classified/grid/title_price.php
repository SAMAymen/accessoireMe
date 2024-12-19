<div class="car-meta-top heading-font clearfix">
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
		<div class="price">
			<div class="normal-price"><?php echo esc_attr( $car_price_form_label ); ?></div>
		</div>
	<?php endif; ?>
	<?php
	if ( empty( $title_max_length ) ) {
		$title_max_length = apply_filters( 'motors_vl_get_nuxy_mod', 44, 'grid_title_max_length' );
	}
	?>
	<div class="car-title" data-max-char="<?php echo esc_attr( $title_max_length ); ?>">
		<?php
		$show_title_two_params_as_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );
		if ( $show_title_two_params_as_labels ) {
			echo apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $show_title_two_params_as_labels ); //phpcs:ignore
		} else {
			echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) );

		}

		?>
	</div>
</div>
