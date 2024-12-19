<?php
/**
 * Builder attribute dealer_info
 *
 * Template can be modified by copying it to yourtheme/ulisting/parts/dealer_info.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

$userId = $args['model']->post_author;

$userPhone = get_user_meta($userId, 'phone', true);
$userCompName = get_user_meta($userId, 'company_name', true);
?>

<div class="stm-dealer-info-wrap">
    <?php if($userCompName):?>
    <div class="company-name">
        <?php echo esc_html($userCompName); ?>
    </div>
    <?php
    endif;
    if($userPhone):
    ?>
    <div class="phone">
		<i class="stm-all-icon-phone"></i>
		<div class="phone heading-font">
			<?php echo substr_replace($userPhone, "*******", 3, strlen($userPhone)); ?>
		</div>
		<span class="stm-show-number" data-id="<?php echo esc_attr($userId); ?>"><?php echo esc_html__("Show number", "motors"); ?></span>
    </div>
    <?php endif; ?>
</div>
