<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
?>

<div class="stm_mm_top_categories_wrap <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<h3><?php echo esc_html( $title ); ?></h3>
	<?php endif; ?>
	<div class="stm_mm-cats-grid">
	<?php
	if ( ! empty( $atts['child_category'] ) ) {
		$cats = explode( ',', $atts['child_category'] );
		foreach ( $cats as $stm_cat ) {
			if ( empty( trim( $stm_cat ) ) ) {
				continue;
			}

			$stm_term = get_term_by( 'slug', $stm_cat, $atts['main_category'] );

			if ( empty( $stm_term->name ) ) {
				continue;
			}

			$image          = get_term_meta( $stm_term->term_id, 'stm_image', true );
			$image          = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
			$category_image = ( ! empty( $image[0] ) ) ? $image[0] : null;
			?>
			<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '', array( $atts['main_category'] => $stm_term->slug ) ) ); ?>" class="stm_listing_icon_filter_single"
				title="<?php echo esc_attr( $stm_term->name ); ?>">
				<div class="inner">
					<?php if ( ! empty( $category_image ) ) : ?>
					<div class="image">
						<img src="<?php echo esc_url( $category_image ); ?>"
							alt="<?php echo esc_attr( $stm_term->name ); ?>"/>
					</div>
					<?php endif; ?>
					<div class="name"><?php echo esc_html( $stm_term->name ); ?></div>
				</div>
			</a>
			<?php
		}
	}
	?>
	</div>
</div>
