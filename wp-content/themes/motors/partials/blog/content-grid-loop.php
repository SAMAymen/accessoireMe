<?php
motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2' ) );
$sidebar_id       = apply_filters( 'motors_vl_get_nuxy_mod', false, 'sidebar' );
$sidebar_position = apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'sidebar_position' );

if ( ! empty( $_GET['sidebar-position'] ) and $_GET['sidebar-position'] == 'left' ) { //phpcs:ignore
	$sidebar_position = 'left';
}

if ( ! empty( $_GET['sidebar-position'] ) and $_GET['sidebar-position'] == 'right' ) {//phpcs:ignore
	$sidebar_position = 'right';
}

if ( ! empty( $_GET['sidebar-position'] ) and $_GET['sidebar-position'] == 'none' ) {//phpcs:ignore
	$sidebar_id = false;
}

if ( 'no_sidebar' === $sidebar_id ) {
	$sidebar_id = false;
}

$stm_sidebar_layout_mode = stm_sidebar_layout_mode( $sidebar_position, $sidebar_id );

$blog_show_excerpt = apply_filters( 'stm_me_get_nuxy_mod', false, 'blog_show_excerpt' );

$img_size = 'stm-img-350';

?>
<div class="<?php echo esc_attr( $stm_sidebar_layout_mode['default_col'] ); ?>">
	<div class="post-grid-single-unit
		<?php
		if ( is_sticky( get_the_id() ) ) {
			echo 'sticky-wrap';
		}
		?>
	">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<a href="<?php the_permalink(); ?>">
					<!--Video Format-->
					<?php if ( 'video' === get_post_format( get_the_ID() ) ) : ?>
						<div class="video-preview">
							<i class="fas fa-film"></i><?php esc_html_e( 'Video', 'motors' ); ?>
						</div>
					<?php endif; ?>
					<!--Sticky Post-->
					<?php if ( is_sticky( get_the_id() ) ) : ?>
						<div class="sticky-post heading-font"><?php esc_html_e( 'Sticky Post', 'motors' ); ?></div>
					<?php endif; ?>
					<?php
					if ( 2 === $stm_sidebar_layout_mode['default_row'] ) {
						the_post_thumbnail( 'stm-img-398', array( 'class' => 'img-responsive' ) );
					} else {
						the_post_thumbnail( $img_size, array( 'class' => 'img-responsive' ) );
					}
					?>
				</a>
			</div>
		<?php else : ?>
			<?php if ( is_sticky( get_the_id() ) ) : ?>
				<div class="sticky-post blog-post-no-image heading-font"><?php esc_html_e( 'Sticky', 'motors' ); ?></div>
			<?php endif; ?>
		<?php endif; ?>
		<div class="content">
			<?php if ( empty( $title ) ) : ?>
			<a href="<?php the_permalink(); ?>">
				<?php endif; ?>
				<div class="title-relative">
					<?php if ( ! empty( $title ) ) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php endif; ?>
						<?php $title = apply_filters( 'stm_trim_title', get_the_title(), 85, '...' );//phpcs:ignore ?>
						<?php if ( ! empty( $title ) ) : ?>
							<h4 class="title"><?php echo esc_attr( $title ); ?></h4>
						<?php endif; ?>
						<?php if ( ! empty( $title ) ) : ?>
					</a>
				<?php endif; ?>
				</div>
				<?php if ( empty( $title ) ) : ?>
			</a>
		<?php endif; ?>
			<?php if ( $blog_show_excerpt ) : ?>
				<div class="blog-posts-excerpt">
					<?php the_excerpt(); ?>
					<div>
						<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'motors' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
			<div class="post-meta-bottom">
				<div class="blog-meta-unit">
					<i class="stm-icon-date"></i>
					<span><?php echo get_the_date(); ?></span>
				</div>
				<div class="blog-meta-unit comments">
					<a href="<?php comments_link(); ?>" class="post_comments">
						<i class="stm-icon-message"></i> <?php comments_number(); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
