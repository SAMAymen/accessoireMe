<?php
$top_bar_wpml_switcher = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_wpml_switcher' );

if ( ! empty( $top_bar_wpml_switcher ) && $top_bar_wpml_switcher ) :
	if ( function_exists( 'icl_get_languages' ) ) :
		$langs = apply_filters( 'wpml_active_languages', 'skip_missing=1&orderby=id&order=asc', null );
	endif;

	if ( ! empty( $langs ) ) :
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
		?>
		<div class="pull-left language-switcher-unit">
			<div class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>" 
				<?php
				if ( count( $langs ) > 1 || is_author() ) {
					?>
				id="lang_dropdown" data-toggle="dropdown"
				<?php } ?>>
				<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $current_lang_flag ) ) : ?>
					<img src="<?php echo esc_url( $current_lang_flag ); ?>" alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>" />
				<?php endif; ?>
				<?php echo esc_attr( ICL_LANGUAGE_NAME ); ?>
				<?php
				if ( count( $langs ) > 1 || is_author() ) {
					?>
					<i class="fas fa-angle-down"></i>
				<?php } ?>
			</div>
			<?php if ( count( $langs ) > 1 && ! is_author() ) : ?>
				<ul class="dropdown-menu lang_dropdown_menu" role="menu" aria-labelledby="lang_dropdown">
					<?php foreach ( $langs as $lang ) : ?>
						<?php if ( ! $lang['active'] ) : ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo esc_url( $lang['url'] ); ?>">
									<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $lang['country_flag_url'] ) ) : ?>
										<img src="<?php echo esc_url( $lang['country_flag_url'] ); ?>" alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>" />
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
				<ul class="dropdown-menu lang_dropdown_menu" role="menu" aria-labelledby="lang_dropdown">
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
								<a role="menuitem" tabindex="-1" href="<?php echo esc_url( $main_url . $request_uri ); ?>">
									<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $val['country_flag_url'] ) ) : ?>
										<img src="<?php echo esc_url( $val['country_flag_url'] ); ?>" alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>" />
									<?php endif; ?>
									<?php echo esc_html( $val['native_name'] ); ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
