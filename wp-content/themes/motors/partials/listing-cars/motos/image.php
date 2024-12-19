<?php
	global $post;

	$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );

	$listing_id = $post->ID;

	/*Media*/
	$car_media   = apply_filters( 'stm_get_car_medias', array(), $listing_id );
	$col         = ( ! empty( get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) ) ) ? 12 / get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) : 4;
	$img_size    = 'stm-img-255';
	$thumb_width = 255;
	$grid_col_w  = '33vw';
	$placeholder = 'Motor-small.jpg';

if ( ! empty( $__vars['is_cars_on_top'] ) || ( ! empty( $__vars['vis_limit'] ) && intval( $__vars['vis_limit'] ) === 3 ) ) {
	$img_size    = 'stm-img-350';
	$thumb_width = 350;
}

if ( 6 === $col || ( ! empty( $__vars['vis_limit'] ) && intval( $__vars['vis_limit'] ) === 2 ) ) {
	$img_size    = 'stm-img-398';
	$thumb_width = 398;
	$grid_col_w  = '50vw';
}

if ( ! empty( $custom_img_size ) ) {
	$img_size = $custom_img_size;
}

	$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), $listing_id, $img_size );

if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) :
	?>

	<img
	<?php if ( has_post_thumbnail() ) : ?>
			src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( $listing_id ), $img_size ) ); ?>"
			srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( $listing_id ), $img_size ) ); ?>"
			sizes="(max-width: 767px) 100vw, (max-width: 1023px) <?php echo esc_attr( $grid_col_w ); ?>, <?php echo esc_attr( $thumb_width ); ?>px"
			alt="<?php echo esc_attr( get_the_title() ); ?>"
		<?php else : ?>
			src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
		<?php endif; ?>
			class="img-responsive" loading="lazy"
	/>

	<?php
	else :
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
