<?php
$logo_url = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );

$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	$fixed_header_class .= ' is-listing';
}

$show_main_phone_on_mobile = true;

if ( wp_is_mobile() && ! apply_filters( 'stm_me_get_nuxy_mod', false, 'header_main_phone_show_on_mobile' ) ) {
	$show_main_phone_on_mobile = false;
}

$header_main_phone = apply_filters( 'stm_me_get_nuxy_mod', '878-9671-4455', 'header_main_phone' );

$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', '/add-a-car', 'header_listing_btn_link' );
$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', esc_html__( 'Add your item', 'motors' ), 'header_listing_btn_text' );
$logo_width              = apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' );
$socials                 = stm_get_header_socials( 'header_socials_enable' );
$logo_margin_top         = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
?>
<div class="stm_motorcycle-header <?php echo esc_attr( $fixed_header_class ); ?>  <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">
	<?php if ( $show_main_phone_on_mobile ) : ?>
	<div class="stm_mc-main header-main">
		<div class="container clearfix">
			<div class="left">
				<div class="clearfix">
					<!--Socials-->
					<?php
					if ( ! empty( $socials ) ) :
						?>
						<div class="pull-left">
							<div class="header-main-socs">
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
					<?php endif; ?>
				</div>
			</div>
			<div class="right">
					<?php if ( stm_img_exists_by_url( $logo_url ) ) : ?>
						<a class="bloglogo hidden-xs" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top );//phpcs:ignore ?>">
							<img
								src="<?php echo esc_url( $logo_url ); ?>"
								style="width: <?php echo esc_attr( $logo_width ); ?>px;"
								title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
								alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
							/>
						</a>
					<?php else : ?>
						<a class="blogname hidden-xs" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
							<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
						</a>
					<?php endif; ?>
				<div class="right-right">
					<div class="clearfix">
						<?php if ( ! empty( $header_main_phone ) && $show_main_phone_on_mobile ) : ?>
							<div class="pull-right">
								<div class="header-main-phone heading-font">
									<div class="phone">
										<span class="phone-number heading-font"><a href="tel:<?php echo preg_replace( '/\s/', '', $header_main_phone ); ?>"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_main_phone, 'Phone Number' ) );// phpcs:ignore ?></a></span>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php get_template_part( 'partials/header/parts/profile' ); ?>

						<?php get_template_part( 'partials/header/parts/cart' ); ?>

						<?php get_template_part( 'partials/header/parts/compare' ); ?>

					</div>
				</div>

			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="stm_mc-nav">
		<div class="mobile-logo-wrap">
			<?php if ( empty( $logo_url ) ) : ?>
				<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'motors' ); ?>">
					<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
				</a>
			<?php else : ?>
				<a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img
						src="<?php echo esc_url( $logo_url ); ?>"
						style="width: <?php echo esc_attr( apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' ) ); ?>px;"
						title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
						alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
						/>
				</a>
			<?php endif; ?>
		</div>
		<?php if ( wp_is_mobile() && boolval( apply_filters( 'is_listing', array() ) ) ) : ?>
			<div class="mobile-pull-right">
				<?php get_template_part( 'partials/header/parts/add_a_car' ); ?>
				<?php get_template_part( 'partials/header/parts/profile' ); ?>
			</div>
		<?php endif; ?>
		<div class="mobile-menu-trigger">
			<span></span>
			<span></span>
			<span></span>
		</div>
		<div class="main-menu hidden-xs">
			<div class="container">
				<div class="inner">
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

						if ( boolval( apply_filters( 'is_listing', array() ) ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) {
							?>
							<li>
								<a href="<?php echo esc_url( $header_listing_btn_link ); ?>">
									<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_listing_btn_text, 'Listing Button Text' ) ); ?>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div>
			<div class="main-menu mobile-menu-holder">
				<div class="container">
					<div class="inner">
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

							get_template_part( 'partials/header/parts/mobile_menu_items' );
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
