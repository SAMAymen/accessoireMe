<?php
$top_bar_login = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );
?>
<?php if ( $top_bar_login ) : ?>
	<?php
	if ( ! boolval( apply_filters( 'is_listing', array() ) ) ) :
		?>
		<?php if ( stm_is_woocommerce_activated() ) : ?>
			<div class="pull-right hidden-xs top-bar-auth">
				<div class="header-login-url">
					<?php if ( is_user_logged_in() ) : ?>
						<?php if ( ! apply_filters( 'stm_is_rental', true ) ) : ?>
							<a class="logout-link"
								href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
								title="<?php esc_html_e( 'Log out', 'motors' ); ?>">
								<i class="fa fa-icon-stm_icon_user"></i>
								<?php esc_html_e( 'Log out', 'motors' ); ?>
							</a>
						<?php else : ?>
							<div class="stm-rent-lOffer-account-unit-main">
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"
									class="stm-rent-lOffer-account-main">
									<?php
									if ( is_user_logged_in() ) :
										$user_fields = apply_filters( 'stm_get_user_custom_fields', '' );
										if ( ! empty( $user_fields['image'] ) ) :
											?>
											<div class="stm-dropdown-user-small-avatar">
												<img src="<?php echo esc_url( $user_fields['image'] ); ?>"
													class="im-responsive"/>
											</div>
										<?php else : ?>
										<div class="stm-dropdown-user-small-avatar">
											<i class="stm-service-icon-user"></i>
										</div>
									<?php endif; ?>
									<?php endif; ?>
									<h5 class="my-account-mobile"><?php esc_html_e( 'My Account', 'motors' ); ?></h5>
								</a>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
							<i class="fas fa-user"></i><span
								class="vt-top"><?php esc_html_e( 'Login', 'motors' ); ?></span>
						</a>
						<span class="vertical-divider"></span>
						<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'Register', 'motors' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<?php
		$login_page = apply_filters( 'stm_me_get_nuxy_mod', 1718, 'login_page' );
		$login_page = apply_filters( 'stm_motors_wpml_is_page', $login_page );
		?>
		<?php if ( ! empty( $login_page ) ) : ?>
			<div class="pull-right hidden-xs top-bar-auth">
				<div class="header-login-url">
					<?php if ( is_user_logged_in() ) : ?>
						<a class="logout-link"
							href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
							title="<?php esc_html_e( 'Log out', 'motors' ); ?>">
							<i class="fa fa-icon-stm_icon_user"></i>
							<?php esc_html_e( 'Log out', 'motors' ); ?>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( get_permalink( $login_page ) ); ?>">
							<i class="fas fa-user"></i><span
								class="vt-top"><?php esc_html_e( 'Login', 'motors' ); ?></span>
						</a>
						<span class="vertical-divider"></span>
						<a href="<?php echo esc_url( get_permalink( $login_page ) ); ?>"><?php esc_html_e( 'Register', 'motors' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
