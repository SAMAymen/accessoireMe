<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );


$random_id = 'owl_' . wp_rand( 1, 99999 );
$items     = vc_param_group_parse_atts( $atts['items'] );

$image_size = 'stm-img-398';
?>
<div class="stm-image-filter-wrap <?php echo esc_attr( $css_class ); ?>">
	<div class="title">
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<div id="<?php echo esc_attr( $random_id ); ?>" class="owl-carousel stm-img-filter-container stm-img-<?php echo esc_attr( $row_number ); ?>">
		<?php
		if ( is_array( $items ) ) {
			$num = 0;
			$i   = 0;
			foreach ( $items as $key => $item ) {
				if ( empty( $item['images'] ) ) {
					continue;
				}
				if ( 0 === $num ) {
					echo '<div class="carousel-container">';
				}
				$stm_term = get_term_by( 'slug', $item['body_type'], 'body' );
				$img      = wp_get_attachment_image_src( $item['images'], $image_size );

				if ( 3 === intval( $row_number ) && ( 0 === $num || 2 === $num ) ) {
					echo '<div class="col-wrap">';
				}

				$calculation = ( $num % intval( $row_number ) );

				?>
				<div class="img-filter-item template-<?php echo esc_attr( $row_number ) . '-' . esc_attr( $calculation ); ?>">
					<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '', array( 'body' => $item['body_type'] ) ) ); ?>">
						<div class="img-wrap">
							<img
								src="<?php echo esc_url( $img[0] ); ?>"
								width="<?php echo esc_attr( $img[1] ); ?>"
								height="<?php echo esc_attr( $img[2] ); ?>"
								alt="<?php echo esc_attr( $stm_term->name ); ?>"
								loading="lazy"
								srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( intval( $item['images'] ), $image_size ) ); ?>"
								<?php if ( 4 === intval( $row_number ) ) : ?>

									<?php if ( in_array( $num, array( 1, 2 ), true ) ) : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 55vw, 635px"
									<?php else : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 45vw, 445px"
									<?php endif; ?>

								<?php elseif ( 3 === intval( $row_number ) ) : ?>

									<?php if ( 2 === $num ) : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 45vw, 445px"
									<?php else : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 55vw, 635px"
									<?php endif; ?>

								<?php elseif ( 5 === intval( $row_number ) ) : ?>

									<?php if ( 0 === $num ) : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 55vw, 635px"
									<?php elseif ( 1 === $num ) : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 45vw, 445px"
									<?php else : ?>
										sizes="(max-width: 499px) 100vw, (max-width: 1160px) 33vw, 350px"
									<?php endif; ?>

								<?php endif; ?>
							/>
						</div>
					</a>
					<div class="body-type-data">
						<div class="bt-title heading-font"><?php echo esc_html( $stm_term->name ?? '' ); ?></div>
						<div class="bt-count normal_font"><?php echo esc_html( $stm_term->count ?? '' ) . ' ' . esc_html__( 'cars', 'motors-wpbakery-widgets' ); ?></div>
					</div>
				</div>
				<?php

				if ( 3 === intval( $row_number ) && ( 1 === $num || 2 === $num ) ) {
					echo '</div>';
				}
				$num = ( intval( $row_number ) - 1 > $num ) ? $num + 1 : 0;
				if ( 0 === $num || ( count( $items ) - 1 ) === $i ) {
					echo '</div>';
				}
				$i++;
			}
		}
		?>
	</div>
</div>

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
					owlIcon.find('.owl-nav, .owl-dots').wrapAll("<div class='owl-controls'></div>");
					owlIcon.find('.owl-dots').remove();
				}, 500);
			});

			owlIcon.owlCarousel({
				items: 1,
				smartSpeed: 800,
				dots: false,
				margin: 0,
				autoplay: false,
				nav: true,
				navElement: 'div',
				loop: false,
				responsiveRefreshRate: 1000,
			})
		});
	})(jQuery);
</script>
