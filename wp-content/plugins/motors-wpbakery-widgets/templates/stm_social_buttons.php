<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$nuxy_whatsapp_massage = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_whatsapp_massage' );
$wpb_whatsapp_massage  = $atts['whatsapp_massage'];

if ( ! empty( $nuxy_whatsapp_massage ) && empty( $wpb_whatsapp_massage ) ) {
	$massage_template = $nuxy_whatsapp_massage;
} elseif ( ! empty( $wpb_whatsapp_massage ) ) {
	$massage_template = $wpb_whatsapp_massage;
} else {
	$massage_template = '';
}

$listing_url   = get_the_permalink( get_the_ID() );
$listing_title = get_the_title( get_the_ID() );

$shortcodes = array(
	'[listing_url]'     => $listing_url,
	'{{listing_url}}'   => $listing_url,
	'[listing_title]'   => $listing_title,
	'{{listing_title}}' => $listing_title,
);

foreach ( $shortcodes as $shortcode => $value ) {
	if ( strpos( $massage_template, $shortcode ) !== false ) {
		$massage_template = str_replace( $shortcode, $value, $massage_template );
	}
}

$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$button_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $button_css, ' ' ) );

$unique_id = 'social_button_' . wp_rand( 1, 99999 );

$listing_author_id = get_post_meta( get_the_ID(), 'stm_car_user', true );
if ( ! empty( $listing_author_id ) ) {
	$user_phone   = get_the_author_meta( 'stm_phone', $listing_author_id );
	$has_whatsapp = get_the_author_meta( 'stm_whatsapp_number', $listing_author_id );

	if ( ! empty( $user_phone ) && ! empty( $has_whatsapp ) ) {
		$phone_number = $user_phone;
	}
}

if ( empty( $phone_number ) ) {
	$blogusers = get_users( array( 'role__in' => array( 'administrator' ) ) );

	if ( ! empty( $blogusers ) ) {
		foreach ( $blogusers as $user ) {
			$phone = get_the_author_meta( 'stm_phone', $user->ID );
			if ( ! empty( $phone ) && empty( $phone_number ) ) {
				$phone_number = $phone;
			}
		}
	}
}

?>
<div class="stm_social_buttons_wrap <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $phone_number ) && ! empty( $has_whatsapp ) ) : ?>
		<div class="whatsapp">
			<a href="https://wa.me/<?php echo esc_attr( trim( preg_replace( '/[^0-9]/', '', $phone_number ) ) ); ?>?text=<?php echo rawurlencode( $massage_template ); ?>" target="_blank">
				<div class="whatsapp-btn heading-font <?php echo esc_attr( $button_class ); ?>" id="<?php echo esc_attr( $unique_id ); ?>">
					<i class="stm-icon-whatsapp"></i>
					<?php echo esc_html__( 'Chat via WhatsApp', 'motors-wpbakery-widgets' ); ?>
				</div>
			</a>
		</div>
	<?php endif; ?>
</div>

<style>
	#<?php echo esc_attr( $unique_id ); ?> {
		<?php if ( ! empty( $button_width ) && is_numeric( $button_width ) ) : ?>
			width: <?php echo esc_attr( floatval( $button_width ) ); ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $button_height ) && is_numeric( $button_height ) ) : ?>
			height: <?php echo esc_attr( floatval( $button_height ) ); ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $button_font_size ) && is_numeric( $button_font_size ) ) : ?>
			font-size: <?php echo esc_attr( floatval( $button_font_size ) ); ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $button_line_height ) && is_numeric( $button_line_height ) ) : ?>
			line-height: <?php echo esc_attr( floatval( $button_line_height ) ); ?>px !important;
		<?php endif; ?>
	}

	.stm_social_buttons_wrap .whatsapp {
		position: relative;
		<?php if ( 'full-width' !== $button_default_width ) : ?>
		max-width: fit-content;
		<?php endif; ?>
	}

	.stm_social_buttons_wrap .whatsapp .whatsapp-btn {
		display: flex;
		align-items: center;
		box-shadow: 0 2px 7px rgba(0, 0, 0, 0.09);
		border: 1px solid #e0e3e7;
		background-color: #ffffff;
		border-radius: 5px;
		color: #121e24;
		font-size: 14px;
		font-weight: 700;
		font-style: normal;
		letter-spacing: normal;
		line-height: 18px;
		text-align: left;
		text-transform: uppercase;
		padding: 13px 17px;
	}

	.stm_social_buttons_wrap .whatsapp .whatsapp-btn:hover {
		background-color: #f8f8f8;
	}

	.stm_social_buttons_wrap .whatsapp .whatsapp-btn .stm-icon-whatsapp {
		margin-right: 15px;
		font-size: 26px;
		color: #45c655;
	}

	.stm_social_buttons_wrap a,
	.stm_social_buttons_wrap a:hover,
	.stm_social_buttons_wrap a:focus,
	.stm_social_buttons_wrap a:active {
		text-decoration: none;
	}

</style>
