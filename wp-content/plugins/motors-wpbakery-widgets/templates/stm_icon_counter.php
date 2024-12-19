<?php
wp_enqueue_style( 'vc_font_awesome_5' );
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $stm_counter_time ) ) {
	$stm_counter_time = '2.5';
}

$css_class        = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$unique_id        = wp_rand( 1, 99999 );
$box_bg_color     = ( ! empty( $box_bg_color ) ) ? 'color:' . $box_bg_color . '; ' : '';
$text_align       = 'text-align: ' . $counter_text_align . ';';
$number_font_size = 'font-size: ' . $stm_counter_value_font_size . 'px;';
$label_font_size  = 'font-size: ' . $stm_counter_label_font_size . 'px;';
$stm_icon_class   = 'stm_icon_class_' . wp_rand( 0, 99999 );

?>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
		.<?php echo esc_attr( $stm_icon_class ); ?>::before {
			color: <?php echo esc_attr( $icon_color ); ?>;
		}
	<?php endif; ?>
</style>

<?php if ( ! empty( $stm_counter_value ) ) : ?>

	<div class="stm-mt-icon-counter" <?php echo esc_attr( $css_class ); ?>>

		<div class="dp-in">
			<div class="clearfix">
				<?php if ( ! empty( $icon ) ) : ?>
					<div class="stm-mt-icon-counter-left">
						<i class="<?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $stm_icon_class ); ?>" style="<?php echo esc_attr( $box_bg_color ); ?>"></i>
					</div>
				<?php endif; ?>

				<div class="stm-counter-meta heading-font" style="<?php echo esc_attr( $box_bg_color . $text_align ); ?>">
					<div class="stm-value-wrapper">
						<div class="stm-value" id="counter_<?php echo esc_attr( $unique_id ); ?>" style="<?php echo esc_attr( $number_font_size ); ?>"></div>
						<?php if ( ! empty( $stm_counter_affix ) ) : ?>
							<div class="stm-value-affix"><?php echo esc_attr( $stm_counter_affix ); ?></div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $stm_counter_label ) ) : ?>
						<div class="stm-label" style="<?php echo esc_attr( $label_font_size ); ?>"><?php echo esc_attr( $stm_counter_label ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>



	<script>
		jQuery(window).on('load', function($) {
			var counter_<?php echo esc_attr( $unique_id ); ?> = new countUp("counter_<?php echo esc_attr( $unique_id ); ?>", 0, <?php echo esc_attr( $stm_counter_value ); ?>, 0, <?php echo esc_attr( $stm_counter_time ); ?>, {
				useEasing : true,
				useGrouping: true,
				separator : ''
			});
   
			jQuery(window).on('scroll', function(){
				if( jQuery("#counter_<?php echo esc_attr( $unique_id ); ?>").is_on_screen() ){
					counter_<?php echo esc_attr( $unique_id ); ?>.start();
				}
			});
		});
	</script>

<?php endif; ?>
