<?php
/**
 * Listing single single
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-single/single.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

use uListing\Classes\StmListing;
use uListing\Classes\StmListingTemplate;

?>
<?php get_header(); ?>
    <div class="container">
        <?php echo StmListingTemplate::load_template( 'listing-list/breadcrumbs');?>
    </div>
    <div class="stm-container">
		<?php if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				$model = StmListing::load( get_post() );
				if ( $model->post_type == 'listing' ) StmListingTemplate::load_template( 'listing-single/content', [ 'model' => $model ], true ); else
					echo the_content();
			endwhile;
		endif; ?>
    </div>
	<script>
		(function ($) {
			$(window).load(function () {
				if($('.big-wrap')) $('.big-wrap').addClass('owl-carousel');
				if($('.thumbs-wrap')) $('.thumbs-wrap').addClass('owl-carousel');

				$('.big-wrap .owl-nav').remove();
				$('.big-wrap .owl-dots').remove();

				$('.thumbs-wrap').find('.owl-nav, .owl-dots').wrapAll("<div class='owl-controls'></div>");
				
			});
		})(jQuery);
	</script>
<?php get_footer(); ?>