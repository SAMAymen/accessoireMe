<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

stm_wcmap_enqueue_scripts_styles( 'stm_wcmap_special_product', 'stm_wcmap_special_product' );

$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#cc6119', 'site_style_base_color' );
$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#6f9ae2', 'site_style_secondary_color' );
$custom_css      = "
            .woocommerce.stm_special_offer .special_offer_product__meta_box .special_offer_product__title > * {
                background: {$base_color};
            }
            
            .woocommerce-loop-product__title a:hover {
                color: {$base_color} !important;
            }
            
            .woocommerce.stm_special_offer .special_offer_product__meta_box .special_offer_product__countdown .special_offer_countdown .count_meta {
                border: 3px solid {$secondary_color};
            }
";

wp_add_inline_style( 'stm-wcmap-stm_wcmap_special_product', $custom_css );

if ( is_shop() ) {
	echo '<style>' . esc_attr( $atts['css'] ) . '</style>';
}

$product = wc_get_product( $product_id );

if ( $product ) :

	$attachment_ids[0] = get_post_thumbnail_id( $product_id );
	$attachment        = wp_get_attachment_image_src( $attachment_ids[0], 'full' );

	if ( ! empty( $datepicker ) && ! empty( $timepicker ) ) {
		wp_enqueue_script( 'jquery.countdown.js', STM_WCMAP_URL . '/assets/js/jquery.countdown.min.js', array( 'jquery' ), '1.0', true );


		$today       = gmdate( 'Y/m/d' );
		$date_format = ( ! empty( $atts['stm_date_format'] ) ) ? $atts['stm_date_format'] : 'Y/m/d';
		$time_format = ( ! empty( $atts['stm_time_format'] ) ) ? $atts['stm_time_format'] : 'H:i';

		$real_day = strtotime( $today );
		$date_end = strtotime( $datepicker );
		$time_end = strtotime( $timepicker );

		$count = wp_rand( 1, 999999 );
	}

	$height                  = ( ! empty( $height ) ) ? $height : 0;
	$height_tablet_landscape = ( empty( $height_tablet_landscape ) ) ? $height : $height_tablet_landscape;
	$height_tablet           = ( ! empty( $height_tablet ) ) ? $height_tablet : $height;
	$height_mobile           = ( ! empty( $height_mobile ) ) ? $height_mobile : $height_tablet;
	?>
	<div class="woocommerce stm_special_offer <?php echo esc_attr( $css ); ?>" >

		<div class="special_offer_product__meta_box">
			<div class="stm-spec-prod-text-info">
				<?php if ( ! empty( $content ) ) : ?>
					<div class="special_offer_product__title">
						<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
					</div>
					<div class="spacer-offer-product-lg" style="<?php echo esc_attr( "height: {$height}px;" ); ?>"></div>
					<div class="spacer-offer-product-md" style="<?php echo esc_attr( "height: {$height_tablet_desktop}px;" ); ?>"></div>
					<div class="visible-sm_landscape" style="<?php echo esc_attr( "height: {$height_tablet_landscape}px;" ); ?>"></div>
					<div class="visible-sm" style="<?php echo esc_attr( "height: {$height_tablet}px;" ); ?>"></div>
					<div class="visible-xs" style="<?php echo esc_attr( "height: {$height_mobile}px;" ); ?>"></div>
				<?php endif; ?>

				<?php if ( ! empty( $product_id ) ) : ?>
					<div class="special_offer_product__meta">
						<h5 class="woocommerce-loop-product__title no_line heading-title">
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<?php echo esc_html( get_the_title( $product_id ) ); ?>
							</a>
						</h5>
						<div class="price heading-title">
							<?php echo wp_kses_post( apply_filters( 'stm_wmap_price_filter', $product->get_price_html() ) ); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $datepicker ) && ! empty( $timepicker ) ) : ?>
					<?php if ( $date_end > $real_day ) : ?>
						<div class="special_offer_product__countdown">
							<div id="<?php echo esc_attr( $count ); ?>" class="special_offer_countdown heading-title"></div>
							<script type="text/javascript">
								(function ($) {
									$(document).ready(function () {
										$('#<?php echo esc_attr( $count ); ?>').countdown('<?php echo esc_js( date_i18n( $date_format, $date_end ) ); ?> <?php echo esc_js( date_i18n( $time_format, $time_end ) ); ?>', function (event) {
											$(this).html(event.strftime('<div class="count_meta heading-font"><div class="count_meta_info">%D<div> <?php esc_html_e( 'days', 'stm-woocommerce-motors-auto-parts' ); ?></div></div></div> <div class="count_meta"><div class="count_meta_info">%H<div> <?php esc_html_e( 'hours', 'stm-woocommerce-motors-auto-parts' ); ?></div></div></div> <div class="count_meta"><div class="count_meta_info">%M<div> <?php esc_html_e( 'minutes', 'stm-woocommerce-motors-auto-parts' ); ?></div></div></div> <div class="count_meta"><div class="count_meta_info">%S<div> <?php esc_html_e( 'seconds', 'stm-woocommerce-motors-auto-parts' ); ?></div></div></div>'));
										});
									});
								})(jQuery);
							</script>
						</div>
					<?php else : ?>
						<div class="special_offer_countdown_out heading-title">
							<?php esc_html_e( 'Time is up, sorry!', 'stm-woocommerce-motors-auto-parts' ); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if ( $attachment ) : ?>
				<div class="stm-spec-prod-img">
					<img src="<?php echo esc_url( $attachment[0] ); ?>" />
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
endif;
