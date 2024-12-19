<?php get_header(); ?>

<?php get_template_part( 'partials/title_box' ); ?>

<div class="container">
	<?php if ( have_posts() ) : ?>
		<div class="stm-services-archive-page">
			<div class="row row-3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12">
						<div class="stm-service-unit">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php
								if ( has_post_thumbnail() ) :

									// Get icon and bg color
									$icon    = get_post_meta( get_the_id(), 'icon', true );
									$icon_bg = get_post_meta( get_the_id(), 'icon_bg', true );
									if ( empty( $icon_bg ) ) {
										$icon_bg = '#6c98e1';
									}
									?>
									<div class="image">
										<?php if ( ! empty( $icon ) ) : ?>
											<div class="icon" style="background-color: <?php echo esc_attr( $icon_bg ); ?>">
												<i class="<?php echo esc_attr( $icon ); ?>"></i>
											</div>
										<?php endif; ?>
										<?php the_post_thumbnail( 'stm-img-350', array( 'class' => 'img-responsive' ) ); ?>
									</div>
								<?php endif; ?>
								<div class="stm-service-meta">
									<div class="title h5">
										<?php the_title(); ?>
									</div>
									<div class="excerpt">
										<?php the_excerpt(); ?>
									</div>
								</div>
							</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		<?php
		echo wp_kses_post(
			paginate_links(
				array(
					'type'      => 'list',
					'prev_text' => '<i class="fas fa-angle-left"></i>',
					'next_text' => '<i class="fas fa-angle-right"></i>',
				)
			)
		);
		?>

	<?php endif; ?>
</div>

<?php get_footer(); ?>
