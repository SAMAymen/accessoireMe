<?php
$user = apply_filters( 'stm_get_user_custom_fields', '' );

$socials_list = array(
	'facebook' => '',
	'twitter'  => '',
	'linkedin' => '',
	'youtube'  => '',
);

$socials = $user['socials'];

foreach ( $socials_list as $key => $val ) {
	$socials[ $key ] = ( isset( $socials[ $key ] ) ) ? $socials[ $key ] : '';
}

$wsl = get_user_meta( $user['user_id'], 'wsl_current_provider', true );
?>

<div class="stm-user-private-settings-wrapper">

	<h4 class="stm-seller-title"><?php esc_html_e( 'Profile Settings', 'motors' ); ?></h4>

	<div class="stm-my-profile-settings">
		<form action="<?php echo esc_url( add_query_arg( array( 'page' => 'settings' ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>"
			method="post" enctype="multipart/form-data" id="stm_user_settings_edit"
			class="stm_save_user_settings_ajax">

			<!--Image-->
			<?php
			$img_url   = '';
			$img_empty = '';
			if ( ! empty( $user['image'] ) ) {
				$img_url   = $user['image'];
				$img_empty = 'hide-empty';
			} else {
				$img_empty = 'hide-photo';
			}
			?>
			<div class="clearfix stm-image-unit stm-image-avatar <?php echo esc_attr( $img_empty ); ?>">
				<div class="image ">
					<div class="stm_image_upl">
						<i class="fas fa-times"></i>
						<img src="<?php echo esc_url( $img_url ); ?>" class="img-responsive"/>
					</div>
					<script type="text/javascript">
						jQuery('document').ready(function () {
							var $ = jQuery;
							$('.stm-my-profile-settings .stm-image-unit .image .fa-times').on('click', function () {
								$('.stm-image-avatar').removeClass('hide-empty').addClass('hide-photo');
								$('.stm-new-upload-area input[type="file"]').val('');
								$(this).append('<input type="hidden" value="delete" id="stm_remove_img" name="stm_remove_img" />');
							});
						});
					</script>

					<div class="stm-empty-avatar-icon"><i class="fas fa-camera"></i></div>

				</div>
				<div class="stm-upload-new-avatar">
					<div class="heading-font"><?php esc_html_e( 'Upload new avatar', 'motors' ); ?></div>
					<div class="stm-new-upload-area clearfix">
						<a href="#" class="button stm-choose-file heading-font"><?php esc_html_e( 'Choose file', 'motors' ); ?></a>
						<div class="stm-new-file-label"><?php esc_html_e( 'No File Chosen', 'motors' ); ?></div>
						<input type="file" name="stm-avatar"/>

					</div>
					<div class="stm-label"><?php esc_html_e( 'JPEG or PNG minimal 160x160px', 'motors' ); ?></div>
				</div>
			</div>

			<!--Main information-->
			<div class="stm-change-block">
				<div class="title">
					<div class="heading-font"><?php esc_html_e( 'Main Information', 'motors' ); ?></div>
				</div>
				<div class="main-info-settings">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4"><?php esc_html_e( 'First name', 'motors' ); ?></div>
								<input class="form-control" type="text" name="stm_first_name"
									value="<?php echo esc_attr( $user['name'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter First Name', 'motors' ); ?>"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4"><?php esc_html_e( 'Last name', 'motors' ); ?></div>
								<input class="form-control" type="text" name="stm_last_name"
									value="<?php echo esc_attr( $user['last_name'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter Last Name', 'motors' ); ?>"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4"><?php esc_html_e( 'Phone', 'motors' ); ?></div>
								<input class="form-control" type="text" name="stm_phone"
									value="<?php echo esc_attr( $user['phone'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter Phone', 'motors' ); ?>"/>
								<label for="whatsapp-checker">
									<input type="checkbox" name="stm_whatsapp_number" id="whatsapp-checker" <?php echo ( ! empty( $user['stm_whatsapp_number'] ) ? 'checked' : '' ); ?>/>
									<span><?php esc_html_e( 'I have a WhatsApp account with this number', 'motors' ); ?></span>
								</label>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4"><?php esc_html_e( 'Email*', 'motors' ); ?></div>
								<input class="form-control" type="email" name="stm_email"
									value="<?php echo esc_attr( $user['email'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter E-mail', 'motors' ); ?>" required/>
								<label>
									<input type="checkbox"
										name="stm_show_mail" <?php echo ( ! empty( $user['show_mail'] ) ? 'checked="checked"' : '' ); ?>/>
									<span><?php esc_html_e( 'Show Email Address on my Profile', 'motors' ); ?></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Change password-->
			<div class="stm-change-block stm-change-password-form">
				<div class="title">
					<div class="heading-font"><?php esc_html_e( 'Change password', 'motors' ); ?></div>
				</div>
				<div class="stm_change_password">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4"><?php esc_html_e( 'New Password', 'motors' ); ?></div>
								<input class="form-control" type="password" name="stm_new_password"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div
									class="stm-label h4"><?php esc_html_e( 'Re-enter New Password', 'motors' ); ?></div>
								<input class="form-control" type="password" name="stm_new_password_confirm"/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Socials-->
			<div class="stm-change-block stm-socials-form">
				<div class="title">
					<div class="heading-font"><?php esc_html_e( 'Your Social Networks', 'motors' ); ?></div>
				</div>
				<div class="stm_socials_settings">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4">
									<i class="fab fa-facebook-f"></i>
									<?php esc_html_e( 'Facebook', 'motors' ); ?>
								</div>
								<input class="form-control" type="text" name="stm_user_facebook"
									value="<?php echo esc_attr( $socials['facebook'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter your Facebook profile URL', 'motors' ); ?>"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4">
									<i class="fab fa-twitter"></i>
									<?php esc_html_e( 'Twitter', 'motors' ); ?>
								</div>
								<input class="form-control" type="text" name="stm_user_twitter"
									value="<?php echo esc_attr( $socials['twitter'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter your Twitter URL', 'motors' ); ?>"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4">
									<i class="fab fa-linkedin"></i>
									<?php esc_html_e( 'Linked In', 'motors' ); ?>
								</div>
								<input class="form-control" type="text" name="stm_user_linkedin"
									value="<?php echo esc_attr( $socials['linkedin'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter Linkedin Public profile URL', 'motors' ); ?>"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="stm-label h4">
									<i class="fab fa-youtube"></i>
									<?php esc_html_e( 'Youtube', 'motors' ); ?>
								</div>
								<input class="form-control" type="text" name="stm_user_youtube"
									value="<?php echo esc_attr( $socials['youtube'] ); ?>"
									placeholder="<?php esc_attr_e( 'Enter Youtube channel URL', 'motors' ); ?>"/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Confirm Password-->

			<div class="stm-settings-confirm-password">
				<?php if ( empty( $wsl ) ) : ?>
					<div class="heading-font"><?php esc_html_e( 'Enter your Current Password to confirm changes', 'motors' ); ?></div>
					<div class="stm-show-password">
						<i class="fas fa-eye-slash"></i>
						<input class="form-control" type="password" name="stm_confirm_password"
							placeholder="<?php esc_attr_e( 'Current Password', 'motors' ); ?>" required/>
					</div>
				<?php endif; ?>
				<input class="button" type="submit" value="<?php esc_attr_e( 'Save Changes', 'motors' ); ?>"/>
				<span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>

				<h4 class="stm-user-message"></h4>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	var stm_settings_file = {}
	jQuery(document).ready(function () {
		var $ = jQuery;
		$('body').on('change', 'input[name="stm-avatar"]', function () {
			var length = $(this)[0].files.length;

			if (length == 1) {
				$('.stm-new-file-label').text($(this).val());
			} else {
				$('.stm-new-file-label').text('<?php esc_html_e( 'No File Chosen', 'motors' ); ?>');
			}

		});

		$('.stm-show-password .fas').mousedown(function () {
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});

		$(document).mouseup(function () {
			$('.stm-show-password').find('input').attr('type', 'password');
			$('.stm-show-password .fas').addClass('fa-eye-slash');
			$('.stm-show-password .fas').removeClass('fa-eye');
		});

		$("body").on('touchstart', '.stm-show-password .fas', function () {
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});
	})
</script>
