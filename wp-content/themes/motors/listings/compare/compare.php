<?php
motors_include_once_scripts_styles( array( 'uniform', 'uniform-init', 'jquery-effects-slide' ) );
$listings_post_type = apply_filters( 'stm_listings_post_type', 'listings' );
$compared_items     = apply_filters( 'stm_get_compared_items', array(), $listings_post_type );
$filter_options     = apply_filters( 'stm_get_single_car_listings', array() );
$empty_cars         = 3 - count( $compared_items );
$counter            = 0;
$add_to_text        = ( apply_filters( 'stm_is_aircrafts', false ) ) ? esc_html__( 'Add to compare', 'motors' ) : esc_html__( 'Add car to compare', 'motors' );

if ( apply_filters( 'stm_is_boats', false ) ) {
	$add_to_text = esc_html__( 'Add boat to compare', 'motors' );
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$add_to_text = esc_html__( 'Add vehicle to compare', 'motors' );
}

$title_text = esc_html__( 'Compare Vehicles', 'motors' );

if ( apply_filters( 'stm_is_boats', false ) ) {
	$title_text = esc_html__( 'Compare Boats', 'motors' );
}

if ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$title_text = esc_html__( 'Compare', 'motors' );
}

$compare_empty_placeholder = ( apply_filters( 'stm_is_aircrafts', false ) ) ? 'ac_empty_compare.jpg' : 'compare-empty.jpg';
$archive_link              = apply_filters( 'stm_filter_listing_link', '' );
?>

<div class="container">
	<?php if ( ! empty( $compared_items ) || count( $compared_items ) !== 0 ) : ?>
		<?php
		$args = array(
			'post_type'      => $listings_post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__in'       => $compared_items,
		);

		$compares = new WP_Query( $args );

		if ( $compares->have_posts() ) :
			?>
			<div class="row row-4 car-listing-row stm-car-compare-row">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<h2 class="compare-title">
						<?php echo esc_html( $title_text ); ?>
					</h2>
					<div class="colored-separator text-left">
						<?php if ( apply_filters( 'stm_is_boats', false ) ) : ?>
							<div><i class="stm-boats-icon-wave stm-base-color"></i></div>
						<?php else : ?>
							<div class="first-long"></div>
							<div class="last-short"></div>
						<?php endif; ?>
					</div>
				</div>
				<?php
				while ( $compares->have_posts() ) :
					$compares->the_post();
					$counter ++;
					?>
					<!--Compare car description-->
					<div
							class="col-md-3 col-sm-3 col-xs-4 compare-col-stm compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
						<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
							<div class="compare-col-stm-empty">
								<div class="image">
									<?php if ( has_post_thumbnail() ) { ?>
										<div class="stm-compare-car-img">
											<?php the_post_thumbnail( 'stm-img-255', array( 'class' => 'img-responsive ' ) ); ?>
										</div>
									<?php } else { ?>
										<i class="stm-icon-add_car"></i>
										<img class="stm-compare-empty" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>" alt="<?php esc_attr_e( 'Empty', 'motors' ); ?>"/>
									<?php }; ?>
								</div>
							</div>
						</a>
						<div class="remove-compare-unlinkable">
							<span
									class="remove-from-compare"
									data-action="remove"
									data-id="<?php echo esc_attr( get_the_ID() ); ?>"
									data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>">
								<i class="stm-icon-remove"></i>
								<span><?php esc_html_e( 'Remove from list', 'motors' ); ?></span>
							</span>
						</div>
						<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
							<div class="listing-car-item-meta">
								<div class="car-meta-top heading-font clearfix">
									<?php $price = get_post_meta( get_the_id(), 'price', true ); ?>
									<?php $sale_price = get_post_meta( get_the_id(), 'sale_price', true ); ?>
									<?php $car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true ); ?>
									<?php if ( empty( $car_price_form_label ) ) : ?>
										<?php if ( ! empty( $price ) && ! empty( $sale_price ) ) : ?>
											<div class="price discounted-price">
												<div
														class="regular-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
												<div
														class="sale-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></div>
											</div>
										<?php elseif ( ! empty( $price ) ) : ?>
											<div class="price">
												<div
														class="normal-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></div>
											</div>
										<?php endif; ?>
									<?php else : ?>
										<div class="price">
											<div
													class="normal-price"><?php echo esc_attr( $car_price_form_label ); ?></div>
										</div>
									<?php endif; ?>
									<div class="car-title"><?php the_title(); ?></div>
								</div>
							</div>
						</a>

						<span class="btn btn-default add-to-compare hidden" data-action="remove"
							data-id="<?php echo esc_attr( get_the_ID() ); ?>">
							<?php esc_html_e( 'Remove from compare', 'motors' ); ?>
						</span>
					</div> <!--md-3-->
				<?php endwhile; ?>
				<?php for ( $i = 0; $i < $empty_cars; $i ++ ) { ?>
					<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-empty">
						<a href="<?php echo esc_url( $archive_link ); ?>">
							<div class="image">
								<i class="stm-icon-add_car"></i>
								<img class="stm-compare-empty"
									src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
									alt="<?php esc_attr_e( 'Empty', 'motors' ); ?>"/>
							</div>
							<div class="h5"><?php echo esc_html( $add_to_text ); ?></div>
						</a>
					</div>
				<?php } ?>
			</div> <!--row-->
		<?php endif; ?>
		<?php if ( $compares->have_posts() ) : ?>
			<div class="row row-4 stm-compare-row">
				<div class="col-md-3 col-sm-3 hidden-xs">
					<?php if ( ! empty( $filter_options ) ) : ?>
						<div class="compare-options">
							<table>
								<?php foreach ( $filter_options as $filter_option ) : ?>
									<?php if ( 'price' !== $filter_option['slug'] ) { ?>
										<tr>
											<?php $compare_option = get_post_meta( get_the_id(), $filter_option['slug'], true ); ?>
											<td class="compare-value-hover <?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>"
												data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
												<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $filter_option['single_name'], 'Compare page ' . $filter_option['single_name'] ) ); ?>
											</td>
										</tr>
									<?php }; ?>
								<?php endforeach; ?>
							</table>
						</div>
					<?php endif; ?>
				</div>
				<?php
				while ( $compares->have_posts() ) :
					$compares->the_post();
					?>
					<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
						<?php if ( ! empty( $filter_options ) ) : ?>
							<div class="compare-values">
								<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
									<div class="compare-car-visible">
										<?php the_post_thumbnail( 'stm-img-398-x-2', array( 'class' => 'img-responsive stm-img-mobile-compare' ) ); ?>
									</div>
								<?php endif; ?>
								<div class="remove-compare-unlinkable">
									<span class="remove-from-compare"
										data-id="<?php echo esc_attr( get_the_ID() ); ?>"
										data-action="remove"
										data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>">
										<i class="stm-icon-remove"></i>
										<span><?php esc_html_e( 'Remove from list', 'motors' ); ?></span>
									</span>
								</div>
								<h4 class="text-transform compare-car-visible"><?php the_title(); ?></h4>
								<table>
									<?php if ( wp_is_mobile() ) : ?>
										<tr>
											<td class="compare-value-hover">
												<div class="h5" data-option="<?php esc_html_e( 'Price', 'motors' ); ?>">
													&nbsp;
													<?php $price = get_post_meta( get_the_id(), 'price', true ); ?>
													<?php $sale_price = get_post_meta( get_the_id(), 'sale_price', true ); ?>
													<?php $car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true ); ?>
													<?php if ( empty( $car_price_form_label ) ) : ?>
														<?php if ( ! empty( $price ) && ! empty( $sale_price ) ) : ?>
															<span class="regular-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
															<span class="sale-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
														<?php elseif ( ! empty( $price ) ) : ?>
															<span class="normal-price"><?php echo esc_attr( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
														<?php endif; ?>
													<?php else : ?>
														<span class="normal-price"><?php echo esc_attr( $car_price_form_label ); ?></span>
													<?php endif; ?>
												</div>
											</td>
										</tr>
									<?php endif; ?>
									<?php foreach ( $filter_options as $filter_option ) : ?>
										<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $filter_option['slug'] ) ) { ?>
											<tr>
												<?php $compare_option = get_post_meta( get_the_id(), $filter_option['slug'], true ); ?>
												<td class="compare-value-hover <?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>"
													data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
													<div class="h5" data-option="<?php echo esc_attr( $filter_option['single_name'] ); ?>">
														<?php
														if ( ! empty( $compare_option ) ) {
															// if numeric get value from meta.
															if ( ! empty( $filter_option['numeric'] ) && $filter_option['numeric'] ) {
																echo esc_attr( $compare_option );
															} else {
																// not numeric, get category name by meta.
																$data_meta_array = explode( ',', $compare_option );
																$datas           = array();

																if ( ! empty( $data_meta_array ) ) {
																	foreach ( $data_meta_array as $data_meta_single ) {
																		$data_meta = get_term_by( 'slug', $data_meta_single, $filter_option['slug'] );
																		if ( ! empty( $data_meta->name ) ) {
																			$datas[] = esc_attr( $data_meta->name );
																		}
																	}
																}
																if ( ! empty( $datas ) ) {
																	echo esc_html( implode( ', ', $datas ) );

																} else {
																	esc_html_e( 'None', 'motors' );
																}
															}
														} else {
															esc_html_e( 'None', 'motors' );
														}
														?>
													</div>
												</td>
											</tr>
										<?php } ?>
									<?php endforeach; ?>
								</table>
							</div>
						<?php endif; ?>
					</div> <!--md-3-->
				<?php endwhile; ?>
				<?php for ( $i = 0; $i < $empty_cars; $i ++ ) { ?>
					<?php if ( ! empty( $filter_options ) ) : ?>
						<div class="col-md-3 col-sm-3 hidden-xs">
							<div class="compare-options">
								<table>
									<?php foreach ( $filter_options as $filter_option ) : ?>
										<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $filter_option['slug'] ) ) { ?>
											<tr>
												<td class="compare-value-hover">&nbsp;</td>
											</tr>
										<?php }; ?>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					<?php endif; ?>
				<?php } ?>
			</div> <!--row-->

			<?php $add_compare_mobile_class = ( $compares->post_count < 3 ) ? ' add-compare-mobile-show' : ''; ?>

			<div class="add-compare-mobile <?php echo esc_attr( $add_compare_mobile_class ); ?>">
				<a href="<?php echo esc_url( $archive_link ); ?>">
					<div class="image">
						<i class="stm-icon-add_car"></i>
						<img
								src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
								alt="<?php esc_attr_e( 'Empty', 'motors' ); ?>"/>
					</div>
					<div class="add-compare-mobile-label"><?php echo esc_html( $add_to_text ); ?></div>
				</a>
			</div>

			<?php
		endif;

		wp_reset_postdata();

	else : // show empty placeholders if compare is empty.
		?>
		<div class="row row-4 car-listing-row stm-car-compare-row stm-no-cars">
			<div class="col-md-3 col-sm-3">
				<h2 class="compare-title">
					<?php echo esc_html( $title_text ); ?>
				</h2>
				<div class="colored-separator text-left">
					<?php if ( apply_filters( 'stm_is_boats', false ) ) : ?>
						<div><i class="stm-boats-icon-wave stm-base-color"></i></div>
					<?php else : ?>
						<div class="first-long"></div>
						<div class="last-short"></div>
					<?php endif; ?>
				</div>
			</div>
			<?php for ( $i = 0; $i < $empty_cars; $i ++ ) { ?>
				<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-empty">
					<a href="<?php echo esc_url( $archive_link ); ?>">
						<div class="image">
							<i class="stm-icon-add_car"></i>
							<img class="stm-compare-empty"
								src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
								alt="<?php esc_attr_e( 'Empty', 'motors' ); ?>"/>
						</div>
						<div class="h5"><?php echo esc_html( $add_to_text ); ?></div>
					</a>
				</div>
			<?php } ?>
		</div> <!--row-->
		<div class="row row-4 stm-compare-row hidden-xs">
			<div class="col-md-3 col-sm-3 col-xs-4 hidden-xs">
				<?php if ( ! empty( $filter_options ) ) : ?>
					<div class="compare-options">
						<table>
							<?php foreach ( $filter_options as $filter_option ) : ?>
								<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $filter_option['slug'] ) ) { ?>
									<tr>
										<?php $compare_option = get_post_meta( get_the_id(), $filter_option['slug'], true ); ?>
										<td class="compare-value-hover <?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>"
											data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
											<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $filter_option['single_name'], 'Compare page ' . $filter_option['single_name'] ) ); ?>
										</td>
									</tr>
								<?php }; ?>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endif; ?>
			</div>
			<?php for ( $i = 0; $i < $empty_cars; $i ++ ) { ?>
				<?php if ( ! empty( $filter_options ) ) : ?>
					<div class="col-md-3 col-sm-3 col-xs-4">
						<div class="compare-options">
							<table>
								<?php foreach ( $filter_options as $filter_option ) : ?>
									<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $filter_option['slug'] ) ) { ?>
										<tr>
											<td>&nbsp;</td>
										</tr>
									<?php }; ?>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				<?php endif; ?>
			<?php } ?>
		</div> <!--row-->
	<?php endif; ?>

	<!--Additional features-->
	<?php if ( ! empty( $compares ) ) : ?>
		<?php if ( $compares->have_posts() ) : ?>
			<div class="row row-4 row-compare-features hidden-xs">
				<div class="col-md-3 col-sm-3">
					<h4 class="stm-compare-features"><?php esc_html_e( 'Additional features', 'motors' ); ?></h4>
				</div>
				<?php
				while ( $compares->have_posts() ) :
					$compares->the_post();
					?>
					<?php $features = get_post_meta( get_the_ID(), 'additional_features', true ); ?>
					<?php if ( ! empty( $features ) ) : ?>
					<div class="col-md-3 col-sm-3 compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
						<?php $features = explode( ',', $features ); ?>
						<ul class="list-style-2">
							<?php foreach ( $features as $feature ) : ?>
								<li><?php echo esc_attr( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php else : ?>
					<div class="col-md-3 col-sm-3 compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>"></div>
				<?php endif; ?>
				<?php endwhile; ?>

			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>


<div class="compare-empty-car-top">
	<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-empty">
		<a href="<?php echo esc_url( $archive_link ); ?>">
			<div class="image">
				<i class="stm-icon-add_car"></i>
				<img class="stm-compare-empty"
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
					alt="<?php esc_attr_e( 'Empty', 'motors' ); ?>"/>
			</div>
			<div class="h5"><?php echo esc_html( $add_to_text ); ?></div>
		</a>
	</div>
</div>

<div class="compare-empty-car-bottom">
	<?php if ( ! empty( $filter_options ) ) : ?>
		<div class="col-md-3 col-sm-3 col-xs-4 hidden-xs">
			<div class="compare-options">
				<table>
					<?php foreach ( $filter_options as $filter_option ) : ?>
						<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $filter_option['slug'] ) ) { ?>
							<tr>
								<td class="compare-value-hover">&nbsp;</td>
							</tr>
						<?php }; ?>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>

<script>
	jQuery(document).ready(function ($) {

		var heigth = 0;
		$('.compare-values table tbody tr td').each(function () {
			heigth = $(this).height();

			$('.' + $(this).attr('data-value')).each(function () {
				if ($(this).height() > heigth || heigth > 0) {
					heigth = $(this).height() + 18;
					$('.' + $(this).attr('data-value')).css('height', heigth + 'px');
				}
			});
		});

		$('.compare-value-hover').on('hover', function () {
			var dataValue = $(this).data('value');
			$('.compare-value-hover[data-value = ' + dataValue + ']').addClass('hovered');
		}, function () {
			$('.compare-value-hover').removeClass('hovered');
		})
	})
</script>
