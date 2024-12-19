<?php
$top_bar  = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_enable' );
$logo_url = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );

$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

$transparent_header = get_post_meta( get_the_ID(), 'transparent_header', true );

$transparent_header_class = ( $transparent_header ) ? 'transparent-header' : '';

if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$langs = apply_filters( 'wpml_active_languages', null, null );

$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', esc_html__( 'Add your item', 'motors' ), 'header_listing_btn_text' );

$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', '/add-car', 'header_listing_btn_link' );
$header_profile          = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );

$phone_label = apply_filters( 'stm_me_get_nuxy_mod', 'Call Free', 'header_main_phone_label' );
$phone       = apply_filters( 'stm_me_get_nuxy_mod', '+1 212-226-3126', 'header_main_phone' );

// header language switcher
$header_wpml_switcher = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_wpml_switcher' );
if ( ! empty( $header_wpml_switcher ) && $header_wpml_switcher ) {
	if ( function_exists( 'icl_get_languages' ) ) {
		$langs = apply_filters( 'wpml_active_languages', 'skip_missing=1&orderby=id&order=asc', null );
	}
	if ( ! empty( $langs ) ) {
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
	}
}

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_top_margin' )

?>
<div id="header" class="<?php echo esc_attr( $transparent_header_class ); ?>">
	<?php
	if ( $top_bar ) {
		get_template_part( 'partials/header/header-ev-dealer/top-bar' );
	}
	?>

	<div class="header-main header-main-ev_dealer <?php echo esc_attr( $fixed_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">
		<div class="container">
			<div class="row header-row" >
				<div class="col-md-2 col-sm-12 col-xs-12">
					<div class="stm-header-left">
						<div class="logo-main" style="<?php echo esc_attr( apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ) ); ?>">
							<?php if ( stm_img_exists_by_url( $logo_url ) ) : ?>
								<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<img src="<?php echo esc_url( $logo_url ); ?>"
										style="width: <?php echo esc_attr( apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' ) ); ?>px;"
										title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
										alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
									/>
								</a>
							<?php else : ?>
								<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
									<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
								</a>
							<?php endif; ?>

							<?php if ( ! empty( $langs ) && true === $header_wpml_switcher ) : ?>
							<!-- WPML Language Switcher start -->
							<div class="language-switcher-unit">
								<div class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>" 
									<?php
									if ( count( $langs ) > 1 || is_author() ) {
										?>
									id="lang_dropdown" data-toggle="dropdown"
									<?php } ?>>
									<?php if ( apply_filters( 'stm_is_rental', false ) && ! empty( $current_lang_flag ) ) : ?>
										<img src="<?php echo esc_url( $current_lang_flag ); ?>" alt="<?php esc_attr_e( 'Language flag', 'motors' ); ?>" />
									<?php endif; ?>
									<?php echo esc_html( ICL_LANGUAGE_NAME ); ?>
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
														<?php echo esc_html( $lang['native_name'] ); ?>
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
							<!-- WPML Language Switcher end -->
							<?php endif; ?>
							<?php
							if ( wp_is_mobile() ) {
								get_template_part( 'partials/header/parts/cart' );
							}
							?>

							<div class="mobile-menu-trigger">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>
					</div>
					<?php
						$compare_page = apply_filters( 'stm_me_get_nuxy_mod', 156, 'compare_page' );
						$show_compare = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );
						$account_link = apply_filters( 'stm_get_author_link', 'register' );
					?>
					<div class="mobile-menu-holder">
						<?php apply_filters( 'stm_get_currency_selector_html', '' ); ?>
						<div class="mobile-menu-wrap">
							<ul class="header-menu clearfix">
								<?php
								$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

								wp_nav_menu(
									array(
										'theme_location' => $location,
										'depth'          => 5,
										'container'      => false,
										'items_wrap'     => '%3$s',
										'fallback_cb'    => false,
									)
								);
								?>
								<?php if ( boolval( apply_filters( 'is_listing', array() ) ) && $header_profile ) : ?>
									<li>
										<a href="<?php echo esc_url( $account_link ); ?>">
											<?php echo esc_html__( 'Account', 'motors' ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php if ( $show_compare ) : ?>
								<li>
									<a class="lOffer-compare" href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>">
										<?php echo esc_html__( 'Compare', 'motors' ); ?>
									</a>
								</li>
								<?php endif; ?>

								<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && boolval( apply_filters( 'is_listing', array() ) ) ) : ?>
									<li>
										<a class="add-listing-btn stm-button heading-font" href="<?php echo esc_html( $header_listing_btn_link ); ?>">
											<?php echo esc_html( $header_listing_btn_text ); ?>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-10 header-right-wrap">
					<div class="stm-header-right" style="<?php echo esc_attr( apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_icon_top_margin ) ); ?>">
						<div class="main-menu" style="<?php echo esc_attr( apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_top_margin ) ); ?>">
							<ul class="header-menu clearfix">
								<?php
								$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

								wp_nav_menu(
									array(
										'menu'           => $location,
										'theme_location' => $location,
										'depth'          => 5,
										'container'      => false,
										'menu_class'     => 'header-menu clearfix',
										'items_wrap'     => '%3$s',
										'fallback_cb'    => false,
									)
								);
								?>
							</ul>
						</div>

						<?php if ( ! empty( $phone ) ) : ?>
							<div class="head-phone-wrap">
								<div class="ph-title heading-font">
									<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $phone_label, 'Header Equipment call free' ) ); ?>
								</div>
								<div class="phone heading-font">
									<?php echo esc_html( $phone ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php get_template_part( 'partials/header/header-ev-dealer/parts/compare' ); ?>
						<?php get_template_part( 'partials/header/parts/cart' ); ?>

						<?php if ( boolval( apply_filters( 'is_listing', array() ) ) ) : ?>

							<?php
							if ( $header_profile ) {
								get_template_part( 'partials/header/header-ev-dealer/parts/account' );}
							?>

							<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) ) : ?>
								<div class="stm-c-f-add-btn-wrap">
									<a class="add-listing-btn stm-button heading-font" href="<?php echo esc_html( $header_listing_btn_link ); ?>">
										<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_listing_btn_icon', 'fas fa-plus' ) ); ?>
										<?php echo esc_html( $header_listing_btn_text ); ?>
									</a>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div> <!--container-->
	</div> <!--header-main-->
</div><!--HEADER-->
