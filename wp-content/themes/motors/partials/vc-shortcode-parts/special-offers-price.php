<?php
if ( ! empty( $args['car_price_form_label'] ) ) :
	?>
	<div class="price heading-font">
		<div class="normal-price"><?php echo esc_attr( $args['car_price_form_label'] ); ?></div>
	</div>
	<?php
else :
	?>
	<?php
	if ( ! empty( $args['price'] ) && ! empty( $args['sale_price'] ) ) :
		?>
		<div class="price heading-font discounted-price">
			<div class="regular-price">
				<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $args['price'] ) ); ?>
			</div>
			<div class="sale-price">
				<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $args['sale_price'] ) ); ?>
			</div>
		</div>
		<?php
	elseif ( ! empty( $args['price'] ) ) :
		?>
		<div class="price heading-font">
			<div class="normal-price">
				<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $args['price'] ) ); ?>
			</div>
		</div>
		<?php
	endif;
endif;
