<?php
class STM_WP_ClassifiedFourPriceView extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_wp_classified_four_price_view',
			'description' => __( 'STM Classified Four Price widget', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_classified_four_price_view', __( 'STM Classified Four Price View', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );
		$price                     = get_post_meta( get_the_ID(), 'price', true );
		$sale_price                = get_post_meta( get_the_ID(), 'sale_price', true );
		$regular_price_description = get_post_meta( get_the_ID(), 'regular_price_description', true );
		$car_price_form_label      = get_post_meta( get_the_ID(), 'car_price_form_label', true );

		if ( ! apply_filters( 'stm_is_aircrafts', true ) ) {
			$regular_price_label   = get_post_meta( get_the_ID(), 'regular_price_label', true );
			$special_price_label   = get_post_meta( get_the_ID(), 'special_price_label', true );
			$instant_savings_label = get_post_meta( get_the_ID(), 'instant_savings_label', true );

			// Get text price field.
			$car_price_form       = get_post_meta( get_the_ID(), 'car_price_form', true );
			$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );
			$show_price           = true;
			$show_sale_price      = true;

			if ( empty( $price ) ) {
				$show_price = false;
			}

			if ( empty( $sale_price ) ) {
				$show_sale_price = false;
			}

			if ( ! empty( $price ) && empty( $sale_price ) ) {
				$show_sale_price = false;
			}

			if ( ! empty( $price ) && ! empty( $sale_price ) ) {
				if ( intval( $price ) === intval( $sale_price ) ) {
					$show_sale_price = false;
				}
			}

			if ( empty( $price ) && ! empty( $sale_price ) ) {
				$price           = $sale_price;
				$show_price      = true;
				$show_sale_price = false;
			}

			if ( apply_filters( 'stm_is_dealer_two', false ) ) {
				$selling_online = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' );
				$is_sell_online = ( $selling_online ) ? ! empty( get_post_meta( get_the_ID(), 'car_mark_woo_online', true ) ) : false;
			}

			if ( $show_price && ! $show_sale_price ) {
				if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) :
					?>
					<a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-price="<?php echo esc_attr( $price ); ?>" >
					<?php
				else :
					if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) :
						?>
						<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
						<?php
					endif;
				endif;
				?>

				<div class="single-car-prices">
					<div class="single-regular-price text-center">
						<?php if ( ! empty( $car_price_form_label ) ) : ?>
							<span class="h3">
								<?php echo esc_html( $car_price_form_label ); ?>
							</span>
						<?php else : ?>

							<?php if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) : ?>
								<span class="labeled"><?php esc_html_e( 'BUY CAR ONLINE:', 'stm_motors_extends' ); ?></span>
							<?php else : ?>

								<?php if ( ! empty( $regular_price_label ) ) : ?>
									<span class="labeled">
										<?php echo esc_html( $regular_price_label ); ?>
									</span>
								<?php endif; ?>

							<?php endif; ?>

							<span class="h3">
								<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) : ?>
					</a>
				<?php else : ?>
					<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
						</a>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( ! empty( $regular_price_description ) ) : ?>
					<div class="price-description-single">
						<?php echo esc_html( $regular_price_description ); ?>
					</div>
				<?php endif; ?>

				<?php
			}

			if ( $show_price && $show_sale_price ) {
				?>

				<div class="single-car-prices">
					<?php
					if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) :
						?>
						<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
							<div class="single-regular-price text-center">
								<?php if ( ! empty( $car_price_form_label ) ) : ?>
									<span class="h3">
										<?php echo esc_html( $car_price_form_label ); ?>
									</span>
								<?php endif; ?>
							</div>
						</a>
						<?php
					else :
						if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) :
							?>
							<a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-price="<?php echo esc_attr( $sale_price ); ?>" >
							<?php
						endif;
						?>
						<div class="single-regular-sale-price">
							<table>
								<?php if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) : ?>
									<tr>
										<td colspan="2" style="border: 0; padding-bottom: 5px;" align="center">
											<span class="labeled"><?php esc_html_e( 'BUY CAR ONLINE', 'stm_motors_extends' ); ?></span>
										</td>
									</tr>
								<?php endif; ?>
								<tr>
									<td>
										<div class="regular-price-with-sale">
											<?php
											if ( ! empty( $regular_price_label ) ) :
												echo esc_html( $regular_price_label );
											endif;
											?>
											<strong>
												<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
											</strong>

										</div>
									</td>
									<td>
										<?php if ( ! empty( $special_price_label ) ) : ?>
											<?php
											echo esc_html( $special_price_label );
											$mg_bt = '';
										else :
											$mg_bt = 'style=margin-top:0';
										endif;
										?>
										<div class="h4" <?php echo esc_attr( $mg_bt ); ?>>
											<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?>
										</div>
									</td>
								</tr>
							</table>
						</div>

						<?php
						if ( apply_filters( 'stm_is_dealer_two', false ) && $is_sell_online ) :
							?>
							</a>
							<?php
						endif;
					endif;
					?>
				</div>
				<?php if ( empty( $car_price_form ) && ! empty( $instant_savings_label ) ) : ?>
					<?php $savings = intval( $price ) - intval( $sale_price ); ?>
					<div class="sale-price-description-single">
						<?php echo esc_html( $instant_savings_label ); ?>
						<strong>
							<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $savings ) ); ?>
						</strong>
					</div>
				<?php endif; ?>
				<?php
			}

			if ( ! $show_price && ! $show_sale_price && ! empty( $car_price_form_label ) ) {
				?>
				<?php
				if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) :
					?>
					<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
					<?php
				endif;
				?>

				<div class="single-car-prices">
					<div class="single-regular-price text-center">
						<span class="h3">
							<?php echo esc_html( $car_price_form_label ); ?>
						</span>
					</div>
				</div>

				<?php
				if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) :
					?>
					</a>
					<?php
				endif;

				if ( ! empty( $regular_price_description ) ) :
					?>
					<div class="price-description-single">
						<?php echo esc_html( $regular_price_description ); ?>
					</div>
					<?php
				endif;
			}
		} else {
			?>
			<div class="aircraft-price-wrap">
				<?php if ( empty( $car_price_form_label ) ) : ?>
					<div class="left">
						<?php if ( empty( $sale_price ) ) : ?>
							<span class="h3"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
						<?php else : ?>
							<span class="h4"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?></span>
							<span class="h3"><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></span>
						<?php endif; ?>
					</div>
					<div class="right">
						<?php if ( ! empty( $regular_price_description ) ) : ?>
							<div class="price-description-single"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_description, 'Regular Price Description' ) ); ?></div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="custom-label">
						<center>
							<span class="h3">
								<?php echo esc_html( $car_price_form_label ); ?>
							</span>
						</center>
					</div>
				<?php endif; ?>
			</div>
			<?php
		}
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ) {
		return $old_instance;
	}

	public function form( $instance ) {}
}

add_action( 'widgets_init', 'register_stm_classified_four_price_view' );
function register_stm_classified_four_price_view() {
	register_widget( 'STM_WP_ClassifiedFourPriceView' );
}
