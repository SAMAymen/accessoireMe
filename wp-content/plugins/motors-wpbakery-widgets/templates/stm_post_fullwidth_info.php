<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
?>

<div class="stm-blog-fullwidth-info<?php echo esc_attr( $css_class ); ?>">
	<!--Post thumbnail-->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail stm-post-thumbnail-wide">
			<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
		</div>
		<div class="absoluted-content">
			<div class="container">
				<h2 class="title"><?php echo esc_html( apply_filters( 'stm_trim_title', get_the_title(), 85, '...' ) ); ?></h2>
				<!--Blog meta-->
				<div class="blog-meta clearfix">
					<div class="left">
						<div class="clearfix">
							<div class="blog-meta-unit h6">
								<i class="stm-icon-date"></i>
								<span><?php echo get_the_date(); ?></span>
							</div>
							<div class="blog-meta-unit h6">
								<i class="stm-icon-author"></i>
								<span><?php esc_html_e( 'Posted by:', 'motors-wpbakery-widgets' ); ?></span>
								<span><?php the_author(); ?></span>
							</div>
						</div>
					</div>
					<div class="right">
						<div class="blog-meta-unit h6">
							<a href="<?php comments_link(); ?>" class="post_comments h6">
								<i class="stm-icon-message"></i> <?php comments_number(); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<script>
	(function($) {
		"use strict";

		$(document).ready(function () {
			stmFullwidthThumb();
		});

		$(window).on('load', function(){
			stmFullwidthThumb();
		})

		$(window).on('resize', function(){
			stmFullwidthThumb();
		})

		function stmFullwidthThumb() {
			var defaultWidth = $('.container').width();
			var screenWidth = $(window).width();
			var marginLeft = (screenWidth - defaultWidth) / 2;
			if(screenWidth < 1200){
				marginLeft = 15;
			}
			if($('body').hasClass('rtl')) {
				$('.stm-blog-fullwidth-info').css({
					'left': marginLeft + 'px'
				})
			}

			$('.stm-blog-fullwidth-info').css({
				'width': screenWidth + 'px',
				'margin-left': '-' + marginLeft + 'px'
			})
		}
	})(jQuery);
</script>
