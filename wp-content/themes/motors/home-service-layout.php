<?php
/**
 * Template Name: Service Page Layout
 *
 * @package WordPress
 * @subpackage motors
 * @since Motors 1.2
 */

?>

<?php get_header(); ?>

	<?php
	if ( ! is_front_page() ) {
		get_template_part( 'partials/title_box' );
	}
	?>

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
		<div class="container">
			<?php the_content(); ?>
		</div>
			<?php
	endwhile;
endif;
	?>

<?php get_footer(); ?>
