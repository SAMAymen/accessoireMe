<?php
if ( ! is_checkout() || ! is_account_page() ) {
	$current_sub = stm_user_active_subscriptions( true );

	if ( ! empty( $current_sub ) ) :
		$stm_pricing_link = '';
		$new_order        = new WC_Order( $current_sub['last_order_id'] );
		$stm_pricing_link = $new_order->get_checkout_payment_url();
		?>
		<div class="stm-no-available-adds-overlay"></div>
		<div class="stm-no-available-adds">
			<h3><?php esc_html_e( 'Plan days left:', 'motors' ); ?> <span>0</span></h3>
			<p><?php esc_html_e( 'Your plan period time is expired, please renew the subscription.', 'motors' ); ?></p>

			<div class="clearfix">
				<a href="<?php echo esc_url( $stm_pricing_link ); ?>" class="button stm-green">
				<?php esc_html_e( 'Renew', 'motors' ); ?>
				</a>
				<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="button stm-green" style="margin-right:0">
				<?php esc_html_e( 'My subscriptions', 'motors' ); ?>
				</a>
			</div>
		</div>
		<?php
	endif;
}
?>
