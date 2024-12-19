<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$query = new WP_Query(
	array(
		'post_type'           => 'any',
		'post_status'         => 'publish',
		'posts_per_page'      => $number_of_posts,
		'ignore_sticky_posts' => true,
		'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => array( 'post-format-video' ),
			),
		),
	)
);

?>

<div class="stm-recent_videos-posts-main">
	<div class="container">
		<h2>
			<?php echo esc_html( $recent_video_title ); ?>
		</h2>
		<div class="recent-owl-nav">
			<div class="prev">
				<i class="fas fa-angle-left"></i>
			</div>
			<div class="next">
				<i class="fas fa-angle-right"></i>
			</div>
		</div>
	</div>
	<div class="recent_videos_posts_wrap owl-carousel">
		<?php
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'partials/vc_loop/recent_videos_loop' );
			}
			wp_reset_postdata();
		}
		?>
	</div>
</div>

<style>
	.recent_videos_posts_wrap .owl-nav.disabled {
		display: none!important;
	}
</style>

<script>
	(function ($) {
		$(window).on('load', function () {
			setTimeout(function () {
				$('.recent_videos_posts_wrap .owl-nav.disabled').remove();
			}, 500);
		});
	})(jQuery);
</script>
