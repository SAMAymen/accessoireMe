<?php
$product_id      = apply_filters( 'stm_get_wpml_product_parent_id', get_the_ID() );
$product         = wc_get_product( $product_id );
$product_type    = 'default';
$cart_items      = stm_get_cart_items();
$car_rent        = $cart_items['car_class'];
$orderCookieData = stm_get_rental_order_fields_values();
$startDate       = $orderCookieData['pickup_date'];
$endDate         = $orderCookieData['return_date'];
$empty_days      = '';


if ( ! empty( $product ) ) :
	if ( $product->is_type( 'variable' ) ) :
		$variations = $product->get_available_variations();
		$prices     = array();

		$fields = stm_get_rental_order_fields_values();
		//to show price if date and time not selected
		if ( ( empty( $fields['order_days'] ) && ( empty( $fields['order_hours'] ) ) && $startDate !== $endDate ) ) {
			$fields['order_days'] = 1;
		}
		if ( ! empty( $variations ) ) {
			$max_price = 0;
			$i         = 0;
			foreach ( $variations as $variation ) {

				if ( ( ! empty( $variation['display_price'] ) || ! empty( $variation['display_regular_price'] ) ) && ! empty( $variation['variation_description'] ) ) {
					$gets = array(
						'add-to-cart'  => $product_id,
						'product_id'   => $product_id,
						'variation_id' => $variation['variation_id'],
					);

					foreach ( $variation['attributes'] as $key => $val ) {
						$gets[ $key ] = $val;
					}

					$url = apply_filters( 'stm_is_rental_one_elementor', false ) ? add_query_arg( $gets, wc_get_cart_url() ) : add_query_arg( $gets, get_permalink( $product_id ) );

					$total_price = false;
					if ( ! empty( $fields['order_days'] ) ) {
						$total_price = ( ! empty( $variation['display_price'] ) ) ? $variation['display_price'] : $variation['display_regular_price'];
					}

					if ( class_exists( 'PriceForQuantityDays' ) && PriceForQuantityDays::hasFixedPrice( $product_id ) && PriceForDatePeriod::hasDatePeriod() === false ) {
						$total_price = PriceForQuantityDays::getFixedPrice( $product_id ) * $orderCookieData['order_days'];
						if ( class_exists( 'PricePerHour' ) && ! empty( PricePerHour::getPricePerHour( $product_id ) ) ) {
							$total_price = $total_price + ( PricePerHour::getPricePerHour( $product_id ) * $fields['order_hours'] );
						}
					}

					if ( ! empty( $total_price ) ) {
						if ( $max_price < $total_price ) {
							$max_price = $total_price;
						}
					}

					$prices[] = array(
						'price'  => stm_get_default_variable_price( $variation['variation_id'] ),
						'text'   => $variation['variation_description'],
						'total'  => $total_price,
						'url'    => $url,
						'var_id' => $variation['variation_id'],
					);
				}

				$i++;
			}
		}

		if ( ! empty( $prices ) ) : ?>
			<div class="stm_rent_prices">
				<?php foreach ( $prices as $price ) : ?>
					<div class="stm_rent_price">
						<div class="total heading-font">
							<?php
							if ( ! empty( $price['total'] ) ) {
								echo sprintf( esc_html__( '%s/Total', 'motors' ), wp_kses_post( wc_price( $price['total'] ) ) );
							} elseif ( empty( $price['total'] ) && ! empty( PricePerHour::getPricePerHour( $product_id ) ) ) {
								$pricePerHour = PricePerHour::getPricePerHour( $product_id ) * $fields['order_hours'];
								echo sprintf( esc_html__( '%s/Total', 'motors' ), wp_kses_post( wc_price( $pricePerHour ) ) );
							}
							?>
						</div>
						<div class="period">
							<?php
								$popup_id = str_replace( array( '.', ',' ), '', $price['var_id'] ) . '-' . $product_id;
							if ( ! empty( $price['price'] ) ) {
								if ( PricePerHour::hasPerHour() || PriceForDatePeriod::hasDatePeriod() || ( class_exists( 'DiscountByDays' ) && DiscountByDays::hasDiscount( $product_id ) ) || ( class_exists( 'PriceForQuantityDays' ) && PriceForQuantityDays::hasFixedPrice( $product_id, $fields['order_days'] ) ) ) {
									echo '<div class="stm-show-rent-promo-info" data-popup-id="stm-promo-popup-wrap-' . esc_attr( $popup_id ) . '">';
									$order_hours = ( empty( $fields['order_hours'] ) ) ? 0 : $fields['order_hours'];
									echo sprintf( esc_html__( '%1$s Days / %2$s Hours', 'motors' ), esc_html( $fields['order_days'] ), esc_html( $order_hours ) );
									echo '</div>';
									stm_get_popup_promo_price( $popup_id, $product_id, $price['price'], $fields );
								} else {
									echo sprintf( esc_html__( '%s/Day', 'motors' ), wp_kses_post( wc_price( $price['price'] ) ) );
								}
							}

							?>
						</div>
						<div class="pay">
							<a class="heading-font" href="<?php echo esc_url( $price['url'] ); ?>">
							<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', wp_strip_all_tags( $price['text'] ), wp_strip_all_tags( $price['text'] ) . ' button title' ) ); ?>
						</a>
						</div>
						<?php if ( ! empty( $max_price ) && $price['total'] < $max_price ) : ?>
							<div class="stm_discount"><?php echo sprintf( esc_html__( 'Saves you %s', 'motors' ), wp_kses_post( wc_price( $max_price - $price['total'] ) ) ); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php
	else :
		$prod   = $product->get_data();
		$price  = ( empty( $prod['sale_price'] ) ) ? $prod['price'] : $prod['sale_price'];
		$fields = stm_get_rental_order_fields_values();

		$gets = array(
			'add-to-cart' => $product_id,
			'product_id'  => $product_id,
		);

		$price_for_date_period = PriceForDatePeriod::getDescribeTotalByDays( $price, $product_id );
		$fixed_price           = 0;
		$pricePerHour          = PricePerHour::getPricePerHour( $product_id );

		$total_price = false;
		//to show price if date and time not selected
		if ( ( empty( $fields['order_days'] ) && ( empty( $fields['order_hours'] ) ) && $startDate !== $endDate ) ) {
			$fields['order_days'] = 1;
			$empty_days           = true;
		}

		if ( ! empty( $fields['order_days'] ) ) {
			$total_price = $product->get_price();
		}

		if ( ! empty( $fields['order_hours'] && empty( $fields['order_days'] ) ) ) {
			$total_price = $pricePerHour * $fields['order_hours'];
		}

		$url = apply_filters( 'stm_is_rental_one_elementor', false ) ? add_query_arg( $gets, wc_get_cart_url() ) : add_query_arg( $gets, get_permalink( $product_id ) );
		if ( ! empty( $total_price ) && $url ) :
			?>
			<div class="stm_rent_prices">
				<div class="stm_rent_price">
					<div class="total heading-font">
						<?php
							echo sprintf( esc_html__( '%s/Total', 'motors' ), wp_kses_post( wc_price( $total_price ) ) );
						?>
					</div>
					<div class="period">
						<?php
						$popup_id = str_replace( array( '.', ',' ), '', $price ) . '-' . $product_id;
						if ( ! empty( $total_price ) ) {
							if ( ( true !== $empty_days ) && ( PricePerHour::hasPerHour() || PriceForDatePeriod::hasDatePeriod() || ( class_exists( 'DiscountByDays' ) && DiscountByDays::hasDiscount( $product_id ) ) || ( class_exists( 'PriceForQuantityDays' ) && PriceForQuantityDays::hasFixedPrice( $product_id, $fields['order_days'] ) ) ) ) {
								echo '<div class="stm-show-rent-promo-info" data-popup-id="stm-promo-popup-wrap-' . esc_attr( $popup_id ) . '">';
								$order_hours = ( empty( $fields['order_hours'] ) ) ? 0 : $fields['order_hours'];
								echo sprintf( esc_html__( '%1$s Days / %2$s Hours', 'motors' ), esc_html( $fields['order_days'] ), esc_html( $order_hours ) );
								echo '</div>';

								stm_get_popup_promo_price( $popup_id, $product_id, $price, $fields );
							} else {
								echo sprintf( esc_html__( '%s/Day', 'motors' ), wp_kses_post( wc_price( $price ) ) );
							}
						}
						?>
					</div>
					<div class="pay">
						<a class="heading-font" href="<?php echo esc_url( $url ); ?>">
							<?php esc_html_e( 'Pay now', 'motors' ); ?>
						</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
