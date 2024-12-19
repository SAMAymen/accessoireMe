<?php
$stm_rental_total_order_info = stm_rental_total_order_info();
if ( ! empty( $stm_rental_total_order_info ) ) : ?>
	<h4 class="rental_title"><?php esc_html_e( 'Summary', 'motors' ); ?></h4>
	<div class="stm_rental_order_success">
		<?php foreach ( $stm_rental_total_order_info as $key => $stm_order_info ) : ?>
			<?php if ( ! empty( $stm_order_info['content'] ) ) : ?>
				<div class="single_order_info">
					<h4 class="title"><?php echo esc_attr( $stm_order_info['title'] ); ?></h4>
					<div class="content"><?php echo wp_kses_post( $stm_order_info['content'] ); ?></div>
				</div>
			<?php endif; ?>
			<?php if ( 'vehicle' === $key && empty( $stm_order_info['content'] ) ) : ?>
				<?php
				$orderId   = $wp_query->query['view-order'];
				$orderMeta = get_post_meta( $orderId, 'order_car' );
				$order     = wc_get_order( $orderId );
				$items     = $order->get_items();
				foreach ( $items as $item ) {
					$product_id  = $item->get_product_id();
					$product     = wc_get_product( $product_id );
					$prduct_type = $product->get_type();
					if ( 'car_option' !== $prduct_type ) {
						$car_class      = get_the_title( $product_id );
						$car_class_info = get_post_meta( $product_id, 'cars_info', true );
					}
				}
				?>
				<div class="single_order_info">
					<h4 class="title"><?php echo esc_attr( $stm_order_info['title'] ); ?></h4>
					<?php if ( ! empty( $car_class ) ) : ?>
						<div class="content"><?php echo wp_kses_post( $car_class . ' - ' . $car_class_info ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php get_template_part( 'partials/rental/common/order', 'policy' ); ?>
<?php get_template_part( 'partials/rental/common/order', 'print' ); ?>
