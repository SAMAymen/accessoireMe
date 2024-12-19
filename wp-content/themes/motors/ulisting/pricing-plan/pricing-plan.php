<?php
/**
 * Pricing plan
 *
 * Template can be modified by copying it to yourtheme/ulisting/pricing-plan/pricing-plan.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.3.9
 */

use uListing\Classes\StmListingTemplate;
$data = array();

stm_listing_pricing_plan_enqueue_scripts_styles();
wp_add_inline_script('stm-pricing-plan', "var stm_pricing_plan_data = json_parse('". ulisting_convert_content(json_encode($data)) ."');", 'before');
?>

<div id="stm-pricing-plan">
	<?php echo StmListingTemplate::load_template( 'listing-list/breadcrumbs'); ?>
<?php
	if(isset($_GET['buy']) AND $pricing_plan = \uListing\Lib\PricingPlan\Classes\StmPricingPlans::find_one($_GET['buy']))
		StmListingTemplate::load_template('pricing-plan/payment',array('pricing_plan' => $pricing_plan), true);
	else
		StmListingTemplate::load_template('pricing-plan/list',array('plans' => $plans, 'subscription_plans' => $subscription_plans), true);
?>
</div>

