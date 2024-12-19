<?php
$cart_items = stm_get_cart_items();
$order_info = 'empty';

if ( $cart_items['has_car'] ) {
	$order_info = '';
}

$checkout_page    = intval( get_option( 'woocommerce_checkout_page_id' ) );
$current_page_id  = intval( get_the_ID() );

if ( class_exists( '\Elementor\Plugin' ) ) {
	$edit_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();
}

?>

<div class="stm_rent_order_info">
	<?php
	get_template_part( 'partials/rental/common/order-info', $order_info );

	if ( empty( $order_info ) && ! is_checkout() && ! ( isset( $edit_mode ) && $current_page_id === $checkout_page ) ) {
		get_template_part( 'partials/rental/common/order-info', 'accept' );
	}
	?>
</div>
