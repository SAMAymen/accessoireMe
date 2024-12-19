<?php
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$thumbnail_size            = 'stm-img-280';

$placeholder_path = 'plchldr255.png';
if ( apply_filters( 'stm_is_boats', false ) ) {
	$placeholder_path = 'boats-placeholders/Boat-small.jpg';
} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$placeholder_path = 'Plane-small.jpg';
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$placeholder_path = 'Motor-small.jpg';
}
if ( ! empty( $custom_img_size ) ) {
	$thumbnail_size = $custom_img_size;
}
$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $thumbnail_size );
?>
<div class="image">
	<!-- Video button with count -->
	<?php do_action( 'stm_listings_load_template', 'loop/video' ); ?>
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">

		<div class="image-inner interactive-hoverable">
			<?php
			// sold/featured badge.
			do_action( 'stm_listings_load_template', 'loop/default/list/badge' );
			?>
			<?php if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) : ?>

				<img
					<?php if ( has_post_thumbnail() ) : ?>
						src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), $thumbnail_size ) ); ?>"
						srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( get_the_ID() ), $thumbnail_size ) ); ?>"
						sizes="(max-width: 767px) 100vw, 257px"
						alt="<?php echo esc_attr( get_the_title() ); ?>"
					<?php else : ?>
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					<?php endif; ?>
						class="img-responsive" loading="lazy"
				/>

			<?php else : ?>
				<?php
				$array_keys    = array_keys( $thumbs['gallery'] );
				$last_item_key = array_pop( $array_keys );
				?>

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
									srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumbs['ids'][ $key ], $thumbnail_size ) ); ?>"
									sizes="(max-width: 767px) 100vw, 257px"
									alt="<?php esc_attr( the_title() ); ?>" class="img-responsive" loading="lazy" />
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="hoverable-indicators">
					<?php foreach ( $thumbs['gallery'] as $key => $thumb ) : ?>
						<div class="indicator <?php echo ( 0 === $key ) ? 'active' : ''; ?>"></div>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

		</div>

	</a>
</div>
