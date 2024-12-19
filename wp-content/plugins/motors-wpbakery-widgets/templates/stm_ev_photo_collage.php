<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$random = wp_rand( 1, 99999 );
$unique = 'stm_collage_' . $random;

if ( empty( $offset_left ) ) {
	$offset_left = 0;
}

if ( empty( $frame_color ) ) {
	$frame_color = '#ffffff';
}

$photos       = array();
$heights      = array();
$total_height = 0;

if ( ! empty( $images ) ) {
	$images = explode( ',', $images );
}

if ( is_array( $images ) && ! empty( $images ) ) {
	foreach ( $images as $attachment_id ) {
		$image = wp_get_attachment_image_src( $attachment_id, 'full' );

		if ( ! empty( $image[2] ) && $image[2] > 500 ) {
			$image = wp_get_attachment_image_src( $attachment_id, 'medium' );
		}

		if ( ! empty( $image[0] ) && ! empty( $image[2] ) ) {
			// array items keyed by photo height.
			$photos[] = array(
				'url'    => $image[0],
				'height' => $image[2],
			);
		}
	}
}

if ( ! empty( $photos ) ) :
	?>
	<div class="stm_ev_photo_collage <?php echo esc_attr( $css_class . ' ' . $unique ); ?>">
		<div class="collage_wrap">
			<?php
			$offset_top   = 0;
			$total_height = 0;
			foreach ( $photos as $key => $img ) :
				if ( 0 === $key ) :
					?>
					<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php esc_html_e( 'Photo collage', 'motors-wpbakery-widgets' ); ?>">
					<?php
				else :
					$prev_key = 0;
					if ( $key > 0 ) {
						$prev_key = $key - 1;
					}

					$prev_height = ceil( $photos[ $prev_key ]['height'] / 2 );

					if ( 0 === $offset_top ) {
						$offset_top = $prev_height + 30;
					} else {
						if ( 0 === $key % 2 ) {
							$offset_top = $offset_top + $prev_height + 75;
						} else {
							$offset_top = $offset_top + $prev_height + 45;
						}
					}

					?>
					<img src="<?php echo esc_url( $img['url'] ); ?>" style="top: <?php echo esc_attr( $offset_top ); ?>px" alt="<?php esc_html_e( 'Photo collage', 'motors-wpbakery-widgets' ); ?>">
					<?php
				endif;
			endforeach;
			$last = end( $photos );
			if ( ! empty( $last['height'] ) ) {
				$total_height = $offset_top + $last['height'];
			}
			?>
		</div>
	</div>
	<?php
endif;
?>

<style>
	@media( min-width: 992px ) {
		.<?php echo esc_attr( $unique ); ?> .collage_wrap img:nth-child(even) {
			border: 15px solid <?php echo esc_attr( $frame_color ); ?>;
			left: <?php echo esc_attr( $offset_left ); ?>px;
		}
	}
</style>

<script>
	(function ($) {
		$(window).on('load', function () {
			var total_height = <?php echo esc_js( $total_height ); ?>;
			if($(window).width() > 991) {
				$('.<?php echo esc_attr( $unique ); ?> .collage_wrap').css('height', total_height);
			}
		});

		$(window).on('resize', function () {
			var total_height = <?php echo esc_js( $total_height ); ?>;
			if($(window).width() > 991) {
				$('.<?php echo esc_attr( $unique ); ?> .collage_wrap').css('height', total_height);
			} else {
				$('.<?php echo esc_attr( $unique ); ?> .collage_wrap').css('height', '');
			}
		});
	})(jQuery);
</script>
