<?php
/**
 * Created by PhpStorm.
 * User: NDA
 * Date: 05.01.2018
 * Time: 15:04
 */

$categories = get_terms(array(
    'taxonomy' => 'category',
	'hide_empty' => 1
));

$get = '';
if(isset($_GET)) {
	unset($_GET['page']);
	$get = '?' . http_build_query($_GET, '', '&amp;');
}

$current_term = get_queried_object();



?>
<div class="stm-magazine-blog-archive-header magazine-archive">
	<h1><?php echo get_the_title(get_option( 'page_for_posts' )); ?></h1>
	<div class="blog-category-filter">
		<ul>
			<li <?php if(!isset($current_term->term_id)) echo 'class="active"'; ?>><a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) . $get ); ?>" class="heading-font"><?php echo esc_html__('All News', 'motors'); ?></a></li>
			<?php foreach ($categories as $val) : ?>
				<li <?php if(isset($current_term->term_id) && $current_term->term_id == $val->term_id) echo 'class="active"'; ?>><a href="<?php echo esc_url(get_term_link($val->term_id) . $get); ?>" class="heading-font"><?php echo esc_attr($val->name); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php ?>
</div>
