<?php
$top_bar_login = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );

$link = apply_filters( 'stm_get_author_link', 'register' );

?>

<?php if ( $top_bar_login ) : ?>
	<ul class="login-reg-urls">
		<li><i class="stm-all-icon-lnr-user"></i></li>
		<li><a href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Login', 'motors' ); ?></a></li>
		<li><a href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Register', 'motors' ); ?></a>
		</li>
	</ul>
<?php endif; ?>
