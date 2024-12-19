<?php
// Get sidebar settings
$sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 'default', 'sidebar' );
$sidebar_position = apply_filters( 'stm_me_get_nuxy_mod', 'right', 'sidebar_position' );

if ( ! empty( $_GET['sidebar-position'] ) && 'left' === $_GET['sidebar-position'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$sidebar_position = 'left';
}

if ( ! empty( $_GET['sidebar-position'] ) && 'right' === $_GET['sidebar-position'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$sidebar_position = 'right';
}

if ( ! empty( $_GET['sidebar-position'] ) && 'none' === $_GET['sidebar-position'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$sidebar_id = false;
}

$view_type = apply_filters( 'stm_me_get_nuxy_mod', 'grid', 'view_type' );

if ( ! empty( $_GET['view-type'] ) && 'grid' === $_GET['view-type'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$view_type = 'grid';
}

if ( ! empty( $_GET['view-type'] ) && 'list' === $_GET['view-type'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$view_type = 'list';
}

if ( ! empty( $sidebar_id ) ) {
	$blog_sidebar = get_post( $sidebar_id );
}

if ( ! is_numeric( $sidebar_id ) && ( 'no_sidebar' === $sidebar_id || ! is_active_sidebar( $sidebar_id ) ) ) {
	$sidebar_id = false;
}

if ( is_numeric( $sidebar_id ) && empty( $blog_sidebar->post_content ) ) {
	$sidebar_id = false;
}

$stm_sidebar_layout_mode = stm_sidebar_layout_mode( $sidebar_position, $sidebar_id );

$tpl = '';
if ( 'grid' === $view_type ) {
	$tpl = ( ! apply_filters( 'stm_is_magazine', true ) ) ? 'partials/blog/content-grid' : 'partials/blog/content-grid-magazine';
} else {
	$tpl = ( ! apply_filters( 'stm_is_magazine', true ) ) ? 'partials/blog/content-list' : 'partials/blog/content-list-magazine';
}

get_header();

if ( ! apply_filters( 'stm_is_magazine', true ) ) {
	get_template_part( 'partials/title_box' );
} else {
	get_template_part( 'partials/magazine/content/breadcrumbs' );
}
?>

	<div class="stm-archives stm-view-type-<?php echo esc_attr( $view_type ); ?>">
		<div class="container">
			<div class="row
		<?php
		if ( apply_filters( 'stm_is_magazine', false ) ) {
			echo esc_html( 'sb-' . $sidebar_position );
		}
		?>
		">
				<?php if ( have_posts() ) : ?>
					<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_before'] ); ?>
					<?php echo wp_kses_post( $stm_sidebar_layout_mode['show_title'] ); ?>
					<?php
					if ( apply_filters( 'stm_is_magazine', false ) ) :
						get_template_part( 'partials/magazine/content/title_box_magazine_archive' );
					endif;
					?>

					<?php if ( 'grid' === $view_type ) : ?>
						<div class="row row-<?php echo esc_attr( $stm_sidebar_layout_mode['default_row'] ); ?>">
					<?php endif; ?>

					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<?php get_template_part( $tpl, 'loop' ); ?>
					<?php endwhile; ?>

					<?php if ( 'grid' === $view_type ) : ?>
						</div>
					<?php endif; ?>

					<!--Pagination-->
					<?php stm_custom_pagination(); ?>

					<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_after'] ); ?>

					<!--Sidebar-->
					<?php
					if ( 'default' === $sidebar_id ) {
						echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );
						get_sidebar();
						echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] );
					} elseif ( ! empty( $sidebar_id ) ) {
						echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );

						if ( class_exists( \Elementor\Plugin::class ) && is_numeric( $sidebar_id ) ) :
							apply_filters( 'motors_render_elementor_content', $sidebar_id );
						else :
							echo apply_filters( 'the_content', $blog_sidebar->post_content ); //phpcs:ignore
						endif;

						echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] );

						if ( ! class_exists( \Elementor\Plugin::class ) ) :
							?>
							<style type="text/css">
								<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true );//phpcs:ignore ?>
							</style>
							<?php
						endif;
					}
					?>
				<?php else : ?>
					<div class="col-md-12">
						<h3 class="text-transform nothing found"><?php esc_html_e( 'No Results', 'motors' ); ?></h3>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
get_footer();

