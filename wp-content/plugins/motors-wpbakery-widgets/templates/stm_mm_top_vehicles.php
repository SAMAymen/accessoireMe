<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$top_vehicles = apply_filters( 'get_top_vehicles_for_mm', array(), 8 );
?>

<div class="stm-mm-top-vehicles <?php echo esc_attr( $css_class ); ?>">
	<?php
	if ( ! empty( $title ) ) {
		?>
		<h3><?php echo esc_html( $title ); ?></h3>
		<?php
	}
	?>
	<div class="stm-mm-vehicles-list">
		<?php if ( ! empty( $top_vehicles ) ) : ?>
			<ul class="top-vehicles">
				<?php
				foreach ( $top_vehicles as $vehicle ) :
					?>
					<li>
						<a class="normal_font" href="<?php echo esc_url( get_the_permalink( apply_filters( 'stm_listings_user_defined_filter_page', '' ) ) ) . '?make=' . esc_attr( $vehicle->make_slug ) . '&serie=' . esc_attr( $vehicle->serie_slug ); ?>"><?php echo esc_html( $vehicle->make . ' ' . $vehicle->serie ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
		wp_reset_postdata();
		?>
	</div>
</div>
