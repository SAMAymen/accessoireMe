<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$title_tag = ( empty( get_post_meta( get_the_ID(), 'stm_title_tag', true ) ) ) ? 'h1' : get_post_meta( get_the_ID(), 'stm_title_tag', true );
?>

<div class="<?php echo esc_attr( $css_class ); ?>">
	<!--Title-->
	<<?php echo esc_attr( $title_tag ); ?> class="post-title"><?php the_title(); ?></<?php echo esc_attr( $title_tag ); ?>>
</div>
