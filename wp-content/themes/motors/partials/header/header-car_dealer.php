<?php
$logo_url   = apply_filters( 'stm_me_get_nuxy_img_src', '', 'logo' );
$logo_width = apply_filters( 'stm_me_get_nuxy_mod', '138', 'logo_width' );

$compare_page = apply_filters( 'stm_me_get_nuxy_mod', 156, 'compare_page' );
if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$user = wp_get_current_user();

if ( apply_filters( 'stm_is_listing_six', false ) && function_exists( 'stm_c_six_get_page_url' ) ) {
	$stm_account_page_link = stm_c_six_get_page_url( 'account_page' );
} elseif ( apply_filters( 'stm_is_listing_five', false ) && function_exists( 'stm_c_f_get_page_url' ) ) {
	$stm_account_page_link = stm_c_f_get_page_url( 'account_page' );
} elseif ( apply_filters( 'is_listing', false ) ) {
	$stm_account_page_link = apply_filters( 'stm_get_author_link', 'register' );
} else {
	$stm_account_page_link = false;
}

$header_secondary_phone_1       = apply_filters( 'stm_me_get_nuxy_mod', '878-3971-3223', 'header_secondary_phone_1' );
$header_secondary_phone_2       = apply_filters( 'stm_me_get_nuxy_mod', '878-0910-0770', 'header_secondary_phone_2' );
$header_secondary_phone_label_1 = apply_filters( 'stm_me_get_nuxy_mod', 'Service', 'header_secondary_phone_label_1' );
$header_secondary_phone_label_2 = apply_filters( 'stm_me_get_nuxy_mod', 'Parts', 'header_secondary_phone_label_2' );
$header_main_phone              = apply_filters( 'stm_me_get_nuxy_mod', '878-9671-4455', 'header_main_phone' );
$header_main_phone_label        = apply_filters( 'stm_me_get_nuxy_mod', 'Sales', 'header_main_phone_label' );
$header_address                 = apply_filters( 'stm_me_get_nuxy_mod', '1840 E Garvey Ave South West Covina, CA 91791', 'header_address' );
$header_address_url             = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_address_url' );
$socials                        = stm_get_header_socials( 'header_socials_enable' );
$show_add_btn                   = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' );
$header_profile                 = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );

$logo_margin_top = apply_filters( 'stm_me_get_nuxy_mod', '', 'logo_margin_top' );
?>

<div class="header-main <?php echo ( wp_is_mobile() ) ? 'header-main-mobile' : ''; ?>">
	<div class="container">
		<div class="clearfix">
			<!--Logo-->
			<div class="logo-main <?php echo ( $show_add_btn ) ? 'showing_add_btn' : ''; ?> <?php echo ( $header_profile ) ? 'showing_profile_btn' : ''; ?>" style="<?php echo esc_attr( apply_filters( 'stm_me_wpcfto_parse_spacing', '', $logo_margin_top ) ); ?>">
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

				if ( boolval( apply_filters( 'is_listing', array() ) ) && 'car_dealer' === stm_get_header_layout() ) :
					?>
					<div class="mobile-pull-right">
						<?php
						$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', '/add-car', 'header_listing_btn_link' );
						$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', 'Add your item', 'header_listing_btn_text' );
						?>
						<?php if ( true === $show_add_btn && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) : ?>
							<a href="<?php echo esc_url( $header_listing_btn_link ); ?>" class="listing_add_cart heading-font">
								<div>
									<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_listing_btn_icon', 'stm-lt-icon-add_car' ) ); ?>
								</div>
							</a>
							<?php
						endif;

						if ( $header_profile ) :
							?>
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
						<?php endif; ?>
					</div>
					<?php
				endif;

				if ( ! empty( $header_main_phone ) || ! empty( $header_secondary_phone_1 ) || ! empty( $header_secondary_phone_2 ) || ! empty( $header_address ) ) :
					?>
					<div class="mobile-contacts-trigger visible-sm visible-xs">
						<i class="stm-icon-phone-o"></i>
						<i class="stm-icon-close-times"></i>
					</div>
					<?php
				endif;
				?>

				<div class="mobile-menu-trigger visible-sm visible-xs">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>

			<?php if ( wp_is_mobile() ) : ?>
			<div class="mobile-menu-holder">
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

					if ( boolval( apply_filters( 'is_listing', array() ) ) && 'car_dealer' === stm_get_header_layout() ) {
						$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', 'add-car', 'header_listing_btn_link' );
						$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', 'Add your item', 'header_listing_btn_text' );

						if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) {
							?>
							<li class="stm_add_car_mobile">
								<a href="<?php echo esc_url( $header_listing_btn_link ); ?>"
									class="listing_add_cart heading-font">
									<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_listing_btn_text, 'Add A Car Button label in header' ) ); ?>
								</a>
							</li>
							<?php
						}
					}
					?>
					<?php if ( ! empty( $stm_account_page_link ) ) : ?>
						<li>
							<a href="<?php echo esc_url( $stm_account_page_link ); ?>">
								<?php esc_html_e( 'Account', 'motors' ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php echo wp_kses_post( apply_filters( 'stm_vin_decoder_mobile_menu', '' ) ); ?>
					<?php if ( ! empty( $compare_page ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' ) ) : ?>
						<li class="stm_compare_mobile">
							<a href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>">
								<?php esc_html_e( 'Compare', 'motors' ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( ! empty( $woocommerce_shop_page_id ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'header_cart_show' ) ) : ?>
						<li class="stm_cart_mobile">
							<a href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>">
								<?php esc_html_e( 'Cart', 'motors' ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<div class="top-info-wrap">
				<div class="header-top-info">
					<div class="clearfix">
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

						<?php if ( ! empty( $header_secondary_phone_1 ) || ! empty( $header_secondary_phone_2 ) ) : ?>
							<div class="pull-right">
								<div class="header-secondary-phone header-secondary-phone-single">
									<?php if ( ! empty( $header_secondary_phone_1 ) ) : ?>
										<div class="phone">
											<?php if ( ! empty( $header_secondary_phone_label_1 ) ) : ?>
												<span class="phone-label">
													<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_label_1, 'Phone Label One' ) ); ?>
												</span>
											<?php endif; ?>
											<span class="phone-number heading-font">
												<a href="tel:<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_1, 'Phone Number One' ) ); ?>">
													<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_1, 'Phone Number One' ) ); ?>
												</a>
											</span>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $header_secondary_phone_2 ) ) : ?>
										<div class="phone">
											<?php if ( ! empty( $header_secondary_phone_label_2 ) ) : ?>
												<span class="phone-label">
													<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_label_2, 'Phone Label Two' ) ); ?>
												</span>
											<?php endif; ?>
											<span class="phone-number heading-font">
												<a href="tel:<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_2, 'Phone Number Two' ) ); ?>">
													<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_secondary_phone_2, 'Phone Number One' ) ); ?>
												</a>
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<!--Header main phone-->
						<?php if ( ! empty( $header_main_phone ) ) : ?>
							<div class="pull-right">
								<div class="header-main-phone heading-font">
									<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_main_phone_icon', 'stm-icon-phone' ) ); ?>
									<div class="phone">
										<?php if ( ! empty( $header_main_phone_label ) ) : ?>
											<span class="phone-label">
												<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_main_phone_label, 'Header Phone Label' ) ); ?>
											</span>
										<?php endif; ?>
										<span class="phone-number heading-font">
											<a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $header_main_phone ) ); ?>">
												<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_main_phone, 'Header Phone' ) ); ?>
											</a>
										</span>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<!--Header address-->
						<?php if ( ! empty( $header_address ) ) : ?>
							<div class="pull-right">
								<div class="header-address">
									<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_address_icon', 'stm-icon-pin' ) ); ?>
									<div class="address">
										<?php if ( ! empty( $header_address ) ) : ?>
											<span class="heading-font">
												<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_address, 'Header address' ) ); ?>
											</span>
											<?php if ( ! empty( $header_address_url ) ) : ?>
												<span id="stm-google-map"
													class="fancy-iframe"
													data-iframe="true"
													data-src="<?php echo esc_url( $header_address_url ); ?>"
												>
													<?php esc_html_e( 'View on map', 'motors' ); ?>
												</span>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div> <!--clearfix-->
				</div> <!--header-top-info-->
			</div> <!-- Top info wrap -->
		</div> <!--clearfix-->
	</div> <!--container-->
</div> <!--header-main-->
<?php
get_template_part( 'partials/header/header-nav' );
