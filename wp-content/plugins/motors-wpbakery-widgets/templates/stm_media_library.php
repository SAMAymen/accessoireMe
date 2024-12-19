<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( '' === $images ) {
	$images = '-1,-2,-3';
}

$images = explode( ',', $images );
$i      = - 1;

$image_gallery = 'media-widget-' . wp_rand( 1, 99999 );
?>

<div class="widget widget_media_library">
	<?php if ( ! empty( $title ) ) : ?>
		<h4 class="widgettitle"><?php echo esc_attr( $title ); ?></h4>
	<?php endif; ?>
	<?php if ( ! empty( $images ) ) : ?>
		<div class="media-widget-list clearfix">
			<?php
			foreach ( $images as $attach_id ) :
				$i ++;
				$post_thumbnail = wp_get_attachment_image_src( $attach_id, 'thumbnail' );

				if ( empty( $post_thumbnail ) || ! is_array( $post_thumbnail ) ) {
					continue;
				}

				$thumbnail = $post_thumbnail[0];
				?>

				<div class="media-widget-item">
					<?php
					$fancy_link = '#!';
					$full_image = wp_get_attachment_image_src( $attach_id, 'full' );
					if ( ! empty( $full_image ) && is_array( $full_image ) ) {
						$fancy_link = $full_image[0];
					}
					?>
					<a class="stm_fancybox" href="<?php echo esc_url( $fancy_link ); ?>" title="<?php esc_attr_e( 'Watch in popup', 'motors-wpbakery-widgets' ); ?>" rel="<?php echo esc_attr( $image_gallery ); ?>">
						<img src="<?php echo esc_url( $thumbnail ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Media gallery image', 'motors-wpbakery-widgets' ); ?>"/>
					</a>
				</div>

			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
