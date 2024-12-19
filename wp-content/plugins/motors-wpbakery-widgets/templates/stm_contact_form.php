<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm-contact-us-form-wrapper <?php echo esc_attr( $css_class ); ?>">
	<?php if ( $title ) : ?>
		<h2 class="heading-font"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h2>
	<?php endif; ?>
	<?php
	if ( ! empty( $form ) && 'none' !== $form ) :
		$cf7 = get_post( $form );

		if ( ! empty( $cf7 ) && is_object( $cf7 ) ) {

			echo( do_shortcode( '[contact-form-7 id="' . $cf7->ID . '" title="' . ( $cf7->post_title ) . '"]' ) );

		}
	endif;
	?>
</div>
