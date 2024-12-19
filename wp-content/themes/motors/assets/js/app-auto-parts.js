"use strict";

(function($) {

    $.fn.parallax = function () {
        var window_width = $(window).width();
        // Parallax Scripts
        return this.each(function(i) {
            var $this = $(this);
            $this.addClass('parallax');

            function updateParallax(initial) {
                var container_height;
                if (window_width < 601) {
                    container_height = ($this.height() > 0) ? $this.height() : $this.children("img").height();
                }
                else {
                    container_height = ($this.height() > 0) ? $this.height() : 500;
                }
                var $img = $this.children("img").first();
                var img_height = $img.height();
                var parallax_dist = img_height - container_height + 100;
                var bottom = $this.offset().top + container_height;
                var top = $this.offset().top;
                var scrollTop = $(window).scrollTop();
                var windowHeight = window.innerHeight;
                var windowBottom = scrollTop + windowHeight;
                var percentScrolled = (windowBottom - top) / (container_height + windowHeight);
                var parallax = Math.round((parallax_dist * percentScrolled));

                if (initial) {
                    $img.css('display', 'block');
                }
                if ((bottom > scrollTop) && (top < (scrollTop + windowHeight))) {
                    $img.css('transform', "translate3D(-50%," + parallax + "px, 0)");
                }

            }

            // Wait for image load
            $this.children("img").one("load", function() {
                updateParallax(true);
            }).each(function() {
                if(this.complete) $(this).load();
            });

            $(document).ready(function () {
                updateParallax(false);
            });

            $(window).on('scroll', function() {
                window_width = $(window).width();
                updateParallax(false);
            });

            $(window).on('resize',function() {
                window_width = $(window).width();
                updateParallax(false);
            });

        });

    };

    $(document).ready(function () {

        var shareTimer;

        $('[data-toggle="tooltip"]').tooltip();

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('img').trigger('appear');
        });

        $('.woocommerce-Price-amount').addClass('heading-font');

        stm_stretch_image();

        if($('.stm-simple-parallax').length) {
            $('.stm-simple-parallax').append('<div class="stm-simple-parallax-gradient"><div class="stm-simple-parallax-vertical"></div></div>');
            jQuery(window).on('scroll', function(){
                var currentScrollPos = $(window).scrollTop();
                var scrollOn = 400 - parseFloat(currentScrollPos/1.2);
                if(scrollOn < -200) {
                    scrollOn = -200;
                }
                $('.stm-simple-parallax').css('background-position', '0 ' + scrollOn + 'px');
            });
        }

        if($('.stm-single-car-page').length && !$('body').hasClass('stm-template-car_dealer_two')) {
            jQuery(window).on('scroll', function(){
                var currentScrollPos = $(window).scrollTop();
                var scrollOn = 200 - parseFloat(currentScrollPos/1.2);
                if(scrollOn < -200) {
                    scrollOn = -200;
                }

                $('.stm-single-car-page').css('background-position', '0 ' + scrollOn + 'px');
            });
        }

        stm_footer_selection();
        stm_listing_mobile_functions();

        if($('.listing-nontransparent-header').length > 0) {
            $('#wrapper').css('padding-top', $('.listing-nontransparent-header').outerHeight() + 'px');
        }

        if($('.stm-banner-image-filter').length > 0) {
            $('.stm-banner-image-filter').css('top', $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top + 'px');
        }

        $('.stm-material-parallax').parallax();

        //Custom functions
        footerToBottom();
        stmFullwidthWithParallax();
        stmMobileMenu();

        function stmIsValidURL(str) {
            var a  = document.createElement('a');
            a.href = str;
            return (a.host && a.host != window.location.host) ? true : false;
        }
        disableFancyHandy();

        // Is on screen
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

        $('.stm-customize-page .wpb_tabs').remove();

        //Default plugins
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

        $('img.lazy').lazyload({
            effect: "fadeIn",
            failure_limit: Math.max('img'.length - 1, 0)
        });

        $('p').each(function(){
            if( $(this).html() == '' ) {
                $(this).addClass('stm-hidden');
            }
        });

        var pixelRatio = window.devicePixelRatio || 1;

		if(typeof pixelRatio != 'undefined' && pixelRatio > 1) {
			$('img').each(function(){
				var stm_retina_image = $(this).data('retina');

				if(typeof stm_retina_image != 'undefined') {
					$(this).attr('src', stm_retina_image);
				}
			})
		}

        $('.header-menu').on('click', function(e){
            var link = $(this).attr('href');
            if(link == '#') {
                e.preventDefault();
            }
        })

        $(".rev_slider_wrapper").each(function(){
            var $this = $(this);
            $this.on('revolution.slide.onloaded', function() {
                setTimeout(function(){
                    $('.stm-template-boats .wpb_revslider_element .button').addClass('loaded');
                }, 1000);
            });
        });

    });

    $(window).on('load',function () {
        footerToBottom();
        stmFullwidthWithParallax();
        stm_stretch_image();

        $('.stm-blackout-overlay').addClass('stm-blackout-loaded');

        stmPreloader();

        if($('.stm-banner-image-filter').length > 0) {
            $('.stm-banner-image-filter').css('top', $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top + 'px');
        }

        if($('.listing-nontransparent-header').length > 0) {
            $('#wrapper').css('padding-top', $('.listing-nontransparent-header').outerHeight() + 'px');
        }

        $('body').removeClass('stm-preloader');
    });

    $(window).on('resize',function () {
        footerToBottom();
        stmFullwidthWithParallax();
        stm_stretch_image();
        disableFancyHandy();

        if($('.stm-banner-image-filter').length > 0) {
            $('.stm-banner-image-filter').css('top', $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top + 'px');
        }

        if($('.listing-nontransparent-header').length > 0) {
            $('#wrapper').css('padding-top', $('.listing-nontransparent-header').outerHeight() + 'px');
        }
    });

    function loadVisible($els, trigger) {
        $els.filter(function () {
            var rect = this.getBoundingClientRect();
            return rect.top >= 0 && rect.top <= window.innerHeight;
        }).trigger(trigger);
    }

    function footerToBottom() {
        var windowH = $(window).height();
        var footerH = $('#footer').outerHeight();
        $('#wrapper').css('min-height',(windowH - footerH) + 'px');
    };

    function stm_widget_color_first_word() {
        $('.stm_wp_widget_text .widget-title h6').each(function(){
            var html = $(this).html();
            var word = html.substr(0, html.indexOf(" "));
            var rest = html.substr(html.indexOf(" "));
            $(this).html(rest).prepend($("<span/>").html(word).addClass("colored"));
        });
    }

    function stm_widget_instagram() {
        $('#sb_instagram').closest('.widget-wrapper').addClass('stm-instagram-unit');
    }

    function stmFullwidthWithParallax() {
        var screenWidth = $(window).width();
        if(screenWidth < 1140) {
            var defaultWidth = screenWidth - 30;
        } else {
            var defaultWidth = 1140 - 30;
        }
        var marginLeft = (screenWidth - defaultWidth) / 2;

        if($('body').hasClass('rtl')) {
            $('.stm-fullwidth-with-parallax').css({
                'position' : 'relative',
                'left': (marginLeft - 15) + 'px'
            })
        }

        $('.stm-fullwidth-with-parallax').css({
            'width': screenWidth + 'px',
            'margin-left': '-' + marginLeft + 'px',
            'padding-left': (marginLeft - 15) + 'px',
            'padding-right': (marginLeft - 15) + 'px'
        })
    }

    function stmMobileMenu() {
        $('.stm_mobile__switcher').on('click', function(){
            $('html').toggleClass('no-scroll');
        })

        $('.stm-header__overlay').on('click', function(){
            $('html').removeClass('no-scroll');
        })
    }

    function disableFancyHandy() {
	    var winWidth = $(window).width();
	    if(winWidth < 1025) {
		    $('.media-carousel-item .stm_fancybox').on('click', function(e){
			    e.preventDefault();
			    e.stopPropagation();
		    })
	    }
    }

    function stmPreloader() {
	    if($('html').hasClass('stm-site-preloader')){
		    $('html').addClass('stm-site-loaded');

		    setTimeout(function(){
				$('html').removeClass('stm-site-preloader stm-site-loaded');
			}, 250);

            var prevent = false;
            $('a[href^=mailto], a[href^=skype], a[href^=tel]').on('click', function(e) {
                prevent = true;
                $('html').removeClass('stm-site-preloader stm-after-hidden');
            });

            $(window).on('beforeunload', function(e, k){
                if(!prevent) {
                    $('html').addClass('stm-site-preloader stm-after-hidden');
                } else {
                    prevent = false;
                }
            });
	    }
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

    function stm_footer_selection() {
        if(typeof stm_footer_terms !== 'undefined') {
            var substringMatcher = function (strs) {
                return function findMatches(q, cb) {
                    var matches, substringRegex;

                    // an array that will be populated with substring matches
                    matches = [];

                    // regex used to determine if a string contains the substring `q`
                    var substrRegex = new RegExp(q, 'i');

                    // iterate through the pool of strings and for any string that
                    // contains the substring `q`, add it to the `matches` array
                    $.each(strs, function (i, str) {
                        if (substrRegex.test(str)) {
                            matches.push(str);
                        }
                    });

                    cb(matches);
                };
            };

            var $input = $('.stm-listing-layout-footer .stm-footer-search-inventory input');

            var selectedValue = '';

            $input.typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'stm_footer_terms',
                source: substringMatcher(stm_footer_terms)
            });

            $input.typeahead('val', stm_default_search_value).trigger('keyup');
            $input.typeahead('close');

            $input.on('keydown', function () {
                selectedValue = $(this).val();
            })

            $input.on('typeahead:select', function (ev, suggestion) {
                selectedValue = suggestion;
            });

            var enableSubmission = false;
            $('.stm-footer-search-inventory form').on('submit', function (e) {
                if (!enableSubmission) {
                    e.preventDefault();
                }
                var keyChosen = $.inArray(selectedValue, stm_footer_terms);
                if (keyChosen != -1) {
                    var slug = stm_footer_terms_slugs[keyChosen];
                    var taxonomy = stm_footer_taxes[keyChosen];
                    if (typeof(taxonomy) != 'undefined' && typeof(slug) != 'undefined' && !enableSubmission) {
                        $input.attr('name', taxonomy);
                        $input.val(slug);
                        enableSubmission = true;
                        $(this).trigger('submit');
                    }
                } else {
                    if (!enableSubmission) {
                        enableSubmission = true;
                        $(this).trigger('submit');
                    }
                }

            });
        }
    }

    $('.stm-form-1-end-unit input[type="text"]').on('blur', function(){
        if($(this).val() == '') {
            $(this).removeClass('stm_has_value');
        } else {
            $(this).addClass('stm_has_value');
        }
    })

    function stm_listing_mobile_functions() {
        $('.listing-menu-mobile > li.menu-item-has-children > a').append('<span class="stm_frst_lvl_trigger"></span>');
        $('body').on('click', '.stm_frst_lvl_trigger', function(e){
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').slideToggle();
            $(this).toggleClass('active');
        });

        $('.boats-menu-ipad > li.menu-item-has-children > a').addClass('has-child');
        $('body').on('click', 'a.has-child', function(e){

            if($(this).hasClass('active')) {
                $(this).parent().trigger('blur');
                $(this).toggleClass('active');
            } else {
                e.preventDefault();
                $(this).toggleClass('active');
            }
        });

        $('.boats-menu-ipad > li.menu-item-has-children > a.has-child').each(function() {
            $(this).parent().on('focusout', function() {
                $('.boats-menu-ipad > li.menu-item-has-children > a.has-child').removeClass('active');
            });
        });

		$('.lOffer-account-dropdown.stm-login-form-unregistered form input').on('focus', function() {

			$('.lOffer-account-unit').find('.lOffer-account').addClass('active');
			$('.lOffer-account-unit').find('.lOffer-account-dropdown.stm-login-form-unregistered').addClass('active');

		})

		$('.lOffer-account-dropdown.stm-login-form-unregistered form input').on('blur', function() {
			$('.lOffer-account-unit').find('.lOffer-account').removeClass('active');
			$('.lOffer-account-unit').find('.lOffer-account-dropdown.stm-login-form-unregistered').removeClass('active');
		})

        $('.stm-menu-trigger').on('click', function(){

            $('.lOffer-account').removeClass('active');
            $('.stm-user-mobile-info-wrapper').removeClass('active');

            $('.stm-opened-menu-listing').toggleClass('opened');
            $('.stm-opened-menu-magazine').toggleClass('opened');
            $(this).toggleClass('opened');
        });

        $('.lOffer-account').on('click', function(e) {
            e.preventDefault();

            $('.stm-opened-menu-listing').removeClass('opened');
            $('.stm-opened-menu-magazine').removeClass('opened');
            $('.stm-menu-trigger').removeClass('opened');

            $(this).toggleClass('active');

            $(this).closest('.lOffer-account-unit').find('.stm-user-mobile-info-wrapper').toggleClass('active');
        });


        $('.stm-rent-lOffer-account').on('click', function(e) {
            e.preventDefault();

            $('.stm-opened-menu-listing').removeClass('opened');
            $('.stm-opened-menu-magazine').removeClass('opened');
            $('.stm-menu-trigger').removeClass('opened');

            $(this).toggleClass('active');

            $(this).closest('.stm-rent-lOffer-account-unit').find('.stm-user-mobile-info-wrapper').toggleClass('active');
        });

        $('body').on('click', function(e) {
            if ($(e.target).closest('#header').length === 0) {
                $('.lOffer-account').removeClass('active');
                $('.stm-user-mobile-info-wrapper').removeClass('active');
                $('.stm-login-form-unregistered').removeClass('active');
                $('.stm-opened-menu-listing').removeClass('opened');
                $('.stm-opened-menu-magazine').removeClass('opened');
                $('.stm-menu-trigger').removeClass('opened');
            }
        });


        /*Boats*/
        $('.stm-menu-boats-trigger').on('click', function(){
            $(this).toggleClass('opened');
            $('.stm-boats-mobile-menu').toggleClass('opened');
        });

        $('.stm-boats-mobile-menu .listing-menu > li.menu-item-has-children > a').append('<span class="stm-boats-menu-first-lvl"></span>');

        $('body').on('click', '.stm-boats-menu-first-lvl', function(e){
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').toggle();
            $(this).toggleClass('active');
        })
    }

    $('.service-mobile-menu-trigger').on('click', function(){
        $('.header-service .header-menu').slideToggle();
        $(this).toggleClass('active');
    });
    
    $('.mobile-header-hamburger').on('click', function() {
        $('.mobile-menu-wrapper').toggleClass('open');
        $('.mobile-header-hamburger').toggleClass('open');
    });
    
    $('.menu-item-has-children').each(function() {
        $(this).append('<i class="fas fa-angle-right"></i>');
    } );
    
    $('.menu-item-has-children > i').on('click', function(e) {
        e.preventDefault();
        var $subMenu = $(this).siblings('.sub-menu');
        $subMenu.toggleClass('active');
        
        $(this).toggleClass('opened');
    });
    
    $('.sub-menu > .menu-item-has-children > i').on('click', function(e) {
        e.preventDefault();
        var $subSubMenu = $(this).siblings('> .sub-menu');
        $subSubMenu.toggleClass('active');
        
        $(this).toggleClass('sub-menu-opened');
    });
    
    $(document).ready(function() {
        function handleScroll() {
            var menuWrapper = $('.mobile-menu-wrapper');
            var scrollPosition = $(window).scrollTop();
            
            if (scrollPosition >= 70) {
                menuWrapper.addClass('fixed');
            } else {
                menuWrapper.removeClass('fixed');
            }
        }
        
        $(window).on('scroll', handleScroll);
    });
    
    $(document).ready(function() {
        var header = $('#header');
        var headerOffset = header.offset().top;
        
        $(window).scroll(function() {
            var scrollPosition = $(window).scrollTop();
            
            if (scrollPosition >= headerOffset) {
                header.addClass('fixed');
            } else {
                header.removeClass('fixed');
            }
        });
    });
    
    $(document).ready(function() {
        var header = $('.header-auto-parts-fixed');
        var scrollTrigger = header.offset().top;
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > scrollTrigger) {
                header.addClass('fixed');
            } else {
                header.removeClass('fixed');
            }
        });
    });
    
    
})(jQuery);

function stm_stretch_image() {
    var $ = jQuery;
    var position = '.stm-stretch-image-right';

    if($(position).length) {
        var windowW = $(document).width();
        var containerW = $('.header-main .container').width();

        var marginW = (windowW - containerW) / 2;

        $(position + ' .vc_column-inner').css({
            'margin-right' : '-' + marginW + 'px'
        });
    }

    position = '.stm-stretch-image-left';

    if($(position).length) {
        var windowW = $(document).width();
        var containerW = $('.header-main .container').width();

        var marginW = (windowW - containerW) / 2;

        $(position + ' .vc_column-inner').css({
            'margin-left' : '-' + marginW + 'px'
        });
    }
}

function stm_test_drive_car_title(id, title) {
    var $ = jQuery;
    $('.test-drive-car-name').text(title);
    $('input[name=vehicle_id]').val(id);
}

function stm_isotope_sort_function(currentChoice) {

    var $ = jQuery;
    var stm_choice = currentChoice;
    var $container = $('.stm-isotope-sorting');
    switch(stm_choice){
        case 'price_low':
            $container.isotope({
	            getSortData: {
                    price: function (itemElem) {
                        var price = $(itemElem).data('price');
                        return parseFloat(price);
                    }
                },
                sortBy: 'price',
                sortAscending: true
            });
            break;
        case 'price_high':
            $container.isotope({
	            getSortData: {
                    price: function (itemElem) {
                        var price = $(itemElem).data('price');
                        return parseFloat(price);
                    }
                },
                sortBy: 'price',
                sortAscending: false
            });
            break;
        case 'date_low':
            $container.isotope({
	            getSortData: {
                    date: function (itemElem) {
				        var date = $(itemElem).data('date');
				        return parseFloat(date);
				    },
                },
                sortBy: 'date',
                sortAscending: true
            });
            break;
        case 'date_high':
            $container.isotope({
	            getSortData: {
                    date: function (itemElem) {
				        var date = $(itemElem).data('date');
				        return parseFloat(date);
				    },
                },
                sortBy: 'date',
                sortAscending: false
            });
            break;
        case 'mileage_low':
            $container.isotope({
	            getSortData: {
                    mileage: function (itemElem) {
				        var mileage = $(itemElem).data('mileage');
				        return parseFloat(mileage);
				    }
                },
                sortBy: 'mileage',
                sortAscending: true
            });
            break;
        case 'mileage_high':
            $container.isotope({
	            getSortData: {
                    mileage: function (itemElem) {
				        var mileage = $(itemElem).data('mileage');
				        return parseFloat(mileage);
				    }
                },
                sortBy: 'mileage',
                sortAscending: false
            });
            break;
        case 'distance':
            $container.isotope({
                getSortData: {
                    distance: function (itemElem) {
                        var distance = $(itemElem).data('distance');
                        return parseFloat(distance);
                    }
                },
                sortBy: 'distance',
                sortAscending: true
            });
            break;
        default:
    }

    $container.isotope('updateSortData').isotope();
}

function stm_check_mobile() {
    var isMobile = false; //initiate as false
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    return isMobile;
}