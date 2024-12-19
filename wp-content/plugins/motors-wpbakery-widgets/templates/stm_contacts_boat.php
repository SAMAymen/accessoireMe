<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm-boat-contact-wrapper">
	<?php if ( ! empty( $title ) ) : ?>
		<h4><?php echo esc_attr( $title ); ?></h4>
	<?php endif; ?>
	<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
</div>
