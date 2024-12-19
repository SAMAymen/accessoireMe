<?php
$top_bar_address       = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_address' );
$top_bar_working_hours = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_working_hours' );
$header_address_url    = apply_filters( 'stm_me_get_nuxy_mod', '', 'header_address_url' );
$top_bar_phone         = apply_filters( 'stm_me_get_nuxy_mod', '', 'top_bar_phone' );
$top_bar_menu          = apply_filters( 'stm_me_get_nuxy_mod', false, 'top_bar_menu' );
?>

<div class="top-bar-wrap">
	<div class="container">
		<div class="stm-c-f-top-bar">
			<?php apply_filters( 'stm_get_currency_selector_html', '' ); ?>
			<?php if ( ! empty( $top_bar_address ) ) : ?>
				<div class="stm-top-address-wrap">
					<span id="top-bar-address" class="
					<?php
					if ( ! empty( $header_address_url ) ) {
						echo 'fancy-iframe';}
					?>
					" data-iframe="true" data-src="<?php echo esc_url( $header_address_url ); ?>">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_address_icon', 'fa fa-map-marker' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_address, 'Top Bar Address' ) ); ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $top_bar_working_hours ) ) : ?>
				<div class="stm-top-address-wrap">
					<span id="top-bar-info">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_working_hours_icon', 'fas fa-clock' ) ); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_working_hours, 'Top Bar Working Hours' ) ); ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $top_bar_phone ) ) : ?>
				<div class="stm-top-address-wrap">
					<span id="top-bar-phone">
						<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'top_bar_phone_icon', 'fas fa-phone' ) ); ?> <a href="tel:<?php echo esc_attr( $top_bar_phone ); ?>"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $top_bar_phone, 'Top Bar Phone' ) ); ?></a>
					</span>
				</div>
				<?php
			endif;
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
				<?php get_template_part( 'partials/header/header-ev-dealer/parts/socials' ); ?>
			</div>
			<?php get_template_part( 'partials/top-bar-parts/top-bar-auth' ); ?>
		</div>
	</div>
</div>
