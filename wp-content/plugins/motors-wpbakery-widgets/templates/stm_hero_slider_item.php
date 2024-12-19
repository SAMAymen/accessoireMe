<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_motors_enqueue_scripts_styles( 'stm_hero_slider' );

$parse_title = explode( ' ', $title );

$new_title = $title;
if ( count( $parse_title ) > 2 ) {
	$new_title = '';
	$new_title = '<span class="stm-white">' . $parse_title[0] . ' ' . $parse_title[1] . '</span>';
	unset( $parse_title[0] );
	unset( $parse_title[1] );
	$new_title .= ' ' . implode( ' ', $parse_title );
}

$uniqid = uniqid();

//phpcs:disable
?>
<div class="swiper-slide">
	<!--This Style for Animation-->
	<style>
        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap .container .stm-info-wrap {
            opacity: 0;
        }

		<?php if( 'style_1' === $info_block_style ) : ?>
        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round:after {
            background-color: <?php echo $info_block_bg; ?>;
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.style_1 .container .stm-info-wrap:after {
            background: <?php echo $info_block_border_color; ?>;
        }
		<?php else : ?>
        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.style_3 .container .stm-info-wrap:after {
            background: <?php echo $info_block_bg; ?>;
        }
		<?php endif; ?>

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-title {
            color: <?php echo $title_color; ?>;
            font-size: <?php echo $title_font_size; ?>px;
            line-height: <?php echo $title_line_height; ?>px;
            font-weight: <?php echo $title_font_weight; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-title {
                font-size: <?php echo $title_font_size_tablet; ?>px;
                line-height: <?php echo $title_line_height_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-title {
                font-size: <?php echo $title_font_size_mobile; ?>px;
                line-height: <?php echo $title_line_height_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit {
            margin-top: <?php echo $price_block_m_t; ?>px;
            margin-bottom: <?php echo $price_block_m_b; ?>px;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit {
                margin-top: <?php echo $price_block_m_t_tablet; ?>px;
                margin-bottom: <?php echo $price_block_m_b_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit {
                margin-top: <?php echo $price_block_m_t_mobile; ?>px;
                margin-bottom: <?php echo $price_block_m_b_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
            margin-top: <?php echo $desc_block_m_t; ?>px;
            margin-bottom: <?php echo $desc_block_m_b; ?>px;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
                margin-top: <?php echo $desc_block_m_t_tablet; ?>px;
                margin-bottom: <?php echo $desc_block_m_b_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
                margin-top: <?php echo $desc_block_m_t_mobile; ?>px;
                margin-bottom: <?php echo $desc_block_m_b_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-hb-price {
            color: <?php echo $title_color; ?>;
            font-size: <?php echo $title_font_size; ?>px;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-hb-price {
                font-size: <?php echo $title_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-hb-price {
                font-size: <?php echo $title_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-divider {
            font-size: <?php echo $delimiter_month_font_size; ?>px;
            color: <?php echo $month_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-divider {
                font-size: <?php echo $delimiter_month_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-divider {
                font-size: <?php echo $delimiter_month_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
            font-size: <?php echo $description_font_size; ?>px;
            line-height: <?php echo $description_line_height; ?>px;
            color: <?php echo $description_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
                font-size: <?php echo $description_font_size_tablet; ?>px;
                line-height: <?php echo $description_line_height_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-round-text {
                font-size: <?php echo $description_font_size_mobile; ?>px;
                line-height: <?php echo $description_line_height_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-currency {
            font-size: <?php echo $currency_font_size; ?>px;
            color: <?php echo $price_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-currency {
                font-size: <?php echo $currency_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-currency {
                font-size: <?php echo $currency_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-price {
            font-size: <?php echo $price_font_size; ?>px;
            color: <?php echo $price_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-price {
                font-size: <?php echo $price_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-price {
                font-size: <?php echo $price_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-label {
            font-size: <?php echo $month_font_size; ?>px;
            color: <?php echo $month_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-label {
                font-size: <?php echo $month_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-label {
                font-size: <?php echo $month_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-value {
            font-size: <?php echo $period_font_size; ?>px;
            color: <?php echo $month_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-value {
                font-size: <?php echo $period_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-price-unit .stm-hb-labels .stm-hb-time-value {
                font-size: <?php echo $period_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-hb-title span.stm-white {
            color: <?php echo $two_first_color; ?> !important;
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button {
            line-height: <?php echo $btn_line_height; ?>px;
            font-size: <?php echo $btn_font_size; ?>px;
            color: <?php echo $btn_text_color; ?>;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button {
                line-height: <?php echo $btn_line_height_tablet; ?>px;
                font-size: <?php echo $btn_font_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button {
                line-height: <?php echo $btn_line_height_mobile; ?>px;
                font-size: <?php echo $btn_font_size_mobile; ?>px;
            }
        }

        .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button i {
            color: <?php echo $btn_icon_color; ?>;
            fill: <?php echo $btn_icon_color; ?>;
            stroke: <?php echo $btn_icon_color; ?>;
            font-size: <?php echo $btn_icon_size; ?>px;
        }

        @media(max-width: 1199px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button i {
                font-size: <?php echo $btn_icon_size_tablet; ?>px;
            }
        }

        @media(max-width: 767px) {
            .stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?> .stm-hero-slider-wrap.loaded .container .stm-info-wrap .stm-hb-round .stm-button i {
                font-size: <?php echo $btn_icon_size_mobile; ?>px;
            }
        }
	</style>
<?php //phpcs:enable ?>
	<div class="stm-hero-slider-item stm-hero-slider-item-<?php echo esc_attr( $uniqid ); ?>">
		<div class="stm-hero-slider-wrap <?php echo esc_attr( $css_class . ' ' . $info_block_style . ' ' . $info_block_position ); ?>">
			<div class="stm-image-wrap">
				<?php echo wp_get_attachment_image( $atts['image'], 'full' ); ?>
			</div>
			<div class="container">
				<div class="stm-info-wrap">
					<div class="stm-hb-round">
						<div class="stm-hb-title heading-font">
							<?php echo wp_kses_post( $new_title ); ?>
						</div>
						<?php if ( 'style_3' !== $info_block_style ) : ?>
							<div class="stm-hb-price-unit heading-font">
								<?php if ( ! empty( $price ) ) : ?>
									<span class="stm-hb-currency">
									<?php echo esc_html( apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) ); ?>
							</span>
									<span class="stm-hb-price">
									<?php echo esc_html( $price ); ?>
							</span>
								<?php endif; ?>
								<?php if ( ! empty( $per_month ) && $period ) : ?>
									<span class="stm-hb-divider"> / </span>
									<span class="stm-hb-labels">
									<span class="stm-hb-time-label">
										<?php echo esc_html( $per_month ); ?>
									</span>
									<span class="stm-hb-time-value">
										<?php echo esc_html( $period ); ?>
									</span>
								</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( 'style_1' !== $info_block_style ) : ?>
							<div class="stm-hb-round-text heading-font">
								<?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $btn_link ) && ! empty( $btn_title ) ) : ?>
							<a class="stm-button heading-font" href="<?php echo esc_url( $btn_link ); ?>" target="_blank">
								<?php if ( ! empty( $btn_icon ) ) : ?>
									<i class="<?php echo esc_attr( $btn_icon ); ?>"></i>
								<?php endif; ?>
								<?php echo esc_html( $btn_title ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
