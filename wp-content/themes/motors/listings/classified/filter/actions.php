<?php
$total_matches  = $filter['total'];
$filter_badges  = stm_get_filter_badges();
if ( isset( $elementor ) ) {
	$elementor_view = $elementor;
} else {
	$elementor_view = null;
}
?>

<div class="stm-car-listing-sort-units stm-car-listing-directory-sort-units clearfix">
	<div class="stm-listing-directory-title">
		<?php if ( empty( $elementor_view ) ) : ?>
			<h3 class="title"><?php echo ( isset( $filter['listing_title'] ) ) ? esc_attr( $filter['listing_title'] ) : ''; ?></h3>
		<?php endif; ?>
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
	<?php if ( empty( $elementor_view ) ) : ?>
	<div class="stm-directory-listing-top__right">
		<div class="clearfix">
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
			<div class="stm-sort-by-options clearfix">
				<span><?php esc_html_e( 'Sort by:', 'motors' ); ?></span>
				<div class="stm-select-sorting">
					<select>
						<?php echo wp_kses_post( apply_filters( 'stm_get_sort_options_html', '' ) ); ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
