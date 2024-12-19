<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image = $image[0];
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = 'style=background-image:url(' . $image[0] . ');';
	}
} else {
	$image = '';
}

?>

<div class="stm-service-contact-us-form-wrapper" <?php echo esc_attr( $image ); ?>>
	<?php if ( ! empty( $form ) && 'none' !== $form ) : ?>
		<?php $cf7 = get_post( $form ); ?>
		<?php echo( do_shortcode( '[contact-form-7 id="' . $cf7->ID . '" title="' . ( $cf7->post_title ) . '"]' ) ); ?>
	<?php endif; ?>
</div>
