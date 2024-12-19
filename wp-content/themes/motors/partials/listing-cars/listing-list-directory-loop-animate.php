<?php
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );
$badge_text          = get_post_meta( get_the_ID(), 'badge_text', true );
$badge_bg_color      = get_post_meta( get_the_ID(), 'badge_bg_color', true );
$badge_style         = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

$price      = get_post_meta( get_the_id(), 'price', true );
$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

$data_price = '0';

if ( ! empty( $price ) ) {
	$data_price = $price;
}

if ( ! empty( $sale_price ) ) {
	$data_price = $sale_price;
}

$mileage = get_post_meta( get_the_id(), 'mileage', true );

$data_mileage = '0';

if ( ! empty( $mileage ) ) {
	$data_mileage = $mileage;
}

$asSold           = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );

// remove "special" if the listing is sold
if ( ! empty( $asSold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

$special_car   = get_post_meta( get_the_ID(), 'special_car', true );
$gallery_video = get_post_meta( get_the_ID(), 'gallery_video', true );

$middle_infos = apply_filters( 'stm_get_car_archive_listings', array() );

$middle_infos[] = 'location';

$total_infos = count( $middle_infos );

$taxonomies = apply_filters( 'stm_get_taxonomies', array() );

$categories = wp_get_post_terms( get_the_ID(), array_values( $taxonomies ) );

$classes = array();

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$classes[] = $category->slug . '-' . $category->term_id;
	}
}

//Lat lang location
$stm_car_location = get_post_meta( get_the_ID(), 'stm_car_location', true );
$stm_to_lng       = get_post_meta( get_the_ID(), 'stm_lng_car_admin', true );
$stm_to_lat       = get_post_meta( get_the_ID(), 'stm_lat_car_admin', true );

$distance = '';
if ( stm_location_validates() ) {

	$stm_from_lng = esc_attr( floatval( $_GET['stm_lng'] ) );
	$stm_from_lat = esc_attr( floatval( $_GET['stm_lat'] ) );

	if ( ! empty( $stm_to_lng ) && ! empty( $stm_to_lat ) ) {
		$distance = apply_filters( 'stm_calculate_distance_between_two_points', '', $stm_from_lat, $stm_from_lng, $stm_to_lat, $stm_to_lng );
	}
}

$show_title_two_params_as_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );

$car_media    = apply_filters( 'stm_get_car_medias', array(), get_the_id() );
$show_compare = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );

$show_favorite = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_favorite_items' );

$hide_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'hide_price_labels' );

if ( $hide_labels ) {
	$classes[] = 'stm-listing-no-price-labels';
}

if ( ! empty( $asSold ) ) {
	$classes[] = 'car-as-sold';
}

$image_data = array();
if ( ! empty( $args['custom_img_size'] ) ) {
	$image_data['custom_img_size'] = $args['custom_img_size'];
}
?>

<div
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?> animated fadeIn listing-list-loop stm-listing-directory-list-loop stm-isotope-listing-item all"
		data-price="<?php echo esc_attr( $data_price ); ?>"
		data-date="<?php echo get_the_date( 'Ymdhi' ); ?>"
		data-mileage="<?php echo esc_attr( $data_mileage ); ?>"
	<?php if ( isset( $distance ) ) : ?>
		data-distance="<?php echo esc_attr( floatval( $distance ) ); ?>"
	<?php endif; ?>
>

	<?php do_action( 'stm_listings_load_template', 'loop/classified/list/image', $image_data ); ?>

	<div class="content">
		<div class="meta-top">
			<?php if ( $hide_labels && ! empty( $price ) ) : ?>
				<?php
				if ( ! empty( $sale_price ) ) {
					$price = $sale_price;
				}
				?>
				<div class="price">
					<div class="normal-price">
						<?php if ( ! empty( $car_price_form_label ) ) : ?>
							<span class="heading-font"><?php echo esc_attr( $car_price_form_label ); ?></span>
						<?php else : ?>
							<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
						<?php endif; ?>
					</div>
				</div>

			<?php else : ?>
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
							<?php if ( ! empty( $car_price_form_label ) ) : ?>
								<span class="heading-font"><?php echo esc_attr( $car_price_form_label ); ?></span>
							<?php else : ?>
								<span class="heading-font"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="title heading-font">
				<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="rmv_txt_drctn">
					<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $show_title_two_params_as_labels ) ); ?>
				</a>
			</div>
		</div>
		<?php if ( ! empty( $middle_infos ) ) : ?>

			<div class="meta-middle">
				<div class="meta-middle-row clearfix">
					<?php $counter = 0; ?>
					<?php
					foreach ( $middle_infos as $middle_info_key => $middle_info ) :
						if ( 'location' !== $middle_info ) :
							$data_meta  = get_post_meta( get_the_id(), $middle_info['slug'], true );
							$data_value = '';

							if ( ! empty( $data_meta ) && 'none' !== $data_meta && 'price' !== $middle_info['slug'] ) :
								if ( ! empty( $middle_info['numeric'] ) && $middle_info['numeric'] ) :
									$affix = '';
									if ( ! empty( $middle_info['number_field_affix'] ) ) {
										$affix = esc_html( $middle_info['number_field_affix'] );
									}

									if ( ! empty( $middle_info['use_delimiter'] ) ) {
										$data_value = number_format( abs( $data_value ), 0, '', ' ' );
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
					endif //location;
						?>

						<?php
						if ( 'location' === $middle_info ) :
							$data_value = '';
							if ( ! empty( $stm_car_location ) || ! empty( $distance ) ) :
								?>
							<div class="meta-middle-unit font-exists location">
								<div class="meta-middle-unit-top">
									<div class="icon"><i class="stm-service-icon-pin_big"></i></div>
									<div class="name"><?php esc_html_e( 'Distance', 'motors' ); ?></div>
								</div>

								<div class="value">
									<?php if ( ! empty( $distance ) ) : ?>
										<div
												class="stm-tooltip-link"
												data-toggle="tooltip"
												data-placement="bottom"
												title="<?php echo esc_attr( $distance ); ?>">
											<?php echo esc_html( $distance ); ?>
										</div>
									<?php else : ?>
										<div
												class="stm-tooltip-link"
												data-toggle="tooltip"
												data-placement="bottom"
												title="<?php echo esc_attr( $stm_car_location ); ?>">
											<?php echo esc_html( $stm_car_location ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="meta-middle-unit meta-middle-divider"></div>
								<?php $counter++; ?>
						<?php endif; ?>
					<?php endif; ?>
						<?php if ( ! empty( $data_value ) ) : ?>
							<?php if ( 'price' !== $middle_info['slug'] && ! empty( $data_meta ) ) : ?>
								<?php $counter ++; ?>
						<div class="meta-middle-unit <?php echo ( ! empty( $middle_info['font'] ) ) ? esc_attr( 'font-exists' ) : ''; ?> <?php echo ' ' . esc_attr( $middle_info['slug'] ); ?>">
							<div class="meta-middle-unit-top">
								<?php if ( ! empty( $middle_info['font'] ) ) : ?>
									<div class="icon"><i class="<?php echo esc_attr( $middle_info['font'] ); ?>"></i>
									</div>
								<?php endif; ?>
								<div class="name"><?php esc_html( $middle_info['single_name'] ); ?></div>
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


							<?php if ( 0 === $counter % 4 ) : ?>
				</div>
								<?php
								$row_no_filled = $total_infos - ( $counter + 1 );
								if ( $row_no_filled < 5 ) {
									$row_no_filled = 'stm-middle-info-not-filled';
								} else {
									$row_no_filled = '';
								}
								?>
				<div class="meta-middle-row <?php echo esc_attr( $row_no_filled ); ?> clearfix">
					<?php endif; ?>

					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="meta-bottom">
			<?php get_template_part( 'partials/listing-cars/listing-directive-list-loop', 'actions' ); ?>
		</div>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="stm-car-view-more button visible-xs">
			<?php esc_html_e( 'View more', 'motors' ); ?>
		</a>
	</div>
</div>
