<?php
$logo_url     = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$logo_width   = apply_filters( 'stm_me_get_nuxy_mod', '112', 'logo_width' );
$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
$compare_page = apply_filters( 'motors_vl_get_nuxy_mod', 156, 'compare_page' );
$header_bg    = apply_filters( 'stm_me_get_nuxy_img_src', '', 'header_listing_layout_image_bg' );
$rgba         = ( is_front_page() ) ? apply_filters( 'stm_me_get_nuxy_mod', '#293742', 'header_bg_color' ) : apply_filters( 'stm_me_get_nuxy_mod', '#293742', 'header_bg_color' );
$header_style = 'style="background-color: ' . $rgba . ';"';

if ( ! empty( $fixed_header ) && $fixed_header ) {
	$fixed_header_class = 'header-magazine-fixed';
} else {
	$fixed_header_class = 'header-magazine-unfixed';
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	$fixed_header_class .= ' is-listing';
}

$logo_margin_top      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'logo_margin_top' );
$menu_icon_top_margin = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_icon_top_margin' );
$menu_top_margin      = apply_filters( 'stm_me_get_nuxy_mod', array( 'top' => '0' ), 'menu_top_margin' );
?>

<div class="header-magazine <?php echo esc_attr( $fixed_header_class ); ?> <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>" <?php echo $header_style; //phpcs:ignore ?>>

	<div class="magazine-header-bg" 
	<?php
	if ( ! empty( $header_bg ) ) :
		?>
		style="background-image: url('<?php echo esc_url( $header_bg ); ?>')"<?php endif; ?>></div>
	<div class="container header-inner-content">
		<!--Logo-->
		<div class="magazine-logo-main" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ); //phpcs:ignore ?>">
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
				<a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html_e( 'Home', 'motors' ); ?>">
					<h1><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></h1>
				</a>
			<?php endif; ?>
		</div>
		<div class="magazine-service-right clearfix" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_icon_top_margin );//phpcs:ignore ?>">

			<div class="magazine-right-actions clearfix">
				<div class="magazine-menu-mobile-wrapper">
					<div class="stm-menu-trigger">
						<span></span>
						<span></span>
						<span></span>
					</div>
					<div class="stm-opened-menu-magazine">
						<ul class="magazine-menu-mobile header-menu heading-font visible-xs visible-sm clearfix">
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

							<?php
							if ( boolval( apply_filters( 'is_listing', array() ) ) ) :
								get_template_part( 'partials/header/parts/mobile_menu_items' );
							else :
								if ( ! empty( $compare_page ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' ) ) :
									?>
									<li class="stm_compare_mobile"><a href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"><?php esc_html_e( 'Compare', 'motors' ); ?></a></li>
								<?php endif; ?>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<!--Socials-->
				<?php $socials = stm_get_header_socials( 'header_socials_enable' ); ?>

				<!-- Header top bar Socials -->
				<?php if ( ! empty( $socials ) ) : ?>
					<div class="pull-right">
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
				<?php
				if ( boolval( apply_filters( 'is_listing', array() ) ) ) :
					get_template_part( 'partials/header/parts/add_a_car' );
					get_template_part( 'partials/header/parts/profile' );
					get_template_part( 'partials/header/parts/cart' );
					get_template_part( 'partials/header/parts/compare' );
				else :
					get_template_part( 'partials/header/parts/cart' );
					if ( ! empty( $compare_page ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' ) ) :
						?>
						<div class="pull-right">
							<a class="lOffer-compare heading-font"
								href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"
								title="<?php esc_attr_e( 'View compared items', 'motors' ); ?>">
								<?php echo esc_html__( 'Compare', 'motors' ); ?>
								<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_compare_icon', 'stm-icon-speedometr2' ) ); ?>
								<span class="list-badge"><span class="stm-current-cars-in-compare">
								<?php
									echo esc_html( count( apply_filters( 'stm_get_compared_items', array() ) ) );
								?>
								</span></span>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<ul class="magazine-menu header-menu clearfix" style="<?php echo apply_filters( 'stm_me_wpcfto_parse_spacing', '', $menu_top_margin ); //phpcs:ignore ?>">
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
