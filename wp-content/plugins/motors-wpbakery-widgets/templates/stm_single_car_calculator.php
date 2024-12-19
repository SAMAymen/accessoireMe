<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$unique    = 'stm_calc_' . wp_rand( 1, 99999 );
?>
<div class="stm_auto_loan_calculator_wrap <?php echo esc_attr( $css_class ); ?>" id="<?php echo esc_attr( $unique ); ?>">
	<?php do_action( 'stm_listings_load_template', 'single-car/car-calculator' ); ?>
</div>

<script>
(function ($) {
	$(document).on('ready', function () {
		let parent_width = $('#<?php echo esc_js( $unique ); ?>').width();
		if(parent_width < 360) {
			$('#<?php echo esc_js( $unique ); ?> .stm_changeable_breakpoint').removeClass('col-md-3');
			$('#<?php echo esc_js( $unique ); ?> .stm_changeable_breakpoint').removeClass('col-sm-3');
			$('#<?php echo esc_js( $unique ); ?> .stm_changeable_breakpoint').removeClass('col-sm-9');
			$('#<?php echo esc_js( $unique ); ?> .stm_changeable_breakpoint').addClass('col-md-12');
			$('#<?php echo esc_js( $unique ); ?> .stm_calculator_results').css('flex-direction', 'column');
		}
	});
})(jQuery);
</script>
