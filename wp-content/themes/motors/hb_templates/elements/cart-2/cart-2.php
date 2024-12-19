<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php
$totalProd  = 0;
$totalPrice = 0;

if ( class_exists( 'WooCommerce' ) ) {
	$cart = ( WC()->cart !== null ) ? WC()->cart : '';

	if ( ! empty( $cart ) ) {
		$totalProd  = array_sum( $cart->get_cart_item_quantities() );
		$totalPrice = $cart->get_cart_total();
	}
}
?>

<?php if ( ! empty( $element['data'] ) ) : ?>
	<a href="<?php echo esc_url( stm_hb_get_cart_url() ); ?>" class="stm-cart-2-link">
		<div class="stm-cart-2">
			<?php if ( ! empty( $element['data']['icon'] ) ) : ?>
				<i class="stm-iconbox__icon stm_hb_mtc stm-iconbox__icon_left icon_22px <?php echo esc_attr( $element['data']['icon'] ); ?>"></i>
			<?php endif; ?>
			<div class="stm-cart-2__info">
				<?php if ( ! empty( $element['data']['title'] ) ) : ?>
					<div class="stm-iconbox__text stm-iconbox__text_nomargin">
						<?php echo wp_kses( $element['data']['title'], array( 'br' => array() ) ); ?>
					</div>
				<?php endif; ?>
				<div class="stm-iconbox__text stm-iconbox__text_nomargin">
					<span class="cart-total-products">
						<?php
						// translators: %d - number of products in cart
							echo wp_kses_post( sprintf( __( '<span class="cart__quantity-badge">%d</span> items', 'motors' ), $totalProd ) );
						?>
					</span>
				</div>
				<div class="stm-iconbox__description">
					<span class="cart-total-price">
						<?php echo wp_kses_post( $totalPrice ); ?>
					</span>
				</div>
			</div>
		</div>
	</a>
<?php endif; ?>
