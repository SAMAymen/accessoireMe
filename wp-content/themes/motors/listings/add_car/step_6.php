<?php
$car_price_form_label = '';
$price                = '';
$sale_price           = '';
if ( ! empty( $id ) ) {
	$car_price_form_label = get_post_meta( $id, 'car_price_form_label', true );
	$price                = (int) apply_filters( 'get_conver_price', get_post_meta( $id, 'price', true ) );
	$sale_price           = ( ! empty( get_post_meta( $id, 'sale_price', true ) ) ) ? (int) apply_filters( 'get_conver_price', get_post_meta( $id, 'sale_price', true ) ) : '';
}
?>

<div class="stm-form-price-edit">
	<div class="stm-car-listing-data-single stm-border-top-unit ">
		<div class="title heading-font"><?php esc_html_e( 'Set Your Asking Price', 'motors' ); ?></div>
		<span class="step_number step_number_5 heading-font"><?php esc_html_e( 'step', 'motors' ); ?> 6</span>
	</div>
	<div class="row stm-relative">
		<?php
		$vars = array(
			'stm_title_desc'        => ( ! empty( $stm_title_desc ) ) ? $stm_title_desc : '',
			'stm_title_price'       => ( ! empty( $stm_title_price ) ) ? $stm_title_price : '',
			'show_sale_price_label' => ( ! empty( $show_sale_price_label ) ) ? $show_sale_price_label : 'no',
			'show_custom_label'     => ( ! empty( $show_custom_label ) ) ? $show_custom_label : 'no',
			'price'                 => $price,
			'car_price_form_label'  => $car_price_form_label,
			'sale_price'            => $sale_price,
			'price_label'           => ( ! empty( $price_label ) ) ? $price_label : '',
			'sale_price_label'      => ( ! empty( $sale_price_label ) ) ? $sale_price_label : '',
		);

		stm_listings_load_template( 'add_car/price_templates/price.php', $vars );

		if ( isset( $show_sale_price_label ) && 'yes' === $show_sale_price_label ) {
			stm_listings_load_template( 'add_car/price_templates/sale_price.php', $vars );
		}
		if ( isset( $show_custom_label ) && 'yes' === $show_custom_label ) {
			stm_listings_load_template( 'add_car/price_templates/custom_label.php', $vars );
		}
		?>
	</div>
	<input type="hidden" name="btn-type" />
	<input type="hidden" name="price_label" value="<?php echo esc_attr( $price_label ); ?>" />
	<input type="hidden" name="sale_price_label" value="<?php echo esc_attr( $sale_price_label ); ?>" />
</div>
