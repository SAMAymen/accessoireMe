<?php
class PriceForQuantityDays {
	const META_KEY_INFO   = 'rental_fixed_price_for_days_info';
	private static $varId = 0;

	public function __construct() {
		add_action( 'stm_fixed_price_for_days', array( $this, 'priceByDaysView' ) );
		add_action( 'save_post', array( $this, 'add_fixed_price_post_meta' ), 10, 2 );
		add_filter( 'woocommerce_product_type_query', array( get_class(), 'setVarId' ), 20, 2 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'updateVariationFixedPrice' ), 15, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'updateVariationFixedPrice' ), 15, 2 );
		add_filter( 'stm_cart_items_content', array( $this, 'updateCart' ), 30, 1 );
	}

	public static function setVarId( $bool, $productId ) {
		if ( 'product' === get_post_type( $productId ) ) {
			$terms = get_the_terms( $productId, 'product_type' );
			if ( $terms && ( 'simple' === $terms[0]->slug || 'variable' === $terms[0]->slug ) ) {
				self::$varId = apply_filters( 'stm_get_wpml_product_parent_id', $productId );
			}
		}
	}

	public static function hasFixedPrice( $id, $days = 0 ) {
		$get_fixed_price = self::get_sorted_fixed_price( $id );

		if ( 0 !== $days && ! empty( $get_fixed_price ) ) {
			$price = 0;

			foreach ( $get_fixed_price as $k => $val ) {
				if ( $price ) {
					continue;
				}
				if ( $k <= $days ) {
					$price = $val;
				}
			}

			return ( 0 !== $price ) ? true : false;
		}

		return ( ! empty( $get_fixed_price ) ) ? true : false;
	}

	public static function add_fixed_price_post_meta( $post_id, $post ) {
		if ( isset( $_POST['pfd_days'][0] ) && ! empty( $_POST['pfd_days'][0] ) && isset( $_POST['pfd_price'][0] ) && ! empty( $_POST['pfd_price'][0] ) ) {
			$data = array();

			foreach ( $_POST['pfd_days'] as $key => $val ) {
				if ( ! empty( $val ) && ! empty( $_POST['pfd_price'][ $key ] ) ) {
					$data[ $val ] = array(
						'pfd_days'  => $val,
						'pfd_price' => filter_var( $_POST['pfd_price'][ $key ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					);
				}
			}

			update_post_meta( $post->ID, self::META_KEY_INFO, $data );
		} else {
			delete_post_meta( $post->ID, self::META_KEY_INFO );
		}
	}

	public static function get_fixed_price_post_meta( $id ) {
		return get_post_meta( $id, self::META_KEY_INFO, true );
	}

	public static function get_sorted_fixed_price( $id ) {
		$fixedPrice = get_post_meta( $id, self::META_KEY_INFO, true );

		$forSort = array();
		if ( ! empty( $fixedPrice ) ) {
			foreach ( $fixedPrice as $k => $val ) {
				$forSort[ $val['pfd_days'] ] = $val['pfd_price'];
			}
		}

		ksort( $forSort );

		return $forSort;
	}

	public static function updateCart( $cartItems ) {
		if ( isset( $cartItems['car_class']['id'] ) && ! empty( self::get_fixed_price_post_meta( $cartItems['car_class']['id'] ) ) ) {

			$total_sum  = stm_get_cart_current_total();
			$fields     = stm_get_rental_order_fields_values();
			$cart       = WC()->cart->get_cart();
			$cart_items = array(
				'has_car'      => false,
				'option_total' => 0,
				'options_list' => array(),
				'car_class'    => array(),
				'options'      => array(),
				'total'        => $total_sum,
				'option_ids'   => array(),
				'oldData'      => 0,
			);

			if ( ! empty( $cart ) ) {
				$cartOldData = ( isset( $_GET['order_old_days'] ) && ! empty( intval( $_GET['order_old_days'] ) ) ) ? $_GET['order_old_days'] : 0;

				foreach ( $cart as $cart_item ) {

					$id   = apply_filters( 'stm_get_wpml_product_parent_id', $cart_item['product_id'] );
					$post = $cart_item['data'];

					$buy_type = ( 'WC_Product_Car_Option' === get_class( $cart_item['data'] ) ) ? 'options' : 'car_class';

					if ( 'options' === $buy_type ) {
						$cartItemQuant = $cart_item['quantity'];

						if ( $cartOldData > 0 ) {
							if ( 1 !== $cart_item['quantity'] ) {
								$cartItemQuant = ( $cart_item['quantity'] / $cartOldData );
							} else {
								$cartItemQuant = 1;
							}
						}

						$priceStr = $cart_item['data']->get_data();

						if ( empty( $priceStr['price'] ) ) {
							$priceStr['price'] = 0;
						}

						$total = $cartItemQuant * $priceStr['price'];

						if ( empty( get_post_meta( $cart_item['product_id'], '_car_option', true ) ) ) {
							$total = $cartItemQuant * $priceStr['price'] * $fields['ceil_days'];
						}

						$cart_items['option_total'] += $total;
						$cart_items['option_ids'][]  = $id;

						$cart_items[ $buy_type ][] = array(
							'id'       => $id,
							'quantity' => $cartItemQuant,
							'name'     => $post->get_title(),
							'price'    => $priceStr['price'],
							'total'    => $total,
							'opt_days' => $fields['ceil_days'],
							'subname'  => get_post_meta( $id, 'cars_info', true ),
						);

						$cart_items['options_list'][ $id ] = $post->get_title();
					} else {

						$variation_id = 0;
						if ( ! empty( $cart_item['variation_id'] ) ) {
							$variation_id = apply_filters( 'stm_get_wpml_product_parent_id', $cart_item['variation_id'] );
						}

						if ( isset( $_GET['pickup_location'] ) ) {
							$pickUpLocationMeta = get_post_meta( $id, 'stm_rental_office' );
							if ( ! in_array( $_GET['pickup_location'], explode( ',', $pickUpLocationMeta[0] ), true ) ) {
								WC()->cart->empty_cart();
							}
						}

						$item = $cart_item['data']->get_data();

						if ( empty( $item['price'] ) ) {
							$item['price'] = 0;
						}

						$cart_items[ $buy_type ][] = array(
							'id'             => $id,
							'variation_id'   => $variation_id,
							'quantity'       => $cart_item['quantity'],
							'name'           => $post->get_title(),
							'price'          => $item['price'],
							'total'          => self::getFixedPrice( $cartItems['car_class']['id'] ) * $fields['order_days'],
							'subname'        => get_post_meta( $id, 'cars_info', true ),
							'payment_method' => get_post_meta( $variation_id, '_stm_payment_method', true ),
							'days'           => $fields['order_days'],
							'hours'          => ( isset( $fields['order_hours'] ) ) ? $fields['order_hours'] : 0,
							'ceil_days'      => $fields['ceil_days'],
							'oldData'        => $cartOldData,
						);
						$cart_items['has_car']     = true;
					}
				}

				/*Get only last element*/
				if ( count( $cart_items['car_class'] ) > 1 ) {
					$rent                       = array_pop( $cart_items['car_class'] );
					$cart_items['delete_items'] = $cart_items['car_class'];
					$cart_items['car_class']    = $rent;
				} else {
					if ( ! empty( $cart_items['car_class'] ) ) {
						$cart_items['car_class'] = $cart_items['car_class'][0];
					}
				}

				return $cart_items;
			}
		}
		return $cartItems;
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return float|int|mixed
	 */
	public static function updateVariationFixedPrice( $price, $product ) {
		if ( 'car_option' === $product->get_type() ) {
			return $price;
		}

		$orderCookieData = stm_get_rental_order_fields_values();
		return ( ! empty( self::getFixedPrice( self::$varId ) ) ) ? self::getFixedPrice( self::$varId ) * ( $orderCookieData['order_days'] - PriceForDatePeriod::$countDaysPerdiod ) : $price;
	}

	public static function getFixedPrice( $varId ) {
		$fixedPrice = self::get_sorted_fixed_price( $varId );

		$orderCookieData = stm_get_rental_order_fields_values();
		if ( ! empty( $fixedPrice ) && '--' !== $orderCookieData['calc_pickup_date'] && '--' !== $orderCookieData['calc_return_date'] ) {
			$date1 = stm_date_create_from_format( $orderCookieData['calc_pickup_date'] );
			$date2 = stm_date_create_from_format( $orderCookieData['calc_return_date'] );

			$price = 0;
			if ( $date1 instanceof DateTime && $date2 instanceof DateTime ) {
				$diff = $date2->diff( $date1 )->format( '%a.%h' );

				if ( empty( $diff ) ) {
					$diff = 1;
				}

				$days = ceil( $diff );

				foreach ( $fixedPrice as $k => $val ) {
					if ( $k <= $days ) {
						$price = $val;
					}
				}
			}

			return $price;
		}

		return 0;
	}

	public static function priceByDaysView() {
		$periods = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ), self::META_KEY_INFO, true );

		$disabled = ( get_the_ID() !== apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ) ) ? 'disabled="disabled"' : '';

		?>
		<div class="price-by-days-wrap">
			<ul class="price-by-days-list">
				<?php
				if ( ! empty( $periods ) ) :
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
										<input
												type="number"
												value="<?php echo esc_attr( $val['pfd_days'] ); ?>"
												min="1"
												name="pfd_days[]"
											<?php echo esc_attr( $disabled ); ?>
										/>
									</td>
									<td>>=</td>
								</tr>
								<tr>
									<td>
										<?php echo esc_html__( 'Price', 'motors' ); ?>
									</td>
									<td>
										<input
												type="number"
												value="<?php echo esc_attr( $val['pfd_price'] ); ?>"
												min="0.01"
												step="0.01"
												name="pfd_price[]"
											<?php echo esc_attr( $disabled ); ?>
										/>
									</td>
									<td></td>
								</tr>
							</table>
							<div class="btn-wrap">
								<button class="remove-days-fields button-secondary" <?php echo esc_attr( $disabled ); ?>>
									<?php echo esc_html__( 'Remove', 'motors' ); ?>
								</button>
							</div>
						</li>
						<?php
						$i++;
					endforeach;
				else :
					?>
					<li>
						<div class="repeat-days-number">1</div>
						<table>
							<tr>
								<td>
									<?php echo esc_html__( 'Days', 'motors' ); ?>
								</td>
								<td>
									<input
											type="number"
											min="1"
											name="pfd_days[]"
										<?php echo esc_attr( $disabled ); ?>
									/>
								</td>
								<td>>=</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html__( 'Price', 'motors' ); ?>
								</td>
								<td>
									<input
											type="number"
											name="pfd_price[]"
											min="0.01"
											step="0.01"
										<?php echo esc_attr( $disabled ); ?>
									/>
								</td>
								<td></td>
							</tr>
						</table>
						<div class="btn-wrap">
							<button class="remove-days-fields button-secondary" <?php echo esc_attr( $disabled ); ?>>
								<?php echo esc_html__( 'Remove', 'motors' ); ?>
							</button>
						</div>
					</li>
					<?php
				endif;
				?>
				<li>
					<button class="repeat-fixed-price-fields button-primary button-large" <?php echo esc_attr( $disabled ); ?>>
						<?php echo esc_html__( 'Add', 'motors' ); ?>
					</button>
				</li>
			</ul>
			<input type="hidden" name="remove-days"/>
		</div>
		<?php
	}
}

new PriceForQuantityDays();
