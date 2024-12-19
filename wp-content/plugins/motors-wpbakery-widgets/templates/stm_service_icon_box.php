<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class   = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$icon_styles = 'style=';

if ( ! empty( $icon_color ) ) {
	$icon_styles .= 'color:' . $icon_color . ';';
}

if ( ! empty( $icon_size ) ) {
	$icon_styles .= 'font-size:' . $icon_size . 'px;';
}

$stm_icon_class = 'stm_iconbox_icon_' . wp_rand( 0, 99999 );

?>

<div class="stm-service-layout-icon-box <?php echo esc_attr( $css_class ); ?>">
	<div class="inner clearfix 
	<?php
	if ( 'yes' === $vertical_a_m ) {
		echo 'vertical_align_middle';}
	?>
	">
		<?php if ( ! empty( $icon ) ) : ?>
			<div class="icon">
				<i class="<?php echo esc_attr( $icon ); ?> stm-service-primary-color <?php echo esc_attr( $stm_icon_class ); ?>" <?php echo esc_attr( $icon_styles ); ?>></i>
			</div>
		<?php endif; ?>
		<div class="icon-box-content">
			<?php if ( ! empty( $title ) ) : ?>
				<div class="title h4"><?php echo esc_html( $title ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $content ) ) : ?>
				<div class="content">
					<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
	.<?php echo esc_attr( $stm_icon_class ); ?>::before {
		color: <?php echo esc_attr( $icon_color ); ?>!important;
	}
	<?php endif; ?>
</style>
