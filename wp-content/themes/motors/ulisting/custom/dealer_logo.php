<?php
/**
 * Builder attribute dealer_logo
 *
 * Template can be modified by copying it to yourtheme/ulisting/parts/dealer_logo.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

$userId = $args['model']->post_author;

$userLogo = get_user_meta($userId, 'stm_listing_avatar', true);

if($userLogo) :
?>

<div class="stm-dealer-logo-wrap">
    <img src="<?php echo esc_url($userLogo['url']); ?>" />
</div>

<?php endif; ?>