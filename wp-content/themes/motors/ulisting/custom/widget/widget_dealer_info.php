<?php
/**
 * Builder attribute widget_dealer_info
 *
 * Template can be modified by copying it to yourtheme/ulisting/custom/widget/widget_dealer_info.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

use uListing\Classes\StmUser;

$userId = $args['model']->post_author;

$user = new StmUser($userId);

$userRole = $user->getRole();
?>
<script>
    (function($) {
        $(document).ready(function() {
            if($('.stm-widget-user-info').parent().find('.wpcf7-form').length > 0) {
                var wpcf = $('.stm-widget-user-info').parent().find('.wpcf7-form');
                var inputAuthor = '<input type="hidden" class="user_recipient" name="motors_changed_recipient" />';
                wpcf.append(inputAuthor);
                $('.user_recipient').attr("value", <?php echo abs($userId); ?>);
            }
        });
    })(jQuery)
</script>

<div class="stm-widget-user-info">
	<?php if( !empty( $user->getAvatarUrl() ) ) : ?>
	<div class="stm-dealer-logo-wrap">
		<div class="logo-wrap">
			<img src="<?php echo esc_url( $user->getAvatarUrl() ); ?>" alt="<?php echo esc_attr( $user->user_login ); ?>" />
		</div>
		<div class="name-wrap">
			<span class="name heading-font"><?php echo esc_html($user->nickname); ?></span>
			<span class="role"><?php echo esc_html($userRole['name']); ?></span>
		</div>
	</div>
	<?php endif; ?>
	<?php if( !empty( $user->phone ) ) { ?>
		<div class="user_box_field number">
            <div class="ico-wrap">
                <i class="stm-all-icon-phone1"></i>
            </div>
            <div class="info-wrap">
                <span class="user_box_value heading-font"><?php echo esc_html( $user->phone ); ?></span>
                <span class="user_box_label"><?php esc_html_e( 'Call Free 24/7', 'motors' ); ?></span>
            </div>
		</div>
	<?php } ?>
	<?php if( !empty( $user->user_email ) ) { ?>
		<div class="user_box_field">
            <div class="ico-wrap">
                <i class="stm-all-icon-mail"></i>
            </div>
            <div class="info-wrap">
                <span class="user_box_label"><?php esc_html_e( 'Seller Email:', 'motors' ); ?></span>
                <span class="user_box_value heading-font"><?php echo esc_html( $user->user_email ); ?></span>
            </div>
		</div>
	<?php } ?>
</div>

