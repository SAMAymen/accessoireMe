<?php
$coupons  = WC()->cart->get_coupons();
$order_id = isset( $_GET['key'] ) ? wc_get_order_id_by_order_key( sanitize_text_field( $_GET['key'] ) ) : '';
if ( ! empty( $coupons ) ) :
	$coupon_sum = 0;
	?>
	<div class="stm_rent_table stm_rent_coupon_table">
		<div class="heading heading-font"><h4><?php esc_html_e( 'Coupons', 'motors' ); ?></h4></div>
		<table>
			<thead class="heading-font">
				<tr>
					<td><?php esc_html_e( 'Coupon code', 'motors' ); ?></td>
					<td>&nbsp;</td>
					<td><?php esc_html_e( 'amount', 'motors' ); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
				<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
					<?php $coupon_sum += WC()->cart->get_coupon_discount_amount( $code ); ?>
					<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
						<td><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
						<td>&nbsp;</td>
						<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
			</tbody>
			<tfoot class="heading-font">
				<tr>
					<td colspan="2"><?php esc_html_e( 'Total discount', 'motors' ); ?></td>
					<td>-<?php echo wp_kses_post( wc_price( $coupon_sum ) ); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php elseif ( ! empty( $order_id ) ) : ?>
	<?php
	if ( $order_id && wc_get_order( $order_id ) ) {
		$wc_order              = wc_get_order( $order_id );
		$wc_order_coupon_total = $wc_order->get_total_discount();
	}
	?>
<div class="stm_rent_table stm_rent_coupon_table">
	<div class="heading heading-font"><h4><?php esc_html_e( 'Coupons', 'motors' ); ?></h4></div>
	<table>
		<tfoot class="heading-font">
		<tr>
			<td colspan="2"><?php esc_html_e( 'Total discount', 'motors' ); ?></td>
			<td><?php echo wp_kses_post( wc_price( -$wc_order_coupon_total ) ); ?></td>
		</tr>
		</tfoot>
	</table>
</div>
<?php endif; ?>
