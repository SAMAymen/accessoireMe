<?php
$filter    = apply_filters( 'stm_listings_filter_func', null, true );
$show_sold = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );
if ( empty( $action ) ) {
	$action = 'listings-result'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
?>
<div class="mobile-overlay"></div>
<div class="mobile-filter-wrapper">
	<div class="mobile-filter">
		<div class="mobile-search-btn">
			<i class="stm-icon-car_search"></i>
			<span class="h4"><?php esc_html_e( 'Search Options', 'motors' ); ?></span>
		</div>
	</div>
</div>

<form class="mobile-search-filter" action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="get"
	data-trigger="filter" data-action="<?php echo esc_attr( $action ); ?>">
	<div class="filter filter-sidebar ajax-filter">
		<div class="sidebar-entry-header-mobile">
			<span class="h4"><?php esc_html_e( 'Search Options', 'motors' ); ?></span>
			<div class="close-btn">
				<span class="close-btn-item"></span>
				<span class="close-btn-item"></span>
			</div>
		</div>
		<?php
		/**
		 * Hook: stm_listings_filter_before.
		 *
		 * @hooked stm_listings_parent_list_response - 10
		 * @hooked stm_listings_filter_nonce_response - 15
		 */
		do_action( 'stm_listings_filter_before' );
		?>
		<div class="row row-pad-top-24">
			<?php
			if ( empty( $filter['filters'] ) ) :
				$post_type_name = __( 'Listings', 'motors' );
				if ( stm_is_multilisting() ) {
					$ml = new STMMultiListing();
					if ( ! empty( $ml->stm_get_current_listing() ) ) {
						$multitype      = $ml->stm_get_current_listing();
						$post_type_name = $multitype['label'];
					}
				}
				?>
				<div class="col-md-12 col-sm-12">
					<p class="text-muted text-center">
						<?php
						printf(
						/* translators: %s post type name */
							esc_html__( 'No categories created for %s', 'motors' ),
							esc_html( $post_type_name )
						);
						?>
					</p>
				</div>
				<?php
			else :
				do_action(
					'stm_listings_load_template',
					'filter/types/keywords',
					array(
						'position' => 'top',
					)
				);

				$parent_list = apply_filters( 'stm_listings_parent_list', false );
				if ( ! is_array( $parent_list ) ) {
					$parent_list = array();
				}
				foreach ( $filter['filters'] as $attribute => $config ) :
					if ( ! empty( $filter['options'][ $attribute ] ) ) :
						if ( ! empty( $config['slider'] ) && ! empty( $config['numeric'] ) ) :
							do_action(
								'stm_listings_load_template',
								'filter/types/slider',
								array(
									'taxonomy' => $config,
									'options'  => $filter['options'][ $attribute ],
								)
							);
						else :
							?>
							<div class="col-md-12 col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
								<div class="form-group">
									<?php
									do_action(
										'stm_listings_load_template',
										'filter/types/select',
										array(
											'options'   => $filter['options'][ $attribute ],
											'name'      => $attribute,
											'is_parent' => in_array( $attribute, $parent_list, true ),
											'multiple'  => array_key_exists( 'is_multiple_select', $config ) ? $config['is_multiple_select'] : false,
										)
									);
									?>
								</div>
							</div>
							<?php
						endif;
					endif;
				endforeach;
				?>

				<?php
				if ( $show_sold && 'listings-sold' !== $action ) :
					$listing_status = apply_filters( 'stm_listings_input', '', 'listing_status' );
					?>
					<div class="col-md-12 col-sm-12 stm-filter_listing_status">
						<div class="form-group">
							<select name="listing_status" aria-label="<?php esc_attr_e( 'Select listing status', 'motors' ); ?>" class="form-control">
								<option value="">
									<?php esc_html_e( 'Listing status', 'motors' ); ?>
								</option>
								<option value="active" <?php selected( $listing_status, 'active' ); ?>>
									<?php esc_html_e( 'Active', 'motors' ); ?>
								</option>
								<option value="sold" <?php selected( $listing_status, 'sold' ); ?>>
									<?php esc_html_e( 'Sold', 'motors' ); ?>
								</option>
							</select>
						</div>
					</div>
				<?php endif; ?>

				<?php
					do_action( 'stm_listings_load_template', 'filter/types/location' );

					do_action(
						'stm_listings_load_template',
						'filter/types/features',
						array(
							'taxonomy' => 'stm_additional_features',
						)
					);

					do_action(
						'stm_listings_load_template',
						'filter/types/keywords',
						array(
							'position' => 'bottom',
						)
					);
			endif;
			?>

		</div>

		<!--View type-->
		<input type="hidden" id="stm_view_type" name="view_type"
			value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'view_type' ) ); ?>"/>
		<!--Filter links-->
		<input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
		<!--Popular-->
		<input type="hidden" name="popular" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'popular' ) ); ?>"/>

		<input type="hidden" name="s" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 's' ) ); ?>"/>
		<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>

		<div class="sidebar-action-units">
			<input id="stm-classic-filter-submit" class="hidden" type="submit"
				value="<?php esc_html_e( 'Show cars', 'motors' ); ?>"/>
		</div>

		<?php do_action( 'stm_listings_filter_after' ); ?>
	</div>
	<?php do_action( 'stm_listings_load_template', 'filter/types/checkboxes', array( 'filter' => $filter ) ); ?>
	<?php do_action( 'stm_listings_load_template', 'filter/types/links', array( 'filter' => $filter ) ); ?>
	<div class="grow-wrapper"></div>
		<div class="sticky-filter-actions">
			<div class="filter-show-cars">
				<button id="show-car-btn-mobile" class="show-car-btn">
					<?php esc_html_e( 'Show ', 'motors' ); ?>
					<?php $total_cars = $filter['total']; ?>
					<span><?php echo esc_html( $total_cars ); ?></span>
					<?php
					if ( stm_is_multilisting() && isset( $post_type ) ) {
						$multilisting   = new STMMultiListing();
						$post_type_name = $multilisting->stm_get_listing_name_by_slug( $post_type );
						echo esc_html( $post_type_name );
					} elseif ( stm_is_multilisting() && is_archive() && ! is_post_type_archive( 'listings' ) ) {
						wp_kses_post( post_type_archive_title() );
					} elseif ( stm_is_motorcycle() ) {
						esc_html_e( 'Moto', 'motors' );
					} elseif ( stm_is_boats() ) {
						esc_html_e( 'Boats', 'motors' );
					} elseif ( stm_is_aircrafts() ) {
						esc_html_e( 'Aircrafts', 'motors' );
					} else {
						esc_html_e( 'Cars', 'motors' );
					}
					?>
				</button>
			</div>
			<a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>" class="mobile-reset-btn">
				<i aria-hidden="true" class="fas fa-undo"></i>
				<span><?php esc_html_e( 'Reset all', 'motors' ); ?></span>
			</a>
		</div>
	</div>
</form>

