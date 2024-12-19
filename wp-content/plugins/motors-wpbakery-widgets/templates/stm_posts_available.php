<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$user      = wp_get_current_user();

if ( ! empty( $user->ID ) ) :
	$limits = apply_filters(
		'stm_get_post_limits',
		array(
			'premoderation' => true,
			'posts_allowed' => 0,
			'posts'         => 0,
			'images'        => 0,
			'role'          => 'user',
		),
		$user->ID,
		'publish'
	);
	?>
		<div class="stm-posts-available-number heading-font <?php echo esc_attr( $css_class ); ?>">
			<?php esc_html_e( 'Slots available', 'motors-wpbakery-widgets' ); ?>: <span><?php echo esc_html( $limits['posts'] ); ?></span>
		</div>
	<?php
endif;
