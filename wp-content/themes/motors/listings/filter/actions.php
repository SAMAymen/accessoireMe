<?php
$total_matches = $filter['total'];
$filter_badges = stm_get_filter_badges();
if ( ! apply_filters( 'stm_is_car_dealer', true ) ) :
	$title_default = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Cars for sale', 'motors' ), 'listing_directory_title_default', );
	?>
	<div class="stm-listing-directory-title">
		<h3 class="title"><?php echo esc_html( $title_default ); ?></h3>
		<?php if ( $total_matches ) : ?>
			<div class="stm-listing-directory-total-matches total stm-secondary-color heading-font"
				<?php
				if ( empty( $filter_badges ) ) :
					?>
					style="display: none;"
				<?php endif; ?>>
					<span>
						<?php echo esc_attr( $total_matches ); ?>
					</span>
				<?php esc_html_e( 'matches', 'motors' ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<div class="stm-car-listing-sort-units clearfix">
	<div class="stm-sort-by-options clearfix">
		<span><?php esc_html_e( 'Sort by:', 'motors' ); ?></span>
		<div class="stm-select-sorting">
			<select>
				<?php echo wp_kses_post( apply_filters( 'stm_get_sort_options_html', '' ) ); ?>
			</select>
		</div>
	</div>

	<?php
	$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );

	if ( wp_is_mobile() ) {
		$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
	}
	$view_type = apply_filters( 'stm_listings_input', $nuxy_mod_option, 'view_type' );

	$view_list = ( 'list' === $view_type ) ? 'active' : '';
	$view_grid = ( 'list' !== $view_type ) ? 'active' : '';

	?>

	<div class="stm-view-by">
		<a href="#" class="view-grid view-type <?php echo esc_attr( $view_grid ); ?>" data-view="grid">
			<i class="stm-icon-grid"></i>
		</a>
		<a href="#" class="view-list view-type <?php echo esc_attr( $view_list ); ?>" data-view="list">
			<i class="stm-icon-list"></i>
		</a>
	</div>
</div>