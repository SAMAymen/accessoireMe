<?php
	$class = '';
if ( stm_is_woocommerce_activated() && ( is_shop() || is_product() || is_cart() ) ) {
	$class = 'current';
}

	$type       = stm_get_empty_placeholder();
	$type_label = '';
	$items      = stm_get_cart_items();
	$options    = $items['options_list'];
if ( ! empty( $items['car_class'] ) ) {
	$type_label = $items['car_class']['name'];
	$type       = $items['car_class']['subname'];
	if ( ! empty( $options ) ) {
		$class .= ' passed';
	}
}

	$type_label = sprintf(
		/* translators: %s label type */
		esc_html__( '%s Type', 'motors' ),
		$type_label
	);

	if ( empty( $options ) ) {
		$options = stm_get_empty_placeholder();
	} else {
		$options = implode( ', ', $options );
	}

	?>

<div class="inner <?php echo esc_attr( $class ); ?>">
	<a href="<?php echo esc_url( stm_woo_shop_page_url() ); ?>" class="top heading-font">
		<div class="number">
			<span>2</span>
		</div>
		<label><?php esc_html_e( 'Select Vehicle/Add-ons', 'motors' ); ?></label>
	</a>
	<div class="content">
		<div class="first">
			<h5><?php echo esc_html( $type_label ); ?></h5>
			<div><?php echo esc_html( $type ); ?></div>
		</div>
		<div class="second">
			<?php if ( ! empty( $items['car_class'] ) ) : ?>
				<a class="h5" href="<?php echo esc_url( get_permalink( $items['car_class']['id'] ) ); ?>"><?php esc_html_e( 'Addons', 'motors' ); ?></a>
			<?php else : ?>
				<h5><?php esc_html_e( 'Addons', 'motors' ); ?></h5>
			<?php endif; ?>
			<div><?php echo esc_html( $options ); ?></div>
		</div>
	</div>
</div>
