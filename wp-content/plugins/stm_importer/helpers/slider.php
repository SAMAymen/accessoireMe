<?php

function stm_theme_import_sliders( $layout ) {
	if ( class_exists( 'RevSlider' ) ) {
		$path        = STM_CONFIGURATIONS_PATH . '/demos/' . $layout . '/sliders/';
		$slider_path = $path . 'home_slider.zip';

		remove_all_sliders();

		if ( file_exists( $slider_path ) ) {
			$slider = new RevSlider();
			$slider->importSliderFromPost( true, true, $slider_path );
		}

		$slider_2_path = $path . 'home_slider_2.zip';
		if ( file_exists( $slider_2_path ) ) {
			$slider = new RevSlider();
			$slider->importSliderFromPost( true, true, $slider_2_path );
		}
	}

	if ( 'ev_dealer' === $layout ) {
		$slider_json  = '{"height":"967","autoplay":true,"duration":3500,"loop":true,"animation":"slide","listing_attrs":["drive","price","battery-size","electric-range","0-60-mph","engine"],"stm_swiper_slides_repeater":[{"background":788,"listing":"384","text":"Drive the future,\u003Cbr\u003Etoday."},{"background":788,"listing":"554","text":"Visualize, Customize,\u003Cbr\u003EActualize."},{"background":788,"listing":"569","text":"The ultimate all-electric\u003Cbr\u003Eperformance SUV"},{"background":788,"listing":"595","text":"Lower maintenance.\u003Cbr\u003ESmarter choice."},{"background":788,"listing":"606","text":"Advanced features.\u003Cbr\u003EEnvironment friendly."}]}';
		$slider_array = json_decode( $slider_json, true );
		update_option( 'stm_swiper_slider', $slider_array );
	}
}

function remove_all_sliders() {
	$slider     = new RevSlider();
	$allSliders = $slider->getArrSliders();

	foreach ( $allSliders as $slider ) {
		$sliderID = $slider->getID();
		$slider->deleteSlider( $sliderID );
	}
}
