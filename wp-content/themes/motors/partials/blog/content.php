<?php
motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2' ) );

$title_tag        = ( empty( get_post_meta( get_the_ID(), 'stm_title_tag', true ) ) ) ? 'h1' : get_post_meta( get_the_ID(), 'stm_title_tag', true );
$vc_status        = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );
$elementor_status = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

if ( 'true' === $vc_status || 'builder' === $elementor_status ) : ?>

	<div id="post-<?php get_the_ID(); ?>" <?php post_class(); ?>>
		<div class="">
			<div class="post-content post-content-vc">
				<?php the_content(); ?>
			</div>
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
		</div>
	</div>

<?php else : ?>

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
	<?php
	echo wp_kses_post( $stm_sidebar_layout_mode['content_before'] );
	?>

	<!--Title-->
	<<?php echo esc_attr( $title_tag ); ?> class="post-title">
	<?php the_title(); ?>
	</<?php echo esc_attr( $title_tag ); ?>>

	<!--Post thumbnail-->
	<?php if ( 'video' !== get_post_format() && has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'stm-img-1110-577', array( 'class' => 'img-responsive' ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( 'video' !== get_post_format() ) : ?>
		<!--Blog meta-->
		<div class="blog-meta clearfix">
			<div class="left">
				<div class="clearfix">
					<div class="blog-meta-unit h6">
						<i class="stm-icon-date"></i>
						<span><?php echo get_the_date(); ?></span>
					</div>
					<div class="blog-meta-unit h6">
						<i class="stm-icon-author"></i>
						<span><?php esc_html_e( 'Posted by:', 'motors' ); ?></span>
						<span><?php the_author(); ?></span>
					</div>
				</div>
			</div>
			<div class="right">
				<div class="blog-meta-unit h6">
					<a href="<?php comments_link(); ?>" class="post_comments h6">
						<i class="stm-icon-message"></i> <?php comments_number(); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( 'video' !== get_post_format() && has_excerpt( get_the_ID() ) ) : ?>
		<div class="stm-excerpt">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>

	<div class="post-content">
		<?php the_content(); ?>
		<div class="clearfix"></div>
	</div>

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
				<!--Categories-->
				<?php $cats = get_the_category( get_the_id() ); ?>
				<?php if ( ! empty( $cats ) ) : ?>
					<div class="post-cat">
						<span class="h6"><?php esc_html_e( 'Category:', 'motors' ); ?></span>
						<?php foreach ( $cats as $cat ) : ?>
							<span class="post-category">
										<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><span><?php echo esc_html( $cat->name ); ?></span></a><span
										class="divider">,</span>
									</span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!--Tags-->
				<?php if ( $tags = wp_get_post_tags( get_the_ID() ) ) {//phpcs:ignore ?>
					<div class="post-tags">

						<span class="h6"><?php esc_html_e( 'Tags:', 'motors' ); ?></span>
						<span class="post-tag">
							<?php echo get_the_tag_list( '', ', ', '' );//phpcs:ignore ?>
						</span>
					</div>
				<?php } ?>
			</div>

			<div class="right">
				<div class="stm-shareble stm-single-car-link">
					<a
							href="#"
							class="car-action-unit stm-share"
							title="<?php esc_html_e( 'Share this', 'motors' ); ?>"
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
		<div class="stm-author-box clearfix">
			<div class="author-image">
				<?php echo get_avatar( get_the_author_meta( 'email' ), 86 ); ?>
			</div>
			<div class="author-content">
				<h6><?php esc_html_e( 'Author:', 'motors' ); ?></h6>
				<h4><?php the_author_meta( 'nickname' ); ?></h4>
				<div class="author-description"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></div>
			</div>
		</div>
	<?php endif; ?>

	<!--Comments-->
	<?php if ( comments_open() || get_comments_number() ) { ?>
		<div class="stm_post_comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

	<?php echo wp_kses_post( $stm_sidebar_layout_mode['content_after'] ); ?>


	<!--Sidebar-->
	<?php
	if ( ! is_numeric( $sidebar_id ) && 'no_sidebar' !== $sidebar_id ) {
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );
		dynamic_sidebar( $sidebar_id );
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_after'] );
	} elseif ( ! empty( $sidebar_id ) && ! empty( $blog_sidebar ) && ! empty( $blog_sidebar->post_content ) ) {
		echo wp_kses_post( $stm_sidebar_layout_mode['sidebar_before'] );

		if ( class_exists( \Elementor\Plugin::class ) && is_numeric( $sidebar_id ) ) :
			apply_filters( 'motors_render_elementor_content', $sidebar_id );
		else :
			echo apply_filters( 'the_content', $blog_sidebar->post_content );//phpcs:ignore
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
	</div>
<?php endif; ?>
