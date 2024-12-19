<?php
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$show_compare              = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );

$size             = 'stm-img-275';
$thumbs           = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $size );
$placeholder_path = 'plchldr255.png';
?>
<div class="col-md-3 col-sm-4 col-xs-12 col-xxs-12 stm-template-front-loop ev-filter-loop">
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn xx">
		<div class="image">

			<?php if ( ! $gallery_hover_interaction || empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) || wp_is_mobile() ) : ?>

				<img
					<?php if ( has_post_thumbnail() ) : ?>
						src="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), $size ) ); ?>"
						srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_post_thumbnail_id( get_the_ID() ), $size ) ); ?>"
						sizes="(max-width: 767px) 100vw, (max-width: 991px) 33vw, (max-width: 1190px) 25vw, 275px"
						alt="<?php echo esc_attr( get_the_title() ); ?>"
					<?php else : ?>
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>"
						alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					<?php endif; ?>
						class="img-responsive" loading="lazy"
				/>

				<?php get_template_part( 'partials/listing-cars/listing-directory', 'badges' ); ?>
				<?php get_template_part( 'partials/price-electric', 'badge' ); ?>

			<?php else : ?>
				<?php
				$array_keys    = array_keys( $thumbs['gallery'] );
				$last_item_key = array_pop( $array_keys );
				?>

				<div class="interactive-hoverable">
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
												/* translators: number of remaining photos */
												echo esc_html( sprintf( _n( '%d more photo', '%d more photos', $thumbs['remaining'], 'motors' ), $thumbs['remaining'] ) );
												?>
											</p>
										</div>
									<?php endif; ?>
									<img
										src="<?php echo esc_url( is_array( $img_url ) ? $img_url[0] : $img_url ); ?>"
										srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumbs['ids'][ $key ], $size ) ); ?>"
										sizes="(max-width: 767px) 100vw, (max-width: 991px) 33vw, (max-width: 1190px) 25vw, 275px"
										alt="<?php echo esc_attr( get_the_title() ); ?>" class="img-responsive" loading="lazy" />
								</div>
							</div>
						<?php endforeach; ?>

						<?php get_template_part( 'partials/listing-cars/listing-directory', 'badges' ); ?>
						<?php get_template_part( 'partials/price-electric', 'badge' ); ?>
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
				</div>

			<?php endif; ?>

			<?php if ( ! empty( $show_compare ) && true === $show_compare ) : ?>
				<div
						class="stm-listing-compare stm-compare-directory-new"
						data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
						data-id="<?php echo esc_attr( get_the_ID() ); ?>"
						data-title="<?php echo esc_attr( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
						data-toggle="tooltip" data-placement="right"
						title="<?php esc_attr_e( 'Add to compare', 'motors' ); ?>"
				>
					<i class="stm-boats-icon-add-to-compare"></i>
				</div>
			<?php endif; ?>
		</div>

		<div class="listing-car-item-meta">
			<div class="car-meta-top heading-font clearfix">
				<div class="car-title">
					<?php
					$listing_title = apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), true );
					if ( ! empty( $listing_title ) ) {
						echo wp_kses( $listing_title, array( 'div' => array( 'class' => array() ) ) );
					}
					?>
				</div>
			</div>

			<?php
			$labels = apply_filters( 'stm_get_car_listings', array() );
			if ( ! empty( $labels ) ) :
				?>

			<div class="car-meta-bottom">
				<ul>
					<?php
					foreach ( $labels as $label ) :
						$label_meta = get_post_meta( get_the_ID(), $label['slug'], true );
						if ( empty( $label_meta ) ) {
							continue;
						}

						if ( ! apply_filters( 'stm_is_listing_price_field', true, $label['slug'] ) ) :
							$single_name = esc_attr__( 'Listing attribute', 'motors' );

							if ( ! empty( $label['single_name'] ) ) {
								$single_name = $label['single_name'];
							}
							?>
							<li title="<?php echo esc_attr( $single_name ); ?>">
								<?php if ( ! empty( $label['font'] ) ) : ?>
									<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
								<?php endif; ?>

								<?php
								if ( ! empty( $label['numeric'] ) && $label['numeric'] ) :
									$affix = '';
									if ( ! empty( $label['number_field_affix'] ) ) {
										$affix = $label['number_field_affix'];
									}
									?>
									<span><?php echo esc_html( $label_meta . $affix ); ?></span>
								<?php else : ?>

									<?php
										$data_meta_array = explode( ',', $label_meta );
										$datas           = array();

									if ( ! empty( $data_meta_array ) ) {
										foreach ( $data_meta_array as $data_meta_single ) {
											$data_meta = get_term_by( 'slug', $data_meta_single, $label['slug'] );
											if ( is_object( $data_meta ) && ! empty( $data_meta->name ) ) {
												$datas[] = esc_attr( $data_meta->name );
											} else {
												echo '---';
											}
										}
									}

									if ( ! empty( $datas ) ) :
										if ( count( $datas ) > 1 ) :
											?>
												<span
													class="stm-tooltip-link"
													data-toggle="tooltip"
													data-placement="bottom"
													title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
													<?php echo esc_html( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
												</span>
											<?php else : ?>
												<span><?php echo esc_html( implode( ', ', $datas ) ); ?></span>
											<?php endif; ?>
									<?php endif; ?>

								<?php endif; ?>
							</li>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
	</a>
</div>
