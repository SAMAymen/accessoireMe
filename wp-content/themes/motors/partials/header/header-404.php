<?php
$logo_url = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$rgba     = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_bg_color' );
?>
<div class="error-header" style="background-color:
<?php
if ( $rgba ) {
	echo esc_attr( $rgba );
}
?>
		!important;">
	<div class="text-center">
		<div class="dp-in">
			<?php if ( ! empty( $logo_url ) && stm_img_exists_by_url( $logo_url ) ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
					<img src="<?php echo esc_url( $logo_url ); ?>" style="width: <?php echo esc_attr( apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' ) ); ?>px;" alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"/>
				</a>
			<?php else : ?>
				<a class="error-header__title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
					<div><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></div>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
