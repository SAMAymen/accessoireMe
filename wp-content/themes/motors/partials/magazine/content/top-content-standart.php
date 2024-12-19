<?php
/**
 * Created by PhpStorm.
 * User: NDA
 * Date: 11.01.2018
 * Time: 15:10
 */

$id = get_the_ID();
$commentCount = wp_count_comments($id)->approved;
?>
<div class="post-top-content">
	<div class="r-t-c-left">
		<div class="r-t-c-author heading-font">
			<div class="author-ava-wrap">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?>
			</div>
			<?php echo esc_html(get_the_author_meta( 'display_name' )); ?>
		</div>
		<div class="r-t-c-date-num heading-font">
			<i class="stm-icon-ico_mag_calendar"></i>
			<?php echo get_the_date('d M Y'); ?>
		</div>
        <div class="r-t-c-comment-num heading-font">
			<i class="stm-icon-ico_mag_reviews"></i>
			<?php echo esc_html($commentCount); ?>
		</div>
		<div class="r-t-c-share">
            <?php if(class_exists( 'SC_Class' )): ?>
                <?php echo do_shortcode('[aps-counter theme="theme-2"]'); ?>
            <?php endif; ?>
		</div>
	</div>
	<div class="r-t-c-right">
        <h1><?php the_title(); ?></h1>
        <div class="post-content">
            <?php the_content(); ?>
            <div class="clearfix"></div>
        </div>
	</div>
</div>