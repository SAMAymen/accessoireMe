<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$city_mpg    = get_post_meta( get_the_ID(), 'city_mpg', true );
$highway_mpg = get_post_meta( get_the_ID(), 'highway_mpg', true );
?>

<div class="stm_electric_car_mpg <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $city_mpg ) || ! empty( $highway_mpg ) ) : ?>

		<div class="electric_vehicle_mpg single-car-mpg heading-font">
			<div class="text-center">
				<div class="clearfix dp-in text-left mpg-mobile-selector units_wrap">
					<div class="mpg-unit">
						<div class="mpg-value"><?php echo ( ! empty( $city_mpg ) ) ? esc_html( $city_mpg ) : '-'; ?></div>
						<div class="mpg-label"><?php esc_html_e( 'CITY MPGe', 'motors-wpbakery-widgets' ); ?></div>
					</div>
					<div class="mpg-icon">
						<i class="stm-icon-twisted-plug"></i>
					</div>
					<div class="mpg-unit">
						<div class="mpg-value"><?php echo ( ! empty( $highway_mpg ) ) ? esc_html( $highway_mpg ) : '-'; ?></div>
						<div class="mpg-label"><?php esc_html_e( 'HWY MPGe', 'motors-wpbakery-widgets' ); ?></div>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>
</div>
