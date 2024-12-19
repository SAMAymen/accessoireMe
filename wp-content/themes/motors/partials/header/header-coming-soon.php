<?php $logo_url = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' ); ?>

<?php if ( stm_img_exists_by_url( $logo_url ) ) : ?>
	<div class="container">
		<div class="coming-soon-header">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html_e( 'Home', 'motors' ); ?>">
				<img src="<?php echo esc_url( $logo_url ); ?>" style="width: <?php echo esc_attr( apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' ) ); ?>px;" alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"/>
			</a>
		</div>
	</div>
<?php else : ?>
	<div class="container">
		<div class="coming-soon-header">
			<a class="coming-soon-header-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
				<div><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></div>
			</a>
		</div>
	</div>
<?php endif; ?>
