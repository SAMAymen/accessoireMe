<?php
/**
 * Account my plans detail
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/my-plans/detail.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.6.2
 */
use uListing\Classes\StmUser;
\uListing\Lib\PricingPlan\Classes\StmUserPlan::updateStatusPlanForExpired();

$plan = $user_plan->getPricingPlan();
$data = array(
	'user_plan_id' => $user_plan->id,
	'cancel_url'   => get_site_url(null, "/api/pricing-plan/user-plan/cancel"),
	'user_id'      => $user->ID
);
wp_add_inline_script('user-plan-detail', "var user_plan_detail_data = json_parse('". ulisting_convert_content(json_encode($data)) ."');", 'before');
?>

<div id="user-plan-detail">

<h2><?php echo esc_html($plan->post_title)?></h2>

<div class="stm-row">
	<div class="stm-col-3"><label>Plan : </label></div>
	<div class="stm-col-4"><?php echo esc_html($plan->post_title)?></div>
</div>

<div class="stm-row">
	<div class="stm-col-3"><label>Status : </label></div>
	<div class="stm-col-4"><?php echo \uListing\Lib\PricingPlan\Classes\StmUserPlan::getStatus($user_plan->status)?></div>
</div>
<?php if($user_plan->payment_type !== \uListing\Lib\PricingPlan\Classes\StmPricingPlans::PRICING_PLANS_PAYMENT_TYPE_SUBSCRIPTION): ?>
<div class="stm-row">
	<div class="stm-col-3"><label>Type : </label></div>
	<div class="stm-col-4"><?php echo \uListing\Lib\PricingPlan\Classes\StmPricingPlans::pricingPlansTypeListData($user_plan->type)?></div>
</div>
<?php endif;?>

<div class="stm-row">
	<div class="stm-col-3"><label>Payment type : </label></div>
	<div class="stm-col-4"><?php echo \uListing\Lib\PricingPlan\Classes\StmPricingPlans::pricingPaymentTypeListData($user_plan->payment_type)?></div>
</div>

<?php if($user_plan->payment_type == \uListing\Lib\PricingPlan\Classes\StmPricingPlans::PRICING_PLANS_PAYMENT_TYPE_SUBSCRIPTION): ?>
	<div class="stm-row">
		<div class="stm-col-3"><label>Expired date : </label></div>
		<div class="stm-col-4">
			<?php echo  date_i18n( get_option( 'date_format' ), strtotime( $user_plan->expired_date ) );?>
			<?php echo  date_i18n( get_option( 'time_format' ), strtotime( $user_plan->expired_date ) );?>
		</div>
	</div>
<?php endif;?>

<div class="stm-row">
	<div class="stm-col-3"><label>Created date : </label></div>
	<div class="stm-col-4">
		<?php echo  date_i18n( get_option( 'date_format' ), strtotime( $user_plan->created_date ) );?>
		<?php echo  date_i18n( get_option( 'time_format' ), strtotime( $user_plan->created_date ) );?>
	</div>
</div>
<hr>
<div class="stm-plans-dashbord-btn-wrap">
    <a href="<?php echo StmUser::getUrl('my-plans')?>" class="btn-primary">
        <?php esc_html_e('My Plans', 'motors'); ?>
    </a>
</div>
<p v-if="message"> {{message}} </p>

	<?php if($user_plan->payment_type == \uListing\Lib\PricingPlan\Classes\StmPricingPlans::PRICING_PLANS_PAYMENT_TYPE_SUBSCRIPTION): ?>
		<?php if( (
			   $user_plan->status == \uListing\Lib\PricingPlan\Classes\StmUserPlan::STATUS_ACTIVE ||
		       $user_plan->status == \uListing\Lib\PricingPlan\Classes\StmUserPlan::STATUS_PENDING ||
		       $user_plan->status == \uListing\Lib\PricingPlan\Classes\StmUserPlan::STATUS_INACTIVE
		      ) AND !$user_plan->getMeta('canceled')  ):?>
		<div class="panel-custom p-t-30 p-b-30">
			<div v-if="loading" class="text-center">
				<div class="stm-spinner"> <div></div> <div></div> <div></div> <div></div> <div></div> </div>
			</div>
			<button class="btn btn-danger" v-if="!loading" @click="user_plan_cancel"><?php _e("Cancel", 'motors')?></button>
		</div>
			<?php elseif($user_plan->getMeta('canceled')):?>
				<div class="alert alert-warning text-uppercase" role="alert">
					<?php echo esc_html($user_plan->getMeta('canceled')->meta_key) ?>
				</div>
			<?php endif;?>
	<?php endif;?>
</div>

