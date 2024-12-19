<?php
/**
 * Account account panel
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/account-panel.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.3.0
 */
use uListing\Classes\StmUser;
$active = ulisting_page_endpoint();

if(is_user_logged_in()):?>
	<div class="ulisting-account-panel">
		<div class="ulisting-account-panel-avatar">
			<img src="<?php echo esc_url($user->getAvatarUrl()); ?>">
		</div>
		<div class="ulisting-account-panel-main">
			<?php echo esc_html($user->get('first_name'))?> <?php echo esc_html($user->get('last_name'))?>
		</div>
		<ul class="ulisting-account-panel-dropdown-menu">
			<?php foreach (StmUser::get_account_link('account-panel') as $item):?>
				<li>
					<a class="nav-link <?php echo (esc_attr($active) == $item['var'])?'active':null?>" href="<?php echo StmUser::getUrl($item['var'])?>"><?php echo esc_html($item['title'])?></a>
				</li>
			<?php endforeach;?>
			<li>
				<a class="nav-link" href="<?php  echo wp_logout_url(home_url())?>"><?php esc_html_e('Logout', "motors")?></a>
			</li>
		</ul>
	</div>
<?php else:?>
	<div class="ulisting-account-panel">
		<div class="ulisting-account-panel-avatar">
			<i class="fas fa-user"></i>
		</div>
		<div class="ulisting-account-panel-main">
			<a href="<?php echo StmUser::getProfileUrl();?>"><?php _e("Log In ", "motors")?></a> / <a href="<?php echo StmUser::getProfileUrl();?>"><?php _e("Sign Up", "motors")?></a>
		</div>
	</div>
<?php endif;?>




