<?php
$id      = get_the_ID();
$excerpt = get_the_excerpt();
$product = wc_get_product();

$price      = $product->get_price();
$reg_price  = $product->get_regular_price();
$sale_price = $product->get_sale_price();

$cart_items = stm_get_cart_items();

$added = false;
if ( ! empty( $cart_items['options_list'][ apply_filters( 'stm_get_wpml_product_parent_id', $id ) ] ) ) {
	$added = true;
}

if ( ! $added ) {
	$gets = array(
		'add-to-cart' => $id,
	);
} else {
	$gets = array(
		'remove-from-cart' => $id,
	);
}

$url = add_query_arg( $gets, strtok( apply_filters( 'stm_get_global_server_val', 'REQUEST_URI' ), '?' ) );

$manage_stock = get_post_meta( $id, '_manage_stock', true );

?>
<div class="stm_rental_option">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="image">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</div>
	<?php endif; ?>
	<div class="stm_rental_option_content">
		<div class="content">
			<div class="title">
				<h4><?php the_title(); ?></h4>
			</div>
			<?php if ( ! empty( $excerpt ) ) : ?>
				<div class="stm-more">
					<a href="#">
						<span><?php esc_html_e( 'More information', 'motors' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="meta">
			<?php if ( 'yes' === $manage_stock ) : ?>
				<div class="quantity">
					<input type="text" step="1" min="0" max="5" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
					<div class="quantity_actions">
						<span class="plus">+</span>
						<span class="minus">-</span>
					</div>
				</div>
			<?php endif; ?>
			<div class="price">
				<?php if ( ! empty( $sale_price ) ) : ?>
					<div class="sale_price">
						<?php
						// translators: for reg price
						sprintf( esc_html__( '%s/Day', 'motors' ), wc_price( $reg_price ) );
						?>
					</div>
				<?php else : ?>
					<div class="empty_sale_price"></div>
				<?php endif; ?>
				<div class="current_price heading-font">
					<?php
					// translators: for price
					sprintf( esc_html__( '%s/Day', 'motors' ), wc_price( $price ) );
					?>
				</div>
			</div>

			<?php if ( ! $added ) : ?>
				<div class="stm-add-to-cart heading-font stm-manage-stock-<?php echo esc_attr( $manage_stock ); ?>">
					<a href="<?php echo esc_url( $url ); ?>">
						<?php esc_html_e( 'Add', 'motors' ); ?>
					</a>
				</div>
			<?php else : ?>
				<div class="stm-add-to-cart added heading-font stm-manage-stock-<?php echo esc_attr( $manage_stock ); ?>">
					<a href="<?php echo esc_url( $url ); ?>">
						<span class="add_text"><?php esc_html_e( 'Added', 'motors' ); ?></span>
						<span class="remove_text"><?php esc_html_e( 'Remove', 'motors' ); ?></span>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>

		<?php if ( ! empty( $excerpt ) ) : ?>
			<div class="more">
				<?php echo wp_kses_post( $excerpt ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
