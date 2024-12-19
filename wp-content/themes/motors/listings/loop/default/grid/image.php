<?php
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$show_compare              = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );

$cars_in_compare    = apply_filters( 'stm_get_compared_items', array() );
$in_compare         = '';
$car_compare_status = esc_html__( 'Add to compare', 'motors' );

if ( ! empty( $cars_in_compare ) && in_array( get_the_ID(), $cars_in_compare, true ) ) {
	$in_compare         = 'active';
	$car_compare_status = esc_html__( 'Remove from compare', 'motors' );
}

$size        = 'stm-img-255';
$thumb_width = 255;
$grid_col_w  = '33vw';

$placeholder_path = 'plchldr255.png';
if ( apply_filters( 'stm_is_boats', false ) ) {
	$placeholder_path = 'boats-placeholders/Boat-small.jpg';
} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$placeholder_path = 'Plane-small.jpg';
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$placeholder_path = 'Motor-small.jpg';
}

if ( wp_is_mobile() ) {
	if ( apply_filters( 'stm_is_boats', false ) ) {
		$placeholder_path = 'boats-placeholders/Boat.jpg';
	}
}

$col = ( ! empty( get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) ) ) ? 12 / get_post_meta( stm_get_listing_archive_page_id(), 'quant_grid_items', true ) : 4;

if ( 6 === $col ) {
	$size        = 'stm-img-398';
	$thumb_width = 398;
	$grid_col_w  = '50vw';
	$placeholder = 'plchldr-398.jpg';
}

if ( ! empty( $custom_img_size ) ) {
	$size = $custom_img_size;
}

$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $size );
?>

<div class="image">
	<?php do_action( 'stm_listings_load_template', 'loop/default/list/badge' ); ?>

	<?php if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) : ?>

		<img
			<?php if ( has_post_thumbnail() ) : ?>
				src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), $size ) ); ?>"
				srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( get_the_ID() ), $size ) ); ?>"
				sizes="(max-width: 767px) 100vw, (max-width: 1023px) <?php echo esc_attr( $grid_col_w ); ?>, <?php echo esc_attr( $thumb_width ); ?>px"
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
								srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumbs['ids'][ $key ], $size ) ); ?>"
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

	<?php
	$tooltip_position = 'left';

	if ( apply_filters( 'stm_is_boats', false ) ) {
		stm_get_boats_image_hover( get_the_ID() );
	}

	// Compare.
	if ( ! empty( $show_compare ) && $show_compare ) :
		?>
		<div
			class="stm-listing-compare stm-compare-directory-new"
			data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
			data-id="<?php echo esc_attr( get_the_id() ); ?>"
			data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
			data-toggle="tooltip"
			data-placement="<?php echo esc_attr( $tooltip_position ); ?>"
			title="<?php echo esc_attr( $car_compare_status ); ?>">
			<i class="stm-boats-icon-add-to-compare"></i>
		</div>
		<?php
	endif;
	?>
</div>
