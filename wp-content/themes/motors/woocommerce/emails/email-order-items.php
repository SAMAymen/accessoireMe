<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';

foreach ( $items as $item_id => $item ) :
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( $image_size );
		$product_type  = $product->get_type();
	}

	//additional data for order info in rental
	if ( apply_filters( 'stm_is_rental', false ) && function_exists( 'stm_get_rental_order_fields_values' ) ) {
		$cart_items           = stm_get_cart_items();
		$product_id           = ( 'variation' === $product_type ) ? $cart_items['car_class']['id'] : $product->get_id();
		$car_options          = $cart_items['options'];
		$order_days           = $cart_items['car_class']['days'];
		$order_hours          = isset( $cart_items['car_class']['hours'] ) ? $cart_items['car_class']['hours'] : 0;
		$car_price            = $cart_items['car_class']['price'];
		$order_total          = '';
		$fixedPrice           = ( class_exists( 'PriceForQuantityDays' ) ) ? PriceForQuantityDays::getFixedPrice( $product_id ) : null;
		$priceDate            = PriceForDatePeriod::getDescribeTotalByDays( $car_price, $product_id );
		$priceDateSimpleDays  = count( $priceDate['simple_price'] );
		$priceDateSimplePrice = $priceDate['simple_price'][0];
		$priceDateTotal       = array_sum( $priceDate['promo_price'] );
		$pricePerHour         = floatval( get_post_meta( $product_id, 'rental_price_per_hour_info', true ) );
		$pricePerHourTotal    = $pricePerHour * $order_hours;
		$discount             = ( class_exists( 'DiscountByDays' ) ) ? DiscountByDays::get_days_post_meta( $product_id ) : null;
		$fields               = stm_get_rental_order_fields_values();
		$location_fee         = 0;

		if ( 'car_option' !== $product_type ) {
			if ( ! empty( $fixedPrice ) ) {
				$order_total = $fixedPrice * $priceDateSimpleDays;
			} else {
				$order_total = $priceDateSimpleDays * $priceDateSimplePrice;
			}
			if ( ! empty( $priceDate ) && count( $priceDate['promo_price'] ) > 0 ) {
				$order_total += $priceDateTotal;
			}
			if ( ! empty( $pricePerHour ) ) {
				$order_total += $pricePerHourTotal;
			}
		}

		if ( 'on' === $fields['return_same'] && apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_fee_for_same_location' ) && ! empty( $fields['pickup_location_fee'] ) ) {
			$location_fee = $fields['pickup_location_fee'];
		} elseif ( 'off' === $fields['return_same'] && ! empty( $fields['return_location_fee'] ) ) {
			$location_fee = $fields['return_location_fee'];
		}
	}

	?>

	<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
			<?php

			// Show title/image etc.
			if ( $show_image ) {
				echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
			}

			// Product name.
			echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

			// SKU.
			if ( $show_sku && $sku ) {
				echo wp_kses_post( ' (#' . $sku . ')' );
			}

			// allow other plugins to add additional product information here.
			do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

			wc_display_item_meta(
				$item,
				array(
					'label_before' => '<strong class="wc-item-meta-label" style="float: ' . esc_attr( $text_align ) . '; margin-' . esc_attr( $margin_side ) . ': .25em; clear: both">',
				)
			);

			// allow other plugins to add additional product information here.
			do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

			?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php
			$qty          = $item->get_quantity();
			$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

			if ( $refunded_qty ) {
				$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
			} else {
				$qty_display = esc_html( $qty );
			}
			echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
			?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php if ( apply_filters( 'stm_is_rental', false ) ) : ?>
				<table>
					<h5 style="border-bottom: solid 1px ;">
						<?php echo ! empty( 'car_option' === $product_type ) ? esc_html__( 'ADD-ON RATE', 'motors' ) : esc_html__( 'RATE', 'motors' ); ?>
					</h5>
					<thead>
					<tr>
						<td><?php esc_html_e( 'QTY', 'motors' ); ?></td>
						<td><?php esc_html_e( 'RATE', 'motors' ); ?></td>
						<td><?php esc_html_e( 'SUBTOTAL', 'motors' ); ?></td>
					</tr>
					</thead>
					<tbody>
					<?php if ( 'car_option' !== $product_type ) : ?>
						<?php if ( ! empty( $fixedPrice ) && ! empty( $order_total ) ) : ?>
							<tr>
								<td><?php echo esc_html__( 'Days ', 'motors' ) . esc_html( $priceDateSimpleDays ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $fixedPrice ) ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $fixedPrice * $priceDateSimpleDays ) ); ?></td>
							</tr>
						<?php endif; ?>
						<?php if ( ! empty( $priceDateSimplePrice ) && empty( $fixedPrice ) && ! empty( $order_total ) ) : ?>
							<tr>
								<td><?php echo esc_html__( 'Days ', 'motors' ) . esc_html( $priceDateSimpleDays ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $priceDateSimplePrice ) ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $priceDateSimpleDays * $priceDateSimplePrice ) ); ?></td>
							</tr>
						<?php endif; ?>
						<?php if ( count( $priceDate['promo_price'] ) > 0 ) : ?>
							<?php
							$promo_prices = array_count_values( $priceDate['promo_price'] );
							foreach ( $promo_prices as $k => $val ) :
								$period_total_price = $val * $k;
								?>
								<tr>
									<td><?php echo esc_html__( 'Days ', 'motors' ) . esc_html( $val ); ?></td>
									<td><?php echo wp_kses_post( wc_price( $k ) ); ?></td>
									<td><?php echo wp_kses_post( wc_price( $period_total_price ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if ( ! empty( $pricePerHourTotal ) && ! empty( $order_hours ) ) : ?>
							<tr>
								<td><?php echo esc_html__( 'Hours ', 'motors' ) . esc_html( $order_hours ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $pricePerHour ) ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $pricePerHourTotal ) ); ?></td>
							</tr>
						<?php endif; ?>
						<?php if ( ! empty( $location_fee ) ) : ?>
							<tr>
								<td colspan="2"><?php esc_html_e( 'Return Location Fee', 'motors' ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $location_fee ) ); ?></td>
							</tr>
						<?php endif; ?>
						<?php
						if ( ! empty( $discount ) ) :
							$currentDiscount = 0;
							$days            = 0;
							foreach ( $discount as $k => $val ) {
								if ( $val['days'] <= $order_days ) {
									$days            = $val['days'];
									$currentDiscount = $val['percent'];
								}
							}

							$forDiscount = $order_total;
							$order_total = $order_total - ( ( $order_total / 100 ) * $currentDiscount );
							?>
							<tr>
								<td colspan="2"><?php printf( esc_html__( 'Discount: %1$s Days and more %2$s%%', 'motors' ), esc_html( $days ), esc_html( $currentDiscount ) ); ?></td>
								<td><?php echo '- ' . wp_kses_post( wc_price( ( $forDiscount / 100 ) * $currentDiscount ) ); ?></td>
							</tr>
						<?php endif; ?>
					<?php else : ?>
						<!--				START CAR OPTION PRODUCT BODY EMAIL TEMPLATE-->
						<?php if ( ! empty( $car_options ) ) : ?>
							<?php foreach ( $car_options as $car_option ) : ?>
								<?php if ( $car_option['id'] === $product_id ) : ?>
									<tr>
										<td><?php echo esc_html__( 'Days ', 'motors' ) . esc_html( $car_option['opt_days'] ); ?></td>
										<td><?php echo wp_kses_post( wc_price( $car_option['price'] ) ); ?></td>
										<td><?php echo wp_kses_post( wc_price( $car_option['total'] ) ); ?></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<!--				END CAR OPTION PRODUCT BODY EMAIL TEMPLATE-->
					<?php endif; ?>
					</tbody>
					<tfoot>
					<?php if ( 'car_option' !== $product_type ) : ?>
						<tr>
							<td colspan="2"><?php esc_html_e( 'Rental Charges Rate', 'motors' ); ?></td>
							<td><?php echo wp_kses_post( wc_price( $order_total ) ); ?></td>
						</tr>
					<?php else : ?>
						<!--				START CAR OPTION PRODUCT FOOTER EMAIL TEMPLATE-->
						<?php foreach ( $car_options as $car_option ) : ?>
							<?php if ( $car_option['id'] === $product_id ) : ?>
								<tr>
									<td colspan="2"><?php esc_html_e( 'ADD-ON Charges Rate', 'motors' ); ?></td>
									<td><?php echo wp_kses_post( wc_price( $car_option['total'] ) ); ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
						<!--				END CAR OPTION PRODUCT FOOTER EMAIL TEMPLATE-->
					<?php endif; ?>
					</tfoot>
				</table>
			<?php else : ?>
				<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
			<?php endif; ?>
		</td>
	</tr>
	<?php

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}
	?>

<?php endforeach; ?>
