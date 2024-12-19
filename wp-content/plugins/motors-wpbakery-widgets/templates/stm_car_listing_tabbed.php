<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class   = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$filter_cats = array();

if ( ! empty( $taxonomy ) ) {
	$stm_tax    = str_replace( ' ', '', $taxonomy );
	$taxonomies = explode( ',', $stm_tax );
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $categories ) {
			if ( ! empty( $categories ) ) {
				$filter_cats[] = explode( '|', $categories );
			}
		}
	}
}

if ( ! empty( $filter_selected ) ) {
	$filter_selected = explode( ',', $filter_selected );
}

if ( empty( $per_page ) ) {
	$per_page = 8; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

if ( empty( $tab_affix ) ) {
	$tab_affix = '';
}

if ( empty( $tab_preffix ) ) {
	$tab_preffix = '';
} else {
	$tab_preffix = $tab_preffix . ' ';
}

if ( ! empty( $enable_search ) && $enable_search ) {
	// Get columns number.
	if ( empty( $filter_columns_number ) ) {
		$filter_columns_number = 2;
	}

	$filter_columns_number = 12 / $filter_columns_number;

	// get options with "Use on car filter" enabled.
	$filter = apply_filters( 'stm_listings_filter_func', null, true );

	$get_post_args = array(
		'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	);
	$all_cars      = new WP_Query( $get_post_args );
	wp_reset_postdata();
}

if ( empty( $search_label ) ) {
	$search_label = esc_html__( 'Search inventory', 'motors-wpbakery-widgets' );
}

// Mileage sorting.
if ( ! function_exists( 'mileage_sort' ) ) {
	function mileage_sort( $a, $b ) {
		if ( $a->slug === $b->slug ) {
			return false;
		}
		return ( ( $a->slug < $b->slug ) ? -1 : 1 );
	}
}

// Search options.
$random_int       = wp_rand( 1, 99999 );
$tab_unique       = 'listing-cars-id-' . $random_int;
$tab_unique_found = 'found-cars-' . $random_int;

$search_icon_class               = ( ! empty( $search_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $search_icon_color ) : '';
$call_to_action_icon_right_class = ( ! empty( $right_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $right_icon_color ) : '';
$cta_icon_class                  = ( ! empty( $action_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $action_icon_color ) : '';

?>

<style>
	<?php if ( ! empty( $search_icon_color ) ) : ?>
		.<?php echo esc_attr( $search_icon_class ); ?>::before {
			color: <?php echo esc_attr( $search_icon_color ); ?>;
		}
	<?php endif; ?>

	<?php if ( ! empty( $right_icon_color ) ) : ?>
		.<?php echo esc_attr( $call_to_action_icon_right_class ); ?>::before {
			color: <?php echo esc_attr( $right_icon_color ); ?>;
		}
	<?php endif; ?>

	<?php if ( ! empty( $action_icon_color ) ) : ?>
		.<?php echo esc_attr( $cta_icon_class ); ?>::before {
			color: <?php echo esc_attr( $action_icon_color ); ?>;
		}
	<?php endif; ?>
</style>

<div class="car-listing-tabs-unit <?php echo esc_attr( $tab_unique ); ?>">
	<div class="car-listing-top-part">
		<div class="found-cars-cloned <?php echo esc_attr( $tab_unique_found ); ?>"></div>
		<?php if ( ! empty( $content ) ) : ?>
			<div class="title">
				<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
			</div>
		<?php endif; ?>
		<?php $filter_cats_counter = 0; ?>
		<div class="stm-listing-tabs">
			<ul class="heading-font" role="tablist">
				<?php if ( ! empty( $filter_cats ) ) : ?>
					<?php
					foreach ( $filter_cats as $filter_cat ) :
						$filter_cats_counter++;
						?>
						<?php if ( ! empty( $filter_cat[0] ) && ! ( empty( $filter_cat[1] ) ) ) : ?>
							<?php $current_category = get_term_by( 'slug', $filter_cat[0], $filter_cat[1] ); ?>
							<?php if ( ! empty( $current_category ) ) : ?>
								<li 
								<?php
								if ( 1 === $filter_cats_counter ) {
									echo esc_attr( 'class=active' );
								}
								?>
								>
									<a href="#car-listing-category-<?php echo esc_attr( $current_category->slug ); ?>" role="tab" data-toggle="tab">
										<?php echo esc_attr( $tab_preffix . $current_category->name . ' ' . $tab_affix ); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( ! empty( $enable_search ) && $enable_search ) : ?>
					<li 
					<?php
					if ( 0 === $filter_cats_counter ) {
						echo esc_attr( 'class=active' );
					}
					?>
					>
						<a href="#car-listing-tab-search" role="tab" data-toggle="tab">
							<?php echo esc_attr( $search_label ); ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php $filter_cats_counter = 0; ?>
	</div>

	<div class="car-listing-main-part">
		<div class="tab-content">
			<?php if ( ! empty( $filter_cats ) ) : ?>
				<?php
				foreach ( $filter_cats as $filter_cat ) :
					$filter_cats_counter++;
					?>
					<?php if ( ! empty( $filter_cat[0] ) && ! ( empty( $filter_cat[1] ) ) ) : ?>
						<?php
						// Creating custom query for each tab.
						$args                 = array(
							'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
							'post_status'    => 'publish',
							'posts_per_page' => $per_page,
						);
						$args['tax_query'][]  = array(
							'taxonomy' => $filter_cat[1],
							'field'    => 'slug',
							'terms'    => array( $filter_cat[0] ),
						);
						$args['meta_query'][] = array(
							'key'     => 'car_mark_as_sold',
							'value'   => '',
							'compare' => '=',
						);

						$listing_cars = new WP_Query( $args );
						?>
						<div role="tabpanel" class="tab-pane 
						<?php
						if ( 1 === $filter_cats_counter ) {
							echo esc_attr( 'active' );
						}
						?>
						" id="car-listing-category-<?php echo esc_attr( $filter_cat[0] ); ?>">
							<div class="found-cars-clone">
								<div class="found-cars heading-font">
									<i class="stm-icon-car"></i><?php esc_html_e( 'available', 'motors-wpbakery-widgets' ); ?> 
									<span class="blue-lt"><?php echo esc_attr( $listing_cars->found_posts ); ?>&nbsp;<?php echo esc_html( $atts['found_cars_prefix'] ); ?></span>
								</div>
							</div>
							<?php if ( $listing_cars->have_posts() ) : ?>
								<div class="row row-4 car-listing-row">
									<?php
									while ( $listing_cars->have_posts() ) :
										$listing_cars->the_post();
										?>
										<?php get_template_part( 'partials/car-filter', 'loop' ); ?>
									<?php endwhile; ?>
								</div>

								<?php if ( ! empty( $enable_ajax_loading ) && $enable_ajax_loading ) : ?>
									<?php if ( $listing_cars->found_posts > $per_page ) : ?>
										<div class="row car-listing-actions">
											<div class="col-xs-12 text-center">
												<div class="dp-in">
													<div class="preloader">
														<span></span>
														<span></span>
														<span></span>
														<span></span>
														<span></span>
													</div>
													<a class="load-more-btn" href=""
														onclick="stm_loadMoreCars(jQuery(this),'<?php echo esc_js( $filter_cat[0] ); ?>','<?php echo esc_js( $filter_cat[1] ); ?>',<?php echo esc_js( intval( $per_page ) ); ?>,<?php echo esc_js( intval( $per_page ) ); ?>);return false;">
														<?php esc_html_e( 'Load more', 'motors-wpbakery-widgets' ); ?>
													</a>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php else : ?>
									<div class="row">
										<div class="col-xs-12 text-center">
											<div class="dp-in">
												<a class="load-more-btn"
													href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ) . '?' . esc_attr( $filter_cat[1] ) . '=' . esc_attr( $filter_cat[0] ); ?>">
													<?php esc_html_e( 'Show all', 'motors-wpbakery-widgets' ); ?>
												</a>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<!--Search tab-->
			<?php if ( ! empty( $enable_search ) && $enable_search ) : ?>
				<div role="tabpanel" class="tab-pane 
				<?php
				if ( 0 === $filter_cats_counter ) {
					echo esc_attr( 'active' );
				}
				?>
				" id="car-listing-tab-search">
					<div class="found-cars-clone">
						<div class="found-cars heading-font">
							<i class="stm-icon-car"></i><?php esc_html_e( 'available', 'motors-wpbakery-widgets' ); ?>
							<span class="blue-lt">
								<?php echo esc_attr( $all_cars->found_posts ); ?> <?php esc_html_e( ' cars', 'motors-wpbakery-widgets' ); ?>
							</span>
						</div>
					</div>
					<?php if ( ! empty( $search_label ) ) : ?>
						<div class="tab-search-title heading-font">
							<?php if ( ! empty( $search_icon ) ) : ?>
								<i class="<?php echo esc_attr( $search_icon ); ?> <?php echo esc_attr( $search_icon_class ); ?>"></i>
							<?php endif; ?>
							<?php echo esc_attr( $search_label ); ?>
						</div>
					<?php endif; ?>
					<div class="filter stm-vc-ajax-filter">
						<?php if ( ! empty( $filter ) && ! empty( $filter_selected ) ) : ?>
							<div class="row">
								<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="get">
									<?php
									foreach ( $filter['filters'] as $attribute => $config ) :
										if ( ! empty( $filter['options'][ $attribute ] ) ) :
											if ( in_array( $attribute, $filter_selected, true ) ) :
												?>
												<?php if ( isset( $filter['options'][ $attribute ] ) ) : ?>
													<div class="col-md-<?php echo esc_attr( $filter_columns_number ); ?> col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
														<div class="form-group">
															<?php

																$args = array(
																	'options' => $filter['options'][ $attribute ],
																	'name' => $attribute,
																);

																if ( apply_filters( 'stm_is_listing_price_field', false, $attribute ) ) {
																	$first   = true;
																	$options = array();

																	foreach ( $filter['options'][ $attribute ] as $key => $option ) {
																		if ( $first ) {
																			$options[''] = array(
																				'label' => $config['single_name'],
																				'selected' => true,
																				'disabled' => false,
																			);

																			$first = false;
																		}

																		$options[ $key ] = $option;
																	}

																	$args['options'] = $options;
																	$args['maxify']  = true;

																}

																do_action( 'stm_listings_load_template', 'filter/types/select', $args );
																?>
														</div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<div class="col-md-3 col-sm-6">
										<div class="row">
											<div class="col-md-8 col-sm-12">
												<button type="submit" class="button icon-button"><i
															class="stm-icon-search"></i><?php esc_html_e( 'Search', 'motors-wpbakery-widgets' ); ?>
												</button>
											</div>
											<div class="col-md-4 hidden-sm hidden-xs">
												<a href="" class="reset-all reset-styled"
													title="<?php esc_html_e( 'Reset search fields', 'motors-wpbakery-widgets' ); ?>"><i
															class="stm-icon-reset"></i></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $enable_call_to_action ) && $enable_call_to_action ) : ?>
							<div class="search-call-to-action">
								<div class="stm-call-to-action heading-font"
									style="background-color:<?php echo esc_attr( $call_to_action_color ); ?>">
									<div class="clearfix">
										<div class="call-to-action-content pull-left">
											<?php if ( ! empty( $call_to_action_label ) ) : ?>
												<div class="content">
													<?php if ( ! empty( $call_to_action_icon ) ) : ?>
														<i class="<?php echo esc_attr( $call_to_action_icon ); ?> <?php echo esc_attr( $cta_icon_class ); ?>"></i>
													<?php endif; ?>
													<?php echo esc_html( $call_to_action_label ); ?>
												</div>
											<?php endif; ?>
										</div>
										<div class="call-to-action-right">
											<div class="call-to-action-meta">
												<?php if ( ! empty( $call_to_action_label_right ) ) : ?>
													<div class="content">
														<?php if ( ! empty( $call_to_action_icon_right ) ) : ?>
															<i class="<?php echo esc_attr( $call_to_action_icon_right ); ?> <?php echo esc_attr( $call_to_action_icon_right_class ); ?>"></i>
														<?php endif; ?>
														<?php echo esc_html( $call_to_action_label_right ); ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ( ! empty( $top_part_bg ) ) : ?>
	<style type="text/css">
		.car-listing-tabs-unit .car-listing-top-part:before {
			background-color: <?php echo esc_attr( $top_part_bg ); ?>
		}
	</style>
<?php endif; ?>

<script>
	(function ($) {
		"use strict";


		$(document).ready(function () {
			$('.<?php echo esc_attr( $tab_unique_found ); ?>').html($('.<?php echo esc_attr( $tab_unique ); ?> .car-listing-main-part .tab-pane.active .found-cars-clone').html());
			$('.<?php echo esc_attr( $tab_unique ); ?> a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				var tab_href = $(e.target).attr('href');
				var found_cars = $(tab_href).find('.found-cars-clone').html();
				$('.<?php echo esc_attr( $tab_unique_found ); ?>').html(found_cars);

			})
		})
	})(jQuery);
</script>
