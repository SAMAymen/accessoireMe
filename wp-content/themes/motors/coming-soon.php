<?php
/*
Template Name: Coming soon
*/
?>

<?php get_header(); ?>

	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php
			if ( has_post_thumbnail() ) :
				$page_bg = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
			endif;
			?>
			<div class="stm-coming-soon-centered">
				<div class="container">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>

<?php if ( ! empty( $page_bg[0] ) ) : ?>
	<style>
		body {
			background-image: url("<?php echo esc_url( $page_bg[0] ); ?>");
		}
		<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
			#wrapper {
				background: transparent !important;
			}
		<?php endif; ?>
	</style>
<?php endif; ?>

<?php get_footer(); ?>
