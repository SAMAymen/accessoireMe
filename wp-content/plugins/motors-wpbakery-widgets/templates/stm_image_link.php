<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$stm_link = vc_build_link( $link );

if ( empty( $image_size ) ) {
	$image_size = '213x142';
}

$thumbnail = '';

if ( ! empty( $images ) ) {
	$images = explode( ',', $images );
	if ( ! empty( $images[0] ) ) {
		$images   = $images[0];
		$images   = wp_get_attachment_image_src( $images, 'full' );
		$image_1x = $images[0];
	}
}

if ( ! empty( $retina_images ) ) {
	$retina_images = explode( ',', $retina_images );
	if ( ! empty( $retina_images[0] ) ) {
		$retina_images = $retina_images[0];
		$retina_images = wp_get_attachment_image_src( $retina_images, 'full' );
		$data_retina   = ( ! empty( $retina_images[0] ) ) ? $retina_images[0] : null;
	}
}

if ( ! empty( $image_1x ) ) : ?>

	<div class="stm-image-link <?php echo esc_attr( $css_class ); ?>">
		<?php if ( ! empty( $stm_link['url'] ) ) : ?>
		<a
			class="icon-box-link"
			href="<?php echo esc_url( $stm_link['url'] ); ?>"
			title="
			<?php
			if ( ! empty( $stm_link['title'] ) ) {
				echo esc_attr( $stm_link['title'] );
			}
			?>
			"

			<?php if ( ! empty( $stm_link['target'] ) ) : ?>
				target="_blank"
			<?php endif; ?>>
			<?php endif; ?>

			<div class="inner">
				<img
					src="<?php echo esc_url( $image_1x ); ?>"
					<?php if ( ! empty( $data_retina ) ) : ?>
						data-retina="<?php echo esc_url( $data_retina ); ?>"
					<?php endif; ?>
				/>
			</div>

			<?php if ( ! empty( $stm_link['url'] ) ) : ?>
		</a>
	<?php endif; ?>
	</div>

<?php endif; ?>
