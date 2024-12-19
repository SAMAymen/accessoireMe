<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
?>

<div class="stm-car-seller-note <?php echo esc_attr( $css_class ); ?>">
	<?php echo wp_kses_post( apply_filters( 'stm_get_listing_seller_note', get_the_ID() ) ); ?>
</div>
