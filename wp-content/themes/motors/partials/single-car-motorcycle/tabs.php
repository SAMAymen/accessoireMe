<?php
$tab_tax       = '';
$tab_tax_exist = false;
$filter        = apply_filters( 'stm_get_car_filter', array() );
foreach ( $filter as $filter_taxex ) {
	if ( ! empty( $filter_taxex['use_on_tabs'] ) && $filter_taxex['use_on_tabs'] && ! $tab_tax_exist ) {
		$tab_tax       = $filter_taxex;
		$tab_tax_exist = true;
	}
}

if ( ! empty( $tab_tax ) ) :
	$p_terms = wp_get_post_terms( get_the_ID(), $tab_tax['slug'] );
	if ( ! is_wp_error( $p_terms ) && ! empty( $p_terms ) ) {
		$p_terms = $p_terms[0]->term_id;
	}

	$cats = stm_get_category_by_slug( $tab_tax['slug'] );

	if ( ! empty( $cats ) ) :
		?>
		<div class="container">
			<div class="stm-filter-tabs-skewed heading-font">
				<div class="stm-filter-tab-single-unit 
				<?php
				if ( ! is_singular( apply_filters( 'stm_listings_post_type', 'listings' ) ) && empty( $_GET[ $tab_tax['slug'] ] ) ) {//phpcs:ignore
					echo esc_attr( 'active' ); }
				?>
				">
					<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>">
						<?php echo esc_html__( 'All', 'motors' ) . ' ' . $tab_tax['plural_name'];//phpcs:ignore ?>
					</a>
				</div>
				<?php foreach ( $cats as $cat ) : ?>
					<?php
					$active = '';
					if ( $cat->term_id === $p_terms ) {
						$active = 'active';
					}

					if ( ! empty( $_GET[ $tab_tax['slug'] ] ) && $_GET[ $tab_tax['slug'] ] === $cat->slug ) {//phpcs:ignore
						$active = 'active';
					}
					?>
					<div class="stm-filter-tab-single-unit <?php echo esc_attr( $active ); ?>">
						<a href="<?php echo esc_url( add_query_arg( $tab_tax['slug'], $cat->slug, apply_filters( 'stm_filter_listing_link', '' ) ) ); ?>">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

<?php endif; ?>
