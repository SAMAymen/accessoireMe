(function ($) {
    $(document).ready(function() {

        $.fn.is_on_screen = function(){
            var win = $(window);
            var viewport = {
                top : win.scrollTop(),
                left : win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();

            var bounds = this.offset();
            bounds.right = bounds.left + this.outerWidth();
            bounds.bottom = bounds.top + this.outerHeight();

            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        };

        // Default plugins
        $("select:not(.hide)").each(function () {
			var selectElement = $(this);
            selectElement.select2({
                width: '100%',
                minimumResultsForSearch: Infinity,
	            dropdownParent: $('body'),
            });
        });

        $("select:not(.hide)").on("select2:open", function() {
            var stmClass = $(this).data('class');
            $('.select2-dropdown--below').parent().addClass(stmClass);

            window.scrollTo(0, $(window).scrollTop() + 1);
            window.scrollTo(0, $(window).scrollTop() - 1);
        });

        $('body').on('change', '.stm-file-realfield', function() {
            var length = $(this)[0].files.length;

            if(length == 1) {
                var uploadVal = $(this).val();
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text(uploadVal);
            } else if(length == 0) {
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text('Choose file...');
            } else if(length > 1){
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text(length + ' files chosen');
            }
        });

        $('img.lazy').lazyload({
            effect: "fadeIn",
            failure_limit: Math.max('img'.length - 1, 0)
        });

        $('.owl-stage, .stm-single-image, .boat-gallery').lightGallery({
            selector: '.stm_fancybox',
            mode : 'lg-fade',
            download: false
        });

        $('.owl-stage').lightGallery({
            selector: '.fancy-iframe',
            mode: 'lg-fade',
            download: false
        })

        $('.fancy-iframe').lightGallery({
            selector: 'this',
            iframeMaxWidth: '80%',
            mode: 'lg-fade',
            download: false
        })

        $('#stm-google-map').lightGallery({
            selector: 'this',
            iframeMaxWidth: '80%',
            mode: 'lg-fade',
            download: false
        })

        $('.stm-carousel').lightGallery({
            selector: '.stm_fancybox',
            mode : 'lg-fade',
            download: false
        });

		// aircraft default mobile menu
        $('.stm-menu-trigger').on('click', function(){
            $('.stm-opened-menu-listing').toggleClass('opened');
			$('.stm-opened-menu-magazine').toggleClass('opened');
            $(this).toggleClass('opened');
            if($(this).hasClass('opened') && $(this).hasClass('stm-body-fixed')) {
                $('body').addClass('body-noscroll');
                $('html').addClass('no-scroll');
            } else {
                $('body').removeClass('body-noscroll');
                $('html').removeClass('no-scroll');
            }
        });

		// dealer one mobile menu
		function stmMobileMenu() {
			$('.mobile-menu-trigger').on('click', function(){
				$(this).toggleClass('opened');
				$('.mobile-menu-holder').slideToggle();

				// close the contacts dropdown
				if($('.mobile-contacts-trigger').length > 0) {
					$('.mobile-contacts-trigger').removeClass('open');
					$('.header-top-info').removeClass('open');
				}

				if($('.header-top-info').hasClass('open')) {
					$('#stm-overlay').show();
				} else {
					$('#stm-overlay').hide();
				}
			})
			$(".mobile-menu-holder .header-menu li.menu-item-has-children > a")
				.after('<span class="arrow"><i class="fas fa-angle-right"></i></span>');

			$(".magazine-menu-mobile > li.menu-item-has-children > a")
				.after('<span class="arrow"><i class="fas fa-angle-right"></i></span>');

			// arrow is clicked
			$('.mobile-menu-holder .header-menu .arrow').on('click', function() {

				$(this).toggleClass('active');

				$(this).closest('li').toggleClass('opened');

				if ( !$(this).parent().hasClass('stm_megamenu') ) {

					$(this).closest('li').find('> ul.sub-menu').slideToggle(300);

					// hide any existing subs
					if ( $(this).closest('li').find('> ul.sub-menu').has('.sub-menu').length > 0 ) {
						$(this).closest('li').find('> ul.sub-menu').find('ul.sub-menu').hide();
						$(this).closest('li').find('> ul.sub-menu').find('.arrow').removeClass('active');
					}
				}
			})

			// if menu item with "#" link is clicked
			$(".mobile-menu-holder .header-menu > li.menu-item-has-children > a").on('click', function (e) {

				if ( $(this).attr('href') == '#' ) {

					e.preventDefault();

					$(this).closest('li').find(' > ul.sub-menu').slideToggle(300);

					$(this).closest('li').toggleClass('opened');

					$(this).closest('li').find(' > .arrow').toggleClass('active');

					// hide any existing subs
					if ( $(this).closest('li').find('> ul.sub-menu').has('.sub-menu').length > 0 ) {
						$(this).closest('li').find('> ul.sub-menu').find('ul.sub-menu').hide();
						$(this).closest('li').find('> ul.sub-menu').find('.arrow').removeClass('active');
					}
				}
			});

			$('body').on('click', '.magazine-menu-mobile > li.menu-item-has-children >.arrow', function (e) {
				$(this).parent().toggleClass('active');
			});
		}

		$('.mobile-contacts-trigger').on('click', function(){
			$(this).toggleClass('open');
			$('.header-top-info').toggleClass('open');

			if($('.mobile-menu-trigger').hasClass('opened')) {
				$('.mobile-menu-trigger').removeClass('opened');
				$('.mobile-menu-holder').slideToggle();
			}

			if($('.header-top-info').hasClass('open')) {
				$('#stm-overlay').show();
			} else {
				$('#stm-overlay').hide();
			}
		});

		stmMobileMenu();

	    $(window).ready(setMobileMenuHeight);
	    $(window).resize(setMobileMenuHeight);
	    $(window).scroll(setMobileMenuHeight);

	    function setMobileMenuHeight() {
		    let header_main = $('#header > div.header-main, #header > div.header-listing'),
			    mobile_menu_holder = $('.stm-opened-menu-listing')

		    if (window.innerWidth > 1025 || !header_main.length || !mobile_menu_holder.length) {
			    return
		    }

		    let top_bar = $('#top-bar'),
			    top_bar_height = top_bar.outerHeight(),
			    header_main_height = header_main.outerHeight(),
			    _top_bar_offset = 0, _header_main_offset = 0;

		    //don't calculate top-bar if menu is fixed
		    if (header_main.hasClass('stm-fixed')) {
			    top_bar_height = 0
		    }

		    if (!top_bar_height) {
			    top_bar_height = 0
		    }

		    if (!header_main_height) {
			    header_main_height = 0
		    }

		    if (top_bar.length) {
			    _top_bar_offset = top_bar.offset().top
		    }

		    _header_main_offset = header_main.offset().top

		    let top_bar_offset = _top_bar_offset,
			    top_bar_visible_height = calculateVisibleHeight(top_bar_height, top_bar_offset),
			    header_main_offset = _header_main_offset,
			    header_main_visible_height = calculateVisibleHeight(header_main_height, header_main_offset)

		    let calculated_height = window.innerHeight - top_bar_visible_height - header_main_visible_height
		    mobile_menu_holder.css('height', calculated_height)
	    }

	    function calculateVisibleHeight(elHeight, elOffset) {
		    if (!elHeight) {
			    elHeight = 0
		    }

		    if (!elOffset) {
			    elOffset = 0
		    }

		    let scrollY = window.scrollY,
			    visible_height = elHeight

		    if (scrollY > elOffset) {
			    visible_height = (elOffset + elHeight) - scrollY
		    }

		    if ((elOffset + scrollY) < elHeight) {
			    visible_height = elHeight - (elOffset + scrollY)
		    }

		    if ((elOffset + scrollY) < elHeight && elOffset > scrollY) {
			    visible_height = elHeight
		    }

		    if ((elOffset + elHeight) < scrollY) {
			    visible_height = 0
		    }

		    return visible_height
	    }

		// boats and dealer two mobile menu
        $('.stm-menu-boats-trigger').on('click', function(){
            $(this).toggleClass('opened');
            $('.stm-boats-mobile-menu').toggleClass('opened');
        });

        $('.stm-boats-mobile-menu .listing-menu > li.menu-item-has-children > a').append('<span class="stm-boats-menu-first-lvl"></span>');

        $('body').on('click', '.stm-boats-menu-first-lvl', function(e){
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').toggle();
            $(this).parent().parent().toggleClass('active');
            $(this).toggleClass('active');
        });

        $('.stm-share').on('click', function (e) {
            e.preventDefault();
        });

        $('.stm-shareble').on({
            mouseenter: function () {
                $(this).parent().find('.stm-a2a-popup').addClass('stm-a2a-popup-active');
            },
            mouseleave: function () {
                $(this).parent().find('.stm-a2a-popup').removeClass('stm-a2a-popup-active');
            }
        });

        $("select[name='stm-multi-currency']").on("select2:select", function () {
            var currency = $(this).val();

            $.cookie('stm_current_currency', encodeURIComponent(currency), { expires: 7, path: '/' });
            var data = $(this).select2('data');
            var selectedText = $(this).attr("data-translate").replace("%s", data[0].text);

            $(".stm-multiple-currency-wrap").find("span.select2-selection__rendered").text(selectedText);
            location.reload();
        });

        stmShowListingIconFilter();

		if($('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').length > 0) {
			// on desktop, hover
			$(document).on('mousemove', '.interactive-hoverable .hoverable-wrap .hoverable-unit', function(){
				var index = $(this).index();
				if($(this).parent().siblings('.hoverable-indicators').find('.indicator.active').index() !== index) {
					$(this).parent().siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
					$(this).parent().siblings('.hoverable-indicators').find('.indicator').eq(index).addClass('active');
				}

				$(this).siblings().removeClass('active');
				$(this).addClass('active');
			});

			$(document).on('mouseleave', '.interactive-hoverable', function(){
				$(this).find('.hoverable-indicators .indicator.active').removeClass('active');
				$(this).find('.hoverable-indicators .indicator:first-child').addClass('active');

				$(this).find('.hoverable-wrap .hoverable-unit.active').removeClass('active');
				$(this).find('.hoverable-wrap .hoverable-unit:first-child').addClass('active');
			});

			// on mobile, swipe
			stm_init_hoverable_swipe();
		};


		// hoverable gallery preview using Brazzers Carousel library
		if($('.brazzers-carousel').length > 0) {
			var brazzers_carousel = $('.brazzers-carousel');
			brazzers_carousel.brazzersCarousel();

			brazzers_carousel.each(function(){
				// remaining number of photos
				if(typeof $(this).data('remaining') !== undefined && $(this).data('remaining') > 0) {
					let remaining_number = parseInt($(this).data('remaining'));
					var remaining_label = remaining_number + ' ' + photo_remaining_singular;
					if(remaining_number > 1) {
						var remaining_label = remaining_number + ' ' + photo_remaining_plural;
					}

					$(this).find('.tmb-wrap').append('<div class="remaining"><i class="stm-icon-album"></i><p>' + remaining_label + '</p></div>');
				}
			});

			$('.brazzers-carousel .tmb-wrap-table > div:nth-child(5)').on('mouseenter', function(){
				$(this).parent().siblings('.remaining').addClass('active');
			});

			$('.brazzers-carousel .tmb-wrap-table > div:nth-child(5)').on('mouseleave', function(){
				$(this).parent().siblings('.remaining').removeClass('active');
			});

			$('.brazzers-carousel .tmb-wrap').on('mouseleave', function(){
				$(this).find('.tmb-wrap-table > div').removeClass('active');
				$(this).find('.tmb-wrap-table > div:first-child').trigger('mouseenter');
			});
		}

    }); // document ready


	// swipe events using vanilla js
	var  SwipeEvent  = (function () {
		function  SwipeEvent(element) {
			this.xDown  =  null;
			this.yDown  =  null;
			this.element  =  typeof (element) === 'string' ? document.querySelector(element) : element;
			this.element.addEventListener('touchstart', function (evt) {
				this.xDown  =  evt.touches[0].clientX;
				this.yDown  =  evt.touches[0].clientY;
			}.bind(this), false);
		}

		SwipeEvent.prototype.onLeft  =  function (callback) {
			this.onLeft  =  callback;
			return this;
		};
		SwipeEvent.prototype.onRight  =  function (callback) {
			this.onRight  =  callback;
			return this;
		};
		SwipeEvent.prototype.onUp  =  function (callback) {
			this.onUp  =  callback;
			return this;
		};
		SwipeEvent.prototype.onDown  =  function (callback) {
			this.onDown  =  callback;
			return this;
		};

		SwipeEvent.prototype.handleTouchMove  =  function (evt) {
			if (!this.xDown  ||  !this.yDown) {
				return;
			}
			var  xUp  =  evt.touches[0].clientX;
			var  yUp  =  evt.touches[0].clientY;
			this.xDiff  = this.xDown  -  xUp;
			this.yDiff  = this.yDown  -  yUp;

			if (Math.abs(this.xDiff) !==  0) {
				if (this.xDiff  >  2) {
					typeof (this.onLeft) ===  "function"  && this.onLeft();
				} else  if (this.xDiff  <  -2) {
					typeof (this.onRight) ===  "function"  && this.onRight();
				}
			}

			if (Math.abs(this.yDiff) !==  0) {
				if (this.yDiff  >  2) {
					typeof (this.onUp) ===  "function"  && this.onUp();
				} else  if (this.yDiff  <  -2) {
					typeof (this.onDown) ===  "function"  && this.onDown();
				}
			}
			// Reset values.
			this.xDown  =  null;
			this.yDown  =  null;
		};

		SwipeEvent.prototype.run  =  function () {
			this.element.addEventListener('touchmove', function (evt) {
				this.handleTouchMove(evt);
			}.bind(this), false);
		};

		return  SwipeEvent;
	}());


	function stm_init_hoverable_swipe() {
		if($('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').length > 0) {
			$('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').each((index, el) => {
				let galleryPreviewSwiper = new SwipeEvent(el);

				galleryPreviewSwiper.onRight(function() {
					let active_index = $(this.element).find('.hoverable-unit.active').index();
					$(this.element).find('.hoverable-unit').removeClass('active');
					$(this.element).siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
					if(active_index === 0) {
						$(this.element).find('.hoverable-unit:last-child').addClass('active');
						$(this.element).siblings('.hoverable-indicators').find('.indicator:last-child').addClass('active');
					} else {
						$(this.element).find('.hoverable-unit').eq(active_index - 1).addClass('active');
						$(this.element).siblings('.hoverable-indicators').find('.indicator').eq(active_index - 1).addClass('active');
					}
				});

				galleryPreviewSwiper.onLeft(function() {
					let active_index = $(this.element).find('.hoverable-unit.active').index();
					let total_items = $(this.element).find('.hoverable-unit');
					$(this.element).find('.hoverable-unit').removeClass('active');
					$(this.element).siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
					if(active_index === parseInt(total_items.length - 1)) {
						$(this.element).find('.hoverable-unit:first-child').addClass('active');
						$(this.element).siblings('.hoverable-indicators').find('.indicator:first-child').addClass('active');
					} else {
						$(this.element).find('.hoverable-unit').eq(active_index + 1).addClass('active');
						$(this.element).siblings('.hoverable-indicators').find('.indicator').eq(active_index + 1).addClass('active');
					}
				});

				galleryPreviewSwiper.run();
			});
		}
	}
		//if preloader timer not empty
		if (preloader_timer !== undefined || preloader_timer !== null || preloader_timer !== '') {
			setTimeout(function () {
				stmPreloader();
			}, preloader_timer);
		}

    $(window).on('load',function () {
			//if preloader timer empty
			if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') {
				stmPreloader();
			}
        stm_listing_mobile_functions();
    });

    function stmPreloader() {
        if($('html').hasClass('stm-site-preloader')) {
					$('html').addClass('stm-site-loaded');
					if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') { // if preloader timer is not set
						setTimeout(function () {
							$('html').removeClass('stm-site-preloader stm-site-loaded');
						}, 250);
					} else { // if preloader timer is set
						$('html').removeClass('stm-site-preloader stm-site-loaded');
					}
				}
    }

    function stm_listing_mobile_functions() {
        $('.listing-menu-mobile > li.menu-item-has-children > a').append('<span class="stm_frst_lvl_trigger"></span>');
        $('body').on('click', '.stm_frst_lvl_trigger', function(e){
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').slideToggle();
            $(this).parent().parent().toggleClass('show_submenu');
            $(this).parent().parent().toggleClass('active');
            $(this).toggleClass('active');
        });

        $('body').on('click', 'a.has-child', function(e){

            if($(this).hasClass('active')) {
                $(this).parent().blur();
                $(this).toggleClass('active');
            } else {
                e.preventDefault();
                $(this).toggleClass('active');
            }
        });
    }

    function stmShowListingIconFilter() {
        $('.stm_icon_filter_label').on('click', function(){

            if(!$(this).hasClass('active')) {
                $(this).closest('.stm_icon_filter_unit').find('.stm_listing_icon_filter').toggleClass('active');
                $(this).closest('.stm_icon_filter_unit').find('.stm_listing_icon_filter .image').hide();

                $(this).addClass('active');
            } else {
                $(this).closest('.stm_icon_filter_unit').find('.stm_listing_icon_filter').toggleClass('active');
                $(this).closest('.stm_icon_filter_unit').find('.stm_listing_icon_filter .image').show();

                $(this).removeClass('active');
            }

        });
    }

        window.STMCascadingSelect = function STMCascadingSelect(container, relations) {
            this.relations = relations;
            this.ctx = container;
            this.options = {
                selectBoxes: []
            };

            var self = this;
            $.each(this.relations, function (slug, options) {
                var selectbox = self.selectbox(slug, options);

                if (selectbox && typeof selectbox === 'object') {
                    self.options.selectBoxes.push(selectbox);
                }
            });

            $(container).cascadingDropdown(this.options);
        };

        STMCascadingSelect.prototype.selectbox = function (slug, config) {
            var parent = config.dependency;

            /*if (!$(this.selector(slug), this.ctx).length || (parent && !$(this.selector(parent), this.ctx).length)) {
                return null;
            }*/

            var $select = $(this.selector(slug), this.ctx);
            var selected = $select.data('selected');

            if ($select.prop('multiple')) {
                selected = selected ? selected.split(',') : [];
            }

            return {
                selector: this.selector(slug),
                paramName: slug,
                requires: parent ? [this.selector(parent)] : null,
                allowAll: config.allowAll,
                selected: selected,
                source: function (request, response) {
                    var selected = request[parent];
                    var options = [];
                    $.each(config.options, function (i, option) {
                        if ((config.allowAll && !selected) || (option.deps && option.deps.indexOf(selected) >= 0)) {
                            options.push(option);
                        }
                    });

                    response(options);
                }
            };
        };

        STMCascadingSelect.prototype.selector = function (slug) {
            if (this.relations[slug].selector) {
                return this.relations[slug].selector;
            }

            return '[name="' + slug + '"]';
        }

		 $('body').on(
			'click',
			'.listing-car-item-meta .price[data-target="#get-car-price"]',
			function (e) {
				if ($(e.target).closest('a').length) {
					e.preventDefault()
				}
			}
		)

})(jQuery)
