if (typeof (STMListings) == 'undefined') {
	var STMListings = {};
}

(function ($) {
	"use strict";

	$( document ).on(
		'click',
		'#listings-result .stm-blog-pagination a',
		function(){
			if ($( '#listings-result' ).length) {
				$( 'html, body' ).animate(
					{
						scrollTop: $( "#listings-result" ).offset().top - 120
					},
					500
				);
			}
		}
	);

	STMListings.Filter.prototype.ajaxBefore = function () {
		/*Add filter preloader*/
		$( '.stm-ajax-row' ).addClass( 'stm-loading' );

		/*Add selects preloader*/
		$( '.classic-filter-row .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b' ).addClass( 'stm-preloader' );
		$( '.mobile-search-filter .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b' ).addClass( 'stm-preloader' );
		$( '.mobile-search-filter .filter-sidebar .select2-container--default .selection' ).addClass( 'stm-overlay' );
		$( '.search-filter-form .active .mobile .filter-sidebar .select2-container--default .selection' ).addClass( 'stm-overlay' );
		$( '.stm-listing-directory-total-matches' ).hide();
	};

	/** update child data, set correct disabled values based on choosen parent **/
	STMListings.Filter.prototype.updateChilds = function (res) {
		$.each(
			res.filters,
			function( filter_name, filter ) {
				if ( filter.hasOwnProperty( 'listing_taxonomy_parent' ) && filter.listing_taxonomy_parent.length !== 0) {
					let select_name = filter_name;
					if ( filter.hasOwnProperty( 'is_multiple_select' ) && filter.is_multiple_select === 1) {
						select_name = filter_name + '[]';
					}
					let select = $( '[name="' + select_name + '"]' );
					$( "#list" ).find( ':selected' ).attr( 'disabled','disabled' );

					$.each(
						res.options[filter_name],
						function( option_value, option ) {
							select.find( "option[value='" + option_value + "']" ).attr( 'disabled', option['disabled'] );
						}
					)
				}
			}
		);
	};

	STMListings.Filter.prototype.ajaxSuccess = function (res) {
		/*Disable useless selects*/
		this.disableOptions( res );

		/** update child data, set correct disabled values based on choosen parent **/
		this.updateChilds( res );

		/*Append new html*/
		this.appendData( res );

		if (res.url) {
			this.pushState( res.url );
		}

		/*Reinit js functions*/
		this.reInitJs();
		/*Remove preloader*/
		$( '.stm-ajax-row' ).removeClass( 'stm-loading' );

		/*Remove select preloaders*/
		$( '.classic-filter-row .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b' ).removeClass( 'stm-preloader' );
		$( '.classic-filter-row .filter-sidebar select' ).prop( "disabled", false );

	};

	STMListings.Filter.prototype.disableOptions = function ( res ) {
		let form = this.form,
			body = $( 'body' );

		if ( typeof res.options !== 'undefined' ) {
			$.each(
				res.options,
				function ( key, options ) {
					let $selector = 'select[name=' + key + ']';

					if ( typeof res.filters[ key ] === "object" && res.filters[ key ].is_multiple_select ) {
						$selector = 'select[name="' + key + '[]"]';
					}

					let select = STMListings.selectDestroy( $( $selector, form ) );

					if ( select ) {
						let	value = select.val();

						$( ' > option', select ).each(
							function () {
								let slug = $( this ).val();

								if ( options.hasOwnProperty( slug ) && value !== slug ) {
									if ( options[ slug ].parent !== false ) {
										$( this ).prop( 'disabled', options[ slug ].disabled );
									}
								}
							}
						);
					}
				}
			);
		}

		let select = STMListings.selectDestroy( $( 'select:not(.stm-multiple-select), select.stm-multiple-select', form ) );

		if ( select ) {
			$( 'select:not(.stm-multiple-select)', form ).select2(
				{
					dropdownParent: body,
				}
			);
		}

		STMListings.init_select();

		let select_sorting = STMListings.selectDestroy( $( '.stm-select-sorting select' ) );

		if ( select_sorting && typeof res.sorts !== "undefined" ) {
			select_sorting.html( '' );
			select_sorting.select2(
				{
					data: res.sorts,
					dropdownParent: body,
				}
			);
		}

		if ( typeof res.filter_links !== "undefined" ) {
			$.each(
				res.filter_links,
				function (key, link) {
					$.each(
						res.options[link['slug']],
						function (key, linkOption) {
							if ( key ) {
								let text = '(' + linkOption['count'] + ')', selector;

								if ( body.hasClass( 'stm-template-aircrafts' ) ) {
									selector = $( 'label[data-taxonomy="stm-iwf-' + link['slug'] + '"] span[data-slug="stm-iwf-' + key + '"]' );
								} else {
									selector = $( '#stm-filter-link-' + link['slug'] + ' li[data-value="' + key + '"] span' );
								}

								selector.text( text );
							}
						}
					)
				}
			)
		}

	};

	STMListings.Filter.prototype.appendData = function (data) {
		this.getTarget().html( data.html );

		/*Listing functions*/
		if ($( '.stm-listing-directory-title .title' ).length > 0) {
			if (typeof(data.listing_title) !== 'undefined' && data.listing_title !== false) {
				$( '.stm-listing-directory-title .title' ).text( data.listing_title );
			}

			if (typeof(data.total) !== 'undefined') {
				$( '.stm-listing-directory-title .total > span' ).text( data.total );
			}
		}

		let form = $( 'form[data-trigger=filter]' ).data( 'Filter' )
		if ( typeof form !== 'undefined' ) {
			let form_url = form.getFormParams();
			var hasSearchParam = hasSearchParams(form_url);
			if (!hasSearchParam) {
				$('.stm-listing-directory-total-matches').hide();
			} else {
				$('.stm-listing-directory-total-matches').show();
			}
		}

		$( '.stm-car-listing-sort-units .stm-listing-directory-title .stm-listing-directory-total-matches > span' ).text( data.total );
	};

	STMListings.Filter.prototype.reInitJs = function () {
		//stButtons.locateElements();
		$( "img.lazy" ).lazyload();
		$( '.stm-tooltip-link, div[data-toggle="tooltip"]' ).tooltip();
		STMListings.initVideoIFrame();

		$( '.stm-shareble' ).on(
			{
				mouseenter: function () {
					$( this ).parent().find( '.stm-a2a-popup' ).addClass( 'stm-a2a-popup-active' );
				},
				mouseleave: function () {
					$( this ).parent().find( '.stm-a2a-popup' ).removeClass( 'stm-a2a-popup-active' );
				}
			}
		);

		$( ".a2a_dd" ).each(
			function() {
				a2a.init( 'page' );
			}
		);

		if ($( 'body' ).hasClass( 'logged-in' )) {
			$.getJSON(
				ajaxurl,
				{action: 'stm_ajax_get_favourites', security: stm_security_nonce},
				function (data) {
					window.stm_favourites.ids = data;
					window.stm_favourites.activateLinks();
				}
			);
		}

		window.stm_compare.activateLinks();

		//Default plugins
		// don't reinit multiple select
		$( "select:not(.hide,.stm-multiple-select)" ).each(
			function () {
				var selectElement = $( this );
				selectElement.select2(
					{
						dropdownParent: $( 'body' ),
					}
				);
			}
		);
	};

	$( document ).on(
		'click',
		'#stm-classic-filter-submit',
		function (e) {
			if ($( this ).hasClass( 'stm-classic-filter-submit-boats' )) {
				e.preventDefault();
				STMListings.stm_disable_rest_filters( $( this ), 'listings-items' );
			}
		}
	);

	$( document ).on(
		'click',
		'#show-car-btn-mobile',
		function (e) {
			if ($( this ).hasClass( 'show-car-btn' )) {
				e.preventDefault();
			}
		}
	);

	//Checkboxes area listing trigger
	$( document ).on(
		'click',
		'.stm-ajax-checkbox-button .button, .stm-ajax-checkbox-instant .stm-option-label input',
		function (e) {

			if ($( this )[0].className == 'button') {
				e.preventDefault();
			}

			if ( typeof stm_elementor_editor_mode === "undefined" ) {
				$( this ).closest( 'form' ).trigger( 'submit' );
			}

		}
	);

	$( document ).ready(
		function() {
			$( document ).on(
				'click',
				'.stm-view-by a',
				function (e) {
					if ( ! $( this ).hasClass( 'stm-modern-view' ) && $( this ).data( 'view' ) !== '') {
						e.preventDefault();
						var viewType = $( this ).data( 'view' );

						$( '.stm-view-by a' ).removeClass( 'active' );
						$( this ).addClass( 'active' );

						$( '#stm_view_type' ).val( viewType );

						var currentUrl = window.location.href;
						var updatedUrl = updateQueryStringParameter( currentUrl, 'view_type', viewType );
						window.history.replaceState( {}, '', updatedUrl );
						window.location.href = updatedUrl;
					}
				}
			);

			function updateQueryStringParameter(uri, key, value) {
				var re        = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
				var separator = uri.indexOf( '?' ) !== -1 ? "&" : "?";
				if (uri.match( re )) {
					return uri.replace( re, '$1' + key + "=" + value + '$2' );
				} else {
					return uri + separator + key + "=" + value;
				}
			}
		}
	);

	/*Remove badge*/
	$( document ).on(
		'click',
		'ul.stm-filter-chosen-units-list li > i',
		function () {
			let $this = $( this ),
				form  = $( 'form[data-trigger=filter]' ).data( 'Filter' )

			let stmType = $this.data( 'type' )
			let stmSlug = $this.data( 'slug' )

			$( 'input[name="' + stmSlug + '[]"]:checked' ).each(
				function () {
					let $input = $( this )
					$input.parent().removeClass( 'checked' )
					$input.prop( 'checked', false )
					$input.closest( '.stm-option-label' ).removeClass( 'checked' )
				}
			)

			if (stmType == 'select') {
				$( 'select[name="' + stmSlug + '[]"]' ).val( null );
				$( 'select[name="' + stmSlug + '[]"]' ).trigger( 'change' );
				$( 'select[name="' + stmSlug + '"]' ).val( '' );
				$( 'select[name="' + stmSlug + '"]' ).find( 'option' ).prop( 'disabled', false );
				$( 'select[name="' + stmSlug + '"]' ).select2( 'destroy' ).select2().select2( 'val', '' );
			}

			if (stmType == 'slider') {
				let sliderObj = $('.stm-' + stmSlug + '-range').slider( 'instance' );
				if ( typeof sliderObj !== 'undefined' ) {
					$('.stm-' + stmSlug + '-range').slider('values', [sliderObj.options.min, sliderObj.options.max]);
					$('input[name="min_' + stmSlug + '"]').val('').attr('placeholder', sliderObj.options.min);
					$('input[name="max_' + stmSlug + '"]').val('').attr('placeholder', sliderObj.options.max);
				}
			}

			let form_url       = form.getFormParams()
			var hasSearchParam = hasSearchParams( form_url );
			if ( ! hasSearchParam ) {
				$( '.stm-listing-directory-total-matches' ).hide();
			}
			form.performAjax( form_url )

		}
	);

	$( document ).on(
		'click',
		'.stm_boats_view_by ul li a, .stm-motorcycle-per-page ul li a',
		function (e) {
			e.preventDefault();
			let $this    = $( this ),
				li       = $this.closest( 'li' ),
				form     = $( 'form[data-trigger=filter]' ).data( 'Filter' ),
				url      = new URL( $this.attr( 'href' ) ),
				form_url = form.getFormParams();

			form_url.searchParams.set( 'posts_per_page', url.searchParams.get( 'posts_per_page' ) )

			form.performAjax( form_url );

			li.siblings().removeClass( 'active' );
			li.addClass( 'active' );
		}
	);

	let keyUpTimer = [];

	/*Location*/
	var delay = (function () {
		let timer = 0;

		return function (callback, ms, id) {
			if ( id ) {
				keyUpTimer.map(
					function ( item ) {
						if ( item.key === id ) {
							clearTimeout( item.value );
						}
					}
				);
			}

			timer = setTimeout( callback, ms );

			return timer;
		};
	})();

	$( document ).on(
		'keyup',
		'#stm_keywords',
		function () {
			if ( typeof stm_elementor_editor_mode === "boolean" ) {
				return;
			}

			let form = $( this ).closest( 'form' ),
				id   = $( this ).attr( 'id' );

			let timer = delay(
				function () {
					form.trigger( 'submit' );
				},
				1500,
				id
			);

			keyUpTimer.push(
				{
					key: id,
					value: timer,
				}
			);
		}
	);

	let checkbox_submit = $( '.stm-checkbox-submit' );

	if (checkbox_submit.length) {
		$( document ).on(
			'change',
			'.classic-filter-row form.search-filter-form.mobile input[type=text], .classic-filter-row form.search-filter-form.mobile select',
			function () {
				STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
			}
		);
	} else {
		$( document ).on(
			'change',
			'.classic-filter-row form.search-filter-form.mobile input, .classic-filter-row form.search-filter-form.mobile select',
			function () {
				STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
			}
		);
	}

	$( document ).on(
		'click',
		'.search-filter-form.mobile .stm-checkbox-submit',
		function () {
			STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
		}
	);

	if (checkbox_submit.length) {
		$( document ).on(
			'change',
			'.mobile-search-filter input[type=text], .mobile-search-filter select',
			function () {
				STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
			}
		);
	} else {
		$( document ).on(
			'change',
			'.mobile-search-filter input, .mobile-search-filter select',
			function () {
				STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
			}
		);
	}

	$( document ).on(
		'click',
		'.mobile-search-filter .stm-checkbox-submit',
		function () {
			STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
		}
	);

	$( document ).on(
		'slidestop',
		'.stm-filter-sidebar-boats .stm-filter-type-slider',
		function () {
			STMListings.stm_disable_rest_filters( $( this ), 'listings-binding' );
		}
	);

	STMListings.stm_disable_rest_filters = function ($_this, action) {
		if ( typeof stm_elementor_editor_mode === "boolean" ) {
			return;
		}

		let $_form       = $_this.closest( 'form' ),
			url          = new URL( $_form.attr( 'action' ) ),
			searchParams = new URLSearchParams( $_form.serialize() );

		for ( let [key, value] of searchParams.entries() ) {
			if ( value !== '' && ! ( [ 'stm_lat', 'stm_lng' ].includes( key ) && parseInt( value ) === 0 ) ) {
				url.searchParams.append( key, value );
			}
		}

		$.ajax(
			{
				url: url,
				dataType: 'json',
				context: this,
				data: 'ajax_action=' + action,
				beforeSend: function () {
					if ( action === 'listings-items' ) {
						$( '.stm-ajax-row' ).addClass( 'stm-loading' );
					} else {
						$( '.classic-filter-row .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b' ).addClass( 'stm-preloader' );
						$( '.mobile-search-filter .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b' ).addClass( 'stm-preloader' );
						$( '.mobile-search-filter .filter-sidebar .select2-container--default .selection' ).addClass( 'stm-overlay' );
						$( '.search-filter-form .filter-sidebar .select2-container--default .selection' ).addClass( 'stm-overlay' );
						$( '.stm-listing-directory-total-matches' ).hide();
					}
				},
				success: function ( res ) {
					if ( action === 'listings-items' ) {
						$( '.stm-ajax-row' ).removeClass( 'stm-loading' );
						$( '#listings-result' ).html( res.html );
						$( "img.lazy" ).lazyload();
						$( '.stm-tooltip-link, div[data-toggle="tooltip"]' ).tooltip();
						this.Filter.prototype.pushState( url );
					} else {
						this.Filter.prototype.ajaxSuccess( res );

						/*Change total*/
						$( '.stm-filter-sidebar-boats #stm-classic-filter-submit span' ).text( res.total );
						$( '.stm-horizontal-filter-sidebar #stm-classic-filter-submit span' ).text( res.total );
						$( '.search-filter-form #show-car-btn-mobile span' ).text( res.total );
						$( '.mobile-search-filter #show-car-btn-mobile span' ).text( res.total );

						$( '.stm-listing-directory-total-matches' ).show();
					}
				}
			}
		);
	};

	// Reset fields
	STMListings.resetFields = function() {
		$( document ).on(
			'reset',
			'select',
			function(){
				$( this ).val( '' );
				$( this ).find( 'option' ).prop( 'disabled', false );
				$( this ).select2( 'destroy' ).select2().select2( 'val', '' );
			}
		);
	};

	STMListings.selectDestroy = function ( select ) {
		if ( $( select ).length ) {
			$( select ).each(
				function ( index, item ) {
					if ( $( item ).length && $( item ).hasClass( 'select2-hidden-accessible' ) && $( item ).data( 'select2' ) ) {
						$( item ).select2( 'close' ).select2( 'destroy' );
					}
				}
			);

			return $( select );
		}

		return false;
	};

	//Mobile search filter
	$( document ).ready(
		function(){

			$( document ).on(
				'click',
				'.mobile-search-btn',
				function(){
					$( '.mobile-search-filter' ).addClass( 'active' );
					$( '.mobile-overlay' ).addClass( 'active' ).insertAfter( '#wrapper' );
					$( 'html' ).addClass( 'mobile-overflow-hidden' );
					$( 'body' ).addClass( 'mobile-search-filter-opened' );
				}
			);
			$( document ).on(
				'click',
				'.close-btn',
				function(){
					$( '.mobile-search-filter' ).removeClass( 'active' );
					$( '.mobile-overlay' ).removeClass( 'active' );
					$( 'html' ).removeClass( 'mobile-overflow-hidden' );
					$( 'body' ).removeClass( 'mobile-search-filter-opened' );
				}
			);
			$( document ).on(
				'click',
				'.mobile-overlay',
				function(){
					$( '.mobile-overlay' ).removeClass( 'active' );
					$( '.mobile-search-filter' ).removeClass( 'active' );
					$( 'html' ).removeClass( 'mobile-overflow-hidden' );
					$( 'body' ).removeClass( 'mobile-search-filter-opened' );
				}
			);
			$( document ).on(
				'click',
				'.show-car-btn',
				function(){
					$( '.mobile-overlay' ).removeClass( 'active' );
					$( '.mobile-search-filter' ).removeClass( 'active' );
					$( 'html' ).removeClass( 'mobile-overflow-hidden' );
					$( 'body' ).removeClass( 'mobile-search-filter-opened' );
				}
			);

		}
	);
	$( document ).ready(
		function() {
			$( window ).scroll(
				function () {
					const staticElement = $( ".mobile-filter-wrapper" );
					const stickyElement = $( ".mobile-filter-sticky" );

					if (staticElement.length > 0) {
						const staticRect = staticElement[0].getBoundingClientRect();

						if (staticRect.bottom >= 0 && staticRect.top <= window.innerHeight) {
							stickyElement.removeClass( "sticky-filter" );
						} else {
							stickyElement.addClass( "sticky-filter" );
						}
					}
				}
			);

			$( window ).trigger( "scroll" );
		}
	);

	$( document ).ready(
		function() {
			let mobileId = 'mobile-inventory-filter';

			function checkScreenSize() {
				let containerFilter  = $( '#' + mobileId ),
					classicFilterRow = $( '.mobile-search-filter' ),
					staticElement    = $( '.mobile-filter-sticky' );

				if ( ! classicFilterRow.length ) {
					return;
				}

				if ( $( window ).width() < 1025 ) {
					if ( classicFilterRow.closest( '#' + mobileId ).length ) {
						return;
					}

					let staticWrapper = $( '.mobile-filter-wrapper' );

					STMListings.selectDestroy( $( 'select', classicFilterRow ) );

					if ( typeof stm_range_slug !== "undefined" && stm_range_slug.length ) {
						stm_range_slug.forEach(
							function (range) {
								let $sliderRange = $( '.stm-' + range + '-range', classicFilterRow );

								if ( $sliderRange.length && $sliderRange.slider( "instance" ) !== undefined ) {
									$sliderRange.slider( "destroy" );
								}
							}
						);
					}

					const mobileFilter = classicFilterRow.clone();

					classicFilterRow.remove();

					if ( ! containerFilter.length ) {
						containerFilter = document.createElement( "div" );

						containerFilter.setAttribute( 'id', mobileId );

						containerFilter = $( containerFilter );

						containerFilter.insertAfter( '#footer' );
						containerFilter.append( mobileFilter );

						staticWrapper = staticWrapper.clone();
						staticWrapper.addClass( "mobile-filter-sticky" );
						containerFilter.append( staticWrapper );
					}

					if ( typeof stm_range_slug !== "undefined" && stm_range_slug.length ) {
						stm_range_slug.forEach(
							function (range) {
								if ( typeof window[ 'stm_options_' + range ] !== "undefined" ) {
									let $sliderRange = $( '.stm-' + range + '-range', containerFilter );

									$sliderRange.slider( window[ 'stm_options_' + range ] );
								}
							}
						);
					}

					STMListings.init_select();
					STMListings.Filter.prototype.reInitJs();

					$( 'form[data-trigger=filter]' ).each(
						function () {
							$( this ).data( 'Filter', new STMListings.Filter( this ) );
						}
					);

					if (typeof stm_slide_filter === "function") {
						stm_slide_filter();
					}
				} else if ( containerFilter.length ) {
					classicFilterRow.appendTo( '.classic-filter-row' );
					staticElement.appendTo( '.classic-filter-row' );
					containerFilter.remove();
					$( '.mobile-overlay' ).remove();
					classicFilterRow.removeClass( 'active' );
					$( 'html' ).removeClass( 'mobile-overflow-hidden' );
					$( 'body' ).removeClass( 'mobile-search-filter-opened' );
				}
			}

			checkScreenSize();

			$( window ).resize( checkScreenSize );
		}
	);

	function hasSearchParams(url) {
		const urlObj       = new URL( url );
		const searchParams = urlObj.searchParams;

		for (let key of searchParams.keys()) {
			if (key !== 'security' && key !== 'ajax_action' && key !== 'posttype' ) {
				return true;
			}
		}

		return false;
	}

})( jQuery );

function stm_get_price_view(price, currency, currencyPos, priceDel) {
	return price;
}
