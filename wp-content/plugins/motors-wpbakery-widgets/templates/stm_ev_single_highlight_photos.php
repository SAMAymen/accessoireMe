<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$gallery   = get_post_meta( get_the_ID(), 'gallery', true );

?>

<div class="stm_highlight_photos <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $gallery ) ) : ?>
		<div class="stm-highlight-gallery owl-carousel">
			<?php
			if ( has_post_thumbnail() ) :
				// Post thumbnail first.
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				?>
				<div class="stm-single-image" id="big-image-<?php echo esc_attr( get_post_thumbnail_id( get_the_ID() ) ); ?>">
					<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
						<?php the_post_thumbnail( 'stm-img-280', array( 'class' => 'img-responsive' ) ); ?>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $gallery ) ) : ?>
				<?php
				foreach ( $gallery as $gallery_image ) :
					$src      = wp_get_attachment_image_src( $gallery_image, 'stm-img-280' );
					$full_src = wp_get_attachment_image_src( $gallery_image, 'full' );

					if ( ! empty( $src[0] ) && get_post_thumbnail_id( get_the_ID() ) !== $gallery_image ) :
						?>
						<div class="stm-single-image" id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
							<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
								<img src="<?php echo esc_url( $src[0] ); ?>" alt="
									<?php
									printf(
										/* translators: listing title */
										esc_attr__( '%s full', 'motors-wpbakery-widgets' ),
										esc_html(
											get_the_title( get_the_ID() )
										)
									);
									?>
								"/>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<!--Enable carousel-->
		<script>
			jQuery(document).ready(function($){
				var $owl = jQuery('.stm-highlight-gallery');

				var owlRtl = false;
				if( jQuery('body').hasClass('rtl') ) {
					owlRtl = true;
				}

				$owl.on('initialized.owl.carousel', function (e) {
					setTimeout(function () {
						$owl.find('.owl-nav').wrapAll("<div class='owl-controls'></div>");
					}, 500);
				});

				$owl.owlCarousel({
						rtl: owlRtl,
						items: 3,
						smartSpeed: 800,
						dots: false,
						margin: 22,
						autoplay: false,
						nav: true,
						navElement: 'div',
						loop: false,
						navText: [],
						responsiveRefreshRate: 1000,
						responsive:{
							0:{
								items:2
							},
							500:{
								items:2
							},
							768:{
								items:3
							},
							1000:{
								items:3
							}
						}
					});

				if(jQuery('.stm-highlight-gallery .stm-single-image').length < 6) {
					jQuery('.stm-highlight-gallery .owl-controls').hide();
					jQuery('.stm-highlight-gallery').css({'margin-top': '22px'});
				}

				jQuery('.stm-highlight-gallery .owl-dots').remove();
			})
		</script>
	<?php endif; ?>
</div>
