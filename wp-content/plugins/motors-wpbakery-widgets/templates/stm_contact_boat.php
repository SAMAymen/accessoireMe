<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$thumbnail = '';
if ( ! empty( $images ) ) {
	$images = explode( ',', $images );
	if ( ! empty( $images[0] ) ) {
		$images = $images[0];

		$post_thumbnail = wpb_getImageBySize(
			array(
				'attach_id'  => $images,
				'thumb_size' => '80x80',
			)
		);

		$thumbnail = $post_thumbnail['thumbnail'];
	}
}

?>

<div class="stm-boat-single-contact">
	<div class="clearfix">

		<?php if ( ! empty( $thumbnail ) ) : ?>
			<div class="image">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif; ?>

		<div class="content">
			<?php if ( ! empty( $name ) ) : ?>
				<h5><?php echo esc_attr( $name ); ?></h5>
			<?php endif; ?>

			<?php if ( ! empty( $phone ) ) : ?>
				<div class="stm-content phone">
					<div class="inner">
						<i class="stm-boats-icon-phone"></i>
						<span><?php echo esc_attr( $phone ); ?></span>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $mail ) ) : ?>
				<div class="stm-content mail">
					<div class="inner">
						<i class="stm-boats-icon-message"></i>
						<a href="mailto:<?php echo esc_attr( $mail ); ?>">
							<span><?php echo esc_attr( $mail ); ?></span>
						</a>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $skype ) ) : ?>
				<div class="stm-content skype">
					<div class="inner">
						<i class="stm-boats-icon-skype"></i>
						<a href="skype:<?php echo esc_attr( $skype ); ?>?call">
							<span><?php echo esc_attr( $skype ); ?></span>
						</a>
					</div>
				</div>
			<?php endif; ?>

		</div>


	</div>
</div>




