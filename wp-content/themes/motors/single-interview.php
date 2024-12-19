<?php
/**
 * Template Name: Interview
 * Template Post Type: post
 */

get_header();
    get_template_part('partials/magazine/content/top-interview');
    get_template_part('partials/magazine/content/breadcrumbs');
	?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="stm-single-post">
            <div class="container">
                <?php if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        get_template_part('partials/magazine/main-interview');
                    endwhile;
                endif; ?>
            </div>
        </div>
    </div>
<?php get_footer();?>