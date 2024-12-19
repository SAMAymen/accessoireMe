<?php if ( is_user_logged_in() ) : ?>
	<?php
		$user = wp_get_current_user();
	if ( ! is_wp_error( $user ) ) :

		$link = apply_filters( 'stm_get_author_link', $user->data->ID );

		$cars_total = ( class_exists( \MotorsVehiclesListing\User\UserListingsController::class ) ) ? \MotorsVehiclesListing\User\UserListingsController::get_listings_count( $user->ID ) : 0;

		$my_fav = get_the_author_meta( 'stm_user_favourites', $user->ID );

		if ( ! empty( $my_fav ) ) {
			$my_fav = count( array_filter( explode( ',', $my_fav ) ) );
		} else {
			$my_fav = 0;
		}
		?>

	<div class="lOffer-account-dropdown login">
		<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'settings' ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>" class="settings">
			<i class="stm-settings-icon stm-service-icon-cog"></i>
		</a>
		<div class="name">
			<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user->ID, '', '', '' ) ); ?></a>
		</div>
		<ul class="account-list">
			<li><a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>"><?php esc_html_e( 'My items', 'motors' ); ?> (<span><?php echo esc_html( $cars_total ); ?></span>)</a></li>
			<?php if ( stm_show_my_plans() ) : ?>
			<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'my-plans' ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>"><?php esc_html_e( 'My plans', 'motors' ); ?></a></li>
			<?php endif; ?>
			<li class="stm-my-favourites"><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'favourite' ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>"><?php esc_html_e( 'Favorites', 'motors' ); ?> (<span><?php echo esc_attr( $my_fav ); ?></span>)</a></li>
		</ul>
		<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="logout">
			<i class="fas fa-power-off"></i><?php esc_html_e( 'Logout', 'motors' ); ?>
		</a>
	</div>

	<?php endif; ?>

<?php else : ?>
	<div class="lOffer-account-dropdown stm-login-form-unregistered">
		<form method="post">
			<?php do_action( 'stm_before_signin_form' ); ?>
			<div class="form-group">
				<h4><?php esc_html_e( 'Login or E-mail', 'motors' ); ?></h4>
				<input type="text" name="stm_user_login" autocomplete="off" placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'motors' ); ?>"/>
			</div>

			<div class="form-group">
				<h4><?php esc_html_e( 'Password', 'motors' ); ?></h4>
				<input type="password" name="stm_user_password" autocomplete="off" placeholder="<?php esc_attr_e( 'Enter password', 'motors' ); ?>"/>
			</div>

			<div class="form-group form-checker">
				<label>
					<input type="checkbox" name="stm_remember_me" />
					<span><?php esc_html_e( 'Remember me', 'motors' ); ?></span>
				</label>
				<div class="stm-forgot-password">
					<a href="#">
						<?php esc_html_e( 'Forgot Password', 'motors' ); ?>
					</a>
				</div>
			</div>
			<?php if ( class_exists( 'SitePress' ) ) : ?>
				<input type="hidden" name="current_lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ); ?>"/>
			<?php endif; ?>
			<input class="heading-font" type="submit" value="<?php esc_attr_e( 'Login', 'motors' ); ?>"/>
			<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
			<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>" class="stm_label"><?php esc_html_e( 'Sign Up', 'motors' ); ?></a>
			<div class="stm-validation-message"></div>
			<?php do_action( 'stm_after_signin_form' ); ?>
		</form>
		<form method="post" class="stm_forgot_password_send" style="display: none;">
			<div class="form-group">
				<a href="#" class="stm-forgot-password-back">
					<i class="fa-solid fa-angle-left"></i>
				</a>
				<h4><?php esc_html_e( 'Login or E-mail', 'motors' ); ?></h4>
				<input type="hidden" name="stm_link_send_to" value="<?php echo esc_attr( get_permalink() ); ?>" readonly/>
				<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'motors' ); ?>"/>
				<input type="submit" value="<?php esc_attr_e( 'Send password', 'motors' ); ?>"/>
				<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
				<div class="stm-validation-message"></div>
			</div>
		</form>
	</div>
<?php endif; ?>
