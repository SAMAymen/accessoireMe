<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

$call_to_action_icon_class       = ( ! empty( $label_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $label_icon_color ) : '';
$call_to_action_icon_right_class = ( ! empty( $right_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $right_icon_color ) : '';
$cta_icon_class                  = ( ! empty( $button_icon_color ) ) ? 'cta_icon_class_' . preg_replace( '/[^a-zA-Z0-9]+/', '', $button_icon_color ) : '';

?>

<style>
	<?php if ( ! empty( $label_icon_color ) ) : ?>
		.<?php echo esc_attr( $call_to_action_icon_class ); ?>::before {
			color: <?php echo esc_attr( $label_icon_color ); ?>;
		}
	<?php endif; ?>

	<?php if ( ! empty( $right_icon_color ) ) : ?>
		.<?php echo esc_attr( $call_to_action_icon_right_class ); ?>::before {
			color: <?php echo esc_attr( $right_icon_color ); ?>;
		}
	<?php endif; ?>

	<?php if ( ! empty( $button_icon_color ) ) : ?>
		.<?php echo esc_attr( $cta_icon_class ); ?>::before {
			color: <?php echo esc_attr( $button_icon_color ); ?>;
		}
	<?php endif; ?>
</style>
<div class="stm-call-to-action heading-font <?php echo esc_attr( $css_class ); ?>" style="background-color:<?php echo esc_attr( $call_to_action_color ); ?>">
	<div class="clearfix">
		<div class="call-to-action-content pull-left">
			<?php if ( ! empty( $call_to_action_label ) ) : ?>
				<div class="content"
				<?php
				if ( ! empty( $call_to_action_text_color_second_part ) ) {
					echo 'style="color: ' . esc_attr( $call_to_action_text_color_second_part ) . '"';
				}
				?>
				>
					<?php if ( ! empty( $call_to_action_icon ) ) : ?>
						<i class="<?php echo esc_attr( $call_to_action_icon ); ?> <?php echo esc_attr( $call_to_action_icon_class ); ?>"></i>
					<?php endif; ?>
					<?php if ( ! empty( $call_to_action_label_2 ) ) : ?>
						<span style="color:<?php echo esc_attr( $call_to_action_text_color ); ?>"><?php echo esc_attr( $call_to_action_label_2 ); ?></span>
					<?php endif; ?>
					<?php echo esc_attr( $call_to_action_label ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="call-to-action-right"
		<?php
		if ( ! empty( $call_to_action_text_color ) ) {
			echo 'style="color: ' . esc_attr( $call_to_action_text_color ) . '"';
		}
		?>
		>

			<?php if ( ( apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_rental', false ) ) && ! empty( $link['url'] ) && ! empty( $link['title'] ) ) : ?>
				<a class="button stm-button stm-button-rental" href="<?php echo esc_url( $link['url'] ); ?>" title="<?php echo esc_attr( $link['title'] ); ?>"
					<?php if ( ! empty( $link['target'] ) ) : ?>
						target="_blank"
					<?php endif; ?>>

					<?php if ( ! empty( $cta_icon ) ) : ?>
						<i class="<?php echo esc_attr( $cta_icon ); ?> <?php echo esc_attr( $cta_icon_class ); ?>"></i>
					<?php endif; ?>

					<span><?php echo esc_html( $link['title'] ); ?></span>
				</a>
			<?php endif; ?>

			<div class="call-to-action-meta"
			<?php
			if ( ! empty( $call_to_action_text_color ) ) {
				echo 'style="color: ' . esc_attr( $call_to_action_text_color ) . '"';
			}
			?>
			>
				<?php if ( ! empty( $call_to_action_label_right ) ) : ?>
					<div class="content" style="color: <?php echo esc_attr( $call_to_action_text_color_right ); ?>">
						<?php if ( ! empty( $call_to_action_icon_right ) ) : ?>
							<i class="<?php echo esc_attr( $call_to_action_icon_right ); ?> <?php echo esc_attr( $call_to_action_icon_right_class ); ?>"></i>
						<?php endif; ?>
						<?php echo esc_attr( $call_to_action_label_right ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>

	</div>
</div>
