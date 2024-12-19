<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $number_of_posts ) ) {
	$number_of_posts = 3;
}

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $number_of_posts,
);

$r = new WP_Query( $args );

?>
<?php if ( $r->have_posts() ) : ?>
	<div class="stm-boats-latest-news row row-3">
		<?php
		while ( $r->have_posts() ) :
			$r->the_post();
			?>
			<div class="col-md-4 col-sm-4">
				<div class="single-latest-news">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="image">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php the_post_thumbnail( 'stm-img-350', array( 'class' => 'img-responsive' ) ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="content-unit">
						<div class="date heading-font">
							<div class="day"><?php echo get_the_date( 'd' ); ?></div>
							<div class="month"><?php echo get_the_date( 'M' ); ?></div>
						</div>

						<div class="content">
							<div class="title heading-font">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
							</div>
							<div class="excerpt">
								<?php echo wp_kses_post( get_the_excerpt() ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
<?php endif; ?>
