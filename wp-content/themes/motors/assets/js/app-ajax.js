"use strict";
(function($) {

	function SearchBox (form) {
		let body = $( 'body' );

		STMListings.SearchBox.form    = form;
		STMListings.SearchBox.listing = ( body.hasClass( 'stm-template-aircrafts' ) && ! body.hasClass( 'single-listings' ) );
		STMListings.SearchBox.prototype.init();
	}

	SearchBox.prototype.init = function () {
		var timer, self = this;

		$( 'select:not(.hide)', STMListings.SearchBox.form ).select2().on(
			'select2:selecting',
			function ( event ) {
				if ( timer ) {
					clearTimeout( timer );
				}

				STMListings.SearchBox.form = $( event.target ).closest( 'form' );

				timer = setTimeout( $.proxy( self.performAjax, self ), 300 );
			}
		);

		$( document ).on(
			'slidestop',
			'.taxonomy_range_wrap .ui-slider-wrap',
			function (event, ui) {
				let min = ui.values.shift(),
				max     = ui.values.shift(),
				$el     = $( event.target ).closest( '.taxonomy_range_wrap' );

				if ( timer ) {
					clearTimeout( timer );
				}

				if ( min && max ) {
					$( '.stm-min-value', $el ).val( min );
					$( '.stm-max-value', $el ).val( max );

					STMListings.SearchBox.form = $el.closest( 'form' );

					timer = setTimeout( $.proxy( self.performAjax, self ), 300 );
				}
			}
		);
	};

	SearchBox.prototype.fragments = function () {
		return 'total' + ( ! STMListings.SearchBox.listing ? ',options' : '') + ( $( STMListings.SearchBox.form ).hasClass( 'stm-review-search-form' ) ? ',listings' : '' );
	};

	SearchBox.prototype.performAjax = function () {
		let $_form   = $( STMListings.SearchBox.form ),
			noCascad = $_form.find( '.no-cascading' ),
			stmUrl   = $_form.attr( 'action' );

		//start if enable UserFriendlyURLs
		if ( stm_theme_config.enable_friendly_urls.length > 0 ) {
			var formPath = '';

			$_form.serializeArray().forEach(
				function (val) {
					let slug = val.name.replace( 'min_', '' ).replace( 'max_', '' );
					if ( ! stm_cats_conf[slug] && val.value !== '' && val.name !== 'posttype') {
						formPath += val.value + '/';
					}
				}
			);

			$_form.serializeArray().forEach(
				function (val) {
					let slug = val.name.replace( 'min_', '' ).replace( 'max_', '' );
					if ( $_form.parents('.multilisting-search-tabs-wrap').length ) {
						if ( stm_cats_conf[slug] && val.value !== '') {
							formPath += formPath.indexOf( '?' ) < 0 ? '?' : '&';
							formPath += val.name + '=' + val.value;
						}
					} else {
						if ( ( stm_cats_conf[slug] || val.name === 'posttype' ) && val.value !== '') {
							formPath += formPath.indexOf( '?' ) < 0 ? '?' : '&';
							formPath += val.name + '=' + val.value;
						}
					}
				}
			);

			if ( ! $_form.hasClass( 'stm-review-search-form' ) ) {
				$_form.attr( 'data-action', stmUrl + formPath );
			}
		}
		//end if enable UserFriendlyURLs

		var fragments = ( ! $_form.closest( '.stm-vc-ajax-filter' ).hasClass( 'filter' )) ? '&fragments=' + STMListings.SearchBox.prototype.fragments() : '';

		noCascad.val( noCascad.attr( 'data-val' ) );

		$.ajax(
			{
				url: $_form.attr( 'action' ),
				dataType: 'json',
				context: this,
				data: $_form.serialize() + '&ajax_action=listings-binding' + fragments + '&hide_empty=true&security=' + stm_security_nonce,
				beforeSend: $.proxy( this.beforeAjax, STMListings.SearchBox.prototype ),
				success: $.proxy( this.success, STMListings.SearchBox.prototype ),
				complete: $.proxy( this.complete, STMListings.SearchBox.prototype )
			}
		);

		//start if enable UserFriendlyURLs
		if ( stm_theme_config.enable_friendly_urls.length > 0 && $_form[0].hasAttribute( 'data-action' ) && $_form.data( 'action' ) !== '' ) {
			$_form.on(
				'submit',
				function (e) {
					e.preventDefault();
					window.location = $_form.attr( 'data-action' );
				}
			);
		}
		//end if enable UserFriendlyURLs
	};

	SearchBox.prototype.beforeAjax = function () {
		let $body   = $( 'body' ),
			$_form  = STMListings.SearchBox.form,
			s_arrow = $( '.select2-container--default .select2-selection--single .select2-selection__arrow b', $_form );

		if ( ! STMListings.SearchBox.listing || $( $_form ).closest( '.stm-vc-ajax-filter' ).hasClass( 'filter' )) {
			$( 'select, button[type="submit"]', $_form ).prop( "disabled", true );
			s_arrow.addClass( 'stm-preloader' );
		}

		if ( $body.hasClass( 'stm-template-aircrafts' ) ) {
			s_arrow.addClass( 'stm-preloader' );
		}
	};

	SearchBox.prototype.success = function (data) {
		let $body  = $( 'body' ),
			$_form = STMListings.SearchBox.form;

		if ( ! STMListings.SearchBox.listing || $( $_form ).closest( '.stm-vc-ajax-filter' ).hasClass( 'filter' )) {
			/* Disable options */
			if ( typeof data.options != 'undefined' ) {
				$.each(
					data.options,
					function (key, options) {
						$( 'select[name=' + key + '] > option', $_form ).each(
							function () {
								var slug = $( this ).val();
								if (options.hasOwnProperty( slug )) {
									$( this ).prop( 'disabled', options[slug].disabled );
								}
							}
						);
					}
				);
			}

			$( 'select:not(.hide)', $_form ).select2( "destroy" ).select2();

			if ( $body.hasClass( 'stm-template-motorcycle' ) ) {
				$( '.stm_mc-found .number-found', $_form ).text( data.total );
			}
		} else if ( $body.hasClass( 'stm-template-aircrafts' ) ) {
			$( '.select2-container--default .select2-selection--single .select2-selection__arrow b', $_form ).removeClass( 'stm-preloader' );
		}

		var total_result = data.total;

		if ( typeof data.listings !== 'undefined' && data.listings.length && typeof listings_with_reviews !== 'undefined' ) {
			var listings_with_reviews_parsed = JSON.parse( listings_with_reviews );
			if ( listings_with_reviews_parsed ) {
				var matched  = listings_with_reviews_parsed.filter(
					function (n) {
						return data.listings.indexOf( n ) !== -1;
					}
				);
				total_result = matched.length;
			}
		}

		$( 'button[type="submit"] span', $_form ).text( total_result );
	};

	SearchBox.prototype.complete = function () {
		let $_form = STMListings.SearchBox.form;

		if ( ! STMListings.SearchBox.listing || $( $_form ).closest( '.stm-vc-ajax-filter' ).hasClass( 'filter' )) {
			$( '.select2-container--default .select2-selection--single .select2-selection__arrow b', $_form ).removeClass( 'stm-preloader' );
			$( 'select, button[type="submit"]', $_form ).prop( "disabled", false );
		}
	};

	STMListings.SearchBox = SearchBox;

	$.cookie.raw = true;

	var Compare = STMListings.Compare = function () {
		this.init();
		this.setCount();
		this.activateLinks();
		this.bind();
	};

	Compare.prototype.init = function () {
		// used in Remove All popup button
		var removal_post_type = '';

		var ids = {};
		if ( typeof compare_init_object !== 'undefined' ) {
			this.ids = compare_init_object;
		}
	};

	Compare.prototype.setCount = function () {
		let total_count = 0;
		$.each(
			this.ids,
			function(key, val){
				total_count += val.length;
			}
		);

		$( '[data-contains=compare-count]' ).text( total_count );

		if ($( '.stm-current-cars-in-compare' ).length > 0) {
			$( '.stm-current-cars-in-compare' ).html( total_count );
		}
	};

	Compare.prototype.manageCookieAjax = function (e) {
		console.log( 'CompareCookieAjax' );
		e.preventDefault();
		var target        = $( e.target ).closest( '[data-id]' );
		var dataId        = target.attr( 'data-id' );
		var dataAction    = target.attr( 'data-action' );
		var post_type     = target.attr( 'data-post-type' );
		var _compare_this = this;

		if (typeof dataAction === 'undefined') {
			dataAction = 'add';
		}

		if (typeof dataId === 'undefined' || typeof post_type === 'undefined') {
			return;
		}

		let single_compare = $( '.single-add-to-compare' );

		$.ajax(
			{
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: '&post_id=' + dataId + '&post_action=' + dataAction + '&action=stm_ajax_add_to_compare&security=' + stm_security_nonce,
				context: this,
				beforeSend: () => {
					target.addClass( 'disabled' );
					single_compare.removeClass( [ 'success', 'danger', 'warning' ] );
				},
				success: function (data) {
					single_compare.addClass( 'single-add-to-compare-visible' );

					if (typeof data.response != 'undefined') {
						$( '.stm-title', single_compare ).text( data.response );
					}

					if (typeof data.status != 'undefined') {
						single_compare.addClass( data.status );
					}

					if (typeof data.length != 'undefined') {
						$( '.stm-current-cars-in-compare' ).text( data.length );
					}

					setTimeout(
						function () {
							single_compare.removeClass( 'single-add-to-compare-visible' );
						},
						5000
					);

					if (data.status === 'danger') {
						return;
					}

					if (dataAction == 'add') {
						_compare_this.ids[post_type].push( dataId );
					} else if (dataAction == 'remove') {
						var index = _compare_this.ids[post_type].indexOf( dataId );
						_compare_this.ids[post_type].splice( index, 1 );
					}

					target.hide().siblings( '[data-id]' ).show();

					if (target.hasClass( 'stm_remove_after' )) {
						window.location.reload();
					}
				},
				complete: function () {
					target.removeClass( 'disabled' );
					_compare_this.setCount();
				}
			}
		);
	};

	Compare.prototype.manageCookieLocal = function (e) {
		console.log( 'CompareCookieLocal' );
		e.preventDefault();
		var $el           = $( e.currentTarget );
		var id            = $el.data( 'id' ).toString();
		var stm_car_title = $el.data( 'title' );
		var post_type     = $el.data( 'post-type' );

		if ( typeof post_type == 'undefined' ) {
			return;
		}

		let single_compare = $( '.single-add-to-compare' );

		if ( this.ids[post_type].indexOf( id ) === -1 ) {
			if (this.ids[post_type].length < 3) {
				$.cookie( cc_prefix + post_type + '[' + id + ']', id, {expires: 7, path: '/'} );
				this.ids[post_type].push( id );
				$el.addClass( 'active' );
				$( '.stm-listing-compare, .compare' ).attr( 'data-original-title', stm_i18n.remove_from_compare );
				$( '.stm-listing-compare, .compare' ).tooltip( 'hide' );

				// Added popup
				$( '.single-add-to-compare .stm-title' ).text( stm_car_title + ' - ' + stm_added_to_compare_text );
				$( '.single-add-to-compare' ).addClass( 'single-add-to-compare-visible' );
				setTimeout(
					function () {
						$( '.single-add-to-compare' ).removeClass( 'single-add-to-compare-visible' );
					},
					5000
				);
				// Added popup
			} else {
				// Already added 3 popup
				$( '.single-add-to-compare .stm-title' ).text( stm_already_added_to_compare_text );
				$( '.single-add-to-compare' ).addClass( 'single-add-to-compare-visible' );
				setTimeout(
					function () {
						$( '.single-add-to-compare' ).removeClass( 'single-add-to-compare-visible' );
						$( '.single-add-to-compare' ).removeClass( 'overadded' );
						$( '.compare-remove-all' ).remove();
					},
					5000
				);
				// Already added 3 popup
				$( '.single-add-to-compare' ).addClass( 'overadded' );
				$( '.compare-remove-all' ).remove();
				$( '.single-add-to-compare .compare-fixed-link' ).before( '<a href="#" style="margin-left: 15px;" class="compare-fixed-link compare-remove-all pull-right heading-font">' + resetAllTxt + '</a>' );
				this.removal_post_type = post_type;
			}
		} else {
			$.removeCookie( cc_prefix + post_type + '[' + id + ']', {path: '/'} );
						$( '.stm-listing-compare, .compare' ).attr( 'data-original-title', stm_i18n.add_to_compare );
						$( '.stm-listing-compare, .compare' ).tooltip( 'hide' );
			this.ids[post_type].splice( this.ids[post_type].indexOf( id ), 1 );
			$el.removeClass( 'active' );

			//Deleted from compare text
			$( '.stm-title', single_compare ).text( stm_car_title + ' ' + stm_removed_from_compare_text );
			single_compare.addClass( 'single-add-to-compare-visible' );
			setTimeout(
				function () {
					single_compare.removeClass( 'single-add-to-compare-visible' );
				},
				5000
			);
			//Deleted from compare text

			$( '.single-add-to-compare' ).removeClass( 'overadded' );
			$( '.compare-remove-all' ).remove();
		}
		this.setCount();
	};

	Compare.prototype.remove = function (e) {
		e.preventDefault();
		var $el                   = $( e.currentTarget );
		var dataId                = $el.attr( 'data-id' );
		var dataAction            = $el.attr( 'data-action' );
		var post_type             = $el.attr( 'data-post-type' );
				var _compare_this = this;

		if (typeof dataId === 'undefined' || typeof post_type === 'undefined') {
			return;
		}

		$.ajax(
			{
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: '&post_id=' + dataId + '&post_action=' + dataAction + '&action=stm_ajax_add_to_compare&security=' + stm_security_nonce,
				context: this,
				beforeSend: function (data) {
					$el.addClass( 'loading' );

					if (parseFloat( $( '.stm-current-cars-in-compare' ).text() ) > 0) {
						$( '.stm-current-cars-in-compare' ).text( parseFloat( $( '.stm-current-cars-in-compare:first' ).text() ) - 1 );
					}

					$( '.car-listing-row .compare-col-stm-' + dataId ).hide(
						'slide',
						{direction: 'left'},
						function () {
							$( '.car-listing-row .compare-col-stm-' + dataId ).remove();
							$( '.car-listing-row' ).append( $( '.compare-empty-car-top' ).html() );
						}
					);

					$( '.stm-compare-row .compare-col-stm-' + dataId ).hide(
						'slide',
						{direction: 'left'},
						function () {
							$( '.stm-compare-row .compare-col-stm-' + dataId ).remove();
							$( '.stm-compare-row' ).append( $( '.compare-empty-car-bottom' ).html() );
						}
					);

					$( '.row-compare-features .compare-col-stm-' + dataId ).hide(
						'slide',
						{direction: 'left'},
						function () {
							$( '.row-compare-features .compare-col-stm-' + dataId ).remove();
							if ($( '.row-compare-features .col-md-3' ).length < 2) {
								$( '.row-compare-features' ).slideUp();
							}
						}
					);

					$( '.add-compare-mobile' ).addClass( 'add-compare-mobile-show' );

					// multilisting buttons decrease counter
					if ( $( '.multilisting_compare_type_buttons .btn-primary' ).length > 0 ) {
						var current_count = $( '.multilisting_compare_type_buttons .btn-primary span.badge' ).html();
						if ( typeof current_count !== undefined && parseInt( current_count ) > 0 ) {
							var result = parseInt( current_count ) - 1;
							$( '.multilisting_compare_type_buttons .btn-primary span.badge' ).html( result );
						}
					}
				},
				success: function (data) {
					if (dataAction == 'add') {
						_compare_this.ids[post_type].push( dataId );
					} else if (dataAction == 'remove') {
						var index = _compare_this.ids[post_type].indexOf( dataId );
						_compare_this.ids[post_type].splice( index, 1 );
					}
				},
				complete: function () {
					_compare_this.setCount();
				}
			}
		);
	};

	Compare.prototype.removeAll = function (e) {
		e.preventDefault();

		var post_type = this.removal_post_type;

		if ( typeof post_type !== 'undefined' && post_type.length > 0 ) {
			$.each(
				this.ids[post_type],
				function (i, id) {
					$.removeCookie( cc_prefix + post_type + '[' + id + ']', {path: '/'} );
				}
			);

			this.ids[post_type] = [];
			this.activateLinks();
			this.setCount();
			location.reload();
		}
	};

	Compare.prototype.activateLinks = function (ctx) {
		$.each(
			this.ids,
			function (post_type, value) {

				if (value.length == 0) {
					return;
				}

				$.each(
					value,
					function(index, post_id){
						$( '[data-compare-id=' + post_id + ']', ctx ).each(
							function () {
								$( 'a', this ).eq( 0 ).show();
								$( 'a', this ).eq( 1 ).hide();
							}
						);

						var data_placement = $( this ).data( 'placement' );
						if (typeof data_placement === undefined) {
							var data_placement = 'auto';
						}

						$( '.stm-compare-directory-new, .stm-listing-compare, .stm-gallery-action-unit.compare', ctx )
							.filter( '[data-id=' + post_id + ']' )
							.addClass( 'active' )
							.tooltip( 'destroy' )
							.attr( 'title', stm_i18n.remove_from_compare )
							.tooltip( {placement: data_placement} );
					}
				);
			}
		);
	};

	Compare.prototype.bind = function () {
		$( document ).on( 'click', '.add-to-compare', $.proxy( this.manageCookieAjax, this ) );
		$( document ).on( 'click', '.ev_add_compare_link, .stm-user-public-listing .stm-listing-compare, .image .stm-listing-compare, .listing-list-loop.stm-listing-directory-list-loop .stm-listing-compare, .stm-gallery-action-unit.compare, .stm-compare-directory-new', $.proxy( this.manageCookieLocal, this ) );
		$( document ).on( 'click', '.remove-from-compare', $.proxy( this.remove, this ) );
		$( document ).on( 'click', '.compare-remove-all', $.proxy( this.removeAll, this ) );
	};

	var Favorites = STMListings.Favorites = function () {
		$( 'body.logged-in' ).on( 'click', '.stm-listing-favorite, .stm-listing-favorite-action', this.clickUser );
		$( 'body.stm-user-not-logged-in' ).on( 'click', '.stm-listing-favorite, .stm-listing-favorite-action', this.clickGuest );

		this.ids = $.cookie( 'stm_car_favourites' );

		if (this.ids) {
			this.ids = this.ids.split( ',' );
		} else {
			this.ids = [];
		}

		var _this = this;
		if ($( 'body' ).hasClass( 'logged-in' )) {
			$.getJSON(
				ajaxurl,
				{action: 'stm_ajax_get_favourites', security: stm_security_nonce},
				function (data) {
					_this.ids = data;
					_this.activateLinks();
				}
			);
		} else {
			this.activateLinks();
		}
	};

	Favorites.prototype.clickUser = function (e) {
		e.preventDefault();

		if ($(this).hasClass('disabled')) {
			return false;
		}

		var $button = $( this );
		$button.tooltip( 'hide' );

		$button.toggleClass( 'active' );
		var stm_car_add_to = $button.data( 'id' );

		$.ajax(
			{
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: '&car_id=' + stm_car_add_to + '&action=stm_ajax_add_to_favourites&security=' + stm_security_nonce,
				context: this,
				beforeSend: function (data) {
					$button.addClass( 'disabled' );
				},
				success: function (data) {
					if (data.count) {
						$( '.stm-my-favourites span' ).text( data.count );
					}
					$button.removeClass( 'disabled' );
					updateTooltip( $button );
				}
			}
		);
	};

	Favorites.prototype.clickGuest = function (e) {
		e.preventDefault();

		var $button = $( this );

		$button.toggleClass( 'active' );

		$button.tooltip( 'hide' );

		var stm_cookies        = $.cookie();
		var stm_car_add_to     = $button.data( 'id' );
		var stm_car_favourites = [];

		if (typeof (stm_cookies['stm_car_favourites']) !== 'undefined') {
			stm_car_favourites = stm_cookies['stm_car_favourites'].split( ',' );
			var index          = stm_car_favourites.indexOf( stm_car_add_to.toString() );
			if (index !== -1) {
				stm_car_favourites.splice( index, 1 );
			} else {
				stm_car_favourites.push( stm_car_add_to.toString() );
			}

			stm_car_favourites = stm_car_favourites.join( ',' );
			$.cookie( 'stm_car_favourites', stm_car_favourites, {expires: 7, path: '/'} );

		} else {
			$.cookie( 'stm_car_favourites', stm_car_add_to.toString(), {expires: 7, path: '/'} );
		}

		updateTooltip( $button );
	};

	function updateTooltip($button) {
		if ($button.hasClass( 'active' )) {
			$button.attr( 'data-original-title', stm_i18n.remove_from_favorites );
		} else {
			$button.attr( 'data-original-title', stm_i18n.add_to_favorites );
		}
	}

	Favorites.prototype.activateLinks = function (ctx) {
		$.each(
			this.ids,
			function (key, value) {
				if ( ! value) {
					return;
				}

				$( '.stm-listing-favorite, .stm-listing-favorite-action', ctx )
				.filter( '[data-id=' + value + ']' )
				.addClass( 'active' )
				.tooltip( 'hide' )
				.attr( 'title', stm_i18n.remove_from_favorites )
				.tooltip( {placement: 'auto'} );

				$( '.stm-listing-favorite.active' )
					.tooltip( 'destroy' )
					.tooltip();

				$( '.stm-user-private-main' ).find( '.stm-listing-favorite.active' )
					.tooltip( 'destroy' )
					.tooltip()
					.tooltip( {placement: 'auto'} );
			}
		);
	};

	var stm_single_filter_link = false;
	var stmIsotope;
	var $container = $( '.stm-isotope-sorting' );

	$( document ).ready(
		function () {
			let $body = $( 'body' );

			/*Terms and conditions rental*/
			$( document ).on(
				"click",
				'.stm-template-car_rental .form-row.terms a',
				function (event) {
					event.preventDefault();

				}
			);

			/*SHOW NOTIFICATION POPUP IF CAR NOT IN STOCK IN SELECTED OFFICE (STEP 1 RESERVATION)*/
			if ( $body.hasClass( 'stm-template-rental-daypicker-page' ) ) {
				$( 'select[name="pickup_location"]' ).on(
					'change',
					function () {
						var locationId = $( this ).select2( "val" );

						$.ajax(
							{
								url: ajaxurl,
								type: "GET",
								dataType: 'json',
								context: this,
								data: 'rental_office_id=' + locationId + '&action=stm_ajax_rental_check_car_in_current_office&security=' + stm_security_nonce,
								success: function (data) {
									$( "#select-vehicle-popup" ).attr( "href", $( "#select-vehicle-popup" ).attr( 'href' ).split( "?" )[0] + "?pickup_location=" + locationId );
									if (data == 'EMPTY') {
										$( '.choose-another-class' ).addClass( 'single-add-to-compare-visible' );
										setTimeout(
											function () {
												$( '.choose-another-class' ).removeClass( 'single-add-to-compare-visible' );
											},
											5000
										);
									}
								}
							}
						);
					}
				);

			}

			stm_ajax_filter_remove_hidden();
			stm_ajax_add_test_drive();
			stm_ajax_add_trade_offer();
			clearFilter();
			clearMagazineFilter();
			stm_filter_links();

			stm_favourites();

			loadMoreDealerCars();
			sortByListingType();
			loadMoreDealerReviews();

			//Isotope
			stm_sort_listing_cars();
			stm_modern_filter_isotope();

			//Clear select value from listing badge
			$body.on(
				'click',
				'#modern-filter-listing .stm-clear-listing-one-unit',
				function () {
					let selectToClear = $( this ).data( 'select' );

					if ( typeof selectToClear !== "undefined" ) {
						let $priceRange = $( '#stm-' + selectToClear + '-range' );

						if ( selectToClear !== 'price' && $( 'select[name=' + selectToClear + ']' ).length > 0 ) {
							$( 'select[name=' + selectToClear + ']' ).val( '' ).trigger( 'change' );

							if ($priceRange.length > 0) {
								$priceRange.slider( 'values', [stmOptionsObj.min, stmOptionsObj.max] );
								$( "#stm_filter_min_" + selectToClear ).val( stmOptionsObj.min );
								$( "#stm_filter_max_" + selectToClear ).val( stmOptionsObj.max );
							}

						} else {
							if ($priceRange.length > 0) {
								$priceRange.slider( 'values', [stmOptions.min, stmOptions.max] );
								$( "#stm_filter_min_" + selectToClear ).val( stmOptions.min );
								$( "#stm_filter_max_" + selectToClear ).val( stmOptions.max );
							}
						}
					}

				}
			);

			$body.on(
				'click',
				'.stm-clear-listing-one-unit-classic',
				function () {
					var selectToClear = $( this ).data( 'select' ); // ! data-select not defined in badges.php
					var clearLinkArg  = $( this ).data( 'url' );

					if (typeof selectToClear != 'undefined') {
						if (selectToClear.indexOf( '[' )) {
							window.location = clearLinkArg;
							return false;
						}
						if (selectToClear != 'price' && $( 'select[name=' + selectToClear + ']' ).length > 0) {
							window.location = clearLinkArg;
						} else {
							var $priceRange = $( '#stm-price-range' );
							if ($priceRange.length > 0) {
								$priceRange.slider( 'values', [stmOptions.min, stmOptions.max] );
								$( "#stm_filter_min_price" ).val( stmOptions.min );
								$( "#stm_filter_max_price" ).val( stmOptions.max );
								$( '.classic-filter-row form' ).trigger( 'submit' );
							}
						}
					}
				}
			);

			$body.on(
				'click',
				'#lmb-car-review',
				function (e) {
					e.preventDefault();
					stm_load_cars_with_review();
				}
			);
		}
	);

	function stm_ajax_filter_remove_hidden() {
		let $filter = $( '.filter-listing.stm-vc-ajax-filter, .filter.stm-vc-ajax-filter, .multilisting-search-tabs-wrap' ),
			$form   = $( 'form', $filter );

		$form.each(
			function () {
				new SearchBox( this );
			}
		);
	}

	function stm_favourites() {
		window.stm_compare = new Compare();
	}

	function stm_ajax_add_test_drive() {
		$( '#test-drive form' ).on(
			"submit",
			function (event) {
				event.preventDefault();
				$.ajax(
					{
						url: ajaxurl,
						type: "POST",
						dataType: 'json',
						context: this,
						data: $( this ).serialize() + '&action=stm_ajax_add_test_drive&security=' + stm_security_nonce,
						beforeSend: function () {
							$( '.alert-modal' ).remove();
							$( this ).closest( 'form' ).find( 'input' ).removeClass( 'form-error' );
							$( this ).closest( 'form' ).find( '.stm-ajax-loader' ).addClass( 'loading' );
						},
						success: function (data) {
							$( this ).closest( 'form' ).find( '.stm-ajax-loader' ).removeClass( 'loading' );
							$( this ).closest( 'form' ).find( '.modal-body-message' ).append( '<div class="alert-modal alert alert-' + data.status + ' text-left">' + data.response + '</div>' )
							for (var key in data.errors) {
								$( '#request-test-drive-form input[name="' + key + '"]' ).addClass( 'form-error' );
							}
							if (data.status === 'success') {
								$( this ).closest( 'form' ).find( '.modal-body-fields' ).addClass( 'hidden' );
								$( this ).closest( 'form' ).find( 'input' ).val( '' );
							}
						}
					}
				);
				$( this ).closest( 'form' ).find( '.form-error' ).on(
					'hover',
					function () {
						$( this ).removeClass( 'form-error' );
					}
				);
			}
		);
	}

	function stm_ajax_add_trade_offer() {
		$( '#trade-offer form' ).on(
			"submit",
			function (event) {
				event.preventDefault();
				$.ajax(
					{
						url: ajaxurl,
						type: "POST",
						dataType: 'json',
						context: this,
						data: $( this ).serialize() + '&action=stm_ajax_add_trade_offer&security=' + stm_security_nonce,
						beforeSend: function () {
							$( '.alert-modal' ).remove();
							$( this ).closest( 'form' ).find( 'input' ).removeClass( 'form-error' );
							$( this ).closest( 'form' ).find( '.stm-ajax-loader' ).addClass( 'loading' );
						},
						success: function (data) {
							$( this ).closest( 'form' ).find( '.stm-ajax-loader' ).removeClass( 'loading' );
							$( this ).closest( 'form' ).find( '.modal-body' ).append( '<div class="alert-modal alert alert-' + data.status + ' text-left">' + data.response + '</div>' )
							for (var key in data.errors) {
								$( '#request-trade-offer-form input[name="' + key + '"]' ).addClass( 'form-error' );
							}
						}
					}
				);
				$( this ).closest( 'form' ).find( '.form-error' ).on(
					'hover',
					function () {
						$( this ).removeClass( 'form-error' );
					}
				);
			}
		);
	}

	function clearFilter() {
		$( '.reset-all' ).on(
			'click',
			function (e) {
				e.preventDefault();

				$( this ).closest( '.filter' ).find( 'select' ).trigger( 'reset' );

				$( this ).closest( '.filter' ).find( 'select' ).find( 'option' ).each(
					function () {
						if ($( this ).attr( 'data-disabled' ) == 'disabled') {
							$( this ).attr( 'disabled', 'disabled' )
						}
					}
				)

				if ($( '.filter-price' ).length > 0) {
					$( '.stm-price-range' ).slider( 'values', [stmOptions.min, stmOptions.max] );
					$( "#stm_filter_min_price" ).val( stmOptions.min );
					$( "#stm_filter_max_price" ).val( stmOptions.max );
				}
			}
		);
	}

	function clearMagazineFilter() {
		$( '.c-r-remove-filter' ).on(
			'click',
			function(e){
				e.preventDefault();

				$( '.selected-filter' ).text( '' );
				$( this ).removeClass( 'active' );

				$( '#listing-with-review' ).find( 'select' ).trigger( 'reset' );

				$( '#listing-with-review' ).find( 'select' ).find( 'option' ).each(
					function(){
						if ( $( this ).attr( 'data-disabled' ) == 'disabled') {
							$( this ).attr( 'disabled','disabled' )
						}
					}
				)

				stm_load_cars_with_review( true );
			}
		);
	}

	function stm_sort_listing_cars() {
		// init Isotope
		if ($( '#modern-filter-listing .stm-isotope-sorting' ).length) {
			if (typeof imagesLoaded == 'function') {
				$( '.stm-isotope-sorting' ).imagesLoaded(
					function () {
						stmIsotope = $container.isotope(
							{
								itemSelector: '.stm-isotope-listing-item',
								layoutMode: 'fitRows',
								hiddenStyle: {
									opacity: 0
								},
								visibleStyle: {
									opacity: 1
								},
								transitionDuration: '0.5s',
								getSortData: {
									price: function (itemElem) {
										var price = $( itemElem ).data( 'price' );
										return parseFloat( price );
									},
									date: function (itemElem) {
										var date = $( itemElem ).data( 'date' );
										return parseFloat( date );
									},
									mileage: function (itemElem) {
										var mileage = $( itemElem ).data( 'mileage' );
										return parseFloat( mileage );
									}
								}
							}
						);
					}
				);
			}
			$( '#modern-filter-listing .stm-select-sorting select' ).select2().on(
				'change',
				function () {
					stm_isotope_sort_function( $( this ).select2( 'val' ) );
				}
			)
		}
	}

	function generateNumberFilter() {
		/*Generate number field*/
		var dataset = [];

		$( '.stm-isotope-listing-item' ).each(
			function () {
				var $this = $( this );

				var attributes = $this[0].attributes;

				for (var prop in attributes) {
					if (typeof attributes[prop].nodeName === 'undefined' || attributes[prop].nodeName == 'data-numeric-price') {
						continue;
					}

					var attribute = attributes[prop].nodeName;

					if ( ! attribute.includes( '-numeric-' )) {
						continue;
					}

					if ( ! dataset.includes( attribute )) {
						dataset.push( attribute );
					}

				}

			}
		);

		dataset.forEach(
			function (attribute) {
				var attribute_name = attribute.replace( 'data-numeric-', '' );
				var $filter        = $( '.stm-accordion-single-unit.' + attribute_name );

				if ( ! $filter.length) {
					return true;
				}

				var $filter_wrapper = $filter.find( '.stm-accordion-content-wrapper' );

				var filter_values = [];

				$( '[' + attribute + ']' ).each(
					function () {
						var $this = $( this );

						var value = $this.attr( attribute );

						if ( ! filter_values.includes( value )) {
							filter_values.push( value );
						}
					}
				);

				filter_values.sort().forEach(
					function(value){
						$filter_wrapper.append(
							'<div class="stm-single-unit">' +
							'<label><input class="numeric-checkbox-js" type="checkbox" name="' + attribute + value + '" data-name="' + value + '">' + value + '</label>' +
							'</div>'
						);
					}
				);

				$( '.numeric-checkbox-js' ).uniform( {} );

				$.uniform.update();

			}
		);
	}

	function stm_modern_filter_isotope() {

		if ($( '#modern-filter-listing' ).length > 0) {

			generateNumberFilter();

			$( 'body' ).on(
				'click',
				'.modern-filter-badges ul li i',
				function () {
					var tab_reset = $( this ).data( 'select' );

					if (tab_reset == 'price') {
						$( '.stm-price-range' ).slider( 'values', [stmOptions.min, stmOptions.max] );

						$( "#stm_filter_min_price" ).val( stmOptions.min );
						$( "#stm_filter_max_price" ).val( stmOptions.max );

						stmIso.isotope(
							{
								filter: function () {
									var itemPrice = $( this ).data( 'price' );

									return parseInt( itemPrice, 10 ) >= stmOptions.min && parseInt( itemPrice, 10 ) <= stmOptions.max;
								}
							}
						);

						/*price_string = '<li><span>Price:</span> ' + stmOptions.min + ' - ' + stmOptions.max;
						price_string += '<i class="fas fa-times stm-clear-listing-one-unit" data-select="price"></i></li>';*/

						price_string = '';
						$( this ).closest( 'li' ).remove();
						var badges_length = $( ".stm-filter-chosen-units-list li" ).length;
						if (badges_length < 1) {
							$( '.stm-filter-chosen-units-list' ).css( {height: 0} );
						}
					} else if (typeof stmOptionsObj[tab_reset] !== 'undefined') {
						$( '.stm-' + tab_reset + '-range' ).slider( 'values', [stmOptionsObj[tab_reset].min, stmOptionsObj[tab_reset].max] );

						$( "#stm_filter_min_" + tab_reset ).val( stmOptionsObj[tab_reset].min );
						$( "#stm_filter_max_" + tab_reset ).val( stmOptionsObj[tab_reset].max );

						stmIso.isotope(
							{
								filter: function () {
									var itemPrice = $( this ).data( tab_reset );
									return parseInt( itemPrice, 10 ) >= stmOptionsObj[tab_reset].min && parseInt( itemPrice, 10 ) <= stmOptionsObj[tab_reset].max;
								}
							}
						);

						dynamic_string[tab_reset] = '';
						$( this ).closest( 'li' ).remove();
						var badges_length = $( ".stm-filter-chosen-units-list li" ).length;
						if (badges_length < 1) {
							$( '.stm-filter-chosen-units-list' ).css( {height: 0} );
						}
					} else {
						$( '#' + tab_reset + ' input[type=checkbox]' ).each(
							function () {
								if ($( this ).prop( 'checked' )) {
									$( this ).trigger( 'click' );
								}
							}
						)
					}

					/*Number of filtered*/
					if (typeof stmIsotope != 'undefined') {
						var stmIsoData = stmIsotope.data( 'isotope' );
						if (typeof stmIsoData != 'undefined') {
							if (typeof stmIsoData.filteredItems != 'undefined') {
								$( '.stm-modern-filter-found-cars span' ).text( stmIsoData.filteredItems.length );
							}
						}
					}
				}
			);

			var stmSortClasses                           = '';
			var main_string                              = '';
			var price_string                             = '';
			var dynamic_string                           = new Object();
			var dynamic_string_html                      = '';
			var string                                   = '';
			var stmIso                                   = $( '.stm-isotope-sorting' );
			var stmFilterGroups                          = {};
			var stmPriceMaxSelected, stmPriceMinSelected = '';
			var stmMilMaxSelected, stmMilMinSelected     = '';

			/*Checkbox clicked, filter*/
			$( '#modern-filter-listing input[type=checkbox]' ).on(
				'click',
				function () {
					var badges       = {};
					var badges_reset = {};
					stmFilterGroups  = {};
					$( window ).trigger( 'scroll' );
					var stmFirst     = 0;
					stmSortClasses   = '';
					var numberOfCats = 0;

					$( '#modern-filter-listing input[type=checkbox]' ).each(
						function () {
							var stmChecked      = $( this ).prop( 'checked' );
							var stmCurrentClass = $( this ).attr( 'name' );
							var stmBadgeValue   = $( this ).data( 'name' );
							var stmBadgeId      = $( this ).closest( '.content' ).attr( 'id' );

							var stmFilterCurrentGroup = $( this ).closest( '.collapse' ).attr( 'id' );

							if (stmChecked) {
								var tab = $( this ).closest( '.stm-accordion-single-unit' ).find( '.title h5' ).text();
								if (typeof badges[tab] == 'undefined') {
									badges[tab] = [];
								}
								if (typeof badges_reset[tab] == 'undefined') {
									badges_reset[tab] = '';
								}
								badges[tab].push( stmBadgeValue );
								badges_reset[tab] = stmBadgeId;

								if (stmFirst == 0) {
									stmSortClasses += '.' + stmCurrentClass;
								} else {
									stmSortClasses += '.' + stmCurrentClass;
								}
								stmFirst++;

								if (typeof (stmFilterGroups[stmFilterCurrentGroup]) == 'undefined') {
									stmFilterGroups[stmFilterCurrentGroup] = [];
								}
								stmFilterGroups[stmFilterCurrentGroup].push( stmCurrentClass );

							}
							if (stmSortClasses == '') {
								stmSortClasses = '.all';
							}

						}
					);

					if ($( '.stm-isotope-sorting' ).length > 0 && stmSortClasses != '') {
						var matches = [];

						stmIso.isotope(
							{
								filter: function () {
									matches       = [];
									var itemPrice = $( this ).data( 'price' );
									var minPrice  = $( '#stm_filter_min_price' ).val();
									var maxPrice  = $( '#stm_filter_max_price' ).val();

									if (Object.keys( stmFilterGroups ).length > 0) {
										for (var key in stmFilterGroups) {
											if (stmFilterGroups.hasOwnProperty( key )) {
												for (var k = 0; k < stmFilterGroups[key].length; k++) {
													var match = false;
													if ($( this ).hasClass( stmFilterGroups[key][k] )) {
														matches[key] = true;
													}
												}
											}
										}

										var final_match = false;

										if (Object.keys( matches ).length == Object.keys( stmFilterGroups ).length) {
											if (Object.keys( matches ).length > 0) {
												for (var m_key in matches) {
													if (matches.hasOwnProperty( m_key )) {
														if (matches[m_key]) {
															final_match = true;
														} else {
															final_match = false;
														}
													}
												}
											} else {
												final_match = false;
											}
										}

										if (final_match) {
											if (typeof minPrice != 'undefined' && typeof maxPrice != 'undefined' && typeof itemPrice != 'undefined') {
												return parseInt( itemPrice, 10 ) >= minPrice && parseInt( itemPrice, 10 ) <= maxPrice;
											} else {
												return ($( this ));
											}
										}
									} else {
										return ($( this ));
									}

								}
							}
						)
					}

					/*create badge*/
					string = '';

					for (var key in badges) {
						if (badges.hasOwnProperty( key )) {
							if (badges.hasOwnProperty( key )) {
								string += '<li><span>' + key + ':</span> ' + badges[key].join( ', ' );
								if (badges_reset.hasOwnProperty( key )) {
									string += '<i class="fas fa-times stm-clear-listing-one-unit" data-select="' + badges_reset[key] + '"></i>';
								}
								string += '</li>';

								main_string = get_dynamic_badge_html( dynamic_string ) + price_string + string;
								$( '.modern-filter-badges ul.stm-filter-chosen-units-list' ).html( main_string );
							}
						}
					}

					if ($.isEmptyObject( badges )) {
						main_string = get_dynamic_badge_html( dynamic_string ) + price_string + string;
						$( '.modern-filter-badges ul.stm-filter-chosen-units-list' ).html( main_string );
					}

					//var badges_length = Object.keys(badges).length;
					var badges_length = $( ".stm-filter-chosen-units-list li" ).length;
					if (badges_length > 0) {

						var badgesWidth    = 0;
						var badgesMargin   = 15;
						var badgesRowWidth = $( '.stm-filter-chosen-units-list' ).outerWidth();

						$( '.stm-filter-chosen-units-list li' ).each(
							function () {
								badgesWidth += $( this ).outerWidth();
							}
						);

						// Add margins
						badgesWidth += badgesMargin * (badges_length - 1);

						var row_number = (badgesWidth / badgesRowWidth) + 1;

						$( '.stm-filter-chosen-units-list' ).css(
							{
								height: (parseInt( row_number ) * 47) + 'px'
							}
						);
					} else {
						$( '.stm-filter-chosen-units-list' ).css(
							{
								height: 0
							}
						);
					}

					/*Number of filtered*/
					if (typeof stmIsotope != 'undefined') {
						var stmIsoData = stmIsotope.data( 'isotope' );
						if (typeof stmIsoData != 'undefined') {
							if (typeof stmIsoData.filteredItems != 'undefined') {
								$( '.stm-modern-filter-found-cars span' ).text( stmIsoData.filteredItems.length );
							}
						}
					}

					stm_set_query_transients( stmPriceMinSelected, stmPriceMaxSelected );
				}
			);

			$( ".stm-price-range" ).on(
				'slide',
				function (event, ui) {

					var minPrice = ui.values[0];
					var maxPrice = ui.values[1];
					stmIso.isotope(
						{
							filter: function () {
								var matches   = [];
								var itemPrice = $( this ).data( 'price' );
								var itemMil   = (typeof ($( this ).data( 'mileage' )) != 'undefined') ? $( this ).data( 'mileage' ) : 0;

								if (Object.keys( stmFilterGroups ).length > 0) {
									for (var key in stmFilterGroups) {
										if (stmFilterGroups.hasOwnProperty( key )) {
											for (var k = 0; k < stmFilterGroups[key].length; k++) {
												var match = false;
												if ($( this ).hasClass( stmFilterGroups[key][k] )) {
													matches[key] = true;
												}
											}
										}
									}

									var final_match = false;

									if (Object.keys( matches ).length == Object.keys( stmFilterGroups ).length) {
										if (Object.keys( matches ).length > 0) {
											for (var m_key in matches) {
												if (matches.hasOwnProperty( m_key )) {
													if (matches[m_key]) {
														final_match = true;
													} else {
														final_match = false;
													}
												}
											}
										} else {
											final_match = false;
										}
									}

									if (final_match) {
										stmPriceMaxSelected = (typeof (maxPrice) == 'undefined') ? 0 : maxPrice;
										stmPriceMinSelected = (typeof (minPrice) == 'undefined') ? 0 : minPrice;

										if (stmMilMaxSelected !== '' && stmMilMinSelected !== '') {
											return parseInt( itemPrice, 10 ) >= minPrice && parseInt( itemPrice, 10 ) <= maxPrice && parseInt( itemMil, 10 ) >= stmMilMinSelected && parseInt( itemMil, 10 ) <= stmMilMaxSelected;
										} else {
											return parseInt( itemPrice, 10 ) >= minPrice && parseInt( itemPrice, 10 ) <= maxPrice;
										}
									}
								} else {
									stmPriceMaxSelected = (typeof (maxPrice) == 'undefined') ? 0 : maxPrice;
									stmPriceMinSelected = (typeof (minPrice) == 'undefined') ? 0 : minPrice;

									if (stmMilMaxSelected !== '' && stmMilMinSelected !== '') {
										return parseInt( itemPrice, 10 ) >= minPrice && parseInt( itemPrice, 10 ) <= maxPrice && parseInt( itemMil, 10 ) >= stmMilMinSelected && parseInt( itemMil, 10 ) <= stmMilMaxSelected;
									} else {
										return parseInt( itemPrice, 10 ) >= minPrice && parseInt( itemPrice, 10 ) <= maxPrice;
									}
								}
							}
						}
					);

					stm_set_query_transients( stmPriceMinSelected, stmPriceMaxSelected );

					/*Number of filtered*/
					if (typeof stmIsotope != 'undefined') {
						var stmIsoData = stmIsotope.data( 'isotope' );
						if (typeof stmIsoData != 'undefined') {
							if (typeof stmIsoData.filteredItems != 'undefined') {
								$( '.stm-modern-filter-found-cars span' ).text( stmIsoData.filteredItems.length );
							}
						}
					}

					price_string  = '<li><span>Price:</span> ' + minPrice + ' - ' + maxPrice;
					price_string += '<i class="fas fa-times stm-clear-listing-one-unit" data-select="price"></i></li>';
					main_string   = get_dynamic_badge_html( dynamic_string ) + price_string + string;
					$( '.modern-filter-badges ul.stm-filter-chosen-units-list' ).html( main_string );
					$( '.stm-filter-chosen-units-list' ).height( '47' );

				}
			);

			$( ".stm-modern-dynamic-slider" ).each(
				function () {
					var sliderIdent = $( this ).data( "slider-name" );

					$( ".stm-" + sliderIdent + "-range" ).on(
						'slide',
						function (event, ui) {
							var minMileage = ui.values[0];
							var maxMileage = ui.values[1];
							stmIso.isotope(
								{
									filter: function () {
										var matches     = [];
										var itemPrice   = (typeof ($( this ).data( 'price' )) != 'undefined') ? $( this ).data( 'price' ) : 0;
										var itemMileage = $( this ).data( sliderIdent );

										if (Object.keys( stmFilterGroups ).length > 0) {
											for (var key in stmFilterGroups) {
												if (stmFilterGroups.hasOwnProperty( key )) {
													for (var k = 0; k < stmFilterGroups[key].length; k++) {
														var match = false;
														if ($( this ).hasClass( stmFilterGroups[key][k] )) {
															matches[key] = true;
														}
													}
												}
											}

											var final_match = false;

											if (Object.keys( matches ).length == Object.keys( stmFilterGroups ).length) {
												if (Object.keys( matches ).length > 0) {
													for (var m_key in matches) {
														if (matches.hasOwnProperty( m_key )) {
															if (matches[m_key]) {
																final_match = true;
															} else {
																final_match = false;
															}
														}
													}
												} else {
													final_match = false;
												}
											}

											if (final_match) {
												stmMilMaxSelected = maxMileage;
												stmMilMinSelected = minMileage;

												if (stmPriceMaxSelected !== '' && stmPriceMinSelected !== '') {
													return parseInt( itemMileage, 10 ) >= minMileage && parseInt( itemMileage, 10 ) <= maxMileage && parseInt( itemPrice, 10 ) >= stmPriceMinSelected && parseInt( itemPrice, 10 ) <= stmPriceMaxSelected;
												} else {
													return parseInt( itemMileage, 10 ) >= minMileage && parseInt( itemMileage, 10 ) <= maxMileage;
												}
											}
										} else {
											stmMilMaxSelected = maxMileage;
											stmMilMinSelected = minMileage;

											if (stmPriceMaxSelected !== '' && stmPriceMinSelected !== '') {
												return parseInt( itemMileage, 10 ) >= minMileage && parseInt( itemMileage, 10 ) <= maxMileage && parseInt( itemPrice, 10 ) >= stmPriceMinSelected && parseInt( itemPrice, 10 ) <= stmPriceMaxSelected;
											} else {
												return parseInt( itemMileage, 10 ) >= minMileage && parseInt( itemMileage, 10 ) <= maxMileage;
											}
										}
									}
								}
							);

							/*Number of filtered*/
							if (typeof stmIsotope != 'undefined') {
								var stmIsoData = stmIsotope.data( 'isotope' );
								if (typeof stmIsoData != 'undefined') {
									if (typeof stmIsoData.filteredItems != 'undefined') {
										$( '.stm-modern-filter-found-cars span' ).text( stmIsoData.filteredItems.length );
									}
								}
							}

							dynamic_string_html  = '<li><span>' + sliderIdent + ':</span> ' + minMileage + ' - ' + maxMileage;
							dynamic_string_html += '<i class="fas fa-times stm-clear-listing-one-unit" data-select="' + sliderIdent + '"></i></li>';

							dynamic_string[sliderIdent] = dynamic_string_html;

							main_string = get_dynamic_badge_html( dynamic_string ) + price_string + string;
							$( '.modern-filter-badges ul.stm-filter-chosen-units-list' ).html( main_string );
							$( '.stm-filter-chosen-units-list' ).height( '47' );
						}
					);

				}
			);

			// stm_remove_query_cookies();

		}
		// else if($('body.single').length == 0) {
		//     stm_remove_query_cookies();
		// }

		// set query cookie
		function stm_set_query_transients(min, max) {

			var cookieQueryObj = Object.assign( {}, stmFilterGroups );

			if (typeof min !== 'undefined' && typeof max !== 'undefined') {
				cookieQueryObj['min_price'] = [min];
				cookieQueryObj['max_price'] = [max];
			}

			// delete query cookie
			// $.cookie('stm_search_results_query_' + stm_site_blog_id, null, {expires: -1, path: '/'});

			// set new query cookie
			// $.cookie('stm_search_results_query_' + stm_site_blog_id, JSON.stringify(cookieQueryObj), {expires: 7, path: '/'});

			// set cookie to come back to modern inventory page
			// $.cookie('stm_modern_inventory_link_' + stm_site_blog_id, window.location.href, {expires: 7, path: '/'});

			$.ajax(
				{
					type: 'post',
					url: ajax_url,
					data: {
						action: 'stm_set_query_transients',
						inventory_query: cookieQueryObj,
						inventory_link: window.location.href,
					}
				}
			);
		}

		// delete query and related cookies
		// function stm_remove_query_cookies() {
		//     // also delete everywhere except on single listing page
		//     $.cookie('stm_search_results_query_' + stm_site_blog_id, null, {expires: -1, path: '/'});
		//     $.cookie('stm_modern_inventory_link_' + stm_site_blog_id, null, {expires: -1, path: '/'});
		// }
	}

	function get_dynamic_badge_html(dynamic_string) {
		var dynamic_string_html = '';
		$.each(
			dynamic_string,
			function (key, event) {
				dynamic_string_html += event;
			}
		);

		return dynamic_string_html;
	}

	function stm_filter_links() {
		$( 'body' ).on(
			'click',
			'.stm-single-filter-link',
			function () {
				stm_single_filter_link = true;
				var stm_name           = $( this ).data( 'slug' );
				var stm_value          = $( this ).data( 'value' );
				if (typeof stm_name !== 'undefined' && typeof stm_value !== 'undefined') {
					$( '.reset-all' ).trigger( 'click' );
					$( '#stm-filter-links-input' ).attr( 'name', stm_name );
					$( '#stm-filter-links-input' ).val( stm_value );
				}
			}
		)
	}

	STMListings.save_user_settings = function () {
		$( '#stm_user_settings_edit.stm_save_user_settings_ajax' ).on(
			'submit',
			function (e) {

				var formData = new FormData();

				/*Add image*/
				formData.append( 'stm-avatar', $( 'input[name="stm-avatar"]' )[0].files[0] );

				/*Add text fields*/
				var formInputs = $( this ).serializeArray();

				for (var key in formInputs) {
					if (formInputs.hasOwnProperty( key )) {
						formData.append( formInputs[key]['name'], formInputs[key]['value'] );
					}
				}

				formData.append( 'action', 'stm_listings_ajax_save_user_data' );

				e.preventDefault();

				$.ajax(
					{
						type: "POST",
						url: ajaxurl,
						dataType: 'json',
						context: this,
						data: formData,
						contentType: false,
						processData: false,
						beforeSend: function () {
							$( '.stm-user-message' ).empty();
							$( this ).find( '.stm-listing-loader' ).addClass( 'visible' );
						},
						success: STMListings.save_user_settings_success
					}
				);
			}
		)
	};

	STMListings.save_user_settings_success = function (data) {
		$( this ).find( '.stm-listing-loader' ).removeClass( 'visible' );
		$( '.stm-user-message' ).text( data.error_msg );

		$( '.stm-image-avatar img, .stm-dropdown-user-small-avatar img' ).attr( 'src', data.new_avatar );

		if (typeof data.new_avatar === 'undefined' || data.new_avatar === '') {
			$( '.stm-image-avatar' ).removeClass( 'hide-empty' ).addClass( 'hide-photo' );
		} else {
			$( '.stm-image-avatar' ).addClass( 'hide-empty' ).removeClass( 'hide-photo' );
		}

	};

	STMListings.stm_logout = function () {
		$( 'body' ).on(
			'click',
			'.stm_logout a',
			function (e) {
				e.preventDefault();
				$.ajax(
					{
						url: ajaxurl,
						type: "POST",
						dataType: 'json',
						context: this,
						data: {
							'action': 'stm_logout_user'
						},
						beforeSend: function () {
							$( '.stm_add_car_form .stm-form-checking-user .stm-form-inner' ).addClass( 'activated' );
						},
						success: function (data) {
							if (data.exit) {
								$( '.stm-form-checking-user button[type="submit"]' ).removeClass( 'enabled' ).addClass( 'disabled' );
								window.location.reload();
							}
						}
					}
				);
			}
		)
	};

	STMListings.stm_ajax_login = function () {
		$( '.lOffer-account-unit' ).on(
			'mouseout',
			function () {
				$( '.stm-login-form-unregistered' ).removeClass( 'working' );
			}
		);

		$( '.stm-forgot-password a' ).on(
			'click',
			function (e) {
				e.preventDefault();
				var login_popup = $( this ).parents( '.stm-login-form-unregistered, .stm-login-form-mobile-unregistered' );
				if ( login_popup.length ) {
					$( '.stm-login-form-unregistered form, .stm-login-form-mobile-unregistered form' ).slideToggle();
					$( '.stm-login-form-unregistered .stm_forgot_password_send input[type=text], .stm-login-form-mobile-unregistered .stm_forgot_password_send input[type=text]' ).trigger( 'focus' );
				} else {
					$( '.stm-login-form .stm_forgot_password_send' ).slideToggle();
					$( '.stm-login-form .stm_forgot_password_send input[type=text]' ).trigger( 'focus' );
				}
				$( this ).toggleClass( 'active' );
			}
		);
		$( '.stm-forgot-password-back' ).on(
			'click',
			function (e) {
				e.preventDefault();
				$( '.stm-login-form-unregistered form, .stm-login-form-mobile-unregistered form' ).slideToggle();
			}
		);

		$( ".stm-login-form-mobile-unregistered form,.stm-login-form form:not(.stm_password_recovery), .stm-login-form-unregistered form" ).on(
			'submit',
			function (e) {
				e.preventDefault();
				if ( ! $( this ).hasClass( 'stm_forgot_password_send' )) {
					$.ajax(
						{
							type: "POST",
							url: ajaxurl,
							dataType: 'json',
							context: this,
							data: $( this ).serialize() + '&action=stm_custom_login',
							beforeSend: function () {
								$( this ).find( 'input' ).removeClass( 'form-error' );
								$( this ).find( '.stm-listing-loader' ).addClass( 'visible' );
								$( '.stm-validation-message' ).empty();

								if ($( this ).parent( '.lOffer-account-unit' ).length > 0) {
									$( '.stm-login-form-unregistered' ).addClass( 'working' );
								}
							},
							success: function (data) {
								if ($( this ).parent( '.lOffer-account-unit' ).length > 0) {
									$( '.stm-login-form-unregistered' ).addClass( 'working' );
								}
								if (data.user_html) {
									$('.add-car-btns-wrap').remove();
									var $user_html = $( data.user_html ).appendTo( '#stm_user_info' );
									$( '.stm-not-disabled, .stm-not-enabled' ).slideUp(
										'fast',
										function () {
											$( '#stm_user_info' ).slideDown( 'fast' );
										}
									);

									$( "html, body" ).animate( {scrollTop: $( '.stm-form-checking-user' ).offset().top}, "slow" );
									$( '.stm-add-a-car-login-overlay,.stm-add-a-car-login' ).toggleClass( 'visiblity' );

									$( '.stm-form-checking-user button[type="submit"]' ).removeClass( 'disabled' ).addClass( 'enabled' );
								}

								if ( data.restricted && data.restricted ) {
									$( '.btn-add-edit' ).remove();
								}

								// insert plans select
								if ( data.plans_select && $( '#user_plans_select_wrap' ).length > 0 ) {
									$( '#user_plans_select_wrap' ).html( data.plans_select );
									$( '#user_plans_select_wrap select' ).select2(
										{
											dropdownParent: $( 'body' ),
										}
									);
								}

								$( this ).find( '.stm-listing-loader' ).removeClass( 'visible' );
								for (var err in data.errors) {
									$( this ).find( 'input[name=' + err + ']' ).addClass( 'form-error' );
								}

								if (data.message) {
									var message = $( '<div class="stm-message-ajax-validation heading-font">' + data.message + '</div>' ).hide();

									$( this ).find( '.stm-validation-message' ).append( message );
									message.slideDown( 'fast' );
								}

								if (typeof (data.redirect_url) !== 'undefined') {
									window.location = data.redirect_url;
								}
							}
						}
					);
				} else {
					/*Send passs*/
					$.ajax(
						{
							type: "POST",
							url: ajaxurl,
							dataType: 'json',
							context: this,
							data: $( this ).serialize() + '&action=stm_restore_password&security=' + stm_security_nonce,
							beforeSend: function () {
								$( this ).find( 'input' ).removeClass( 'form-error' );
								$( this ).find( '.stm-listing-loader' ).addClass( 'visible' );
								$( '.stm-validation-message' ).empty();
							},
							success: function (data) {
								$( this ).find( '.stm-listing-loader' ).removeClass( 'visible' );
								if (data.message) {
									var message = $( '<div class="stm-message-ajax-validation heading-font">' + data.message + '</div>' ).hide();

									$( this ).find( '.stm-validation-message' ).append( message );
									message.slideDown( 'fast' );
								}
								for (var err in data.errors) {
									$( this ).find( 'input[name=' + err + ']' ).addClass( 'form-error' );
								}
							}
						}
					);
				}
			}
		);

		$( '.user_validated_field' ).on(
			'hover',
			function () {
				$( this ).removeClass( 'form-error' );
			}
		);

		$( 'input[name="stm_accept_terms"]' ).on(
			'click',
			function () {
				if ($( this ).is( ':checked' )) {
					$( '.stm-login-register-form .stm-register-form form input[type="submit"]' ).removeAttr( 'disabled' );
				} else {
					$( '.stm-login-register-form .stm-register-form form input[type="submit"]' ).attr( 'disabled', '1' );
				}
			}
		);

	};

	function loadMoreDealerCars() {
		$( 'body' ).on(
			'click',
			'.stm-load-more-dealer-cars a',
			function (e) {
				e.preventDefault();

				if ($( this ).closest( '.stm-load-more-dealer-cars' ).hasClass( 'not-clickable' )) {
					return false;
				}

				var offset    = $( this ).attr( 'data-offset' );
				var user_id   = $( this ).data( 'user' );
				var popular   = $( this ).data( 'popular' );
				var view_type = $( '#stm_dealer_view_type' ).val();

				$.ajax(
					{
						url: ajaxurl,
						data: {
							action: 'stm_ajax_dealer_load_cars',
							offset: offset,
							user_id: user_id,
							popular: popular,
							view_type: view_type,
							security: stm_security_nonce
						},
						method: 'POST',
						dataType: 'json',
						context: this,
						beforeSend: function () {
							$( this ).closest( '.stm-load-more-dealer-cars' ).addClass( 'not-clickable' );
						},
						success: function (data) {
							$( this ).closest( '.stm-load-more-dealer-cars' ).removeClass( 'not-clickable' );
							if (data.html) {
								$( this ).closest( '.tab-pane' ).find( '.car-listing-row' ).append( data.html );
							}
							if (data.new_offset) {
								$( this ).attr( 'data-offset', data.new_offset );
							}
							if (data.button == 'hide') {
								$( this ).closest( '.stm-load-more-dealer-cars' ).slideUp();
								$( this ).closest( '.tab-pane' ).find( '.row-no-border-last' ).removeClass( 'row-no-border-last' );
							}

							$( 'img.lazy' ).lazyload(
								{
									effect: "fadeIn",
									failure_limit: Math.max( 'img'.length - 1, 0 )
								}
							);
						}
					}
				);
			}
		)
	}

	function sortByListingType() {
		$( 'body' ).on(
			'change',
			'.select-listing-type select',
			function () {
				const $select       = $( this );
				const listingType   = $select.val();
				const viewType      = $( '#stm_dealer_view_type' ).val();
				const listingsView  = $( '#stm-dealer-view-type' ).val();
				const userId        = $select.data( 'user' );
				const popular       = listingsView === 'popular' ? 'yes' : 'no';
				const userPublic    = $select.data( 'user-public' );
				const userPrivate   = $select.data( 'user-private' );
				const userFavourite = $select.data( 'user-favourite' );

				$.ajax(
					{
						url: ajaxurl,
						data: {
							action: 'stm_ajax_dealer_load_listings_by_type',
							listing_type: listingType,
							user_id: userId,
							popular: popular,
							view_type: viewType,
							user_public: userPublic,
							user_private: userPrivate,
							user_favourite: userFavourite,
							security: stm_security_nonce
						},
						method: 'POST',
						dataType: 'json',
						beforeSend: function () {
							$( '.select-listing-type select' ).prop( "disabled", true );
						},
						success: function (data) {
							$( '.select-listing-type select' ).prop( "disabled", false );
							if (data) {
								const $target = userPublic || userPrivate ? $( '.archive-listing-page' ) :
								userFavourite ? $( '.archive-listing-page .car-listing-row' ) :
								$( '#' + listingsView ).find( '.car-listing-row' );
								$target.html( data.html );
							}

							const $loadMoreButton = $( '#' + listingsView ).find( '.stm-load-more-dealer-cars' );
							if (data.button === 'hide') {
								$loadMoreButton.slideUp();
								$( '#' + listingsView ).find( '.row-no-border-last' ).removeClass( 'row-no-border-last' );
							} else if (data.button === 'show') {
								$loadMoreButton.find( 'a' ).attr( 'data-offset', 6 );
								$loadMoreButton.slideDown();
								$( '#' + listingsView ).find( '.row-no-border-last' ).addClass( 'row-no-border-last' );
							}

							$( 'img.lazy' ).lazyload(
								{
									effect: "fadeIn",
									failure_limit: Math.max( $( 'img' ).length - 1, 0 )
								}
							);
						}
					}
				);
			}
		);
	}

	function loadMoreDealerReviews() {
		$( 'body' ).on(
			'click',
			'.stm-load-more-dealer-reviews a',
			function (e) {
				e.preventDefault();

				if ($( this ).closest( '.stm-load-more-dealer-reviews' ).hasClass( 'not-clickable' )) {
					return false;
				}

				var offset  = $( this ).attr( 'data-offset' );
				var user_id = $( this ).data( 'user' );

				$.ajax(
					{
						url: ajaxurl,
						data: {
							action: 'stm_ajax_dealer_load_reviews',
							offset: offset,
							user_id: user_id,
							security: stm_security_nonce
						},
						method: 'POST',
						dataType: 'json',
						context: this,
						beforeSend: function () {
							$( this ).closest( '.stm-load-more-dealer-reviews' ).addClass( 'not-clickable' );
						},
						success: function (data) {
							$( this ).closest( '.stm-load-more-dealer-reviews' ).removeClass( 'not-clickable' );
							if (data.html) {
								$( this ).closest( '.tab-pane' ).find( '#stm-dealer-reviews-units' ).append( data.html );
							}
							if (data.new_offset) {
								$( this ).attr( 'data-offset', data.new_offset );
							}
							if (data.button == 'hide') {
								$( this ).closest( '.stm-load-more-dealer-reviews' ).slideUp();
							}
						}
					}
				);
			}
		)
	}

	$( '#stm_submit_review .button' ).on(
		'click',
		function (e) {
			e.preventDefault();

			if ( ! $( this ).hasClass( 'disabled' )) {

				$.ajax(
					{
						url: ajaxurl,
						dataType: 'json',
						context: this,
						data: $( this ).closest( 'form' ).serialize() + '&action=stm_submit_review&security=' + stm_security_nonce,
						beforeSend: function () {
							$( this ).closest( 'form' ).find( '.stm-icon-load1' ).addClass( 'activated' );
							$( '#write-review-message ' ).slideUp();
						},
						success: function (data) {
							$( this ).closest( 'form' ).find( '.stm-icon-load1' ).removeClass( 'activated' );

							if (data.message) {
								$( '#write-review-message ' ).text( data.message ).slideDown();
							}

							if (data.updated) {
								window.location.href = data.updated;
								location.reload();
							}
						}
					}
				);
			}
		}
	)

	$( '.stm-comment-dealer-wrapper .stm-bottom .stm-report-review a' ).on(
		'click',
		function (e) {
			e.preventDefault();

			if ( ! $( this ).hasClass( 'reported' )) {
				var stmID = $( this ).data( 'id' );
				$.ajax(
					{
						type: "POST",
						url: ajaxurl,
						dataType: 'json',
						context: this,
						data: {
							'id': stmID,
							'action': 'stm_report_review',
							'security': stm_security_nonce
						},
						beforeSend: function () {
							$( this ).addClass( 'reported' );
						},
						success: function (data) {
							if (data.message) {
								$( this ).text( data.message );
							}
						}
					}
				);
			}
		}
	);

	$( '.dealer-search-title select' ).on(
		'change',
		function () {
			$( 'input[name="stm_sort_by"]' ).val( $( this ).val() );
			$( "#stm_all_listing_tab" ).find( "form" ).trigger( 'submit' );
		}
	);

	$( '#stm_all_listing_tab_without_tabs .reset-filter' ).on(
		'click',
		function (e) {
			e.preventDefault();
			$( this )
				.closest( 'form' )
				.find( 'select' )
				.val( null )
				.trigger( 'change' );

			$( this )
				.closest( 'form' )
				.find( 'input' )
				.val( '' );

			return false;
		}
	);

	$( '.stm-load-more-dealers' ).on(
		'click',
		function (e) {
			e.preventDefault();

			if ($( this ).hasClass( 'not-clickable' )) {
				return false;
			}

			var offset = $( this ).attr( 'data-offset' );

			$.ajax(
				{
					//url: ajaxurl,
					url: stm_motors_current_ajax_url,
					dataType: 'json',
					context: this,
					data: $( '.stm_dynamic_listing_dealer_filter form' ).serialize() + '&offset=' + offset + '&ajax_action=stm_load_dealers_list&security=' + stm_security_nonce,
					beforeSend: function () {
						$( this ).addClass( 'not-clickable' );
					},
					success: function (data) {
						$( this ).removeClass( 'not-clickable' );
						if (data.user_html) {
							$( '.dealer-search-results table tbody' ).append( data.user_html );
						}
						if (data.new_offset) {
							$( '.stm-load-more-dealers' ).attr( 'data-offset', data.new_offset );
						}
						if (data.remove && data.remove === 'hide') {
							$( this ).remove();
						}
					}
				}
			);
		}
	);

	$( 'body' ).on(
		"click",
		'.stm-show-number',
		function () {
			var parent         = $( this ).parent();
			var phone_owner_id = $( this ).attr( "data-id" );
			var listing_id     = $( this ).attr( "data-listing-id" );

			if (typeof listing_id === 'undefined' || listing_id === false) {
				listing_id = '0';
			}

			parent.find( ".stm-show-number" ).text( '' ).addClass( 'load_number' );
			$.ajax(
				{
					url: ajaxurl,
					type: "GET",
					dataType: 'json',
					context: this,
					data: 'phone_owner_id=' + phone_owner_id + '&listing_id=' + listing_id + '&action=stm_ajax_get_seller_phone&security=' + stm_security_nonce,
					success: function (data) {
						parent.find( ".stm-show-number" ).hide();
						parent.find( ".phone" ).html( '<a href="tel:' + data + '">' + data + '</a>' );
					}
				}
			);
		}
	);

	$( '.stm-btn-plan-trial, .stm-btn-plan-pause, .stm-btn-plan-cancel' ).on(
		'click',
		function() {
			var subsId   = $( this ).data( 'subsid' );
			var userId   = $( this ).data( 'userid' );
			var status   = $( this ).data( 'status' );
			var msgblock = $( this ).data( 'msgblock' );

			var btn = $( this );

			btn.addClass( 'btn-load' );

			$.ajax(
				{
					url: ajaxurl,
					type: "POST",
					dataType: 'json',
					context: this,
					data: 'subs_id=' + subsId + '&user_id=' + userId + '&subs_status=' + status + '&action=stm_ajax_subscriptio_change_status&security=' + stm_security_nonce,
					success: function (data) {

						btn.removeClass( 'btn-load' );

						if (data.status == 'success') {
							location.reload();
						} else {
							$( '.' + msgblock ).text( data.status );
						}
					}
				}
			);
		}
	);
})( jQuery );

function stm_loadMoreCars(that, category, taxonomy, offset, per_page, random_int = null ) {
	var $ = jQuery;
	$.ajax(
		{
			url: ajaxurl,
			data: {
				action: 'stm_ajax_load_more_cars',
				category: category,
				taxonomy: taxonomy,
				offset: offset,
				per_page: per_page,
				security: stm_security_nonce,
				random_int: random_int,
			},
			method: 'POST',
			dataType: 'json',
			beforeSend: function () {
				$( that ).addClass( 'not-visible' );
				$( that ).closest( '.dp-in' ).find( '.preloader' ).fadeIn( 600 );
			},
			success: function (data) {
				$( that ).closest( '.dp-in' ).find( '.preloader' ).hide();
				if (random_int != null) {
					const appendTo = data['appendTo'] + '-' + random_int;
					if (data['content'] && data['appendTo']) {
						$( appendTo + ' .car-listing-row' ).append( data['content'] );
					}
				}
				if (data['content'] && data['appendTo']) {
					$( data['appendTo'] + ' .car-listing-row' ).append( data['content'] );
				}
				if (data['button']) {
					$( that ).attr( 'onclick', data['button'] ).removeClass( 'not-visible' );
				} else {
					$( data['appendTo'] ).find( '.car-listing-actions' ).addClass( 'all-done' );
					that.parent().text( '' );
				}
			}
		}
	);
}

function stm_load_cars_with_review(upd) {
	var update = (upd) ? upd : false;
	var $      = jQuery;
	var $_form = $( '#listing-with-review' ).closest( 'form' );
	var stmUrl = $_form.attr( 'action' );

	$.ajax(
		{
			url: stmUrl,
			dataType: 'json',
			context: this,
			data: $_form.serialize() + '&ajax_action=listings-binding',
			beforeSend: function () {
				if (typeof filterApp != 'undefined') {
					filterApp.loadListings = true;
				}
			},
			success: function (data) {
				if (typeof data.offset != 'undefined') {
					$( 'input[name="offset"]' ).val( data.offset );
					$( '.load-more-btn-wrap' ).show();
				} else {
					$( 'input[name="offset"]' ).val( 0 );
					$( '.load-more-btn-wrap' ).hide();
				}

				if (typeof data.posts != 'undefined') {
					if ( ! update) {
						for (var q = 0; q < data.posts.length; q++) {
							filterApp.pushNewData( data.posts[q] );
						}
					} else {
						filterApp.posts = data.posts;
						setTimeout(
							function () {
								filterApp.loadListings = false;
							},
							300
						);
					}
				}
			}
		}
	);
	return false;
}
