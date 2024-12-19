(function ($) {
	"use strict";

	$(window).on('load', function () {
		$('.stm-hero-slider-wrap').addClass('loaded');
	});

	setTimeout(function(){

		$('.stm-hero-slider').each(function(){

			let data = $(this).data();
			let options = data.options;

			let uniqid = $(this).data('widget-id');

			let slider_options = {
				slidesPerView: 1,
				spaceBetween: 0,
				speed: 500,
			}

			if (options.hasOwnProperty('loop') && options.loop)
				slider_options.loop = true

			if (options.hasOwnProperty('autoplay') && options.autoplay) {
				slider_options.autoplay = {
					delay: 1000,
				}
				if (options.hasOwnProperty('delay') && parseInt(options.delay)) {
					slider_options.autoplay.delay = parseInt(options.delay)
				}
			}

			if (options.hasOwnProperty('transition_speed') && parseInt(options.transition_speed))
				slider_options.speed = parseInt(options.transition_speed)

			if (options.hasOwnProperty('navigation') && options.navigation) {
				slider_options.navigation = {
					nextEl: '.stm-hero-slider-' + uniqid + ' .stm-hero-slider-nav-next',
					prevEl: '.stm-hero-slider-' + uniqid + ' .stm-hero-slider-nav-prev',
				}
			}

			let swiper = new Swiper( this, slider_options );

			if (options.hasOwnProperty('pause_on_mouseover') && options.pause_on_mouseover) {
				$(swiper.$el[0]).hover(swiper.autoplay.stop, swiper.autoplay.start)
			}

		});

	},500);

})(jQuery)
