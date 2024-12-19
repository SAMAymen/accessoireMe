<?php
$id = get_the_ID();
if (class_exists( 'WooCommerce' ) and is_shop()) {
    $id = stm_woo_shop_page_id();
}
$page_bg_color = get_post_meta($id, 'page_bg_color', true);
if (!empty($page_bg_color)): ?>
    <style type="text/css">
        #wrapper {
            background-color: <?php echo esc_attr($page_bg_color); ?> !important;
        }
    </style>
<?php endif;