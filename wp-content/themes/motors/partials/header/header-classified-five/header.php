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

$header_listing_btn_link = ( boolval( apply_filters( 'is_listing', array( 'listing_five' ) ) ) && function_exists( 'stm_c_f_get_page_url' ) ) ? stm_c_f_get_page_url( 'add_listing' ) : apply_filters( 'stm_me_get_nuxy_mod', '/add-car', 'header_listing_btn_link' );
$header_listing_btn_link = ( boolval( apply_filters( 'is_listing', array( 'listing_six' ) ) ) && function_exists( 'stm_c_six_get_page_url' ) ) ? stm_c_six_get_page_url( 'add_listing' ) : $header_listing_btn_link;
$header_profile          = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );

$phoneLabel = apply_filters( 'stm_me_get_nuxy_mod', 'Call Free', 'header_main_phone_label' );
$phone      = apply_filters( 'stm_me_get_nuxy_mod', '+1 212-226-3126', 'header_main_phone' );

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_top_margin' );
?>
<div id="header" class="<?php echo esc_attr( $transparent_header_class ); ?>"><!--HEADER-->
	<?php
	if ( $top_bar ) {
		get_template_part( 'partials/header/header-classified-five/top-bar' );}
	?>

	<div class="header-main header-main-listing-five <?php echo esc_attr( $fixed_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">
		<div class="container">
			<div class="row header-row" >
				<div class="col-md-2 col-sm-12 col-xs-12">
					<div class="stm-header-left">
						<div class="logo-main" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ); //phpcs:ignore ?>">
							<?php if ( stm_img_exists_by_url( $logo_url ) ) : ?>
								<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<img src="<?php echo esc_url( $logo_url ); ?>"
										style="width: <?php echo apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' );//phpcs:ignore ?>px;"
										title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
										alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
									/>
								</a>
							<?php else : ?>
								<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
									<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
								</a>
							<?php endif; ?>
							<div class="mobile-menu-trigger visible-sm visible-xs">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>
					</div>
					<?php
					if ( wp_is_mobile() ) :
						$compare_page = ( defined( 'ULISTING_VERSION' ) && boolval( apply_filters( 'is_listing', array( 'listing_five', 'listing_six' ) ) ) ) ? \uListing\Classes\StmListingSettings::getPages( 'compare_page' ) : apply_filters( 'motors_vl_get_nuxy_mod', 156, 'compare_page' );

						$show_compare  = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );
						$wishlist_page = ( defined( 'ULISTING_VERSION' ) && boolval( apply_filters( 'is_listing', array( 'listing_five', 'listing_six' ) ) ) ) ? \uListing\Classes\StmListingSettings::getPages( 'wishlist_page' ) : null;
						$account_link  = ( defined( 'ULISTING_VERSION' ) && boolval( apply_filters( 'is_listing', array( 'listing_five' ) ) ) ) ? stm_c_f_get_page_url( 'account_page' ) : apply_filters( 'stm_get_author_link', 'register' );
						$account_link  = ( defined( 'ULISTING_VERSION' ) && boolval( apply_filters( 'is_listing', array( 'listing_six' ) ) ) ) ? stm_c_six_get_page_url( 'account_page' ) : $account_link;
						?>
						<div class="mobile-menu-holder">
							<div class="account-lang-wrap">
								<?php get_template_part( 'partials/header/header-classified-five/parts/lang-switcher' ); ?>
							</div>
							<?php apply_filters( 'stm_get_currency_selector_html', '' ); ?>
							<div class="mobile-menu-wrap">
								<ul class="header-menu clearfix">
									<?php
									$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

									wp_nav_menu(
										array(
											'theme_location' => $location,
											'depth'       => 5,
											'container'   => false,
											'items_wrap'  => '%3$s',
											'fallback_cb' => false,
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

									<?php if ( ! empty( $wishlist_page ) ) : ?>
										<li>
											<a href="<?php echo esc_url( get_the_permalink( $wishlist_page ) ); ?>"><?php esc_html_e( 'Wishlist', 'motors' ); ?></a>
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
					<?php endif; ?>
				</div>
				<div class="col-md-10 hidden-sm hidden-xs">
					<div class="stm-header-right" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_icon_top_margin );//phpcs:ignore ?>">
						<div class="main-menu" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_top_margin );//phpcs:ignore ?>">
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

						<?php if ( apply_filters( 'stm_is_listing_six', false ) ) : ?>
							<div class="head-phone-wrap">
								<div class="ph-title heading-font">
									<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $phoneLabel, 'Header Equipment call free' ) ); ?>
								</div>
								<div class="phone heading-font">
									<?php echo esc_html( $phone ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php get_template_part( 'partials/header/header-classified-five/parts/compare' ); ?>
						<?php get_template_part( 'partials/header/parts/cart' ); ?>

						<?php if ( boolval( apply_filters( 'is_listing', array() ) ) ) : ?>

							<?php
							if ( $header_profile ) {
								get_template_part( 'partials/header/header-classified-five/parts/account' );}
							?>

							<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) ) : ?>
								<div class="stm-c-f-add-btn-wrap">
									<a class="add-listing-btn stm-button heading-font" href="<?php echo esc_url( $header_listing_btn_link ); ?>">
										<?php echo stm_me_get_wpcfto_icon( 'header_listing_btn_icon', 'fas fa-plus' );//phpcs:ignore ?>
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

