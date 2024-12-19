<?php

class PriceForDatePeriod {
	private static $wpdbObj;
	private static $varId           = 0;
	public static $countDaysPerdiod = 0;

	public function __construct() {
		global $wpdb;

		self::$wpdbObj = $wpdb;
		self::createPriceInfoDB();
		add_action( 'stm_date_period', array( $this, 'priceForDateView' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadScriptStyles' ) );
		add_action( 'save_post', array( $this, 'stm_save_customer_note_meta' ), 10, 2 );
		add_filter( 'woocommerce_product_type_query', array( get_class(), 'setVarId' ), 20, 2 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'updateVariationPrice' ), 20, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'updateVariationPrice' ), 20, 2 );
		add_filter( 'stm_cart_items_content', array( $this, 'updateCart' ), 30, 1 );
	}

	public static function hasDatePeriod() {
		return ( ! empty( self::getPeriods() ) ) ? true : false;
	}

	private function createPriceInfoDB() {
		$charset_collate = self::$wpdbObj->get_charset_collate();
		$table_name      = self::$wpdbObj->prefix . 'rental_price_date';

		if ( self::$wpdbObj->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {

			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            variation_id INT NOT NULL,
            starttime INT NOT NULL,
            endtime INT NOT NULL,
            price FLOAT NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		$option = get_option( 'rental_price_date_format_float' );
		if ( ! $option ) {
			$table_name = self::$wpdbObj->prefix . 'rental_price_date';
			$field_name = 'price';
			$new_type   = 'FLOAT';
			$query      = "ALTER TABLE $table_name MODIFY $field_name $new_type;";
			$result     = self::$wpdbObj->query( $query );
			add_option( 'rental_price_date_format_float', true );
		}

	}

	public static function setVarId( $bool, $productId ) {
		if ( 'product' === get_post_type( $productId ) ) {
			$terms = get_the_terms( $productId, 'product_type' );
			if ( $terms && ( 'simple' === $terms[0]->slug || 'variable' === $terms[0]->slug ) ) {
				self::$varId = apply_filters( 'stm_get_wpml_product_parent_id', $productId );
			}
		}
	}

	public static function updateVariationPrice( $price, $product ) {
		if ( empty( $price ) ) {
			return $price;
		}

		if ( 'car_option' === $product->get_type() ) {
			$orderCookieData       = stm_get_rental_order_fields_values();
			$product_id            = $product->get_id();
			$car_option_single_pay = get_post_meta( $product_id, '_car_option', true );
			if ( 'yes' !== $car_option_single_pay ) {
				$price = $price * $orderCookieData['order_days'];
			}
			return $price;
		}

		if ( empty( self::hasDatePeriod() ) && class_exists( 'PriceForQuantityDays' ) && ! empty( PriceForQuantityDays::hasFixedPrice( self::$varId ) ) ) {
			$orderCookieData = stm_get_rental_order_fields_values();
			if ( $orderCookieData['order_days'] > 0 ) {
				$price = $price / $orderCookieData['order_days'];
			}
		}

		$table_name = self::$wpdbObj->prefix . 'rental_price_date';

		$orderCookieData     = stm_get_rental_order_fields_values();
		$newPrice            = 0;
		$is_product_simple   = $product instanceof WC_Product_Simple;
		$is_product_variable = $product instanceof WC_Product_Variable;
		$countPromoDays      = 0;
		$countDays           = $orderCookieData['order_days'];
		$fields              = stm_get_rental_order_fields_values();
		$hours               = $fields['order_hours'];

		if ( 0 !== $orderCookieData['order_days'] ) {
			$varId = apply_filters( 'stm_get_wpml_product_parent_id', self::$varId );

			$endDate = ( isset( $orderCookieData['calc_return_date'] ) && ! empty( $orderCookieData['calc_return_date'] ) ) ? strtotime( $orderCookieData['calc_return_date'] ) : '';

			$dates = stm_get_date_range_with_time( $orderCookieData['calc_pickup_date'], $orderCookieData['calc_return_date'] );

			foreach ( $dates as $k => $date ) {
				$date = strtotime( $date );

				$endDate = ( $date !== $endDate && isset( $dates[ $k + 1 ] ) ) ? strtotime( $dates[ $k + 1 ] ) : $endDate;

				$response = self::$wpdbObj->get_row(
					self::$wpdbObj->prepare(
						"SELECT * FROM {$table_name}
					  WHERE
					  (variation_id = %d) AND
					  (starttime <= %s AND endtime >= %s) ORDER BY id DESC LIMIT 1",
						array( $varId, $date, $date )
					)
				);

				if ( $k <= $countDays ) {
					$price = ( ! empty( $price ) ) ? $price : 0;
					if ( ! empty( $response ) && isset( $response->price ) ) {
						$datePeriodPrice = $response->price;
						$newPrice       += $response->price;
						$countPromoDays++;
					} else {
							$newPrice += ( $is_product_variable || $is_product_simple ) ? 0 : $price;

					}
				}
			}
		}

		if ( 0 === $countDays - $countPromoDays ) {
			$price = 0;
		}

		if ( 0 === $newPrice ) {
			return $price;
		}

		if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_fixed_price_for_days' ) ) {

			if ( $is_product_simple && empty( PriceForQuantityDays::hasFixedPrice( $varId ) ) ) {
				if ( $hours <= 0 && ( ( $countDays - $countPromoDays ) < 0 ) ) {
					$newPrice = $newPrice - $datePeriodPrice;
				} else {
					$newPrice = ( $price * ( $countDays - $countPromoDays ) ) + $newPrice;
				}
			} elseif ( $is_product_variable && empty( PriceForQuantityDays::hasFixedPrice( $varId ) ) ) {
				if ( $hours <= 0 && ( ( $countDays - $countPromoDays ) < 0 ) ) {
					$newPrice = $newPrice - $datePeriodPrice;
				} else {
					$newPrice = ( $price * ( $countDays - $countPromoDays ) ) + $newPrice;
				}
			} else {
				$newPrice = $newPrice + $price;
			}

			return apply_filters( 'rental_variation_price_manipulations', $newPrice );
		} else {

			if ( ( $is_product_variable || $is_product_simple ) ) {
				if ( $hours <= 0 && ( ( $countDays - $countPromoDays ) < 0 ) ) {
					$newPrice = $newPrice - $datePeriodPrice;
				} else {
					$newPrice = ( $price * ( $countDays - $countPromoDays ) ) + $newPrice;
				}
			} else {
				$newPrice = $price + $newPrice;
			}
		}

		return apply_filters( 'rental_variation_price_manipulations', $newPrice );
	}

	public static function getTotalByDays( $price, $varId ) {
		$table_name = self::$wpdbObj->prefix . 'rental_price_date';

		$orderCookieData = stm_get_rental_order_fields_values();
		if ( '0' === $orderCookieData['order_days'] && '0' === $orderCookieData['ceil_days'] ) {
			return 0;
		}

		$endDate = ( isset( $orderCookieData['calc_return_date'] ) && ! empty( $orderCookieData['calc_return_date'] ) ) ? strtotime( $orderCookieData['calc_return_date'] ) : '';

		$dates = stm_get_date_range_with_time( $orderCookieData['calc_pickup_date'], $orderCookieData['calc_return_date'] );

		$newPrice = 0;

		$countDays = $orderCookieData['order_days'];

		foreach ( $dates as $k => $date ) {
			$date = strtotime( $date );

			$endDate = ( $date !== $endDate && isset( $dates[ $k + 1 ] ) ) ? strtotime( $dates[ $k + 1 ] ) : $endDate;

			$response = self::$wpdbObj->get_row(
				self::$wpdbObj->prepare(
					"SELECT * FROM {$table_name}
                          WHERE
                          (variation_id = %d) AND
                          (starttime BETWEEN %s AND %s OR
                          endtime BETWEEN %s AND %s OR
                          (starttime <= %s AND endtime >= %s)) ORDER BY id DESC LIMIT 1",
					array( $varId, $date, $endDate, $date, $endDate, $date, $endDate )
				)
			);

			if ( $k < $countDays ) {
				$price     = ( ! empty( $price ) ) ? $price : 0;
				$newPrice += ( ! empty( $response ) && isset( $response->price ) ) ? $response->price : $price;
			}
		}

		return ( 0 === $newPrice ) ? $price : $newPrice;
	}

	public static function getDescribeTotalByDays( $price, $varId ) {
		$table_name      = self::$wpdbObj->prefix . 'rental_price_date';
		$orderCookieData = stm_get_rental_order_fields_values();
		$endDate         = ( isset( $orderCookieData['calc_return_date'] ) && ! empty( $orderCookieData['calc_return_date'] ) ) ? strtotime( $orderCookieData['calc_return_date'] ) : '';
		$dates           = stm_get_date_range_with_time( $orderCookieData['calc_pickup_date'], $orderCookieData['calc_return_date'] );

		$priceDescribe = array(
			'simple_price' => array(),
			'promo_price'  => array(),
		);

		$countDays = $orderCookieData['order_days'];

		foreach ( $dates as $k => $date ) {
			$date = strtotime( $date );

			$endDate = ( $date !== $endDate && isset( $dates[ $k + 1 ] ) ) ? strtotime( $dates[ $k + 1 ] ) : $endDate;

			$response = self::$wpdbObj->get_row(
				self::$wpdbObj->prepare(
					"SELECT * FROM {$table_name}
                          WHERE
                          (variation_id = %d) AND
                          (starttime BETWEEN %s AND %s OR
                          endtime BETWEEN %s AND %s OR
                          (starttime <= %s AND endtime >= %s)) ORDER BY id DESC LIMIT 1",
					array( $varId, $date, $date, $date, $date, $date, $date )
				)
			);

			if ( $k < $countDays ) {

				if ( ! empty( $response ) && isset( $response->price ) ) {
					$priceDescribe['promo_price'][] = $response->price;
				} else {
					$priceDescribe['simple_price'][] = $price;
				}
			}
			self::$countDaysPerdiod = count( $priceDescribe['promo_price'] );
		}

		return $priceDescribe;
	}

	public static function updateCart( $cartItems ) {

		if ( isset( $cartItems['car_class']['id'] ) && ! empty( self::getPeriods( $cartItems['car_class']['id'] ) ) ) {

			$total_sum = stm_get_cart_current_total();
			$fields    = stm_get_rental_order_fields_values();
			$cart      = WC()->cart->get_cart();

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
							'total'          => self::getTotalByDays( $item['price'], apply_filters( 'stm_get_wpml_product_parent_id', $item['parent_id'] ) ),
							'subname'        => get_post_meta( $id, 'cars_info', true ),
							'payment_method' => get_post_meta( $variation_id, '_stm_payment_method', true ),
							'days'           => $fields['order_days'],
							'hours'          => ( isset( $fields['order_hours'] ) ) ? $fields['order_hours'] : 0,
							'ceil_days'      => $fields['ceil_days'],
							'oldData'        => $cartOldData,
						);

						$cart_items['has_car'] = true;
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

	public static function getPeriods( $varId = '' ) {
		$id     = ( ! empty( $varId ) ) ? $varId : apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() );
		$result = ( $id ) ? self::$wpdbObj->get_results( 'SELECT * FROM ' . self::$wpdbObj->prefix . "rental_price_date WHERE `variation_id` = {$id} ORDER BY id ASC" ) : '';

		return $result;
	}

	public static function addPeriodIntoDB( $varId, $datePickup, $dateDrop, $price ) {
		$table_name = self::$wpdbObj->prefix . 'rental_price_date';

		if ( ! is_null( self::checkEntry( $datePickup, $dateDrop, $varId ) ) ) {
			self::$wpdbObj->update(
				$table_name,
				array(
					'variation_id' => $varId,
					'starttime'    => strtotime( $datePickup ),
					'endtime'      => strtotime( $dateDrop ),
					'price'        => $price,
				),
				array(
					'variation_id' => $varId,
					'starttime'    => strtotime( $datePickup ),
					'endtime'      => strtotime( $dateDrop ),
				),
				array( '%d', '%d', '%d', '%f' ),
				array( '%d', '%d', '%d' )
			);
		} else {
			self::$wpdbObj->insert(
				$table_name,
				array(
					'variation_id' => $varId,
					'starttime'    => strtotime( $datePickup ),
					'endtime'      => strtotime( $dateDrop ),
					'price'        => $price,
				),
				array( '%d', '%d', '%d', '%f' )
			);
		}
	}

	public static function deleteEntry( $ids ) {
		$table_name = self::$wpdbObj->prefix . 'rental_price_date';
		foreach ( explode( ',', $ids ) as $item ) {
			self::$wpdbObj->delete( $table_name, array( 'id' => $item ) );
		}
	}

	public static function checkEntry( $startTime, $endTime, $varId ) {
		$startTime = strtotime( $startTime );
		$endTime   = strtotime( $endTime );
		$result    = self::$wpdbObj->get_var( self::$wpdbObj->prepare( 'SELECT id FROM ' . self::$wpdbObj->prefix . 'rental_price_date WHERE `variation_id` = %d AND `starttime` = %s AND `endtime` = %s', array( $varId, $startTime, $endTime ) ) );

		if ( ! empty( $result ) ) {
			return $result;
		}

		return null;
	}

	public static function loadScriptStyles() {
		wp_enqueue_script( 'rental-price-js', get_template_directory_uri() . '/inc/rental/assets/js/rental-price.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_style( 'rental-price-styles', get_template_directory_uri() . '/inc/rental/assets/css/rental-price.css', array(), get_bloginfo( 'version' ), 'all' );
	}

	public static function stm_save_customer_note_meta( $post_id, $post ) {

		if ( 'product' !== $post->post_type ) {
			return;
		}

		if ( isset( $_POST['remove-date'] ) && ! empty( $_POST['remove-date'] ) ) {
			self::deleteEntry( sanitize_text_field( $_POST['remove-date'] ) );
		}

		if ( isset( $_POST['date-pickup'] ) && isset( $_POST['date-drop'] ) && isset( $_POST['date-price'] ) ) {
			$pickup_date_count = count( $_POST['date-pickup'] );
			for ( $q = 0; $q < $pickup_date_count; $q++ ) {
				if ( isset( $_POST['date-pickup'][ $q ] ) && isset( $_POST['date-drop'][ $q ] ) && isset( $_POST['date-price'][ $q ] ) && ! empty( $_POST['date-pickup'][ $q ] ) && ! empty( $_POST['date-drop'][ $q ] ) && ! empty( $_POST['date-price'][ $q ] ) ) {
					self::addPeriodIntoDB( $post->ID, wp_slash( $_POST['date-pickup'][ $q ] ), wp_slash( $_POST['date-drop'][ $q ] ), sanitize_text_field( $_POST['date-price'][ $q ] ) );
				}
			}
		}
	}

	/*get price for date period*/
	public static function getVariationPriceView( $carId ) {
		$table_name = self::$wpdbObj->prefix . 'rental_price_date';

		$orderCookieData = stm_get_rental_order_fields_values();

		$varId     = $carId;
		$startDate = ( isset( $orderCookieData['calc_pickup_date'] ) && ! empty( $orderCookieData['calc_pickup_date'] ) ) ? strtotime( $orderCookieData['calc_pickup_date'] ) : '';
		$endDate   = ( isset( $orderCookieData['calc_return_date'] ) && ! empty( $orderCookieData['calc_return_date'] ) ) ? strtotime( $orderCookieData['calc_return_date'] ) : '';
		$orderDays = ( isset( $orderCookieData['order_days'] ) && ! empty( $orderCookieData['order_days'] ) ) ? $orderCookieData['order_days'] : '';

		if ( ! empty( $startDate ) && ! empty( $endDate ) ) {
			$request = "SELECT * FROM {$table_name}
          WHERE
          (variation_id = {$varId}) AND
          (starttime BETWEEN {$startDate} AND {$endDate} OR
          endtime BETWEEN {$startDate} AND {$endDate} OR
          (starttime <= {$startDate} AND endtime >= {$endDate})) ORDER BY id ASC";

			$response = self::$wpdbObj->get_results( $request );

			return $response;
		}

		return '';
	}

	public static function priceForDateView() {
		$periods = self::getPeriods();

		$disabled = ( get_the_ID() !== apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ) ) ? 'disabled="disabled"' : '';

		?>
		<div class="rental-price-date-wrap">
			<ul class="rental-price-date-list">
				<?php
				if ( ! empty( $periods ) ) :
					foreach ( $periods as $k => $val ) :
						?>
						<li>
							<div class="repeat-number"><?php echo esc_html( $k + 1 ); ?></div>
							<table>
								<tr>
									<td>
										<?php echo esc_html__( 'Start Date', 'motors' ); ?>
									</td>
									<td>
										<input type="text" class="date-pickup"
											value="<?php echo esc_html( gmdate( 'Y/m/d', $val->starttime ) ); ?>"
											name="date-pickup[]" <?php echo esc_attr( $disabled ); ?> />
									</td>
								</tr>
								<tr>
									<td>
										<?php echo esc_html__( 'End Date', 'motors' ); ?>
									</td>
									<td>
										<input type="text" class="date-drop"
											value="<?php echo esc_html( date( 'Y/m/d', $val->endtime ) ); ?>"
											name="date-drop[]" <?php echo esc_attr( $disabled ); ?> />
									</td>
								</tr>
								<tr>
									<td>
										<?php echo esc_html__( 'Price', 'motors' ); ?>
									</td>
									<td>
										<input type="text" value="<?php echo esc_attr( $val->price ); ?>"
											name="date-price[]" <?php echo esc_attr( $disabled ); ?> />
									</td>
								</tr>
							</table>
							<div class="btn-wrap">
								<button class="remove-fields button-secondary"
										data-remove="<?php echo esc_attr( $val->id ); ?>" <?php echo esc_attr( $disabled ); ?>>
									<?php echo esc_html__( 'Remove', 'motors' ); ?>
								</button>
							</div>
						</li>
						<?php
					endforeach;
				else :
					?>
					<li>
						<div class="repeat-number">1</div>
						<table>
							<tr>
								<td>
									<?php echo esc_html__( 'Start Date', 'motors' ); ?>
								</td>
								<td>
									<input type="text" class="date-pickup" name="date-pickup[]"/>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html__( 'End Date', 'motors' ); ?>
								</td>
								<td>
									<input type="text" class="date-drop" name="date-drop[]"/>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html__( 'Price', 'motors' ); ?>
								</td>
								<td>
									<input type="text" name="date-price[]"/>
								</td>
							</tr>
						</table>
						<div class="btn-wrap">
							<button class="remove-fields button-secondary">
								<?php echo esc_html__( 'Remove', 'motors' ); ?>
							</button>
						</div>
					</li>
					<?php
				endif;
				?>
				<li>
					<button class="repeat-fields button-primary button-large" <?php echo esc_attr( $disabled ); ?>>
						<?php echo esc_html__( 'Add', 'motors' ); ?>
					</button>
				</li>
			</ul>
			<input type="hidden" name="remove-date"/>
		</div>
		<?php
	}
}

new PriceForDatePeriod();
