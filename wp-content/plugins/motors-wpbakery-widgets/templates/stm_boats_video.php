<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( empty( $height ) ) {
	$height = '310';
}

if ( ! empty( $image ) && ! empty( $link ) ) :
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image = $image[0];
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = $image[0];
	}
	?>

	<div class="stm-boats-video-iframe">
		<a href="#" data-url="<?php echo esc_url( $link ); ?>" class="stm-boats-video-poster" style="background-image: url('<?php echo esc_url( $image ); ?>')"></a>
	</div>

	<style>
		.stm-boats-video-iframe {
			height: <?php echo intval( $height ); ?>px !important;
			width: 100%;
		}
	</style>

	<script>
		(function ($) {
			"use strict";

			$(document).ready(function ($) {
				stmPlayIframeVideo();
			});

			/* Custom func */
			function stmPlayIframeVideo() {
				$('.stm-boats-video-poster').on('click', function (e) {
					e.preventDefault();

					var url = $(this).attr('data-url');

					jQuery(this).lightGallery({
						dynamic: true,
						dynamicEl: [
							{
								src: url
							},
						],
						download: false,
						mode: 'lg-fade',
					})
				});
			}

		})(jQuery);
	</script>

<?php endif; ?>
