<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $image_size ) ) {
	$image_size = '200x200';
}

$thumbnail = '';

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image          = $image[0];
		$post_thumbnail = wpb_getImageBySize(
			array(
				'attach_id'  => $image,
				'thumb_size' => $image_size,
			)
		);

		$thumbnail = $post_thumbnail['thumbnail'];
	}
}
?>

<div class="testimonial-unit-boats">
	<div class="clearfix">
		<?php if ( ! empty( $thumbnail ) ) : ?>
			<div class="image">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif; ?>
		<div class="content heading-font">
			<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
		</div>
	</div>
	<div class="testimonial-quote">
		<i class="stm-boats-icon-quote"></i>
	</div>
	<div class="testimonial-meta">
		<?php if ( ! empty( $author ) ) : ?>
			<div class="author heading-font">
				<?php echo esc_attr( $author ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
