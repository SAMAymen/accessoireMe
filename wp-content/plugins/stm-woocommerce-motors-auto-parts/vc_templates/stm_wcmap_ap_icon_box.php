<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_ap_icon_box', 'stm_wcmap_ap_icon_box' );

?>

<div class="stm-wcmap-ap-icon-box <?php echo esc_attr( $css_class ); ?>">
	<?php
	if ( ! empty( $image ) ) :
		$img = wp_get_attachment_image_src( $image, 'full' );
		?>
	<div class="stm-wcmap-ap-icon-wrap">
		<img src="<?php echo esc_url( $img[0] ); ?>" />
	</div>
	<?php endif; ?>

	<div class="stm-wcmap-text-wrap">
		<h4 class="heading-font"><?php echo esc_html( $title ); ?></h4>
		<div class="content">
			<?php echo wp_kses_post( apply_filters( 'stm_wcmap_ap_icon_box_content_filter', $content ) ); ?>
		</div>
	</div>
</div>
