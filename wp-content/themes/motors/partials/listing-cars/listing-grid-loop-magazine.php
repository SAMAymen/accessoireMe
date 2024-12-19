<?php
$review_id = get_post_id_by_meta_k_v( 'review_car', get_the_ID() );
$start_at  = get_post_meta( $review_id, 'show_title_start_at', true );
$price     = apply_filters( 'stm_filter_price_view', '', get_post_meta( get_the_ID(), 'stm_genuine_price', true ) );
$hwy       = get_post_meta( get_the_ID(), 'highway_mpg', true );
$cwy       = get_post_meta( get_the_ID(), 'sity_mpg', true );

$rating_summary = 0;

if ( ! is_null( $review_id ) ) {
	$performance    = get_post_meta( $review_id, 'performance', true );
	$comfort        = get_post_meta( $review_id, 'comfort', true );
	$interior       = get_post_meta( $review_id, 'interior', true );
	$exterior       = get_post_meta( $review_id, 'exterior', true );
	$rating_summary = ( ( $performance + $comfort + $interior + $exterior ) / 4 );
}

?>
<div class="col-md-4 col-sm-6 col-xs-12">
	<div class="magazine-listing-item">
		<div class="magazine-loop">
			<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			<a href="<?php the_permalink(); ?>">
				<div class="img">
					<?php do_action( 'stm_listings_load_template', 'loop/default/grid/image' ); ?>
					<div class='fa-round'><i class='fa fa-share'></i></div>
				</div>
			</a>
			<div class="middle_info 
			<?php
			if ( $rating_summary > 0 ) {
				echo 'middle-rating';}
			?>
			">
				<div class="car_info">
					<?php if ( ! empty( $start_at ) ) : ?>
						<div class="starting-at normal-font">
							<?php echo esc_html__( 'Starting at', 'motors' ); ?>
						</div>
					<?php endif; ?>
					<div class="price heading-font">
						<?php echo wp_kses_post( $price ); ?>
					</div>
					<?php if ( empty( $start_at ) ) : ?>
						<div class="mpg normal-font">
							<?php echo esc_html( $hwy ) . esc_html__( 'Hwy', 'motors' ) . ' / ' . esc_html( $cwy ) . esc_html__( 'City', 'motors' ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( $rating_summary > 0 ) : ?>
					<div class="rating">
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $rating_summary ) * 20; ?>%;"></i>
						</div>
						<div class="rating-text heading-font">
							<?php echo sprintf( esc_html__( '%s out of 5.0', 'motors' ), $rating_summary ); //phpcs:ignore ?>
						</div>
						<div class="rating-details-popup">
							<ul class="rating-params">
								<li>
									<span class="normal-font"><?php echo esc_html__( 'Performance', 'motors' ); ?></span>
									<div class="rating-stars">
										<i class="rating-empty"></i>
										<i class="rating-color" style="width: <?php echo esc_attr( $performance ) * 20; ?>%;"></i>
									</div>
								</li>
								<li>
									<span class="normal-font"><?php echo esc_html__( 'Comfort', 'motors' ); ?></span>
									<div class="rating-stars">
										<i class="rating-empty"></i>
										<i class="rating-color" style="width: <?php echo esc_attr( $comfort ) * 20; ?>%;"></i>
									</div>
								</li>
								<li>
									<span class="normal-font"><?php echo esc_html__( 'Interior', 'motors' ); ?></span>
									<div class="rating-stars">
										<i class="rating-empty"></i>
										<i class="rating-color" style="width: <?php echo esc_attr( $interior ) * 20; ?>%;"></i>
									</div>
								</li>
								<li>
									<span class="normal-font"><?php echo esc_html__( 'Exterior', 'motors' ); ?></span>
									<div class="rating-stars">
										<i class="rating-empty"></i>
										<i class="rating-color" style="width: <?php echo esc_attr( $exterior ) * 20; ?>%;"></i>
									</div>
								</li>
							</ul>
						</div>
					</div>
				<?php else : ?>
					<div class="no-review normal-font">
						<?php echo esc_html__( 'No reviews for this Vehicle', 'motors' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="excerpt normal-font">
				<?php apply_filters( 'the_excerpt_max_charlength', get_the_excerpt( get_the_ID() ), 115 ); ?>
			</div>
		</div>
	</div>
</div>
