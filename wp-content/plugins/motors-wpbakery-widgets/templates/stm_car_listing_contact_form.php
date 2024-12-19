<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$unique_id = 'single_contact_form_' . wp_rand( 1, 99999 );

if ( ! wp_style_is( 'stm_car_listing_contact_form', 'enqueued' ) ) {
	wp_enqueue_style( 'stm_car_listing_contact_form', get_template_directory_uri() . '/assets/css/dist/stm_car_listing_contact_form.css', null, get_bloginfo( 'version' ), 'all' );
}

?>

<div class="stm_listing_car_form <?php echo esc_attr( $css_class ); ?>" id="<?php echo esc_attr( $unique_id ); ?>">
	<div class="stm-single-car-contact">

		<?php if ( ! empty( $title ) ) : ?>
			<div class="title">
				<?php echo esc_html( $title ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $form ) && 'none' !== $form ) : ?>
			<?php $cf7 = get_post( $form ); ?>
			<?php if ( ! empty( $cf7 ) ) : ?>
				<?php echo( do_shortcode( '[contact-form-7 id="' . $cf7->ID . '" title="' . ( $cf7->post_title ) . '"]' ) ); ?>
			<?php endif; ?>
		<?php endif; ?>

	</div>
</div>

<?php
$user_added_by = get_post_meta( get_the_id(), 'stm_car_user', true );
if ( ! empty( $user_added_by ) ) :
	$user_data = get_userdata( $user_added_by );
	if ( $user_data ) :
		?>
		<script>
			jQuery(document).ready(function(){
				var inputAuthor = '<input type="hidden" value="<?php echo intval( $user_added_by ); ?>" name="stm_changed_recepient" />';
				jQuery('#<?php echo esc_js( $unique_id ); ?> form').append(inputAuthor);

				// replace privacy policy consent label
				if(jQuery('#<?php echo esc_js( $unique_id ); ?> .consent .wpcf7-list-item-label').length > 0) {
					var consent_link = 'I accept the <a href="<?php echo ( get_privacy_policy_url() ) ? esc_url( get_privacy_policy_url() ) : '#!'; ?>" target="_blank">privacy policy</a>';
					jQuery('#<?php echo esc_js( $unique_id ); ?> .consent .wpcf7-list-item-label').html(consent_link);
				}
			});
		</script>
		<?php
	endif;
endif;
