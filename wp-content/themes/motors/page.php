<?php
if ( apply_filters( 'stm_is_rental_two', false ) ) {
	if ( stm_is_woocommerce_activated() && ( is_checkout() || is_cart() ) ) {
		do_action( 'stm_mcr_reservation_archive' );
	} else {
		get_header();
		echo '<div class="container"><div class="page-content-wrap">';
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
		endif;
		echo '</div></div>';
		get_footer();
	}
} elseif ( apply_filters( 'stm_is_ulisting_layout', false ) ) {
	get_header();
	?>
	<div class="container">
	<?php

	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	endif;

	?>
	</div>
	<?php
	get_footer();
} else {
	if ( apply_filters( 'stm_is_rental', false ) && stm_is_woocommerce_activated() && ( is_checkout() || is_cart() ) ) {
		get_template_part( 'partials/rental/reservation', 'archive' );
		return false;
	}

	get_header();

	if ( ! apply_filters( 'stm_is_auto_parts', false ) && ! is_front_page() ) {
		get_template_part( 'partials/page_bg' );
		get_template_part( 'partials/title_box' );
	}

	do_action( 'stm_wcmap_title_box' );

	// Get compare page.
	$compare_page = null;
	if ( ! stm_motors_is_unit_test_mod() ) {
		$compare_page = apply_filters( 'stm_me_get_nuxy_mod', 156, 'compare_page' );
	}

	$compare_page = apply_filters( 'stm_motors_wpml_is_page', $compare_page );

	if ( ! empty( $compare_page ) && get_the_ID() === intval( $compare_page ) ) :
		$vc_status        = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );
		$elementor_status = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

		if ( 'true' === $vc_status || 'builder' === $elementor_status ) {
			if ( have_posts() ) {
				echo '<div class="container">';
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				echo '</div>';
			}
		} else {
			do_action( 'stm_listings_load_template', 'compare/compare' );
		}
	else :
		?>
		<div class="container">

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			endif;
			?>

			<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'motors' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'motors' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				)
			);
			?>

			<div class="clearfix">
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
		<?php
	endif;
	get_footer();
}
