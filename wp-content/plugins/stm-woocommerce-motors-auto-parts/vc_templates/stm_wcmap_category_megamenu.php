<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_category_megamenu', 'stm_wcmap_category_megamenu' );

$base_color = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$custom_css = "
.stm-wcmap-category-mm-wrap > ul > li > a:hover {
background-color: {$base_color};
}
.stm-wcmap-title{
	border-bottom-color:  {$base_color};
}
.stm-wcmap-subcats-content{
	border-top-color:  {$base_color};
}
";
wp_add_inline_style( 'stm-wcmap-stm_wcmap_category_megamenu', $custom_css );

echo '<style>' . esc_attr( $atts['css'] ) . '</style>';
?>

<div class="stm-wcmap-category-mm-wrap <?php echo esc_attr( $css_class ); ?>"  >
	<div class="stm-wcmap-title">
		<span class="icon-ap-note"></span>
		<h4 class="heading-font">
			<?php echo esc_html( $atts['title'] ); ?>
		</h4>
	</div>
	<?php do_shortcode( '[stm_wcmap_sc cats="' . $atts['taxonomy'] . '"]' ); ?>
</div>
