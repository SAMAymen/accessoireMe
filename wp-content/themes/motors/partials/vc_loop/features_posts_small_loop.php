<?php
$id = get_the_ID();
$category = stm_motors_get_terms_array($id, 'category', 'name', false);
if(empty($category)) $category = stm_motors_get_terms_array($id, 'review_category', 'name', false);
if(empty($category)) $category = stm_motors_get_terms_array($id, 'event_category', 'name', false);

$date = get_the_date('d M Y', $id);

$comments_count = wp_count_comments( $id);
$post_views = get_post_meta($id, 'stm_car_views', true);
$post_views = (!empty($post_views)) ? $post_views : '0';
?>

<div class="features-small-wrap">
    <a href="<?php the_permalink(); ?>"
       title="<?php the_title(); ?>"
        <?php echo esc_attr(post_class('stm_magazine_single_grid no_deco')); ?>>
        <div class="magazine-grid-img">
            <?php the_post_thumbnail('stm-img-200-200');?>
        </div>
        <div class="stm-magazine-loop-data">
            <?php if(isset($category[0])): ?>
                <div class="magazine-category heading-font">
                    <?php echo esc_html($category[0]);?>
                </div>
            <?php endif; ?>
            <div class="news-meta-wrap">
                <h3 class="ttc"><?php the_title(); ?></h3>
            </div>
        </div>
    </a>
</div>