<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class             = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$testimonials_carousel = 'testimonials_carousel_boats_' . wp_rand( 1, 99999 );

?>

<div class="testimonials-carousel-boats owl-carousel <?php echo esc_attr( $testimonials_carousel . $css_class ); ?>">
	<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
</div>

<script>
	(function($) {
		"use strict";

		var owlRtl = false;
		if( $('body').hasClass('rtl') ) {
			owlRtl = true;
		}

		var owl = $('.<?php echo esc_js( $testimonials_carousel ); ?>');

		$(document).ready(function () {
			owl.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					owl.find('.owl-nav, .owl-dots, .stm-owl-prev, .stm-owl-next').wrapAll("<div class='owl-controls'></div>");
					owl.trigger("to.owl.carousel", [0, 200]);
				}, 500);
			});

			owl.owlCarousel({
				rtl: owlRtl,
				items: 1,
				smartSpeed: 800,
				dots: true,
				nav:true,
				navElement: 'div',
				autoplay: false,
				loop: true,
				navText: '',
				responsiveRefreshRate: 1000
			});
		});
	})(jQuery);
</script>
