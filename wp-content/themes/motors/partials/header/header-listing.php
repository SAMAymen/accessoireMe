<?php
$logo_url             = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$fixed_header         = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
$transparent_header   = get_post_meta( get_the_ID(), 'transparent_header', '' );
$compare_page         = apply_filters( 'motors_vl_get_nuxy_mod', 156, 'compare_page' );
$show_compare         = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );
$header_bg            = apply_filters( 'stm_me_get_nuxy_img_src', '', 'header_listing_layout_image_bg' );
$logo_width           = apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' );
$compared_items_count = count( apply_filters( 'stm_get_compared_items', array() ) );

if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

if ( empty( $transparent_header ) ) {
	$transparent_header_class = 'listing-nontransparent-header';
} else {
	$transparent_header_class = '';
}

if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '17' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '17' ), 'menu_top_margin' );

?>

<div class="header-listing <?php echo esc_attr( $fixed_header_class . ' ' . $transparent_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">

	<div class="listing-header-bg"
	<?php
	if ( ! empty( $header_bg ) ) :
		?>
		style="background-image: url('<?php echo esc_url( $header_bg ); ?>')"<?php endif; ?>></div>
	<div class="container header-inner-content">
		<!--Logo-->
		<div class="listing-logo-main" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ); //phpcs:ignore ?>">
			<?php if ( stm_img_exists_by_url( $logo_url ) ) : ?>
				<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img
						src="<?php echo esc_url( $logo_url ); ?>"
						style="width: <?php echo esc_attr( $logo_width ); ?>px;"
						title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
						alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
					/>
				</a>
			<?php else : ?>
				<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
					<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
				</a>
			<?php endif; ?>
		</div>
		<div class="listing-service-right clearfix" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_icon_top_margin );//phpcs:ignore ?>">
			<div class="listing-right-actions clearfix">
				<?php if ( boolval( apply_filters( 'is_listing', array() ) ) ) : ?>
					<?php
						$header_profile = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );

						$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', '/add-car', 'header_listing_btn_link' );
						$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', 'Add your item', 'header_listing_btn_text' );
					?>
					<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) : ?>
						<a href="<?php echo esc_url( $header_listing_btn_link ); ?>" class="listing_add_cart heading-font">
							<div>
								<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_listing_btn_icon', ( ! boolval( apply_filters( 'is_listing', array( 'listing_two', 'listing_three' ) ) ) ) ? 'stm-service-icon-listing_car_plus' : 'stm-lt-icon-add_car' ) ); ?>
								<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_listing_btn_text, 'Add A Car Button label in header' ) ); ?>
							</div>
						</a>
					<?php endif; ?>
					<?php if ( $header_profile ) : ?>
						<div class="pull-right">
							<div class="lOffer-account-unit">
								<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>" class="lOffer-account">
									<?php
									if ( is_user_logged_in() ) :
										$user_fields = apply_filters( 'stm_get_user_custom_fields', '' );
										if ( ! empty( $user_fields['image'] ) ) :
											?>
											<div class="stm-dropdown-user-small-avatar">
												<img src="<?php echo esc_url( $user_fields['image'] ); ?>" class="im-responsive"/>
											</div>
										<?php endif; ?>
									<?php endif; ?>
									<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_profile_icon', 'stm-service-icon-user' ) ); ?>
								</a>
								<?php get_template_part( 'partials/user/user', 'dropdown' ); ?>
								<?php get_template_part( 'partials/user/private/mobile/user' ); ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( ! empty( $compare_page ) && $show_compare ) : ?>
					<div class="pull-right">
						<a class="lOffer-compare"
							href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"
							title="<?php esc_attr_e( 'View compared items', 'motors' ); ?>"
							<?php
							if ( ! boolval( apply_filters( 'is_listing', array() ) ) ) {
								echo 'style="margin-right: 0 !important;"';}
							?>
						>
							<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_compare_icon', 'stm-service-icon-listing-compare', 'list-icon' ) ); ?>
							<span class="list-badge">
								<span class="stm-current-cars-in-compare" data-contains="compare-count">
									<?php echo esc_html( $compared_items_count ); ?>
								</span>
							</span>
						</a>
					</div>
				<?php endif; ?>
				<?php get_template_part( 'partials/header/parts/cart' ); ?>
				<?php if ( wp_is_mobile() ) : ?>
					<div class="listing-menu-mobile-wrapper">
						<div class="stm-menu-trigger">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div class="stm-opened-menu-listing">
							<ul class="listing-menu-mobile heading-font visible-xs visible-sm clearfix">
								<?php
								$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
								wp_nav_menu(
									array(
										'menu'           => $location,
										'theme_location' => $location,
										'depth'          => 3,
										'container'      => false,
										'menu_class'     => 'service-header-menu clearfix',
										'items_wrap'     => '%3$s',
										'fallback_cb'    => false,
									)
								);
								?>

								<?php if ( ! empty( $compare_page ) && $show_compare ) : ?>
									<li class="stm_compare_mobile"><a href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"><?php esc_html_e( 'Compare', 'motors' ); ?></a></li>
								<?php endif; ?>
								<?php if ( ! empty( $woocommerce_shop_page_id ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_cart_show' ) ) : ?>
									<li class="stm_cart_mobile"><a
												href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>"><?php esc_html_e( 'Cart', 'motors' ); ?></a>
									</li>
								<?php endif; ?>
							</ul>
							<?php get_template_part( 'partials/top', 'bar' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<ul class="listing-menu clearfix" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_top_margin );//phpcs:ignore ?>">
				<?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

				wp_nav_menu(
					array(
						'menu'           => $location,
						'theme_location' => $location,
						'depth'          => 3,
						'container'      => false,
						'menu_class'     => 'service-header-menu clearfix',
						'items_wrap'     => '%3$s',
						'fallback_cb'    => false,
					)
				);
				?>
			</ul>
		</div>
	</div>
</div>
