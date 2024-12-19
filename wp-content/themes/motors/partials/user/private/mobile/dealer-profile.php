<?php
$user        = wp_get_current_user();
$user_id     = $user->ID;
$user_fields = apply_filters( 'stm_get_user_custom_fields', $user_id );
?>

<div class="stm-user-private stm-dealer-private">
	<div class="stm-user-private-sidebar">

		<div class="clearfix stm-user-top">

			<div class="stm-user-profile-information">
				<div class="title heading-font"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user->ID, '', '', '' ) ); ?></div>
				<?php if ( ! empty( $user_fields['socials'] ) ) : ?>
					<div class="socials clearfix">
						<?php foreach ( $user_fields['socials'] as $social_key => $social ) : ?>
							<a href="<?php echo esc_url( $social ); ?>">
								<i class="fab fa-<?php echo esc_attr( $social_key ); ?>"></i>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( apply_filters( 'stm_pricing_enabled', false ) ) : ?>
				<?php $stm_user_active_subscriptions = stm_user_active_subscriptions( false, $user_id ); ?>
				<div class="stm-user-current-plan-info heading-font" style="margin-bottom: 30px;">
					<?php if ( ! empty( $stm_user_active_subscriptions ) ) : ?>
						<?php
						$day_left     = false;
						$date_expires = strtotime( $stm_user_active_subscriptions['expires'] );
						$date_now     = time();
						$date_diff    = ( $date_expires - $date_now ) / ( 60 * 60 * 24 );

						if ( $date_diff < 1 ) {
							$day_left = true;
						}
						$wp_date_format = get_option( 'date_format' );
						?>


						<div class="sub-title"><?php esc_html_e( 'Current Plan', 'motors' ); ?></div>
						<div class="stm-plan-name"><?php echo esc_html( $stm_user_active_subscriptions['plan_name'] ); ?></div>
						<div class="sub-title"><?php esc_html_e( 'Subscription renewal', 'motors' ); ?></div>
						<?php if ( $day_left ) : ?>
							<div class="days-left stm-start-countdown"></div>

							<script type="text/javascript">
								jQuery(document).ready(function(){
									var $ = jQuery;
									$(".stm-start-countdown")
										.countdown("<?php echo esc_attr( $stm_user_active_subscriptions['expires'] ); ?>", function (event) {
											$(this).text(
												event.strftime('%H:%M:%S')
											);
										});
								})
							</script>

						<?php else : ?>
							<div class="days-left">
								<?php echo esc_html( gmdate( $wp_date_format, $date_expires ) ); ?>
							</div>
						<?php endif; ?>


						<?php
						$stm_pricing_link = stm_pricing_link();
						if ( ! empty( $stm_pricing_link ) ) :
							?>
							<div class="stm-plan-renew">
								<a href="<?php echo esc_url( $stm_pricing_link ); ?>" class="button stm-dp-in"><?php esc_html_e( 'Get new plan', 'motors' ); ?></a>
							</div>
						<?php endif; ?>
						<?php else : ?>
							<div class="sub-title"><?php esc_html_e( 'Current Plan', 'motors' ); ?></div>
							<div class="stm-plan-name stm-free-plan"><?php esc_html_e( 'Free', 'motors' ); ?></div>
							<?php
							$stm_pricing_link = stm_pricing_link();

							if ( ! empty( $stm_pricing_link ) ) :
								?>
							<div class="stm-plan-renew">
								<a href="<?php echo esc_url( $stm_pricing_link ); ?>" class="button stm-dp-in"><?php esc_html_e( 'Upgrade plan', 'motors' ); ?></a>
							</div>
							<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="stm-user-avatar">
				<?php if ( ! empty( $user_fields['logo'] ) ) : ?>
					<img class="img-responsive img-avatar" src="<?php echo esc_url( $user_fields['logo'] ); ?>"/>
				<?php else : ?>
					<img class="img-responsive img-avatar" src="<?php stm_get_dealer_logo_placeholder(); ?>"/>
				<?php endif; ?>
			</div>

		</div>

		<?php get_template_part( 'partials/user/private/navigation' ); ?>

		<?php if ( ! empty( $user_fields['phone'] ) ) : ?>
			<div class="stm-dealer-phone">
				<i class="stm-service-icon-phone"></i>

				<div class="phone-label heading-font"><?php esc_html_e( 'Dealer Contact Phone', 'motors' ); ?></div>
				<div class="phone"><?php echo esc_attr( $user_fields['phone'] ); ?></div>
			</div>
		<?php endif; ?>

		<div class="stm-dealer-mail">
			<i class="fas fa-envelope-open"></i>

			<div class="mail-label heading-font"><?php esc_html_e( 'Dealer Email', 'motors' ); ?></div>
			<div class="mail"><a href="mailto:<?php echo esc_attr( $user->data->user_email ); ?>">
					<?php echo esc_attr( $user->data->user_email ); ?>
				</a></div>
		</div>

		<div class="show-my-profile">
			<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'myself-view' ) ); ?>" target="_blank"><i
					class="fas fa-external-link-alt"></i><?php esc_html_e( 'Show my Public Profile', 'motors' ); ?></a>
		</div>

		<div class="show-my-profile">
			<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><i
					class="fas fa-sign-out-alt"></i><?php esc_html_e( 'Logout', 'motors' ); ?></a>
		</div>

	</div>
</div>
