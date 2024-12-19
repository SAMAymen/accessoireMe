<?php
$logo_url     = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$logo_width   = apply_filters( 'stm_me_get_nuxy_mod', '112', 'logo_width' );
$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );

if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	$fixed_header_class .= ' is-listing';
}

$transparent_header = get_post_meta( get_the_id(), 'transparent_header', true );
$top_bar_login      = apply_filters( 'stm_me_get_nuxy_mod', true, 'top_bar_login' );

$show_main_phone_on_mobile = true;

if ( wp_is_mobile() && ! apply_filters( 'stm_me_get_nuxy_mod', false, 'header_main_phone_show_on_mobile' ) ) {
	$show_main_phone_on_mobile = false;
}

$header_style = 'style="background-color:' . apply_filters( 'stm_me_get_nuxy_mod', '#23393d', 'header_bg_color' ) . '";';

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '17' ), 'menu_top_margin' );
?>

<div class="header-rental header-main header-listing <?php echo esc_attr( $fixed_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>" <?php echo $header_style; //phpcs:ignore ?>>

	<div class="container header-inner-content" data-bg="<?php echo apply_filters( 'stm_me_get_nuxy_mod', '#23393d', 'header_bg_color' );//phpcs:ignore ?>">
		<!--Logo-->
		<div class="listing-logo-main" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top );//phpcs:ignore ?>">
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
					<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
				</a>
			<?php endif; ?>
		</div>

		<div class="listing-service-right clearfix" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_icon_top_margin );//phpcs:ignore ?>">

			<div class="listing-right-actions clearfix">
				<?php
				$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', '709-458-2140', 'header_main_phone' );

				if ( $show_main_phone_on_mobile ) :
					?>
					<a href="tel:<?php echo esc_attr( $header_listing_btn_text ); ?>" class="stm_rental_button heading-font">
						<?php echo stm_me_get_wpcfto_icon( 'header_main_phone_icon', 'stm-rental-phone_circle' );//phpcs:ignore ?>
						<span><?php echo esc_html( $header_listing_btn_text ); ?></span>
					</a>
				<?php endif; ?>

				<?php get_template_part( 'partials/header/parts/add_a_car' ); ?>
				<?php get_template_part( 'partials/header/parts/profile' ); ?>
				<?php get_template_part( 'partials/header/parts/cart' ); ?>
				<?php get_template_part( 'partials/header/parts/compare' ); ?>

				<?php if ( ! empty( $top_bar_login ) && $top_bar_login ) : ?>
				<div class="stm-rent-lOffer-account-unit">
					<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>" class="stm-rent-lOffer-account">
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
						<?php echo stm_me_get_wpcfto_icon( 'header_profile_icon', 'stm-service-icon-user' ); //phpcs:ignore ?>
					</a>
					<?php get_template_part( 'partials/user/private/mobile/user' ); ?>
				</div>

				<?php endif; ?>

				<div class="listing-menu-mobile-wrapper">
					<div class="stm-menu-trigger">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>

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


		<div class="stm-opened-menu-listing">
			<!-- <div class="stm-mobile-menu-collapser">
				<i class="fas fa-times stm-close-mobile-menu"></i>
			</div> -->
			<ul class="listing-menu-mobile heading-font clearfix">
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

				get_template_part( 'partials/header/parts/mobile_menu_items' );
				?>
			</ul>
			<?php get_template_part( 'partials/top', 'bar' ); ?>
			<?php get_template_part( 'partials/top-bar-parts/top-bar-auth' ); ?>
		</div>
	</div>
</div>
