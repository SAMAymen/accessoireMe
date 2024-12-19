<?php

class DiscountByDays {
	const META_KEY_INFO   = 'rental_discount_days_info';
	private static $varId = 0;

	public function __construct() {
		add_action( 'stm_disc_by_days', array( $this, 'discountByDaysView' ) );
		add_action( 'save_post', array( $this, 'add_days_post_meta' ), 10, 2 );
		add_filter( 'woocommerce_product_type_query', array( get_class(), 'setVarId' ), 20, 2 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'updateVariationPriceWithDiscount' ), 50, 2 );
		add_filter(
			'woocommerce_product_variation_get_price',
			array(
				$this,
				'updateVariationPriceWithDiscount',
			),
			50,
			2
		);
		add_filter( 'stm_cart_items_content', array( $this, 'updateCart' ), 50, 1 );
	}

	public static function hasDiscount( $id, $days = 0 ) {
		if ( $days ) {
			$getDaysDisc = self::get_days_post_meta( $id );

			return ( ! empty( $getDaysDisc[ $days ] ) ) ? true : false;
		}

		return ( ! empty( self::get_days_post_meta( $id ) ) ) ? true : false;
	}

	public static function add_days_post_meta( $post_id, $post ) {
		if ( isset( $_POST['days'][0] ) && ! empty( $_POST['days'][0] ) && isset( $_POST['percent'][0] ) && ! empty( $_POST['percent'][0] ) ) {
			$data = array();

			foreach ( $_POST['days'] as $key => $val ) {
				if ( ! empty( $val ) && ! empty( $_POST['percent'][ $key ] ) ) {
					$data[ $val ] = array(
						'days'    => $val,
						'percent' => filter_var( $_POST['percent'][ $key ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					);
				}
			}

			update_post_meta( $post->ID, self::META_KEY_INFO, $data );
		} else {
			delete_post_meta( $post->ID, self::META_KEY_INFO );
		}
	}

	public static function get_days_post_meta( $id ) {
		return get_post_meta( $id, self::META_KEY_INFO, true );
	}

	public static function updateCart( $cartItems ) {
		$orderCookieData = stm_get_rental_order_fields_values();

		if ( $orderCookieData['order_days'] && isset( $cartItems['car_class']['total'] ) && isset( $cartItems['car_class']['id'] ) ) {
			$cartItems['car_class']['total'] = $cartItems['car_class']['total'] - ( $cartItems['car_class']['total'] * self::getPercent( $cartItems['car_class']['id'] ) );
		}

		return $cartItems;
	}

	public static function setVarId( $bool, $productId ) {
		if ( 'product' === get_post_type( $productId ) ) {
			$terms = get_the_terms( $productId, 'product_type' );
			if ( $terms && ( 'simple' === $terms[0]->slug || 'variable' === $terms[0]->slug ) ) {
				self::$varId = apply_filters( 'stm_get_wpml_product_parent_id', $productId );
			}
		}
	}

	public static function updateVariationPriceWithDiscount( $price, $product ) {
		if ( 'car_option' === $product->get_type() ) {
			return $price;
		}

		return ( ! empty( self::getPercent( self::$varId ) ) ) ? $price - ( $price * self::getPercent( self::$varId ) ) : $price;
	}

	public static function getPercent( $varId ) {
		$discounts = self::get_days_post_meta( $varId );

		$orderCookieData = stm_get_rental_order_fields_values();
		if ( '--' !== $orderCookieData['calc_pickup_date'] && '--' !== $orderCookieData['calc_return_date'] ) {
			$date1 = stm_date_create_from_format( $orderCookieData['calc_pickup_date'] );
			$date2 = stm_date_create_from_format( $orderCookieData['calc_return_date'] );

			if ( $date1 instanceof DateTime && $date2 instanceof DateTime ) {

				$diff = $date2->diff( $date1 )->format( '%a.%h' );

				if ( empty( $diff ) ) {
					$diff = 1;
				}

				if ( ! empty( get_post_meta( $varId, 'rental_price_per_hour_info', true ) ) ) {
					$dh    = explode( '.', $diff );
					$dates = $dh[0];
				} else {
					$dates = ceil( $diff );
				}

				if ( ! empty( $discounts ) ) {
					$nearId  = 0;
					$minDays = 0;

					foreach ( $discounts as $k => $val ) {
						if ( ! empty( $k ) ) {
							if ( 0 === $minDays ) {
								if ( ( $dates - $k ) >= 0 ) {
									$minDays = ( $dates - $k );
									$nearId  = $k;
								}
							} else {
								if ( ( (int) ( $dates - $k ) >= 0 ) && ( ( $dates - $k ) <= $minDays ) && ( $dates >= $k ) ) {
									$minDays = ( $dates - $k );
									$nearId  = $k;
								}
							}
						}
					}

					return ( isset( $discounts[ $nearId ] ) ) ? $discounts[ $nearId ]['percent'] / 100 : 0;
				}
			}
		}

		return 0;
	}

	public static function discountByDaysView() {

		$periods = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ), self::META_KEY_INFO, true );

		$disabled = ( (int) get_the_ID() !== (int) apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ) ) ? 'disabled="disabled"' : '';

		?>
		<div class="discount-by-days-wrap">
			<ul class="discount-by-days-list">
				<?php if ( ! empty( $periods ) ) : ?>
					<?php
					$i = 1;
					foreach ( $periods as $k => $val ) :
						?>
						<li>
							<div class="repeat-days-number"><?php echo esc_html( $i ); ?></div>
							<table>
								<tr>
									<td>
										<?php echo esc_html__( 'Days', 'motors' ); ?>
									</td>
									<td>
										<input type="number" min="1" step="1" value="<?php echo esc_attr( $val['days'] ); ?>"
												name="days[]" <?php echo esc_attr( $disabled ); ?>/>
									</td>
									<td>>=</td>
								</tr>
								<tr>
									<td>
										<?php echo esc_html__( 'Discount', 'motors' ); ?>
									</td>
									<td>
										<input type="number" min="0.01" step="0.01" value="<?php echo esc_attr( $val['percent'] ); ?>"
												name="percent[]" <?php echo esc_attr( $disabled ); ?>/>
									</td>
									<td>
										%
									</td>
								</tr>
							</table>
							<div class="btn-wrap">
								<button class="remove-days-fields button-secondary" <?php echo esc_attr( $disabled ); ?>>
									<?php echo esc_html__( 'Remove', 'motors' ); ?>
								</button>
							</div>
						</li>
						<?php
						$i ++;
					endforeach;
					?>
				<?php else : ?>
					<li>
						<div class="repeat-days-number">1</div>
						<table>
							<tr>
								<td>
									<?php echo esc_html__( 'Days', 'motors' ); ?>
								</td>
								<td>
									<input type="number" min="1" step="1" name="days[]" <?php echo esc_attr( $disabled ); ?>/>
								</td>
								<td>>=</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html__( 'Discount', 'motors' ); ?>
								</td>
								<td>
									<input type="number" min="0.01" step="0.01" name="percent[]" <?php echo esc_attr( $disabled ); ?>/>
								</td>
								<td>
									%
								</td>
							</tr>
						</table>
						<div class="btn-wrap">
							<button class="remove-days-fields button-secondary" <?php echo esc_attr( $disabled ); ?>>
								<?php echo esc_html__( 'Remove', 'motors' ); ?>
							</button>
						</div>
					</li>
				<?php endif; ?>
				<li>
					<button class="repeat-days-fields button-primary button-large" <?php echo esc_attr( $disabled ); ?>>
						<?php echo esc_html__( 'Add', 'motors' ); ?>
					</button>
				</li>
			</ul>
			<input type="hidden" name="remove-days"/>
		</div>
		<?php
	}
}

new DiscountByDays();
