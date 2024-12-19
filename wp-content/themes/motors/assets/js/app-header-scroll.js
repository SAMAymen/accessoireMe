"use strict";

//This file only controls flow header, as its named
(function($) {

	$( document ).ready(
		function () {
			stm_default_header_height();
			stm_default_header();

			stm_fixed_transparent_header();
		}
	);

	$( window ).on(
		'load',
		function () {
			stm_default_header_height();
			stm_default_header();

			stm_fixed_transparent_header();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_default_header_height();
			stm_default_header();

			stm_fixed_transparent_header();
		}
	);

	$( window ).on(
		'scroll',
		function () {
			stm_default_header();
			stm_fixed_transparent_header();
		}
	);

	//Default header
	function stm_default_header_height(){
		let prefix			  = 'header-nav',
			header_nav        = $( '.' + prefix ),
			header_nav_holder = $( '#' + prefix + '-holder' );

		if ( header_nav_holder.length > 0 && header_nav.length > 0 && header_nav.hasClass( prefix + '-default' ) ) {
			header_nav_holder.css(
				{
					'min-height': header_nav.outerHeight() + 'px'
				}
			);
		}
	}

	function stm_header( style ) {
		let prefix			  = 'header-nav',
			$header           = $( '#header' ),
			header_style      = prefix + '-' + style,
			header_sticky     = prefix + '-sticky',
			header_nav        = $( '.' + prefix ),
			header_nav_holder = $( '#' + prefix + '-holder' ),
			$header_style     = $( '.' + header_style );

		if ( header_nav_holder.length > 0 && header_nav.length > 0 && header_nav.hasClass( header_style ) ) {
			let currentScrollPos = $( window ).scrollTop(),
				headerPos        = header_nav_holder.offset().top;

			if ( currentScrollPos >= headerPos ) {
				$header_style.addClass( header_sticky );
				$header.addClass( 'stm-sticky-on' );
			} else {
				$header_style.removeClass( header_sticky );
				$header.removeClass( 'stm-sticky-on' );
			}
		}
	}

	function stm_default_header() {
		stm_header( 'default' );
	}

	//Transparent header
	function stm_fixed_transparent_header() {
		stm_header( 'transparent' );
	}

})( jQuery );

//  car service scripts
var $ = jQuery;
if ($( '.header-service' ).length > 0) {

	var sections = [];

	var headerOffset = 48;

	var currentVisibleSection = '';

	var hasVisible = false;

	$( document ).ready(
		function () {
			stm_sticky_service_header();

			$( '.header-service .header-menu li a, .header-service .header-service-right .service-header-appointment, .stm-slider-button' ).on(
				'click',
				function(e){

					var name = $( this ).attr( 'href' );

					var hash = name.split( '#' );

					var pageHasDiv = false;

					if (typeof hash[1] !== 'undefined') {
						if ($( '#' + hash[1] ).length) {
							pageHasDiv = true;
						}
					}

					if (pageHasDiv) {
						if (typeof($( 'div#' + hash[1] ).offset()) != 'undefined') {
							e.preventDefault();
							$( 'html, body' ).stop().animate(
								{
									scrollTop: $( 'div#' + hash[1] ).offset().top - headerOffset
								},
								700
							);
						}
					}
				}
			);
		}
	);

	$( window ).on(
		'load',
		function () {
			stm_sticky_service_header();

			stm_getSections();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_sticky_service_header();
		}
	);

	$( window ).on(
		'scroll',
		function () {
			stm_sticky_service_header();
			stm_getSections();
		}
	);
}

// electric vehicle dealership scripts
if ($( '.header-main-ev_dealer.header-listing-fixed' ).length > 0) {

	var $this      = $( '.header-main-ev_dealer.header-listing-fixed' );
	var isAbsolute = $this.css( 'position' ) == 'absolute';

	$( document ).ready(
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'load',
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'scroll',
		function () {
			stm_listing_fixed_header();
		}
	);
}

// Listing scripts
if ($( '.header-listing.header-listing-fixed' ).length > 0) {

	var $this      = $( '.header-listing.header-listing-fixed' );
	var isAbsolute = $this.css( 'position' ) == 'absolute';

	$( document ).ready(
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'load',
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'scroll',
		function () {
			stm_listing_fixed_header();
		}
	);
}

// magazine scripts
if ($( '.header-magazine.header-magazine-fixed' ).length > 0) {

	var $this      = $( '.header-magazine.header-magazine-fixed' );
	var isAbsolute = $this.css( 'position' ) == 'absolute';

	$( document ).ready(
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).load(
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_listing_fixed_header();
		}
	);

	$( window ).scroll(
		function () {
			stm_listing_fixed_header();
		}
	);
}

// motorcycle scripts
if ($( '.stm_motorcycle-header.header-listing-fixed' ).length > 0) {

	var $this      = $( '.stm_motorcycle-header.header-listing-fixed' );
	var isAbsolute = $this.css( 'position' ) == 'absolute';

	$( document ).ready(
		function () {
			stm_motocycle_fixed_header();
		}
	);

	$( window ).on(
		'load',
		function () {
			stm_motocycle_fixed_header();
		}
	);

	$( window ).on(
		'resize',
		function () {
			stm_motocycle_fixed_header();
		}
	);

	$( window ).on(
		'scroll',
		function () {
			stm_motocycle_fixed_header();
		}
	);
}

function stm_sticky_service_header() {
	var currentScrollPos = $( window ).scrollTop();
	var headerPos        = $( '#header' ).offset().top;
	if (currentScrollPos >= headerPos) {
		$( '.header-service-fixed' ).addClass( 'header-service-sticky' );
	} else {
		$( '.header-service-fixed' ).removeClass( 'header-service-sticky' );
	}

	if (sections) {
		hasVisible = false;
		sections.forEach(
			function(sectionObj){

				if (currentScrollPos < sectionObj.height && (currentScrollPos + headerOffset + 1) > sectionObj.offset) {

					currentVisibleSection = sectionObj.id;

					$( '.header-service .header-menu li' ).removeClass( 'active' );
					$( 'a[href="' + sectionObj.id + '"]' ).closest( 'li' ).addClass( 'active' );

					if ( ! hasVisible) {
						hasVisible = true;
					}

				}

			}
		);

		if ( ! hasVisible) {
			$( '.header-service .header-menu li' ).removeClass( 'active' );
		}
	}
}

function stm_getSections() {
	sections = [];

	$( '.header-menu li' ).each(
		function(){

			var currentId = $( this ).find( 'a' ).attr( 'href' );

			if (currentId.charAt( 0 ) == '#') {

				var currentIdOffset = $( 'div' + currentId ).offset();

				if (typeof currentIdOffset != 'undefined') {
					currentIdOffset    = currentIdOffset.top;
					var currenIdHeight = $( 'div' + currentId ).outerHeight() + currentIdOffset;
					sections.push(
						{
							id:currentId,
							offset: currentIdOffset,
							height: currenIdHeight
						}
					);
				}
			}
		}
	);
}

function stm_listing_fixed_header() {

	if ( $( '.header-listing' ).hasClass( 'header-listing-fixed' ) || $( '.header-magazine' ).hasClass( 'header-magazine-fixed' ) || $( '.header-main-ev_dealer' ).hasClass( 'header-listing-fixed' )) {
		let $header           = $( '#header' ),
			currentScrollPos  = $( window ).scrollTop(),
			headerPos         = $header.offset().top,
			$headerHeight     = $this.outerHeight(),
			stmFixedClass     = 'stm-fixed',
			invisibleClass    = stmFixedClass + '-invisible',
			invisibleMobClass = invisibleClass + '-mobile',
			headerWasFixed    = 'stm-header-was-fixed',
			innerContent      = $this.find( ".header-inner-content" );

		if ( $this.hasClass( 'listing-nontransparent-header' ) || ( $this.hasClass( invisibleMobClass ) && ! isAbsolute ) ) {
			$header.css( 'min-height', $headerHeight + 'px' );
		}

		if ( currentScrollPos > headerPos ) {
			$this.addClass( invisibleMobClass );

		} else {
			$this.removeClass( invisibleMobClass );
		}

		if ( $this.hasClass( invisibleMobClass ) ) {
			$this.addClass( invisibleClass );
			$header.addClass( 'stm-sticky-on' );
		} else {
			$header.removeAttr( 'style' );
			$this.removeClass( invisibleClass );
			$header.removeClass( 'stm-sticky-on' );
		}

		if ( $this.hasClass( invisibleMobClass ) ) {
			let width = $( "#main" ).width(),
				bg    = innerContent.attr( "data-bg" );

			$this.addClass( stmFixedClass );

			if ( $( ".stm-boxed" ).width() != null ) {
				innerContent.attr( "style", "background-color: " + bg + "; width: " + width + "px; max-width: " + width + "px;" );
				$header.addClass( headerWasFixed );
			}
		} else {
			$this.removeClass( stmFixedClass );
			$header.removeClass( headerWasFixed );
			innerContent.removeAttr( "style" );
		}
	}
}

function stm_motocycle_fixed_header() {
	if ( $( '.stm_motorcycle-header' ).hasClass( 'header-listing-fixed' ) ) {
		let $header          = $( '#header' ),
			currentScrollPos = $( window ).scrollTop(),
			headerPos        = $header.offset().top,
			stmFixedClass    = 'stm-fixed',
			invisibleClass   = stmFixedClass + '-invisible',
			headerWasFixed   = 'stm-header-was-fixed';

		if ( $this.hasClass( 'listing-nontransparent-header' ) ) {
			$header.css( 'min-height', $this.outerHeight() + 'px' );
		}

		if ( currentScrollPos > headerPos + 200 ) {
			$this.addClass( invisibleClass );
		} else {
			$this.removeClass( invisibleClass );
		}

		if ( currentScrollPos > headerPos + 400 ) {
			$this.addClass( stmFixedClass );
			$header.addClass( headerWasFixed );
		} else {
			$this.removeClass( stmFixedClass );
			$header.removeClass( headerWasFixed );
		}
	}
}
