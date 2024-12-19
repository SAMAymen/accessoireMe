<?php
	$user_id_get = intval($_GET['user_id']);
	$user_hash_check = esc_attr($_GET['hash_check']);
	$message = '';
	$error = false;

	$user_exist = get_user_by('id', $user_id_get);

	if(!$user_exist) {
		$error = true;
	}

	$user_hash = get_the_author_meta('stm_lost_password_hash',$user_id_get);
	if($user_hash !== $user_hash_check) {
		$error = true;
	}

	if(!empty($_POST['stm_new_password']) and !$error) {
		$new_password = stm_motors_sanitize_text_field($_POST['stm_new_password']);
		wp_set_password($new_password, $user_id_get);
		update_user_meta($user_id_get, 'stm_lost_password_hash', '');
		$message = esc_html__('Password changed', 'motors');
	}

	if(!$error):
?>

	<div class="row">
		<div class="col-md-4">
			<h3><?php esc_html_e('Password Recovery', 'motors'); ?></h3>
			<div class="stm-login-form">

				<form method="post" class="stm_password_recovery" action="">
					<div class="form-group">
						<h4><?php esc_html_e('New password', 'motors'); ?></h4>
						<input type="password" name="stm_new_password" placeholder="<?php esc_attr_e('Enter new password', 'motors') ?>" required/>
						<input type="submit" value="<?php esc_attr_e('Set new password', 'motors'); ?>"/>
						<?php if(!empty($message)): ?>
							<div class="stm-validation-message"><?php echo esc_attr($message); ?></div>
						<?php endif; ?>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php endif; ?>