<?php
$top_bar               = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_enable' );
$top_bar_login         = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );
$top_bar_wpml_switcher = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_wpml_switcher' );


if ( ! empty( $top_bar ) && $top_bar ) :
	?>

	<div id="top-bar">
		<div class="container">

			<?php
			if ( function_exists( 'icl_get_languages' ) ) :
				$langs = icl_get_languages( 'skip_missing=1&orderby=id&order=asc' );
			endif;
			?>
				<div class="clearfix top-bar-wrapper">
				<!--LANGS-->
				<?php if ( ! empty( $top_bar_wpml_switcher ) && $top_bar_wpml_switcher ) : ?>
					<?php if ( ! empty( $langs ) ) : ?>
						<?php
						if ( count( $langs ) > 1 ) {
							$langs_exist = 'dropdown_toggle';
						} else {
							$langs_exist = 'no_other_langs';
						}

						$lang_attrs = '';
						if ( count( $langs ) > 1 ) {
							$lang_attrs = 'id="lang_dropdown" data-toggle="dropdown"';
						}

						?>
						<div class="pull-left language-switcher-unit">
							<div class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>" <?php echo esc_attr( $lang_attrs ); ?>>
								<?php echo esc_html( ICL_LANGUAGE_NAME ); ?>
								<?php if ( count( $langs ) > 1 ) : ?>
									<i class="fas fa-angle-down"></i>
								<?php endif; ?>
							</div>
							<?php if ( count( $langs ) > 1 ) : ?>
								<ul class="dropdown-menu lang_dropdown_menu" role="menu" aria-labelledby="lang_dropdown">
									<?php foreach ( $langs as $lang ) : ?>
										<?php if ( ! $lang['active'] ) : ?>
											<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo esc_url( $lang['url'] ); ?>"><?php echo esc_attr( $lang['native_name'] ); ?></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="pull-left">
					<?php apply_filters( 'stm_get_currency_selector_html', '' ); ?>
				</div>


				<div class="stm-boats-top-bar-right clearfix">

					<!-- Header Top bar Login -->
					<?php if ( ! empty( $top_bar_login ) && $top_bar_login ) : ?>
						<?php if ( ! apply_filters( 'stm_is_listing', true ) ) : ?>
							<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) : ?>
								<div class="pull-right hidden-xs">
									<div class="header-login-url">
										<?php if ( is_user_logged_in() ) : ?>
											<a class="logout-link" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" title="<?php esc_attr_e( 'Log out', 'motors' ); ?>">
												<i class="stm-boats-icon-cap-hat"></i>
												<?php esc_html_e( 'Log out', 'motors' ); ?>
											</a>
										<?php else : ?>
											<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
												<i class="stm-boats-icon-cap-hat"></i><span class="vt-top"><?php esc_html_e( 'Sign Up', 'motors' ); ?></span>
											</a>
											<span class="vertical-divider"></span>
											<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'Login', 'motors' ); ?></a>
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
								<div class="pull-right hidden-xs">
									<div class="header-login-url">
										<?php if ( is_user_logged_in() ) : ?>
											<a class="logout-link" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" title="<?php esc_html_e( 'Log out', 'motors' ); ?>">
												<i class="fa fa-icon-stm_icon_user"></i>
												<?php esc_html_e( 'Log out', 'motors' ); ?>
											</a>
										<?php else : ?>
											<a href="<?php echo esc_url( get_permalink( $login_page ) ); ?>">
												<i class="fas fa-user"></i><span class="vt-top"><?php esc_html_e( 'Login', 'motors' ); ?></span>
											</a>
											<span class="vertical-divider"></span>
											<a href="<?php echo esc_url( get_permalink( $login_page ) ); ?>"><?php esc_html_e( 'Register', 'motors' ); ?></a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

					<div class="stm-boats-top-bar-centered clearfix">

						<?php
						$top_bar_address        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address' );
						$top_bar_address_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_address_mobile' );

						$hidden_address_class = '';
						if ( false === $top_bar_address_mobile ) {
							$hidden_address_class = 'hidden-info';
						}

						$top_bar_working_hours        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_working_hours' );
						$top_bar_working_hours_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_working_hours_mobile' );

						$hidden_workhour_class = '';
						if ( false === $top_bar_working_hours_mobile ) {
							$hidden_workhour_class = 'hidden-info';
						}

						$top_bar_phone        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_phone' );
						$top_bar_phone_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_phone_mobile' );

						$hidden_info_class = '';
						if ( false === $top_bar_phone_mobile ) {
							$hidden_info_class = 'hidden-info';
						}

						$top_bar_menu = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_menu' );

						if ( $top_bar_menu ) :
							?>
							<div class="top_bar_menu">
								<?php get_template_part( 'partials/top-bar', 'menu' ); ?>
							</div>
							<?php
						endif;

						if ( $top_bar_address || $top_bar_working_hours || $top_bar_phone ) :
							?>
							<ul class="top-bar-info clearfix">
								<?php if ( $top_bar_working_hours ) { ?>
									<li class="<?php echo esc_attr( $hidden_workhour_class ); ?>"><?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_working_hours_icon', 'far fa-fa fa-calendar-check' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_working_hours, 'Top Bar Working Hours' ) ); ?></li>
								<?php } ?>
								<?php
								$top_bar_address_url = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address_url' );
								$fancy_frame_class   = '';
								if ( ! empty( $top_bar_address_url ) ) {
									$fancy_frame_class = 'fancy-iframe';
								}
								?>
								<?php if ( $top_bar_address ) { ?>
									<li class="<?php echo esc_attr( $hidden_address_class ); ?>">
										<span id="top-bar-address" class=" <?php esc_attr( $fancy_frame_class ); ?>" data-iframe="true" data-src="<?php echo esc_url( $top_bar_address_url ); ?>">
											<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_address_icon', 'fa fa-map-marker' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_address, 'Top Bar Address' ) ); ?>
										</span>
									</li>
								<?php } ?>
								<?php if ( $top_bar_phone ) { ?>
									<li class="<?php echo esc_attr( $hidden_info_class ); ?>"><?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_phone_icon', 'fas fa-phone' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_phone, 'Top Bar Phone' ) ); ?></li>
								<?php } ?>
							</ul>
						<?php endif; ?>

						<?php $socials = stm_get_header_socials( 'top_bar_socials_enable' ); ?>
						<!-- Header top bar Socials -->
						<?php if ( ! empty( $socials ) ) : ?>
							<div class="header-top-bar-socs">
								<ul class="clearfix">
									<?php foreach ( $socials as $key => $val ) : ?>
										<li>
											<a href="<?php echo esc_url( $val ); ?>" target="_blank">
												<i class="fab fa-<?php echo esc_attr( $key ); ?>"></i>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?php endif; ?>
