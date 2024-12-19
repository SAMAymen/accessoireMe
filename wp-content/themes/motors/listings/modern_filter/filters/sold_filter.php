<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $unit
 */

$listing_rows_numbers_default_expanded = 'false';
if ( isset( $unit['listing_rows_numbers_default_expanded'] ) && 'open' === $unit['listing_rows_numbers_default_expanded'] ) {
	$listing_rows_numbers_default_expanded = 'true';
}
?>
<div class="stm-accordion-single-unit listing_status">
	<a class="title <?php echo ( 'false' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'collapsed' : ''; ?>"
		data-toggle="collapse"
		href="#listing_status"
		aria-expanded="<?php echo esc_attr( $listing_rows_numbers_default_expanded ); ?>">
		<h5><?php esc_html_e( 'Listing status', 'motors' ); ?></h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="collapse content <?php echo ( 'true' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'in' : ''; ?>" id="listing_status">
			<div class="stm-accordion-content-wrapper">
				<div class="stm-single-unit">
					<label>
						<input type="checkbox" name="listing_is_active" data-name="<?php esc_attr_e( 'Active', 'motors' ); ?>" />
						<?php echo esc_html__( 'Active', 'motors' ); ?>
					</label>
				</div>
				<div class="stm-single-unit">
					<label>
						<input type="checkbox" name="listing_is_sold" data-name="<?php esc_attr_e( 'Sold', 'motors' ); ?>" />
						<?php echo esc_html__( 'Sold', 'motors' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
