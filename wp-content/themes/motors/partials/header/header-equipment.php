<?php
$logo_url = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );

$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = '';
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	$fixed_header_class .= ' is-listing';
}

$phoneLabel           = apply_filters( 'stm_me_get_nuxy_mod', 'Call Free', 'header_main_phone_label' );
$phone                = apply_filters( 'stm_me_get_nuxy_mod', '+1 212-226-3126', 'header_main_phone' );
$top_bar_phone_mobile = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_phone_mobile' );
$logo_width           = apply_filters( 'stm_me_get_nuxy_mod', '160', 'logo_width' );

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '4' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_top_margin' );
?>

<div class="header-listing <?php echo esc_attr( $fixed_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">

	<div class="container header-inner-content">
		<!--Logo-->
		<div class="listing-logo-main"
			 style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top );//phpcs:ignore ?>">
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
			<div class="listing-right-actions">
				<?php if ( ! empty( $phone ) ) : ?>
				<div class="head-phone-wrap">
					<div class="ph-title heading-font">
						<?php echo stm_me_get_wpcfto_icon( 'header_main_phone_icon', 'fas fa-phone' );//phpcs:ignore ?>
						<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $phoneLabel, 'Header Equipment call free' ) ); ?>
						
					</div>
					<div class="phone heading-font">
						<?php echo esc_html( $phone ); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php
				if ( boolval( apply_filters( 'is_listing', array() ) ) && ! wp_is_mobile() ) :
					get_template_part( 'partials/header/parts/add_a_car' );
					get_template_part( 'partials/header/parts/profile' );
				endif;
				?>

				<?php get_template_part( 'partials/header/parts/cart' ); ?>

				<?php get_template_part( 'partials/header/parts/compare' ); ?>
			</div>

			<ul class="listing-menu clearfix"
				style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_top_margin );//phpcs:ignore ?>">
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
			<div class="mobile-menu-trigger visible-sm visible-xs">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>

		<div class="mobile-menu-holder">
			<ul class="header-menu clearfix">
				<?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
				wp_nav_menu(
					array(
						'menu'           => $location,
						'theme_location' => $location,
						'depth'          => 3,
						'container'      => false,
						'items_wrap'     => '%3$s',
						'fallback_cb'    => false,
					)
				);

				get_template_part( 'partials/header/parts/mobile_menu_items' );
				?>
			</ul>
		</div>
	</div>
</div>
