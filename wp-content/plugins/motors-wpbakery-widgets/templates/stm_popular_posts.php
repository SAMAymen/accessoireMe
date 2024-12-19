<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
wp_reset_postdata();

$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'posts_per_page'      => ( ! empty( $number_of_posts ) ) ? $number_of_posts : 3,
		'offset'              => 3,
		'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'stm_car_views',
				'value'   => '0',
				'compare' => '!=',
			),
		),
		'orderby'             => 'meta_value',
		'order'               => 'DESC',
	)
);

?>

<div class="stm-most-popular-posts">
	<h2><?php echo esc_html( $popular_title ); ?></h2>
	<?php
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			get_template_part( 'partials/vc_loop/popular_loop' );
		}

		wp_reset_postdata();
	}
	?>
</div>
