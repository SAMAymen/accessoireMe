<?php
/**
 * Pricing plan list
 *
 * Template can be modified by copying it to yourtheme/ulisting/pricing-plan/list.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.6.2
 */
use uListing\Lib\PricingPlan\Classes\StmPricingPlans;
?>
<?php if($plans):?>
	<h2><?php the_title(); ?></h2>
    <div class="stm-plans-list-wrap">
        <ul class="stm-plans-list">
            <?php foreach ($plans as $plan):?>
                <?php $meta = $plan->getData()?>
                <li>
                    <div class="pricing-plan-box">
                        <div class="pricing-plan-title heading-font"><?php echo esc_attr( $plan->post_title ); ?></div>
                        <div class="pricing-plan-price heading-font"><?php echo ulisting_currency_format( $meta['price'] ); ?></div>
                        <div class="pricing-plan-description">
                            <?php echo html_entity_decode( $plan->post_content ); ?>
                        </div>
                        <div class="pricing-plan-info">
                            <p><?php echo esc_attr( $meta['feature_limit'] ); ?> <?php echo esc_attr( $meta['listing_limit'] ); ?> <?php esc_html_e( 'Listings', 'motors' ); ?></p>
                            <p><?php echo esc_attr( $meta['duration'] ); ?> <?php echo esc_attr( $meta['duration_type'] ); ?> <?php esc_html_e( 'Duration', 'motors' ); ?></p>
                            <p><?php esc_html_e( 'Status:', 'motors' ); ?> <?php echo esc_attr( $meta['status'] ); ?></p>
                        </div>
                        <div class="pricing-plan-button">
                            <a href="<?php echo StmPricingPlans::get_page_url(); ?>?buy=<?php echo esc_attr( $plan->ID ); ?>" class="btn-primary heading-font"><?php esc_html_e( 'Buy Now', 'motors' ); ?></a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else:?>
    <div style="width: 65%; text-align: center; margin: 20px auto;">
        <h3><?php esc_html_e( 'Pricing plans are currently under development. Please contact the site administrator for details.', 'motors' ); ?></h3>
    </div>
<?php endif;?>

<?php if($subscription_plans):?>
	<h2><?php echo __('Subscription', 'motors'); ?></h2>
    <div class="stm-plans-list-wrap">
        <ul class="stm-plans-list">
		<?php foreach ($subscription_plans as $plan):?>
			<?php $meta = $plan->getData()?>
            <li>
                <div class="pricing-plan-box">
                    <div class="pricing-plan-title heading-font"><?php echo esc_attr( $plan->post_title ); ?></div>
                    <div class="pricing-plan-price heading-font"><?php echo ulisting_currency_format( $meta['price'] ); ?></div>
                    <div class="pricing-plan-description">
						<?php echo html_entity_decode( $plan->post_content ); ?>
                    </div>
                    <div class="pricing-plan-info">
                        <p><?php echo esc_attr( $meta['feature_limit'] ); ?> <?php echo esc_attr( $meta['listing_limit'] ); ?> <?php esc_html_e( 'Listings', 'motors' ); ?></p>
                        <p><?php echo esc_attr( $meta['duration'] ); ?> <?php echo esc_attr( $meta['duration_type'] ); ?> <?php esc_html_e( 'Duration', 'motors' ); ?></p>
                        <p><?php esc_html_e( 'Status:', 'motors' ); ?> <?php echo esc_attr( $meta['status'] ); ?></p>
                    </div>
                    <div class="pricing-plan-button">
                        <a href="<?php echo StmPricingPlans::get_page_url(); ?>?buy=<?php echo esc_attr( $plan->ID ); ?>" class="btn-primary heading-font"><?php esc_html_e( 'Buy Now', 'motors' ); ?></a>
                    </div>
                </div>
            </li>
		<?php endforeach; ?>
        </ul>
	</div>
<?php endif;?>


