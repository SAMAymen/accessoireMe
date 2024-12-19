<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class             = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$testimonials_carousel = 'testimonials_carousel_' . wp_rand( 0, 99999 );

if ( empty( $slides_per_row ) ) {
	$slides_per_row = 1;
}

$shortcode_attrs = array();

if ( ! empty( $content ) ) :
	$shortcode_attrs = shortcode_parse_atts( $content );

	if ( is_array( $shortcode_attrs ) && ( ! isset( $shortcode_attrs['style_view'] ) || empty( $shortcode_attrs['style_view'] ) ) ) {
		$shortcode_attrs['style_view'] = '';
	}
	?>

	<div class="testimonials-carousel-wrapper  <?php echo esc_attr( $shortcode_attrs['style_view'] ); ?> ">
		<div class="testimonials-carousel owl-carousel <?php echo esc_attr( $testimonials_carousel . ' ' . $css_class ); ?>">
			<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
		</div>
	</div>

	<?php
	if ( apply_filters( 'stm_is_rental', false ) ) :
		?>
		<style>
			.testimonials-carousel .owl-nav.disabled {
				display: none!important;
			}
		</style>
		<?php
	endif;
	?>

	<script>
		(function($) {
			"use strict";

			var owlRtl = false;
			if( $('body').hasClass('rtl') ) {
				owlRtl = true;
			}

			var owl = $('.<?php echo esc_js( $testimonials_carousel ); ?>');

			var loopOwl = (owl.find(".testimonial-unit").length > 1) ? true : false;

			$(document).on('ready', function () {
				owl.on('initialized.owl.carousel', function(e){
					setTimeout(function () {
						owl.find('.owl-nav, .owl-dots, .stm-owl-prev, .stm-owl-next').wrapAll("<div class='owl-controls'></div>");
						owl.trigger("to.owl.carousel", [0, 200]);
					}, 500);
				});
				owl.owlCarousel({
					rtl: owlRtl,
					items: <?php echo esc_js( $slides_per_row ); ?>,
					responsive: {
						0: {
							items: 1
						},
						769: {
							items: <?php echo esc_js( $slides_per_row ); ?>
						}
					},
					smartSpeed: 800,
					dots: <?php echo ( false === apply_filters( 'stm_is_rental', false ) ) ? 'false' : 'true'; ?>,
					nav: true,
					navElement: 'div',
					autoplay: false,
					loop: loopOwl,
					navText: '',
					responsiveRefreshRate: 1000
				});
			});
		})(jQuery);
	</script>
	<?php
endif;
