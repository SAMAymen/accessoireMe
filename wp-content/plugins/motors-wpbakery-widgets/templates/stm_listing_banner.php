<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image = $image[0];
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = $image[0] ?? '';
	}
}

wp_enqueue_script(
	'stm-vivus',
	STM_MWW_URL . '/assets/js/lib/vivus.min.js',
	'',
	get_bloginfo( 'version' ),
	true
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
?>

<div class="stm-banner-image-filter image" style="background-image: url('<?php echo esc_url( $image ); ?>')"></div>

<div class="stm_lOffers_banner animated fadeIn">
	<?php if ( ! empty( $content ) ) : ?>
		<div class="centered-banner-content-listing <?php echo esc_attr( $css_class ); ?>">
			<div class="inner">
				<?php if ( ! empty( $show_svg_arrow ) && 'yes' === $show_svg_arrow ) : ?>
					<object id="stm-vivus-arrow" type="image/svg+xml"
							data="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/icons/arrow7white.svg' ); ?>"></object>
				<?php endif; ?>
				<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
			</div>
		</div>
		<?php if ( ! empty( $show_svg_arrow ) && 'yes' === $show_svg_arrow ) : ?>
	<?php endif; ?>
	<?php endif; ?>
</div>
