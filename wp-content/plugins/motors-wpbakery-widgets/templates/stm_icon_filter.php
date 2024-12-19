<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class           = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$display_as_carousel = ( ! empty( $as_carousel ) ) ? $as_carousel : 'no';
$random_id           = 'owl_' . wp_rand( 0, 99999 );

if ( ! empty( $filter_selected ) ) :

	$filter_selected_info = apply_filters( 'stm_vl_get_all_by_slug', array(), $filter_selected );

	$args = array(
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,
		'pad_counts' => true,
	);

	$terms = get_terms( $filter_selected, $args );

	$terms_images = array();
	$terms_text   = array();
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $stm_term ) {
			$image = get_term_meta( $stm_term->term_id, 'stm_image', true );
			if ( empty( $image ) ) {
				$terms_text[] = $stm_term;
			} else {
				$terms_images[] = $stm_term;
			}
		}
	}

	if ( empty( $limit ) && 'no' === $display_as_carousel ) {
		$limit = 20;
	}

	if ( 'yes' === $display_as_carousel ) {
		$limit = 100;
	}

	?>
	<div class="stm_icon_filter_unit <?php echo esc_attr( $css_class ); ?>">
		<div class="clearfix">
			<?php if ( ! empty( $duration ) && 'yes' !== $display_as_carousel ) : ?>
				<div class="stm_icon_filter_label">
					<?php echo esc_attr( $duration ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $content ) ) : ?>
				<div class="stm_icon_filter_title">
					<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $terms ) ) : ?>
			<div id="<?php echo esc_attr( $random_id ); ?>" class="<?php echo ( 'yes' === $display_as_carousel ) ? 'owl-carousel' : ''; ?> stm_listing_icon_filter stm_listing_icon_filter_<?php echo esc_attr( $per_row ); ?> text-<?php echo esc_attr( $align ); ?> filter_<?php echo esc_attr( $filter_selected ); ?>">

				<?php
				$i = 0;
				foreach ( $terms_images as $stm_term ) :
					?>
					<?php
					$image = get_term_meta( $stm_term->term_id, 'stm_image', true );

					// Getting limit for frontend without showing all.
					if ( $limit > $i ) :
						$image          = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
						$category_image = $image[0];
						?>
						<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '', array( $filter_selected => $stm_term->slug ) ) ); ?>" class="stm_listing_icon_filter_single"
							title="<?php echo esc_attr( $stm_term->name ); ?>">
							<div class="inner">
								<div class="image">
									<img src="<?php echo esc_url( $category_image ); ?>"
										alt="<?php echo esc_attr( $stm_term->name ); ?>"/>
								</div>
								<div class="name"><?php echo esc_attr( $stm_term->name ); ?>
									<?php
									if ( 'no' === $display_as_carousel ) :
										?>
										<span class="count">(<?php echo esc_html( $stm_term->count ); ?>)</span><?php endif; ?></div>
							</div>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '', array( $filter_selected => $stm_term->slug ) ) ); ?>"
							class="stm_listing_icon_filter_single non-visible"
							title="<?php echo esc_attr( $stm_term->name ); ?>">
							<div class="inner">
								<div class="name">
									<?php echo esc_attr( $stm_term->name ); ?>
									<?php
									if ( 'no' === $display_as_carousel ) :
										?>
										<span class="count">(<?php echo esc_html( $stm_term->count ); ?>)</span><?php endif; ?></div>
							</div>
						</a>
					<?php endif; ?>
					<?php $i++; ?>
				<?php endforeach; ?>
				<?php foreach ( $terms_text as $stm_term ) : ?>
					<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '', array( $filter_selected => $stm_term->slug ) ) ); ?>"
						class="stm_listing_icon_filter_single non-visible"
						title="<?php echo esc_attr( $stm_term->name ); ?>">
						<div class="inner">
							<div class="name">
								<?php echo esc_attr( $stm_term->name ); ?>
								<?php
								if ( 'no' === $display_as_carousel ) :
									?>
									<span class="count">(<?php echo esc_html( $stm_term->count ); ?>)</span>
								<?php endif; ?>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
endif;

if ( 'yes' === $display_as_carousel ) :
	?>
<script>
	(function($) {
		$(document).ready(function () {
			var owlIcon = $('#<?php echo esc_attr( $random_id ); ?>');
			var owlRtl = false;
			if( $('body').hasClass('rtl') ) {
				owlRtl = true;
			}

			owlIcon.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					owlIcon.find('.owl-nav').wrapAll("<div class='owl-controls'></div>");
					owlIcon.find('.owl-dots').remove();
				}, 500);
			});

			owlIcon.owlCarousel({
				items: <?php echo esc_js( $atts['items_count'] ); ?>,
				smartSpeed: 800,
				dots: false,
				margin: 0,
				autoplay: false,
				nav: true,
				navElement: 'div',
				loop: false,
				responsiveRefreshRate: 1000,
				responsive:{
					0:{
						items:2
					},
					500:{
						items:4
					},
					768:{
						items:5
					},
					1000:{
						items: <?php echo esc_js( $atts['items_count'] ); ?>
					}
				}
			})
		});
	})(jQuery);
</script>
<?php endif; ?>
