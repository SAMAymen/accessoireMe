<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'motors' ) ) );

	return;
}
function count_active_woocommerce_payment_methods() {
	$payment_gateways = WC()->payment_gateways->payment_gateways();

	$count = 0;

	foreach ( $payment_gateways as $gateway ) {
		if ( $gateway->is_available() ) {
			$count++;
		}
	}

	return $count;
}

$active_payment_methods_count = count_active_woocommerce_payment_methods();



?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( apply_filters( 'stm_is_auto_parts', true ) ) : ?>

		<!-- Auto Parts Checkout -->

		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="customer_details">
				<?php
				if ( $checkout->get_checkout_fields() ) :
					do_action( 'woocommerce_checkout_before_customer_details' );
					do_action( 'woocommerce_checkout_billing' );
					do_action( 'woocommerce_checkout_shipping' );
					do_action( 'woocommerce_checkout_after_customer_details' );
				endif;
				?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="order_review">
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<h4 class="heading-font" id="order_review_heading"><?php esc_html_e( 'Your order', 'motors' ); ?></h4>
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>

		<!-- End of Auto Parts Checkout -->

	<?php elseif ( apply_filters( 'is_listing', false ) ) : ?>

		<!-- Classified Checkout -->

		<div class="classified-checkout">

			<div class="row">

				<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
					<?php
					if ( $checkout->get_checkout_fields() ) :
						do_action( 'woocommerce_checkout_before_customer_details' );
						do_action( 'woocommerce_checkout_billing' );
						do_action( 'woocommerce_checkout_shipping' );
						do_action( 'woocommerce_checkout_after_customer_details' );
					endif;
					?>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 order-summary">
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
					<div class="classified-order-review">
					<h4><?php esc_html_e( 'Your order', 'motors' ); ?></h4>
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>

			</div>

		</div>

		<!-- End of Classified Checkout -->

	<?php else : ?>

		<!-- Dealership Checkout -->

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="row" id="customer_details">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 stm_woocommerce_checkout_billing">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 stm_woocommerce_checkout_shipping">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order <?php echo ( 1 === $active_payment_methods_count ) ? esc_attr( 'single_method_available' ) : ''; ?>">
			<div class="row">

				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 hidden">
					<div class="colored-separator text-left">
						<div class="first-long"></div>
						<div class="last-short"></div>
					</div>
					<h4 id="order_review_heading"><?php esc_html_e( 'Your order', 'motors' ); ?></h4>

					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>

		<!-- End of Dealership Checkout -->

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
