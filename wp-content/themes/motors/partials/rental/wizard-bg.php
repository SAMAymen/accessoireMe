<?php
$id = stm_woo_shop_page_id();

if(has_post_thumbnail($id)):
    $img_id = get_post_thumbnail_id($id);
    if(!empty($img_id)) {
        $img_id = wp_get_attachment_image_src($img_id, 'full');
        if(!empty($img_id[0])) {
            $img_id = $img_id[0];
        }
    }
    if(!empty($img_id)): ?>
        <style type="text/css">
            .stm-fullwidth-with-parallax-bg {
                background-image: url('<?php echo esc_url($img_id); ?>');
            }
        </style>
    <?php endif; ?>
<?php endif; ?>