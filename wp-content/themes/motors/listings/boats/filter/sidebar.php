<?php
$filter    = apply_filters( 'stm_listings_filter_func', null, true );
$show_sold = apply_filters( 'motors_vl_get_nuxy_mod', '', 'show_sold_listings' );
?>
<form action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="get" data-trigger="filter">
	<div class="filter filter-sidebar stm-filter-sidebar-boats">
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
			<div class="stm-boats-shorten-filter clearfix">
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
					$parent_list = apply_filters( 'stm_listings_parent_list', false );
					if ( ! is_array( $parent_list ) ) {
						$parent_list = array();
					}
					$close_filter = 0;
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

						$close_filter ++;

						if ( 3 === $close_filter ) :
							?>
			</div>
			<div class="stm-boats-expand-filter col-md-12">
				<span><?php esc_html_e( 'More options', 'motors' ); ?></span></div>
			<script type="text/javascript">
				var stm_filter_expand_close = '<?php esc_html_e( 'Less options', 'motors' ); ?>';
			</script>
			<div class="stm-boats-longer-filter clearfix">
				<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>

				<?php
				if ( $show_sold ) :
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
					'filter/types/keywords',
					array(
						'position' => 'bottom',
					)
				);
				?>
			</div>
		</div>

		<!--View type-->
		<input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'view_type' ) ); ?>"/>
		<!--Filter links-->
		<input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
		<!--Popular-->
		<input type="hidden" name="popular" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'popular' ) ); ?>"/>

		<input type="hidden" name="s" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 's' ) ); ?>"/>
		<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>

		<button id="stm-classic-filter-submit" class="stm-classic-filter-submit-boats heading-font" type="submit">
			<i class="stm-icon-search"></i>
			<span><?php echo intval( $filter['total'] ); ?></span>
			<?php esc_html_e( 'Items', 'motors' ); ?>
		</button>

		<?php do_action( 'stm_listings_filter_after' ); ?>
	</div>

	<?php do_action( 'stm_listings_load_template', 'filter/types/checkboxes', array( 'filter' => $filter ) ); ?>

</form>

<?php do_action( 'stm_listings_load_template', 'filter/types/links', array( 'filter' => $filter ) ); ?>
