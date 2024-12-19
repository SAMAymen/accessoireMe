<?php
get_header();

stm_wcmap_enqueue_scripts_styles( 'templates', 'templates' );

$base_color   = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$second_color = apply_filters( 'stm_me_get_nuxy_mod', '#6f9ae2', 'site_style_secondary_color' );
$custom_css   = "
	.single-product .woocommerce-breadcrumb i { color: {$second_color}; }
	.stm-shop-sidebar-area .widget_product_categories .widget_title { border-bottom-color: {$base_color}; }
	.stm-shop-sidebar-area .widget_product_categories .widget_title:before { color: {$second_color}; }
	.stm-shop-sidebar-area .widget_product_categories ul li .stm-wcmap-subcats-content .subcat-list li .subSubCat:before { background: {$second_color}; }
	.stm-shop-sidebar-area .widget_recently_viewed_products ul li:hover .meta-wrap a span,
	.stm-shop-sidebar-area .widget_top_rated_products ul li:hover .meta-wrap a span,
	.stm-shop-sidebar-area .widget_products ul li:hover .meta-wrap a span,
	.stm-shop-sidebar-area .widget_recent_reviews ul li:hover .meta-wrap a span { color: {$base_color} !important; }
	.stm-shop-sidebar-area .widget_recently_viewed_products ul li .meta-wrap .star-rating:before,
	.stm-shop-sidebar-area .widget_top_rated_products ul li .meta-wrap .star-rating:before,
	.stm-shop-sidebar-area .widget_products ul li .meta-wrap .star-rating:before,
	.stm-shop-sidebar-area .widget_recent_reviews ul li .meta-wrap .star-rating:before { color: {$base_color}; }
	.stm-shop-sidebar-area .widget_recently_viewed_products ul li .meta-wrap .star-rating span:before,
	.stm-shop-sidebar-area .widget_top_rated_products ul li .meta-wrap .star-rating span:before,
	.stm-shop-sidebar-area .widget_products ul li .meta-wrap .star-rating span:before,
	.stm-shop-sidebar-area .widget_recent_reviews ul li .meta-wrap .star-rating span:before { color: {$base_color}; }
	.stm-shop-sidebar-area .widget_product_search .woocommerce-product-search button { background: {$base_color}; }
	.stm-wcmap-single-product .prod-add-to-cart .cart button { background: {$base_color}; }
	.stm-wcmap-single-product .prod-compare-whish-wrap .prod-compare a:before { color: {$second_color}; }
	.stm-wcmap-single-product .prod-compare-whish-wrap .prod-wishlist a:before { color: {$second_color}; }
	.stm-wcmap-single-product .prod-compare-whish-wrap .prod-wishlist a:hover { color: {$base_color}; }
	.stm-wcmap-single-product .product_images .flex-control-nav li img.flex-active { border-color: {$base_color} !important; }
	.stm-wcmap-single-product .prod-meta .prod-stock { color: #8dd921; }
	.stm-wcmap-single-product .prod-meta .woocommerce-product-rating .star-rating:before,
	.stm-wcmap-single-product .prod-rating .woocommerce-product-rating .star-rating:before { color: {$base_color}; }
	.stm-wcmap-single-product .prod-meta .woocommerce-product-rating .star-rating span:before,
	.stm-wcmap-single-product .prod-rating .woocommerce-product-rating .star-rating span:before { color: {$base_color}; }
	.stm-wcmap-single-product .prod-price .price > .amount, .stm-wcmap-single-product .prod-price .price ins { color: #cc0033; }
	.stm-wcmap-single-product .woocommerce-tabs ul.wc-tabs li a:hover { color: {$base_color}; }
	.stm-wcmap-single-product .woocommerce-tabs ul.wc-tabs li.active a { border-bottom-color: {$base_color}; }
	.stm-wcmap-single-product .woocommerce-tabs #reviews .commentlist li .comment_container .comment-text .review-meta-wrap .author-rating-wrap .rating-wrap .star-rating:before { color: {$base_color}; }
	.stm-wcmap-single-product .woocommerce-tabs #reviews .commentlist li .comment_container .comment-text .review-meta-wrap .author-rating-wrap .rating-wrap .star-rating span:before { color: {$base_color}; }
	.stm-wcmap-single-product .woocommerce-tabs #review_form_wrapper .comment-form .comment-form-rating .stars span a:before { color: {$base_color}; }
	.stm-wcmap-single-product .woocommerce-tabs #review_form_wrapper .comment-form input[type='submit'] { background: {$base_color}; }
	.stm-user-not-logged-in .stm-wcmap-single-product.stm-wcmap__template-1 #review_form_wrapper .comment-form .row .col-md-6 p input:active, .stm-user-not-logged-in .stm-wcmap-single-product.stm-wcmap__template-1 #review_form_wrapper .comment-form .row .col-md-6 p input:hover { border-color: {$base_color}; }
	.stm-user-not-logged-in .stm-wcmap-single-product.stm-wcmap__template-1 #review_form_wrapper .comment-form .comment-form-comment textarea:active, .stm-user-not-logged-in .stm-wcmap-single-product.stm-wcmap__template-1 #review_form_wrapper .comment-form .comment-form-comment textarea:hover, .stm-user-not-logged-in .stm-wcmap-single-product.stm-wcmap__template-1 #review_form_wrapper .comment-form .comment-form-comment textarea:hover { border-color: {$base_color}; }
	.stm-wcmap__template-1 #reviews .commentlist li .comment_container .comment-text .review-meta-wrap .author-rating-wrap .rating-wrap .star-rating:before { color: {$base_color}; }
	.stm-wcmap__template-1 #reviews .commentlist li .comment_container .comment-text .review-meta-wrap .author-rating-wrap .rating-wrap .star-rating span:before { color: {$base_color}; }
	.stm-wcmap__template-1 #reviews #review_form_wrapper .comment-form .comment-form-rating .stars span a:before { color: {$base_color}; }
	.stm-wcmap__template-1 #reviews #review_form_wrapper .comment-form input[type='submit'] { background: {$base_color}; }
				";
wp_add_inline_style( 'stm-wcmap-templates', $custom_css );

if ( is_singular( 'product' ) ) {
	$sp_sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 768, 'wcmap_single_product_sidebar', 768 );
	$sp_template         = apply_filters( 'stm_me_get_nuxy_mod', 'template_1', 'wcmap_single_product_template' );
	$sp_sidebar_position = ( 'template_sidebar' === $sp_template ) ? apply_filters( 'stm_me_get_nuxy_mod', 'left', 'wcmap_single_product_sidebar_position' ) : 'none';

	if ( ! empty( $sp_sidebar_id ) ) {
		$sp_sidebar = get_post( $sp_sidebar_id );
	}

	$stm_sidebar_layout_mode = stm_wcmap_sidebar_layout_mode( $sp_sidebar_position, $sp_sidebar_id );

	?>

	<div class="container">
		<div class="row">
			<?php

			echo wp_kses_post( apply_filters( 'stm_wcmap_content_before_filter', $stm_sidebar_layout_mode['content_before'] ) );

			if ( 'template_1' !== $sp_template ) {
				woocommerce_breadcrumb( array( 'delimiter' => '<i class="fas fa-chevron-right"></i>' ) );
			}

			if ( have_posts() ) {
				while ( have_posts() ) :
					the_post();
					stm_wcmap_get_template( 'templates/' . $sp_template );
				endwhile;
			}

			echo wp_kses_post( apply_filters( 'stm_wcmap_content_after_filter', $stm_sidebar_layout_mode['content_after'] ) );

			if ( 'none' !== $sp_sidebar_position ) {
				echo wp_kses_post( apply_filters( 'stm_wcmap_sidebar_before_filter', $stm_sidebar_layout_mode['sidebar_before'] ) );
				?>

				<div class="stm-shop-sidebar-area">
					<?php
					if ( ! empty( $sp_sidebar_id ) && ! empty( $sp_sidebar->post_content ) ) {
						echo wp_kses_post( apply_filters( 'the_content', $sp_sidebar->post_content ) );
					}
					?>
				</div>

				<?php

				echo wp_kses_post( apply_filters( 'stm_wcmap_sidebar_after_filter', $stm_sidebar_layout_mode['sidebar_after'] ) );
			}
			?>

		</div> <!--row-->

		<div class="other-prod-wrap">
			<?php
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' );
				do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>
	</div> <!--container-->
	<?php
} else {
	$sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 768, 'shop_sidebar' );
	$sidebar_position = apply_filters( 'stm_me_get_nuxy_mod', 'left', 'shop_sidebar_position' );

	if ( ! empty( $sidebar_id ) ) {
		$sp_sidebar = get_post( $sidebar_id );
	}

	$stm_sidebar_layout_mode = stm_wcmap_sidebar_layout_mode( $sidebar_position, $sidebar_id );

	if ( ! is_shop() && ! is_product_category() ) {
		get_template_part( 'partials/title_box' );
	}
	?>

	<div class="container">
		<div class="row">

			<?php echo wp_kses_post( apply_filters( 'stm_wcmap_content_before_filter', $stm_sidebar_layout_mode['content_before'] ) ); ?>
			<?php
			if ( is_singular( 'product' ) ) {

				while ( have_posts() ) :
					the_post();
					wc_get_template_part( 'content', 'single-product' );
					endwhile;

			} else {
				?>
				<?php do_action( 'woocommerce_archive_description' ); ?>

					<?php if ( woocommerce_product_loop() ) : ?>
						<div class="action-bar-wrap">
							<div class="left">
							<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
								<h1 class="page-title">
									<?php woocommerce_page_title(); ?>
								</h1>
							<?php endif; ?>
							</div>
							<div class="right">
								<?php do_action( 'woocommerce_before_shop_loop' ); ?>
							</div>
						</div>
						<?php
							wc_set_loop_prop( 'columns', 3 );
							woocommerce_product_loop_start();
						?>
						<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
							<?php while ( have_posts() ) : ?>
								<?php the_post(); ?>
								<?php wc_get_template_part( 'content', 'product' ); ?>
							<?php endwhile; ?>
						<?php endif; ?>

						<?php woocommerce_product_loop_end(); ?>

						<?php do_action( 'woocommerce_after_shop_loop' ); ?>
					<?php else : ?>
						<?php do_action( 'woocommerce_no_products_found' ); ?>

						<?php
					endif;

			}
			?>
			<?php echo wp_kses_post( apply_filters( 'stm_wcmap_content_after_filter', $stm_sidebar_layout_mode['content_after'] ) ); ?>

			<?php echo wp_kses_post( apply_filters( 'stm_wcmap_sidebar_before_filter', $stm_sidebar_layout_mode['sidebar_before'] ) ); ?>
			<div class="stm-shop-sidebar-area">
				<?php
				if ( ! empty( $sidebar_id ) && ! empty( $sp_sidebar->post_content ) ) {
					echo wp_kses_post( apply_filters( 'the_content', $sp_sidebar->post_content ) );
				}
				?>
			</div>
			<?php echo wp_kses_post( apply_filters( 'stm_wcmap_sidebar_after_filter', $stm_sidebar_layout_mode['sidebar_after'] ) ); ?>

		</div> <!--row-->
	</div> <!--container-->
	<?php
}
get_footer();
