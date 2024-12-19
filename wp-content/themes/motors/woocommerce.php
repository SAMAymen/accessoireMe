<?php if ( ! apply_filters( 'stm_is_auto_parts', true ) ) : ?>
	<?php if ( apply_filters( 'stm_is_rental', false ) ) : ?>
		<?php
		if ( ! apply_filters( 'stm_is_rental_two', false ) ) {
			get_template_part( 'partials/rental/reservation', 'archive' );
		} else {
			do_action( 'stm_mcr_reservation_archive' );
		}
		?>
	<?php else : ?>
		<?php get_header(); ?>
		<?php
		$sp_sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 'shop', 'shop_sidebar' );
		$sp_sidebar_position = apply_filters( 'stm_me_get_nuxy_mod', 'left', 'shop_sidebar_position' );

		if ( ! empty( $sp_sidebar_id ) && is_numeric( $sp_sidebar_id ) ) {
			$sp_sidebar = get_post( $sp_sidebar_id );
		}

		$stm_sidebar_layout_mode = stm_sidebar_layout_mode( $sp_sidebar_position, $sp_sidebar_id );
		?>

		<?php get_template_part( 'partials/title_box' ); ?>

		<div class="container">
			<div class="row">
				<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_before'] ); ?>
				<?php
				if ( have_posts() ) {
					woocommerce_content();
				}
				?>
				<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_after'] ); ?>

				<?php echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] ); ?>
				<div class="stm-shop-sidebar-area">
					<?php
					if ( ! empty( $sp_sidebar_id ) && ! empty( $sp_sidebar->post_content ) ) {
						echo wp_kses_post( apply_filters( 'the_content', $sp_sidebar->post_content ) );
					} elseif ( ! empty( $sp_sidebar_id ) && empty( $sp_sidebar->post_content ) ) {
						dynamic_sidebar( $sp_sidebar_id );
					}
					?>
				</div>
				<?php echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] ); ?>
			</div> <!--row-->
		</div> <!--container-->
		<?php get_footer(); ?>
	<?php endif; ?>
	<?php
else :
		do_action( 'stm_wcmap_single_product_view' );
endif;
?>
