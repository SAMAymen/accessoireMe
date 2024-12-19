<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( ! empty( $box_bg_color ) ) {
	$box_bg_style = 'style=background-color:' . $box_bg_color . ';';
} else {
	$box_bg_style = '';
}

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

if ( ! empty( $title_color ) ) {
	$title_color = 'style=color:' . $title_color . ';';
}


$price_style = 'style=';

if ( ! empty( $price_color ) ) {
	$price_style .= 'color:' . $price_color . ';';
}

if ( ! empty( $price_background_color ) ) {
	$price_style .= 'background-color:' . $price_background_color . ';';
}

$content_class = 'content-' . wp_rand( 1, 99999 );

if ( ! empty( $content_color ) ) {
	$content_color_style = 'style=color:' . $content_color . '!important;';
} else {
	$content_color_style = '';
}

?>

	<div class="stm-service-layout-info-box <?php echo esc_attr( $css_class ); ?>" <?php echo esc_attr( $box_bg_style ); ?>>
		<div class="inner" <?php echo esc_attr( $image ); ?>>
			<?php if ( ! empty( $title ) ) : ?>
				<div class="title heading-font" <?php echo esc_attr( $title_color ); ?>>
					<?php echo esc_html( $title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $price_value ) ) : ?>
				<div class="service-price heading-font" <?php echo esc_attr( $price_style ); ?>>
					<?php if ( ! empty( $price_label ) ) : ?>
						<div class="price-label">
							<?php echo esc_attr( $price_label ); ?>
						</div>
					<?php endif; ?>

					<div class="price-value">
						<?php echo esc_attr( $price_value ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $content ) ) : ?>
				<div
					class="content <?php echo esc_attr( $content_class ); ?>" <?php echo esc_attr( $content_color_style ); ?>>
					<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

<?php if ( ! empty( $content_color ) ) : ?>
	<style type="text/css">
		.stm-service-layout-info-box .inner .content.<?php echo esc_attr( $content_class ); ?> ul li:before {
			background-color: <?php echo esc_attr( $content_color ); ?>;
		}
	</style>
<?php endif; ?>
