<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<div class="colored-separator text-left">
	<div class="first-long"></div>
	<div class="last-short"></div>
</div>
<header><h4><?php esc_html_e( 'Customer Details', 'motors' ); ?></h4></header>

<table class="shop_table shop_table_responsive customer_details">
	<?php if ( $order->get_customer_note() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Note:', 'motors' ); ?></th>
			<td><?php echo esc_html( $order->get_customer_note() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->get_billing_email() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Email:', 'motors' ); ?></th>
			<td><?php echo esc_html( $order->get_billing_email() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->get_billing_phone() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Telephone:', 'motors' ); ?></th>
			<td><?php echo esc_html( $order->get_billing_phone() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</table>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

<div class="col2-set addresses">
	<div class="col-1">

<?php endif; ?>

<div class="colored-separator text-left">
	<div class="first-long"></div>
	<div class="last-short"></div>
</div>
<header class="title">
	<h4><?php esc_html_e( 'Billing address', 'motors' ); ?></h4>
</header>
<address>
	<?php
	$address = $order->get_formatted_billing_address();
	echo wp_kses_post( ( ! empty( $address ) ) ? $address : esc_html__( 'N/A', 'motors' ) );

	/**
	 * Action hook fired after an address in the order customer details.
	 *
	 * @param string $address_type Type of address (billing or shipping).
	 * @param WC_Order $order Order object.
	 * @since 8.7.0
	 */
	do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order );
	?>
</address>

	<?php if ( $show_shipping ) : ?>

	</div><!-- /.col-1 -->
	<div class="col-2">
		<header class="title">
			<h3><?php esc_html_e( 'Shipping address', 'motors' ); ?></h3>
		</header>
		<address>
			<?php
			$address = $order->get_formatted_shipping_address();
			echo wp_kses_post( ( ! empty( $address ) ) ? $address : __( 'N/A', 'motors' ) );
			?>

			<?php
			/**
			 * Action hook fired after an address in the order customer details.
			 *
			 * @since 8.7.0
			 * @param string $address_type Type of address (billing or shipping).
			 * @param WC_Order $order Order object.
			 */
			do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order );
			?>
		</address>
	</div><!-- /.col-2 -->
</div><!-- /.col2-set -->

<?php endif; ?>

<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
