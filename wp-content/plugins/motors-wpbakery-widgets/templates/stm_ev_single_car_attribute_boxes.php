<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$attributes = apply_filters( 'stm_get_all_listing_attributes', array(), 'all' );

if ( ! empty( $boxes ) ) {
	$boxes = explode( ',', $boxes );
}

if ( empty( $icon_color ) ) {
	$icon_color = '#6c98e1';
}

if ( empty( $text_color ) ) {
	$text_color = '#fff';
}

if ( empty( $box_bg_color ) ) {
	$box_bg_color = '#393b3d';
}

$unique_class = 'attr_boxes_' . wp_rand( 1, 99999 );
?>

<style>
	.<?php echo esc_attr( $unique_class ); ?> p {
		margin: 0;
		color: <?php echo esc_attr( $text_color ); ?>;
	}
</style>

<div class="stm-attribute_boxes <?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $unique_class ); ?>">
	<?php if ( ! empty( $boxes ) && ! empty( $attributes ) ) : ?>
		<div class="row">
			<?php
			foreach ( $boxes as $box ) :
				$arr_key = array_search( $box, array_column( $attributes, 'slug' ), true );

				if ( empty( $attributes[ $arr_key ]['slug'] ) ) {
					continue;
				}
				// icon.
				if ( empty( $attributes[ $arr_key ]['font'] ) ) {
					$icon = 'fas fa-box';
				} else {
					$icon = $attributes[ $arr_key ]['font'];
				}

				// attribute value.
				$meta_value = get_post_meta( get_the_ID(), $attributes[ $arr_key ]['slug'], true );

				$affix = '';
				if ( ! empty( $attributes[ $arr_key ]['number_field_affix'] ) ) {
					$affix = $attributes[ $arr_key ]['number_field_affix'];
				}

				$value = '';
				if ( ! empty( $meta_value ) && 'none' !== $meta_value ) {
					if ( ! empty( $attributes[ $arr_key ]['numeric'] ) && $attributes[ $arr_key ]['numeric'] ) {
						$value = esc_attr( ucfirst( $meta_value . $affix ) );
					} else {
						$data_meta_array = explode( ',', $meta_value );
						$datas           = array();

						if ( ! empty( $data_meta_array ) ) {
							foreach ( $data_meta_array as $data_meta_single ) {
								$data_meta = get_term_by( 'slug', $data_meta_single, $attributes[ $arr_key ]['slug'] );
								if ( ! empty( $data_meta->name ) ) {
									$datas[] = esc_attr( $data_meta->name ) . $affix;
								}
							}
						}

						$value = implode( ', ', $datas );
					}
				}

				?>
				<div class="col-xs-6 col-md-3">
					<div class="attribute-box" style="background-color: <?php echo esc_attr( $box_bg_color ); ?>">
						<i class="<?php echo esc_attr( $icon ); ?>" style="color: <?php echo esc_attr( $icon_color ); ?>"></i>
						<p class="label-text"><?php echo ( ! empty( $attributes[ $arr_key ]['single_name'] ) ) ? esc_attr( $attributes[ $arr_key ]['single_name'] ) : ''; ?></p>
						<p class="value-text"><?php echo esc_html( $value ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>        
	<?php endif; ?>
</div>

