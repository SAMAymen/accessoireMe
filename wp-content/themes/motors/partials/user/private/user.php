<?php
motors_include_once_scripts_styles( array( 'stm-theme-user-sidebar', 'addtoany-core' ) );

$user        = wp_get_current_user();
$user_fields = apply_filters( 'stm_get_user_custom_fields', $user->ID );
$stm_link    = add_query_arg( array( 'page' => 'settings' ), apply_filters( 'stm_get_author_link', '' ) );
$avatar      = $user_fields['image'];

if ( apply_filters( 'stm_is_listing_five', false ) ) {
	$stm_link = add_query_arg( array( 'page' => 'settings' ), apply_filters( 'stm_get_author_link', '' ) );
}

if ( ! empty( get_user_meta( $user->ID, 'wsl_current_user_image', true ) ) ) {
	$avatar = get_user_meta( $user->ID, 'wsl_current_user_image', true );
}
?>

<div class="stm-user-private">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-12 stm-sticky-user-sidebar">
				<div class="stm-user-private-sidebar">
					<div class="clearfix stm-user-top">
						<div class="stm-user-avatar">
							<a href="<?php echo esc_url( $stm_link ); ?>"
									class="stm-image-avatar image <?php echo esc_attr( empty( $user_fields['image'] ) ? 'hide-photo' : 'hide-empty' ); ?>">
								<img class="img-responsive img-avatar" src="<?php echo esc_url( $avatar ); ?>"/>
								<div class="stm-empty-avatar-icon"><i class="fas fa-camera"></i></div>
							</a>
						</div>
						<div class="stm-user-profile-information">
							<a href="<?php echo esc_url( $stm_link ); ?>"
									class="title heading-font"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user->ID, '', '', '' ) ); ?></a>
							<div class="title-sub"><?php esc_html_e( 'Private Seller', 'motors' ); ?></div>
							<?php if ( ! empty( $user_fields['socials'] ) ) : ?>
								<div class="socials clearfix">
									<?php foreach ( $user_fields['socials'] as $social_key => $social ) : ?>
										<a href="<?php echo esc_url( $social ); ?>">
											<?php
											if ( 'facebook' === $social_key ) {
												$social_key = 'facebook-f';
											}
											?>
											<i class="fab fa-<?php echo esc_attr( $social_key ); ?>"></i>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

					</div>

					<?php if ( ! apply_filters( 'stm_pricing_enabled', false ) ) : ?>
						<div class="stm-became-dealer">
							<a href="<?php echo esc_url( add_query_arg( array( 'become_dealer' => 1 ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>"
									class="button stm-dp-in"><?php esc_html_e( 'Become a dealer', 'motors' ); ?></a>
						</div>
					<?php endif; ?>

					<?php get_template_part( 'partials/user/private/navigation' ); ?>

					<?php if ( ! empty( $user_fields['phone'] ) ) : ?>
						<div class="stm-dealer-phone">
							<i class="stm-service-icon-phone"></i>
							<div class="phone-label heading-font"><?php esc_html_e( 'Seller Contact Phone', 'motors' ); ?></div>
							<div class="phone"><?php echo esc_attr( $user_fields['phone'] ); ?></div>
						</div>
					<?php endif; ?>

					<div class="stm-dealer-mail">
						<i class="fas fa-envelope-open"></i>
						<div class="mail-label heading-font"><?php esc_html_e( 'Seller Email', 'motors' ); ?></div>
						<div class="mail"><a href="mailto:<?php echo esc_attr( $user->data->user_email ); ?>">
								<?php echo esc_attr( $user->data->user_email ); ?>
							</a></div>
					</div>


					<?php if ( apply_filters( 'stm_pricing_enabled', false ) ) : ?>
						<?php $stm_user_active_subscriptions = stm_user_active_subscriptions( false, $user->data->ID ); ?>
						<div class="stm-user-current-plan-info heading-font">
							<?php if ( ! empty( $stm_user_active_subscriptions ) ) : ?>
								<div class="sub-title"><?php esc_html_e( 'Current Plan', 'motors' ); ?></div>
								<div class="stm-plan-name"><?php echo wp_kses_post( $stm_user_active_subscriptions['plan_name'] ); ?></div>
								<?php
								$day_left     = false;
								$date_expires = strtotime( $stm_user_active_subscriptions['expires'] );

								if ( $date_expires ) {
									$date_now  = time();
									$date_diff = ( $date_expires - $date_now ) / ( 60 * 60 * 24 );

									if ( $date_diff < 1 ) {
										$day_left = true;
									}
								}
								$wp_date_format = get_option( 'date_format' );
								?>
								<?php if ( $day_left && $date_expires ) : ?>
								<div class="sub-title"><?php esc_html_e( 'Subscription renewal', 'motors' ); ?></div>
								<div class="days-left stm-start-countdown"></div>
								<script type="text/javascript">
									jQuery(document).ready(function () {
										var $ = jQuery;
										$(".stm-start-countdown")
											.countdown("<?php echo esc_js( $stm_user_active_subscriptions['expires'] ); ?>", function (event) {
												$(this).text(
													event.strftime('%H:%M:%S')
												);
											});
									})
								</script>
							<?php elseif ( $date_expires ) : ?>
								<div class="sub-title"><?php esc_html_e( 'Subscription renewal', 'motors' ); ?></div>
								<div class="days-left"><?php echo esc_html( gmdate( $wp_date_format, $date_expires ) ); ?></div>
							<?php endif; ?>
								<?php $stm_pricing_link = stm_pricing_link(); ?>
								<?php if ( ! empty( $stm_pricing_link ) ) : ?>
								<div class="stm-plan-renew">
									<a href="<?php echo esc_url( $stm_pricing_link ); ?>"
											class="button stm-dp-in"><?php esc_html_e( 'Get new plan', 'motors' ); ?></a>
								</div>
							<?php endif; ?>
							<?php else : ?>
								<div class="sub-title"><?php esc_html_e( 'Current Plan', 'motors' ); ?></div>
								<div class="stm-plan-name stm-free-plan"><?php esc_html_e( 'Free', 'motors' ); ?></div>
								<?php $stm_pricing_link = stm_pricing_link(); ?>
								<?php if ( ! empty( $stm_pricing_link ) ) : ?>
								<div class="stm-plan-renew">
									<a href="<?php echo esc_url( $stm_pricing_link ); ?>"
											class="button stm-dp-in"><?php esc_html_e( 'Upgrade plan', 'motors' ); ?></a>
								</div>
							<?php endif; ?>
							<?php endif; ?>
						</div>
					<?php else : ?>
						<div class="show-my-profile">
							<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'myself-view' ) ); ?>" target="_blank">
								<i class="fas fa-external-link-alt"></i>
								<?php esc_html_e( 'Show my Public Profile', 'motors' ); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</div>

			<div class="col-md-9 col-sm-12">

				<?php get_template_part( 'partials/user/private/main' ); ?>

			</div>

		</div>
	</div>
</div>
