<?php
// Get archive shop page id
if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();

	$items = 0;
	if ( ! empty( WC()->cart->cart_contents_count ) ) {
		$items = WC()->cart->cart_contents_count;
	}
}

$show_cart = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_cart_show' );

if ( $show_cart && ! empty( $woocommerce_shop_page_id ) ) : ?>
	<div class="pull-right hdn-767">
		<div class="help-bar-shop">
			<a href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>" title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>">
				<?php echo stm_me_get_wpcfto_icon( 'header_cart_icon', 'stm-boats-icon-cart' );//phpcs:ignore ?>
				<span class="list-badge">
					<span class="stm-current-items-in-cart">
						<?php echo esc_html( $items ); ?>
					</span>
				</span>
			</a>
		</div>
	</div>
<?php endif; ?>
