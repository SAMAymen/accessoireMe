<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = wc_get_loop_prop( 'columns' );
}

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop'] ++;

// Extra post classes
$classes = array();

$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] ) . ' col-sm-4 col-xs-12';
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

if ( apply_filters( 'stm_is_auto_parts', false ) ) {
	$saleIcoLink     = stm_wcmap_get_sale_icon();
	$bestSellIcoLink = stm_wcmap_get_hot_icon();
	$topRateIcoLink  = stm_wcmap_get_rate_icon();

	$bestSellMin = apply_filters( 'stm_me_get_nuxy_mod', 10, 'wcmap_best_sell_min' );
	$bestRateMin = apply_filters( 'stm_me_get_nuxy_mod', 4.5, 'wcmap_best_rate_min' );

	$icons = '<div class="stm-wc-badges-wrap">';

	if ( ! empty( $product->get_sale_price() ) ) {
		$icons .= '<div class="badge-wrap"><img src="' . esc_url( $saleIcoLink ) . '" title="' . esc_attr__( 'Sale Product', 'motors' ) . '" alt="' . esc_attr__( 'Sale Product', 'motors' ) . '" /></div>';
	}
	if ( $product->get_total_sales() > $bestSellMin ) {
		$icons .= '<div class="badge-wrap"><img src="' . esc_url( $bestSellIcoLink ) . '" title="' . esc_attr__( 'Best Seller Product', 'motors' ) . '" alt="' . esc_attr__( 'Best Seller Product', 'motors' ) . '" /></div>';
	}
	if ( $product->get_average_rating() > $bestRateMin ) {
		$icons .= '<div class="badge-wrap"><img src="' . esc_url( $topRateIcoLink ) . '" title="' . esc_attr__( 'Top Rated Product', 'motors' ) . '" alt="' . esc_attr__( 'Top Rated Product', 'motors' ) . '" /></div>';
	}

	$icons .= '</div>';
}

?>
<li <?php post_class( $classes ); ?>>

	<div class="stm-product-inner">

		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="product_thumbnail">
			<?php
			if ( apply_filters( 'stm_is_auto_parts', false ) ) {
				echo wp_kses_post( $icons );
			}

			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */

			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
		</div>
		<?php if ( ! apply_filters( 'stm_is_auto_parts', true ) ) : ?>
			<h5><?php the_title(); ?></h5>
			<div class="product_info clearfix">
				<?php
				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */

				do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
			</div>
			<?php
			/**
			 * woocommerce_after_shop_loop_item hook.
			 *
			 * @hooked woocommerce_template_loop_product_link_close - 5
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item' );
			?>
		<?php else : ?>
			<h6><?php echo wp_kses_post( wc_get_product_category_list( get_the_ID() ) ); ?></h6>
			<h5><a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>"><?php the_title(); ?></a></h5>

			<div class="stm-info-button-wrap">
				<div class="product_info clearfix">
					<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
				</div>
				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
			</div>
		<?php endif; ?>


	</div>

</li>
