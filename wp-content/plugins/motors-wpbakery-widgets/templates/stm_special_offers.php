<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$args = array(
	'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'    => 'publish',
	'posts_per_page' => -1,
);

$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	array(
		'key'     => 'special_car',
		'value'   => 'on',
		'compare' => '=',
	),
	array(
		'key'     => 'car_mark_as_sold',
		'value'   => '',
		'compare' => '=',
	),
);

$special_query = new WP_Query( $args );

$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$carousel_unique_class     = 'special-carousel-' . wp_rand( 1, 99999 );
$image_size                = 'stm-img-350';
$class                     = ( 'carousel' === $view_type ) ? 'listing-cars-carousel owl-carousel ' : 'listing-cars-grid';
$sell_online               = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' );

$plchldr = apply_filters( 'stm_is_aircrafts', false ) ? 'Plane-small.jpg' : 'plchldr255.png';

?>

<div class="special-offers <?php echo esc_attr( $view_style . ' view_type_' . $view_type ); ?>">
	<div class="title heading-font">
		<?php echo esc_html( $title ); ?>

		<?php if ( ! empty( $show_all_link_specials ) && $show_all_link_specials ) : ?>
			<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>?featured_top=true" class="all-offers">
				<i class="stm-icon-label-reverse"></i>
				<span class="vt-top"><?php esc_html_e( 'all', 'motors-wpbakery-widgets' ); ?></span>
				<span class="lt-blue"><?php esc_html_e( 'specials', 'motors-wpbakery-widgets' ); ?></span>
			</a>
		<?php endif; ?>

	</div>
	<?php if ( 'carousel' === $view_type ) : ?>
	<div class="colored-separator">
		<div class="first-long stm-base-background-color"></div>
		<div class="last-short stm-base-background-color"></div>
	</div>
		<?php
	endif;

	if ( $special_query->have_posts() ) :
		?>
		<div class="listing-car-items-units">
			<div class="listing-car-items <?php echo esc_attr( $class ); ?> text-center clearfix <?php echo esc_attr( $carousel_unique_class ); ?>">
				<?php
				while ( $special_query->have_posts() ) :
					$special_query->the_post();
					$spec_banner       = get_post_meta( get_the_id(), 'special_image', true );
					$banner_src        = wp_get_attachment_image_src( $spec_banner, 'stm-img-350-356' );
					$banner_src_retina = wp_get_attachment_image_src( $spec_banner, 'full' );
					if ( empty( $spec_banner ) ) :
						$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );
						$price                = get_post_meta( get_the_id(), 'price', true );
						$sale_price           = get_post_meta( get_the_id(), 'sale_price', true );
						$labels               = apply_filters( 'stm_get_car_listings', array() );
						$is_sell_online       = ( true === $sell_online ) ? ! empty( get_post_meta( get_the_ID(), 'car_mark_woo_online', true ) ) : false;

						?>
						<div class="dp-in">
							<div class="listing-car-item">
								<div class="listing-car-item-inner">
									<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn" title="
										<?php
										esc_attr_e( 'View full information about', 'motors-wpbakery-widgets' );
										echo esc_attr( ' ' . get_the_title() );
										?>
									">
										<?php
										$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $image_size );
										?>

										<?php if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) || wp_is_mobile() ) : ?>

										<div class="text-center">

											<div class="image dp-in">

											<img
												<?php if ( has_post_thumbnail() ) : ?>
													src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), $image_size ) ); ?>"
													srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( get_the_ID() ), $image_size ) ); ?>"
													sizes="(max-width: 767px) 100vw, (max-width: 991px) 50vw, (max-width: 1160px) 33vw, 350px"
													alt="<?php echo esc_attr( get_the_title() ); ?>"
												<?php else : ?>
													src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>"
													alt="<?php esc_attr_e( 'Placeholder', 'motors-wpbakery-widgets' ); ?>"
												<?php endif; ?>
													class="img-responsive" loading="lazy"
											/>

											<?php
											if ( 'style_2' === $view_style ) :
												if ( ! empty( $car_price_form_label ) ) :
													?>
													<div class="price heading-font">
														<div class="normal-price"><?php echo wp_kses_post( $car_price_form_label ); ?></div>
													</div>
													<?php
												else :
													?>
													<?php
													if ( ! empty( $price ) && ! empty( $sale_price ) ) :
														?>
														<div class="price heading-font discounted-price">
															<div class="regular-price">
																<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
															</div>
															<div class="sale-price">
																<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?>
															</div>
														</div>
														<?php
													elseif ( ! empty( $price ) ) :
														?>
														<div class="price heading-font">
															<div class="normal-price">
																<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
															</div>
														</div>
														<?php
													endif;
												endif;
											endif;
											?>

											</div>

										</div>

										<?php else : ?>
											<?php
											$array_keys    = array_keys( $thumbs['gallery'] );
											$last_item_key = array_pop( $array_keys );
											?>

										<div class="image">

											<div class="interactive-hoverable">
												<div class="hoverable-wrap">
													<?php
													foreach ( $thumbs['gallery'] as $key => $img_url ) :
														?>
														<div class="hoverable-unit <?php echo ( 0 === $key ) ? 'active' : ''; ?>">
															<div class="thumb">
																<?php if ( $key === $last_item_key && 5 === count( $thumbs['gallery'] ) && 0 < $thumbs['remaining'] ) : ?>
																	<div class="remaining">
																		<i class="stm-icon-album"></i>
																		<p>
																			<?php
																			/* translators: number of remaining photos */
																			echo esc_html( sprintf( _n( '%d more photo', '%d more photos', $thumbs['remaining'], 'motors-wpbakery-widgets' ), $thumbs['remaining'] ) );
																			?>
																		</p>
																	</div>
																<?php endif; ?>
																<img
																	src="<?php echo esc_url( is_array( $img_url ) ? $img_url[0] : $img_url ); ?>"
																	srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumbs['ids'][ $key ], $image_size ) ); ?>"
																	sizes="(max-width: 767px) 100vw, (max-width: 991px) 50vw, (max-width: 1160px) 33vw, 350px"
																	alt="<?php echo esc_attr( get_the_title() ); ?>" class="img-responsive" loading="lazy" />
															</div>
														</div>
														<?php
													endforeach;
													if ( 'style_2' === $view_style ) :
														if ( ! empty( $car_price_form_label ) ) :
															?>
															<div class="price heading-font">
																<div class="normal-price"><?php echo wp_kses_post( $car_price_form_label ); ?></div>
															</div>
															<?php
														else :
															?>
															<?php
															if ( ! empty( $price ) && ! empty( $sale_price ) ) :
																?>
																<div class="price heading-font discounted-price">
																	<div class="regular-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
																	</div>
																	<div class="sale-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?>
																	</div>
																</div>
																<?php
															elseif ( ! empty( $price ) ) :
																?>
																<div class="price heading-font">
																	<div class="normal-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
																	</div>
																</div>
																<?php
															endif;
														endif;
													endif;
													?>
												</div>
												<div class="hoverable-indicators">
													<?php
													$first = true;
													foreach ( $thumbs['gallery'] as $thumb ) :
														?>
														<div class="indicator <?php echo ( $first ) ? 'active' : ''; ?>"></div>
														<?php
														$first = false;
													endforeach;
													?>
												</div>
											</div>

										</div>

										<?php endif; ?>

										<div class="listing-car-item-meta">
											<div class="car-meta-top heading-font clearfix">
												<?php
												if ( 'style_1' === $view_style ) :
													if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) :
														if ( ! empty( $sale_price ) ) {
															$price = $sale_price;
														}
														?>
														<div class="sell-online-wrap price">
															<div class="normal-price">
																<span class="normal_font"><?php echo esc_html__( 'BUY ONLINE', 'motors-wpbakery-widgets' ); ?></span>
																<span class="heading-font"><?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
															</div>
														</div>
														<?php
													else :
														if ( ! empty( $car_price_form_label ) ) :
															?>
															<div class="price">
																	<div class="normal-price"><?php echo wp_kses_post( $car_price_form_label ); ?></div>
																</div>
															<?php
														else :
															if ( ! empty( $price ) && ! empty( $sale_price ) ) :
																?>
																<div class="price discounted-price">
																	<div class="regular-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
																	</div>
																	<div class="sale-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?>
																	</div>
																</div>
															<?php elseif ( ! empty( $price ) ) : ?>
																<div class="price">
																	<div class="normal-price">
																		<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
																	</div>
																</div>
															<?php endif; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endif; ?>
												<?php
												if ( 'style_2' === $view_style ) :
													$subtitle = '';
													$car_uear = wp_get_post_terms( get_the_ID(), 'ca-year', array( 'fields' => 'names' ) );
													$body     = wp_get_post_terms( get_the_ID(), 'body', array( 'fields' => 'names' ) );

													if ( ! is_wp_error( $car_uear ) && is_array( $car_uear ) ) {
														$subtitle .= ( $car_uear ) ? '<span>' . $car_uear[0] . '</span> ' : '';
													}

													if ( ! is_wp_error( $body ) && is_array( $body ) ) {
														$subtitle .= ( $body ) ? '<span>' . $body[0] . '</span>' : '';
													}
													?>
													<div class="car-subtitle heading-font">
														<?php echo wp_kses_post( $subtitle ); ?>
													</div>
												<?php endif; ?>
												<div class="car-title">
													<?php echo wp_kses_post( trim( preg_replace( '/\s+/', ' ', substr( get_the_title(), 0, 23 ) ) ) ); ?>
													<?php
													if ( strlen( get_the_title() ) > 23 ) {
														echo esc_html( '...' );
													}
													?>
												</div>
											</div>
											<div class="car-meta-bottom">
												<?php $special_text = get_post_meta( get_the_id(), 'special_text', true ); ?>
												<?php if ( empty( $special_text ) ) : ?>
													<?php if ( ! empty( $labels ) ) : ?>
														<ul>
															<?php foreach ( $labels as $label ) : ?>
																<?php $label_meta = get_post_meta( get_the_id(), $label['slug'], true ); ?>
																<?php if ( ! apply_filters( 'is_empty_value', $label_meta ) && '' !== $label_meta ) : ?>
																	<li>
																		<?php if ( ! empty( $label['font'] ) ) : ?>
																			<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
																		<?php endif; ?>

																		<?php if ( ! empty( $label['numeric'] ) && $label['numeric'] ) : ?>
																			<span><?php echo esc_html( $label_meta ); ?></span>
																			<?php if ( isset( $label['number_field_affix'] ) && ! empty( $label['number_field_affix'] ) ) : ?>
																				<span><?php echo esc_html( $label['number_field_affix'] ); ?></span>
																			<?php endif; ?>
																		<?php else : ?>

																			<?php
																				$data_meta_array = explode( ',', $label_meta );
																				$datas           = array();

																			if ( ! empty( $data_meta_array ) ) {
																				foreach ( $data_meta_array as $data_meta_single ) {
																					$data_meta = get_term_by( 'slug', $data_meta_single, $label['slug'] );
																					if ( ! empty( $data_meta->name ) ) {
																						$datas[] = esc_attr( $data_meta->name );
																					}
																				}
																			}

																			if ( ! empty( $datas ) ) :
																				if ( count( $datas ) > 1 ) {
																					?>

																					<span
																						class="stm-tooltip-link"
																						data-toggle="tooltip"
																						data-placement="top"
																						title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
																						<?php echo esc_html( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
																					</span>

																				<?php } else { ?>
																					<span><?php echo esc_html( implode( ', ', $datas ) ); ?></span>
																					<?php
																				}
																			endif;
																			?>

																		<?php endif; ?>
																	</li>
																<?php endif; ?>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>
												<?php else : ?>
													<ul>
														<li>
															<div class="special-text">
																<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $special_text, 'Special Text' ) ); ?>
															</div>
														</li>
													</ul>
												<?php endif; ?>
											</div>
										</div>
									</a>
								</div>
							</div>
						</div>
					<?php elseif ( ! empty( $banner_src[0] ) ) : ?>
						<div class="dp-in">
							<div class="listing-car-item">
								<div class="listing-car-item-inner">
									<a href="<?php the_permalink(); ?>">
										<img class="img-responsive" src="<?php echo esc_url( $banner_src[0] ); ?>" data-retina="<?php echo esc_url( $banner_src_retina[0] ); ?>" alt="<?php the_title(); ?>" />
									</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>

</div>

<?php if ( 'grid' !== $view_type ) : ?>
<script>
	(function($) {
		"use strict";

		var owl = $('.<?php echo esc_js( $carousel_unique_class ); ?>');

		$(window).on('load', function () {
			owl.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					owl.find('.owl-dots').before('<div class="stm-owl-prev"><i class="fas fa-angle-left"></i></div>');
					owl.find('.owl-dots').after('<div class="stm-owl-next"><i class="fas fa-angle-right"></i></div>');
					owl.find('.owl-nav, .owl-dots, .stm-owl-prev, .stm-owl-next').wrapAll("<div class='owl-controls'></div>");
				}, 500);
			});

			var owlRtl = false;
			if( $('body').hasClass('rtl') ) {
				owlRtl = true;
			}

			var owlLoop = true;
			<?php if ( 1 === $special_query->post_count ) : ?>
				owlLoop = false;
			<?php endif; ?>

			owl.owlCarousel({
				rtl: owlRtl,
				items: 3,
				dots: true,
				autoplay: false,
				slideBy: 3,
				loop: owlLoop,
				responsive:{
					0:{
						items:1,
						slideBy: 1,
						dots: false
					},
					768:{
						items:2,
						slideBy: 2,
						dots: true
					},
					992:{
						items:3,
						slideBy: 3
					}
				}
			});
			owl.on('click','.stm-owl-prev', function(){
				owl.trigger('prev.owl.carousel');
			});
			owl.on('click','.stm-owl-next', function(){
				owl.trigger('next.owl.carousel');
			});

			<?php if ( ! empty( $colored_first_word ) && $colored_first_word ) : ?>
			owl.find('.car-title').each(function(){
				var html = $(this).html();
				var word = html.substr(0, html.indexOf(" "));
				var rest = html.substr(html.indexOf(" "));
				$(this).html(rest).prepend($("<span/>").html(word).addClass("stm-base-color"));
			});
			<?php endif; ?>

			owl.find('.owl-nav.disabled').remove();
		});
	})(jQuery);
</script>
<?php endif; ?>
