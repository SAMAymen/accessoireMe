<?php
$taxes = stm_rental_order_taxes();

if ( ! empty( $taxes ) ) : ?>
	<div class="stm_rent_table stm_rent_tax_table">
		<div class="heading heading-font">
			<h4>
				<?php esc_html_e( 'Taxes & Fees', 'motors' ); ?>
			</h4>
		</div>
		<table>
			<tbody>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
				<?php foreach ( $taxes as $name => $tax ) : ?>
					<tr class="cart-tax tax-<?php echo sanitize_title( $name ); ?>">
						<td><?php echo sanitize_text_field( $tax['label'] ); ?></td>
						<td>&nbsp;</td>
						<td><?php echo sanitize_text_field( $tax['value'] ); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3" class="divider"></td>
				</tr>
			</tbody>
		</table>
	</div>
<?php endif; ?>
