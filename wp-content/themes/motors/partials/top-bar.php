<?php
$top_bar               = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_enable' );
$top_bar_login         = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );
$top_bar_wpml_switcher = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_wpml_switcher' );

if (
		wp_is_mobile() &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' ) === false &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_wpml_switcher' ) === false &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_currency_enable' ) === false &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' ) === false &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_address_mobile' ) === false &&
		apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_working_hours_mobile' ) === false
	) {
	$top_bar = false;
}

if ( $top_bar ) :

	global $sitepress;

	?>
	<div id="top-bar" class="<?php echo ( wp_is_mobile() ) ? 'top-bar-mobile' : ''; ?>">
		<div class="container">

			<?php
			if ( function_exists( 'icl_get_languages' ) ) :
				$langs = apply_filters( 'wpml_active_languages', 'skip_missing=1&orderby=id&order=asc', null );
			endif;
			?>
			<div class="clearfix top-bar-wrapper">
				<!--LANGS-->
				<?php if ( $top_bar_wpml_switcher ) : ?>
					<?php if ( ! empty( $langs ) ) : ?>
						<?php
						if ( count( $langs ) > 1 || is_author() ) {
							$langs_exist = 'dropdown_toggle';
						} else {
							$langs_exist = 'no_other_langs';
						}

						$current_lang      = '';
						$current_lang_flag = '';
						if ( ! empty( $langs[ ICL_LANGUAGE_CODE ] ) ) {
							$current_lang = $langs[ ICL_LANGUAGE_CODE ];
							if ( ! empty( $current_lang['country_flag_url'] ) ) {
								$current_lang_flag = $current_lang['country_flag_url'];
							}
						}

						$lang_attrs = '';
						if ( count( $langs ) > 1 ) {
							$lang_attrs = 'id="lang_dropdown" data-toggle="dropdown"';
						}
						?>
						<div class="pull-left language-switcher-unit">
							<div class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>" <?php echo esc_attr( $lang_attrs ); ?>>
								<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $current_lang_flag ) ) : ?>
									<img src="<?php echo esc_url( $current_lang_flag ); ?>" alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>"/>
								<?php endif; ?>
								<?php echo esc_html( ICL_LANGUAGE_NAME ); ?>
								<?php if ( count( $langs ) > 1 || is_author() ) : ?>
									<i class="fas fa-angle-down"></i>
								<?php endif; ?>
							</div>
							<?php if ( count( $langs ) > 1 && ! is_author() ) : ?>
								<ul class="dropdown-menu lang_dropdown_menu" role="menu"
									aria-labelledby="lang_dropdown">
									<?php foreach ( $langs as $lang ) : ?>
										<?php if ( ! $lang['active'] ) : ?>
											<li role="presentation">
												<a role="menuitem" tabindex="-1" href="<?php echo esc_url( $lang['url'] ); ?>">
													<?php if ( ( apply_filters( 'stm_is_rental', false ) || apply_filters( 'stm_is_boats', false ) ) && ! empty( $lang['country_flag_url'] ) ) : ?>
														<img src="<?php echo esc_url( $lang['country_flag_url'] ); ?>"
															alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>"/>
													<?php endif; ?>
													<?php echo esc_attr( $lang['native_name'] ); ?>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
								<?php
							elseif ( is_author() ) :
								$user = get_user_by( 'ID', get_current_user_id() );

								?>
								<ul class="dropdown-menu lang_dropdown_menu" role="menu"
									aria-labelledby="lang_dropdown">
									<?php foreach ( icl_get_languages( 'skip_missing=0' ) as $val ) : ?>
										<?php
										$request_uri = str_replace( '/' . wpml_get_current_language() . '/', '/', apply_filters( 'stm_get_global_server_val', 'REQUEST_URI' ) );
										if ( ! $val['active'] ) :
											$main_url = $sitepress->language_url( $val['code'] );

											$url_append = '';
											if ( is_multisite() ) {
												$ms_slug     = get_blog_details()->path;
												$request_uri = str_replace( $ms_slug, '', $request_uri );
											}
											?>
											<li role="presentation">
												<a role="menuitem" tabindex="-1"
													href="<?php echo esc_url( $main_url . $request_uri ); ?>">
													<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $val['country_flag_url'] ) ) : ?>
														<img src="<?php echo esc_url( $val['country_flag_url'] ); ?>"
															alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>"/>
													<?php endif; ?>
													<?php echo esc_attr( $val['native_name'] ); ?>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<!-- Header Top bar Currency Switcher -->
				<?php apply_filters( 'stm_get_currency_selector_html', '' ); ?>

				<!-- Header Top bar Login -->
				<?php get_template_part( 'partials/top-bar-parts/top-bar-auth' ); ?>

				<?php $socials = stm_get_header_socials( 'top_bar_socials_enable' ); ?>
				<!-- Header top bar Socials -->
				<?php if ( ! empty( $socials ) ) : ?>
					<div class="pull-right top-bar-socials">
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
					</div>
					<?php
				endif;

				$top_bar_address        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address' );
				$top_bar_address_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_address_mobile' );

				$top_bar_working_hours        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_working_hours' );
				$top_bar_working_hours_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_working_hours_mobile' );

				$top_bar_phone        = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_phone' );
				$top_bar_phone_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_phone_mobile' );

				$hidden_info_class = '';
				if ( false === $top_bar_phone_mobile ) {
					$hidden_info_class = ' hidden-info';
				}

				$top_bar_menu = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_menu' );

				if ( $top_bar_menu ) :
					?>
					<div class="pull-right top-bar-menu-wrap">
						<div class="top_bar_menu">
							<?php get_template_part( 'partials/top-bar', 'menu' ); ?>
						</div>
					</div>
					<?php
				endif;

				if ( $top_bar_address || $top_bar_working_hours || $top_bar_phone ) :
					?>
					<div class="pull-right xs-pull-left top-bar-info-wrap">
						<ul class="top-bar-info clearfix">
							<?php if ( $top_bar_working_hours ) { ?>
								<li
								<?php
								if ( ! $top_bar_working_hours_mobile ) {
									?>
									class="hidden-info"<?php } ?>><?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_working_hours_icon', 'far fa-fa fa-calendar-check ' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_working_hours, 'Top Bar Working Hours Label' ) ); ?></li>
							<?php } ?>
							<?php if ( $top_bar_address ) { ?>
								<?php $top_bar_address_url = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address_url' ); ?>
								<li
								<?php
								if ( ! $top_bar_address_mobile ) {
									?>
									class="hidden-info"<?php } ?>>
									<span id="top-bar-address" class="fancy-iframe" data-iframe="true"
										data-src="<?php echo esc_attr( $top_bar_address_url ); ?>">
										<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_address_icon', 'fas fa-map-marker' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_address, 'Top Bar Address' ) ); ?>
									</span>
								</li>
							<?php } ?>
							<?php if ( $top_bar_phone ) { ?>
								<li class="stm-phone-number<?php echo esc_attr( $hidden_info_class ); ?>"><?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_phone_icon', 'fas fa-phone' ) ); ?>
									<a href="tel:<?php echo esc_attr( $top_bar_phone ); ?>"> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_phone, 'Top Bar Phone' ) ); ?></a>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

<?php endif; ?>
