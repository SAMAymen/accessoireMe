<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
?>

<!--Author info-->
<?php if ( get_the_author_meta( 'description' ) ) : ?>
	<div class="stm-author-box clearfix<?php echo esc_attr( $css_class ); ?>">
		<div class="author-image">
			<?php echo get_avatar( get_the_author_meta( 'email' ), 86 ); ?>
		</div>
		<div class="author-content">
			<h6><?php esc_html_e( 'Author:', 'motors-wpbakery-widgets' ); ?></h6>
			<h4><?php the_author_meta( 'nickname' ); ?></h4>
			<div class="author-description">
				<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
			</div>
		</div>
	</div>
<?php endif; ?>
