<?php
$currency_symbol = apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) );
$price           = get_post_meta( get_the_id(), 'price', true );
$sale_price      = get_post_meta( get_the_id(), 'sale_price', true );

if ( ! empty( $sale_price ) ) {
	$price = apply_filters( 'get_conver_price', $sale_price );
} elseif ( ! empty( $price ) ) {
	$price = apply_filters( 'get_conver_price', $price );
} else {
	$price = 0;
}

/*Calculator default*/
$interest                     = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_interest_rate' );
$period                       = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_month_period' );
$down_payment                 = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_down_payment' );
$default_down_payment_type    = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_down_payment_type' );
$default_down_payment_percent = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_down_payment_percent' );

if ( 'percent' === $default_down_payment_type ) {
	if ( $default_down_payment_percent > 100 ) {
		$default_down_payment_percent = 100;
	}

	$down_payment = $price * ( $default_down_payment_percent / 100 );
}

if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_calculator' ) ) {
	?>
	<?php if ( ! apply_filters( 'stm_is_magazine', true ) ) : ?>

<div class="stm_auto_loan_calculator novo">
	<div class="title">
		<i class="stm-icon-calculator"></i>
		<h5><?php esc_html_e( 'Financing calculator', 'motors' ); ?></h5>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3 col-sm-6 stm_changeable_breakpoint">
					<!--Amount-->
					<div class="form-group">
						<div class="labeled"><?php esc_html_e( 'Vehicle price', 'motors' ); ?> <span class="orange">(<?php echo wp_kses_post( $currency_symbol ); ?>)</span></div>
						<input type="text" class="numbersOnly vehicle_price" value="<?php echo esc_attr( $price ); ?>"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 stm_changeable_breakpoint">
					<!--Interest rate-->
					<div class="form-group md-mg-rt">
						<div class="labeled"><?php esc_html_e( 'Interest rate', 'motors' ); ?> <span class="orange">(%)</span></div>
						<input type="text" class="numbersOnly interest_rate" value="<?php echo esc_attr( $interest ); ?>" />
					</div>
				</div>
				<div class="col-md-3 col-sm-6 stm_changeable_breakpoint">
					<!--Period-->
					<div class="form-group md-mg-lt">
						<div class="labeled"><?php esc_html_e( 'Period', 'motors' ); ?> <span class="orange">(<?php esc_html_e( 'month', 'motors' ); ?>)</span></div>
						<input type="text" class="numbersOnly period_month" value="<?php echo esc_attr( $period ); ?>"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 stm_changeable_breakpoint">
					<!--Down Payment-->
					<div class="form-group">
						<div class="labeled"><?php esc_html_e( 'Down Payment', 'motors' ); ?> <span class="orange">(<?php echo wp_kses_post( $currency_symbol ); ?>)</span></div>
						<input type="text" class="numbersOnly down_payment" value="<?php echo esc_attr( $down_payment ); ?>"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-3 stm_changeable_breakpoint">
					<a href="#" class="button button-sm calculate_loan_payment dp-in">
						<?php esc_html_e( 'Calculate', 'motors' ); ?>
					</a>
				</div>
				<div class="col-sm-9 stm_changeable_breakpoint">
					<div class="stm_calculator_results">
						<div class="result">
							<div class="stm-calc-label"><?php esc_html_e( 'Monthly Payment', 'motors' ); ?></div>
							<div class="monthly_payment h5"></div>
						</div>
						<div class="result">
							<div class="stm-calc-label"><?php esc_html_e( 'Total Interest Payment', 'motors' ); ?></div>
							<div class="total_interest_payment h5"></div>
						</div>
						<div class="result">
							<div class="stm-calc-label"><?php esc_html_e( 'Total Amount to Pay', 'motors' ); ?></div>
							<div class="total_amount_to_pay h5"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="calculator-alert alert alert-danger"></div>

		</div>
	</div>
</div>

<script>
	(function($) {
		"use strict";

		$(document).ready(function () {
			var vehicle_price;
			var interest_rate;
			var down_payment;
			var period_month;

			var stmCurrency = "<?php echo esc_js( apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) ) ); ?>";
			var stmPriceDel = "<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', ' ', 'price_delimeter' ) ); ?>";
			var stmCurrencyPos = "<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'price_currency_position' ) ); ?>";

			$('.calculate_loan_payment').on('click', function(e){
				e.preventDefault();

				//Useful vars
				var current_calculator = $(this).closest('.stm_auto_loan_calculator');

				var calculator_alert = current_calculator.find('.calculator-alert');
				//First of all hide alert
				calculator_alert.removeClass('visible-alert');

				//4 values for calculating
				vehicle_price = parseFloat(current_calculator.find('input.vehicle_price').val());

				interest_rate = parseFloat(current_calculator.find('input.interest_rate').val());
				interest_rate = interest_rate/1200;

				down_payment = parseFloat(current_calculator.find('input.down_payment').val());

				period_month = parseFloat(current_calculator.find('input.period_month').val());

				//Help vars

				var validation_errors = true;

				var monthly_payment = 0;
				var total_interest_payment = 0;
				var total_amount_to_pay = 0;

				//Check if not nan
				if(isNaN(vehicle_price)){
					calculator_alert.text("<?php esc_html_e( 'Please fill Vehicle Price field', 'motors' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.vehicle_price').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(interest_rate)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Interest Rate field', 'motors' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.interest_rate').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(period_month)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Period field', 'motors' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.period_month').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(down_payment)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Down Payment field', 'motors' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (down_payment > vehicle_price) {
					//Check if down payment is not bigger than vehicle price
					calculator_alert.text("<?php esc_html_e( 'Down payment can not be more than vehicle price', 'motors' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else {
					validation_errors = false;
				}

				if(!validation_errors) {
					var interest_rate_unused = interest_rate;
					var mathPow = Math.pow(1 + interest_rate, period_month);

					if(interest_rate == 0) {
						interest_rate_unused = 1;
					}

					monthly_payment = (interest_rate_unused != 1) ? (vehicle_price - down_payment) * interest_rate_unused * mathPow : (vehicle_price - down_payment) / period_month;
					var monthly_payment_div = (mathPow - 1);
					if(monthly_payment_div == 0) {
						monthly_payment_div = 1;
					}

					monthly_payment = monthly_payment/monthly_payment_div;
					monthly_payment = monthly_payment.toFixed(2);

					total_amount_to_pay = down_payment + (monthly_payment*period_month);
					total_amount_to_pay = (interest_rate == 0) ? vehicle_price : total_amount_to_pay.toFixed(2);

					total_interest_payment = total_amount_to_pay - vehicle_price;
					total_interest_payment = (interest_rate == 0) ? 0 : total_interest_payment.toFixed(2);

					current_calculator.find('.stm_calculator_results').slideDown();
					current_calculator.find('.monthly_payment').text(stm_get_price_view(monthly_payment, stmCurrency, stmCurrencyPos, stmPriceDel ));
					current_calculator.find('.total_interest_payment').text(stm_get_price_view(total_interest_payment, stmCurrency, stmCurrencyPos, stmPriceDel ));
					current_calculator.find('.total_amount_to_pay').text(stm_get_price_view(total_amount_to_pay, stmCurrency, stmCurrencyPos, stmPriceDel ));
				} else {
					current_calculator.find('.stm_calculator_results').slideUp();
					current_calculator.find('.monthly_payment').text('');
					current_calculator.find('.total_interest_payment').text('');
					current_calculator.find('.total_amount_to_pay').text('');
				}
			})

			$(".numbersOnly").on("keypress keyup blur",function (event) {
				//this.value = this.value.replace(/[^0-9\.]/g,'');
				$(this).val($(this).val().replace(/[^0-9\.]/g,''));
				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}

				if ( $(this).val() != '' ){
					$(this).closest('.form-group').removeClass('has-error');
				}
			});

			<?php if ( ! empty( $interest ) && ! empty( $period ) && ! empty( $down_payment ) ) : ?>
			$('.calculate_loan_payment').trigger('click');
			<?php endif; ?>
		});

	})(jQuery);
</script>

<style>
	.stm_auto_loan_calculator.novo .stm_calculator_results .result .h5:before {
		content: '<?php echo wp_kses_post( $currency_symbol ); ?>';
	}
</style>

<?php endif; ?>

<?php } ?>
