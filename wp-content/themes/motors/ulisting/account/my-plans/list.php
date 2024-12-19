<?php
/**
 * Account my plans list
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/my-plans/list.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.6.2
 */
use uListing\Classes\StmUser;
use uListing\Classes\StmPaginator;
use uListing\Classes\StmListingTemplate;
use uListing\Lib\PricingPlan\Classes\StmUserPlan;
use uListing\Lib\PricingPlan\Classes\StmPricingPlans;

$limit = 5;
$page  = (get_query_var(ulisting_page_endpoint())) ? get_query_var(ulisting_page_endpoint()) : 0;

if( !($models = StmUserPlan::getList($limit, ($page > 1) ? (($page - 1) * $limit ) : 0, array('user_id' => get_current_user_id() ))) )
	$models = array();
?>

<?php StmListingTemplate::load_template( 'account/navigation', ['user' => $user], true );?>

<?php if(!empty($models)):?>
	<table class="table ulisting-table">
		<thead>
		<tr>
			<th>#</th>
			<th><?php esc_html_e("Plan", 'motors')?></th>
			<th><?php esc_html_e("Status", 'motors')?></th>
			<th><?php esc_html_e("Type", 'motors')?></th>
			<th><?php esc_html_e("Payment Type", 'motors')?></th>
			<th><?php esc_html_e("Expired", 'motors')?></th>
			<th><?php esc_html_e("Created", 'motors')?></th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
			foreach ( $models as $model ):
				$PricingPlan = $model->getPricingPlan();
		?>
			<tr>
				<th scope="row"><?php echo esc_attr($model->id)?></th>
				<td><?php echo (isset($PricingPlan->post_title)) ? esc_attr($PricingPlan->post_title) : _e("Pricing Plan not found ",'motors') ?></td>
				<td><?php echo StmUserPlan::getStatus($model->status)?></td>
                <?php if($model->payment_type !== \uListing\Lib\PricingPlan\Classes\StmPricingPlans::PRICING_PLANS_PAYMENT_TYPE_SUBSCRIPTION): ?>
				<td><?php echo StmPricingPlans::pricingPlansTypeListData($model->type) ?></td>
                <?php else:;?>
                    <td>--/--/----</td>
                <?php endif;?>
				<td><?php echo StmPricingPlans::pricingPaymentTypeListData($model->payment_type)?></td>
				<td>
					<?php if($model->payment_type == \uListing\Lib\PricingPlan\Classes\StmPricingPlans::PRICING_PLANS_PAYMENT_TYPE_SUBSCRIPTION): ?>
						<?php echo ulisting_convert_date_format($model->expired_date)?>
					<?php else:?>
						--/--/----
					<?php endif;?>
				</td>
				<td><?php echo ulisting_convert_date_format($model->updated_date).' '.ulisting_convert_time_format($model->updated_date)?></td>
				<td>
					<a href="<?php echo StmUser::getUrl('my-plans').'?id='.$model->id?>">Detail</a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>

	<?php
	$paginator = new StmPaginator(
		StmUserPlan::getList($limit, ($page > 1) ? (($page - 1) * $limit ) : 0, array('user_id' => get_current_user_id()), true),
		$limit,
		$page,
		StmUser::getUrl('my-plans').'/(:num)',
		array(
			'maxPagesToShow' => 8,
			'class' => 'pagination',
			'item_class' => '',
			'link_class' => '',
		)
	);
	echo html_entity_decode($paginator);
	?>
<?php else:?>
	<div class="stm-row stm-justify-content-center p-t-30 stm-empty-msg">
        <h2><?php echo esc_html__('You have no plans yet.', 'motors'); ?></h2>
        <h2><?php echo esc_html__('But you can buy them.', 'motors'); ?></h2>
        <div class="stm-col-12">
			<a class="btn btn-success" href="<?php echo ulisting_get_page_link('pricing_plan')?>"><?php _e('Buy plan', 'motors')?> </a>
		</div>
	</div>
<?php endif;?>


