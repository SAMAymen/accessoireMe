<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$stm_link  = vc_build_link( $link );

if ( empty( $image_size ) ) {
	$image_size = '253x233';
}

$thumbnail = '';

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image = $image[0];

		$post_thumbnail = wpb_getImageBySize(
			array(
				'attach_id'  => $image,
				'thumb_size' => $image_size,
			)
		);

		$thumbnail = $post_thumbnail['thumbnail'];
	}
}

if ( empty( $text_image_width ) ) {
	$text_image_width = '';
}

$stm_icon_class = 'stm_icon_class_' . wp_rand( 0, 99999 );

?>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
		.<?php echo esc_attr( $stm_icon_class ); ?>::before {
			color: <?php echo esc_attr( $icon_color ); ?>;
		}
	<?php endif; ?>
</style>

<div class="stm-compact-sidebar<?php echo esc_attr( $css_class ); ?>">
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

		<?php if ( ! empty( $icon_or_image ) && 'image' === $icon_or_image ) : ?>

			<?php if ( ! empty( $text_image ) ) : ?>
				<?php $text_image_src = wp_get_attachment_image_src( $text_image, 'stm-img-350-356' ); ?>
				<?php if ( ! empty( $text_image_src ) && ! empty( $text_image_src[0] ) ) : ?>

					<?php $text_image_width = 'style=max-width:' . $text_image_width . 'px;'; ?>

					<div class="text-image" <?php echo esc_attr( $text_image_width ); ?>>
						<img class="img-responsive" src="<?php echo esc_url( $text_image_src[0] ); ?>"/>
					</div>
				<?php endif; ?>
			<?php endif; ?>

		<?php elseif ( ! empty( $icon_or_image ) && 'icon' === $icon_or_image ) : ?>
			<?php if ( ! empty( $text_icon ) ) : ?>
				<div class="icon">
					<i class="<?php echo esc_attr( $text_icon ); ?> <?php echo esc_attr( $stm_icon_class ); ?>"></i>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $content ) ) : ?>
			<div class="content">
				<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $thumbnail ) ) : ?>
			<div class="image">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif; ?>



		<?php if ( ! empty( $stm_link['url'] ) ) : ?>
	</a>
<?php endif; ?>
</div>
