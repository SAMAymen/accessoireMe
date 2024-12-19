<?php
/**
 * Account
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/account.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */
use uListing\Classes\StmListingTemplate;
use uListing\Classes\StmUser;

$user = null;
$view = 'account/profile';

if(is_user_logged_in())
	$user = new StmUser(get_current_user_id());

if(isset($_GET['action']) AND $_GET['action'] == 'profile_edit')
	$view = 'account/profile_edit';
?>
<?php echo StmListingTemplate::load_template( 'listing-list/breadcrumbs'); ?>
<?php if(!is_user_logged_in()):?>
	<div class="stm-row">
		<div class="stm-col-12 stm-col-md-6">
            <h2><?php echo esc_html__('Login', 'motors');?></h2>
			<?php StmListingTemplate::load_template( 'account/login', null, true );?>
		</div>
		<div class="stm-col-12 stm-col-md-6">
            <h2><?php echo esc_html__('Registration', 'motors');?></h2>
			<?php StmListingTemplate::load_template( 'account/register', null, true );?>
		</div>
	</div>
<?php else:?>
	<?php  StmListingTemplate::load_template( $view, array( 'user' => $user), true );?>
<?php endif;?>














