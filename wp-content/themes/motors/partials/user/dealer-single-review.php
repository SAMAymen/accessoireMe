<?php

$post_id = get_the_id();
/*Get user added review*/
$user_added = get_post_meta( $post_id, 'stm_review_added_by', true );

if ( ! empty( $user_added ) ) :

	/*Get enabled reviews*/
	$rate_1_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Customer Service', 'motors' ), 'dealer_rate_1' );
	$rate_2_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Buying Process', 'motors' ), 'dealer_rate_2' );
	$rate_3_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Overall Experience', 'motors' ), 'dealer_rate_3' );

	$rate1           = get_post_meta( $post_id, 'stm_rate_1', true );
	$rate2           = get_post_meta( $post_id, 'stm_rate_2', true );
	$rate3           = get_post_meta( $post_id, 'stm_rate_3', true );
	$stm_recommended = get_post_meta( $post_id, 'stm_recommended', true );

	$number_of_rates = 3;
	$average         = 0;

	if ( empty( $rate_1_label ) ) {
		$rate1 = 0;
		$number_of_rates --;
	} else {
		$average = $rate1;
	}

	if ( empty( $rate_2_label ) ) {
		$rate2 = 0;
		$number_of_rates --;
	} else {
		$average = $average + $rate2;
	}

	if ( empty( $rate_3_label ) ) {
		$rate3 = 0;
		$number_of_rates --;
	} else {
		$average = $average + $rate3;
	}

	?>

	<div class="stm-comment-dealer-wrapper animated fadeIn">
		<?php if ( ! empty( $rate1 ) || ! empty( $rate2 ) || ! empty( $rate3 ) ) : ?>

			<?php
			/*Average*/
			$average       = round( $average / $number_of_rates, 1 );
			$average_width = round( ( ( $average * 100 ) / 5 ), 1 ) . '%';
			?>

			<!--Rating-->
			<div class="clearfix">
				<div class="average">
					<span class="heading-font"><?php echo number_format( $average, '1', '.', '' ); ?></span>
					<div class="stm-star-rating">
						<div class="inner">
							<div class="stm-star-rating-upper" style="width:<?php echo esc_attr( $average_width ); ?>"></div>
							<div class="stm-star-rating-lower"></div>
						</div>
					</div>
				</div>
				<div class="overall">
					<div class="row">
						<?php for ( $i = 1; $i < 4; $i ++ ) : ?>
							<?php if ( ! empty( ${'rate' . $i} ) ) { ?>
								<?php $rate_width = round( ( ${'rate' . $i} * 100 ) / 5, 1 ) . '%'; ?>
								<?php if ( ! empty( ${'rate_' . $i . '_label'} ) ) : ?>
									<div class="stm-one-rate col-md-4 col-sm-4">
										<div>
											<strong>
												<?php echo esc_attr( ${'rate_' . $i . '_label'} ); ?>
											</strong>
											<div class="stm-star-rating">
												<div class="inner">
													<div class="stm-star-rating-upper" style="width:<?php echo esc_attr( $rate_width ); ?>"></div>
													<div class="stm-star-rating-lower"></div>
												</div>
												<span><strong><?php echo number_format( esc_attr( ${'rate' . $i} ), '1', '.', '' ); ?></strong> <?php esc_html_e( 'out of', 'motors' ); ?> 5.0</span>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php } ?>
						<?php endfor; ?>
					</div>
				</div>
			</div>

			<!--Title-->
			<div class="title">
				<?php the_title(); ?>
			</div>

			<!--Content-->
			<div class="content">
				<?php the_content(); ?>
			</div>

			<div class="stm-bottom clearfix">
				<div class="stm-added-by">
					<?php esc_html_e( 'By', 'motors' ); ?> <a class="heading-font" target="_blank" href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user_added ) ); ?>"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', intval( $user_added ), '', '', '' ) ); ?></a>
				</div>
				<?php if ( ! empty( $stm_recommended ) ) : ?>
					<div class="stm-recommend">
						<?php esc_html_e( 'Would I recommend this Dealer:', 'motors' ); ?>
						<?php if ( 'yes' === $stm_recommended ) : ?>
							<strong><?php esc_html_e( 'Yes', 'motors' ); ?></strong>
							<i class="far fa-thumbs-up"></i>
						<?php else : ?>
							<strong><?php esc_html_e( 'No', 'motors' ); ?></strong>
							<i class="far fa-thumbs-down"></i>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="stm-report-review">
					<a href="#" data-id="<?php echo get_the_ID(); ?>"><?php esc_html_e( 'Report', 'motors' ); ?></a>
				</div>
			</div>


		<?php endif; ?>
	</div>

<?php endif; ?>
