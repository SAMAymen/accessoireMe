<?php
$regular_price_label       = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label       = get_post_meta( get_the_ID(), 'special_price_label', true );
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );

$price      = get_post_meta( get_the_id(), 'price', true );
$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

$data_price = '0';

if ( ! empty( $price ) ) {
	$data_price = apply_filters( 'get_conver_price', $price );
}

if ( ! empty( $sale_price ) ) {
	$data_price = apply_filters( 'get_conver_price', $sale_price );
}

$mileage = get_post_meta( get_the_id(), 'mileage', true );

$data_mileage = '0';

if ( ! empty( $mileage ) ) {
	$data_mileage = $mileage;
}

$classes = array();

/* is listing active or sold? */
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );
$sold             = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
if ( ! empty( $sold ) && 'on' === $sold ) {
	$classes[] = 'listing_is_sold';
} else {
	$classes[] = 'listing_is_active';
}

// remove "special" if the listing is sold.
if ( ! empty( $sold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

$special_car   = get_post_meta( get_the_ID(), 'special_car', true );
$gallery_video = get_post_meta( get_the_ID(), 'gallery_video', true );

$middle_infos = apply_filters( 'stm_get_car_archive_listings', array() );

$total_infos = count( $middle_infos );

$taxonomies = apply_filters( 'stm_get_taxonomies', array() );

$categories = wp_get_post_terms( get_the_ID(), array_values( $taxonomies ) );

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$classes[] = $category->slug . '-' . $category->term_id;
	}
}

// Lat lang location.
$stm_car_location = get_post_meta( get_the_ID(), 'stm_car_location', true );
$stm_to_lng       = get_post_meta( get_the_ID(), 'stm_lng_car_admin', true );
$stm_to_lat       = get_post_meta( get_the_ID(), 'stm_lat_car_admin', true );

$distance = '';
if ( stm_location_validates() ) {

	$stm_from_lng = esc_attr( floatval( $_GET['stm_lng'] ) ); // phpcs:ignore WordPress.Security
	$stm_from_lat = esc_attr( floatval( $_GET['stm_lat'] ) ); // phpcs:ignore WordPress.Security

	if ( ! empty( $stm_to_lng ) && ! empty( $stm_to_lat ) ) {
		$distance = apply_filters( 'stm_calculate_distance_between_two_points', '', $stm_from_lat, $stm_from_lng, $stm_to_lat, $stm_to_lng );
	}
}

$show_title_two_params_as_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );

$car_media    = apply_filters( 'stm_get_car_medias', array(), get_the_id() );
$show_compare = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );

$classes[] = 'stm-special-car-top-' . $special_car;

$placeholder_path = 'Motor-small.jpg';

?>
<div
	class="listing-list-loop stm-listing-directory-list-loop stm-isotope-listing-item all <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-price="<?php echo esc_attr( $data_price ); ?>"
	data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
	data-mileage="<?php echo esc_attr( $data_mileage ); ?>"
	<?php if ( isset( $distance ) ) : ?>
		data-distance="<?php echo esc_attr( floatval( $distance ) ); ?>"
	<?php endif; ?>
	>

	<div class="image">
		<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
			<div class="image-inner interactive-hoverable">
				<?php
				if ( has_post_thumbnail() ) :
					$img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-398-x-2' );
					if ( true === $gallery_hover_interaction ) {
						$thumbs = apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), 'stm-img-398-x-2' );
						if ( empty( $thumbs['gallery'] ) || 1 === count( $thumbs['gallery'] ) ) :
							the_post_thumbnail( 'stm-img-398-x-2', array( 'class' => 'img-responsive' ) );
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
															/* translators: number of remaining photos */
															echo esc_html( sprintf( _n( '%d more photo', '%d more photos', $thumbs['remaining'], 'motors' ), $thumbs['remaining'] ) );
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
						data-src="<?php echo esc_url( $img[0] ); ?>"
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>"
						class="lazy img-responsive"
						alt="<?php echo esc_attr( apply_filters( 'stm_get_img_alt', '', get_post_thumbnail_id( get_the_ID() ) ) ); ?>"
						/>
						<?php
						get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
					}
				else :
					?>
					<img
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>"
					class="img-responsive"
					alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					/>
					<?php
					get_template_part( 'partials/listing-cars/listing-directory', 'badges' );
				endif;
				?>
				<div class="stm_moto_hover_unit">
					<!--Compare-->
					<?php if ( ! empty( $show_compare ) && $show_compare ) : ?>
						<div
							class="stm-listing-compare heading-font stm-compare-directory-new"
							data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
							data-id="<?php echo esc_attr( get_the_id() ); ?>"
							data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
							>
							<i class="stm-service-icon-compare-new"></i>
							<?php esc_html_e( 'Compare', 'motors' ); ?>
						</div>
					<?php endif; ?>
					<?php stm_get_boats_image_hover( get_the_ID() ); ?>
				</div>
			</div>
		</a>
	</div>

	<div class="content">
		<div class="meta-top">
			<?php if ( empty( $car_price_form_label ) ) : ?>
				<?php if ( ! empty( $price ) && ! empty( $sale_price ) && $price !== $sale_price ) : ?>
					<div class="price discounted-price">
						<div class="regular-price">
							<?php if ( ! empty( $special_price_label ) ) : ?>
								<span class="label-price"><?php echo esc_attr( $special_price_label ); ?></span>
							<?php endif; ?>
							<?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
						</div>

						<div class="sale-price">
							<?php if ( ! empty( $regular_price_label ) ) : ?>
								<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
							<?php endif; ?>
							<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
						</div>
					</div>
				<?php elseif ( ! empty( $price ) ) : ?>
					<div class="price">
						<div class="normal-price">
							<?php if ( ! empty( $regular_price_label ) ) : ?>
								<span class="label-price"><?php echo esc_attr( $regular_price_label ); ?></span>
							<?php endif; ?>
							<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
						</div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="price">
					<div class="normal-price">
						<a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_html( get_the_title( get_the_ID() ) ); ?>" data-id="<?php echo get_the_ID(); ?>">
							<span class="heading-font"><?php echo esc_attr( $car_price_form_label ); ?></span>
						</a>
					</div>
				</div>
			<?php endif; ?>
			<div class="title heading-font">
				<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
					<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $show_title_two_params_as_labels ) ); ?>
				</a>
			</div>
		</div>
		<?php if ( ! empty( $middle_infos ) ) : ?>

			<div class="meta-middle">
				<div class="meta-middle-row heading-font clearfix">
					<?php $counter = 0; ?>
					<?php foreach ( $middle_infos as $middle_info_key => $middle_info ) : ?>
						<?php
							$data_meta  = get_post_meta( get_the_id(), $middle_info['slug'], true );
							$data_value = '';
						?>
							<?php
							if ( ! apply_filters( 'stm_is_listing_price_field', true, $middle_info['slug'] ) ) :
								if ( ! empty( $middle_info['numeric'] ) && $middle_info['numeric'] ) :
									$affix = '';
									if ( ! empty( $middle_info['number_field_affix'] ) ) {
										$affix = esc_html( apply_filters( 'stm_dynamic_string_translation', $middle_info['number_field_affix'], 'Number Field Affix' ) );
									}
									$data_value = ucfirst( $data_meta ) . ' ' . $affix;
								else :
									$data_meta_array = explode( ',', $data_meta );
									$data_value      = array();

									if ( ! empty( $data_meta_array ) ) {
										foreach ( $data_meta_array as $data_meta_single ) {
											$data_meta = get_term_by( 'slug', $data_meta_single, $middle_info['slug'] );
											if ( ! empty( $data_meta->name ) ) {
												$data_value[] = esc_attr( $data_meta->name );
											}
										}
									}

								endif;

							endif;
							?>


						<?php if ( ! empty( $data_value ) && '' !== str_replace( ' ', '', $data_value ) ) : ?>
							<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $middle_info['slug'] ) ) : ?>
								<?php $counter++; ?>
								<div class="meta-middle-unit
								<?php
								if ( ! empty( $middle_info['font'] ) ) {
									echo esc_attr( 'font-exists' );}
								?>
								<?php echo ' ' . esc_attr( $middle_info['slug'] ); ?>">
									<div class="meta-middle-unit-top">
										<?php if ( ! empty( $middle_info['font'] ) ) : ?>
											<div class="icon"><i class="<?php echo esc_attr( $middle_info['font'] ); ?>"></i></div>
										<?php endif; ?>

										<div class="name"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $middle_info['single_name'], 'Label Name' ) ); ?></div>
									</div>

									<div class="value">
										<?php
										if ( is_array( $data_value ) ) {
											if ( count( $data_value ) > 1 ) {
												?>
												<div
													class="stm-tooltip-link"
													data-toggle="tooltip"
													data-placement="bottom"
													title="<?php echo esc_attr( implode( ', ', $data_value ) ); ?>">
													<?php echo esc_attr( implode( ', ', $data_value ) ); ?>
												</div>
												<?php
											} else {
												echo esc_attr( implode( ', ', $data_value ) );
											}
										} else {
											echo esc_attr( $data_value );
										}
										?>
									</div>
								</div>
								<div class="meta-middle-unit meta-middle-divider"></div>
							<?php endif; ?>

						<?php endif; ?>

					<?php endforeach; ?>
					<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_stock' ) ) : ?>
						<?php $stock_number = get_post_meta( get_the_ID(), 'stock_number', true ); ?>
						<?php if ( ! empty( $stock_number ) ) : ?>
							<div class="meta-middle-unit
							<?php
							if ( ! empty( $middle_info['font'] ) ) {
								echo esc_attr( 'font-exists' );}
							?>
							<?php echo ' ' . esc_attr( $middle_info['slug'] ); ?>">
								<div class="meta-middle-unit-top"><div class="name"><?php esc_html_e( 'Stock#', 'motors' ); ?></div></div>

								<div class="value"><?php echo esc_attr( $stock_number ); ?></div>
							</div>
							<div class="meta-middle-unit meta-middle-divider"></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="meta-bottom">
			<?php get_template_part( 'partials/listing-cars/motos/list', 'actions' ); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="stm-car-view-more button visible-xs"><?php esc_html_e( 'View more', 'motors' ); ?></a>
	</div>

</div>
