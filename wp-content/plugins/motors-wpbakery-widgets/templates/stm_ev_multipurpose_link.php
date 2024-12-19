<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$unique_class = 'ev_gallery_' . wp_rand( 1, 99999 );

$plchldr = 'plchldr350.png';
?>

<div class="stm_multipurpose_link <?php echo esc_attr( $css_class ); ?>">

	<?php if ( 'permalink' === $link_function ) : ?>
		<a href="<?php echo esc_url( $link_href ); ?>" class="heading-font" target="<?php echo esc_attr( $link_target ); ?>">
			<?php echo esc_html( $link_title ); ?>
			<?php if ( ! empty( $link_icon ) ) : ?>
				<i class="<?php echo esc_attr( $link_icon ); ?>"></i>
			<?php endif; ?>
		</a>
	<?php endif ?>

	<?php
	if ( 'open_gallery' === $link_function ) :
		$car_media = apply_filters( 'stm_get_car_medias', array(), get_the_ID() );
		?>
		<a href="#!" class="heading-font <?php echo esc_attr( $unique_class ); ?>">
			<?php echo esc_html( $link_title ); ?>
			<?php if ( ! empty( $link_icon ) ) : ?>
				<i class="<?php echo esc_attr( $link_icon ); ?>"></i>
			<?php endif; ?>
		</a>
		<!--Enable carousel-->
		<script>
			jQuery(document).ready(function(){
				jQuery(".<?php echo esc_attr( $unique_class ); ?>").on('click', function(e) {
					e.preventDefault();
					jQuery(this).lightGallery({
						dynamic: true,
						dynamicEl: [
							<?php if ( ! empty( $car_media['car_photos'] ) ) : ?>
								<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
								{
									src  : "<?php echo esc_url( $car_photo ); ?>",
									thumb: "<?php echo esc_url( $car_photo ); ?>"
								},
								<?php endforeach; ?>
							<?php else : ?>
								{
									src : "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>",
									thumb: "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>"
								}
							<?php endif; ?>
						],
						download: false,
						mode: 'lg-fade',
					})
				});
			});
		</script>
	<?php endif ?>

	<?php if ( 'add_to_compare' === $link_function ) : ?>
		<a href="#!" class="heading-font ev_add_compare_link"
		data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
		data-id="<?php echo esc_attr( get_the_id() ); ?>"
		data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
		>
			<?php echo esc_html( $link_title ); ?>
			<?php if ( ! empty( $link_icon ) ) : ?>
				<i class="<?php echo esc_attr( $link_icon ); ?>"></i>
			<?php endif; ?>
		</a>
	<?php endif ?>

</div>
