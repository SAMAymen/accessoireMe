<div class="modal" id="get-car-calculator" tabindex="-1" role="dialog" aria-labelledby="myModalLabelCalc">
	<div class="modal-calculator">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header modal-header-iconed">
					<i class="motors-icons-steering_wheel"></i>

					<h3 class="modal-title" id="myModalLabelCalc">
						<?php esc_html_e( 'Calculate Payment', 'stm_vehicles_listing' ); ?>
					</h3>

					<div class="test-drive-car-name">
						<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_queried_object_id() ), get_queried_object_id() ) ); ?>
					</div>

					<div class="mobile-close-modal" data-dismiss="modal" aria-label="Close">
						<i class="fas fa-times" aria-hidden="true"></i>
					</div>
				</div>
				<div class="modal-body">

					<?php
					$currency_symbol = apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) );
					$price           = get_post_meta( get_queried_object_id(), 'price', true );
					$sale_price      = get_post_meta( get_queried_object_id(), 'sale_price', true );

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

					?>
					<div class="stm_auto_loan_calculator">
						<div class="title">
							<i class="motors-icons-calculator"></i>
							<h5><?php esc_html_e( 'Financing calculator', 'stm_vehicles_listing' ); ?></h5>
						</div>
						<div class="row">
							<div class="col-md-12">

								<!--Amount-->
								<div class="form-group">
									<div class="labeled">
										<?php esc_html_e( 'Vehicle price', 'stm_vehicles_listing' ); ?>
										<span class="orange">(<?php echo esc_html( $currency_symbol ); ?>)</span>
									</div>
									<input type="text" class="numbersOnly vehicle_price" value="<?php echo esc_attr( $price ); ?>"/>
								</div>

								<div class="row">
									<div class="col-md-6 col-sm-6">
										<!--Interest rate-->
										<div class="form-group md-mg-rt">
											<div class="labeled">
												<?php esc_html_e( 'Interest rate', 'stm_vehicles_listing' ); ?>
												<span class="orange">(%)</span>
											</div>
											<input type="text" class="numbersOnly interest_rate" value="<?php echo esc_attr( $interest ); ?>" />
										</div>
									</div>
									<div class="col-md-6 col-sm-6">
										<!--Period-->
										<div class="form-group md-mg-lt">
											<div class="labeled">
												<?php esc_html_e( 'Period', 'stm_vehicles_listing' ); ?>
												<span class="orange">(<?php esc_html_e( 'month', 'stm_vehicles_listing' ); ?>)</span>
											</div>
											<input type="text" class="numbersOnly period_month" value="<?php echo esc_attr( $period ); ?>"/>
										</div>
									</div>
								</div>

								<!--Down Payment-->
								<div class="form-group">
									<div class="labeled"><?php esc_html_e( 'Down Payment', 'stm_vehicles_listing' ); ?> <span
											class="orange">(<?php echo esc_html( $currency_symbol ); ?>)</span></div>
									<input type="text" class="numbersOnly down_payment" value="<?php echo esc_attr( $down_payment ); ?>"/>
								</div>

								<a href="#" class="stm-button button-sm calculate_loan_payment dp-in"><?php esc_html_e( 'Calculate', 'stm_vehicles_listing' ); ?></a>

								<div class="calculator-alert alert alert-danger">

								</div>

							</div>

							<!--Results-->
							<div class="col-md-12">
								<div class="stm_calculator_results">
									<div class="stm-calc-results-inner">
										<div class="stm-calc-label"><?php esc_html_e( 'Monthly Payment', 'stm_vehicles_listing' ); ?></div>
										<div class="monthly_payment h5"></div>

										<div class="stm-calc-label"><?php esc_html_e( 'Total Interest Payment', 'stm_vehicles_listing' ); ?></div>
										<div class="total_interest_payment h5"></div>

										<div class="stm-calc-label"><?php esc_html_e( 'Total Amount to Pay', 'stm_vehicles_listing' ); ?></div>
										<div class="total_amount_to_pay h5"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						(function ($) {
							"use strict";

							$(document).ready(function () {
								var vehicle_price;
								var interest_rate;
								var down_payment;
								var period_month;

								var stmCurrency = "<?php echo esc_js( apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) ) ); ?>";
								var stmPriceDel = "<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', ' ', 'price_delimeter' ) ); ?>";
								var stmCurrencyPos = "<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'price_currency_position' ) ); ?>";

								$('.calculate_loan_payment').on('click', function (e) {
									e.preventDefault();

									//Useful vars
									var current_calculator = $(this).closest('.stm_auto_loan_calculator');

									var calculator_alert = current_calculator.find('.calculator-alert');
									//First of all hide alert
									calculator_alert.removeClass('visible-alert');

									//4 values for calculating
									vehicle_price = parseFloat(current_calculator.find('input.vehicle_price').val());

									interest_rate = parseFloat(current_calculator.find('input.interest_rate').val());
									interest_rate = interest_rate / 1200;

									down_payment = parseFloat(current_calculator.find('input.down_payment').val());

									period_month = parseFloat(current_calculator.find('input.period_month').val());

									//Help vars

									var validation_errors = true;

									var monthly_payment = 0;
									var total_interest_payment = 0;
									var total_amount_to_pay = 0;

									//Check if not nan
									if (isNaN(vehicle_price)) {
										calculator_alert.text("<?php esc_html_e( 'Please fill Vehicle Price field', 'stm_vehicles_listing' ); ?>");
										calculator_alert.addClass('visible-alert');
										current_calculator.find('input.vehicle_price').closest('.form-group').addClass('has-error');
										validation_errors = true;
									} else if (isNaN(interest_rate)) {
										calculator_alert.text("<?php esc_html_e( 'Please fill Interest Rate field', 'stm_vehicles_listing' ); ?>");
										calculator_alert.addClass('visible-alert');
										current_calculator.find('input.interest_rate').closest('.form-group').addClass('has-error');
										validation_errors = true;
									} else if (isNaN(period_month)) {
										calculator_alert.text("<?php esc_html_e( 'Please fill Period field', 'stm_vehicles_listing' ); ?>");
										calculator_alert.addClass('visible-alert');
										current_calculator.find('input.period_month').closest('.form-group').addClass('has-error');
										validation_errors = true;
									} else if (isNaN(down_payment)) {
										calculator_alert.text("<?php esc_html_e( 'Please fill Down Payment field', 'stm_vehicles_listing' ); ?>");
										calculator_alert.addClass('visible-alert');
										current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
										validation_errors = true;
									} else if (down_payment > vehicle_price) {
										//Check if down payment is not bigger than vehicle price
										calculator_alert.text("<?php esc_html_e( 'Down payment can not be more than vehicle price', 'stm_vehicles_listing' ); ?>");
										calculator_alert.addClass('visible-alert');
										current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
										validation_errors = true;
									} else {
										validation_errors = false;
									}

									if (!validation_errors) {
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
										current_calculator.find('.monthly_payment').text( monthly_payment );
										current_calculator.find('.total_interest_payment').text( total_interest_payment );
										current_calculator.find('.total_amount_to_pay').text( total_amount_to_pay );
									} else {
										current_calculator.find('.stm_calculator_results').slideUp();
										current_calculator.find('.monthly_payment').text('');
										current_calculator.find('.total_interest_payment').text('');
										current_calculator.find('.total_amount_to_pay').text('');
									}
								})

								$(".numbersOnly").on("keypress keyup blur", function (event) {
									//this.value = this.value.replace(/[^0-9\.]/g,'');
									$(this).val($(this).val().replace(/[^0-9\.]/g, ''));
									if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
										event.preventDefault();
									}

									if ($(this).val() != '') {
										$(this).closest('.form-group').removeClass('has-error');
									}
								});
							});

						})(jQuery);
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
