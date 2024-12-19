<?php
global $woocommerce;
$checkout_url = wc_get_checkout_url();
if(!empty($checkout_url)):
?>

    <div class="stm_rent_accept_wrapper">
        <a href="<?php echo esc_url($checkout_url); ?>" class="stm_rent_accept heading-font"><?php esc_html_e('Continue', 'motors'); ?></a>
    </div>

<?php endif; ?>