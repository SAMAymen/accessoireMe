<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( empty( $align ) ) {
	$align = 'text-center';
}

if ( empty( $style ) ) {
	$style = 'style_1';
}

?>

<div class="colored-separator<?php echo esc_attr( $css_class . ' ' . $align . ' ' . $style ); ?>">
	<?php if ( apply_filters( 'stm_is_boats', false ) ) : ?>
		<div 
		<?php
		if ( ! empty( $color ) ) :
			?>
			style="color: <?php echo esc_attr( $color ); ?>" <?php endif; ?>><i class="stm-boats-icon-wave stm-base-color"></i></div>
	<?php else : ?>
		<div class="first-long stm-base-background-color" 
		<?php
		if ( ! empty( $color ) ) :
			?>
			style="background-color: <?php echo esc_attr( $color ); ?>" <?php endif; ?>></div>
		<div class="last-short stm-base-background-color" 
		<?php
		if ( ! empty( $color ) ) :
			?>
			style="background-color: <?php echo esc_attr( $color ); ?>" <?php endif; ?>></div>
	<?php endif; ?>
</div>
