<?php
$sunday    = '';
$monday    = '';
$tuesday   = '';
$wednesday = '';
$thursday  = '';
$friday    = '';
$saturday  = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$days_of_week = array(
	esc_html__( 'Sunday', 'motors-wpbakery-widgets' )    => $sunday,
	esc_html__( 'Monday', 'motors-wpbakery-widgets' )    => $monday,
	esc_html__( 'Tuesday', 'motors-wpbakery-widgets' )   => $tuesday,
	esc_html__( 'Wednesday', 'motors-wpbakery-widgets' ) => $wednesday,
	esc_html__( 'Thursday', 'motors-wpbakery-widgets' )  => $thursday,
	esc_html__( 'Friday', 'motors-wpbakery-widgets' )    => $friday,
	esc_html__( 'Saturday', 'motors-wpbakery-widgets' )  => $saturday,
)

?>

<div class="stm-working-days<?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<div class="title">
			<i class="stm-icon-calendar"></i>
			<span class="h5"><?php echo esc_attr( $title ); ?></span>
		</div>
	<?php endif; ?>

	<table class="stm-working-days-table">
		<?php foreach ( $days_of_week as $key => $label ) : ?>
			<tr>
				<td class="day-label"><?php echo esc_attr( $key ); ?></td>
				<?php if ( empty( $label ) ) : ?>
					<td class="heading-font day-value day-closed">
						<?php esc_html_e( 'Closed', 'motors-wpbakery-widgets' ); ?>
					</td>
				<?php else : ?>
					<td class="heading-font day-value">
						<?php echo esc_attr( $label ); ?>
					</td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
