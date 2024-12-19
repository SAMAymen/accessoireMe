<?php
/**
 * Builder attribute add_listing_step_nav
 *
 * Template can be modified by copying it to yourtheme/ulisting/custom/add_listing_step_nav.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

$step_nav = $args['step_num'];

?>

<div class="add-listing-navigate">
	<div class="step <?php if($step_nav == 1) echo esc_attr('active'); ?>">
		<div class="step-num heading-font">01</div>
		<div class="step-name heading-font"><?php echo esc_html__('Listing Type', 'motors');?></div>
	</div>
	<div class="step <?php if($step_nav == 2) echo esc_attr('active'); ?>">
		<div class="step-num heading-font">02</div>
		<div class="step-name heading-font"><?php echo esc_html__('Create listing', 'motors');?></div>
	</div>
	<div class="step <?php if($step_nav == 3) echo esc_attr('active'); ?>">
		<div class="step-num heading-font">03</div>
		<div class="step-name heading-font"><?php echo esc_html__('Done', 'motors');?></div>
	</div>
</div>
