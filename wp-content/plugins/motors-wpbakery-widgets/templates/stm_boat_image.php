<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class        = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$is_sold          = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );
$special_car      = get_post_meta( get_the_ID(), 'special_car', true );
$badge_text       = get_post_meta( get_the_ID(), 'badge_text', true );
$badge_bg_color   = get_post_meta( get_the_ID(), 'badge_bg_color', true );

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors-wpbakery-widgets' );
}

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

?>
<div class="stm-boats-featured-image <?php echo esc_attr( $css_class ); ?>">
	<?php if ( empty( $is_sold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
			<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
		</div>
	<?php elseif ( apply_filters( 'stm_sold_status_enabled', true ) && ! empty( $is_sold ) ) : ?>
		<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
			<?php esc_html_e( 'Sold', 'motors-wpbakery-widgets' ); ?>
		</div>
	<?php endif; ?>
	<?php get_template_part( 'partials/single-car-boats/boat', 'image' ); ?>
</div>
