<?php
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
?>
<div class="image">
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
		<div class="image-inner interactive-hoverable">
			<?php
			if ( has_post_thumbnail() ) :
				$plchldr = get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/Boat-small.jpg';
				$img     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-350-205' );
				$img_x2  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-350-205-x-2' );
				if ( true === $gallery_hover_interaction ) {
					$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), 'stm-img-350-205' );
					if ( empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) :
						the_post_thumbnail( 'stm-img-350-205', array( 'class' => 'img-responsive' ) );
					else :
						$array_keys    = array_keys( $thumbs['gallery'] );
						$last_item_key = array_pop( $array_keys );
						?>
						<div class="hoverable-wrap">
							<?php
							foreach ( $thumbs['gallery'] as $key => $img_url ) :
								?>
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
									</div>
								</div>
								<?php
							endforeach;

							get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
							?>
						</div>
						<div class="hoverable-indicators">
							<?php
							$first = true;
							foreach ( $thumbs['gallery'] as $thumb ) :
								?>
								<div class="indicator <?php echo ( $first ) ? 'active' : ''; ?>"></div>
								<?php
								$first = false;
							endforeach;
							?>
						</div>
						<?php
					endif;
				} else {
					?>
					<img
						data-src="<?php echo esc_url( ( ! empty( $img[0] ) ) ? $img[0] : $plchldr ); ?>"
						srcset="<?php echo esc_url( ! empty( $img[0] ) ? $img[0] : $plchldr ); ?> 1x, <?php echo esc_url( ! empty( $img_x2[0] ) ? $img_x2[0] : $plchldr ); ?> 2x"
						src="<?php echo esc_url( $plchldr ); ?>"
						class="lazy img-responsive"
						alt="<?php echo esc_attr( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ); ?>"
					/>
					<?php
					get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
				}
			else :
				?>
				<img
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/Boat-small.jpg' ); ?>"
					class="img-responsive"
					alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
				/>
				<?php get_template_part( 'partials/listing-cars/listing-directory', 'badges' ); ?>
				<?php
			endif;
			?>
		</div>
		<?php
		stm_get_boats_image_hover( get_the_ID() );
		?>
	</a>
</div>
