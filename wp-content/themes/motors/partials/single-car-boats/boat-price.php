<?php
$price      = get_post_meta( get_the_ID(), 'price', true );
$sale_price = get_post_meta( get_the_ID(), 'sale_price', true );

$regular_price_label       = get_post_meta( get_the_ID(), 'regular_price_label', true );
$regular_price_description = get_post_meta( get_the_ID(), 'regular_price_description', true );
$special_price_label       = get_post_meta( get_the_ID(), 'special_price_label', true );
$instant_savings_label     = get_post_meta( get_the_ID(), 'instant_savings_label', true );

//Get text price field
$car_price_form       = get_post_meta( get_the_ID(), 'car_price_form', true );
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );


$show_price      = true;
$show_sale_price = false;

if ( empty( $price ) ) {
	$show_price = false;
}

if ( ! empty( $price ) && empty( $sale_price ) ) {
	$show_sale_price = false;
}

if ( ! empty( $price ) && ! empty( $sale_price ) ) {
	if ( intval( $price ) === intval( $sale_price ) ) {
		$show_sale_price = false;
	}
	$price = $sale_price;
}

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price           = $sale_price;
	$show_price      = true;
	$show_sale_price = false;
}
?>

<?php //SINGLE REGULAR PRICE ?>
<?php if ( $show_price && ! $show_sale_price ) : ?>

	<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
		<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
	<?php endif; ?>

	<div class="single-car-prices">
		<div class="single-regular-price text-center">
			<?php if ( ! empty( $car_price_form_label ) ) : ?>
				<span class="h3"><?php echo wp_kses_post( $car_price_form_label ); ?></span>
			<?php else : ?>
				<?php if ( ! empty( $regular_price_label ) ) : ?>
					<span class="labeled"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_label, 'Regular Price Label' ) ); ?></span>
				<?php endif; ?>
				<span class="h3"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
		</a>
	<?php endif; ?>


	<?php if ( ! empty( $regular_price_description ) && empty( $car_price_form_label ) ) : ?>
		<div class="price-description-single"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_description, 'Regular Price Description' ) ); ?></div>
	<?php endif; ?>

<?php endif; ?>

<?php //SINGLE REGULAR && SALE PRICE ?>
<?php if ( $show_price && $show_sale_price ) : ?>

	<div class="single-car-prices">
		<div class="single-regular-sale-price">
			<?php if ( ! empty( $car_price_form_label ) ) : ?>
				<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
					<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
				<?php endif; ?>
				<span class="h3"><?php echo esc_attr( $car_price_form_label ); ?></span>
				<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
					</a>
				<?php endif; ?>
			<?php else : ?>
				<table>
					<tr>
						<td>
							<div class="regular-price-with-sale">
								<?php if ( ! empty( $special_price_label ) ) : ?>
									<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $special_price_label, 'Special Price Label' ) ); ?>
								<?php endif; ?>
								<?php if ( ! empty( $car_price_form_label ) ) : ?>
									<strong><?php echo wp_kses_post( $car_price_form_label ); ?></strong>
								<?php else : ?>
									<strong>
										<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
									</strong>
								<?php endif; ?>

							</div>
						</td>
						<td>
							<?php
							if ( ! empty( $regular_price_label ) ) :
								echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_label, 'Regular Price Label' ) );
								$mg_bt = '';
							else :
								$mg_bt = 'style=margin-top:0';
							endif;
							?>
							<div class="h4" <?php echo esc_attr( $mg_bt ); ?>><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></div>
						</td>
					</tr>
				</table>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( ! empty( $instant_savings_label ) && empty( $car_price_form_label ) ) : ?>
		<?php $savings = intval( $price ) - intval( $sale_price ); ?>
		<div class="sale-price-description-single">
			<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $instant_savings_label, 'Instant Savings Label' ) ); ?>
			<strong> <?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $savings ) ); ?></strong>
		</div>
	<?php endif; ?>
	<?php
endif;

