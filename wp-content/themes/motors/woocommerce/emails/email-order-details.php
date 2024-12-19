<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

$orderPD = get_post_meta( $order->get_id(), 'order_pickup_date' );
$orderPL = get_post_meta( $order->get_id(), 'order_pickup_location' );
$orderDD = get_post_meta( $order->get_id(), 'order_drop_date' );
$orderDL = get_post_meta( $order->get_id(), 'order_drop_location' );

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php if ( ! $sent_to_admin ) : ?>
	<h2><?php printf( __( 'Order #%s', 'motors' ), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) );//phpcs:ignore ?>)</h2>
<?php else : ?>
	<h2>
		<a class="link" href="<?php echo esc_url( admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ); ?>"><?php printf( __( 'Order #%s', 'motors' ), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) );//phpcs:ignore ?>)
	</h2>
<?php endif; ?>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
	<tr>
		<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'motors' ); ?></th>
		<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'motors' ); ?></th>
		<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'motors' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	echo wc_get_email_order_items(//phpcs:ignore
		$order,
		array(
			'show_sku'      => $sent_to_admin,
			'show_image'    => false,
			'image_size'    => array( 32, 32 ),
			'plain_text'    => $plain_text,
			'sent_to_admin' => $sent_to_admin,
		)
	);
	?>
	</tbody>
	<tfoot>
	<!--rental layout order custom details START-->
	<?php
	if ( apply_filters( 'stm_is_rental', false ) ) :
		$date                  = stm_get_rental_order_fields_values();
		$cart_data             = stm_get_cart_items();
		$coupon_codes          = $cart_data['coupon_code'];
		$coupon_total_discount = -$cart_data['coupon'];
		?>
		<tr>
			<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html__( 'Pickup date:', 'motors' ); ?>
			</th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html( $orderPD[0] ); ?>
			</td>
		</tr>
		<tr>
			<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html__( 'Pickup location:', 'motors' ); ?>
			</th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html( $orderPL[0] ); ?>
			</td>
		</tr>
		<tr>
			<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html__( 'Return date:', 'motors' ); ?>
			</th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html( $orderDD[0] ); ?>
			</td>
		</tr>
		<tr>
			<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html__( 'Return location:', 'motors' ); ?>
			</th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>">
				<?php echo esc_html( $orderDL[0] ); ?>
			</td>
		</tr>
	<?php endif; ?>
	<!--rental layout order custom details END-->
	<?php
	$totals = $order->get_order_item_totals();

	if ( $totals ) {
		if ( apply_filters( 'stm_is_rental', false ) ) {
			foreach ( $totals as $key => $total ) {
				if ( 'payment_method' === $key || 'order_total' === $key ) {
					?>
					<?php if ( 'order_total' === $key && ! empty( $coupon_total_discount ) ) : ?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php esc_html_e( 'Coupons Total Discount:' ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo wp_kses_post( wc_price( $coupon_total_discount ) ); ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo esc_html( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
		} else {
			foreach ( $totals as $total ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo esc_html( $total['label'] ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
		}
	}
	?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
