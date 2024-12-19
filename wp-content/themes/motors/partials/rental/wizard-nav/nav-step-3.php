<?php
	$class = '';
if ( stm_is_woocommerce_activated() && is_checkout() ) {
	$class = 'current';
}

	$billing           = stm_rental_billing_info();
	$empty_placeholder = stm_get_empty_placeholder();
	$payment           = $empty_placeholder;
	$info              = $empty_placeholder;
if ( ! empty( $billing['first_name'] ) && ! empty( $billing['last_name'] ) ) {
	$info = $billing['first_name'] . ' ' . $billing['last_name'];
}

if ( ! empty( $billing['total'] ) ) {
	$payment = sprintf(
		/* translators: %s total */
		__( 'Estimated Total - %s', 'motors' ),
		$billing['total']
	);
}

?>

<div class="inner <?php echo esc_attr( $class ); ?>">
	<a href="<?php echo esc_url( stm_woo_shop_checkout_url() ); ?>" class="top heading-font">
		<div class="number">
			<span>3</span>
		</div>
		<label><?php esc_html_e( 'Reserve Your Vehicle', 'motors' ); ?></label>
	</a>
	<div class="content">
		<div class="first">
			<h5><?php esc_html_e( 'Your information', 'motors' ); ?></h5>
			<div><?php echo esc_html( $info ); ?></div>
		</div>
		<div class="second">
			<h5><?php esc_html_e( 'Payment information', 'motors' ); ?></h5>
			<div>
				<?php
				echo wp_kses_post( $payment );

				if ( ! empty( $billing['payment'] ) ) :
					?>
					<br/>
					<?php echo wp_kses_post( $billing['payment'] ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
