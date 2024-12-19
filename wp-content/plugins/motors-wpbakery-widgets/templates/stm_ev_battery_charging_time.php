<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class        = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$home_charge_time = get_post_meta( get_the_ID(), 'home_charge_time', true );
$fast_charge_time = get_post_meta( get_the_ID(), 'fast_charge_time', true );

?>

<div class="stm_battery_charging_time <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $home_charge_time ) || ! empty( $fast_charge_time ) ) : ?>

		<div class="charge_times_wrap single-car-mpg heading-font">
			<div class="text-center">
				<h3 class="heading-font"><?php esc_html_e( 'Battery charging time', 'motors-wpbakery-widgets' ); ?></h3>
				<div class="clearfix dp-in text-left mpg-mobile-selector">
					<div class="mpg-unit">
						<div class="mpg-value"><?php echo ( ! empty( $home_charge_time ) ) ? esc_attr( $home_charge_time ) : '-'; ?></div>
						<div class="mpg-label">
							<span><?php esc_html_e( 'Home charge', 'motors-wpbakery-widgets' ); ?></span>
						</div>
						<div class="mpg-label"><?php esc_html_e( 'AC 16A', 'motors-wpbakery-widgets' ); ?></div>
					</div>
					<div class="mpg-icon">
						<i class="stm-icon-charge-bolt"></i>
					</div>
					<div class="mpg-unit">
						<div class="mpg-value"><?php echo ( ! empty( $fast_charge_time ) ) ? esc_attr( $fast_charge_time ) : '-'; ?></div>
						<div class="mpg-label">
							<span><?php esc_html_e( 'Fast charge', 'motors-wpbakery-widgets' ); ?></span>
						</div>
						<div class="mpg-label"><?php esc_html_e( 'DC 64A', 'motors-wpbakery-widgets' ); ?></div>
					</div>
				</div>
				<p class="charge-range heading-font">
					<?php esc_html_e( 'Charging from 0 to 80%', 'motors-wpbakery-widgets' ); ?>
				</p>
			</div>
		</div>

	<?php endif; ?>
</div>
