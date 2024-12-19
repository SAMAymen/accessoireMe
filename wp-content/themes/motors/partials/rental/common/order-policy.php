<?php
$policy_page = apply_filters( 'stm_me_get_nuxy_mod', false, 'order_received' );
if ( ! empty( $policy_page ) ) {
	$policy_page = apply_filters( 'stm_motors_wpml_is_page', $policy_page );
	$page        = get_post( $policy_page );
	if ( ! empty( $page ) ) {
		$content = apply_filters( 'the_content', $page->post_content );
	}
}

if ( ! empty( $content ) ) : ?>
	<div class="stm_policy_content">
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<style type="text/css">
		<?php echo get_post_meta( $page->ID, '_wpb_shortcodes_custom_css', true ); //phpcs:ignore?>
	</style>
<?php endif; ?>
