<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class     = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$listing_attrs = apply_filters( 'stm_get_all_listing_attributes', array(), 'all' );

if ( ! empty( $attributes ) ) {
	$attributes = explode( ',', $attributes );
}

if ( ! empty( $attributes ) && ! empty( $listing_attrs ) ) {
	$attributes_value = array();
	foreach ( $attributes as $attribute ) {
		$arr_key = array_search( $attribute, array_column( $listing_attrs, 'slug' ), true );

		// attribute value.
		$meta_value = get_post_meta( get_the_ID(), $listing_attrs[ $arr_key ]['slug'], true );

		$affix = '';
		if ( ! empty( $listing_attrs[ $arr_key ]['number_field_affix'] ) ) {
			$affix = $listing_attrs[ $arr_key ]['number_field_affix'];
		}

		if ( ! empty( $meta_value ) && 'none' !== $meta_value ) {
			if ( ! empty( $listing_attrs[ $arr_key ]['numeric'] ) && $listing_attrs[ $arr_key ]['numeric'] ) {
				$attributes_value[] = ucfirst( $meta_value . $affix );
			} else {
				$data_meta_array = explode( ',', $meta_value );
				$datas           = array();

				if ( ! empty( $data_meta_array ) ) {
					foreach ( $data_meta_array as $data_meta_single ) {
						$data_meta = get_term_by( 'slug', $data_meta_single, $listing_attrs[ $arr_key ]['slug'] );
						if ( ! empty( $data_meta->name ) ) {
							$datas[] = $data_meta->name . $affix;
						}
					}
				}

				$attributes_value[] = implode( ', ', $datas );
			}
		}
	}
}

$listing_title = apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false );
$stock_number  = get_post_meta( get_the_ID(), 'stock_number', true );
$show_stock    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_stock' );

?>

<div class="stm_ev_title_attributes <?php echo esc_attr( $css_class ); ?>">

	<h1 class="title h2 stm_listing_title">
		<?php echo wp_kses_post( $listing_title ); ?>
	</h1> 
	<p class="ev_title_attributes">
		<?php
		if ( ! empty( $stock_number ) && is_numeric( $stock_number ) && $show_stock ) {
			echo esc_html(
				sprintf(
					/* translators: stock number */
					__( 'Stock #%d', 'motors-wpbakery-widgets' ),
					$stock_number . ' '
				)
			);
		}

		if ( ! empty( $attributes_value ) ) {
			echo esc_html( implode( ' ', $attributes_value ) );
		}
		?>
	</p>
</div>
