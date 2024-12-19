<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
?>

<div class="<?php echo esc_attr( $css_class ); ?>">
	<!--Blog meta-->
	<div class="blog-meta clearfix">
		<div class="left">
			<div class="clearfix">
				<div class="blog-meta-unit h6">
					<i class="stm-icon-date"></i>
					<span><?php echo get_the_date(); ?></span>
				</div>
				<div class="blog-meta-unit h6">
					<i class="stm-icon-author"></i>
					<span><?php esc_html_e( 'Posted by:', 'motors-wpbakery-widgets' ); ?></span>
					<span><?php the_author(); ?></span>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="blog-meta-unit h6">
				<a href="<?php comments_link(); ?>" class="post_comments h6">
					<i class="stm-icon-message"></i> <?php comments_number(); ?>
				</a>
			</div>
		</div>
	</div>
</div>
