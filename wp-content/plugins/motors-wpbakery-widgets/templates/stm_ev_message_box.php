<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$unique_class = 'msgbox_' . wp_rand( 1, 99999 );

?>

<style>
	<?php if ( ! empty( $left_border_color ) ) : ?>
		.<?php echo esc_attr( $unique_class ); ?> {
			border-left: 5px solid <?php echo esc_attr( $left_border_color ); ?> !important;
		}
	<?php endif; ?>
</style>

<div class="stm_ev_message_box <?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $unique_class ); ?>">
	<?php if ( ! empty( $content ) ) : ?>
		<p style="margin: 0;">
			<?php echo esc_html( $content ); ?>
		</p>
	<?php endif; ?>
</div>
