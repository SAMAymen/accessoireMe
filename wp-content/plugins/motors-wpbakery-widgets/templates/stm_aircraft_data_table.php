<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm-data-table">
	<?php if ( ! empty( $title ) ) : ?>
		<h3>
			<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $title, 'Aircraft data table title' ) ); ?>
		</h3>
	<?php endif; ?>
	<div class="stm-data-table-wrap">
		<?php
		$stm_cats = explode( ',', $taxonomy_list_col_one );
		foreach ( $stm_cats as $stm_cat ) {
			$tax_name = get_taxonomy( $stm_cat );
			if ( $tax_name ) {
				$tax_data  = get_the_terms( get_the_ID(), $stm_cat );
				$tax_value = '';

				if ( $tax_data ) {
					if ( count( $tax_data ) > 1 ) {
						foreach ( $tax_data as $k => $stm_tax ) {
							if ( 0 !== $k ) {
								$tax_value .= ', ';
							}

							$tax_value .= $stm_tax->name;
						}
					} else {
						$tax_value = ( $tax_data ) ? $tax_data[0]->name : '';
					}
				} else {
					$tax_value = get_post_meta( get_the_ID(), $stm_cat, true );
				}

				?>
				<div class="data-row-wrap heading-font">
					<div class="left">
						<span><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $tax_name->label, 'Aircraft taxonomy name' ) ); ?></span>
					</div>
					<div class="right">
						<span><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $tax_value, 'Aircraft taxonomy value' ) ); ?></span>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>
