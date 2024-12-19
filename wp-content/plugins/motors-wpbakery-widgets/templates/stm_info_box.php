<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( ! empty( $title_counter ) && 'yes' === $title_counter ) {
	wp_enqueue_script( 'stm-countUp.min.js' );
}

if ( ! empty( $box_bg_color ) ) {
	$box_bg_style = 'style=background-color:' . $box_bg_color . ';';
} else {
	$box_bg_style = '';
}

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image = $image[0];
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = ( ! empty( $image[0] ) ) ? $image[0] : null;
	}
} else {
	$image = '';
}

if ( ! empty( $title_color ) ) {
	$title_color = 'style=color:' . $title_color . ';';
}

$content_class = 'content-' . wp_rand( 1, 99999 );

if ( ! empty( $content_color ) ) {
	$content_color_style = 'style=color:' . $content_color . '!important;';
} else {
	$content_color_style = '';
}

$random_id = wp_rand( 1, 99999 );

?>

	<div class="stm-info-box <?php echo esc_attr( $css_class ); ?>" <?php echo esc_attr( $box_bg_style ); ?>>
		<div class="inner">
			<?php if ( ! empty( $image ) ) : ?>
				<img src="<?php echo esc_html( $image ); ?>"/>
			<?php endif; ?>
			<?php if ( ! empty( $title ) ) : ?>
				<div id="stm-ib-counter_<?php echo esc_attr( $random_id ); ?>" class="title heading-font" <?php echo esc_attr( $title_color ); ?>><?php echo esc_attr( $title ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $content ) ) : ?>
				<div class="content heading-font <?php echo esc_attr( $content_class ); ?>" <?php echo esc_attr( $content_color_style ); ?>>
					<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

<?php if ( ! empty( $content_color ) ) : ?>
	<style type="text/css">
		.stm-service-layout-info-box .inner .content.<?php echo esc_attr( $content_class ); ?> ul li:before {
			background-color: <?php echo esc_attr( $content_color ); ?>;
		}
	</style>
<?php endif; ?>

<?php if ( ! empty( $title_counter ) && 'yes' === $title_counter ) : ?>

	<script>
		jQuery(window).on('load', function($) {
			var counter_<?php echo esc_attr( $random_id ); ?> = new countUp("stm-ib-counter_<?php echo esc_attr( $random_id ); ?>", 0, <?php echo esc_attr( $title ); ?>, 0, 2.5, {
				useEasing : true,
				useGrouping: true,
				separator : ''
			});

			jQuery(window).on('scroll', function(){
				if( jQuery("#stm-ib-counter_<?php echo esc_attr( $random_id ); ?>").is_on_screen() ){
					counter_<?php echo esc_attr( $random_id ); ?>.start();
				}
			});
		});
	</script>
<?php endif; ?>
