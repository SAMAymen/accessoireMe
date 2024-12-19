<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$ss        = get_option( 'stm_swiper_slider', array() );
$autoplay  = true;
$loop      = true;
$duration  = 3500;
$animation = 'slide';
$height    = 600;

if ( isset( $ss['autoplay'] ) && is_bool( $ss['autoplay'] ) ) {
	$autoplay = $ss['autoplay'];
}

if ( isset( $ss['loop'] ) && is_bool( $ss['loop'] ) ) {
	$loop = $ss['loop'];
}

if ( ! empty( $ss['duration'] ) && is_numeric( $ss['duration'] ) ) {
	$duration = $ss['duration'];
}

if ( ! empty( $ss['animation'] ) ) {
	$animation = $ss['animation'];
}

if ( ! empty( $ss['height'] ) && is_numeric( $ss['height'] ) ) {
	$height = $ss['height'];
}

$slides = array();

if ( ! empty( $ss['stm_swiper_slides_repeater'] ) ) {
	$slides = $ss['stm_swiper_slides_repeater'];
}

$listing_attributes = array();

if ( ! empty( $ss['listing_attrs'] ) ) {
	$listing_attributes = $ss['listing_attrs'];
}

?>

<div class="stm_ev_swiper_slider" <?php echo esc_attr( $css_class ); ?>>
	<?php if ( ! empty( $slides ) ) : ?>
		<!-- Slider main container -->
		<div class="swiper">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<!-- Slides -->
				<?php
				foreach ( $slides as $key => $slide ) :
					if ( empty( $slide['background'] ) || empty( $slide['listing'] ) || ! is_numeric( $slide['listing'] ) ) {
						continue;
					}

					$bg_image = wp_get_attachment_url( $slide['background'] );
					if ( false === $bg_image ) {
						continue;
					}

					$listing_id = intval( $slide['listing'] );

					$text = '';
					if ( ! empty( $slide['text'] ) ) {
						$text = $slide['text'];
					}

					// price.
					$regular_price = '';
					$sale_price    = '';
					$price         = '';

					$meta_regular = get_post_meta( $listing_id, 'price', true );
					if ( ! empty( $meta_regular ) ) {
						$regular_price = $meta_regular;
						$price         = $meta_regular;
					}

					$meta_sale = get_post_meta( $listing_id, 'sale_price', true );
					if ( ! empty( $meta_sale ) ) {
						$sale_price = $meta_sale;
						$price      = $meta_sale;
					}

					$regular_price_label = get_post_meta( $listing_id, 'regular_price_label', true );

					// listing title.
					$listing_title = apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id, false );

					// logo above title (we'll take the "make" attribute for now, but gotta make it dynamic to suite multilisting in the future).

					$listing_logo   = '';
					$make_attribute = get_the_terms( $listing_id, 'make' );
					if ( ! is_wp_error( $make_attribute ) && false !== $make_attribute ) {
						if ( ! empty( $make_attribute[0] ) && is_object( $make_attribute[0] ) ) {
							$attachment_id = get_term_meta( $make_attribute[0]->term_id, 'stm_image', true );
							if ( ! empty( $attachment_id ) ) {
								$attachment_url = wp_get_attachment_url( $attachment_id );
								if ( $attachment_url && stm_img_exists_by_url( $attachment_url ) ) {
									$listing_logo = $attachment_url;
								}
							}
						}
					}
					?>
					<div class="swiper-slide" style="background: url('<?php echo esc_url( $bg_image ); ?>');">
						<?php if ( ! empty( $text ) ) : ?>
							<div class="slider_text_wrapper">
								<div class="slider_text">
									<p class="heading-font"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p>
								</div>
							</div>
						<?php endif; ?>
						<div class="slider_listing_attrs_wrapper">
							<div class="slider_listing_attrs">
								<div class="logo_title">
									<?php if ( ! empty( $listing_logo ) ) : ?>
										<img src="<?php echo esc_url( $listing_logo ); ?>" alt="<?php echo esc_html( $listing_title ); ?>">
									<?php endif; ?>
									<?php if ( ! empty( $listing_title ) ) : ?>
									<h3 class="heading-font"><?php echo wp_kses_post( $listing_title ); ?></h3>
									<?php endif; ?>
									<?php if ( wp_is_mobile() ) : ?>
										<div class="listing_price">
											<?php if ( ! empty( $sale_price ) && ! empty( $regular_price ) ) : ?>
												<h4 class="sale_price">
													<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $regular_price ) ); ?>
												</h4>
											<?php elseif ( ! empty( $regular_price_label ) && ! empty( $regular_price ) ) : ?>
												<h4 class="price_label">
													<?php echo esc_html( $regular_price_label ); ?>
												</h4>
											<?php endif; ?>

											<?php if ( ! empty( $price ) && ! empty( $regular_price ) ) : ?>
												<h3>
													<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
												</h3>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<?php if ( ! empty( $listing_attributes ) ) : ?>
									<?php
									foreach ( $listing_attributes as $attr ) :
										if ( function_exists( 'stm_vl_get_all_by_slug' ) ) {
											$options = apply_filters( 'stm_vl_get_all_by_slug', array(), $attr );
										}

										if ( empty( $options ) ) {
											continue;
										}

										// don't show price attribute here!
										if ( true === apply_filters( 'stm_is_listing_price_field', $attr ) ) {
											continue;
										}

										$affix = '';
										if ( ! empty( $options['number_field_affix'] ) ) {
											$affix = $options['number_field_affix'];
										}

										$att_value = '--';

										if ( ! empty( $options['numeric'] ) ) {
											$meta_value = get_post_meta( $listing_id, $attr, true );
											if ( ! empty( $meta_value ) ) {
												$att_value = $meta_value;
											}
										} else {
											$tax_value = get_the_terms( $listing_id, $attr );
											if ( ! is_wp_error( $tax_value ) && false !== $tax_value ) {
												if ( ! empty( $tax_value[0] ) && is_object( $tax_value[0] ) ) {
													$att_value = $tax_value[0]->name;
												}
											}
										}
										?>
										<?php if ( ! wp_is_mobile() ) : ?>
										<div class="listing_attr">
											<i class="<?php echo ( ! empty( $options['font'] ) ) ? esc_attr( $options['font'] ) : ''; ?>"></i>
											<?php if ( ! empty( $options['single_name'] ) ) : ?>
												<p class="attr attr_name"><?php echo esc_html( $options['single_name'] ); ?></p>
											<?php endif; ?>
											<p class="attr attr_value"><?php echo esc_html( $att_value . $affix ); ?></p>
										</div>
									<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if ( ! wp_is_mobile() ) : ?>
								<div class="listing_price">
									<?php if ( ! empty( $sale_price ) && ! empty( $regular_price ) ) : ?>
										<h4 class="sale_price">
											<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $regular_price ) ); ?>
										</h4>
									<?php elseif ( ! empty( $regular_price_label ) && ! empty( $regular_price ) ) : ?>
										<h4 class="price_label">
											<?php echo esc_html( $regular_price_label ); ?>
										</h4>
									<?php endif; ?>

									<?php if ( ! empty( $price ) && ! empty( $regular_price ) ) : ?>
										<h3>
											<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
										</h3>
									<?php endif; ?>

									<a href="<?php echo esc_url( get_the_permalink( $listing_id ) ); ?>" class="slider_attr_btn">
										<?php esc_html_e( 'More info', 'motors-wpbakery-widgets' ); ?>
									</a>
								</div>
								<?php endif; ?>
								<?php if ( wp_is_mobile() ) : ?>
									<div class="listing_price">
										<a href="<?php echo esc_url( get_the_permalink( $listing_id ) ); ?>" class="slider_attr_btn">
											<?php esc_html_e( 'More info', 'motors-wpbakery-widgets' ); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="slider_hexagon_nav stm_swiper-button-prev">
				<div class="fa-stack">
					<i class="stm-icon-hexagon-left initial"></i>
					<i class="stm-icon-hexagon-fill fa-stack-2x hovered"></i>
					<i class="fas fa-chevron-left fa-stack-1x hovered"></i>
				</div>
			</div>
			<div class="slider_hexagon_nav stm_swiper-button-next">
				<div class="fa-stack">
					<i class="stm-icon-hexagon-right initial"></i>
					<i class="stm-icon-hexagon-fill fa-stack-2x hovered"></i>
					<i class="fas fa-chevron-right fa-stack-1x hovered"></i>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const swiper = new Swiper('.swiper', {
			<?php if ( true === $autoplay ) : ?>
			autoplay: {
				delay: <?php echo esc_attr( $duration ); ?>,
			},
			<?php endif; ?>
			direction: 'horizontal',
			loop: <?php echo ( true === $loop ) ? 'true' : 'false'; ?>,
			effect: '<?php echo esc_attr( $animation ); ?>',
			speed: 500,
			// Navigation arrows
			navigation: {
				nextEl: '.stm_swiper-button-next',
				prevEl: '.stm_swiper-button-prev',
			},
		});
	});
</script>

<style>
	/* Small devices (landscape phones, 576px and up) */
	@media (max-width: 767px) {
		.stm_ev_swiper_slider .swiper {
			height: 500px !important;
		}
	}
	@media (min-width: 576px) {
		.stm_ev_swiper_slider .swiper {
			height: 310px !important;
		}
	}

	/* Medium devices (tablets, 768px and up) */
	@media (min-width: 768px) {
		.stm_ev_swiper_slider .swiper {
			height: 600px !important;
		}
	}

	/* Large devices (desktops, 992px and up) */
	@media (min-width: 992px) {
		.stm_ev_swiper_slider .swiper {
			height: 600px !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs .listing_attr {
			padding: 20px 50px 20px 0 !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs > div {
			padding-right: 30px !important;
			margin-right: 30px !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs .logo_title {
			padding-right: 20px !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs h3 {
			font-size: 24px !important;
		}
	}

	/* X-Large devices (large desktops, 1200px and up) */
	@media (min-width: 1200px) {
		.stm_ev_swiper_slider .swiper {
			height: 750px !important;
		}
	}

	/* Large devices (desktops, 992px and up) */
	@media (min-width: 1400px) {
		.stm_ev_swiper_slider .swiper {
			height: <?php echo esc_attr( $height ); ?>px !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs {
			min-width: 1225px !important;
			padding: 42px !important;
		}

		.stm_ev_swiper_slider .swiper .swiper-slide .slider_listing_attrs_wrapper .slider_listing_attrs .listing_attr {
			min-width: 134px !important;
		}
	}
</style>
