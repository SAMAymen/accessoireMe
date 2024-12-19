<?php
$restricted = false;

$user_id = '';
if ( is_user_logged_in() ) {
	$user    = wp_get_current_user();
	$user_id = $user->ID;
}

$restrictions = apply_filters(
	'stm_get_post_limits',
	array(
		'premoderation' => true,
		'posts_allowed' => 0,
		'posts'         => 0,
		'images'        => 0,
		'role'          => 'user',
	),
	$user_id,
	'publish'
);

if ( $restrictions['posts'] < 1 && apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_pay_per_listing' ) ) {
	$restricted = true;
}

if ( get_post_meta( $id, 'pay_per_listing', true ) && apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_pay_per_listing' ) ) {
	$restricted = false;
}

if ( ! empty( $id ) && get_post_status( $id ) === 'publish' ) {
	$restricted = false;
}
?>
<div class="stm-form-checking-user">
	<div class="stm-form-inner">
		<i class="stm-icon-load1"></i>
		<?php
		if ( is_user_logged_in() ) :
			$disabled = 'enabled';
			$user     = wp_get_current_user();
			$user_id  = $user->ID;
			?>
			<div id="stm_user_info">
				<?php
				do_action(
					'stm_listings_load_template',
					'add_car/user_info',
					array(
						'user_login' => '',
						'f_name'     => '',
						'l_name'     => '',
						'_id'        => $id,
						'restricted' => $restricted,
						'user_id'    => $user_id,
					)
				);
				?>
			</div>
			<?php
		else :
			$disabled = 'disabled';
			?>
			<div id="stm_user_info" style="display:none;"></div>
			<?php
		endif;
		?>

		<div class="stm-not-<?php echo esc_attr( $disabled ); ?>">
			<?php do_action( 'stm_listings_load_template', 'add_car/registration', compact( 'stm_title_user', 'stm_text_user', 'link' ) ); ?>
			<div class="stm-add-a-car-login-overlay"></div>
			<div class="stm-add-a-car-login">
				<div class="stm-login-form">
					<form method="post">
						<input type="hidden" name="redirect" value="disable">
						<input type="hidden" name="fetch_plans" value="true">
						<div class="form-group">
							<h4><?php esc_html_e( 'Login or E-mail', 'motors' ); ?></h4>
							<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'motors' ); ?>">
						</div>

						<div class="form-group">
							<h4><?php esc_html_e( 'Password', 'motors' ); ?></h4>
							<input type="password" name="stm_user_password" placeholder="<?php esc_attr_e( 'Enter password', 'motors' ); ?>">
						</div>

						<div class="form-group form-checker">
							<label>
								<input type="checkbox" name="stm_remember_me">
								<span><?php esc_attr_e( 'Remember me', 'motors' ); ?></span>
							</label>
						</div>
						<input type="submit" value="Login">
						<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
						<div class="stm-validation-message"></div>
					</form>
				</div>
			</div>
		</div>
		<?php if ( ! is_user_logged_in() ) : ?>
			<ul class="add-car-btns-wrap">
				<?php
				if ( false === $restricted ) :
					$btnType = ( ! empty( $id ) ) ? 'edit' : 'add';
					$btnType = ( ! empty( get_post_meta( $id, 'pay_per_listing', true ) ) ) ? 'edit-ppl' : $btnType;
					?>
					<li class="btn-add-edit heading-font">
						<button type="submit" class="heading-font <?php echo esc_attr( $disabled ); ?>" data-load="<?php echo esc_attr( $btnType ); ?>"
							<?php
							if ( empty( $id ) ) {
								echo 'data-toggle="tooltip" data-placement="top" title="' . esc_html__( 'Add a Listing using Free or Paid Plan limits', 'motors' ) . '"';
							}
							?>
						>
							<?php if ( ! empty( $id ) ) : ?>
								<i class="stm-service-icon-add_check"></i><?php esc_html_e( 'Update Listing', 'motors' ); ?>
							<?php else : ?>
								<i class="stm-service-icon-add_check"></i><?php esc_html_e( 'Submit listing', 'motors' ); ?>
							<?php endif; ?>
						</button>
						<span class="stm-add-a-car-loader add"><i class="stm-icon-load1"></i></span>
					</li>
				<?php endif; ?>
				<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_pay_per_listing' ) && empty( $id ) ) : ?>
					<li class="btn-ppl">
						<button type="submit" class="heading-font <?php echo esc_attr( $disabled ); ?>" data-load="pay"
							<?php
							if ( empty( $id ) ) {
								echo 'data-toggle="tooltip" data-placement="top" title="' . esc_html__( 'Pay for this Listing', 'motors' ) . '"';
							}
							?>
						>
							<i class="stm-service-icon-payment_listing"></i><?php esc_html_e( 'Pay for Listing', 'motors' ); ?>
						</button>
						<span class="stm-add-a-car-loader pay"><i class="stm-icon-load1"></i></span>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>


	</div>
</div>
<div class="stm-add-a-car-message heading-font"></div>
