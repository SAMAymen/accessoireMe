<?php
/**
 * Account my plans
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/my-plans.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */
$user_plan = false;
if(isset($_GET['id']))
	$user_plan = \uListing\Lib\PricingPlan\Classes\StmUserPlan::query()
	                                                            ->where('id', sanitize_text_field($_GET['id']))
	                                                            ->where('user_id', $user->ID)
	                                                            ->findOne();
?>
<?php if($user_plan):?>
	<?php \uListing\Classes\StmListingTemplate::load_template( 'account/my-plans/detail', ['user' => $user, 'user_plan' => $user_plan], true );?>
<?php else:?>
	<?php \uListing\Classes\StmListingTemplate::load_template( 'account/my-plans/list', ['user' => $user], true );?>
<?php endif;?>