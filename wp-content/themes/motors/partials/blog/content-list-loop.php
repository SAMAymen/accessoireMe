<div class="post-list-single-item">

	<div class="image">
		<a href="<?php the_permalink(); ?>">
			<!--Sticky Post-->
			<?php if ( is_sticky( get_the_id() ) ) : ?>
				<div class="sticky-post heading-font"><?php esc_html_e( 'Sticky Post', 'motors' ); ?></div>
			<?php endif; ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'stm-img-1110-577', array( 'class' => 'img-responsive' ) ); ?>
			<?php else : ?>
				<img class="img-responsive"
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/tmp/blog_placeholder.png' ); ?>"
					alt="<?php the_title(); ?>"/>
			<?php endif; ?>

			<div class="absoluted-content">
				<div class="relatived-content">
					<!--Video Format-->
					<?php if ( 'video' === get_post_format( get_the_ID() ) ) : ?>
						<div class="video-preview">
							<i class="fas fa-film"></i><?php esc_html_e( 'Video', 'motors' ); ?>
						</div>
					<?php endif; ?>
					<h3 class="title"><?php echo esc_html( apply_filters( 'stm_trim_title', get_the_title(), 85, '...' ) ); ?></h3>
				</div>
			</div>
		</a>
	</div>

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
</div>
