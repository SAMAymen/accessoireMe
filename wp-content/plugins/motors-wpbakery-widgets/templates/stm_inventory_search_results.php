<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$args['post_type']              = apply_filters( 'stm_listings_post_type', 'listings' );
$args['order']                  = 'DESC';
$args['orderby']                = 'date';
$args['meta_query']['relation'] = 'AND';

require get_template_directory() . '/partials/inventory-search-results-query.php';

$listings = new WP_Query( $args );

$random_id = wp_rand( 1, 99999 ) . '_sr_' . wp_rand( 1, 99999 );

?>

<div class="stm-isearch-results-carousel-wrap <?php echo esc_attr( $random_id ); ?>">
	<div class="navigation-controls">
		<div class="back-search-results heading-font">
			<a href="<?php echo esc_url( $back_inventory_link ); ?>">
				<h4><i class="fas fa-arrow-left"></i> <?php esc_html_e( 'Search results', 'motors-wpbakery-widgets' ); ?></h4>
			</a>
		</div>
		<div class="next-prev-controls">
			<div class="stm-isearch-prev"><i class="fas fa-angle-left"></i></div>
			<div class="stm-isearch-next"><i class="fas fa-angle-right"></i></div>
		</div>
	</div>

	<div id="<?php echo esc_attr( $random_id ); ?>" class="stm-carousel owl-carousel stm-isearch-results-carousel car-listing-row <?php echo esc_attr( $css_class ); ?>">

		<?php
		if ( $listings->have_posts() ) :
			$current_vehicle_id = get_queried_object_ID();
			while ( $listings->have_posts() ) :
				$listings->the_post();
				?>

				<div class="media-carousel-item">
					<?php get_template_part( 'partials/inventory-search-results-carousel-loop', null, array( 'current_vehicle_id' => $current_vehicle_id ) ); ?>
				</div>

				<?php
			endwhile;
		endif;

		wp_reset_postdata();
		?>

	</div>
</div>

<style>
	.stm-isearch-results-carousel-wrap .owl-nav, .stm-isearch-results-carousel-wrap .owl-dots {
		display: none!important;
	}
</style>

<script>
	(function ($) {
		"use strict";
		var owl_id = '<?php echo esc_attr( $random_id ); ?>';
		var $owl = $('#'+owl_id);

		$(window).on('load', function () {
			var owlRtl = false;
			if ($('body').hasClass('rtl')) {
				owlRtl = true;
			}

			$owl.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					$owl.find('.owl-nav, .owl-dots').remove();
					$('#' + owl_id + ' .tmb-wrap-table div:first-child').trigger('mouseenter');
				}, 100);
			});

			$owl.owlCarousel({
				rtl: owlRtl,
				items: 4,
				smartSpeed: 800,
				dots: false,
				margin: 10,
				autoplay: false,
				loop: false,
				responsiveRefreshRate: 1000,
				stagePadding: 25,
				responsive: {
					0: {
						center: true,
						items: 1.2,
					},
					550: {
						items: 2,
					},
					768: {
						items: 2,
						loop: false
					},
					991: {
						items: 4,
						loop: false
					},
					1025: {
						items: 4
					}
				}
			});

			var toIndex = 0;
			var count = 0;

			$('#'+owl_id+' .owl-stage .owl-item').each(function(){
				if($(this).find('.stm-template-front-loop').hasClass('current')) {
					toIndex = parseInt(count);
				}
				count++;
			});

			$owl.trigger('to.owl.carousel', [toIndex, 1, true]);

			$('.'+owl_id+' .stm-isearch-prev').on('click', function () {
				if($(this).hasClass('disabled')) return;

				$owl.trigger('prev.owl.carousel');

				$('.'+owl_id+' .stm-isearch-next').removeClass('disabled');

				var first_slide = $('#'+owl_id+' .owl-stage .owl-item').first();
				if(first_slide.hasClass('active')) {
					$(this).addClass('disabled');
				} else {
					$(this).removeClass('disabled');
				}
			});

			$('.'+owl_id+' .stm-isearch-next').on('click', function () {
				if($(this).hasClass('disabled')) return;

				$owl.trigger('next.owl.carousel');

				$('.'+owl_id+' .stm-isearch-prev').removeClass('disabled');

				var last_slide = $('#'+owl_id+' .owl-stage .owl-item').last();
				if(last_slide.hasClass('active')) {
					$(this).addClass('disabled');
				} else {
					$(this).removeClass('disabled');
				}
			});
		});
	})(jQuery);
</script>
