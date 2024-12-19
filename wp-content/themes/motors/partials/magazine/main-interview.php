<?php
$sidebar_id       = apply_filters( 'stm_me_get_nuxy_mod', 'default', 'sidebar_blog' );
$sidebar_position = apply_filters( 'stm_me_get_nuxy_mod', 'left', 'sidebar_position' );

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

?>
<div class="row">
	<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_before'] ); ?>

	<?php get_template_part( 'partials/magazine/content/top-content-interview' ); ?>

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

	<div class="blog-meta-bottom">
		<div class="clearfix">
			<div class="left">
				<!--Tags-->
				<?php
				$tags = wp_get_post_tags( get_the_ID() );
				if ( $tags ) {
					?>
					<div class="post-tags">
						<span class="h6"><?php esc_html_e( 'Tags', 'motors' ); ?></span>
						<span class="post-tag">
							<?php echo get_the_tag_list( '', '', '' );//phpcs:ignore ?>
						</span>
					</div>
				<?php } ?>
			</div>

			<div class="right">
				<div class="stm-shareble stm-single-car-link">
					<a
						href="#"
						class="car-action-unit stm-share"
						title="<?php esc_attr_e( 'Share this', 'motors' ); ?>"
						download>
						<i class="stm-icon-share"></i>
						<?php esc_html_e( 'Share this', 'motors' ); ?>
					</a>
					<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) && ! get_post_meta( get_the_ID(), 'sharing_disabled', true ) ) : ?>
						<div class="stm-a2a-popup">
							<?php echo apply_filters( 'stm_add_to_any_shortcode', get_the_ID() );//phpcs:ignore ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!--Author info-->
	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<div class="stm-author-box clearfix author-info-wrap">
			<div class="author-image">
				<?php echo get_avatar( get_the_author_meta( 'email' ), 86 ); ?>
			</div>
			<div class="author-content">
				<h6><?php esc_html_e( 'Author:', 'motors' ); ?></h6>
				<h4><?php the_author_meta( 'display_name' ); ?></h4>
				<div class="author-description"><?php echo get_the_author_meta( 'description' );//phpcs:ignore ?></div>
			</div>
		</div>
	<?php endif; ?>

	<!--Previous Next Post Pagination-->
	<?php get_template_part( 'partials/magazine/content/previous_next_pagination' ); ?>

	<!--Comments-->
	<?php if ( comments_open() || get_comments_number() ) { ?>
		<div class="stm_post_comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

	<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_after'] ); ?>


	<!--Sidebar-->
	<?php
	if ( 'default' === $sidebar_id ) {
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );
		get_sidebar();
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] );
	} elseif ( ! empty( $sidebar_id ) ) {
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );
		echo wp_kses_post( apply_filters( 'the_content', $blog_sidebar->post_content ) );
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] );
		?>
		<style type="text/css">
			<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true );//phpcs:ignore ?>
		</style>
		<?php
	}
	?>
</div>
