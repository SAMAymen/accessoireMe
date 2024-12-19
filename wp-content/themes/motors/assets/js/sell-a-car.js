(function ($) {
	"use strict";

	var ListingForm = STMListings.ListingForm = function (form) {
		this.$form         = $( form );
		this.formUser      = $( '.stm-form-checking-user' );
		this.featuredId    = 0;
		this.post_id       = 0;
		this.userFiles     = [];
		this.imageProgress = 0;
		this.placeholder   = 0;
		this.selectedPlan  = null;
		this.imageSettings = [];
		this.allowedExt    = /(\.jpg|\.jpeg|\.png)$/i;
		this.orderChanged  = false;
		this.progressWrap  = '.add-a-car-progress';
		this.progress      = null;
		this.bar_prefix    = 'stm-progress-bar';
		this.percentage    = 0;
		this.init();
	};

	ListingForm.prototype.init = function () {
		this.$loader  = $( '.stm-add-a-car-loader' );
		this.$message = $( '.stm-add-a-car-message' );

		this.$form.on( 'submit', $.proxy( this.submit, this ) );

		this.$form.on( 'change', 'input[name^="stm_car_gallery_add"]', $.proxy( this.onImagePicked, this ) );

		this.$form.on( 'change', 'select[name="selectedPlan"]', $.proxy( this.checkMultiPlanImgLimit, this ) );

		$( 'select[name="selectedPlan"]' ).trigger( 'change' );

		$( document ).on( 'touchend click', '.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas', $.proxy( this.imageRemove, this ) );

		var $this     = this;
		this.progress = new Progress(
			{
				get: function () {
					$this.percentage = $this.percentage + Math.random() * 10 | 0;
					if ($this.percentage > 100) {
						$this.percentage = 100;
					}
					$this.progress.update( $this.percentage );
				},
				set: function (percentage) {
					jQuery( "#add-a-car-progress" ).css( 'width', percentage + '%' ).text( percentage + '%' );
				}
			}
		);

		$( '.stm-media-car-gallery .stm-placeholder' ).droppable(
			{
				drop: $.proxy( this.onDropped, this )
			}
		);
	};

	ListingForm.prototype.submit = function (e) {
		e.preventDefault();

		var loadType = $( 'input[name="btn-type"]' ).val();
		this.$loader = $( '.stm-add-a-car-loader.' + loadType );

		$.ajax(
			{
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				context: this,
				data: this.submitData(),
				beforeSend: function () {
					this.$loader.addClass( 'activated' );
					this.$message.slideUp();
				},
				success: this.success
			}
		);

	};

	ListingForm.prototype.submitData = function () {
		var gdpr = '';

		if (typeof this.formUser.find( 'input[name="motors-gdpr-agree"]' )[0] !== 'undefined') {
			var gdprAgree = (this.formUser.find( 'input[name="motors-gdpr-agree"]' )[0].checked) ? 'agree' : 'not_agree';
			gdpr          = '&motors-gdpr-agree=' + gdprAgree;
		}

		return this.$form.serialize() + gdpr + '&action=stm_ajax_add_a_car&security=' + stm_security_nonce;
	};

	ListingForm.prototype.success = function (data) {
		this.$loader.removeClass( 'activated' );
		$( '.stm-form-checking-user button[type="submit"]' ).removeClass().addClass( 'enabled' );

		if (data.message) {
			this.$message.html( data.message ).slideDown();
		}

		if (data.post_id) {
			this.$message.html( data.message ).slideDown();
			this.$loader.addClass( 'activated' );

			if (typeof (this.userFiles) !== 'undefined') {
				if ( ! this.orderChanged) {
					this.sortImages();
				}

				this.uploadImages.call( this, data );
			}
		} else {
			$( this.progressWrap ).hide();
			this.progress.reset();
		}
	};

	ListingForm.prototype.makeThumbFromFile = function (imageFile) {
		return {url: URL.createObjectURL( imageFile )};
	};

	ListingForm.prototype.uploadImage = function (file, image, index) {
		var fd    = new FormData(),
			_this = this;

		if (_this.$form.closest( '.stm_edit_car_form' ).length) {
			fd.append( 'stm_edit', 'update' );
			fd.append( 'post_id', _this.post_id );
		}

		let attachments = [];

		if (_this.userFiles.length) {
			attachments = _this.userFiles.map(
				function (item, mapIndex) {
					if (typeof item !== "number") {
						return mapIndex;
					}

					return item;
				}
			);
		}

		fd.append( 'action', 'stm_ajax_add_a_car_images' );
		fd.append( 'security', stm_security_nonce );
		fd.append( 'files[0]', file );
		fd.append( 'attachments', attachments );

		let ajax_settings = {
			xhr: function () {
				let xhr = $.ajaxSettings.xhr();

				xhr.upload.addEventListener(
					'progress',
					function (evt) {
						if (evt.lengthComputable) {
							const percentComplete = Math.ceil( evt.loaded / evt.total * 100 );
							//Do something with upload progress here

							_this.uploadProgress( image, percentComplete );
						}
					},
					false
				);

				return xhr;
			},
			target: '#stm_sell_a_car_form',
			type: 'POST',
			url: ajaxurl,
			data: fd,
			beforeSend: function () {
				_this.uploadProgress( image, 0, 'upload' );
			},
			contentType: false,
			processData: false,
			context: this,
			cache: false
		};

		let $ajax = $.ajax( ajax_settings );

		$ajax.always(
			function (response) {
				if (response.attachment) {
					_this.userFiles[index] = response.attachment.id;

					_this.uploadProgress( image, 0, 'rendering' );

					let xmlHTTP = new XMLHttpRequest();

					xmlHTTP.open( 'GET', response.attachment.url, true );
					xmlHTTP.responseType = 'arraybuffer';
					xmlHTTP.onload       = function () {
						image
							.css( 'background-image', 'url(' + this.responseURL + ')' )
							.attr( 'data-media', response.attachment.id );

						$( '.stm-media-car-gallery .stm-placeholder' ).stop().droppable(
							{
								drop: $.proxy( _this.onDropped, _this ),
								delay: 200
							}
						);

						$( 'button[type="submit"]', _this.formUser ).removeClass().addClass( 'enabled' );

						_this.uploadProgress( image, false, 'remove' );
					};
					xmlHTTP.onprogress   = function (e) {
						const percentComplete = Math.ceil( e.loaded / e.total * 100 );

						_this.uploadProgress( image, percentComplete );
					};

					xmlHTTP.send();
				} else {
					_this.uploadError( image, response.message, index );
				}
			}
		);

		$ajax.fail(
			function () {
				_this.uploadError( image, 'error', index );

				$( 'button[type="submit"]', _this.formUser ).removeClass().addClass( 'enabled' );
			}
		);
	};

	ListingForm.prototype.resizeImage = function (file, image) {
		let _this = this;

		_this.uploadProgress( image, 0, 'optimization' );

		return new Promise(
			function (resolve, reject) {
				// Load the image
				var reader    = new FileReader();
				reader.onload = function (readerEvent) {
					var image    = new Image();
					image.onload = function () {

						// Resize the image
						var canvas     = document.createElement( 'canvas' ),
							max_size_w = _this.imageSettings.cropping.width, // Max width
							max_size_h = _this.imageSettings.cropping.height, // Max height
							width      = image.width,
							height     = image.height;

						if (width > height) {
							if (width > max_size_w) {
								height *= max_size_w / width;
								width   = max_size_w;
							}
						} else {
							if (height > max_size_h) {
								width *= max_size_h / height;
								height = max_size_h;
							}
						}

						canvas.width  = width;
						canvas.height = height;
						canvas.getContext( '2d' ).drawImage( image, 0, 0, width, height );
						let resizedImage = canvas.toDataURL( 'image/jpeg' ),
							_blob        = _this.dataURItoBlob( resizedImage ),
							opt          = {
								type: file.type,
								lastModifiedDate: file.lastModifiedDate
						};

						file = new File( [_blob], file.name, opt );
						// End resized the image
						resolve( file );
					}
					image.src    = readerEvent.target.result;
				}
				reader.readAsDataURL( file );
			}
		);
	};

	ListingForm.prototype.dataURItoBlob = function (dataURI) {
		// convert base64/URLEncoded data component to raw binary data held in a string
		let byteString;
		if (dataURI.split( ',' )[0].indexOf( 'base64' ) >= 0) {
			byteString = atob( dataURI.split( ',' )[1] );
		} else {
			byteString = unescape( dataURI.split( ',' )[1] );
		}
		// separate out the mime component
		let mimeString = dataURI.split( ',' )[0].split( ':' )[1].split( ';' )[0];
		// write the bytes of the string to a typed array
		let ia = new Uint8Array( byteString.length );
		for (var i = 0; i < byteString.length; i++) {
			ia[i] = byteString.charCodeAt( i );
		}
		return new Blob( [ia], {type: mimeString} );
	}

	ListingForm.prototype.uploadImages = function (data) {
		var fd = new FormData();

		if (this.$form.closest( '.stm_edit_car_form' ).length) {
			fd.append( 'stm_edit', 'update' );
		}

		fd.append( 'action', 'stm_ajax_add_a_car_media' );
		fd.append( 'security', stm_security_nonce );
		fd.append( 'post_id', data.post_id );
		fd.append( 'redirect_type', data.redirect_type );

		$.each(
			this.userFiles,
			function (i, file) {
				if (typeof (file) !== undefined && typeof (file) === 'number') {
					fd.append( 'media_position_' + i, file );
				}
			}
		);

		$.ajax(
			{
				type: 'POST',
				url: ajaxurl,
				data: fd,
				contentType: false,
				processData: false,
				context: this,
				success: function (response) {
					var responseObj;

					//progressbar set 100%
					this.progress.finish();

					if (typeof (response) != 'object') {
						responseObj = JSON.parse( response );
					} else {
						responseObj = response;
					}
					if (responseObj.allowed_posts) {
						$( '.stm-posts-available-number span' ).text( responseObj.allowed_posts );
					}
					this.$loader.removeClass( 'activated' );
					if (responseObj.message) {
						this.$message.html( responseObj.message ).slideDown();
					}
					if (responseObj.url) {
						window.location = responseObj.url;
					}
				}
			}
		);
	};

	ListingForm.prototype.sortImages = function () {
		$( ".stm-media-car-gallery .stm-placeholder" ).each(
			function () {
				$( this ).trigger( 'blur' );
				$( this ).find( ".inner" ).removeClass( "active" );
				$( this ).find( ".stm-image-preview" ).trigger( 'blur' );
			}
		);

		var _this = this;

		setTimeout(
			function () {
				var tmpArr = [];

				$( '.stm-placeholder.stm-placeholder-generated' ).each(
					function (i, e) {
						/*Get old id*/
						var oldId = $( this ).find( '.stm-image-preview' ).attr( 'data-id' );

						/*Set new ids to preview and to delete icon*/
						$( this ).find( '.stm-image-preview' ).attr( 'data-id', i );
						$( this ).find( '.stm-image-preview .fas' ).attr( 'data-id', i );

						if (typeof (_this.userFiles[oldId]) !== 'undefined') {
							tmpArr[i] = _this.userFiles[oldId];
						}
					}
				);

				_this.featuredId = 0;
				_this.userFiles  = tmpArr;
			},
			100
		);
	};

	ListingForm.prototype.uploadProgress = function (image, percent, message = '') {
		let _this    = this,
			_wrapper = image.find( '.' + _this.bar_prefix + '__wrapper' ),
			_percent = _wrapper.find( '.' + _this.bar_prefix + '__percent' ),
			_text    = _wrapper.find( '.' + _this.bar_prefix + '__text' ),
			_bar     = _wrapper.find( '.' + _this.bar_prefix );

		if (false === percent) {
			_percent.hide();
			_bar.hide();
		} else {
			_percent.html( percent + '%' );
		}

		if ('optimization' === message) {
			_text.html( _this.imageSettings.messages.optimizing_image );
		} else if ('upload' === message) {
			_text.html( _this.imageSettings.messages.wait_upload );
		} else if ('rendering' === message) {
			_text.html( _this.imageSettings.messages.rendering );
		} else if ('error' === message) {
			_text.html( _this.imageSettings.messages.ajax_error );
		} else if ('remove' === message) {
			_wrapper.remove();
		} else if (message) {
			_text.html( message );
		}
	};

	ListingForm.prototype.uploadError = function (image, message, index) {
		let _this = this;

		_this.uploadProgress( image, false, message );

		setTimeout(
			function () {
				delete _this.userFiles[index];
				_this.placeholder.remove();
			},
			3000
		);
	};

	ListingForm.prototype.onImagePicked = function (event) {
		let _this                   = this,
			filesLength             = _this.userFiles.length,
			multiPlanUploadedImages = null,
			inputFiles              = event.target.files;

		inputFiles = Array.prototype.slice.call( inputFiles );

		const diff              = filesLength + inputFiles.length - _this.imageSettings.upload_limit.max;
		const multiPlanImgLimit = _this.imageSettings.upload_multi_plans_limit;
		if ( multiPlanImgLimit ) {
			multiPlanUploadedImages = filesLength + inputFiles.length;
			if (_this.selectedPlan) {
				const limit         = multiPlanImgLimit[_this.selectedPlan].limit;
				const errorMessage  = multiPlanImgLimit[_this.selectedPlan].text;
				const diffMultiPlan = multiPlanUploadedImages - limit
				if (diffMultiPlan > 0) {
					for (let i = 1; i <= diffMultiPlan; i++) {
						inputFiles.splice( inputFiles.length - i, 1 );
					}

					alert( errorMessage );
				}
			}
		} else {
			if (diff > 0) {
				for (let i = 1; i <= diff; i++) {
					inputFiles.splice( inputFiles.length - i, 1 );
				}

				alert( _this.imageSettings.messages.limit );
			}
		}

		if ( inputFiles.length > 0 ) {
			$( 'button[type="submit"]', _this.formUser ).removeClass( 'enabled' ).addClass( 'disabled' );
		}

		[].forEach.call(
			inputFiles,
			function (file) {
				_this.userFiles.push( file );

				let index = _this.userFiles.length - 1;

				$( '.stm-media-car-gallery .stm-placeholder-native' )
					.before(
						'<div class="stm-placeholder stm-placeholder-generated"><div class="inner">' +
						'<div class="stm-image-preview" data-id="' + index + '">' +
						'<div class="' + _this.bar_prefix + '__wrapper">' +
						'<div class="' + _this.bar_prefix + '">' +
						'<span class="' + _this.bar_prefix + '__item"></span>' +
						'<span class="' + _this.bar_prefix + '__item"></span>' +
						'<span class="' + _this.bar_prefix + '__item"></span>' +
						'<span class="' + _this.bar_prefix + '__item"></span>' +
						'</div>' +
						'<span class="' + _this.bar_prefix + '__info">' +
						'<span class="' + _this.bar_prefix + '__text"></span>' +
						'<span class="' + _this.bar_prefix + '__percent">0%</span>' +
						'</span>' +
						'</div>' +
						'<i class="fas fa-times" data-id="' + index + '"></i>' +
						'</div></div>'
					);

				let image_preview = $( '.stm-image-preview[data-id="' + index + '"]' );

				_this.placeholder = image_preview.parents( '.stm-placeholder' );

				if ( ! _this.allowedExt.exec( file.name )) {
					delete _this.userFiles[index];
					_this.placeholder.remove();
					alert( _this.imageSettings.messages.format + ' ' + file.name );
					return;
				}

				if (file.size > _this.imageSettings.size) {
					delete _this.userFiles[index];
					_this.placeholder.remove();
					alert( _this.imageSettings.messages.large + ' ' + file.name );
					return;
				}

				if (typeof (file) === 'object') {
					if (_this.imageSettings.cropping.enable) {
						_this.resizeImage( file, image_preview ).then(
							function (newFile) {
								file = newFile;

								_this.uploadProgress( image_preview, 100 );

								_this.uploadImage( file, image_preview, index );
							}
						);
					} else {
						_this.uploadProgress( image_preview, 100 );

						_this.uploadImage( file, image_preview, index );
					}
				}
			},
		);

		$( '.stm_add_car_form input[type="file"]' ).val( '' );
	};

	ListingForm.prototype.checkMultiPlanImgLimit = function (event, validate) {
		if ( ! validate ) {
			this.selectedPlan     = event.target.value;
			const multiPlansLimit = this.imageSettings.upload_multi_plans_limit;
			if (multiPlansLimit && this.selectedPlan) {
				const limit        = multiPlansLimit[this.selectedPlan].limit;
				const errorMessage = multiPlansLimit[this.selectedPlan].text;
				if (this.userFiles.length > limit) {
					const photosStep = document.querySelector( '.stm-form-3-photos' );
					if (photosStep) {
						const elementPosition = photosStep.getBoundingClientRect().top + window.scrollY;
						const offsetPosition  = elementPosition - 150;

						window.scrollTo(
							{
								top: offsetPosition,
								behavior: 'smooth'
							}
						);
					}
					alert( errorMessage );
				}
			}
		} else {
			const multiPlansLimit = this.imageSettings.upload_multi_plans_limit;
			if (multiPlansLimit && this.selectedPlan) {
				const limit        = multiPlansLimit[this.selectedPlan].limit;
				const errorMessage = multiPlansLimit[this.selectedPlan].text;
				if (this.userFiles.length > limit) {
					const photosStep = document.querySelector( '.stm-form-3-photos' );
					if (photosStep) {
						const elementPosition = photosStep.getBoundingClientRect().top + window.scrollY;
						const offsetPosition  = elementPosition - 150;

						window.scrollTo(
							{
								top: offsetPosition,
								behavior: 'smooth'
							}
						);
					}
					alert( errorMessage );
					return false;
				}
				return true;
			}
			return true;
		}
	};

	ListingForm.prototype.onDropped = function (event, ui) {
		var dragFrom      = ui.draggable.closest( '.inner' );
		var dragTo        = $( event.target ).find( '.inner' );
		var dragToPreview = dragTo.find( '.stm-image-preview' );

		if (ui.draggable.length > 0 && dragToPreview.length > 0 && dragTo.length > 0 && dragFrom.length > 0) {

			if (dragFrom[0] !== dragTo[0]) {

				ui.draggable.clone().appendTo( dragTo );
				dragToPreview.clone().appendTo( dragFrom );

				/*If placed in first pos*/
				if (dragTo.closest( '.stm-placeholder' ).index() === 0) {
					$( '.stm-media-car-main-input .stm-image-preview' ).remove();
					ui.draggable.clone().appendTo( '.stm-media-car-main-input' );
					this.featuredId = ui.draggable.data( 'id' );
				}

				/*If moving from first place*/
				if (ui.draggable.closest( '.stm-placeholder' ).index() === 0) {
					$( '.stm-media-car-main-input .stm-image-preview' ).remove();
					dragToPreview.clone().appendTo( '.stm-media-car-main-input' );
					this.featuredId = dragToPreview.data( 'id' );
				}

				ui.draggable.remove();
				dragToPreview.remove();

				this.sortImages();
				this.orderChanged = true;
			}
		}
	};

	ListingForm.prototype.imageRemove = function (event) {
		var stm_id = $( event.target ).attr( 'data-id' );
		delete this.userFiles[stm_id];
		$( '.stm-placeholder .inner' ).removeClass( 'deleting' );

		$( event.target ).closest( '.stm-placeholder' ).remove();

		this.sortImages();
	};

	ListingForm.prototype.validateFields = function () {
		var hasRequired = false;

		this.$message.slideUp();

		this.$form.find( '.stm-form-1-end-unit, .stm-form1-intro-unit' ).find( 'select' ).each(
			function () {
				$( this ).data( 'select2' ).$container.removeClass( 'reuqired_field' );
				if ($( this ).attr( 'required' ) && $( this ).find( ':selected' ).val() == '') {
					$( this ).data( 'select2' ).$container.addClass( 'reuqired_field' );
					hasRequired = true;
				}
			}
		);

		this.$form.find( '.stm-form-1-end-unit' ).find( 'input' ).each(
			function () {
				$( this ).removeClass( 'reuqired_field' );
				if ($( this ).attr( 'required' ) && $( this ).val() == '') {
					$( this ).addClass( 'reuqired_field' );
					hasRequired = true;
				}
			}
		);

		if ( ! this.checkMultiPlanImgLimit( null, true ) ) {
			return true;
		}

		if (hasRequired) {
			this.$message.html( 'Please enter required fields' ).slideDown();
		}

		return hasRequired;
	};

	$( document ).ready(
		function () {

			var $form = $( '#stm_sell_a_car_form' ), listingForm = new ListingForm( $form );

			//window.hasOwnProperty = window.hasOwnProperty || Object.prototype.hasOwnProperty;

			/*Sell a car*/
			if (typeof stmUserFilesLoaded !== 'undefined') {
				listingForm.userFiles = stmUserFilesLoaded;
			}

			if (typeof stm_image_upload_settings !== 'undefined') {
				listingForm.imageSettings = stm_image_upload_settings;
			}

			if (listingForm.$form.closest( '.stm_edit_car_form' ).length) {
				listingForm.post_id = listingForm.$form.find( 'input[name="stm_current_car_id"]' ).val();
			}

			$( document ).on(
				'mouseenter touchstart',
				'.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas',
				function () {
					$( this ).closest( '.inner' ).addClass( 'deleting' );
				}
			);

			$( document ).on(
				'mouseleave touchend',
				'.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas',
				function () {
					$( this ).closest( '.inner' ).removeClass( 'deleting' );
				}
			);

			/*Droppable*/
			$( document ).on(
				"mouseenter touchstart",
				'.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview',
				function (e) {
					$( this ).draggable(
						{
							revert: 'invalid',
							helper: "clone"
						}
					)
				}
			);

			$( document ).on(
				"mouseenter touchstart click",
				".stm-media-car-gallery .stm-placeholder .inner",
				function () {
					$( ".stm-media-car-gallery .stm-placeholder" ).each(
						function () {
							$( this ).trigger( 'blur' );
							$( this ).find( ".inner" ).removeClass( "active" );
							$( this ).find( ".stm-image-preview" ).trigger( 'blur' );
						}
					);

					$( this ).addClass( "active" );
				}
			);

			$( '.stm-form-checking-user button[type="submit"]' ).on(
				'click',
				function (e) {
					e.preventDefault();

					if (listingForm.validateFields()) {
						return;
					}

					if ( ! $( this ).hasClass( 'disabled' )) {

						$( listingForm.progressWrap ).show();
						listingForm.progress.start();

						var loadType = $( this ).attr( 'data-load' );
						$( 'input[name="btn-type"]' ).val( loadType );

						$( this ).removeClass().addClass( 'disabled' );
						$form.trigger( 'submit' );
					}
				}
			);
		}
	);
})( jQuery );
