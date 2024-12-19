<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class      = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$inventory_link = apply_filters( 'stm_filter_listing_link', '' );

?>

<div class="stm-listing-cetegories-grid-wrap <?php echo esc_attr( $css_class ); ?>">
	<h3><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $title, 'Listing Categories Grid Title' ) ); ?></h3>
	<div class="stm-lcg-items-wrap">
		<?php
			$tax_list = explode( ',', $taxonomy_list );

		foreach ( $tax_list as $stm_tax ) {
			if ( ! empty( trim( $stm_tax ) ) ) {
				$tax_slug       = explode( '|', $stm_tax );
				$slug           = trim( $tax_slug[0] );
				$stm_taxonomy   = trim( $tax_slug[1] );
				$tax_data       = get_term_by( 'slug', $slug, $stm_taxonomy );
				$image          = get_term_meta( $tax_data->term_id ?? null, 'stm_image', true );
				$image          = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
				$category_image = ( $image ) ? $image[0] : stm_get_plchdr( stm_get_current_layout() );
				$filter_link    = $inventory_link . '?' . $stm_taxonomy . '=' . $slug;
				$numeric        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ? apply_filters( 'get_value_from_listing_category', false, $slug, 'numeric' ) : true;
				if ( ! $numeric ) {
					$filter_link = apply_filters( 'stm_filter_listing_link', '' ) . $slug . '/';
				}

				?>
					<div class="stm-lcg-item">
						<a href="<?php echo esc_url( $filter_link ); ?>">
							<img src="<?php echo esc_url( $category_image ); ?>"/>
							<span class="normal-font"><?php echo esc_html( $tax_data->name ?? '' ); ?></span>
						</a>
					</div>
					<?php
			}
		}
		?>
	</div>
</div>
