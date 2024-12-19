<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// Creating custom query for each tab.
$args                 = array(
	'post_type'      => apply_filters( 'stm_listings_multi_type', array( 'listings' ) ),
	'post_status'    => 'publish',
	'posts_per_page' => $per_page,
);
$args['meta_query'][] = array(
	'key'     => 'car_mark_as_sold',
	'value'   => '',
	'compare' => '=',
);

$listing_cars = new WP_Query( $args );

$random_number = wp_rand( 1, 99999 );

?>

<div id="ev_recent_<?php echo esc_attr( $random_number ); ?>" class="ev_recent_listings_grid <?php echo esc_attr( $css_class ); ?>">
	<div class="title_nav">
		<?php if ( ! empty( $title ) ) : ?>
			<div class="title heading-font" 
			<?php
			if ( ! empty( $title_color ) ) {
				?>
				style="color: <?php echo esc_attr( $title_color ); ?>" <?php } ?>>
				<?php echo esc_html( $title ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $show_view_all_btn ) && 'yes' === $show_view_all_btn ) : ?>
			<div class="all_listings">
				<a href="<?php echo ( ! empty( $view_all_link ) ) ? esc_url( $view_all_link ) : '#!'; ?>" class="view_all_button">
					<?php esc_html_e( 'View all inventory', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $listing_cars->have_posts() ) : ?>
		<div class="row car-listing-row">
			<?php
			while ( $listing_cars->have_posts() ) :
				$listing_cars->the_post();
				?>
				<?php get_template_part( 'partials/car-filter-ev', 'loop' ); ?>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</div>

<script>
	(function ($) {
		$(document).on('ready', function () {
			var heights = [];
			var selector = $('#ev_recent_<?php echo esc_js( $random_number ); ?> .car-listing-row .ev-filter-loop .listing-car-item-meta');
			if(typeof selector !== undefined) {
				selector.each(function(){
					heights.push($(this).outerHeight());
				});

				if(heights.length > 0) {
					var min_height = Math.max.apply(Math, heights);
					if(min_height > 0) {
						selector.each(function(){
							$(this).css('min-height', min_height +'px');
						});
					}
				}
			}
		});
	})(jQuery);
</script>
