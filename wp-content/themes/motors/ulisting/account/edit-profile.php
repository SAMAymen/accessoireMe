<?php
/**
 * Account edit profile
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/edit-profile.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0.2
 */
use uListing\Classes\StmListingTemplate;
$data = array(
	'user_id' => $user->ID,
	'first_name' => $user->first_name,
	'last_name' => $user->last_name,
	'email' => $user->user_email
);
$user_meta = apply_filters('ulisting_user_meta_data', ['user' => $user, 'data' => []]);
$edit_data = apply_filters('ulisting_profile_edit_data', ['user' => $user, 'data' => []]);
$data = array_merge($data, $edit_data['data']);
$data['user_meta'] = $user_meta['data'];

wp_enqueue_script('stm-profile-edit', ULISTING_URL . '/assets/js/frontend/stm-profile-edit.js', array('vue'), ULISTING_VERSION, true);
wp_add_inline_script('stm-profile-edit', "var stm_user_data = json_parse('".ulisting_convert_content(json_encode($data))."');", 'before');
?>

<?php StmListingTemplate::load_template( 'account/navigation', ['user' => $user], true );?>

<div id="stm-listing-profile-edit" class="panel-custom p-t-30 p-b-30">
	<div class="stm-row">
		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop">
				<label> <?php echo  esc_html__('Avatar', "motors"); ?></label>
				<input type="file"
					ref="avatar"
					v-on:change="handleFileUpload()"
					class="form-control"/>
				<span v-if="errors['avatar']" style="color: red">{{errors['avatar']}}</span>
			</div>
		</div>

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop">
				<label> <?php echo  esc_html__('First name', "motors"); ?></label>
				<input type="text"
					v-model="first_name"
					class="form-control"
					placeholder="<?php esc_html_e('Enter first name', "motors"); ?>"/>
				<span v-if="errors['first_name']" style="color: red">{{errors['first_name']}}</span>
			</div>
		</div>

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop">
				<label> <?php echo  esc_html__('Last name', "motors"); ?></label>
				<input type="text"
					v-model="last_name"
					class="form-control"
					placeholder="<?php esc_html_e('Enter last name', "motors"); ?>"/>
				<span v-if="errors['last_name']" style="color: red">{{errors['last_name']}}</span>
			</div>
		</div>

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop">
				<label> <?php echo  esc_html__('Email', "motors"); ?></label>
				<input type="email"
					v-model="email"
					class="form-control"
					placeholder="<?php esc_html_e('Enter email', "motors"); ?>"/>
				<span v-if="errors['email']" style="color: red">{{errors['email']}}</span>
			</div>
		</div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Company Name', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.company_name.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter company name', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Mobile Phone', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.phone.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter mobile phone', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Office phone', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.cful_office.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter office phone', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Fax', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.cful_fax.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter fax', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Address', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.cful_address.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter address', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'License', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.cful_license.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter license', 'motors' ); ?>"/>
        </div>
        <div class="stm-col-12 stm-col-md-6">
            <label><?php echo esc_html_e( 'Tax number', 'motors' ); ?></label>
            <input type="text"
                   v-model="user_meta.cful_tax_number.value"
                   class="stm-form-control"
                   placeholder="<?php esc_attr_e( 'Enter tax number', 'motors' ); ?>"/>
        </div>

		<?php do_action("ulisting-profile-edit-form", ['user' => $user])?>
		<br>
		<div class="stm-col-12 stm-col-md-12">
			<h4> <?php echo  esc_html__('Social', "motors"); ?></h4>
			<div class="stm-row">
				<?php foreach ($user->get_social() as $k => $v):?>
					<div class="stm-col-12 stm-col-md-6">
						<div class="ulisting-form-gruop">
							<label> <?php echo esc_attr($v['name']); ?></label>
							<input type="email"
								v-model="user_meta.<?php echo esc_attr($k)?>.value"
								class="form-control"
								placeholder="<?php echo esc_attr($v['name']); ?>"/>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		</div>

		<div class="stm-col-12 stm-col-md-12">
			<div>
				<br>
				<button @click="edit" type="button" class="btn btn-primary w-full"><?php echo  esc_html__('Update', "motors"); ?></button>
			</div>
		</div>
	</div>
	<div v-if="loading">Loading...</div>
	<div v-if="message"  v-bind:class="status" >{{message}}</div>
	<hr>
	<div class="stm-row">

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop"><label> <?php echo  esc_html__('Old password', "motors"); ?></label>
			<input v-model="old_password" type="password" placeholder="<?php echo  esc_html__('Old password', "motors"); ?>" class="form-control"></div>
			<span v-if="password_errors['old_password']" style="color: red">{{password_errors['old_password']}}</span>
		</div>

		<div class="stm-col-12 stm-col-md-6"></div>

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop"><label> <?php echo  esc_html__('New password', "motors"); ?></label>
				<input v-model="new_password" type="password" placeholder="<?php echo  esc_html__('New password', "motors"); ?>" class="form-control"></div>
			<span v-if="password_errors['new_password']" style="color: red">{{password_errors['new_password']}}</span>
		</div>

		<div class="stm-col-12 stm-col-md-6">
			<div class="ulisting-form-gruop"><label> <?php echo  esc_html__('Confirmation new password', "motors"); ?></label>
			<input v-model="new_password_confirmation" type="password" placeholder="<?php echo  esc_html__('Confirmation new password', "motors"); ?>" class="form-control"></div>
			<span v-if="password_errors['new_password_confirmation']" style="color: red">{{password_errors['new_password_confirmation']}}</span>
		</div>

		<div class="stm-col-12 stm-col-md-12">
			<div>
				<br>

				<p v-if="password_loading" class="text-center"><?php echo  esc_html__('Load...', "motors"); ?></p>
				<button v-if="!password_loading" @click="update_password" type="button" class="btn btn-primary w-full"><?php echo  esc_html__('Update password', "motors"); ?></button>
				<p v-if="password_message"  v-bind:class="password_status" >{{password_message}}</p>

			</div>
		</div>

	</div>


</div>








