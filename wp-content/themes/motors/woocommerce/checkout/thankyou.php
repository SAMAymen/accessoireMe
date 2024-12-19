<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'stm_is_rental_two', false ) ) {
	do_action( 'smt_mcr_thankyou', $order );
} else {
	?>

	<div class="woocommerce-order">

		<?php if ( $order ) : ?>

			<?php if ( apply_filters( 'stm_is_auto_parts', false ) ) : ?>
				<div class="left">
			<?php endif; ?>
			<?php if ( $order->has_status( 'failed' ) ) : ?>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'motors' ); ?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
					class="button pay"><?php esc_html_e( 'Pay', 'motors' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
						class="button pay"><?php esc_html_e( 'My Account', 'motors' ); ?></a>
					<?php endif; ?>
				</p>

			<?php else : ?>


				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received heading-font">
					<i class="fas fa-check"></i>
					<?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'motors' ), $order ) ); ?>
				</p>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details heading-font">

					<li class="woocommerce-order-overview__order order">
						<span><?php esc_html_e( 'Order number:', 'motors' ); ?></span>
						<strong class="heading-font">#<?php echo esc_html( $order->get_order_number() ); ?></strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						<?php esc_html_e( 'Date:', 'motors' ); ?>
						<strong><?php echo wc_format_datetime( $order->get_date_created() );//phpcs:ignore ?></strong>
					</li>

					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<?php esc_html_e( 'Email:', 'motors' ); ?>
							<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
						</li>
					<?php endif; ?>

					<li class="woocommerce-order-overview__total total">
						<?php esc_html_e( 'Total:', 'motors' ); ?>
						<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>

						<li class="woocommerce-order-overview__payment-method method">
							<span><?php esc_html_e( 'Payment method:', 'motors' ); ?></span>
							<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>

					<?php endif; ?>

				</ul>
				<div class="clear"></div>
				<div class="woocommerce-order-stm-return-to-page">
					<?php
					if ( apply_filters( 'stm_is_listing', false ) ) :
						$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

						if ( ! empty( $order_items ) ) :
							$products_visible = false;

							if ( count( $order_items ) <= 1 ) :
								foreach ( $order_items as $order_item ) :
									$product    = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );
									$is_visible = $product && $product->is_visible();
									if ( $is_visible ) :
										$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

										if ( stm_is_multilisting() ) {
											$slugs = STMMultiListing::stm_get_listing_type_slugs();
											if ( ! empty( $slugs ) ) {
												$post_types = array_merge( $post_types, $slugs );
											}
										}

										$products_visible  = $is_visible && ( in_array( get_post_type( $product->get_id() ), $post_types, true ) );
										$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $order_item ) : '', $order_item, $order );
										if ( $products_visible ) :
											?>
										<p>
											<a href="<?php echo esc_url( $product_permalink ); ?>" class="button">
												<?php esc_html_e( 'View Listing', 'motors' ); ?>
											</a>
										</p>
											<?php
										endif;
									endif;
								endforeach;
							endif;
							if ( ! $products_visible ) :
								?>
								<p>
									<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="button">
										<?php esc_html_e( 'Return to account', 'motors' ); ?>
									</a>
								</p>
								<?php
							endif;
						endif;
					endif;
					?>
				</div>
			<?php endif; ?>

			<?php
			if ( ! apply_filters( 'stm_is_rental', true ) ) {
				do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
			}
			?>
			<?php if ( apply_filters( 'stm_is_auto_parts', false ) ) : ?>
				</div>
			<?php endif; ?>
			<?php if ( apply_filters( 'stm_is_auto_parts', false ) ) : ?>
				<div class="right">
			<?php endif; ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			<?php if ( apply_filters( 'stm_is_auto_parts', false ) ) : ?>
				<?php
				$shopPageId = get_option( 'woocommerce_shop_page_id' );
				if ( $shopPageId ) :
					?>
					<a href="<?php echo esc_url( get_the_permalink( $shopPageId ) ); ?>" class="go-to-shop heading-font">
						<?php echo esc_html__( 'Continue Shopping', 'motors' ); ?>
					</a>
				<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php do_action( 'stm_send_email_pay_per_listing', $order->get_id() ); ?>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'motors' ), null ) ); ?></p>

		<?php endif; ?>

	</div>
<?php } ?>
