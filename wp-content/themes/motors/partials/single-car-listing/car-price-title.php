<?php
$price                = get_post_meta( get_the_ID(), 'price', true );
$sale_price           = get_post_meta( get_the_ID(), 'sale_price', true );
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );
$regular_price_label  = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label  = get_post_meta( get_the_ID(), 'special_price_label', true );

$asSold = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );

?>
<div class="stm-listing-single-price-title heading-font clearfix">
	<?php if ( ! apply_filters( 'stm_is_dealer_two', true ) ) : ?>
		<?php if ( ! $asSold ) : ?>
			<?php if ( ! empty( $car_price_form_label ) ) : ?>
				<a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" data-id="<?php echo get_the_ID(); ?>">
					<div class="price"><?php echo esc_html( $car_price_form_label ); ?></div>
				</a>
			<?php else : ?>
				<?php if ( ! empty( $price ) ) : ?>
					<?php if ( empty( $sale_price ) ) : ?>
						<div class="price"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
					<?php else : ?>
						<div class="price discounted-price">
							<div class="regular-price">
								<?php if ( ! empty( $special_price_label ) ) : ?>
									<span class="label-price"><?php echo esc_html( $regular_price_label ); ?></span>
								<?php endif; ?>
								<span class="value"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
							</div>
							<div class="sale-price">
								<?php if ( ! empty( $regular_price_label ) ) : ?>
									<span class="label-price"><?php echo esc_html( $special_price_label ); ?></span>
								<?php endif; ?>
								<span class="value"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php else : ?>
			<div class="price"><?php echo esc_html__( 'Sold', 'motors' ); ?></div>
		<?php endif; ?>
	<?php endif; ?>
	<div class="stm-single-title-wrap">
		<h1 class="title">
			<?php
			$as_label = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );
			echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $as_label ) );
			?>
		</h1>
		<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_added_date' ) ) : ?>
			<span class="normal_font">
				<i class="far fa-clock"></i>
				<?php printf( esc_html__( 'ADDED: %s', 'motors' ), get_the_date( 'F d, Y' ) );//phpcs:ignore ?>
			</span>
		<?php endif; ?>
	</div>
</div>
