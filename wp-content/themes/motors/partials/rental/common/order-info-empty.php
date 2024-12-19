<?php
$cart_items = stm_get_cart_items();
$car_rent   = $cart_items['car_class'];
?>

<div class="title">
	<h4><?php esc_html_e( 'Car', 'motors' ); ?></h4>
	<div class="subtitle heading-font"><?php esc_html_e( 'Please select your vehicle ', 'motors' ); ?></div>
</div>
<div class="image image-placeholder">
	<a href="<?php echo esc_url( stm_woo_shop_page_url() ); ?>">
		<div class="placeholder">
			<span class="plus"></span>
			<i class="stm-icon-car-relic"></i>
		</div>
		<div class="clearfix"></div>
		<span class="button">
			<?php esc_html_e( 'Find a vehicle', 'motors' ); ?>
			<i class="fas fa-arrow-right"></i>
		</span>
	</a>
</div>

<!--Car rent-->
<div class="stm_rent_table">
	<div class="heading heading-font"><h4><?php esc_html_e( 'Rate', 'motors' ); ?></h4></div>
	<table>

		<tbody>
			<tr>
				<td colspan="3" class="divider"></td>
			</tr>
			<tr>
				<td><?php echo stm_get_empty_placeholder(); ?></td>
				<td><?php echo stm_get_empty_placeholder(); ?></td>
				<td><?php echo stm_get_empty_placeholder(); ?></td>
			</tr>
			<tr>
				<td colspan="3" class="divider"></td>
			</tr>
		</tbody>
	</table>
</div>

<!--Add-ons-->

<div class="stm_rent_table">
	<div class="heading heading-font"><h4><?php esc_html_e( 'Add-ons', 'motors' ); ?></h4></div>
	<table>
		<?php if ( ! empty( $cart_items['options'] ) ) : ?>
			<thead class="heading-font">
				<tr>
					<td><?php esc_html_e( 'QTY', 'motors' ); ?></td>
					<td><?php esc_html_e( 'Rate', 'motors' ); ?></td>
					<td><?php esc_html_e( 'Subtotal', 'motors' ); ?></td>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>

				<?php foreach ( $cart_items['options'] as $car_option ) : ?>
					<tr>
						<td><?php printf( esc_html__( '%1$d x %2$s', 'motors' ), $car_option['quantity'], $car_option['name'] ); ?></td>
						<td><?php echo wc_price( $car_option['price'] ); ?></td>
						<td><?php echo wc_price( $car_option['total'] ); ?></td>
					</tr>
					<tr>
						<td colspan="3" class="divider"></td>
					</tr>
				<?php endforeach; ?>

			</tbody>
			<tfoot class="heading-font">
				<tr>
					<td colspan="2"><?php esc_html_e( 'Rental Charges Rate', 'motors' ); ?></td>
					<td><?php echo wc_price( $cart_items['option_total'] ); ?></td>
				</tr>
			</tfoot>
		<?php else : ?>
			<tbody>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
				<tr>
					<td><?php echo stm_get_empty_placeholder(); ?></td>
					<td><?php echo stm_get_empty_placeholder(); ?></td>
					<td><?php echo stm_get_empty_placeholder(); ?></td>
				</tr>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
			</tbody>
		<?php endif; ?>
	</table>

</div>

<div class="stm-rent-total heading-font">
	<table>
		<tr>
			<td><?php esc_html_e( 'Estimated total', 'motors' ); ?></td>
			<td><?php echo stm_get_empty_placeholder(); ?></td>
		</tr>
	</table>
</div>
