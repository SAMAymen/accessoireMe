<?php
$show_compare              = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$show_favorite             = ( apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_aircrafts', false ) ) ? false : apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_favorite_items' );

/*Media*/
$car_media   = apply_filters( 'stm_get_car_medias', array(), get_the_ID() );
$col         = ( ! empty( get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) ) ) ? 12 / get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) : 4;
$img_size    = 'stm-img-255';
$thumb_width = 255;
$grid_col_w  = '33vw';
$placeholder = 'plchldr255.png';

if ( apply_filters( 'stm_is_ev_dealer', false ) ) {
	$img_size    = 'stm-img-275';
	$thumb_width = 275;
}

if ( ! empty( $__vars['is_cars_on_top'] ) || ( ! empty( $__vars['vis_limit'] ) && intval( $__vars['vis_limit'] ) === 3 ) ) {
	$img_size    = 'stm-img-350';
	$thumb_width = 350;
	$placeholder = 'plchldr350.png';
}

if ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$placeholder = 'ac_plchldr.jpg';
}

if ( 6 === $col || ( ! empty( $__vars['vis_limit'] ) && intval( $__vars['vis_limit'] ) === 2 ) ) {
	$img_size    = 'stm-img-398';
	$thumb_width = 398;
	$grid_col_w  = '50vw';
	$placeholder = 'plchldr-398.jpg';
}

if ( ! empty( $custom_img_size ) && has_image_size( $custom_img_size ) ) {
	$img_size = $custom_img_size;
}

$thumbs      = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $img_size );
$photo_class = 'stm-car-photos-' . get_the_ID() . '-' . wp_rand( 1, 99 );
$video_class = 'stm-car-videos-' . get_the_ID() . '-' . wp_rand( 1, 99 );
?>
<div class="image">

	<?php if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) : ?>

		<img
			<?php if ( has_post_thumbnail() ) : ?>
				src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), $img_size ) ); ?>"
				srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( get_the_ID() ), $img_size ) ); ?>"
				sizes="(max-width: 767px) 100vw, (max-width: 1023px) <?php echo esc_attr( $grid_col_w ); ?>, <?php echo esc_attr( $thumb_width ); ?>px"
				alt="<?php echo esc_attr( get_the_title() ); ?>"
			<?php else : ?>
				src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
			<?php endif; ?>
				class="img-responsive" loading="lazy"
		/>

	<?php else : ?>
		<?php
		$array_keys    = array_keys( $thumbs['gallery'] );
		$last_item_key = array_pop( $array_keys );
		?>

		<div class="interactive-hoverable">
			<div class="hoverable-wrap">
				<?php foreach ( $thumbs['gallery'] as $key => $img_url ) : ?>
					<div class="hoverable-unit <?php echo ( 0 === $key ) ? 'active' : ''; ?>">
						<div class="thumb">
							<?php if ( $key === $last_item_key && 5 === count( $thumbs['gallery'] ) && 0 < $thumbs['remaining'] ) : ?>
								<div class="remaining">
									<i class="stm-icon-album"></i>
									<p>
										<?php
										echo esc_html(
											sprintf(
											/* translators: number of remaining photos */
												_n( '%d more photo', '%d more photos', $thumbs['remaining'], 'motors' ),
												$thumbs['remaining']
											)
										);
										?>
									</p>
								</div>
							<?php endif; ?>
							<img
								src="<?php echo esc_url( is_array( $img_url ) ? $img_url[0] : $img_url ); ?>"
								srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumbs['ids'][ $key ], $img_size ) ); ?>"
								sizes="(max-width: 767px) 100vw, (max-width: 1023px) <?php echo esc_attr( $grid_col_w ); ?>, <?php echo esc_attr( $thumb_width ); ?>px"
								alt="<?php echo esc_attr( get_the_title() ); ?>" class="img-responsive" loading="lazy" />
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="hoverable-indicators">
				<?php foreach ( $thumbs['gallery'] as $key => $thumb ) : ?>
					<div class="indicator <?php echo ( 0 === $key ) ? 'active' : ''; ?>"></div>
				<?php endforeach; ?>
			</div>
		</div>

	<?php endif; ?>
	<?php get_template_part( 'partials/listing-cars/listing-directory', 'badges' ); ?>

	<!---Media-->
	<div class="stm-car-medias">
		<?php if ( ! empty( $car_media['car_photos_count'] ) ) : ?>
			<div class="stm-listing-photos-unit stm-car-photos-<?php echo get_the_ID(); ?> <?php echo esc_attr( $photo_class ); ?>">
				<i class="stm-service-icon-photo"></i>
				<span><?php echo esc_html( $car_media['car_photos_count'] ); ?></span>
			</div>

			<script>
				jQuery(document).ready(function(){
					jQuery(".<?php echo esc_attr( $photo_class ); ?>").on('click', function(e) {
						e.preventDefault();
						jQuery(this).lightGallery({
							dynamic: true,
							dynamicEl: [
								<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
								{
									src  : "<?php echo esc_url( $car_photo ); ?>",
									thumb: "<?php echo esc_url( $car_photo ); ?>"
								},
								<?php endforeach; ?>
							],
							download: false,
							mode: 'lg-fade',
						})
					});
				});

			</script>
		<?php endif; ?>
		<?php if ( ! empty( $car_media['car_videos_count'] ) ) : ?>
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_ID(); ?> <?php echo esc_attr( $video_class ); ?>">
				<i class="fas fa-film"></i>
				<span><?php echo esc_html( $car_media['car_videos_count'] ); ?></span>
			</div>

			<script>
				(function ($) {
					$(document).ready(function(){
						$(".<?php echo esc_attr( $video_class ); ?>").on('click', function(e) {
							e.preventDefault();

							$(this).lightGallery({
								dynamic: true,
								dynamicEl: [
									<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
									{
										src  : "<?php echo esc_url( $car_video ); ?>"
									},
									<?php endforeach; ?>
								],
								download: false,
								mode: 'lg-fade',
							})
						}); //click
					}); //ready
				})(jQuery);
			</script>
		<?php endif; ?>
	</div>

	<!--Favorite-->
	<?php if ( ! empty( $show_favorite ) ) : ?>
		<div
			class="stm-listing-favorite"
			data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			data-toggle="tooltip" data-placement="right"
			title="<?php esc_attr_e( 'Add to favorites', 'motors' ); ?>"
		>
			<i class="stm-service-icon-staricon"></i>
		</div>
	<?php endif; ?>

	<!--Compare-->
	<?php if ( ! empty( $show_compare ) ) : ?>
		<div
			class="stm-listing-compare stm-compare-directory-new"
			data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
			data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
			data-toggle="tooltip" data-placement="left"
			title="<?php esc_attr_e( 'Add to compare', 'motors' ); ?>"
		>
			<i class="stm-service-icon-compare-new"></i>
		</div>
	<?php endif; ?>
</div>
