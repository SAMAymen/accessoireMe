<?php
$top_bar       = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_enable' );
$top_bar_menu  = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_menu' );
$top_bar_login = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );
$currency      = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_currency_enable' );
if ( class_exists( 'WooCommerce' ) && class_exists( 'WOOMULTI_CURRENCY_F' ) ) {
	motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2' ) );
	$wooSettings      = new WOOMULTI_CURRENCY_F_Data();
	$currency_list    = $wooSettings->get_currencies();
	$current_currency = $wooSettings->get_current_currency();
	$links            = $wooSettings->get_links();
}
?>
<?php if ( $top_bar ) : ?>
<div id="top-bar">
	<div class="container">
		<div class="top-bar-wrapper">
			<div class="top-bar-left">
				<div class="top-bar-menu">
				<?php if ( $top_bar_menu ) : ?>
					<div class="pull-left top-bar-menu-wrap">
						<?php get_template_part( 'partials/top-bar', 'menu' ); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="top-bar-right">
				<div class="pull-right" >
					<?php if ( ! empty( $top_bar_login ) ) : ?>
					<div class="top-bar-login-register">
						<div class="header-login-url">
							<?php if ( is_user_logged_in() ) : ?>
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="no_deco"><i class="fa fa-user stm_mgr_8"></i><?php esc_html_e( 'My account', 'motors' ); ?></a>
							<?php else : ?>
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
									<i class="fa fa-user"></i><span class="vt-top"><?php esc_html_e( 'Login', 'motors' ); ?> / </span>
								</a>
								<span class="vertical-divider"></span>
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'Register', 'motors' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if ( ! empty( $currency ) ) : ?>
					<div class="top-bar-currency">
						<?php if ( count( $currency_list ) > 1 ) : ?>
						<div class="stm-multi-currency">
							<div class="stm-multi-currency__info">
								<div class="stm-multi-curr__text stm-multi-curr__text_nomargin">
									<?php echo wp_kses( ( ! empty( $element['data']['title'] ) ) ? $element['data']['title'] : esc_html__( 'Currency ', 'motors' ), array( 'br' => array() ) ); ?>
								</div>
							</div>
							<div class="stm-multicurr-select">
								<select id="stm-multi-curr-select">
									<?php
									foreach ( $currency_list as $k => $item ) {
										$selected = ( $current_currency === $item ) ? 'selected' : '';
										$stm_link = ( $current_currency !== $item ) ? $links[ $item ] : '';
										echo '<option ' . esc_attr( $selected ) . ' value="' . esc_attr( $stm_link ) . '">' . esc_html( $item ) . ' (' . esc_html( get_woocommerce_currency_symbol( $item ) ) . ')</option>';
									}
									?>
								</select>
							</div>
						</div>
						<script>
							(function ($) {
								$(document).ready(function () {
									$('#stm-multi-curr-select').on('change', function () {
										window.location = $(this).val();
									});
								});
							})(jQuery);
						</script>
						<?php else : ?>
							<p><?php echo esc_html__( 'Please, install WooCommerce & Multi Currency for WooCommerce', 'motors' ); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
