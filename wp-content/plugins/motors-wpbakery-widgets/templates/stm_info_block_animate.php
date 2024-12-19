<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$img       = wp_get_attachment_image_src( $image );

?>

<div class="stm-info-block-animate">
	<div class="info-wrap">
		<span>
			<div class="stm_iconbox stm_flipbox clearfix stm_iconbox_style_1 stm_iconbox59c2001f7355a text-center  stm_iconbox__icon-top clearfix">
				<div class="stm_flipbox__front">
					<div class="inner">
						<div class="inner-flex">
							<div class="stm_iconbox__icon">
								<img src="<?php echo esc_url( $img[0] ); ?>" />
							</div>
							<div class="ib-title heading-font"><?php echo esc_html( $i_b_title ); ?></div>
						</div>
					</div>
				</div>
				<div class="stm_flipbox__back">
					<div class="inner mbc">
						<div class="inner-flex">
							<div class="ib-title"><?php echo esc_html( $i_b_title ); ?></div>
							<div class="ib-desc"><?php echo wp_kses_post( $content ); ?></div>
						</div>
					</div>
				</div>
			</div>
		</span>
	</div>
</div>
