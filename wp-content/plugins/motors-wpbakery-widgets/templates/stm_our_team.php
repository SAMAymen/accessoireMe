<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $image_size ) ) {
	$image_size = '257x170';
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

<div class="stm-our-team">
	<?php if ( ! empty( $thumbnail ) ) : ?>
		<div class="image">
			<?php echo wp_kses_post( $thumbnail ); ?>

			<?php if ( ! empty( $email ) || ! empty( $phone ) ) : ?>
				<div class="team-info">
					<?php if ( ! empty( $email ) ) : ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="email">
							<?php echo esc_attr( $email ); ?>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $phone ) ) : ?>
						<div class="phone heading-font">
							<i class="stm-icon-phone"></i>
							<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	<?php endif; ?>

	<?php if ( ! empty( $name ) || ! empty( $position ) ) : ?>
		<div class="meta">
			<?php if ( ! empty( $name ) ) : ?>
				<div class="name h5 heading-font">
					<?php echo esc_attr( $name ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $position ) ) : ?>
				<div class="position">
					<?php echo esc_attr( $position ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
