<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );
extract( $atts );
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_ap_tabs', 'stm_wcmap_ap_tabs' );

$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#6f9ae2', 'site_style_secondary_color' );
$custom_css      = "
                .stm-template-auto_parts .vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab a span {
                    color: {$secondary_color};
                    border-bottom: 1px dashed {$secondary_color};
                }
                ";
wp_add_inline_style( 'stm-wcmap-stm_wcmap_category_megamenu', $custom_css );

$this->setGlobalTtaInfo();

$this->enqueueTtaStyles();
$this->enqueueTtaScript();

$prepare_content = $this->getTemplateVariable( 'content' );

$class_to_filter  = $this->getTtaGeneralClasses();
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getCSSAnimation( $css_animation );
$css_class        = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$output  = '<div ' . $this->getWrapperAttributes() . '>';
$output .= '<div class="stm_wcmap_ap_tabs_wrap ' . esc_attr( $css_class ) . '">';
$output .= '<div class="stm_nav_title_wrap heading-title">';
$output .= '<h2 class="heading-title">' . $tabs_title . '</h2>';
$output .= $this->getTemplateVariable( 'tabs-list-top' );
$output .= $this->getTemplateVariable( 'tabs-list-left' );
$output .= '</div>';
$output .= '<div class="vc_tta-panels-container">';
$output .= $this->getTemplateVariable( 'pagination-top' );
$output .= '<div class="vc_tta-panels">';
$output .= $prepare_content;
$output .= '</div>';
$output .= $this->getTemplateVariable( 'pagination-bottom' );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo wp_kses_post( apply_filters( 'stm_wcmap_tabs_output', $output ) );
