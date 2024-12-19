<?php
$stm_title       = '';
$interval        = '';
$el_class        = '';
$vc_tabs_style_2 = '';
$atts            = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $vc_tabs_style_2 ) && 'yes' !== $vc_tabs_style_2 ) {
	$vc_tabs_style_2 = 'stm_tabs_style_1';
} else {
	$vc_tabs_style_2 = 'stm_tabs_style_2';
}

if ( ! empty( $vc_tabs_style_service ) && 'yes' === $vc_tabs_style_service ) {
	$vc_tabs_style_service = 'stm_tabs_style_service';
} else {
	$vc_tabs_style_service = '';
}

wp_enqueue_script( 'jquery-ui-tabs' );

$el_class = $this->getExtraClass( $el_class );

$element = 'wpb_tabs';
if ( 'vc_tour' === $this->shortcode ) {
	$element = 'wpb_tour';
}

// Extract tab titles.
preg_match_all( '/vc_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}

$tabs_nav  = '';
$tabs_nav .= '<ul class="wpb_tabs_nav ui-tabs-nav vc_clearfix">';

foreach ( $tab_titles as $stm_tab ) {
	$tab_atts = shortcode_parse_atts( $stm_tab[0] );
	if ( isset( $tab_atts['title'] ) ) {
		$tabs_nav .= '<li><a href="#tab-' . ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) ) . '">' . $tab_atts['title'] . '</a></li>';
	}
}

$tabs_nav .= '</ul>';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element ' . $el_class ), $this->settings['base'], $atts );

if ( 'vc_tour' === $this->shortcode ) {
	$next_prev_nav = '<div class="wpb_tour_next_prev_nav vc_clearfix"> <span class="wpb_prev_slide"><a href="#prev" title="' . __( 'Previous tab', 'motors-wpbakery-widgets' ) . '">' . __( 'Previous tab', 'motors-wpbakery-widgets' ) . '</a></span> <span class="wpb_next_slide"><a href="#next" title="' . __( 'Next tab', 'motors-wpbakery-widgets' ) . '">' . __( 'Next tab', 'motors-wpbakery-widgets' ) . '</a></span></div>';
} else {
	$next_prev_nav = '';
}

$output = '
	<div class="' . $css_class . '" data-interval="' . $interval . '">
		<div class="wpb_wrapper wpb_tour_tabs_wrapper ui-tabs ' . $vc_tabs_style_2 . ' ' . $vc_tabs_style_service . ' vc_clearfix">
			' . wpb_widget_title(
			array(
				'title'      => $stm_title,
				'extraclass' => $element . '_heading',
			)
		)
	. $tabs_nav
	. wpb_js_remove_wpautop( $content )
	. $next_prev_nav . '
		</div>
	</div>
';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
