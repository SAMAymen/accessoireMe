<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
?>

	<!--Comments-->
<?php if ( comments_open() || get_comments_number() ) { ?>
	<div class="stm_post_comments<?php echo esc_attr( $css_class ); ?>">
		<?php comments_template(); ?>
	</div>
<?php } ?>
