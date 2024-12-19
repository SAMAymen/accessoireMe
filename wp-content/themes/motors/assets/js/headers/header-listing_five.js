(function ($) {
	$( document ).ready(
		function () {

			var $this      = $( '.header-main.header-listing-fixed' );
			var isAbsolute = $this.css( 'position' ) === 'absolute';

			$( '.stm-menu-trigger' ).on(
				'click',
				function(){
					$( '.stm-opened-menu-listing' ).toggleClass( 'opened' );
					$( this ).toggleClass( 'opened' );
					if ($( this ).hasClass( 'opened' ) && $( this ).hasClass( 'stm-body-fixed' )) {
						$( 'body' ).addClass( 'body-noscroll' );
						$( 'html' ).addClass( 'no-scroll' );
					} else {
						$( 'body' ).removeClass( 'body-noscroll' );
						$( 'html' ).removeClass( 'no-scroll' );
					}
				}
			);

			stm_listing_fixed_header();
			$( window ).on( 'load', stm_listing_fixed_header );
			$( window ).on( 'resize', stm_listing_fixed_header );
			$( window ).on( 'scroll', stm_listing_fixed_header );

			function stm_listing_fixed_header() {
				let header_main    = $( '.header-main' ),
					$header        = $this.parents( '#header' ),
					stmFixedClass  = 'stm-fixed',
					invisibleClass = stmFixedClass + '-invisible';

				if ( header_main.hasClass( 'header-listing-fixed' ) ) {
					let currentScrollPos = $( window ).scrollTop(),
						headerPos        = $header.offset().top;

					if ( currentScrollPos > headerPos + 200 ) {
						if ( ! isAbsolute ) {
							$header.css( 'min-height', $header.outerHeight() + 'px' );
						}
						$this.addClass( invisibleClass );
						$header.addClass( 'stm-sticky-on' );
					} else {
						$header.removeAttr( 'style' );
						$this.removeClass( invisibleClass );
						$header.removeClass( 'stm-sticky-on' );
					}

					if ( currentScrollPos > headerPos + 400 ) {
						header_main.addClass( stmFixedClass );
					} else {
						header_main.removeClass( stmFixedClass );
					}
				}
			}

		}
	);
})( jQuery )
