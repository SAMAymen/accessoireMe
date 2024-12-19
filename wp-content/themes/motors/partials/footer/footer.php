<?php
if ( is_active_sidebar( 'footer' ) ) { ?>
	<?php
	if ( empty( $sidebar_widgets ) ) {
		$sidebar_widgets = get_option( 'sidebars_widgets', array() );
	}

	$widgets_count = $sidebar_widgets;
	$sidebar_count = count( $widgets_count['footer'] );

	$sidebar_class = '';
	if ( $sidebar_count <= 4 ) {
		$sidebar_class = 'less_4';
	} elseif ( $sidebar_count > 8 ) {
		$sidebar_class = 'more_8';
	}

	if ( apply_filters( 'stm_me_get_nuxy_mod', '', 'footer_sidebar_count' ) > 0 ) {
		?>
		<div id="footer-main">
			<div class="footer_widgets_wrapper <?php echo esc_attr( $sidebar_class ); ?>">
				<div class="container">
					<div class="widgets cols_<?php echo esc_attr( apply_filters( 'stm_me_get_nuxy_mod', 4, 'footer_sidebar_count' ) ); ?> clearfix">
						<?php dynamic_sidebar( 'footer' ); ?>
					</div>
				</div>
			</div>
		</div>
<?php }
} ?>
