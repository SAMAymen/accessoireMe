<?php
$phone_label_text          = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_phone_label' );
$pone_number               = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_phone' );
$phone_description         = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_phone_description' );
$working_hours_label       = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_working_hours_label' );
$working_hours             = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_working_hours' );
$working_hours_description = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_working_hours_description' );
$logo_url                  = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$logo_width                = apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' );
$fixed_header              = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky_main' );
$rgba_desktop              = apply_filters( 'stm_me_get_nuxy_mod', '#293742', 'header_bg_color' );
$rgba_mob                  = apply_filters( 'stm_me_get_nuxy_mod', '#293742', 'header_mobile_bg_color' );
$header_style_mob          = 'style="background-color: ' . $rgba_mob . ';"';
$rgba_menu_bg              = apply_filters( 'stm_me_get_nuxy_mod', '#293742', 'hma_background_color' );
$header_style_menu_bbg     = 'style="background-color: ' . $rgba_menu_bg . ';"';
$top_bar_login             = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_login' );
$header_padding_conf       = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'header_padding' );
$paddings                  = '';
$header_show_cart          = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_cart' );
$logo_margin_top           = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
$shopping_cart_title       = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_cart_title' );
if ( ! empty( $header_padding_conf ) ) {
	foreach ( $header_padding_conf as $property => $value ) {
		if ( ! empty( $value ) && 'unit' !== $property ) {
			$paddings .= "$value{$header_padding_conf['unit']} ";
		}
		$header_padding = "padding: $paddings";
	}
}

$header_style_desktop = 'style="background-color: ' . $rgba_desktop . ';' . ( ! empty( $header_padding ) ? ' ' . $header_padding . ';' : '' ) . '"';

if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-auto-parts-fixed';
} else {
	$fixed_header_class = 'header-auto-parts-unfixed';
}

$cart_total = 0;

if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();

	$items = 0;
	if ( ! empty( WC()->cart->cart_contents_count ) ) {
		$items = WC()->cart->cart_contents_count;
	}
	$cart_total = WC()->cart->get_cart_total();

}

$show_cart = stm_me_get_nuxy_mod( 'header_show_cart', false );
?>

<div id="header">
	<div class="header-wrapper">
		<div class="container">
			<div class="header-inner" <?php echo wp_kses_post( $header_style_desktop ); ?> >
				<div class="header-logo" style="<?php echo esc_attr( apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ) ); ?>">
					<?php
					if ( stm_img_exists_by_url( $logo_url ) ) :
						?>
						<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img src="<?php echo esc_url( $logo_url ); ?>"
									style="width: <?php echo esc_attr( $logo_width ); ?>px;"
									title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
									alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
							/>
						</a>
						<?php
					else :
						?>
						<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>"
								title="<?php esc_html_e( 'Home', 'motors' ); ?>">
							<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
						</a>
						<?php
					endif;
					?>
				</div>
				<div class="header-info">
					<div class="header-info-contact">
						<div class="info-icon">
							<?php echo stm_me_get_wpcfto_icon( 'header_phone_icon', 'stm-icon-phone-o' ); //phpcs:ignore ?>
						</div>
						<div class="info-contacts-wrap">
							<div class="info-contacts-title"><?php echo wp_kses_post( $phone_label_text ); ?></div>
							<div class="info-contacts-phone"><?php echo wp_kses_post( $pone_number ); ?></div>
							<div class="info-contacts-descr"><?php echo wp_kses_post( $phone_description ); ?></div>
						</div>
					</div>
					<div class="header-info-hours">
						<div class="info-icon">
							<?php echo stm_me_get_wpcfto_icon( 'header_working_hours_icon', 'icon-ap-time' );//phpcs:ignore ?>
						</div>
						<div class="info-hours-wrap">
							<div class="info-hours-title"><?php echo wp_kses_post( $working_hours_label ); ?></div>
							<div class="info-hours-info"><?php echo wp_kses_post( $working_hours ); ?></div>
							<div class="info-hours-descr"><?php echo wp_kses_post( $working_hours_description ); ?></div>
						</div>
					</div>
					<?php if ( true === $header_show_cart ) : ?>
					<div class="header-cart">
						<a href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>" title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>">
							<?php echo stm_me_get_wpcfto_icon( 'cart-icon', 'icon-ap-cart'  ); //phpcs:ignore ?>
							<div class="cart-info">
								<span class="cart-description"><?php echo wp_kses_post( $shopping_cart_title ); ?></span>
								<span class="list-badge">
								<span class="stm-current-items-in-cart">
									<?php echo esc_html( $items ) . ' ' . esc_html__( 'items', 'motors' ); ?>
								</span>
							</span>
								<span class="cart-total cart-total-price"><?php echo wp_kses_post( $cart_total ); ?></span>
							</div>
						</a>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="header-menu-wrapper <?php echo wp_kses_post( $fixed_header_class ); ?>" <?php echo wp_kses_post( $header_style_menu_bbg ); ?>>
				<ul class="header-menu">
					<?php
					$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
					wp_nav_menu(
						array(
							'menu'           => $location,
							'theme_location' => $location,
							'depth'          => 3,
							'container'      => false,
							'menu_class'     => 'auto-parts-header-menu clearfix',
							'items_wrap'     => '%3$s',
							'fallback_cb'    => false,
						)
					);
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="mobile-header" <?php echo wp_kses_post( $header_style_mob ); ?> >
		<div class="mobile-header-main">
			<div class="mobile-header-wrapper">
				<div class="mobile-header-logo">
					<?php
					if ( stm_img_exists_by_url( $logo_url ) ) :
						?>
						<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img src="<?php echo esc_url( $logo_url ); ?>"
									style="width: <?php echo esc_attr( $logo_width ); ?>px;"
									title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
									alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
							/>
						</a>
						<?php
					else :
						?>
						<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>"
								title="<?php esc_html_e( 'Home', 'motors' ); ?>">
							<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
						</a>
						<?php
					endif;
					?>
				</div>
				<div class="mobile-header-menu-actions">
					<div class="mobile-header-cart">
						<a href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>" title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>">
							<i class="icon-ap-cart"></i>
							<div class="cart-info">
							<span class="list-badge">
								<span class="stm-current-items-in-cart">
									<?php echo esc_html( $items ); ?>
								</span>
							</span>
							</div>
						</a>
					</div>
					<div class="mobile-header-profile">
						<?php if ( ! empty( $top_bar_login ) ) : ?>
							<div class="top-bar-login-register">
								<div class="header-login-url">
									<?php if ( is_user_logged_in() ) : ?>
										<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="no_deco"><i class="fa fa-user stm_mgr_8"></i></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="mobile-header-hamburger">
						<div class="bar"></div>
						<div class="bar"></div>
						<div class="bar"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="mobile-menu-wrapper">
			<ul class="mobile-menu" <?php echo wp_kses_post( $header_style_mob ); ?>>
				<?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
				wp_nav_menu(
					array(
						'menu'           => $location,
						'theme_location' => $location,
						'depth'          => 3,
						'container'      => false,
						'menu_class'     => 'auto-parts-header-menu clearfix',
						'items_wrap'     => '%3$s',
						'fallback_cb'    => false,
					)
				);
				?>
			</ul>
		</div>
	</div>
</div>
