<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_products_list', 'stm_wcmap_products_list' );

$atts = array(
	'limit'        => $number_posts,
	'columns'      => $columns,
	'orderby'      => $orderby,
	'order'        => $order,
	'cat_operator' => 'IN',
);

$wc_prod = new WC_Shortcodes();
switch ( $product_type ) {
	case 'featured':
		echo wp_kses_post( apply_filters( 'stm_fp_filter', $wc_prod::featured_products( $atts ) ) );
		break;
	case 'sale':
		echo wp_kses_post( apply_filters( 'stm_sp_filter', $wc_prod::sale_products( $atts ) ) );
		break;
	case 'best_selling':
		$atts = array(
			'limit'        => $number_posts,
			'columns'      => $columns,
			'cat_operator' => 'IN',
			'best_selling' => 'best_selling',
		);
		echo wp_kses_post( apply_filters( 'stm_prod_filter', $wc_prod::products( $atts ) ) );
		break;
	case 'top_rated':
		echo wp_kses_post( apply_filters( 'stm_trp_filter', $wc_prod::top_rated_products( $atts ) ) );
		break;
	default:
		echo wp_kses_post( apply_filters( 'stm_def_prod_fitler', $wc_prod::products( $atts ) ) );

}
