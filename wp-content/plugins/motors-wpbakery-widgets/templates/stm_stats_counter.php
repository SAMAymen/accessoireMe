<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

wp_enqueue_script( 'stm-countUp.min.js' );

if ( empty( $duration ) ) {
	$duration = '2.5';
}

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$random_id = wp_rand( 1, 99999 );
?>


<?php if ( ! empty( $counter_value ) ) : ?>

	<div class="stm-counter clearfix">
		<div class="stm-counter-circle heading-font" id="counter_<?php echo esc_attr( $random_id ); ?>"></div>
		<?php if ( ! empty( $title ) ) : ?>
			<div class="stm-counter-label">
				<div class="h4"><?php echo esc_attr( $title ); ?></div>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $append_plus ) && 'yes' === $append_plus ) : ?>
		<style>
			#counter_<?php echo esc_attr( $random_id ); ?>::after {
				content: '+';
			}
		</style>
	<?php endif; ?>

	<script>
		jQuery(window).on('load', function($) {
			var counter_<?php echo esc_attr( $random_id ); ?> = new countUp("counter_<?php echo esc_attr( $random_id ); ?>", 0, <?php echo esc_attr( $counter_value ); ?>, 0, <?php echo esc_attr( $duration ); ?>, {
				useEasing : true,
				useGrouping: true,
				separator : ','
			});

			jQuery(window).on('scroll', function(){
				if( jQuery("#counter_<?php echo esc_attr( $random_id ); ?>").is_on_screen() ){
					counter_<?php echo esc_attr( $random_id ); ?>.start();
				}
			});
		});
	</script>

<?php endif; ?>
