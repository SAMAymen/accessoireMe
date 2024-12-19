<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_icon_filter', 'stm_wcmap_icon_filter' );

$base_color = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$custom_css = ".stm-wcmap-icon-filter-wrap .stm-wcmap-icon-filter a:hover {
	border-top-color: {$base_color};
}

.stm-wcmap-icon-filter-wrap .stm-wcmap-title-wrap span:hover {
	color: {$base_color};
	border-bottom: 1px dashed {$base_color};
}
";
wp_add_inline_style( 'stm-wcmap-stm_wcmap_icon_filter', $custom_css );

$stm_type = ( 'atts' === $atts['filter_type'] ) ? 'pa_make' : 'product_cat';
$img_type = ( 'atts' === $atts['filter_type'] ) ? 'stm_attr_wcmap_image' : 'thumbnail_id';

$stm_cats = get_terms(
	array(
		'orderby'      => 'id',
		'order'        => 'ASC',
		'fields'       => 'all',
		'show_count'   => 0,
		'hierarchical' => 1,
		'hide_empty'   => 0,
		'parent'       => 0,
		'taxonomy'     => $stm_type,
	)
);

if ( 'product_cat' === $stm_type ) {
	unset( $stm_cats[0] );
}

$shop_page_id = apply_filters( 'woocommerce_get_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );

?>
<div class="stm-wcmap-icon-filter-wrap" >
	<div class="stm-wcmap-title-wrap">
		<h2><?php echo esc_html( $title ); ?></h2>
		<span><?php echo esc_html__( 'See all Makes', 'stm-woocommerce-motors-auto-parts' ); ?></span>
	</div>
	<div class="stm-wcmap-icon-filter">
		<?php

		$i = 0;
		if ( ! is_wp_error( $stm_cats ) ) {
			foreach ( $stm_cats as $k => $stm_cat ) :
				$img_meta = get_term_meta( $stm_cat->term_id, $img_type, true );
				if ( $img_meta ) {
					$img = wp_get_attachment_image_url( $img_meta, 'full' );
				}

				$stm_link = get_term_link( $stm_cat );

				if ( 'pa_make' === $stm_type ) {
					$stm_link = get_the_permalink( $shop_page_id ) . '?filter_make=' . $stm_cat->slug;
				}

				?>
				<a href="<?php echo esc_url( $stm_link ); ?>"
					class="stm_listing_icon_filter_single <?php echo ( $i > 7 ) ? esc_attr( 'non-visible' ) : ''; ?>"
					title="<?php echo esc_attr( $stm_cat->name ); ?>">
					<div class="inner">
						<?php if ( ! empty( $img ) ) : ?>
							<div class="image">
								<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $stm_cat->name ); ?>">
							</div>
						<?php endif; ?>
						<div class="name"><?php echo esc_html( $stm_cat->name ); ?>
							<?php
							if ( $show_count ) :
								?>
							<span class="count">(<?php echo esc_html( $stm_cat->count ); ?>)</span><?php endif; ?></div>
					</div>
				</a>
				<?php

				$i++;
			endforeach;
		}
		?>
	</div>
</div>
