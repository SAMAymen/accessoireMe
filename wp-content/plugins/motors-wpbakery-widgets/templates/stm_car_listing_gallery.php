<?php
stm_motors_enqueue_scripts_styles( 'stm_car_listing_gallery' );
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm_car_listing_gallery <?php echo esc_attr( $css_class ); ?>">
	<?php get_template_part( 'partials/single-car-listing/car-gallery' ); ?>
</div>
