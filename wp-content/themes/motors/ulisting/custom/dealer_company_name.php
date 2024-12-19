<?php
/**
 * Builder attribute dealer_company_name
 *
 * Template can be modified by copying it to yourtheme/ulisting/parts/dealer_company_name.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

$userId = $args['model']->post_author;

$userCompName = get_user_meta($userId, 'company_name', true);
?>

<div class="stm-dealer-comp-name-wrap">
	<?php if($userCompName):?>
		<div class="company-name">
			<?php echo esc_html($userCompName); ?>
		</div>
	<?php endif; ?>
</div>
