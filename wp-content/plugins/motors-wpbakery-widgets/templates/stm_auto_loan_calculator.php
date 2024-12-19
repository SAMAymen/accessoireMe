<?php
global $listing_id;
$listing_id = ( is_null( $listing_id ) ) ? get_the_ID() : $listing_id;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( empty( $currency_symbol ) ) {
	$currency_symbol = '';
}

$price      = get_post_meta( $listing_id, 'price', true );
$sale_price = get_post_meta( $listing_id, 'sale_price', true );

if ( ! empty( $sale_price ) ) {
	$price = apply_filters( 'get_conver_price', $sale_price );
} elseif ( ! empty( $price ) ) {
	$price = apply_filters( 'get_conver_price', $price );
} else {
	$price = '';
}

$wide_calculator = '';

if ( ! empty( $wide_version ) && $wide_version ) {
	$wide_calculator = 'wide-version';
}

$default_interest_rate = '';
$default_month_period  = '';
$default_down_payment  = '';

$default_interest_rate = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_interest_rate' );
$default_month_period  = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_month_period' );
$default_down_payment  = apply_filters( 'motors_vl_get_nuxy_mod', '', 'default_down_payment' );

?>
<div class="stm_auto_loan_calculator <?php echo esc_attr( $css_class . ' ' . $wide_calculator ); ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<div class="title single-calculator-title">
			<i class="stm-icon-calculator"></i>
			<h3><?php echo esc_attr( $title ); ?></h3>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-md-12">

			<!--Amount-->
			<div class="form-group">
				<div class="labeled
				<?php
				if ( ! empty( $label_font_size ) ) {
					echo 'heading-font';
				}
				?>
				">
					<?php esc_html_e( 'Vehicle price', 'motors-wpbakery-widgets' ); ?> <span
							class="orange">(<?php echo esc_html( $currency_symbol ); ?>)</span></div>
				<input type="text" class="numbersOnly vehicle_price" value="<?php echo esc_attr( $price ); ?>"/>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-6">
					<!--Interest rate-->
					<div class="form-group md-mg-rt">
						<div class="labeled
						<?php
						if ( ! empty( $label_font_size ) ) {
							echo 'heading-font';
						}
						?>
						"><?php esc_html_e( 'Interest rate', 'motors-wpbakery-widgets' ); ?> <span
									class="orange">(%)</span></div>
						<input type="text" class="numbersOnly interest_rate" value="<?php echo esc_html( $default_interest_rate ); ?>"/>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<!--Period-->
					<div class="form-group md-mg-lt">
						<div class="labeled
						<?php
						if ( ! empty( $label_font_size ) ) {
							echo 'heading-font';
						}
						?>
						"><?php esc_html_e( 'Period', 'motors-wpbakery-widgets' ); ?> <span
									class="orange">(<?php esc_html_e( 'month', 'motors-wpbakery-widgets' ); ?>)</span>
						</div>
						<input type="text" class="numbersOnly period_month" value="<?php echo esc_html( $default_month_period ); ?>"/>
					</div>
				</div>
			</div>

			<!--Down Payment-->
			<div class="form-group">
				<div class="labeled
				<?php
				if ( ! empty( $label_font_size ) ) {
					echo 'heading-font';
				}
				?>
				"><?php esc_html_e( 'Down Payment', 'motors-wpbakery-widgets' ); ?> <span
							class="orange">(<?php echo esc_html( $currency_symbol ); ?>)</span></div>
				<input type="text" class="numbersOnly down_payment" value="<?php echo esc_html( $default_down_payment ); ?>"/>
			</div>

			<a href="" class="button button-sm calculate_loan_payment dp-in"><?php esc_html_e( 'Calculate', 'motors-wpbakery-widgets' ); ?></a>

			<div class="calculator-alert alert alert-danger">

			</div>

		</div>

		<!--Results-->
		<div class="col-md-12">
			<div class="stm_calculator_results">
				<div class="stm-calc-results-inner">
					<div class="stm-calc-label"><?php esc_html_e( 'Monthly Payment', 'motors-wpbakery-widgets' ); ?></div>
					<div class="monthly_payment h5"></div>

					<div class="stm-calc-label"><?php esc_html_e( 'Total Interest Payment', 'motors-wpbakery-widgets' ); ?></div>
					<div class="total_interest_payment h5"></div>

					<div class="stm-calc-label"><?php esc_html_e( 'Total Amount to Pay', 'motors-wpbakery-widgets' ); ?></div>
					<div class="total_amount_to_pay h5"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php //phpcs:disable ?>
<?php if ( ! empty( $label_font_size ) ) : ?>
	<style>
		.stm_auto_loan_calculator .labeled.heading-font {
			margin-bottom: 9px;
			font-size: <?php echo esc_attr( $label_font_size ); ?>px;
			text-transform: uppercase;
			color: #232628;
			font-weight: 700;
		}

		.stm_auto_loan_calculator .labeled.heading-font .orange {
			font-weight: 400;
		}
	</style>
<?php endif; ?>

<script>
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
					calculator_alert.text("<?php esc_html_e( 'Please fill Vehicle Price field', 'motors-wpbakery-widgets' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.vehicle_price').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(interest_rate)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Interest Rate field', 'motors-wpbakery-widgets' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.interest_rate').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(period_month)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Period field', 'motors-wpbakery-widgets' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.period_month').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (isNaN(down_payment)) {
					calculator_alert.text("<?php esc_html_e( 'Please fill Down Payment field', 'motors-wpbakery-widgets' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else if (down_payment > vehicle_price) {
					//Check if down payment is not bigger than vehicle price
					calculator_alert.text("<?php esc_html_e( 'Down payment can not be more than vehicle price', 'motors-wpbakery-widgets' ); ?>");
					calculator_alert.addClass('visible-alert');
					current_calculator.find('input.down_payment').closest('.form-group').addClass('has-error');
					validation_errors = true;
				} else {
					validation_errors = false;
				}

				if (!validation_errors) {
					var interest_rate_unused = interest_rate;

					if (interest_rate == 0) {
						interest_rate_unused = 1;
					}

					monthly_payment = (vehicle_price - down_payment) * interest_rate_unused * Math.pow(1 + interest_rate, period_month);

					var monthly_payment_div = ((Math.pow(1 + interest_rate, period_month)) - 1);

					if (monthly_payment_div == 0) {
						monthly_payment_div = 1;
					}

					monthly_payment = monthly_payment / monthly_payment_div;
					monthly_payment = monthly_payment.toFixed(2);

					total_amount_to_pay = down_payment + (monthly_payment * period_month);
					total_amount_to_pay = total_amount_to_pay.toFixed(2);

					total_interest_payment = total_amount_to_pay - vehicle_price;
					total_interest_payment = total_interest_payment.toFixed(2);

					current_calculator.find('.stm_calculator_results').slideDown();
					current_calculator.find('.monthly_payment').text(stm_get_price_view(monthly_payment, stmCurrency, stmCurrencyPos, stmPriceDel));
					current_calculator.find('.total_interest_payment').text(stm_get_price_view(total_interest_payment, stmCurrency, stmCurrencyPos, stmPriceDel));
					current_calculator.find('.total_amount_to_pay').text(stm_get_price_view(total_amount_to_pay, stmCurrency, stmCurrencyPos, stmPriceDel));
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

<?php //phpcs:enable ?>
