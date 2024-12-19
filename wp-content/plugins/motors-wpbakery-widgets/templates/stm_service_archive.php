<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $image_size ) ) {
	$image_size = '350x205';
}

$thumbnail = '';
$stm_paged = get_query_var( 'paged', 1 );
$services  = new WP_Query(
	array(
		'post_type'      => 'service',
		'posts_per_page' => $per_page,
		'paged'          => $stm_paged,
	)
);

?>

<?php if ( $services->have_posts() ) : ?>
	<div class="stm-services-archive-page">
		<div class="row row-3">
			<?php
			while ( $services->have_posts() ) :
				$services->the_post();
				?>
				<div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12">
					<div class="stm-service-unit">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php
							if ( has_post_thumbnail() ) :
								$post_thumbnail_id = get_post_thumbnail_id( get_the_id() );
								$post_thumbnail    = wpb_getImageBySize(
									array(
										'attach_id'  => $post_thumbnail_id,
										'thumb_size' => $image_size,
									)
								);
								$thumbnail         = $post_thumbnail['thumbnail'];

								// Get icon and bg color.
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
									<?php echo wp_kses_post( $thumbnail ); ?>
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
				'total'     => $services->max_num_pages,
				'prev_text' => '<i class="fas fa-angle-left"></i>',
				'next_text' => '<i class="fas fa-angle-right"></i>',
			)
		)
	);
	?>

<?php endif; ?>
<?php wp_reset_postdata(); ?>
