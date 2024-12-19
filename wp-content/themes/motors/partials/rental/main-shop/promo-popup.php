<?php

/**
 * @var $priceDate
 * @var $pricePerHour
 * @var $discount
 * @var $fixedPrice
 * @var $id
 * @var $tax_rates
 * @var $popup_id
 */

extract( $args );

$cart_items = stm_get_cart_items();

$car_rent = $cart_items['car_class'];

?>
<div id="stm-promo-popup-wrap-<?php echo esc_attr( $popup_id ); ?>" class="stm-promo-popup-wrap">
	<div class="stm-promo-popup">
		<div class="stm-table stm-pp-head">
			<div class="stm-pp-row stm-pp-qty heading-font"><?php esc_html_e( 'QTY', 'motors' ); ?></div>
			<div class="stm-pp-row stm-pp-rate heading-font"><?php esc_html_e( 'RATE', 'motors' ); ?></div>
			<div class="stm-pp-row stm-pp-subtotal heading-font"><?php esc_html_e( 'SUBTOTAL', 'motors' ); ?></div>
		</div>

		<!--PRICES BY DAYS PERIOD-->
		<?php
		$total          = 0;
		$priceDateTotal = 0;
		if ( ! empty( $price_date ) ) :
			if ( count( $price_date['simple_price'] ) > 0 && empty( $fixed_price ) ) :
				$total = $total + array_sum( $price_date['simple_price'] );
				?>
				<div class="stm-table stm-pp-by-day">
					<div class="stm-pp-row stm-pp-qty"><?php echo sprintf( esc_html__( '%s Days', 'motors' ), count( $price_date['simple_price'] ) ); ?></div>
					<div class="stm-pp-row stm-pp-rate"><?php echo wp_kses_post( wc_price( $price_date['simple_price'][0] ) ); ?></div>
					<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( array_sum( $price_date['simple_price'] ) ) ); ?></div>
				</div>
				<?php
			endif;

			if ( count( $price_date['promo_price'] ) > 0 ) :
				$total          = $total + array_sum( $price_date['promo_price'] );
				$priceDateTotal = $total;
				$promo_prices   = array_count_values( $price_date['promo_price'] );

				foreach ( $promo_prices as $k => $val ) :
					$summ = $k * $val;
					?>
					<div class="stm-table stm-pp-promo-by-day">
						<div class="stm-pp-row stm-pp-qty"><?php echo sprintf( esc_html__( '%s Days', 'motors' ), esc_html( $val ) ); ?></div>
						<div class="stm-pp-row stm-pp-rate"><?php echo wp_kses_post( wc_price( $k ) ); ?></div>
						<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( $summ ) ); ?></div>
					</div>
					<?php
				endforeach;
			endif;
		endif;

		// FIXED PRICE
		$days       = $fields['order_days'];
		$periodDays = count( $price_date['promo_price'] );

		if ( is_array( $price_date ) && ! empty( $fixed_price ) && ( $days - $periodDays ) !== 0 ) :
			$price = 0;

			if ( is_array( $car_rent ) && isset( $car_rent['price'] ) ) {
				$price = $car_rent['price'];
			}

			foreach ( $fixed_price as $k => $val ) {
				if ( $days >= $k ) {
					$price = $val;
				}
			}
			if ( ! empty( $price_date['promo_price'] ) ) {
				$total      = $price * ( $days - $periodDays );
				$fixedPrice = $total;
			} else {
				$total      = $price * $days;
				$fixedPrice = $total;
			}

			?>
			<div class="stm-table stm-pp-promo-by-day">
				<div class="stm-pp-row stm-pp-qty"><?php echo sprintf( esc_html__( '%s Days', 'motors' ), esc_html( ! empty( $periodDays ) ? $days - $periodDays : $days ) ); ?></div>
				<div class="stm-pp-row stm-pp-rate"><?php echo wp_kses_post( wc_price( $price ) ); ?></div>
				<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( ! empty( $periodDays ) ? $price * ( $days - $periodDays ) : $price * $days ) ); ?></div>
			</div>
			<?php
		endif;
		?>

		<!--PRICE PER HOUR-->
		<?php
		if ( ! empty( $price_per_hour ) ) :
			$total = $total + $fields['order_hours'] * $price_per_hour;

			?>
			<div class="stm-table stm-pp-per-hour">
				<div class="stm-pp-row stm-pp-qty"><?php echo sprintf( esc_html__( '%s Hours', 'motors' ), esc_html( $fields['order_hours'] ) ); ?></div>
				<div class="stm-pp-row stm-pp-rate"><?php echo wp_kses_post( wc_price( $price_per_hour ) ); ?></div>
				<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( $fields['order_hours'] * $price_per_hour ) ); ?></div>
			</div>
			<?php
		endif;
		?>
		<!--Return Location Fee-->
		<?php if ( apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_office_location_fee' ) ) : ?>
			<?php
			if ( 'on' === $fields['return_same'] && apply_filters( 'stm_me_get_nuxy_mod', true, 'enable_fee_for_same_location' ) && ! empty( $fields['pickup_location_fee'] ) ) :
				$total = $total + $fields['pickup_location_fee'];
				?>
			<div class="stm-table stm-pp-fee">
				<div class="stm-pp-row stm-pp-qty"><?php esc_html_e( 'Return Location Fee', 'motors' ); ?></div>
				<div class="stm-pp-row stm-pp-rate"></div>
				<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( $fields['pickup_location_fee'] ) ); ?></div>
			</div>
		<?php endif; ?>
			<?php
			if ( 'off' === $fields['return_same'] && ! empty( $fields['return_location_fee'] ) ) :
				$total = $total + $fields['return_location_fee'];
				?>
			<div class="stm-table stm-pp-fee">
				<div class="stm-pp-row stm-pp-qty"><?php esc_html_e( 'Return Location Fee', 'motors' ); ?></div>
				<div class="stm-pp-row stm-pp-rate"></div>
				<div class="stm-pp-row stm-pp-subtotal"><?php echo wp_kses_post( wc_price( $fields['return_location_fee'] ) ); ?></div>
			</div>
		<?php endif; ?>
		<?php endif; ?>
		<!--DISCOUNT-->
		<?php
		if ( ! empty( $priceDateTotal && ! empty( $fixedPrice ) ) ) {
			$discount_price = $total + $priceDateTotal;
		} else {
			$discount_price = $total;
		}

		if ( ! empty( $discount ) ) :
			$current_discount = 0;
			foreach ( $discount as $k => $val ) {
				if ( $val['days'] <= $fields['order_days'] ) {
					$current_discount = $val['percent'];
				}
			}
			$discount_price = $total - ( ( $total / 100 ) * $current_discount );
			?>
			<div class="stm-table stm-pp-discount">
				<div class="stm-pp-row stm-pp-qty"><?php esc_html_e( 'Discount', 'motors' ); ?></div>
				<div class="stm-pp-row stm-pp-rate"><?php echo esc_html( $current_discount . '%' ); ?></div>
				<div class="stm-pp-row stm-pp-subtotal"><?php echo '- ' . wp_kses_post( wc_price( ( $total / 100 ) * $current_discount ) ); ?></div>
			</div>
		<?php endif; ?>
		<!--TAX && FEES RATES-->
		<?php
		if ( count( $tax_rates ) > 0 ) :
			$tax_fees = 0;
			echo '<div class="stm-pp-tax-margin"></div>';
			foreach ( $tax_rates as $k => $val ) :
				$tax_fees = $tax_fees + ( ( $discount_price / 100 ) * $val['rate'] );
				?>
				<div class="stm-table stm-pp-tax">
					<div class="stm-pp-row stm-pp-qty heading-font"><?php echo esc_html( $val['label'] ); ?></div>
					<div class="stm-pp-row stm-pp-subtotal heading-font"><?php echo wp_kses_post( wc_price( ( $discount_price / 100 ) * $val['rate'] ) ); ?></div>
				</div>
				<?php
			endforeach;
		endif
		?>
		<div class="stm-table-total">
			<div class="stm-pp-total-label heading-font"><?php echo esc_html__( 'Rental Charges Rate', 'motors' ); ?></div>
			<div class="stm-pp-total-price heading-font"><?php echo wp_kses_post( wc_price( $discount_price ) ); ?></div>
		</div>
		<div class="stm-rental-ico-close"
			data-close-id="stm-promo-popup-wrap-<?php echo esc_attr( $popup_id ); ?>"></div>
	</div>
</div>
