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
<div class="stm-accordion-single-unit <?php echo esc_attr( $unit['slug'] ); ?>">
	<a class="title <?php echo ( 'false' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'collapsed' : ''; ?>"
		data-toggle="collapse"
		href="#<?php echo esc_attr( $unit['slug'] ); ?>"
		aria-expanded="<?php echo esc_attr( $listing_rows_numbers_default_expanded ); ?>">
		<h5><?php echo esc_html( $unit['single_name'] ); ?></h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="collapse content <?php echo ( 'true' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'in' : ''; ?>"
			id="<?php echo esc_attr( $unit['slug'] ); ?>">
			<div class="stm-accordion-content-wrapper">
				<?php foreach ( $terms as $term ) : ?>
					<?php if ( ! empty( $_GET[ $unit['slug'] ] ) && $_GET[ $unit['slug'] ] === $term->slug ) { ?>
					<script>
						jQuery(window).on('load', function () {
							var $ = jQuery;
							$('input[name="<?php echo esc_attr( $term->slug . '-' . $term->term_id ); ?>"]').trigger('click');
							$.uniform.update();
						});
					</script>
				<?php } ?>
					<div class="stm-single-unit">
						<label>
							<input type="checkbox"
									name="<?php echo esc_attr( $term->slug . '-' . $term->term_id ); ?>"
									data-name="<?php echo esc_attr( $term->name ); ?>"
							/>
							<?php echo esc_attr( $term->name ); ?>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
