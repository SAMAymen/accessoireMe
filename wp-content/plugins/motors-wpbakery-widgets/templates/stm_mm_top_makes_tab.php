<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$makes = explode( ',', $top_makes );
$rand  = wp_rand( 1, 99999 );
?>
<div class="stm-mm-top-makes-wrap">
	<ul class="nav nav-tabs" id="mmTab<?php echo esc_attr( $rand ); ?>" role="tablist">
		<?php foreach ( $makes as $k => $make ) : ?>

			<li class="nav-item
			<?php
			if ( 0 === $k ) {
				echo 'active';
			}
			?>
		">
				<a class="nav-link" id="<?php echo esc_attr( $make . $rand ); ?>-tab" data-toggle="tab"
				href="#<?php echo esc_attr( $make . $rand ); ?>" role="tab"
				aria-controls="<?php echo esc_attr( $make . $rand ); ?>"
				aria-selected="<?php echo ( 0 === $k ) ? 'true' : 'false'; ?>"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $make ) ) ); ?></a>
			</li>

		<?php endforeach; ?>
	</ul>
	<div class="tab-content" id="mmTabContent<?php echo esc_attr( $rand ); ?>">
		<?php
		foreach ( $makes as $k => $make ) :
			$tax_query = '';
			if ( 'all_makes' !== $make ) {
				$tax_query = array(
					array(
						'taxonomy' => 'make',
						'field'    => 'slug',
						'terms'    => $make,
					),
				);
			}

			$query = new WP_Query(
				array(
					'post_type'           => apply_filters( 'stm_listings_post_type', 'listings' ),
					'ignore_sticky_posts' => 1,
					'post_status'         => 'publish',
					'posts_per_page'      => 3,
					'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'stm_car_views',
							'value'   => '0',
							'compare' => '!=',
						),
					),
					'tax_query'           => $tax_query,
					'orderby'             => 'meta_value',
					'order'               => 'DESC',
				)
			);
			?>
			<div class="tab-pane fade 
			<?php
			if ( 0 === $k ) {
				echo 'in active';
			}
			?>
			" id="<?php echo esc_attr( $make . $rand ); ?>" role="tabpanel"
				aria-labelledby="<?php echo esc_attr( $make . $rand ); ?>-tab">
				<div class="stm-mm-vehicles-wrap">
					<?php
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();

							$img   = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-380-240' );
							$price = get_post_meta( get_the_ID(), 'stm_genuine_price', true );
							?>
							<div class="stm-mm-vehicle">
								<div class="vehicle-img">
									<img src="<?php echo esc_url( $img[0] ?? '' ); ?>" class="lazy img-responsive" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
									<div class="heading-font price">
										<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
									</div>
								</div>
								<div class="title heading-font">
									<a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>">
										<?php the_title(); ?>
									</a>
								</div>
							</div>
							<?php
							endwhile;
					endif;
					wp_reset_postdata();
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
