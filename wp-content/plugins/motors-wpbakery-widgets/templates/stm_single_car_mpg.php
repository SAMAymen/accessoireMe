<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm_single_car_mpg <?php echo esc_attr( $css_class ); ?>">
	<?php do_action( 'stm_listings_load_template', 'single-car/car-mpg' ); ?>
</div>
