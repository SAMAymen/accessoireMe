<?php
if ( function_exists( 'get_post_id_by_meta_k_v' ) ) {
	$reviewId = get_post_id_by_meta_k_v( 'review_car', get_the_ID() );
	if ( ! is_null( $reviewId ) ) {

		$performance = get_post_meta( $reviewId, 'performance', true );
		$comfort     = get_post_meta( $reviewId, 'comfort', true );
		$interior    = get_post_meta( $reviewId, 'interior', true );
		$exterior    = get_post_meta( $reviewId, 'exterior', true );

		$ratingSumm = ( ( $performance + $comfort + $interior + $exterior ) / 4 );
		?>
		<div class="single-car-review-rating">
			<table>
				<tbody>
				<tr>
					<td>
						<div class="h6 title_black"><?php echo esc_html__( 'Car Overview', 'motors' ); ?></div>
						<div class="h6 title_blue"><?php echo esc_html__( 'Ratings', 'motors' ); ?></div>
					</td>
					<td>
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $ratingSumm ) * 20; ?>%;"></i>
						</div>
						<div class="rating-text heading-font">
							<?php
							// translators: for rating summ
							echo wp_kses_post( sprintf( esc_html__( '%s out of 5.0', 'motors' ), $ratingSumm ) );
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="t-lable">
						<span class="normal-font"><?php echo esc_html__( 'Performance', 'motors' ); ?></span>
					</td>
					<td class="t-value h6">
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $performance ) * 20; ?>%;"></i>
						</div>
					</td>
				</tr>
				<tr>
					<td class="t-lable">
						<span class="normal-font"><?php echo esc_html__( 'Comfort', 'motors' ); ?></span>
					</td>
					<td class="t-value h6">
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $comfort ) * 20; ?>%;"></i>
						</div>
					</td>
				</tr>
				<tr>
					<td class="t-lable">
						<span class="normal-font"><?php echo esc_html__( 'Interior', 'motors' ); ?></span>
					</td>
					<td class="t-value h6">
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $interior ) * 20; ?>%;"></i>
						</div>
					</td>
				</tr>
				<tr>
					<td class="t-lable">
						<span class="normal-font"><?php echo esc_html__( 'Exterior', 'motors' ); ?></span>
					</td>
					<td class="t-value h6">
						<div class="rating-stars">
							<i class="rating-empty"></i>
							<i class="rating-color" style="width: <?php echo esc_attr( $exterior ) * 20; ?>%;"></i>
						</div>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}
