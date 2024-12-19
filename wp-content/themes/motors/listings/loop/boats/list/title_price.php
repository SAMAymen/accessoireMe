<?php
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );

?>
<div class="meta-top">
	<?php if ( ! empty( $price ) && ! empty( $sale_price ) && $price !== $sale_price ) : ?>
		<div class="price discounted-price">
			<?php if ( ! empty( $car_price_form_label ) ) : ?>
				<div class="normal-price">
					<a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal"
					data-target="#get-car-price"
					data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
					data-id="<?php echo get_the_ID(); ?>">
						<span class="heading-font price-form-label"><?php echo esc_attr( $car_price_form_label ); ?></span>
					</a>
				</div>
			<?php else : ?>
				<div class="regular-price">
					<?php if ( ! empty( $special_price_label ) ) : ?>
						<span class="label-price"><?php echo esc_attr( $special_price_label ); ?></span>
					<?php endif; ?>
					<?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
				</div>

				<div class="sale-price">
					<?php if ( ! empty( $regular_price_label ) ) : ?>
						<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
					<?php endif; ?>
					<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( ! empty( $price ) ) : ?>
		<div class="price">
			<div class="normal-price">
				<?php if ( ! empty( $car_price_form_label ) ) : ?>
					<a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal"
					data-target="#get-car-price"
					data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
					data-id="<?php echo get_the_ID(); ?>">
						<span class="heading-font"><?php echo esc_attr( $car_price_form_label ); ?></span>
					</a>
				<?php else : ?>
					<?php if ( ! empty( $regular_price_label ) ) : ?>
						<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
					<?php endif; ?>
					<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="title heading-font">
		<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
			<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ); ?>
		</a>
	</div>
</div>
