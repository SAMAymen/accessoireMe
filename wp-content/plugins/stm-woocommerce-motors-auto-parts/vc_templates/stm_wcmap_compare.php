<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_compare', 'stm_wcmap_compare' );

$base_color = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$custom_css = ".add_to_cart_button {
                        border: 2px solid {$base_color} !important;
                    }";

wp_add_inline_style( 'stm-wcmap-stm_wcmap_compare', $custom_css );

global $yith_woocompare, $product;

$woo_compare_class  = $yith_woocompare->obj;
$products           = $woo_compare_class->get_products_list();
$fields             = ( $woo_compare_class->fields() ) ? $woo_compare_class->fields() : $woo_compare_class->default_fields();
$repeat_price       = 'no';
$repeat_add_to_cart = get_option( 'yith_woocompare_add_to_cart_end' );

?>

<div class="stm-wcmap-compare">
	<table class="compare-list" cellpadding="0" cellspacing="0"
	<?php
	if ( empty( $products ) ) {
		echo ' style="width:100%"';}
	?>
	>
		<thead>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</tfoot>
		<tbody>

		<?php if ( empty( $products ) ) : ?>

			<tr class="no-products">
				<td>
					<?php echo esc_html__( 'No products added in the compare table.', 'stm-woocommerce-motors-auto-parts' ); ?>
				</td>
			</tr>

		<?php else : ?>
			<tr class="remove">
				<th>&nbsp;</th>
				<?php
				$index = 0;
				foreach ( $products as $product_id => $product ) :
					$product_class = ( 0 === $index % 2 ? 'odd' : 'even' ) . ' product_' . $product_id
					?>
					<td class="<?php echo esc_attr( $product_class ); ?>">
						<a href="<?php echo esc_url( add_query_arg( 'redirect', 'view', $woo_compare_class->remove_product_url( $product_id ) ) ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">
						<?php echo esc_html__( 'Remove', 'stm-woocommerce-motors-auto-parts' ); ?> <span class="remove">x</span>
					</a>
					</td>
					<?php
					++$index;
				endforeach;
				?>
			</tr>

			<?php foreach ( $fields as $field => $name ) : ?>

				<tr class="<?php echo esc_attr( $field ); ?>">

					<th>
						<?php
						if ( 'image' !== $field ) {
							echo esc_html( $name );}
						?>
					</th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( 0 === $index % 2 ? 'odd' : 'even' ) . ' product_' . $product_id;
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>">
							<?php
							switch ( $field ) {

								case 'image':
									echo '<div class="image-wrap">' . wp_kses_post( $product->get_image( 'yith-woocompare-image' ) ) . '</div>';
									break;

								case 'add-to-cart':
									woocommerce_template_loop_add_to_cart();
									break;

								default:
									echo empty( $product->fields[ $field ] ) ? '&nbsp;' : wp_kses_post( $product->fields[ $field ] );
									break;
							}
							?>
						</td>
						<?php
						++$index;
					endforeach;
					?>

				</tr>

			<?php endforeach; ?>

			<?php if ( 'yes' === $repeat_price && isset( $fields['price'] ) ) : ?>
				<tr class="price repeated">
					<th>
						<?php echo esc_html( $fields['price'] ); ?>
					</th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( 0 === $index % 2 ? 'odd' : 'even' ) . ' product_' . $product_id
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>">
							<?php echo esc_html( $product->fields['price'] ); ?>
						</td>
						<?php
						++$index;
					endforeach;
					?>

				</tr>
			<?php endif; ?>

			<?php if ( 'yes' === $repeat_add_to_cart && isset( $fields['add-to-cart'] ) ) : ?>
				<tr class="add-to-cart repeated">
					<th><?php echo esc_html( $fields['add-to-cart'] ); ?></th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( 0 === $index % 2 ? 'odd' : 'even' ) . ' product_' . $product_id
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>">
							<?php woocommerce_template_loop_add_to_cart(); ?>
						</td>
						<?php
						++$index;
					endforeach;
					?>

				</tr>
			<?php endif; ?>

		<?php endif; ?>

		</tbody>
	</table>
</div>
