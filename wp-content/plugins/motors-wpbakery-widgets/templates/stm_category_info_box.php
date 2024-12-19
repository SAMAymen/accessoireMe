<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class     = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$options_data  = get_option( 'stm_vehicle_listing_options' );
$taxonomy_name = get_taxonomy( $cat_slug );
$stm_cat       = get_the_terms( get_the_ID(), $cat_slug );

if ( ! empty( $stm_cat[0] ) ) {
	$stm_cat = $stm_cat[0];
}

$cat_name = '';
if ( ! empty( $stm_cat->name ) ) {
	$cat_name = $stm_cat->name;
} elseif ( ! empty( get_post_meta( get_the_ID(), $cat_slug, true ) ) ) {
	$cat_name = get_post_meta( get_the_ID(), $cat_slug, true );
}

$font = '';

foreach ( $options_data as $opt ) {
	if ( $opt['slug'] === $cat_slug ) {
		$font = $opt['font'];
	}
}

?>

<div class="stm-cat-info-box">
	<i class="<?php echo esc_attr( $font ); ?>"></i>
	<div class="stm-cat-name heading-font">
		<?php echo esc_html( $taxonomy_name->label ); ?>
	</div>
	<div class="stm-cat-val heading-font">
		<?php echo ( ! empty( $cat_name ) ) ? esc_html( $cat_name ) : ''; ?>
	</div>
</div>
