<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class       = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$css_share_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_share, ' ' ) );
?>

<div class="blog-meta-bottom <?php echo esc_attr( $css_class ); ?>">
	<div class="clearfix">
		<div class="left">
			<!--Categories-->
			<?php $stm_cats = get_the_category( get_the_id() ); ?>
			<?php if ( ! empty( $stm_cats ) ) : ?>
				<div class="post-cat">
					<span class="h6"><?php esc_html_e( 'Category:', 'motors-wpbakery-widgets' ); ?></span>
					<?php foreach ( $stm_cats as $stm_cat ) : ?>
						<span class="post-category">
							<a href="<?php echo esc_url( get_category_link( $stm_cat->term_id ) ); ?>"><span><?php echo esc_html( $stm_cat->name ); ?></span></a><span class="divider">,</span>
						</span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<!--Tags-->
			<?php
			$tags = wp_get_post_tags( get_the_ID() );
			if ( ! empty( $tags ) ) {
				?>
				<div class="post-tags">
					<span class="h6"><?php esc_html_e( 'Tags:', 'motors-wpbakery-widgets' ); ?></span>
					<span class="post-tag">
						<?php echo wp_kses_post( get_the_tag_list( '', ', ', '' ) ); ?>
					</span>
				</div>
			<?php } ?>
		</div>

		<div class="right">
			<div class="stm-shareble<?php echo esc_attr( $css_share_class ); ?>">
				<a
					href="#"
					class="car-action-unit stm-share"
					title="<?php esc_html_e( 'Share this', 'motors-wpbakery-widgets' ); ?>"
					download>
					<i class="stm-icon-share"></i>
					<?php esc_html_e( 'Share this', 'motors-wpbakery-widgets' ); ?>
				</a>
				<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) && ! get_post_meta( get_the_ID(), 'sharing_disabled', true ) ) : ?>
					<div class="stm-a2a-popup">
						<?php echo wp_kses_post( apply_filters( 'stm_add_to_any_shortcode', get_the_ID() ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
