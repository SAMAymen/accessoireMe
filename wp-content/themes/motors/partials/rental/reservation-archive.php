<?php get_header(); ?>
<?php
$shop_sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 768, 'shop_sidebar' );
$shop_sidebar_position = apply_filters( 'stm_me_get_nuxy_mod', 'left', 'shop_sidebar_position' );

if ( ! empty( $shop_sidebar_id ) ) {
	$shop_sidebar = get_post( $shop_sidebar_id );
}


$stm_sidebar_layout_mode = stm_sidebar_layout_mode( $shop_sidebar_position, false );
?>

<?php get_template_part( 'partials/title_box' ); ?>
<?php get_template_part( 'partials/page_bg' ); ?>

<?php get_template_part( 'partials/rental/wizard' ); ?>
<?php get_template_part( 'partials/rental/wizard', 'bg' ); ?>

	<div class="stm-reservation-archive">
		<div class="container">
			<div class="row">

				<?php
				echo wp_kses_post( $stm_sidebar_layout_mode['content_before'] );

				if ( is_shop() ) {
					get_template_part( 'partials/rental/main-shop/archive', 'content' );
				} elseif ( is_product() || is_cart() ) {
					get_template_part( 'partials/rental/product/content' );
				} elseif ( is_checkout() ) {
					if ( apply_filters( 'stm_is_rental_one_elementor', false ) && have_posts() ) {
						while ( have_posts() ) {
							the_post();
							the_content();
						}
					}
					get_template_part( 'partials/rental/checkout/checkout' );
				} else {
					if ( have_posts() ) {
						woocommerce_content();
					}
				}

				echo wp_kses_post( $stm_sidebar_layout_mode['content_after'] );
				?>

				<?php if ( isset( $shop_sidebar ) && ! empty( $shop_sidebar_id ) ) { ?>
					<?php echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] ); ?>
				<div class="stm-shop-sidebar-area">
					<?php echo wp_kses_post( apply_filters( 'the_content', $shop_sidebar->post_content ) ); ?>
				</div>
					<?php echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] ); ?>
				<?php } ?>

			</div> <!--row-->
		</div> <!--container-->
	</div>
<?php get_footer(); ?>
