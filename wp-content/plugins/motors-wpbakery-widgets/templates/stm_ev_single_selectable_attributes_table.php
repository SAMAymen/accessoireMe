<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$taxonomies = array();

if ( ! empty( $attributes ) ) {
	$taxonomies = explode( ',', $attributes );
}

if ( ! empty( $taxonomies ) ) :
	?>
	<div class="selectable_attributes_table <?php echo esc_attr( $css_class ); ?>">
		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="heading-font">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>
		<table>
			<?php
			foreach ( $taxonomies as $tax_slug ) :
				$data_value = apply_filters( 'stm_vl_get_all_by_slug', array(), $tax_slug );
				if ( empty( $data_value ) ) {
					continue;
				}

				$affix = '';

				if ( ! empty( $data_value['number_field_affix'] ) ) {
					$affix = $data_value['number_field_affix'];
				}

				if ( false === apply_filters( 'stm_is_listing_price_field', $data_value['slug'] ) ) :

					$data_meta = get_post_meta( get_the_ID(), $data_value['slug'], true );

					if ( ! apply_filters( 'is_empty_value', $data_meta ) && '' !== $data_meta ) :
						?>
						<tr>
							<td class="t-label"><?php echo esc_html( $data_value['single_name'] ); ?></td>
							<?php if ( ! empty( $data_value['numeric'] ) && $data_value['numeric'] ) : ?>
								<td class="t-value h6">
									<?php echo esc_html( ucfirst( $data_meta . $affix ) ); ?>
								</td>
								<?php
							else :

								if ( is_string( $data_meta ) ) {
									$data_meta_array = explode( ',', $data_meta );
								}

								$datas = array();

								if ( ! empty( $data_meta_array ) ) {
									foreach ( $data_meta_array as $data_meta_single ) {
										$data_meta = get_term_by( 'slug', $data_meta_single, $data_value['slug'] );
										if ( ! empty( $data_meta->name ) ) {
											$datas[] = $data_meta->name . $affix;
										}
									}
								}
								?>
								<td class="t-value h6">
									<?php echo esc_html( implode( ', ', $datas ) ); ?>
								</td>
							<?php endif; ?>
						</tr>
						<?php
					endif;

				endif;

			endforeach;
			?>
		</table>
	</div>
	<?php
endif;
