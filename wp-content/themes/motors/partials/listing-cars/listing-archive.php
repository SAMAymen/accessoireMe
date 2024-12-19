<div class="row">

	<?php
	$sidebar_pos = apply_filters(
		'stm_get_sidebar_position',
		array(
			'sidebar' => 'left',
			'content' => '',
		)
	);

	$filter = apply_filters( 'stm_listings_filter_func', null, true );

	$sidebar_id = apply_filters( 'motors_vl_get_nuxy_mod', 'no_sidebar', 'listing_sidebar' );
	if ( ! empty( $sidebar_id ) ) {
		$blog_sidebar = get_post( $sidebar_id );
	}

	if ( ! is_numeric( $sidebar_id ) && ( 'no_sidebar' === $sidebar_id || ! is_active_sidebar( $sidebar_id ) ) ) {
		$sidebar_id = false;
	}

	if ( is_numeric( $sidebar_id ) && empty( $blog_sidebar->post_content ) ) {
		$sidebar_id = false;
	}
	?>
	<div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt
	<?php
	if ( class_exists( 'Elementor\Plugin' ) ) {
		echo 'motors-elementor-widget';
	}
	?>
	<?php echo esc_attr( $sidebar_pos['sidebar'] ); ?>">
		<?php
		$sidebar_template = ( wp_is_mobile() ) ? 'filter/sidebar-mobile' : 'filter/sidebar';
		do_action( 'stm_listings_load_template', $sidebar_template );
		?>
		<!--Sidebar-->
		<div class="stm-inventory-sidebar">
			<?php
			if ( 'default' === $sidebar_id ) {
				get_sidebar();
			} elseif ( ! empty( $sidebar_id ) ) {
				if ( class_exists( \Elementor\Plugin::class ) && is_numeric( $sidebar_id ) ) :
					apply_filters( 'motors_render_elementor_content', $sidebar_id );
				else :
					echo apply_filters( 'the_content', $blog_sidebar->post_content );//phpcs:ignore
				endif;

				if ( ! class_exists( \Elementor\Plugin::class ) ) :
					?>
					<style type="text/css">
						<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true );//phpcs:ignore ?>
					</style>
					<?php
				endif;
			}
			?>
		</div>
	</div>

	<div class="col-md-9 col-sm-12 <?php echo esc_attr( $sidebar_pos['content'] ); ?>">

		<div class="stm-ajax-row">
			<?php do_action( 'stm_listings_load_template', 'filter/actions', array( 'filter' => $filter ) ); ?>

			<div id="listings-result">
				<?php
				do_action(
					'stm_listings_load_results',
					array(
						'custom_img_size' => ( ! empty( $args['custom_img_size'] ) ) ? $args['custom_img_size'] : null,
					)
				);

				wp_reset_query(); //phpcs:ignore
				?>
			</div>
		</div>

	</div> <!--col-md-9-->
</div>
