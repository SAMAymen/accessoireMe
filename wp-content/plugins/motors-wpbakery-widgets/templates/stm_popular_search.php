<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$filter_link  = apply_filters( 'stm_filter_listing_link', '' );
$stm_title    = '';
$stm_tax_list = explode( ',', $stm_taxonomy_list );

foreach ( $stm_tax_list as $k => $stm_tax ) {
	if ( ! empty( trim( $stm_tax ) ) ) {
		$tax_slug     = explode( '|', $stm_tax );
		$slug         = trim( $tax_slug[0] );
		$stm_taxonomy = trim( $tax_slug[1] );
		$tax_data     = get_term_by( 'slug', $slug, $stm_taxonomy );
		$image        = get_term_meta( $tax_data->term_id, 'stm_image', true );
		$filter_link .= ( 0 === $k ) ? '?' : '&';
		$filter_link .= $stm_taxonomy . '=' . $slug;
		$stm_title    = ( 0 === $k ) ? $tax_data->name : ' ' . $tax_data->name;
	}
}

?>
<div class="stm-ps-item">
	<a href="<?php echo esc_url( $filter_link ); ?>">
		<span><?php echo esc_html( $stm_title ); ?></span>
		<i class="fas fa-chevron-right"></i>
	</a>
</div>
