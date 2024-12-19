<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( empty( $posts_per_page ) ) {
	$posts_per_page = 4;
}

$args = array(
	'post_type'        => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'      => 'publish',
	'posts_per_page'   => $posts_per_page,
	'suppress_filters' => 0,
	'order_by'         => 'ID',
	'order'            => $order_by,
);

if ( apply_filters( 'stm_sold_status_enabled', true ) ) {
	$args['meta_query'][] = array(
		'key'     => 'car_mark_as_sold',
		'value'   => '',
		'compare' => '=',
	);
}

$query = new WP_Query( $args );

?>

<div class="stm-inventory-no-filter-wrap">
	<div class="stm-isotope-sorting stm-isotope-sorting-list" data-per-page="<?php echo esc_attr( $posts_per_page ); ?>">
	<?php
	if ( $query->have_posts() ) :

		$template = 'partials/vc_loop/inventory-no-filter-loop';

		while ( $query->have_posts() ) :
			$query->the_post();
			get_template_part( $template );
			endwhile;

		endif;
	?>
	</div>
	<div class="stm_ajax_pagination stm-blog-pagination">
		<?php
		echo wp_kses_post(
			paginate_links(
				array(
					'type'      => 'list',
					'total'     => $query->found_posts / $posts_per_page,
					'prev_text' => '<i class="fas fa-angle-left"></i>',
					'next_text' => '<i class="fas fa-angle-right"></i>',
				)
			)
		);
		?>
	</div>
	<?php wp_reset_postdata(); ?>
</div>
