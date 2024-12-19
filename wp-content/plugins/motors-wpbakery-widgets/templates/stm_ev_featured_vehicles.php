<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );

$args = array(
	'post_type'      => apply_filters( 'stm_listings_multi_type', array( 'listings' ) ),
	'post_status'    => 'publish',
	'posts_per_page' => -1,
);

$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	array(
		'key'     => 'special_car',
		'value'   => 'on',
		'compare' => '=',
	),
);

$special_query = new WP_Query( $args );

$labels = apply_filters( 'stm_get_car_listings', array() );

$random_number = wp_rand( 1, 99999 );

$unique_carousel = 'featured_vehicles_carousel_' . $random_number;

$prev_class = 'stm-hex-next_' . $random_number;
$next_class = 'stm-hex-prev_' . $random_number;

$class      = 'listing-cars-carousel owl-carousel';
$image_size = 'stm-img-536-382';

?>

<div class="ev_featured_vehicles_carousel <?php echo esc_attr( $css_class ); ?>">
	<div class="title_nav">
		<div class="title heading-font"
			<?php
			if ( ! empty( $title_color ) ) {
				?>
				style="color: <?php echo esc_attr( $title_color ); ?>"
			<?php } ?>
			>
			<?php echo esc_html( $title ); ?>
		</div>
		<?php if ( 3 < $special_query->post_count ) : ?>
			<div class="hexagon_nav">
				<div class="fa-stack <?php echo esc_attr( $prev_class ); ?>">
					<i class="stm-icon-hexagon-left initial"></i>
					<i class="stm-icon-hexagon-fill fa-stack-2x hovered"></i>
					<i class="fas fa-chevron-left fa-stack-1x hovered"></i>
				</div>
				<div class="fa-stack <?php echo esc_attr( $next_class ); ?>">
					<i class="stm-icon-hexagon-right initial"></i>
					<i class="stm-icon-hexagon-fill fa-stack-2x hovered"></i>
					<i class="fas fa-chevron-right fa-stack-1x hovered"></i>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $special_query->have_posts() ) : ?>
		<div class="listing-car-items-units">
			<div class="listing-car-items <?php echo esc_attr( $class ); ?> text-center clearfix <?php echo esc_attr( $unique_carousel ); ?>">
		<?php
		while ( $special_query->have_posts() ) :
			$special_query->the_post();
			?>
			<?php $spec_banner = get_post_meta( get_the_id(), 'special_image', true ); ?>
			<?php if ( empty( $spec_banner ) ) : ?>
					<div class="dp-in">
						<div class="listing-car-item">
							<div class="listing-car-item-inner">
								<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn" title="<?php esc_attr_e( 'Featured listing', 'motors-wpbakery-widgets' ); ?>">
				<?php
				if ( has_post_thumbnail() ) :
					$img    = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
					$img_2x = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-796-466' );

					if ( true === $gallery_hover_interaction && ! wp_is_mobile() ) {
						$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $image_size );
						if ( empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) :
							?>
							<div class="text-center">
								<div class="image dp-in">
									<img src="<?php echo esc_url( $img[0] ); ?>"
										data-retina="<?php echo esc_url( $img_2x[0] ); ?>"
										class="img-responsive"
										alt="<?php echo esc_attr( apply_filters( 'stm_get_img_alt', '', get_post_thumbnail_id( get_the_ID() ) ) ); ?>">
								</div>
							</div>
							<?php
					else :
						$array_keys    = array_keys( $thumbs['gallery'] );
						$last_item_key = array_pop( $array_keys );
						?>
												<div class="interactive-hoverable">
													<div class="hoverable-wrap">
						<?php foreach ( $thumbs['gallery'] as $key => $img_url ) : ?>
															<div class="hoverable-unit <?php echo ( 0 === $key ) ? 'active' : ''; ?>">
																<div class="thumb">
							<?php if ( $key === $last_item_key && 5 === count( $thumbs['gallery'] ) && 0 < $thumbs['remaining'] ) : ?>
																		<div class="remaining">
																			<i class="stm-icon-album"></i>
																			<p>
								<?php
								/* translators: %d: more photo */
								echo esc_html( sprintf( _n( '%d more photo', '%d more photos', $thumbs['remaining'], 'motors-wpbakery-widgets' ), $thumbs['remaining'] ) );
								?>
																			</p>
																		</div>
							<?php endif; ?>
							<?php if ( is_array( $img_url ) ) : ?>
																		<img
																				data-src="<?php echo esc_url( $img_url[0] ); ?>"
																				srcset="<?php echo esc_url( $img_url[0] ); ?> 1x, <?php echo esc_url( $img_url[1] ); ?> 2x"
																				src="<?php echo esc_url( $img_url[0] ); ?>"
																				class="lazy img-responsive"
																				alt="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>">
																	<?php else : ?>
																		<img src="<?php echo esc_url( $img_url ); ?>"
																			class="lazy img-responsive"
																			alt="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>">
																	<?php endif; ?>
																</div>
															</div>
						<?php endforeach; ?>
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
						<?php
					endif;
					} else {
						?>
											<div class="text-center">
												<div class="image dp-in">
													<img src="<?php echo esc_url( $img[0] ); ?>"
														data-retina="<?php echo esc_url( $img_2x[0] ); ?>"
														class="img-responsive"
														alt="<?php echo esc_attr( apply_filters( 'stm_get_img_alt', '', get_post_thumbnail_id( get_the_ID() ) ) ); ?>">
												</div>
											</div>
						<?php
					}
									else :
										?>
										<div class="image dp-in">
											<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Car.jpg' ); ?>"
												class="img-responsive">
										</div>
										<?php
									endif;
									?>

									<div class="listing-car-item-meta heading-font">
				<?php
				$price_label         = esc_html__( 'Price', 'motors-wpbakery-widgets' );
				$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
				$genuine_price       = get_post_meta( get_the_id(), 'stm_genuine_price', true );
				$listing_title       = apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), true );
				if ( ! empty( $regular_price_label ) ) {
					$price_label = $regular_price_label;
				}
				?>
										<div class="car-title">
				<?php
				if ( ! empty( $listing_title ) ) {
					echo wp_kses( $listing_title, array( 'div' => array( 'class' => array() ) ) );
				}
				?>
										</div>
										<div class="price_details">
				<?php if ( ! empty( $genuine_price ) && ! empty( $show_price ) && 'yes' === $show_price ) : ?>
												<div class="car-price">
														<span>
					<?php echo esc_html( $price_label ); ?>
														</span>
					<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $genuine_price ) ); ?>
												</div>
				<?php endif; ?>
				<?php if ( ! empty( $show_details_btn ) && 'yes' === $show_details_btn ) : ?>
												<div class="details_btn">
					<?php esc_html_e( 'Details', 'motors-wpbakery-widgets' ); ?>
												</div>
				<?php endif; ?>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="dp-in">
						<div class="listing-car-item">
							<div class="listing-car-item-inner">
								<?php $banner_src = wp_get_attachment_image_src( $spec_banner, 'stm-img-350-356' ); ?>
								<?php $banner_src_retina = wp_get_attachment_image_src( $spec_banner, 'full' ); ?>
								<a href="<?php the_permalink(); ?>">
									<img class="img-responsive" src="<?php echo esc_url( $banner_src[0] ); ?>"
										data-retina="<?php echo esc_url( $banner_src_retina[0] ); ?>"
										alt="<?php the_title(); ?>"/>
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

<script>
	(function($) {
		"use strict";

		var <?php echo esc_js( $unique_carousel ); ?> = $('.<?php echo esc_js( $unique_carousel ); ?>');

		function ev_make_slide_hoverable() {
			let original_width = 0;
			let original_height = 0;

			// Try getting width and height from non-hoverable galleries around the carousel
			<?php echo esc_js( $unique_carousel ); ?>.find('.owl-stage .owl-item').each(function(){
				if($(this).find('.interactive-hoverable').length === 0 && original_width === 0 && original_height === 0) {
					if($(this).find('.listing-car-item-inner').width() > 0 && $(this).find('.listing-car-item-inner').height() > 0) {
						original_width = $(this).find('.listing-car-item-inner').width();
						original_height = $(this).find('.listing-car-item-inner').height();
					}
				}
			});

			// If all carousel items are hoverable, fallback to default height and search for width
			if(original_width === 0 && original_height === 0) {
				<?php echo esc_js( $unique_carousel ); ?>.find('.owl-stage .owl-item.active').each(function(){
					if(original_width === 0) {
						original_width = $(this).width() - 30; // minus padding-x
					}
				});

				// electric vehicle demo carousel item image default height
				original_height = 296;
			}

			if( original_width > 0 && original_height > 0) {
				<?php echo esc_js( $unique_carousel ); ?>.find('.owl-stage .owl-item').each(function(){
					if($(this).find('.interactive-hoverable').length > 0) {
						$(this).find('.interactive-hoverable').css('min-width', original_width);
						$(this).find('.interactive-hoverable').css('min-height', original_height);
					}
				});
			}
		}

		$(window).on('load', function () {
			<?php echo esc_js( $unique_carousel ); ?>.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					<?php echo esc_js( $unique_carousel ); ?>.find('.owl-nav, .owl-dots').wrapAll("<div class='owl-controls'></div>");
					ev_make_slide_hoverable();
				}, 500);
			});

			var owlRtl = false;
			if ($('body').hasClass('rtl')) {
				owlRtl = true;
			}

			var owlLoop = true;
			<?php if ( 1 === $special_query->post_count ) : ?>
				owlLoop = false;
			<?php endif; ?>

			<?php echo esc_js( $unique_carousel ); ?>.owlCarousel({
				rtl: owlRtl,
				items: 3,
				dots: true,
				autoplay: false,
				slideBy: 3,
				loop: owlLoop,
				responsive: {
					0: {
						items: 1,
						slideBy: 1,
						dots: false
					},
					768: {
						items: 2,
						slideBy: 2,
						dots: true
					},
					992: {
						items: 3,
						slideBy: 3
					}
				}
			});

			$('.<?php echo esc_attr( $prev_class ); ?>').on('click', function(){
				<?php echo esc_js( $unique_carousel ); ?>.trigger('prev.owl.carousel');
			});

			$('.<?php echo esc_attr( $next_class ); ?>').on('click', function(){
				<?php echo esc_js( $unique_carousel ); ?>.trigger('next.owl.carousel');
			});

			<?php echo esc_js( $unique_carousel ); ?>.find('.owl-nav.disabled').remove();
		});

		$(window).on('resize', function(){
			setTimeout(function () {
				ev_make_slide_hoverable();
			}, 500);
		});
	})(jQuery);
</script>
