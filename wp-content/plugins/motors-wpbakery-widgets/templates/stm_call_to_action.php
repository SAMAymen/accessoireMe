<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$stm_link  = vc_build_link( $link );

// Get icon or image.
if ( ! empty( $icon_or_image ) && 'image' === $icon_or_image ) {
	if ( ! empty( $text_image ) ) {
		$text_image = wp_get_attachment_image_src( $text_image, 'thumbnail' );
		if ( ! empty( $text_image[0] ) ) {
			$text_image = $text_image[0];
		}
	}
}

// Get bg.
if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$attachment_id = intval( $image[0] );
		$attachment    = wp_get_attachment_image_src( $attachment_id, 'full' );
		if ( is_array( $attachment ) && ! empty( $attachment ) ) {
			$image = $attachment[0];
		} else {
			$image = '';
		}
	}
}

$ca_rand        = wp_rand( 0, 99999 );
$ca_unique      = ' stm-call-to-action-' . $ca_rand;
$stm_icon_class = 'stm_icon_class_' . $ca_rand;

?>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
		.<?php echo esc_attr( $stm_icon_class ); ?>::before {
			color: <?php echo esc_attr( $icon_color ); ?>;
		}
	<?php endif; ?>
</style>

<div class="<?php echo esc_attr( $css_class ); ?>">
<?php if ( ! empty( $stm_link['url'] ) ) : ?>
	<a
		class="icon-box-link"
		href="<?php echo esc_url( $stm_link['url'] ); ?>"
		title="
		<?php
		if ( ! empty( $stm_link['title'] ) ) {
			echo esc_attr( $stm_link['title'] );
		}
		?>
		"
		<?php if ( ! empty( $stm_link['target'] ) ) : ?>
			target="_blank"
		<?php endif; ?>>
	<?php endif; ?>

		<div class="stm-call-to-action-1 <?php echo esc_attr( $css_class . $ca_unique ); ?>">
			<div class="stm-call-action-left">
				<div class="stm-call-action-content">
					<div class="stm-call-action-<?php echo esc_attr( $icon_or_image ); ?>">
						<?php if ( ! empty( $icon_or_image ) && 'image' === $icon_or_image && ! empty( $text_image ) ) : ?>
							<img src="<?php echo esc_url( $text_image ); ?>"
									alt="<?php esc_attr_e( 'Call to action', 'motors-wpbakery-widgets' ); ?>"/>
						<?php endif; ?>
						<?php if ( ! empty( $icon_or_image ) && 'icon' === $icon_or_image ) { ?>
							<i class="<?php echo esc_attr( $text_icon ); ?> <?php echo esc_attr( $stm_icon_class ); ?>"></i>
						<?php } ?>
					</div>
					<?php if ( ! empty( $content ) ) : ?>
						<div class="content heading-font">
							<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( ! empty( $image ) ) : ?>
				<div class="stm-call-action-right">
					<div class="stm-call-action-right-banner" style="background-image:url('<?php echo esc_url( $image ); ?>')"></div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $stm_link['url'] ) ) : ?>
	</a>
<?php endif; ?>
</div>

<?php if ( ! empty( $box_color ) ) : ?>
	<style type="text/css">
		.stm-call-to-action-<?php echo esc_attr( $ca_rand ); ?> .stm-call-action-left,
		.stm-call-to-action-<?php echo esc_attr( $ca_rand ); ?> .stm-call-action-left:after {
			background-color: <?php echo esc_attr( $box_color ); ?>;
		}
	</style>
<?php endif; ?>
