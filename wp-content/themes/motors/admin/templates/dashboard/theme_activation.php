<?php
$activated    = STM_Theme_Activation::check_token();
$token        = STM_Theme_Info::get_activation_info();
$code         = isset( $token['code'] ) ? $token['code'] : null;
$confirmation = 'Are you sure to deactivate current site? The theme will be deleted during the deactivation process.';
?>

<div class="stm-admin-theme_activation">
	<div class="stm-startup-admin-content">
		<?php if ( ! $activated ) { ?>
			<div class="stm-admin-box stm-admin-not-activated">
				<div class="__top">
					<div class="__header __text"><i class="stmadmin-icon-activation"></i>This theme is not activated on
						this website
					</div>
					<?php if ( defined( 'STM_INSTRUCTIONS_URL' ) ) : ?>
					<a href="<?php echo esc_url( STM_INSTRUCTIONS_URL ); ?>" target="_blank">How to activate the theme?</a>
					<?php endif; ?>
				</div>
				<div class="__activate_row">
					<a href="<?php echo esc_url( STM_Theme_Info::get_activation_url() ); ?>" class="stm-button">
						<i class="stmadmin-icon-envato"></i>Activate via Envato
					</a>
					<div class="__info">
						<i class="stmadmin-icon-info-filled"></i> You need to activate your theme license to choose
						layout and import demo content.
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="stm-admin-box stm-admin-activated">
				<div class="__top">
					<div class="__header __text"><i class="stmadmin-icon-activation"></i>The theme is activated on the
						website
					</div>
					<?php if ( defined( 'STM_GENERATE_TOKEN' ) ) : ?>
					<a href="<?php echo esc_url( STM_GENERATE_TOKEN ); ?>" target="_blank">How to activate the theme?</a>
					<?php endif; ?>
				</div>
				<div class="__code_row">
					<div class="__code">
						<?php echo esc_attr( $code ); ?><i class="stmadmin-icon-imported-check"></i>
					</div>
					<form method="post" onsubmit="return confirm('<?php echo esc_attr( $confirmation ); ?>')">
						<input type="hidden" name="action" value="stm-deactivate" />
						<button type="submit" class="__deactivate">Deactivate</button>
					</form>
					<?php if ( defined( 'STM_BUY_ANOTHER_LICENSE' ) ) : ?>
					<a class="new-license" href="<?php echo esc_url( STM_BUY_ANOTHER_LICENSE ); ?>" target="_blank">
						Buy new License
					</a>
					<?php endif; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
