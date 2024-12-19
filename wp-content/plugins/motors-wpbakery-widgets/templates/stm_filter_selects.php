<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// Creating new array for tax query and meta query.
$filter_options = apply_filters( 'stm_get_car_filter', array() );
$tax_query_args = array();

$terms_args = array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => true,
	'fields'     => 'all',
	'pad_counts' => false,
);

if ( ! empty( $filter_selected ) ) {
	$filter_selected = explode( ',', $filter_selected );

	foreach ( $filter_options as $filter_option ) {
		if ( in_array( $filter_option['slug'], $filter_selected, true ) ) {

			if ( empty( $filter_option['numeric'] ) ) {
				$r_tax        = array( 'taxonomy' => $filter_option['slug'] );
				$merged_array = array_merge( $terms_args, $r_tax );
				$terms        = get_terms( $merged_array );

				$tax_query_args[ $filter_option['slug'] ] = $terms;
			} else {
				$terms_args = array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
					'fields'     => 'all',
					'taxonomy'   => $filter_option['slug'],
				);

				$terms = get_terms( $terms_args );
				foreach ( $terms as $stm_term ) {
					$stm_term->numeric = true;
				}
				$tax_query_args[ $filter_option['slug'] ] = $terms;
			}
		}
	}
}

if ( empty( $filter_columns_number ) ) {
	$filter_columns_number = 3;
}

$filter_columns_number = 12 / $filter_columns_number;

?>

<div class="stm_mc-filter-selects filter-listing filter stm-vc-ajax-filter">
	<?php if ( ! empty( $tax_query_args ) ) : ?>
		<div class="row">
			<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="get">
				<?php
				foreach ( $tax_query_args as $taxonomy_term_key => $taxonomy_term ) :
					$tax_info        = apply_filters( 'stm_vl_get_all_by_slug', array(), $taxonomy_term_key );
					$tax_plural_name = '';
					if ( ! empty( $tax_info['plural_name'] ) ) {
						$tax_plural_name = $tax_info['plural_name'];
					}
					?>
					<?php if ( ! empty( $taxonomy_term ) ) : ?>
						<div class="col-md-<?php echo esc_attr( $filter_columns_number ); ?> col-sm-6">
							<div class="stm_mc-plural-name heading-font">
								<?php echo esc_html( $tax_plural_name ); ?>
							</div>
							<div class="form-group">
								<select name="<?php echo esc_attr( $taxonomy_term_key ); ?>" class="form-control">
									<option value="">
										<?php
										printf(
											/* translators: category name */
											esc_html__( 'Select %s', 'motors-wpbakery-widgets' ),
											esc_html( stm_get_name_by_slug( $taxonomy_term_key ) )
										);
										?>
									</option>

									<?php
									if ( ! isset( $taxonomy_term[0]->numeric ) ) :
										foreach ( $taxonomy_term as $attr_key => $attr ) :
											?>
										<option value="<?php echo esc_attr( $attr->slug ); ?>" 
											<?php
											if ( 0 === $attr->count ) {
												echo 'disabled'; }
											?>
										>
											<?php echo esc_attr( $attr->name ); ?>
										</option>
											<?php
										endforeach;
									else :
										$numbers = array();
										foreach ( $terms as $stm_term ) {
											$numbers[] = intval( $stm_term->name );
										}
										sort( $numbers );
										$output = '';
										foreach ( $numbers as $number_key => $number_value ) {
											if ( 0 === $number_key ) {
												$output .= '<option value=">' . $number_value . '">> ' . $number_value . '</option>';
											} elseif ( count( $numbers ) - 1 === $number_key ) {
												$output .= '<option value="<' . $number_value . '">< ' . $number_value . '</option>';
											} else {
												$option_value = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
												$option_name  = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
												$output      .= '<option value="' . $option_value . '"> ' . $option_name . '</option>';
											}
										}
										echo wp_kses_post( $output );
									endif;
									?>
								</select>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<div class="stm_mc-submit-btn">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<?php
							$stm_posts = apply_filters( 'stm_get_custom_taxonomy_count', 0, '', '' );
							?>
							<div class="stm_mc-found">
								<span class="number-label"><?php esc_html_e( 'Found:', 'motors-wpbakery-widgets' ); ?></span>
								<span class="number-found"><?php echo esc_html( intval( $stm_posts ) ); ?></span>
								<?php esc_html_e( 'Vehicles', 'motors-wpbakery-widgets' ); ?>
							</div>
							<button type="submit" class="button icon-button">
								<?php esc_html_e( 'Search', 'motors-wpbakery-widgets' ); ?>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php endif; ?>
</div>

<?php
$bind_tax = apply_filters( 'stm_data_binding_func', array(), true );
if ( ! empty( $bind_tax ) ) :
	?>

	<script>
		(function($) {
			"use strict";

			$(document).ready(function(){
				var stmTaxRelations = <?php echo esc_html( $bind_tax ); ?>;

				$('.stm_mc-filter-selects select:not(.hide)').select2().on('change', function(){

					/*Remove disabled*/

					var stmCurVal = $(this).val();
					var stmCurSelect = $(this).attr('name');

					if (stmTaxRelations[stmCurSelect]) {
						var key = stmTaxRelations[stmCurSelect]['dependency'];
						$('select[name="' + key + '"]').val('');
						if(stmCurVal === '') {
							$('select[name="' + key + '"] > option').each(function () {
								$(this).removeAttr('disabled');
							});

						} else {
							var allowedTerms = stmTaxRelations[stmCurSelect][stmCurVal];

							if(typeof(allowedTerms) == 'object') {
								$('select[name="' + key + '"] > option').removeAttr('disabled');

								$('select[name="' + key + '"] > option').each(function () {
									var optVal = $(this).val();
									if (optVal != '' && $.inArray(optVal, allowedTerms) == -1) {
										$(this).attr('disabled', '1');
									}
								});
							} else {
								$('select[name="' + key + '"]').val(allowedTerms);
							}

							if(typeof(stmTaxRelations[stmCurSelect][stmCurVal]) == 'undefined') {
								$('select[name="' + key + '"] > option').each(function () {
									$(this).removeAttr('disabled');
								});
							}
						}

						$('.stm_mc-filter-selects select[name="' + key + '"]').select2("destroy");

						$('.stm_mc-filter-selects select[name="' + key + '"]').select2();
					}
				});
			});

		})(jQuery);
	</script>

<?php endif; ?>
