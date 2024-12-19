<?php
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$car_price_form_label      = get_post_meta( get_the_ID(), 'car_price_form_label', true );
// Compare.
if ( apply_filters( 'stm_is_boats', false ) ) {
	$placeholder_path = 'plchldr255.png';
	if ( apply_filters( 'stm_is_boats', false ) ) {
		$placeholder_path = 'boats-placeholders/Boat-small.jpg';
	}
}

$current_vehicle_id = $args['current_vehicle_id'];

$img_size        = 'stm-img-255';
$img_size_retina = 'stm-img-398-x-2';

if ( ! empty( $args['custom_img_size'] ) ) {
	$img_size        = $args['custom_img_size'];
	$img_size_retina = ( has_image_size( $args['custom_img_size'] . '-x-2' ) ) ? $args['custom_img_size'] . '-x-2' : null;
}
?>

<div class="stm-template-front-loop <?php echo ( intval( $current_vehicle_id ) === get_the_ID() ) ? 'current' : ''; ?>">
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn xx">
		<div class="image <?php echo ( ! has_post_thumbnail() ) ? esc_attr( 'empty-image' ) : ''; ?>">
			<?php
			if ( has_post_thumbnail() ) :
				$img_2x = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $img_size_retina );

				if ( true === $gallery_hover_interaction && ! wp_is_mobile() ) {
					$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $img_size );
					if ( empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) :
						?>
						<div class="brazzers-wrap">
							<div class="brazzers-carousel">
								<?php
								echo wp_get_attachment_image(
									get_post_thumbnail_id( get_the_ID() ),
									$img_size,
									false,
									array(
										'data-retina' => ( ! empty( $img_2x ) ) ? $img_2x[0] : false,
										'alt'         => get_the_title(),
									)
								);
								?>
							</div>
							<?php
							get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
							?>
							<div class="listing-car-item-meta">
								<?php do_action( 'stm_listings_load_template', 'loop/default/list/price' ); ?>
							</div>
						</div>
						<?php
					else :
						$array_keys       = array_keys( $thumbs['gallery'] );
						$last_item_key    = array_pop( $array_keys );
						$remaining_photos = '';
						if ( ! empty( $thumbs['remaining'] ) && 0 < $thumbs['remaining'] ) {
							$remaining_photos = $thumbs['remaining'];
						}
						?>
						<div class="brazzers-wrap">
							<div class="brazzers-carousel" data-remaining="<?php echo esc_attr( $remaining_photos ); ?>">
								<?php foreach ( $thumbs['gallery'] as $key => $img_url ) : ?>
									<?php if ( is_array( $img_url ) ) : ?>
										<img
											data-src="<?php echo esc_url( $img_url[0] ); ?>"
											srcset="<?php echo esc_url( $img_url[0] ); ?> 1x, <?php echo esc_url( $img_url[1] ); ?> 2x"
											src="<?php echo esc_url( $img_url[0] ); ?>"
											class="lazy img-responsive"
											alt="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" >
									<?php else : ?>
										<img src="<?php echo esc_url( $img_url ); ?>" class="lazy img-responsive" alt="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" >
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<?php
							get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
							?>
							<div class="listing-car-item-meta">
								<?php do_action( 'stm_listings_load_template', 'loop/default/list/price' ); ?>
							</div>
						</div>
						<?php
					endif;
				} else {
					echo wp_get_attachment_image(
						get_post_thumbnail_id( get_the_ID() ),
						$img_size,
						false,
						array(
							'data-retina' => $img_2x[0],
							'alt'         => get_the_title(),
						)
					);

					get_template_part( 'partials/listing-cars/listing-directory', 'badges' );

					?>
					<div class="listing-car-item-meta">
						<?php do_action( 'stm_listings_load_template', 'loop/default/list/price' ); ?>
					</div>
					<?php
				}
			else :
				if ( stm_check_if_car_imported( get_the_id() ) ) :
					?>
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Car.jpg' ); ?>"
						class="img-responsive placeholder"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
						/>
					<?php
				else :
					?>
					<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Motor.jpg' ); ?>"
						class="img-responsive placeholder"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					/>
				<?php elseif ( apply_filters( 'stm_is_boats', false ) ) : ?>
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/Boat.jpg' ); ?>"
						class="img-responsive placeholder"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					/>
				<?php elseif ( apply_filters( 'stm_is_aircrafts', false ) ) : ?>
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Plane.jpg' ); ?>"
						class="img-responsive placeholder"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					/>
				<?php else : ?>
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Car.jpg' ); ?>"
						class="img-responsive placeholder"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
						/>
				<?php endif; ?>
					<?php
				endif;
				get_template_part( 'partials/listing-cars/listing-directory', 'badges' );

				?>
				<div class="listing-car-item-meta">
					<?php do_action( 'stm_listings_load_template', 'loop/default/list/price' ); ?>
				</div>
				<?php
			endif;
			if ( apply_filters( 'stm_is_boats', false ) ) {
				stm_get_boats_image_hover( get_the_ID() );
			}
			?>
		</div>
		<div class="listing-car-item-meta">
			<div class="car-meta-top heading-font clearfix">
				<div class="car-title">
					<?php
					echo esc_attr( trim( preg_replace( '/\s+/', ' ', substr( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ), 0, 35 ) ) ) );

					if ( strlen( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ) > 35 ) {
						echo esc_attr( '...' );
					}
					?>
				</div>
			</div>
		</div>
	</a>
</div>
