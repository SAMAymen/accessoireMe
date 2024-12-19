(function ($) {
	var errorFields = {
		firstStep: {},
		secondStep: {},
		thirdStep: {},
	}

	function stm_validateFirstStep(form_id) {
		errorFields.firstStep = {}
		var widget_selector =
			typeof form_id !== 'undefined'
				? '.stm-sell-a-car-form-' + form_id + ' '
				: ''
		$(widget_selector + '#step-one input[type="text"]').each(function () {
			var required = $(this).data('need')
			if (typeof required !== 'undefined') {
				if ($(this).attr('name') != 'video_url') {
					if ($(this).val() == '') {
						$(this).addClass('form-error')
						errorFields.firstStep[$(this).attr('name')] = $(this)
							.closest('.form-group')
							.find('.contact-us-label')
							.text()
					} else {
						$(this).removeClass('form-error')
					}
				}
			}
		})
		var errorsLength = Object.keys(errorFields.firstStep).length
		return errorsLength === 0
	}

	function stm_validateThirdStep(form_id) {
		errorFields.thirdStep = {}
		var widget_selector =
			typeof form_id !== 'undefined'
				? '.stm-sell-a-car-form-' + form_id + ' '
				: ''
		var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
		var phonePattern = /^[\d\+\-\.\(\)\s]{7,}$/
		var hasErrors = false

		$(
			widget_selector +
				'.contact-details input[type="text"],' +
				widget_selector +
				'.contact-details input[type="email"],' +
				widget_selector +
				'.contact-details input[type="phone"]'
		).each(function () {
			var fieldValue = $(this).val().trim()
			var fieldName = $(this).attr('name')

			if (fieldValue === '') {
				$(this).addClass('form-error')
				errorFields.thirdStep[fieldName] = $(this)
					.closest('.form-group')
					.find('.contact-us-label')
					.text()
				hasErrors = true
			} else {
				$(this).removeClass('form-error')

				if (fieldName === 'email' && !emailPattern.test(fieldValue)) {
					$(this).addClass('form-error')
					hasErrors = true
				}

				if (fieldName === 'phone' && !phonePattern.test(fieldValue)) {
					$(this).addClass('form-error')
					hasErrors = true
				}
			}
		})
		return !hasErrors
	}

	$(document).on('ready', function () {
		$('.sell-a-car-proceed').on('click', function (e) {
			e.preventDefault()
			var step = $(this).data('step')
			var form_id = $(this).closest('.stm-sell-a-car-form').data('form-id')
			var widget_selector =
				typeof form_id !== 'undefined'
					? '.stm-sell-a-car-form-' + form_id + ' '
					: ''

			if (step == '2') {
				if (stm_validateFirstStep(form_id)) {
					$(widget_selector + 'a[href="#step-one"]').removeClass('active')
					$(widget_selector + 'a[href="#step-two"]').addClass('active')
					$(widget_selector + '.form-content-unit').slideUp()
					$(widget_selector + '#step-two').slideDown()
				}
			}
			if (step == '3') {
				$(widget_selector + 'a[href="#step-two"]').removeClass('active')
				$(widget_selector + 'a[href="#step-three"]').addClass('active')
				$(widget_selector + '.form-content-unit').slideUp()
				$(widget_selector + '#step-three').slideDown()
				$(widget_selector + 'a[href="#step-two"]').addClass('validated')
			}
		})

		$('.stm-sell-a-car-form input[type="submit"]').on('click', function (e) {
			e.preventDefault()
			var form_id = $(this).closest('.stm-sell-a-car-form').data('form-id')
			var widget_selector =
				typeof form_id !== 'undefined'
					? '.stm-sell-a-car-form-' + form_id + ' '
					: ''
			var validated = true

			if (stm_validateThirdStep(form_id)) {
				$(widget_selector + '.form-content-unit').slideUp()
				$(widget_selector + '#step-three').slideDown()
				$(widget_selector + 'a[href="#step-three"]').addClass('validated')
			} else {
				validated = false
			}

			var form = $(this).closest('#trade-in-default')

			if (form.length === 0) {
				return
			}

			var formData = new FormData(form[0])

			var nonce = form.find('input[name="trade_in_wpnonce"]').val()
			formData.append('action', 'stm_trade_in_form')
			formData.append('trade_in_wpnonce', nonce)

			if (validated) {
				$('.stm-sell-a-car-loader').toggleClass('active')
				$('.stm-sell-a-car-form').toggleClass('hidden')

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						if (response.success === false) {
							var $errorContainer = $('.wpcf7-validation-errors')

							if ($errorContainer.length === 0) {
								$errorContainer = $(
									'<div class="wpcf7-validation-errors"></div>'
								).appendTo('.stm-sell-a-car-form')
							} else {
								$errorContainer.html('')
							}

							$errorContainer.append(response.message)
							$errorContainer.removeClass('hidden')
							$('.stm-sell-a-car-form').removeClass('hidden')

							return
						}
						console.log(response)
						$('.wpcf7-mail-sent-ok').toggleClass('hidden')
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.error(textStatus, errorThrown)
					},
					complete: function () {
						$('.stm-sell-a-car-loader').toggleClass('active')
					},
				})
			}
		})

		$('.stm-sell-a-car-form .form-navigation-unit').on('click', function (e) {
			e.preventDefault()
			var form_id = $(this).closest('.stm-sell-a-car-form').data('form-id')
			var widget_selector =
				typeof form_id !== 'undefined'
					? '.stm-sell-a-car-form-' + form_id + ' '
					: ''
			stm_validateFirstStep(form_id)
			if (!$(this).hasClass('active')) {
				$(widget_selector + '.form-navigation-unit').removeClass('active')
				$(this).addClass('active')

				var tab = $(this).data('tab')
				$(widget_selector + '.form-content-unit').slideUp()
				$(widget_selector + '#' + tab).slideDown()
			}
		})

		$('.stm-sell-a-car-form .stm-plus').on('click', function (e) {
			e.preventDefault()
			var filesnum = $(this)
				.closest('.stm-sell-a-car-form')
				.find('.upload-photos .stm-pseudo-file-input').length
			if (filesnum < 5) {
				var input_label = $(this)
					.closest('.stm-sell-a-car-form')
					.find('.upload-photos .stm-pseudo-file-input:first-of-type')
					.data('placeholder')
				$(this)
					.closest('.stm-sell-a-car-form')
					.find('.upload-photos')
					.append(
						'<div class="stm-pseudo-file-input generated"><div class="stm-filename">' +
							input_label +
							'</div><div class="stm-plus"></div><input class="stm-file-realfield" type="file" name="gallery_images_' +
							(filesnum + 1) +
							'"/></div>'
					)
			}
		})

		$('body').on('change', '.stm-file-realfield', function () {
			var fileName = $(this).val().split('\\').pop()
			$(this).siblings('.stm-filename').text(fileName)
		})

		$('body').on('click', '.generated .stm-plus', function () {
			$(this).closest('.stm-pseudo-file-input').remove()
		})
	})
})(jQuery)
