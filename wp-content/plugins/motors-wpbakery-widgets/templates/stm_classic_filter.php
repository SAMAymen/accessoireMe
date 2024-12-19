<?php
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var WPBakeryShortCode $this
 * @var $quant_listing_on_grid
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$view_type = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type' );

if ( ! empty( $_GET['view_type'] ) && in_array( $_GET['view_type'], array( 'grid', 'list' ), true ) ) {
	$view_type = sanitize_text_field( $_GET['view_type'] );
}

if ( apply_filters( 'stm_is_motorcycle', false ) ) {
	get_template_part( 'partials/single-car-motorcycle/tabs' );
}

$template_args = array(
	'quantity_per_row' => ( ! empty( $quant_listing_on_grid ) ) ? $quant_listing_on_grid : 3,
	'custom_img_size'  => ( ! empty( ${$view_type . '_thumb_img_size'} ) ) ? ${$view_type . '_thumb_img_size'} : null,
);
?>

<div class="<?php echo esc_attr( $css_class ); ?>">
	<?php do_action( 'stm_listings_load_template', 'filter/inventory/main', $template_args ); ?>
</div>
