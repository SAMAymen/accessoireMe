<?php
$el_class          = '';
$full_height       = '';
$full_width        = '';
$equal_height      = '';
$flex_row          = '';
$columns_placement = '';
$content_placement = '';
$parallax          = '';
$parallax_image    = '';
$css               = '';
$el_id             = '';
$video_bg          = '';
$video_bg_url      = '';
$video_bg_parallax = '';
$output            = '';
$after_output      = '';
$disable_element   = '';
$atts              = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class );

$css_classes = array(
	'vc_row',
	'wpb_row',
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row      = true;
	$css_classes[] = ' vc_row-o-equal-height';
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = ' vc_row-flex';
}

if ( ! empty( $stm_fullwidth ) && 'yes' === $stm_fullwidth ) {
	$css_classes[] = 'stm-fullwidth-with-parallax';
	if ( ! empty( $blackout_opacity ) && 0 !== $blackout_opacity ) {
		$css_classes[] = 'stm-blackout-lvl-' . $blackout_opacity;

	}
}

$wrapper_attributes = array();
// build attributes for wrapper.
if ( ! empty( $el_id ) ) {
	$anchor = '';

	if ( ! empty( $anchor_id_float_menu ) ) {
		$anchor = ' ' . $anchor_id_float_menu;
	}

	$wrapper_attributes[] = 'id="' . esc_attr( $el_id . $anchor ) . '"';
}

if ( ! empty( $anchor_id_float_menu ) && empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $anchor_id_float_menu ) . '"';
}

if ( ! empty( $anchor_id_float_menu ) ) {
	$wrapper_attributes[] = ' data-anchor="' . esc_attr( $anchor_id_float_menu ) . '" ';
}

if ( ! empty( $float_menu_item_title ) ) {
	$wrapper_attributes[] = ' data-menu-name="' . esc_attr( $float_menu_item_title ) . '" ';
}

if ( ! empty( $float_menu_color ) ) {
	$wrapper_attributes[] = ' data-menu-color="' . esc_attr( $float_menu_color ) . '" ';
}

if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[]        = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $content_placement ) ) {
		$css_classes[] = ' vc_row-o-content-' . $content_placement;
	}
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

if ( $has_video_bg ) {
	$parallax       = $video_bg_parallax;
	$parallax_image = $video_bg_url;
	$css_classes[]  = ' vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="1.5"';
	$css_classes[]        = 'vc_general vc_parallax stm-fixed-bg vc_parallax-' . $parallax;
	if ( strpos( $parallax, 'fade' ) !== false ) {
		$css_classes[]        = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( strpos( $parallax, 'fixed' ) !== false ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id  = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}
$css_class            = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
if ( ! empty( $stm_fullwidth ) && 'yes' === $stm_fullwidth ) {
	$output .= '<div class="stm-blackout-overlay"></div>';
}
$output .= '</div>';

$output .= $after_output;

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
