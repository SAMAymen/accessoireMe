<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( empty( $per_page ) ) {
	$per_page = 3; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

$args = array(
	'post_type'      => 'post',
	'status'         => 'publish',
	'posts_per_page' => $per_page,
);

$query = new WP_Query( $args );

$img = wp_get_attachment_image_src( $advert_image, 'full' );
$img = ( isset( $img[0] ) ) ? $img[0] : '';

?>

	<?php if ( $query->have_posts() ) : ?>
		<div class="row row-3">
			<?php
			$i = 0;
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'partials/blog/grid', 'loop' );

				if ( ! empty( $show_advert ) && ! empty( $img ) && ( 1 === $i || ( $query->found_posts < 2 ) ) ) {
					?>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="post-grid-single-unit">
							<div class="image advert">
								<a href="<?php echo esc_url( $advert_link ); ?>">
									<img src="<?php echo esc_url( $img ); ?>" />
								</a>
							</div>
						</div>
					</div>
					<?php
				}

				$i++;
			endwhile;
			?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
