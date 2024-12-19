<?php
wp_enqueue_style( 'vc_font_awesome_5' );
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$stm_link  = vc_build_link( $link );

if ( empty( $align ) ) {
	$align = 'left';
}

if ( empty( $box_bg_color ) ) {
	$box_bg_color_rgba = '';
} else {
	$box_bg_color_rgba = $box_bg_color;

}

if ( empty( $box_text_color ) ) {
	$box_text_color = '#fff';
}

$styles = 'background-color:' . $box_bg_color_rgba . '; color:' . $box_text_color . '!important;';

if ( ! empty( $box_bg_color ) ) {
	$styles .= 'box-shadow: 0 2px 0 rgba(' . apply_filters( 'stm_hex2rgb', $box_bg_color ) . ',0.8)';
}

$stm_icon_class = 'stm_icon_class_' . wp_rand( 0, 99999 );

?>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
		.<?php echo esc_attr( $stm_icon_class ); ?>::before {
			color: <?php echo esc_attr( $icon_color ); ?>;
		}
	<?php endif; ?>
</style>

<?php if ( ! empty( $stm_link['url'] ) && ! empty( $stm_link['title'] ) ) : ?>
	<div class="text-<?php echo esc_attr( $align ); ?>">
		<?php if ( empty( $icon ) ) : ?>
			<a class="heading-font stm-button <?php echo esc_attr( $css_class ); ?>"
				style="<?php echo esc_attr( $styles ); ?>"
				href="<?php echo esc_url( $stm_link['url'] ); ?>"
				title="<?php echo esc_attr( $stm_link['title'] ); ?>"
				<?php if ( ! empty( $stm_link['target'] ) ) : ?>
					target="_blank"
				<?php endif; ?>><?php echo esc_attr( $stm_link['title'] ); ?></a>
		<?php else : ?>
			<a class="button stm-button stm-button-icon stm-button-secondary-color <?php echo esc_attr( $css_class ); ?>"
				style="<?php echo esc_attr( $styles ); ?>"
				href="<?php echo esc_url( $stm_link['url'] ); ?>"
				title="<?php echo esc_attr( $stm_link['title'] ); ?>"
				<?php if ( ! empty( $stm_link['target'] ) ) : ?>
					target="_blank"
				<?php endif; ?>>
				<?php
				if ( ! empty( $icon ) ) :
					?>
					<i class="<?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $stm_icon_class ); ?>"></i>
				<?php endif; ?>
				<?php echo esc_html( $stm_link['title'] ); ?>
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>
