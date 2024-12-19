<?php

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore
	return;
}

global $product;

$sale_icon       = stm_wcmap_get_sale_icon();
$bestseller_icon = stm_wcmap_get_hot_icon();
$top_rated_icon  = stm_wcmap_get_rate_icon();
$bestseller_min  = apply_filters( 'stm_me_get_nuxy_mod', 10, 'wcmap_best_sell_min' );
$best_rated_min  = apply_filters( 'stm_me_get_nuxy_mod', 4.5, 'wcmap_best_rate_min' );
$icons           = '<div class="stm-wc-badges-wrap">';

if ( ! empty( $product->get_sale_price() ) ) {
	$icons .= '<div class="badge-wrap"><img src="' . $sale_icon . '" title="' . esc_attr__( 'Sale Product', 'stm-woocommerce-motors-auto-parts' ) . '" alt="' . esc_attr__( 'Sale Product', 'stm-woocommerce-motors-auto-parts' ) . '" /></div>';
}
if ( $product->get_total_sales() > $bestseller_min ) {
	$icons .= '<div class="badge-wrap"><img src="' . $bestseller_icon . '" title="' . esc_attr__( 'Best Seller Product', 'stm-woocommerce-motors-auto-parts' ) . '" alt="' . esc_attr__( 'Best Seller Product', 'stm-woocommerce-motors-auto-parts' ) . '" /></div>';
}
if ( $product->get_average_rating() > $best_rated_min ) {
	$icons .= '<div class="badge-wrap"><img src="' . $top_rated_icon . '" title="' . esc_attr__( 'Top Rated Product', 'stm-woocommerce-motors-auto-parts' ) . '" alt="' . esc_attr__( 'Top Rated Product', 'stm-woocommerce-motors-auto-parts' ) . '" /></div>';
}

$icons .= '</div>';

?>
<div id="product-<?php the_ID(); ?>" <?php post_class( array( 'stm-wcmap-single-product stm-wcmap__template-sidebar' ) ); ?>>
	<div class="row">
		<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
			<div class="product_images">
				<?php
				woocommerce_show_product_images();
				echo wp_kses_post( apply_filters( 'stm_wcmap_icons_filter', $icons ) );
				?>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
			<div class="summary entry-summary">
				<?php
				woocommerce_template_single_title();
				?>
				<div class="prod-meta">
					<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
						<div class="prod-sku">
							<span class="sku_wrapper">
								<?php esc_html_e( 'SKU:', 'woocommerce' ); ?>
								<span class="sku">
									<?php
									if ( ! empty( $product->get_sku() ) ) {
										echo wp_kses_post( $product->get_sku() );
									} else {
										esc_html_e( 'N/A', 'woocommerce' );
									}
									?>
								</span>
							</span>
						</div>
					<?php endif; ?>
					<div class="prod-stock">
						<?php echo wp_kses_post( stm_auto_parts_stock_label( $product ) ); ?>
					</div>
				</div>
				<div class="prod-rating">
					<?php woocommerce_template_single_rating(); ?>
				</div>
				<div class="prod-excerpt">
					<?php echo wp_kses_post( get_the_excerpt() ); ?>
				</div>
				<div class="prod-price">
					<?php woocommerce_template_single_price(); ?>
				</div>
				<div class="prod-add-to-cart">
					<?php do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); ?>
				</div>
				<div class="prod-compare-whish-wrap">
					<?php stm_wcmap_get_template( 'templates/parts/compare' ); ?>
					<?php stm_wcmap_get_template( 'templates/parts/wishlist' ); ?>
				</div>

			</div><!-- .summary -->
		</div>
	</div><!-- .row -->

	<?php woocommerce_output_product_data_tabs(); ?>

	<meta itemprop="url" content="<?php the_permalink(); ?>"/>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
