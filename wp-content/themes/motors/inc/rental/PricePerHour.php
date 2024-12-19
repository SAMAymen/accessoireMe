<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 6/12/2018
 * Time: 5:52 PM
 */

class PricePerHour {
	const META_KEY_INFO   = 'rental_price_per_hour_info';
	private static $varId = 0;

	public function __construct() {
		do_action( 'stm_rental_meta_box' );
		add_action( 'save_post', array( get_class(), 'add_price_per_day_post_meta' ), 10, 2 );
		add_filter( 'woocommerce_product_type_query', array( get_class(), 'setVarId' ), 10, 2 );
		add_filter( 'woocommerce_product_get_price', array( get_class(), 'setVarPrice' ), 30, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( get_class(), 'setVarPrice' ), 30, 2 );
		add_filter( 'stm_rental_date_values', array( get_class(), 'updateDaysAndHour' ), 10, 1 );
		add_filter( 'stm_cart_items_content', array( get_class(), 'updateCart' ), 40, 1 );
	}

	public static function hasPerHour() {
		$pricePerHour = self::getPricePerHour( self::$varId );

		return ( ! empty( $pricePerHour ) ) ? true : false;
	}

	public static function add_price_per_day_post_meta( $post_id, $post ) {
		if ( isset( $_POST['price-per-hour'] ) && ! empty( $_POST['price-per-hour'] ) ) {
			update_post_meta( $post->ID, self::META_KEY_INFO, filter_var( $_POST['price-per-hour'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) );
		} else {
			delete_post_meta( $post->ID, self::META_KEY_INFO );
		}
	}

	public static function setVarId( $bool, $productId ) {
		if ( 'product' === get_post_type( $productId ) ) {
			$terms = get_the_terms( $productId, 'product_type' );
			if ( isset( $terms[0]->slug ) && ( 'simple' === $terms[0]->slug || 'variable' === $terms[0]->slug ) ) {
				self::$varId = apply_filters( 'stm_get_wpml_product_parent_id', $productId );
			}
		}
	}

	public static function setVarPrice( $price, $product ) {

		if ( 'car_option' === $product->get_type() ) {
			return $price;
		}
		if ( ! empty( $product->get_data() ) ) {

			$pId = 'variation' === $product->get_type() ? $product->get_parent_id() : $product->get_id();

			self::$varId         = apply_filters( 'stm_get_wpml_product_parent_id', $pId );
			$orderCookieData     = stm_get_rental_order_fields_values();
			$pricePerHour        = floatval( self::getPricePerHour( self::$varId ) );
			$fixedPrice          = 0;
			$is_product_simple   = $product instanceof WC_Product_Simple;
			$is_product_variable = $product instanceof WC_Product_Variable;

			$simplePrice = ! empty( $product->get_sale_price() ) ? $product->get_sale_price() : $product->get_regular_price();

			//to show price if date and time not selected
			if ( empty( $orderCookieData['order_days'] ) && empty( $orderCookieData['order_hours'] ) ) {
				$orderCookieData['order_days'] = 1;
			}

			if ( class_exists( 'PriceForDatePeriod' ) ) {
				$DaysPeriod      = PriceForDatePeriod::getDescribeTotalByDays( $price, self::$varId );
				$countDaysPeriod = count( $DaysPeriod['promo_price'] );
				$daysPeriodPrice = array_sum( $DaysPeriod['promo_price'] );
			}

			if ( class_exists( 'PriceForQuantityDays' ) ) {
				$fixedPrice = PriceForQuantityDays::getFixedPrice( self::$varId );
				$fixedPrice = $fixedPrice * ( $orderCookieData['order_days'] - $countDaysPeriod );
			}

			if ( ! empty( $fixedPrice ) ) {
				$price = ( ! empty( $daysPeriodPrice ) ) ? $fixedPrice + $daysPeriodPrice : $fixedPrice;
			}

			if ( empty( $fixedPrice ) && empty( $daysPeriodPrice ) && ! empty( $price ) ) {
				$price = $price * $orderCookieData['order_days'] + ( $orderCookieData['order_hours'] * $pricePerHour );
			}

			if ( ! empty( $pricePerHour ) && ! empty( $daysPeriodPrice ) && empty( $fixedPrice ) ) {
				$price = $price + ( $orderCookieData['order_hours'] * $pricePerHour );
			}

			if ( ! empty( $DaysPeriod ) && ( ! empty( $pricePerHour ) && 0 !== $orderCookieData['order_hours'] ) && ! empty( $simplePrice ) && $is_product_simple && empty( $fixedPrice ) ) {
				$price = $daysPeriodPrice + ( ( $orderCookieData['order_days'] - $countDaysPeriod ) * $simplePrice ) + ( $orderCookieData['order_hours'] * $pricePerHour );
			}

			if ( ! empty( $price ) ) {
				if ( class_exists( 'PriceForQuantityDays' ) ) {
					$fixedPriceMeta = PriceForQuantityDays::get_fixed_price_post_meta( self::$varId );
					if ( is_array( $fixedPriceMeta ) ) {
						$fixedDays = 0;
						foreach ( $fixedPriceMeta as $meta ) {
							$fixedDays = $meta['pfd_days'];
						}
						if ( ( $fixedDays < $orderCookieData['order_days'] ) || ( ! empty( $fixedPrice ) && ! empty( $pricePerHour ) ) ) {
							$price = $price + ( $orderCookieData['order_hours'] * $pricePerHour );
						} else {
							$price = $price + ( $orderCookieData['order_hours'] * $pricePerHour );
						}
					}
				}
			}
		}
		return $price;
	}

	public static function getPricePerHour( $varId ) {
		return get_post_meta( $varId, self::META_KEY_INFO, true );
	}

	public static function updateDaysAndHour( $data ) {
		$pickupDate = $data['calc_pickup_date'];
		$returnDate = $data['calc_return_date'];

		if ( '--' !== $pickupDate && '--' !== $returnDate ) {
			$date1 = stm_date_create_from_format( $pickupDate );
			$date2 = stm_date_create_from_format( $returnDate );

			if ( $date1 instanceof DateTime && $date2 instanceof DateTime ) {
				$diff = $date2->diff( $date1 )->format( '%a.%h' );
				$diff = explode( '.', $diff );

				$data['order_days'] = (int) $diff[0];

				$pricePerHour = floatval( self::getPricePerHour( self::$varId ) );
				$hoursDiff    = intval( $diff[1] );
				if ( 0 === $data['order_hours'] && 0 === $hoursDiff ) {
					$data['order_days'] = (int) $diff[0];
					$data['ceil_days']  = (int) $diff[0];
				} else {
					$data['order_days'] = (int) $diff[0] + 1;
					$data['ceil_days']  = (int) $diff[0] + 1;
				}

				if ( isset( $diff[1] ) && 0 !== (int) $diff[1] && ! empty( $pricePerHour ) ) {
					$data['order_days'] = (int) $diff[0];
					$data['ceil_days']  = (int) $diff[0];
				}

				if ( ! empty( self::getPricePerHour( self::$varId ) ) ) {
					$data['order_hours'] = (int) $diff[1];
				}
			}
		}

		return $data;
	}

	public static function updateCart( $cartItems ) {
		if ( isset( $cartItems['car_class']['total'] ) && isset( $cartItems['car_class']['id'] ) ) {
			$orderCookieData = stm_get_rental_order_fields_values();
			$pId             = $cartItems['car_class']['id'];
			$pricePerHour    = self::getPricePerHour( $pId );

			if ( isset( $orderCookieData['order_hours'] ) && $orderCookieData['order_hours'] && ! empty( $pricePerHour ) ) {
				$cartItems['car_class']['total'] = ( ! empty( $cartItems['car_class']['days'] ) ) ? $cartItems['car_class']['total'] + ( $orderCookieData['order_hours'] * $pricePerHour ) : ( $orderCookieData['order_hours'] * $pricePerHour );
				$cartItems['car_class']['hours'] = $orderCookieData['order_hours'];

				if ( ! $orderCookieData['order_days'] ) {
					$cartItems['total'] = wc_price( $cartItems['car_class']['total'] );
				}
			}
		}

		return $cartItems;
	}

	public static function pricePerHourView() {
		$price = get_post_meta( apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ), self::META_KEY_INFO, true );

		$disabled = ( (int) get_the_ID() !== (int) apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() ) ) ? 'disabled="disabled"' : '';

		?>
		<div class="admin-rent-info-wrap">
			<ul class="stm-rent-nav-tabs">
				<li>
					<a class="stm-nav-link active" data-id="price-per-hour"><?php echo esc_html__( 'Price Per Hour', 'motors' ); ?></a>
				</li>
				<li>
					<a class="stm-nav-link" data-id="discount-by-days">
						<?php echo esc_html( apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_fixed_price_for_days' ) ? __( 'Fixed Price By Quantity Days', 'motors' ) : __( 'Discount By Days', 'motors' ) ); ?>
					</a>
				</li>
				<li>
					<a class="stm-nav-link" data-id="price-date-period"><?php echo esc_html__( 'Price For Date Period', 'motors' ); ?></a>
				</li>
			</ul>
			<div class="stm-tabs-content">
				<div class="tab-pane show active" id="price-per-hour">
					<div class="price-per-hour-wrap">
						<div class="price-per-hour-input">
							<?php echo esc_html__( 'Price', 'motors' ); ?> <input type="number" value="<?php echo esc_attr( $price ); ?>" <?php echo esc_attr( $disabled ); ?> min="0.01" step="0.01" name="price-per-hour" />
						</div>
					</div>
				</div>
				<div class="tab-pane" id="discount-by-days">
					<?php
					if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_fixed_price_for_days' ) ) {
						do_action( 'stm_fixed_price_for_days' );
					} else {
						do_action( 'stm_disc_by_days' );
					}
					?>
				</div>
				<div class="tab-pane" id="price-date-period">
					<?php do_action( 'stm_date_period' ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

new PricePerHour();
