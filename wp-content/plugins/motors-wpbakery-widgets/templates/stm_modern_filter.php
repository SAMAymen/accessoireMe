<?php
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var WPBakeryShortCode $this
 * @var $quant_listing_on_grid
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$args = array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => false,
	'pad_counts' => true,
);

/*Get modern Filter*/
$modern_filter = apply_filters( 'stm_get_car_modern_filter', null );

$query_args = array(
	'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'paged'          => false,
);

$show_sold = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );

if ( false === $show_sold ) {
	$query_args['meta_query'][] = array(
		array(
			'key'     => 'car_mark_as_sold',
			'value'   => '',
			'compare' => '=',
		),
	);
}

$listings = new WP_Query( $query_args );

$listing_filter_position = apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'listing_filter_position' );
if ( ! empty( $_GET['filter_position'] ) && 'right' === $_GET['filter_position'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$listing_filter_position = 'right';
}

$sidebar_pos_classes = '';
$content_pos_classes = '';

if ( 'right' === $listing_filter_position ) {
	$sidebar_pos_classes = 'col-md-push-9 col-sm-push-0';
	$content_pos_classes = 'col-md-pull-3 col-sm-pull-0';
}
?>
<script>
	var stmOptionsObj = {};
</script>

<div class="row" id="modern-filter-listing">
	<div class="col-md-3 col-sm-12 sidebar-sm-mg-bt <?php echo esc_attr( $sidebar_pos_classes ); ?>">
		<?php
		if ( ! empty( $modern_filter ) ) {
			$counter = 0;
			foreach ( $modern_filter as $unit ) {
				$counter++;
				$terms = get_terms( array( $unit['slug'] ), $args );
				$_vars = compact( 'modern_filter', 'unit', 'terms' );

				$unit['listing_rows_numbers_default_expanded'] = 'open';

				if ( ! empty( $unit['numeric'] ) && 'price' !== $unit['slug'] && empty( $unit['slider'] ) ) {
					do_action( 'stm_listings_load_template', 'modern_filter/filters/numeric', $_vars );
				} else {
					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						if ( empty( $unit['slider'] ) && 'price' !== $unit['slug'] ) {
							/*First one if ts not image goes on another view*/
							if ( 1 === $counter && empty( $unit['use_on_car_modern_filter_view_images'] ) && ! $unit['use_on_car_modern_filter_view_images'] ) {
								do_action( 'stm_listings_load_template', 'modern_filter/filters/checkbox', $_vars );
							} else { // if its not first one and have images.
								if ( ! empty( $unit['use_on_car_modern_filter_view_images'] ) ) {
									do_action( 'stm_listings_load_template', 'modern_filter/filters/images', $_vars );
								} else { // all others...
									do_action( 'stm_listings_load_template', 'modern_filter/filters/checkbox', $_vars );
								}
							}
						} else { /*price*/
							if ( 'price' === $unit['slug'] ) {
								do_action( 'stm_listings_load_template', 'modern_filter/filters/price', $_vars );
							} else {
								do_action( 'stm_listings_load_template', 'modern_filter/filters/slider', $_vars );
							} // if terms price not empty
						} // slider price
					}
				} // numberic price
			} // foreach

			if ( $show_sold ) {
				do_action(
					'stm_listings_load_template',
					'modern_filter/filters/sold_filter',
					compact( 'modern_filter', 'unit' )
				);
			}
		} // if modern filter
		?>
	</div>
	<div class="col-md-9 col-sm-12 <?php echo esc_attr( $content_pos_classes ); ?>">
		<div class="stm-car-listing-sort-units stm-modern-filter-actions clearfix">
			<div class="stm-modern-filter-found-cars">
				<h4>
					<span class="orange">
						<?php echo esc_attr( $listings->found_posts ); ?>
					</span>

					<?php esc_html_e( 'Vehicles available', 'motors-wpbakery-widgets' ); ?>
				</h4>
			</div>
			<?php
			$view_list       = '';
			$view_grid       = '';
			$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );
			if ( wp_is_mobile() ) {
				$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
			}
			$view_type = apply_filters( 'stm_listings_input', $nuxy_mod_option, 'view_type' );

			if ( 'list' === $view_type ) {
				$view_list = 'active';
			} elseif ( 'grid' === $view_type ) {
				$view_grid = 'active';
			}
			?>
			<div class="stm-view-by">
				<a href="?view_type=grid" class="stm-modern-view view-grid view-type <?php echo esc_attr( $view_grid ); ?>">
					<i class="stm-icon-grid"></i>
				</a>
				<a href="?view_type=list" class="stm-modern-view view-list view-type <?php echo esc_attr( $view_list ); ?>">
					<i class="stm-icon-list"></i>
				</a>
			</div>
			<div class="stm-sort-by-options clearfix">
				<span><?php esc_html_e( 'Sort by:', 'motors-wpbakery-widgets' ); ?></span>
				<div class="stm-select-sorting">
					<select aria-label="<?php esc_attr_e( 'Sort by:', 'motors-wpbakery-widgets' ); ?>">
						<?php echo wp_kses_post( apply_filters( 'stm_get_sort_options_html', '' ) ); ?>
					</select>
				</div>
			</div>
		</div>
		<div class="modern-filter-badges">
			<ul class="stm-filter-chosen-units-list"></ul>
		</div>
		<?php
		if ( $listings->have_posts() ) :
			if ( 'active' === $view_grid ) :
				?>
				<div class="row row-3 car-listing-row car-listing-modern-grid">
			<?php endif; ?>

			<div class="stm-isotope-sorting">
			<?php
			$template = 'partials/listing-cars/listing-grid-loop';
			if ( 'active' === $view_grid ) {
				if ( apply_filters( 'stm_is_motorcycle', false ) ) {
					$template = 'partials/listing-cars/motos/grid';
				} elseif ( apply_filters( 'stm_is_listing', false ) ) {
					$template = 'partials/listing-cars/listing-grid-directory-loop';
				}
			} else {
				if ( apply_filters( 'stm_is_motorcycle', false ) ) {
					$template = 'partials/listing-cars/motos/list';
				} elseif ( apply_filters( 'stm_is_listing', false ) ) {
					$template = 'partials/listing-cars/listing-list-directory-loop';
				} elseif ( apply_filters( 'stm_is_boats', false ) ) {
					$template = 'partials/listing-cars/listing-list-loop-boats';
				} else {
					$template = 'partials/listing-cars/listing-list-loop';
				}
			}

			$template_args = array(
				'quantity_per_row' => ( ! empty( $quant_listing_on_grid ) ) ? $quant_listing_on_grid : 3,
				'custom_img_size'  => ( ! empty( ${$view_type . '_thumb_img_size'} ) ) ? ${$view_type . '_thumb_img_size'} : null,
				'modern_filter'    => true,
			);

			while ( $listings->have_posts() ) :
				$listings->the_post();
				get_template_part( $template, '', $template_args );
			endwhile;

			wp_reset_postdata();
			?>
				<a class="button stm-show-all-modern-filter stm-hidden-filter">
					<?php esc_html_e( 'Show all', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
			<?php if ( 'active' === $view_grid ) : ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
