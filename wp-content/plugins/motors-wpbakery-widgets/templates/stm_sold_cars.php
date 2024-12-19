<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$view_type = apply_filters( 'stm_listings_input', apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' ), 'view_type' );

if ( 'list' === $view_type && ! empty( $ppp_on_list ) ) {
	$posts_per_page = intval( $ppp_on_list );
} elseif ( 'grid' === $view_type && ! empty( $ppp_on_grid ) ) {
	$posts_per_page = intval( $ppp_on_grid );
} else {
	$posts_per_page = get_option( 'posts_per_page' );
}
?>

<div class="archive-listing-page sold-listings-inventory">
	<div class="container">
		<div class="row">
			<?php
			$filter = apply_filters( 'stm_listings_filter_func', array( 'sold_car' => 'on' ) );

			$sidebar_pos = apply_filters(
				'stm_get_sidebar_position',
				array(
					'sidebar' => 'left',
					'content' => '',
				)
			);
			$sidebar_id  = apply_filters( 'motors_vl_get_nuxy_mod', 'no_sidebar', 'listing_sidebar' );
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
			<div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt <?php echo esc_attr( $sidebar_pos['sidebar'] ); ?>">
				<?php
				if ( apply_filters( 'stm_is_motorcycle', false ) ) :
					$sidebar_template = ( wp_is_mobile() ) ? 'filter/sidebar-mobile' : 'motorcycles/filter/sidebar';
				elseif ( apply_filters( 'stm_is_dealer_two', false ) ) :
					$sidebar_template = ( wp_is_mobile() ) ? 'filter/sidebar-mobile' : 'classified/filter/sidebar';
				else :
					$sidebar_template = ( wp_is_mobile() ) ? 'filter/sidebar-mobile' : 'filter/sidebar';
				endif;

				do_action(
					'stm_listings_load_template',
					$sidebar_template,
					array(
						'filter' => $filter,
						'action' => 'listings-sold',
					)
				);
				?>
				<!--Sidebar-->
				<div class="stm-inventory-sidebar">
					<?php
					if ( 'default' === $sidebar_id ) {
						get_sidebar();
					} elseif ( ! empty( $sidebar_id ) ) {
						echo apply_filters( 'the_content', $blog_sidebar->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<style type="text/css">
							<?php echo esc_attr( get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true ) ); ?>
						</style>
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-md-9 col-sm-12 <?php echo esc_attr( $sidebar_pos['content'] ); ?>">
				<div class="stm-ajax-row">
					<?php do_action( 'stm_listings_load_template', 'classified/filter/actions', array( 'filter' => $filter ) ); ?>
					<div id="listings-result" data-type="sold-car">
						<?php
						do_action(
							'stm_listings_load_results',
							array(
								'sold_car'       => 'on',
								'posts_per_page' => $posts_per_page,
							),
							'sold_car'
						);
						?>
					</div>
				</div>
			</div> <!--col-md-9-->

		</div>
	</div>
</div>

<?php wp_reset_postdata(); ?>
