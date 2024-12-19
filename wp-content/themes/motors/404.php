<?php
get_header();
$custom_page_option = apply_filters( 'stm_me_get_nuxy_mod', '', '404_page' );
$page_id            = apply_filters( 'stm_me_get_nuxy_mod', '', '404_page_dropdown' );

if ( $custom_page_option && ! empty( $page_id ) ) {
	if ( defined( 'ELEMENTOR_VERSION' ) && ! empty( get_post_meta( $page_id, '_elementor_edit_mode', 'builder' ) ) ) {
		delete_post_meta( $page_id, '_wpb_vc_js_status' );
		$plugin_name  = \Elementor\Plugin::instance();
		$page_content = $plugin_name->frontend->get_builder_content( $page_id );
		echo do_shortcode( $page_content );
	} elseif ( defined( 'WPB_VC_VERSION' ) && 'true' === get_post_meta( $page_id, '_wpb_vc_js_status', true ) ) {
		delete_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		?>
		<div class="container">
			<?php
			$page_post    = get_post( $page_id );
			$page_content = $page_post->post_content;
			WPBMap::addAllMappedShortcodes();
			echo do_shortcode( apply_filters( 'the_content', $page_content ) );
			$shortcodes_custom_css = get_post_meta( $page_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				echo esc_html( $shortcodes_custom_css );
				echo '</style>';
			}
			?>
		</div>
		<?php
	}
} else {
	?>
	<div class="stm-error-page-unit">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2><?php esc_html_e( 'The page you are looking for does not exist', 'motors' ); ?></h2>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" alt="<?php echo esc_attr__( 'Home', 'motors' ); ?>" class="button"><?php esc_html_e( 'Home Page', 'motors' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
get_footer();
?>
