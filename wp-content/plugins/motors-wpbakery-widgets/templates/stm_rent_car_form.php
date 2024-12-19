<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $style
 * @var $css
 * */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$args = array(
	'post_type'      => 'stm_office',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
);

$style_type = 'style_1';
if ( ! empty( $style ) && 'style_2' === $style ) {
	$style_type = 'style_2';
}

$work_hour = '';
if ( ! empty( $office_working_hours ) ) {
	$tm        = explode( '-', $office_working_hours );
	$work_hour = array();
	$ending    = end( $tm );
	for ( $i = $tm[0]; $i <= $ending; $i++ ) {
		$work_hour[] = $i . ':00';
	}
} elseif ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'working_hours' ) ) && empty( $office_working_hours ) ) {
	$tm        = explode( '-', apply_filters( 'stm_me_get_nuxy_mod', '', 'working_hours' ) );
	$work_hour = array();
	$ending    = end( $tm );
	for ( $i = $tm[0]; $i <= $ending; $i++ ) {
		$work_hour[] = $i . ':00';
	}
} else {
	$tm = 0;
}

$fields    = stm_get_rental_order_fields_values( true );
$locations = apply_filters( 'stm_rental_locations', array(), true );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$form_url   = stm_woo_shop_page_url();
$reserv_url = stm_woo_shop_page_url();
$items      = stm_get_cart_items();

$date_format      = get_option( 'date_format' );
$time_format      = get_option( 'time_format' );
$date_format      = ( preg_match_all( '/[d].[m].[yY]/', $date_format ) ) ? 'm/d/Y' : $date_format;
$date_time_format = $fields['format'];

$my_locale = explode( '_', get_locale() );
?>

<div class="stm_rent_car_form_wrapper <?php echo esc_attr( $style_type . ' ' . $align . ' ' . $css_class ); ?>">
	<div class="stm_rent_car_form">
		<form action="<?php echo esc_url( $form_url ); ?>" method="get">
			<h4><?php esc_html_e( 'Pick Up', 'motors-wpbakery-widgets' ); ?></h4>
			<div class="stm_rent_form_fields">
				<h4 class="stm_form_title"><?php esc_html_e( 'Place to pick up the Car*', 'motors-wpbakery-widgets' ); ?></h4>
				<div class="stm_pickup_location">
					<i class="stm-service-icon-pin"></i>
					<select name="pickup_location" data-class="stm_rent_location">
						<option value=""><?php esc_html_e( 'Choose office', 'motors-wpbakery-widgets' ); ?></option>
						<?php if ( ! empty( $locations ) ) : ?>
							<?php foreach ( $locations as $location ) : ?>
								<option value="<?php echo esc_attr( $location[5] ); ?>" <?php echo ( esc_attr( $location[5] ) === $fields['pickup_location_id'] ) ? 'selected="selected"' : ''; ?>>
									<?php echo esc_html( $location[4] ); ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<label>
					<input type="checkbox" name="return_same" <?php echo ( 'on' === $fields['return_same'] ) ? 'checked' : ''; ?>/>
					<?php esc_html_e( 'Return to the same location', 'motors-wpbakery-widgets' ); ?>
				</label>
				<div class="stm_date_time_input">
					<h4 class="stm_form_title"><?php esc_html_e( 'Pick-up Date/Time*', 'motors-wpbakery-widgets' ); ?></h4>
					<div class="stm_date_input">
						<input type="text" value="<?php echo function_exists( 'change_date_format' ) ? esc_attr( change_date_format( $fields['pickup_date'] ) ) : esc_attr( $fields['pickup_date'] ); ?>" class="stm-date-timepicker-start" name="pickup_date" placeholder="<?php esc_attr_e( 'Pickup Date', 'motors-wpbakery-widgets' ); ?>" required readonly
							<?php echo ( ! empty( $fields['calc_pickup_date'] ) ) ? 'data-dt-hide="' . esc_attr( $fields['calc_pickup_date'] ) . '"' : ''; ?>
						/>
						<i class="stm-icon-date"></i>
					</div>
				</div>
			</div>

			<h4><?php esc_html_e( 'Return', 'motors-wpbakery-widgets' ); ?></h4>
			<div class="stm_rent_form_fields stm_rent_form_fields-drop">
				<div class="stm_same_return <?php echo ( 'on' === $fields['return_same'] ) ? '' : 'active'; ?>">
					<h4 class="stm_form_title"><?php esc_html_e( 'Place to drop the Car*', 'motors-wpbakery-widgets' ); ?></h4>
					<div class="stm_pickup_location stm_drop_location">
						<i class="stm-service-icon-pin"></i>
						<select name="drop_location" data-class="stm_rent_location">
							<option value=""><?php esc_html_e( 'Choose office', 'motors-wpbakery-widgets' ); ?></option>
							<?php if ( ! empty( $locations ) ) : ?>
								<?php foreach ( $locations as $location ) : ?>
									<option
										<?php echo ( $location[5] === $fields['return_location_id'] ) ? 'selected="selected"' : ''; ?>
										value="<?php echo esc_attr( $location[5] ); ?>">
										<?php echo esc_html( $location[4] ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>

					</div>
				</div>
				<div class="stm_date_time_input">
					<h4 class="stm_form_title"><?php esc_html_e( 'Drop Date/Time*', 'motors-wpbakery-widgets' ); ?></h4>
					<div class="stm_date_input">
						<input type="text" class="stm-date-timepicker-end" name="return_date"
							value="<?php echo function_exists( 'change_date_format' ) ? esc_attr( change_date_format( $fields['return_date'] ) ) : esc_attr( $fields['return_date'] ); ?>"
							placeholder="<?php esc_attr_e( 'Return Date', 'motors-wpbakery-widgets' ); ?>" required readonly
							<?php echo ( ! empty( $fields['calc_return_date'] ) ) ? 'data-dt-hide="' . esc_attr( $fields['calc_return_date'] ) . '"' : ''; ?>
						/>
						<i class="stm-icon-date"></i>
					</div>
				</div>
			</div>
			<?php
			$old_days = stm_get_rental_order_fields_values();
			if ( ! empty( $old_days['order_days'] ) ) :
				?>
				<input type="hidden" name="order_old_days" value="<?php echo esc_attr( $old_days['order_days'] ); ?>" />
			<?php endif; ?>
			<?php if ( isset( $_GET['lang'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<input type="hidden" name="lang" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['lang'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>" />
			<?php endif; ?>
			<?php if ( 'style_1' === $style_type ) : ?>
				<div class="form-btn-wrap">
				<button class="heading-font" type="submit"><?php esc_html_e( 'Find a vehicle', 'motors-wpbakery-widgets' ); ?><i
						class="fas fa-arrow-right"></i></button>
					<button type="submit" class="clear-data normal_font" data-type="clear-data"><i class="stm-rental-redo"></i><span><?php esc_html_e( 'Clear Data', 'motors-wpbakery-widgets' ); ?></span></button>
				</div>
			<?php else : ?>
				<button class="heading-font" type="submit"><?php esc_html_e( 'Continue reservation', 'motors-wpbakery-widgets' ); ?></button>
					<button type="submit" class="clear-data normal_font" data-type="clear-data">
						<i class="stm-rental-redo"></i>
						<span>
							<?php esc_html_e( 'Clear Data', 'motors-wpbakery-widgets' ); ?>
						</span>
					</button>
			<?php endif; ?>
		</form>
	</div>
</div>

<script>
	(function($) {
		"use strict";

		$(document).ready(function(){
			$('input[name="return_same"]').on('change', function(){
			if($(this).prop('checked')) {
				$('.stm_same_return').slideUp();
			} else {
				$('.stm_same_return').slideDown();
			}
			});

			let form = $('.stm_rent_car_form form'),
				select = $('.stm_pickup_location select');

			select.on('select2:open', function() {
				$('body').addClass('stm_background_overlay');
				$('.select2-container').css('width', $('.select2-dropdown').outerWidth());
			});

			select.on('select2:close', function(){
				$('body').removeClass('stm_background_overlay');
			});

			$('.stm_date_time_input input').on('change', function(){
			if($(this).val() === '') {
				$(this).removeClass('active');
			} else {
				$(this).addClass('active');
			}
			});

			$('.stm-date-timepicker-start').on('change.datetimepicker', function(e) {
				clearWooCommerceCart();
			});

			$('.stm-date-timepicker-end').on('change.datetimepicker', function(e) {
				clearWooCommerceCart();
			});

			function clearWooCommerceCart() {

				$.ajax({
					url: woocommerce_params.ajax_url,
					type: 'POST',
					data: {
						action: 'clear_woo_cart'
					},
				});

			}

			var locations = <?php echo wp_json_encode( $locations ); ?>;
			var contents = [];
			var content = '';
			var i = 0;

			for (i = 0; i < locations.length; i++) {
				content = '<ul class="stm_locations_description <?php echo esc_attr( $align . '_position' ); ?>">';
				content += '<li>' + locations[i][0] + '</li>';
				content += '</ul>';

				contents.push(content);
			}

			$(document).on('mouseover', '.stm_rent_location .select2-results__options li', function(){
				var currentLi = ($(this).index()) - 1;
				$('.stm_rent_location .stm_locations_description').remove();
				$('.stm_rent_location').append(contents[currentLi]);
			});

			var stmStart = $('.stm-date-timepicker-start')
			var stmEnd = $('.stm-date-timepicker-end')
			var stmStartVal = stmStart.val();
			var stmEndVal = stmEnd.val();

			/*Timepicker*/
			var stmToday = new Date();
			var stmTomorrow = new Date(+new Date() + 86400000);
			var startDate = stmStartVal ? stmStartVal : false;
			var endDate = stmEndVal ? stmEndVal : false;
			var dateTimeFormat = '<?php echo esc_js( $date_time_format ); ?>';
			var dateTimeFormatHide = 'MM DD, YYYY HH:mm';

			stmStart.datetimepicker({
				format: dateTimeFormat,
				dayOfWeekStart: <?php echo esc_js( get_option( 'start_of_week' ) ); ?>,
				defaultDate: stmToday,
				defaultSelect: true,
				closeOnDateSelect: false,
				timeHeightInTimePicker: 40,
				validateOnBlur: false,
				<?php if ( ! empty( $work_hour ) ) : ?>
				allowTimes: <?php echo wp_json_encode( $work_hour ); ?>,
				<?php endif; ?>
				fixed: false,
				lang: currentLocale ? currentLocale : '<?php echo esc_js( $my_locale[0] ); ?>',
				onShow: function( ct ) {
					$('body').addClass('stm_background_overlay stm-lock');

					var stmEndDate = stmEnd.val() ? moment(stmEnd.val()) : false;

					if(stmEndDate) {
						stmEndDate = stmEndDate.toDate();
					}

					this.setOptions({
						minDate: new Date(),
					});

					$(".xdsoft_time_variant").css('margin-top', '-600px');
				},
				onSelectDate: function (ct, $i) {
					$i.datetimepicker('close');

					$('.xdsoft_time').removeClass('xdsoft_current');
					//reset end date
					stmEnd.val('');
					stmEnd.removeAttr( 'data-dt-hide' );
				},
				onClose: function (ct, $i) {
					startDate = ct;

					if (ct < new Date()) {
						$i.datetimepicker('reset');
					}

					if (startDate && endDate) {
						checkDate(moment(startDate).format(dateTimeFormatHide), moment(endDate).format(dateTimeFormatHide));
					}
					$('.stm-date-timepicker-end').trigger('blur');

				},

				onChangeDateTime: function (ct, $i) {
					$i.attr('data-dt-hide', moment(ct).format(dateTimeFormatHide));
				},

				onGenerate: function () {
					if(!stmStart.val()) {
						$('.xdsoft_time').removeClass('xdsoft_current');
					}
				}
			});

			stmEnd.datetimepicker({
				format:dateTimeFormat,
				dayOfWeekStart: <?php echo esc_js( get_option( 'start_of_week' ) ); ?>,
				defaultDate: stmTomorrow,
				defaultSelect: true,
				closeOnDateSelect: false,
				timeHeightInTimePicker: 40,
				validateOnBlur: false,
				<?php if ( ! empty( $work_hour ) ) : ?>
				allowTimes: <?php echo wp_json_encode( $work_hour ); ?>,
				<?php endif; ?>
				fixed: false,
				lang: currentLocale ? currentLocale : '<?php echo esc_js( $my_locale[0] ); ?>',
				onShow: function (ct, $i) {
					$('body').addClass('stm_background_overlay stm-lock');

					var stmStartDate = startDate ? moment(startDate).add(1, 'day') : false;
					if (stmStartDate) {
						stmStartDate = stmStartDate.toDate();
					} else {
						stmStartDate = new Date();
					}

					this.setOptions({
						minDate: stmStartDate,
						defaultDate: stmStartDate,
					})
				},
				onSelectDate: function (ct, $i) {
					$('.xdsoft_time').removeClass('xdsoft_current');
					$i.datetimepicker('close');
				},
				onClose: function( ct, $i ) {
					endDate = ct;

					if(ct < new Date()) {
						$i.datetimepicker('reset');
					}

					if(!stmStart.val() && !$i.val()) {
						checkDate(moment(startDate).format(dateTimeFormatHide), moment(endDate).format(dateTimeFormatHide));
					}

					var s = moment(startDate);
					var e = moment(ct);

					if(e.diff(s) < 0) {
						s = s.add(1, 'day');
						$i.val('');
						$i.attr('data-dt-hide', '');
						this.setOptions({
							minDate: s.toDate(),
							defaultDate: s.toDate()
						});
					}

					$('body').removeClass('stm_background_overlay stm-lock');
				},
				onChangeDateTime: function (ct, $i) {
					$i.attr('data-dt-hide', moment(ct).format(dateTimeFormatHide));
				},
				onGenerate: function (ct, $i) {
					if ($i.val() === '') {
						$('.xdsoft_time').removeClass('xdsoft_current');
					}
				}
			});

			let clear_button = $('form .clear-data')

			<?php if ( ! empty( $fields['pickup_date'] ) && ! empty( $fields['return_date'] ) ) : ?>
			clear_button.each(function (index, item) {
				$(item).addClass('isNotHidden')
			})
			<?php endif; ?>

			clear_button.each(function (index, item) {
				if (!$(item).hasClass('isNotHidden'))
					$(item).hide()
			})

			form.find('select, input').change(function () {
				let form = $(this.form)
				form.find('.clear-data').show()
			})

			clear_button.on('click', function (e) {
				e.preventDefault();

				let form = $(this.form)

				form.attr('action', '<?php echo esc_url( $reserv_url ); ?>');

				jQuery.ajax({
					url: ajaxurl,
					type: "GET",
					dataType: 'json',
					context: this,
					data: 'action=stm_ajax_clear_data&security=' + stm_security_nonce,
					success: function (data) {}
				});

				$("select[name='pickup_location']").val('').trigger('change');
				$("select[name='drop_location']").val('').trigger('change');

				$.each(form.serializeArray(), function (i, field) {
					if(field.name === 'pickup_location' || field.name === 'drop_location') {
						$.cookie('stm_' + field.name + '_' + stm_site_blog_id, '', { expires: -1, path: '/' });
						$.cookie('stm_car_watched', '', { expires: -1, path: '/' });
						return
					} else {
						let _field = $('input[name="' + field.name + '"]')
						_field.val('');
						_field.attr('data-dt-hide', '');
					}

					$.cookie('stm_' + field.name + '_' + stm_site_blog_id, '', { expires: -1, path: '/' });
					$.cookie('stm_car_watched', '', { expires: -1, path: '/' });
				});

				$(this).hide();

				return false;
			});

			/*Set cookie with order data*/
			form.on('submit', function (e) {
				var error = false;

				$('.stm_pickup_location').removeClass('stm_error');
				$('.stm_date_time_input input').removeClass('stm_error');

				const pickupDateValue = $('input[name="pickup_date"]').val();
				const returnDateValue = $('input[name="return_date"]').val();

				const convertedPickupDate = moment(pickupDateValue).format('MM DD, YYYY HH:mm')
				const convertedReturnDate = moment(returnDateValue).format('MM DD, YYYY HH:mm')

				/*Save in cookies all fields*/
				if($.cookie('stm_pickup_date_' + stm_site_blog_id) != null) {
					$.cookie('stm_pickup_date_old_' + stm_site_blog_id, $.cookie('stm_pickup_date_' + stm_site_blog_id), {expires: 7, path: '/'});
					$.cookie('stm_return_date_old_' + stm_site_blog_id, $.cookie('stm_return_date_' + stm_site_blog_id), {expires: 7, path: '/'});
				}

				$.each($(this).serializeArray(), function (i, field) {
					$.cookie('stm_' + field.name + '_' + stm_site_blog_id, encodeURIComponent(field.value), {expires: 7, path: '/'});

					if (field.name == 'pickup_date' || field.name == 'return_date') {
						if (typeof $('input[name="' + field.name + '"]').attr('data-dt-hide') == 'undefined' || $('input[name="' + field.name + '"]').attr('data-dt-hide') == '') {
							$('input[name="' + field.name + '"]').addClass('stm_error');
							error = true;
						} else {
							$('input[name="' + field.name + '"]').removeClass('stm_error');
						}

						$.cookie('stm_calc_' + field.name + '_' + stm_site_blog_id, (typeof $('input[name="' + field.name + '"]').attr('data-dt-hide') != 'undefined') ? $('input[name="' + field.name + '"]').attr('data-dt-hide') : '', {expires: 7, path: '/'});
					}
				});

				if(!$('input[name="return_same"]').prop('checked')) {
					$.cookie('stm_return_same_' + stm_site_blog_id, "off", {expires: 7, path: '/'});
				}

				var stm_pickup_location = $('.stm_pickup_location select').val();
				var return_same = $('input[name="return_same"]').prop('checked');
				var stm_drop_location = $('.stm_drop_location select').val();

				if (stm_pickup_location == '') {
					$('.stm_pickup_location:not(".stm_drop_location")').addClass('stm_error');
					error = true;
				}

				if (!return_same && stm_drop_location == '') {
					$('.stm_drop_location').addClass('stm_error');
					error = true;
				}

				if (error) {
					e.preventDefault();
				}
			});


			$('.stm-template-car_rental .stm_rent_order_info .image.image-placeholder a').on('click', function(e){
				var $stmThis = $('.stm_rent_car_form form');
				$stmThis.trigger('submit');
				e.preventDefault();
			});

			$('body').on('click touchstart', '.stm-rental-overlay', function(e) {
				$('.stm-date-timepicker-start').trigger('blur');
				$('.stm-date-timepicker-end').trigger('blur');
				$('.xdsoft_datetimepicker').hide();
				$('body').removeClass('stm_background_overlay');
			});

		});

	})(jQuery);

	function checkDate ($start, $end) {

		var locationId = jQuery('select[name="pickup_location"]').select2("val");
		var stm_timeout_rental;
		if(locationId != '') {
			jQuery.ajax({
				url: ajaxurl,
				type: "GET",
				dataType: 'json',
				context: this,
				data: 'startDate=' + $start + '&endDate=' + $end + '&action=stm_ajax_check_is_available_car_date&security=' + stm_security_nonce,
				success: function (data) {
					jQuery("#select-vehicle-popup").attr("href", jQuery("#select-vehicle-popup").attr('href').split("?")[0] + "?pickup_location=" + locationId);
					if (data != '') {
						clearTimeout(stm_timeout_rental);
						jQuery('.choose-another-class').addClass('single-add-to-compare-visible');
						jQuery(".choose-another-class").addClass('car-reserved');
						jQuery(".choose-another-class").find(".stm-title.h5").html(data);
						stm_timeout_rental = setTimeout(function () {
							jQuery('.choose-another-class').removeClass('single-add-to-compare-visible').removeClass('car-reserved');
						}, 10000);
					}
				}
			});
		}
	}
</script>
