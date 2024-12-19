<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// Get columns number.
if ( empty( $filter_columns_number ) ) {
	$filter_columns_number = 2;
}

$filter_columns_number = 12 / $filter_columns_number;

// Get all filter options from STM listing plugin - Listing - listing categories.
$filter_options = apply_filters( 'stm_get_car_filter', array() );

// Creating new array for tax query and meta query.
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
}

foreach ( $filter_options as $filter_option ) {

	if ( in_array( $filter_option['slug'], $filter_selected, true ) ) {

		if ( empty( $filter_option['numeric'] ) ) {
			$terms = get_terms( $filter_option['slug'], $terms_args );

			$tax_query_args[ $filter_option['slug'] ] = $terms;
		} else {
			$terms = get_terms( $filter_option['slug'], $terms_args );
			foreach ( $terms as $stm_term ) {
				$stm_term->numeric = true;
			}

			$tax_query_args[ $filter_option['slug'] ] = $terms;
		}
	}
}
?>
<div class="filter stm-vc-ajax-filter row">
	<?php if ( ! empty( $tax_query_args ) ) : ?>
		<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="get">
			<?php foreach ( $tax_query_args as $taxonomy_term_key => $taxonomy_term ) : ?>
				<?php if ( empty( $taxonomy_term[0]->numeric ) && ! empty( $taxonomy_term[0] ) ) : ?>
					<div class="col-md-<?php echo esc_attr( $filter_columns_number ); ?> col-sm-<?php echo esc_attr( $filter_columns_number ); ?>">
						<div class="form-group">
							<select name="<?php echo esc_attr( $taxonomy_term_key ); ?>" class="form-control">
								<option value=""><?php echo esc_attr( stm_get_name_by_slug( $taxonomy_term_key ) ); ?></option>
								<?php foreach ( $taxonomy_term as $attr_key => $attr ) : ?>
									<option
										value="<?php echo esc_attr( $attr->slug ); ?>"
										<?php
										if ( 0 === $attr->count ) {
											echo 'disabled'; }
										?>
										>
										<?php echo esc_attr( $attr->name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				<?php else : ?>
					<div class="col-md-3 col-sm-3">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<select name="min_<?php echo esc_attr( $taxonomy_term_key ); ?>" class="form-control">
										<option value=""><?php echo esc_attr( 'Min ' . ucfirst( $taxonomy_term_key ) ); ?></option>
										<?php foreach ( $taxonomy_term as $attr_key => $attr ) : ?>
											<option value="<?php echo esc_attr( $attr->slug ); ?>" 
												<?php
												if ( ! empty( $filter_user_args[ 'min_' . $taxonomy_term_key ] ) && $attr->slug === $filter_user_args[ 'min_' . $taxonomy_term_key ] ) {
													echo 'selected'; }
												?>
											>
												<?php echo esc_attr( $attr->name ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<select name="max_<?php echo esc_attr( $taxonomy_term_key ); ?>" class="form-control">
										<option value=""><?php echo esc_attr( 'Max ' . ucfirst( $taxonomy_term_key ) ); ?></option>
										<?php foreach ( $taxonomy_term as $attr_key => $attr ) : ?>
											<option value="<?php echo esc_attr( $attr->slug ); ?>" 
												<?php
												if ( ! empty( $filter_user_args[ 'max_' . $taxonomy_term_key ] ) && $attr->slug === $filter_user_args[ 'max_' . $taxonomy_term_key ] ) {
													echo 'selected'; }
												?>
											>
												<?php echo esc_attr( $attr->name ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="col-md-12">
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="filter" />
				</div>
			</div>
		</form>
	<?php endif; ?>
</div>
