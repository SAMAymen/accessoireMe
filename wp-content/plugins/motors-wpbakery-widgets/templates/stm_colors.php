<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( isset( $atts['items'] ) && strlen( $atts['items'] ) > 0 ) {
	$items = vc_param_group_parse_atts( $atts['items'] );
	if ( ! is_array( $items ) ) {
		$temp         = explode( ',', $atts['items'] );
		$param_values = array();
		foreach ( $temp as $value ) {
			$data                   = explode( '|', $value );
			$new_line               = array();
			$new_line['color']      = isset( $data[0] ) ? $data[0] : 0;
			$new_line['color_name'] = isset( $data[1] ) ? $data[1] : '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$new_line['color']      = (float) str_replace( '%', '', $data[1] );
				$new_line['color_name'] = isset( $data[2] ) ? $data[2] : '';
			}
			$param_values[] = $new_line;
		}
		$atts['items'] = rawurlencode( wp_json_encode( $param_values ) );
	}
}

if ( ! empty( $items ) ) : ?>
	<div class="row">
		<?php foreach ( $items as $item ) : ?>
			<?php
			if ( empty( $item['color'] ) ) {
				$item['color'] = '#fff';
			}
			?>
			<div class="stm-boats-color">
				<div class="color-round" style="background-color: <?php echo esc_attr( $item['color'] ); ?>"></div>
				<?php if ( ! empty( $item['color_name'] ) ) : ?>
					<div class="color-label"><?php echo esc_attr( $item['color_name'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
