<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_parts_search', 'stm_wcmap_parts_search' );

$base_color = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$custom_css = ".icon-ap-car {
    color: {$base_color};
}
	button {
    background: {$base_color};
}";

wp_add_inline_style( 'stm-wcmap-stm_wcmap_parts_search', $custom_css );
?>

<div class="stm_wcmap_parts_search_wrap">
	<div class="stm_wcmap_title_wrap">
		<i class="icon-ap-car"></i>
		<h2 class="heading-font">
			<?php echo esc_html( $atts['title'] ); ?>
		</h2>
	</div>
<?php
	$d = new ClassWCMAPSearchFilter();
	$d->getWCMAPFilterHtml();
?>
</div>
