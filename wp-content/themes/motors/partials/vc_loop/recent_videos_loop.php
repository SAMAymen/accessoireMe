<?php
$id = get_the_ID();
$category = stm_motors_get_terms_array($id, 'category', 'name', false);
$date = get_the_date('d M Y', $id);

$comments_count = wp_count_comments( $id);
$post_views = get_post_meta($id, 'stm_car_views', true);
$post_views = (!empty($post_views)) ? $post_views : '0';

$videoUrl = get_post_meta($id, 'video_url', true);

if(!$videoUrl) {
    $videoUrl = get_post_meta($id, 'gallery_video', true);
}
?>

<div class="recent-videos-wrap-loop">
    <a href="<?php echo str_replace('watch?v=', 'embed/', $videoUrl); ?>"
       title="<?php the_title(); ?>"
        <?php echo esc_attr(post_class('stm_magazine_single_grid no_deco')); ?>>
        <div class="magazine-grid-img">
            <?php the_post_thumbnail('stm-mag-img-472-265');?>
            <div class="hover-play-btn">
                <i class="play-btn"></i>
            </div>
        </div>
        <div class="stm-magazine-loop-data">
            <?php if(isset($category[0])): ?>
                <div class="magazine-category heading-font">
                    <?php echo esc_html($category[0]);?>
                </div>
            <?php endif; ?>
            <div class="news-meta-wrap">
                <div class="left">
                    <h3 class="ttc"><?php the_title(); ?></h3>
                </div>
                <div class="right">
                    <div class="magazine-loop-reviews">
                        <i class="stm-icon-ico_mag_reviews"></i>
                        <div class="heading-font"><?php echo esc_html($comments_count->total_comments); ?></div>
                    </div>
                    <div class="magazine-loop-views">
                        <i class="stm-icon-ico_mag_eye"></i>
                        <div class="heading-font"><?php echo esc_html($post_views); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
