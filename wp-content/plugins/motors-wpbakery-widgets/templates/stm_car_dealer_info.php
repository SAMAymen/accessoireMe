<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// settings in vc module determine $show_whatsapp and $show_email. Global settings have higher priority over vc module settings.
// if private seller/dealer/user disables showing his/her whatsapp/email on listings, none of the settings can enable the visibility.

$whatsapp_visibility   = 'yes';
$email_visibility      = 'yes';
$setting_show_whatsapp = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_whatsapp' );
$setting_show_email    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_email' );


if ( false === $setting_show_whatsapp ) {
	$whatsapp_visibility = false;
} elseif ( empty( $show_whatsapp ) && true === $setting_show_whatsapp ) {
	$whatsapp_visibility = false;
}

if ( false === $setting_show_email ) {
	$email_visibility = false;
} elseif ( empty( $show_email ) && true === $setting_show_email ) {
	$email_visibility = false;
}

?>

<div class="<?php echo esc_attr( $css_class ); ?>">
	<?php
	get_template_part(
		'partials/single-car-listing/car',
		'dealer',
		array(
			'show_whatsapp' => $whatsapp_visibility,
			'show_email'    => $email_visibility,
		)
	);
	?>
</div>
