<?php
$top_bar_address       = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address' );
$top_bar_working_hours = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_working_hours' );
$top_bar_address_url   = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address_url' );
$top_bar_phone         = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_phone' );
$top_bar_menu          = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_menu' );

$top_bar_address_mobile = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address_mobile' );
$address_hidden_class   = '';
if ( false === $top_bar_address_mobile ) {
	$address_hidden_class = 'hidden-mobile-top-bar';
}

$top_bar_working_hours_mobile = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_working_hours_mobile' );
$working_hours_hidden_class   = '';
if ( false === $top_bar_working_hours_mobile ) {
	$working_hours_hidden_class = 'hidden-mobile-top-bar';
}

$top_bar_phone_mobile = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_phone_mobile' );
$phone_hidden_class   = '';
if ( false === $top_bar_phone_mobile ) {
	$phone_hidden_class = 'hidden-mobile-top-bar';
}

?>

<div class="top-bar-wrap">
	<div class="container">
		<div class="stm-c-f-top-bar">
			<?php
			get_template_part( 'partials/header/header-classified-five/parts/lang-switcher' );
			apply_filters( 'stm_get_currency_selector_html', '' );
			?>
			<?php if ( ! empty( $top_bar_address ) ) : ?>
				<div class="stm-top-address-wrap <?php echo esc_attr( $address_hidden_class ); ?>">
					<span id="top-bar-address" class="
					<?php
					if ( ! empty( $top_bar_address_url ) ) {
						echo 'fancy-iframe';}
					?>
					" data-iframe="true" data-src="<?php echo esc_url( $top_bar_address_url ); ?>">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_address_icon', 'fa fa-map-marker' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_address, 'Top Bar Address' ) ); ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $top_bar_working_hours ) ) : ?>
				<div class="stm-top-address-wrap <?php echo esc_attr( $working_hours_hidden_class ); ?>">
					<span id="top-bar-info">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_working_hours_icon', 'fas fa-clock' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_working_hours, 'Top Bar Working Hours' ) ); ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $top_bar_phone ) ) : ?>
				<div class="stm-top-address-wrap <?php echo esc_attr( $phone_hidden_class ); ?>">
					<span id="top-bar-phone">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_phone_icon', 'fas fa-phone' ) ); ?> <a href="tel:<?php echo esc_attr( $top_bar_phone ); ?>"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_phone, 'Top Bar Phone' ) ); ?></a>
					</span>
				</div>
			<?php endif; ?>

			<?php
			if ( $top_bar_menu ) :
				?>
				<!--Top Bar Menu-->
			<div class="pull-right top-bar-menu-wrap">
				<div class="top_bar_menu">
					<?php get_template_part( 'partials/top-bar', 'menu' ); ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="pull-right">
				<?php get_template_part( 'partials/header/header-classified-five/parts/socials' ); ?>
				<?php
				if ( ! is_user_logged_in() && ( apply_filters( 'stm_is_listing_five', false ) || apply_filters( 'stm_is_listing_six', false ) ) ) {
					get_template_part( 'partials/header/header-classified-five/parts/login-reg-links' );
				}
				?>
			</div>
			<?php if ( ! apply_filters( 'stm_is_listing_five', true ) && ! apply_filters( 'stm_is_listing_six', true ) ) : ?>
				<?php get_template_part( 'partials/top-bar-parts/top-bar-auth' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
