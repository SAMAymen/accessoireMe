<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

?>

<div class="stm-login-register-form <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $_GET['user_id'] ) && ! empty( $_GET['hash_check'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<?php get_template_part( 'partials/user/private/password', 'recovery' ); ?>
	<?php endif; ?>

	<div class="row">

		<div class="col-md-4">
			<h3><?php esc_html_e( 'Sign In', 'motors-wpbakery-widgets' ); ?></h3>
			<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'site_demo_mode' ) ) : ?>
			<div style="background: #FFF; padding: 15px; margin-bottom: 15px;">
				<span style="width: 100%;">You can use these credentials for demo testing:</span>

				<div style="display: flex; flex-direction: row; margin-top: 10px;">
					<span style="width: 40%;">
						<b>Dealer:</b><br />
						dealer<br />
						dealer
					</span>

					<span style="width: 40%;">
						<b>User:</b><br />
						demo<br />
						demo
					</span>
				</div>
			</div>
			<?php endif; ?>
			<div class="stm-login-form">
				<form method="post">
					<?php do_action( 'stm_before_signin_form' ); ?>
					<div class="form-group">
						<h4><?php esc_html_e( 'Login or E-mail', 'motors-wpbakery-widgets' ); ?></h4>
						<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'motors-wpbakery-widgets' ); ?>"/>
					</div>
					<div class="form-group">
						<h4><?php esc_html_e( 'Password', 'motors-wpbakery-widgets' ); ?></h4>
						<input type="password" name="stm_user_password"  placeholder="<?php esc_attr_e( 'Enter password', 'motors-wpbakery-widgets' ); ?>"/>
					</div>
					<div class="form-group form-checker">
						<label>
							<input type="checkbox" name="stm_remember_me" />
							<span><?php esc_html_e( 'Remember me', 'motors-wpbakery-widgets' ); ?></span>
						</label>
						<div class="stm-forgot-password">
							<a href="#">
								<?php esc_html_e( 'Forgot Password', 'motors-wpbakery-widgets' ); ?>
							</a>
						</div>
					</div>
					<?php
					if ( class_exists( 'SitePress' ) ) :
						?>
						<input type="hidden" name="current_lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ); ?>"/><?php endif; ?>
					<input class="heading-font" type="submit" value="<?php esc_html_e( 'Login', 'motors-wpbakery-widgets' ); ?>"/>
					<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
					<div class="stm-validation-message"></div>
					<?php do_action( 'stm_after_signin_form' ); ?>
				</form>
				<form method="post" class="stm_forgot_password_send">
					<div class="form-group">
						<h4><?php esc_html_e( 'Login or E-mail', 'motors-wpbakery-widgets' ); ?></h4>
						<input type="hidden" name="stm_link_send_to" value="<?php echo esc_attr( get_permalink() ); ?>" readonly/>
						<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'motors-wpbakery-widgets' ); ?>"/>
						<input type="submit" value="<?php esc_attr_e( 'Send password', 'motors-wpbakery-widgets' ); ?>"/>
						<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
						<div class="stm-validation-message"></div>
					</div>
				</form>
			</div>
			<?php if ( boolval( apply_filters( 'is_listing', array() ) ) && defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) ) : ?>
				<div class="stm-social-login-wrap">
					<?php do_action( 'wordpress_social_login' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="col-md-8">
			<h3><?php esc_html_e( 'Sign Up', 'motors-wpbakery-widgets' ); ?></h3>
			<div class="stm-register-form">
				<form id="page-register-form" method="post">
					<?php do_action( 'stm_before_signup_form' ); ?>
					<div class="row form-group">
						<div class="col-md-6">
							<h4><?php esc_html_e( 'First Name', 'motors-wpbakery-widgets' ); ?></h4>
							<input class="user_validated_field" type="text" name="stm_user_first_name" placeholder="<?php esc_attr_e( 'Enter First name', 'motors-wpbakery-widgets' ); ?>"/>
						</div>
						<div class="col-md-6">
							<h4><?php esc_html_e( 'Last Name', 'motors-wpbakery-widgets' ); ?></h4>
							<input class="user_validated_field" type="text" name="stm_user_last_name" placeholder="<?php esc_attr_e( 'Enter Last name', 'motors-wpbakery-widgets' ); ?>"/>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-6">
							<h4><?php esc_html_e( 'Phone number', 'motors-wpbakery-widgets' ); ?></h4>
							<input class="user_validated_field" type="tel" name="stm_user_phone" placeholder="<?php esc_attr_e( 'Enter Phone', 'motors-wpbakery-widgets' ); ?>"/>
							<label for="whatsapp-checker">
								<input type="checkbox" name="stm_whatsapp_number" id="whatsapp-checker" />
								<span><small class="text-muted"><?php esc_html_e( 'I have a WhatsApp account with this number', 'motors-wpbakery-widgets' ); ?></small></span>
							</label>
						</div>
						<div class="col-md-6">
							<h4><?php esc_html_e( 'Email *', 'motors-wpbakery-widgets' ); ?></h4>
							<input class="user_validated_field" type="email" name="stm_user_mail" placeholder="<?php esc_attr_e( 'Enter E-mail', 'motors-wpbakery-widgets' ); ?>"/>
						</div>
					</div>

					<div class="row form-group">
						<div class="col-md-6">
							<h4><?php esc_html_e( 'Login *', 'motors-wpbakery-widgets' ); ?></h4>
							<input class="user_validated_field" type="text" name="stm_nickname" placeholder="<?php esc_attr_e( 'Enter Login', 'motors-wpbakery-widgets' ); ?>"/>
						</div>
						<div class="col-md-6">
							<h4><?php esc_html_e( 'Password *', 'motors-wpbakery-widgets' ); ?></h4>
							<div class="stm-show-password">
								<i class="fas fa-eye-slash"></i>
								<input class="user_validated_field" type="password" name="stm_user_password"  placeholder="<?php esc_attr_e( 'Enter Password', 'motors-wpbakery-widgets' ); ?>"/>
							</div>
						</div>
					</div>
					<?php
					if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_term_service' ) ) :
						$link = apply_filters( 'motors_vl_get_nuxy_mod', '', 'terms_service' );
						?>
						<div class="form-group form-checker">
							<label>
								<input type="checkbox" name="stm_accept_terms" />
								<span>
								<?php esc_html_e( 'I accept the terms of the', 'motors-wpbakery-widgets' ); ?>
									<?php if ( ! empty( $link ) && ! empty( $link['url'] ) ) : ?>
										<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank">
										<?php echo esc_html( $link['title'] ); ?>
									</a>
									<?php endif; ?>
							</span>
							</label>
						</div>
					<?php endif; ?>

					<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'allow_user_register_as_dealer' ) ) : ?>
						<div class="stm-register-as-dealer form-group form-checker">
							<label>
								<input type="checkbox" name="register_as_dealer" value="1"/>
								<span><?php esc_html_e( 'As Dealer', 'motors-wpbakery-widgets' ); ?></span>
							</label>
						</div>
					<?php endif; ?>

					<div class="form-group form-group-submit clearfix">
						<?php
						$recaptcha_enabled    = apply_filters( 'motors_vl_get_nuxy_mod', 0, 'enable_recaptcha' );
						$recaptcha_public_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_public_key' );
						$recaptcha_secret_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_secret_key' );
						if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) :
							?>
							<script type="text/javascript" src="https://www.google.com/recaptcha/api.js <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
							<?php
							if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_term_service' ) ) {
								echo '?onload=onloadRecaptchaCallback';
							}
							?>
							">
							</script> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
							<script>
								function onSubmitPageRegister(token) {
									var form = jQuery('#page-register-form');
									jQuery.ajax({
										type: "POST",
										url: ajaxurl,
										dataType: 'json',
										context: this,
										data: form.serialize() + '&action=stm_custom_register',
										beforeSend: function () {
											form.find('input').removeClass('form-error');
											form.find('.stm-listing-loader').addClass('visible');
											jQuery('.stm-validation-message').empty();
										},
										success: function (data) {
											if (data.user_html) {
												jQuery('#stm_user_info').append(data.user_html);

												jQuery('.stm-not-disabled, .stm-not-enabled').slideUp('fast', function () {
													jQuery('#stm_user_info').slideDown('fast');
												});
												jQuery("html, body").animate({scrollTop: jQuery('.stm-form-checking-user').offset().top}, "slow");

												jQuery('.stm-form-checking-user button[type="submit"]').removeClass('disabled').addClass('enabled');
											}

											if ( data.restricted && data.restricted ) {
												jQuery('.btn-add-edit').remove();
											}

											form.find('.stm-listing-loader').removeClass('visible');
											for (var err in data.errors) {
												form.find('input[name=' + err + ']').addClass('form-error');
											}

											// insert plans select
											if ( data.plans_select && $('#user_plans_select_wrap').length > 0 ) {
												jQuery('#user_plans_select_wrap').html(data.plans_select);
												jQuery( '#user_plans_select_wrap select' ).select2();
											}

											if (data.redirect_url) {
												window.location = data.redirect_url;
											}

											if (data.message) {
												var message = jQuery('<div class="stm-message-ajax-validation heading-font">' + data.message + '</div>').hide();

												form.find('.stm-validation-message').append(message);
												message.slideDown('fast');
											}
										}
									});
								}
							</script>
						<input class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_public_key ); ?>" data-callback='onSubmitPageRegister' type="submit" value="<?php esc_html_e( 'Sign up now!', 'motors-wpbakery-widgets' ); ?>" disabled/>
						<?php else : ?>
						<input class="heading-font" type="submit" value="<?php esc_html_e( 'Sign up now!', 'motors-wpbakery-widgets' ); ?>" disabled/>
						<?php endif; ?>
						<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
					</div>

					<div class="stm-validation-message"></div>

					<?php do_action( 'stm_after_signup_form' ); ?>

				</form>
			</div>
		</div>

	</div>
</div>

<script>
	jQuery(document).ready(function(){
		var $= jQuery;

		$('.stm-show-password .fas').mousedown(function(){
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});

		$(document).mouseup(function(){
			$('.stm-show-password').find('input').attr('type', 'password');
			$('.stm-show-password .fas').addClass('fa-eye-slash');
			$('.stm-show-password .fas').removeClass('fa-eye');
		});

		$("body").on('touchstart', '.stm-show-password .fas', function () {
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});
	});
</script>
