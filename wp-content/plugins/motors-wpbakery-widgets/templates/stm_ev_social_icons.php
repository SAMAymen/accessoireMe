<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class         = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$socials           = apply_filters( 'motors_vl_get_nuxy_mod', '', 'socials_link' );
$icon_unique_class = 'social_icons_' . wp_rand( 1, 99999 );

if ( ! empty( $socials ) ) :
	?>
	<div class="stm_ev_social_icons <?php echo esc_attr( $css_class ); ?>">
		<div class="icons_wrap">
			<?php
			foreach ( $socials as $key => $value ) :
				if ( empty( $value['value'] ) ) {
					continue;
				}
				?>
				<a href="<?php echo esc_url( $value['value'] ); ?>" target="<?php echo ( ! empty( $target_blank ) && 'yes' === $target_blank ) ? '_blank' : '_self'; ?>">
					<i class="fab fa-<?php echo esc_attr( $value['key'] . ' ' . $icon_unique_class ); ?>"></i>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<style>
	<?php if ( ! empty( $icons_color ) ) : ?>
	.stm_ev_social_icons .icons_wrap .<?php echo esc_attr( $icon_unique_class ); ?>::before {
		color: <?php echo esc_attr( $icons_color ); ?>!important;
	}
	<?php endif; ?>
	<?php if ( ! empty( $icons_size ) && is_numeric( $icons_size ) ) : ?>
	.stm_ev_social_icons .icons_wrap .<?php echo esc_attr( $icon_unique_class ); ?> {
		font-size: <?php echo esc_attr( floatval( $icons_size ) ); ?>px!important;
	}
	<?php endif; ?>

	<?php if ( ! empty( $align_icons ) ) : ?>
		.stm_ev_social_icons .icons_wrap {
			text-align: <?php echo esc_attr( $align_icons ); ?>!important;
		}
	<?php endif; ?>

	.stm_ev_social_icons .icons_wrap > a,
	.stm_ev_social_icons .icons_wrap > a:hover,
	.stm_ev_social_icons .icons_wrap > a:active,
	.stm_ev_social_icons .icons_wrap > a:visited {
		color: unset!important;
		text-decoration: none !important;
	}

	.stm_ev_social_icons .icons_wrap > a {
		margin-right: 31px;
	}

	.stm_ev_social_icons .icons_wrap > a:last-child {
		margin-right: 0 !important;
	}
</style>
