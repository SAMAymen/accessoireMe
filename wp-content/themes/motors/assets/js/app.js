"use strict";

(function ($) {
    $.fn.parallax = function () {
        var window_width = $(window).width();
        // Parallax Scripts
        return this.each(function (i) {
            var $this = $(this);
            $this.addClass('parallax');

            function updateParallax(initial) {
                var container_height;
                if (window_width < 601) {
                    container_height = ($this.height() > 0) ? $this.height() : $this.children("img").height();
                } else {
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
            $this.children("img").one("load", function () {
                updateParallax(true);
            }).each(function () {
                if (this.complete) $(this).load();
            });

            $(document).ready(function () {
                updateParallax(false);
            });

            $(window).on('scroll', function () {
                window_width = $(window).width();
                updateParallax(false);
            });

            $(window).on('resize', function () {
                window_width = $(window).width();
                updateParallax(false);
            });

        });

    };

    // swipe events using vanilla js
    var SwipeEvent = (function () {
        function SwipeEvent(element) {
            this.xDown = null;
            this.yDown = null;
            this.element = typeof (element) === 'string' ? document.querySelector(element) : element;
            this.element.addEventListener('touchstart', function (evt) {
                this.xDown = evt.touches[0].clientX;
                this.yDown = evt.touches[0].clientY;
            }.bind(this), false);
        }

        SwipeEvent.prototype.onLeft = function (callback) {
            this.onLeft = callback;
            return this;
        };
        SwipeEvent.prototype.onRight = function (callback) {
            this.onRight = callback;
            return this;
        };
        SwipeEvent.prototype.onUp = function (callback) {
            this.onUp = callback;
            return this;
        };
        SwipeEvent.prototype.onDown = function (callback) {
            this.onDown = callback;
            return this;
        };

        SwipeEvent.prototype.handleTouchMove = function (evt) {
            if (!this.xDown || !this.yDown) {
                return;
            }
            var xUp = evt.touches[0].clientX;
            var yUp = evt.touches[0].clientY;
            this.xDiff = this.xDown - xUp;
            this.yDiff = this.yDown - yUp;

            if (Math.abs(this.xDiff) !== 0) {
                if (this.xDiff > 2) {
                    typeof (this.onLeft) === "function" && this.onLeft();
                } else if (this.xDiff < -2) {
                    typeof (this.onRight) === "function" && this.onRight();
                }
            }

            if (Math.abs(this.yDiff) !== 0) {
                if (this.yDiff > 2) {
                    typeof (this.onUp) === "function" && this.onUp();
                } else if (this.yDiff < -2) {
                    typeof (this.onDown) === "function" && this.onDown();
                }
            }
            // Reset values.
            this.xDown = null;
            this.yDown = null;
        };

        SwipeEvent.prototype.run = function () {
            this.element.addEventListener('touchmove', function (evt) {
                this.handleTouchMove(evt);
            }.bind(this), false);
        };

        return SwipeEvent;
    }());

    // all in one price block - smoothly scroll to div
    $(".stm_all_in_one_price_block .contact-btn").on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr("href")
        $('html, body').animate({
            scrollTop: $(id).offset().top
        }, 2000);
    });

    // EV dealer demo home footer contact scripts
    function ev_home_footer_contact() {
        // collapse the input
        if ($(window).width() > 991) {
            $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form input:not(.wpcf7-submit), .stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form textarea').css('height', '10px');
        } else {
            $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form input:not(.wpcf7-submit)').css('height', '43px');
            $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form textarea').css('height', '100px');
        }

        // release on focus
        $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form input:not(.wpcf7-submit)').on('focus', function () {
            $(this).css({'height': '43px'});
        });
        $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form textarea').on('focus', function () {
            $(this).css({'height': '100px'});
        });

        // return collapsed state if input is empty
        $('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form input:not(.wpcf7-submit), .stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form textarea').on('focusout', function () {
            let input_val = $(this).val();
            if (input_val.length === 0 && $(window).width() > 991) {
                $(this).css({'height': '10px'});
            }
        });
    }

    $(document).ready(function () {

        var shareTimer;
        var currentSelect;

        $(document).on('click', '.language-switcher-unit', function () {
            $(this).toggleClass('open');
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('body').on('click', '.c-l-test-drive', function () {
            var carName = $('.carName').text();
            $('.test-drive-car-name').text(carName);
            $('input[name="vehicle_name"]').val(carName);
            $('input[name="vehicle_id"]').val(0);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('img').trigger('appear');
        });

        if ($('body').hasClass('stm-boxed') && $('body').hasClass('stm-inventory-map-body')) {

            var width = parseInt($('#main').width()) + 30;

        }

        $('.stm-more').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');

            $(this).closest('.stm_single_class_car, .stm_rental_option').find('.more').slideToggle();
        });

        stm_stretch_image();

        if ($('.stm-simple-parallax').length) {
            $('.stm-simple-parallax').append('<div class="stm-simple-parallax-gradient"><div class="stm-simple-parallax-vertical"></div></div>');
            jQuery(window).on('scroll', function () {
                var currentScrollPos = $(window).scrollTop();
                var scrollOn = 400 - parseFloat(currentScrollPos / 1.2);
                if (scrollOn < -200) {
                    scrollOn = -200;
                }
                $('.stm-simple-parallax').css('background-position', '0 ' + scrollOn + 'px');
            });
        }

        if ($('.stm-single-car-page').length && !$('body').hasClass('stm-template-car_dealer_two')) {
            jQuery(window).on('scroll', function () {
                var currentScrollPos = $(window).scrollTop();
                var scrollOn = 200 - parseFloat(currentScrollPos / 1.2);
                if (scrollOn < -200) {
                    scrollOn = -200;
                }

                $('.stm-single-car-page').css('background-position', '0 ' + scrollOn + 'px');
            });
        }

        stm_footer_selection();
        stm_listing_mobile_functions();
        if ($('.listing-nontransparent-header').length > 0) {
            //$('#wrapper').css('padding-top', $('.listing-nontransparent-header').outerHeight() + 'px');
        }

        if ($('.stm-banner-image-filter').length > 0) {
            var top = $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top;

            if ($('body').hasClass('has_envato_iframe')) {
                top = top - 54;
                $('.stm-banner-image-filter').css('top', top + 'px');
            }
        }

        $(document).on('click', '.stm_motorcycle_pp', function () {
            $(this).toggleClass('active');
            $(this).find('ul').toggleClass('activated');
        });

        $('.stm-material-parallax').parallax();

        //Custom functions
        stm_widget_color_first_word();
        stm_widget_instagram();
        footerToBottom();
        stmFullwidthWithParallax();
        stmMobileMenu();

        stmShowListingIconFilter();

        $('body').on('click', '.stm-after-video', function (e) {
            var $this = $(this).closest('.stm-video-link-unit-wrap');
            stm_listing_add_video_input($this, 2);
        })

        var $current_video_count = 1;

        function stm_listing_add_video_input($video_unit, stm_restrict) {
            var hasEmptyCount = 0;
            var hasInputs = 0;
            $('.stm-video-units .stm-video-link-unit-wrap').each(function () {
                hasInputs++;
                var stmVal = $(this).find('input').val();
                if (stmVal.length !== 0) {
                    hasEmptyCount++;
                }
            });

            var $enable = (hasInputs - hasEmptyCount);

            if ($enable < stm_restrict || hasInputs == 1) {
                $current_video_count++;
                var $video_label = $video_unit.find('.video-label').text();

                var $new_item_string =
                    '<div class="stm-video-link-unit-wrap">' +
                    '<div class="heading-font">' +
                    '<span class="video-label">' + $video_label + '</span>' +
                    ' <span class="count">' + $current_video_count + '</span>' +
                    '</div> ' +
                    '<div class="stm-video-link-unit"> ' +
                    '<input type="text" name="stm_video[]"> ' +
                    '<div class="stm-after-video"></div> ' +
                    '</div> ' +
                    '</div>'

                var new_item = $($new_item_string).hide();
                $('.stm-video-units').append(new_item);
                new_item.slideDown('fast');
            }
        }

        function stmIsValidURL(str) {
            var a = document.createElement('a');
            a.href = str;
            return (a.host && a.host != window.location.host) ? true : false;
        }

        $('body').on('input', '.stm-video-link-unit input[type="text"]', function (e) {
            if ($(this).val().length > 0) {
                if (stmIsValidURL($(this).val())) {
                    $(this).closest('.stm-video-link-unit').find('.stm-after-video').addClass('active');
                    var $this = $(this).closest('.stm-video-link-unit-wrap');
                    stm_listing_add_video_input($this, 1);
                }
            } else {
                $(this).closest('.stm-video-link-unit').find('.stm-after-video').removeClass('active');
            }
        });


        if ($('.stm_automanager_features_list').length > 0) {
            $('.wpb_tabs_nav li').on('click', function () {
                var data_tab = $(this).attr('aria-controls');

                if ($('#' + data_tab).find('.stm_automanager_features_list').length > 0) {
                    $('.stm_automanager_features_list').isotope({
                        // main isotope options
                        itemSelector: '.stm_automanager_single'
                    })
                }
            });
        }

        disableFancyHandy();

        // Is on screen
        $.fn.is_on_screen = function () {
            var win = $(window);
            var viewport = {
                top: win.scrollTop(),
                left: win.scrollLeft()
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


        $('img.lazy').lazyload({
            effect: "fadeIn",
            failure_limit: Math.max('img'.length - 1, 0)
        });

        $('.owl-stage, .stm-single-image, .boat-gallery, .equip-gallery').lightGallery({
            selector: '.stm_fancybox',
            mode: 'lg-fade',
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

        $('#top-bar-address').lightGallery({
            selector: 'this',
            iframeMaxWidth: '80%',
            mode: 'lg-fade',
            download: false
        })

        $('.stm-carousel').lightGallery({
            selector: '.stm_fancybox',
            mode: 'lg-fade',
            download: false
        });

        $('a.stm_fancybox').lightGallery({
            selector: 'this',
            mode: 'lg-fade',
            download: false
        });

        $('#searchModal').on('shown.bs.modal', function (e) {
            $('#searchform .search-input').trigger('focus');
        });


        $('p').each(function () {
            if ($(this).html() == '') {
                $(this).addClass('stm-hidden');
            }
        });

        $('.calculated_shipping').on('DOMSubtreeModified', function (e) {
        });

        var pixelRatio = window.devicePixelRatio || 1;

        if (typeof pixelRatio != 'undefined' && pixelRatio > 1) {
            $('img').each(function () {
                var stm_retina_image = $(this).data('retina');

                if (typeof stm_retina_image != 'undefined') {
                    $(this).attr('src', stm_retina_image);
                }
            })
        }

        $('body').on('click', '.car-action-unit.add-to-compare.disabled, a.stm_fancybox', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })

        // Quantity actions
        $('body').on('click', '.quantity_actions span', function () {
            var quantityContainer = $(this).closest('.quantity'),
                quantityInput = quantityContainer.find('.qty'),
                quantityVal = quantityInput.attr('value');

            $('.button.update-cart').removeAttr('disabled');

            if ($(this).hasClass('plus')) {
                quantityInput.attr('value', parseInt(quantityVal) + 1);
            } else if ($(this).hasClass('minus')) {
                if (quantityVal > 1) {
                    quantityInput.attr('value', parseInt(quantityVal) - 1);
                }
            }
        });

        $('body').on('click', '.stm-modern-filter-unit-images .stm-single-unit-image', function () {

            var stmHasChecked = false;
            $('.stm-modern-filter-unit-images .stm-single-unit-image .image').addClass('non-active');
            $('.stm-modern-filter-unit-images .stm-single-unit-image').each(function () {
                var checked = $(this).find('input[type=checkbox]').prop('checked');
                if (checked) {
                    $(this).find('.image').removeClass('non-active');
                    stmHasChecked = true;
                }
            });

            if (!stmHasChecked) {
                $('.stm-modern-filter-unit-images .stm-single-unit-image .image').removeClass('non-active');
            }

        })

        $('.stm-modern-view-others').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.stm-single-unit-wrapper').find('.stm-modern-filter-others').slideToggle('fast');
        })

        $('.header-help-bar-trigger').on('click', function () {
            $(this).toggleClass('active');
            $('.header-help-bar ul').slideToggle();
        })

        $('.header-menu').on('click', function (e) {
            var link = $(this).attr('href');
            if (link == '#') {
                e.preventDefault();
            }
        })

        $('#main .widget_search form.search-form input[type=search]').on('focus', function () {
            $(this).closest('form').addClass('focus');
        });

        $('#main .widget_search form.search-form input[type=search]').on('focusout', function () {
            $(this).closest('form').removeClass('focus');
        });

        $('body').on('change', '.stm-file-realfield', function () {
            var length = $(this)[0].files.length;

            if (length == 1) {
                var uploadVal = $(this).val();
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text(uploadVal);
            } else if (length == 0) {
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text('Choose file...');
            } else if (length > 1) {
                $(this).closest('.stm-pseudo-file-input').find(".stm-filename").text(length + ' files chosen');
            }
        });

        $(".rev_slider_wrapper").each(function () {
            var $this = $(this);
            $this.on('revolution.slide.onloaded', function () {
                setTimeout(function () {
                    $('.stm-template-boats .wpb_revslider_element .button').addClass('loaded');
                }, 1000);
            });
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

        $('.unit-stm-moto-icon-share').on({
            mouseenter: function () {
                $(this).find('.stm-a2a-popup').addClass('stm-a2a-popup-active');
            },
            mouseleave: function () {
                $(this).find('.stm-a2a-popup').removeClass('stm-a2a-popup-active');
            }
        });

        $('.stm-gallery-action-unit').on({
            mouseenter: function () {
                if (shareTimer) {
                    clearTimeout(shareTimer);
                }
                $(this).find('.stm-a2a-popup').addClass('stm-a2a-popup-active');
            },
            mouseleave: function () {
                shareTimer = setTimeout(function () {
                    if ($('.stm-a2a-popup-active:hover').length == 0) {
                        $('.stm-a2a-popup').removeClass('stm-a2a-popup-active');
                    }
                }, 500);

                $('.stm-a2a-popup-active').mouseleave(function () {
                    $('.stm-a2a-popup').removeClass('stm-a2a-popup-active');
                });
            }
        });

        $('body').on('click', '.archive_request_price', function () {
            var title = $(this).data('title');
            var id = $(this).data('id');

            $('#get-car-price form').find('.test-drive-car-name').text(title);
            $('#get-car-price form').find('input[name="vehicle_id"]').val(id);
        });

        $('body').on(
            'click',
            '.listing-car-item-meta .price[data-target="#get-car-price"], .stm_moto_hover_unit .price [data-target="#get-car-price"], #listings-result .image .price [data-target="#get-car-price"]',
            function (e) {
                if ($(e.target).closest('a').length) {
                    e.preventDefault()
                }
            }
        )

        if ($('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').length > 0) {
            // on desktop, hover
            $(document).on('mousemove', '.interactive-hoverable .hoverable-wrap .hoverable-unit', function () {
                var index = $(this).index();
                if ($(this).parent().siblings('.hoverable-indicators').find('.indicator.active').index() !== index) {
                    $(this).parent().siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
                    $(this).parent().siblings('.hoverable-indicators').find('.indicator').eq(index).addClass('active');
                }

                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });

            $(document).on('mouseleave', '.interactive-hoverable', function () {
                $(this).find('.hoverable-indicators .indicator.active').removeClass('active');
                $(this).find('.hoverable-indicators .indicator:first-child').addClass('active');

                $(this).find('.hoverable-wrap .hoverable-unit.active').removeClass('active');
                $(this).find('.hoverable-wrap .hoverable-unit:first-child').addClass('active');
            });

            // on mobile, swipe
            stm_init_hoverable_swipe();
        }

        $('.mobile-contacts-trigger').on('click', function () {
            $(this).toggleClass('open');
            $('.header-top-info').toggleClass('open');

            if ($('.mobile-menu-trigger').hasClass('opened')) {
                $('html').trigger('removeNoScroll')
                $('.mobile-menu-trigger').removeClass('opened');
                $('.mobile-menu-holder').slideToggle();
            }

            if ($('.header-top-info').hasClass('open')) {
                $('#stm-overlay').show();
            } else {
                $('#stm-overlay').hide();
            }
        });

        // hoverable gallery preview using Brazzers Carousel library
        var brazzers_carousel = $('.brazzers-carousel');
        if (brazzers_carousel.length > 0) {
            brazzers_carousel.brazzersCarousel();

            brazzers_carousel.each(function () {
                // remaining number of photos
                if (typeof $(this).data('remaining') !== undefined && $(this).data('remaining') > 0) {
                    let remaining_number = parseInt($(this).data('remaining'));
                    var remaining_label = remaining_number + ' ' + photo_remaining_singular;
                    if (remaining_number > 1) {
                        var remaining_label = remaining_number + ' ' + photo_remaining_plural;
                    }

                    $(this).find('.tmb-wrap').append('<div class="remaining"><i class="stm-icon-album"></i><p>' + remaining_label + '</p></div>');
                }
            });

            $('.brazzers-carousel .tmb-wrap-table > div:nth-child(5)').on('mouseenter', function () {
                $(this).parent().siblings('.remaining').addClass('active');
            });

            $('.brazzers-carousel .tmb-wrap-table > div:nth-child(5)').on('mouseleave', function () {
                $(this).parent().siblings('.remaining').removeClass('active');
            });

            $('.brazzers-carousel .tmb-wrap').on('mouseleave', function () {
                $(this).find('.tmb-wrap-table > div').removeClass('active');
                $(this).find('.tmb-wrap-table > div:first-child').trigger('mouseenter');
            });
        }

        $(document).on('mouseenter', '.select2-selection__rendered', function () {
            $(this).attr('title', $.trim($(this).attr('title')));
        });

    }); // end of "document ready"

    function stm_init_hoverable_swipe() {
        if ($('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').length > 0) {
            $('.stm-hoverable-interactive-galleries .interactive-hoverable .hoverable-wrap').each((index, el) => {
                let galleryPreviewSwiper = new SwipeEvent(el);

                galleryPreviewSwiper.onRight(function () {
                    let active_index = $(this.element).find('.hoverable-unit.active').index();
                    $(this.element).find('.hoverable-unit').removeClass('active');
                    $(this.element).siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
                    if (active_index === 0) {
                        $(this.element).find('.hoverable-unit:last-child').addClass('active');
                        $(this.element).siblings('.hoverable-indicators').find('.indicator:last-child').addClass('active');
                    } else {
                        $(this.element).find('.hoverable-unit').eq(active_index - 1).addClass('active');
                        $(this.element).siblings('.hoverable-indicators').find('.indicator').eq(active_index - 1).addClass('active');
                    }
                });

                galleryPreviewSwiper.onLeft(function () {
                    let active_index = $(this.element).find('.hoverable-unit.active').index();
                    let total_items = $(this.element).find('.hoverable-unit');
                    $(this.element).find('.hoverable-unit').removeClass('active');
                    $(this.element).siblings('.hoverable-indicators').find('.indicator.active').removeClass('active');
                    if (active_index === parseInt(total_items.length - 1)) {
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

    $(window).on('load', function () {
        footerToBottom();
        stmFullwidthWithParallax();

        stm_stretch_image();
        $('.stm-blackout-overlay').addClass('stm-blackout-loaded');

        // if preloader_timer empty
        if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') {
            stmPreloader();
        }

        if ($('.stm-banner-image-filter').length > 0) {
            var top = $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top;

            if ($('body').hasClass('has_envato_iframe')) {
                top = top - 54;
                $('.stm-banner-image-filter').css('top', top + 'px');
            }
        }

        // ev dealer demo home footer contact
        if ($('.stm-template-ev_dealer .ev_footer_contact_block .wpcf7-form').length > 0) {
            ev_home_footer_contact();
        }

    }); // end of "window on load"

    $(window).on('resize', function () {
        footerToBottom();
        stmFullwidthWithParallax();

        stm_stretch_image();

        disableFancyHandy();
        if ($('.stm-banner-image-filter').length > 0) {
            var top = $('.stm-banner-image-filter').closest('.wpb_wrapper').offset().top;

            if ($('body').hasClass('has_envato_iframe')) {
                top = top - 54;
                $('.stm-banner-image-filter').css('top', top + 'px');
            }
        }

        // ev dealer demo home footer contact
        ev_home_footer_contact();

    });

    //if preloader timer not empty
    if (preloader_timer !== undefined || preloader_timer !== null || preloader_timer !== '') {
        setTimeout(function () {
            stmPreloader();
        }, preloader_timer);
    }

    function loadVisible($els, trigger) {
        $els.filter(function () {
            var rect = this.getBoundingClientRect();
            return rect.top >= 0 && rect.top <= window.innerHeight;
        }).trigger(trigger);
    }

    function footerToBottom() {
        var windowH = $(window).height();
        var footerH = $('#footer').outerHeight();
        $('#wrapper').css('min-height', (windowH - footerH) + 'px');
    };

    function stm_widget_color_first_word() {
        // don't apply for electric vehicle layout
        if ($('body').hasClass('stm-template-ev_dealer')) {
            return false;
        }

			// go ahead for other layouts
			$('.stm_wp_widget_text .widget-title h6').each(function () {
				var html = $(this).html()
				var spaceIndex = html.indexOf(' ')

				if (spaceIndex === -1) {
					$(this).html($('<span/>').html(html).addClass('colored'))
				} else {
					var word = html.substr(0, spaceIndex)
					var rest = html.substr(spaceIndex)
					$(this)
						.html(rest)
						.prepend($('<span/>').html(word).addClass('colored'))
				}
			})
		}

    function stm_widget_instagram() {
        $('#sb_instagram').closest('.widget-wrapper').addClass('stm-instagram-unit');
    }

    function stmFullwidthWithParallax() {
        var screenWidth = $(window).width();
        if (screenWidth < 1140) {
            var defaultWidth = screenWidth - 30;
        } else {
            var defaultWidth = 1140 - 30;
        }
        var marginLeft = (screenWidth - defaultWidth) / 2;

        if ($('body').hasClass('rtl')) {
            $('.stm-fullwidth-with-parallax').css({
                'position': 'relative',
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

        let mobile_menu_holder = $('.mobile-menu-trigger')

        mobile_menu_holder.on('click', function () {

            let $this = $(this)
            if ($this.hasClass('opened')) {
                $('html').trigger('removeNoScroll')
            } else {
                $('html').trigger('addNoScroll')
            }

            $this.toggleClass('opened');
            $('.mobile-menu-holder').slideToggle();


            // close the contacts dropdown
            if ($('.mobile-contacts-trigger').length > 0) {
                $('.mobile-contacts-trigger').removeClass('open');
                $('.header-top-info').removeClass('open');
            }

            if ($('.header-top-info').hasClass('open')) {
                $('#stm-overlay').show();
            } else {
                $('#stm-overlay').hide();
            }
        });


        $(".mobile-menu-holder .header-menu li.menu-item-has-children > a")
            .after('<span class="arrow"><i class="fas fa-angle-right"></i></span>');

        $(".magazine-menu-mobile > li.menu-item-has-children > a")
            .after('<span class="arrow"><i class="fas fa-angle-right"></i></span>');

        let arrow = $('.mobile-menu-holder .header-menu .arrow')
        arrow.parents('.stm_megamenu').find('> ul.sub-menu').hide();
        arrow.on('click', function () {

            $(this).toggleClass('active');

            $(this).closest('li').toggleClass('opened');

            if (!$(this).parent().hasClass('stm_megamenu')) {

                $(this).closest('li').find('> ul.sub-menu').slideToggle(300);

                // hide any existing subs
                if ($(this).closest('li').find('> ul.sub-menu').has('.sub-menu').length > 0) {
                    $(this).closest('li').find('> ul.sub-menu').find('ul.sub-menu').hide();
                    $(this).closest('li').find('> ul.sub-menu').find('.arrow').removeClass('active');
                }
            } else {
                // if contains megamenu
                $(this).closest('li').find('> ul.sub-menu').slideToggle(300);
            }
        });

        // if menu item with "#" link is clicked
        $(".mobile-menu-holder .header-menu > li.menu-item-has-children > a").on('click', function (e) {

            if ($(this).attr('href') == '#') {

                e.preventDefault();

                $(this).closest('li').find(' > ul.sub-menu').slideToggle(300);

                $(this).closest('li').toggleClass('opened');

                $(this).closest('li').find(' > .arrow').toggleClass('active');

                // hide any existing subs
                if ($(this).closest('li').find('> ul.sub-menu').has('.sub-menu').length > 0) {
                    $(this).closest('li').find('> ul.sub-menu').find('ul.sub-menu').hide();
                    $(this).closest('li').find('> ul.sub-menu').find('.arrow').removeClass('active');
                }
            }
        });

        $('body').on('click', '.magazine-menu-mobile > li.menu-item-has-children >.arrow', function (e) {
            $(this).parent().toggleClass('active');
        });

        let html = $('html');
        html.on('addNoScroll', addNoScroll)
        html.on('removeNoScroll', removeNoScroll);

    }

    function addNoScroll(el) {
        if (!el) {
            el = $('html')
        }

        if (typeof el === 'object' && el.hasOwnProperty('currentTarget')) {
            el = $(el.currentTarget)
        }

        el.addClass('no-scroll');
    }

    function removeNoScroll(el) {
        if (!el) {
            el = $('html')
        }

        if (typeof el === 'object' && el.hasOwnProperty('currentTarget')) {
            el = $(el.currentTarget)
        }

        el.removeClass('no-scroll');
    }

    let is_header = {
            car_magazine: false,
            dealer: false,
        },
        body = $('body')

    if (body.hasClass('stm-layout-header-car_magazine')) {
        is_header.car_magazine = true
    }

    if (body.hasClass('stm-layout-header-car_dealer')) {
        is_header.dealer = true
    }

    $(window).ready(setMobileMenuHeight);
    $(window).resize(setMobileMenuHeight);
    $(window).scroll(setMobileMenuHeight);

    function setMobileMenuHeight() {
        let header_main = $('#header > div.header-main, #header > div.header-listing'),
            mobile_menu_holder = $('.mobile-menu-holder')

        if (is_header.car_magazine) {
            header_main = $('#header > div.header-magazine')
            mobile_menu_holder = $('.stm-opened-menu-magazine')
        }

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

        if (is_header.dealer) {
            mobile_menu_holder.css('top', header_main_height)
        }
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

    function disableFancyHandy() {
        var winWidth = $(window).width();
        if (winWidth < 1025) {
            $('.media-carousel-item .stm_fancybox').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
        }
    }

    function stmPreloader() {
        if ($('html').hasClass('stm-site-preloader')) {
            $('html').addClass('stm-site-loaded');

            if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') { // if preloader timer is not set
                setTimeout(function () {
                    $('html').removeClass('stm-site-preloader stm-site-loaded');
                }, 250);
                var prevent = false;
                $('a[href^=mailto], a[href^=skype], a[href^=tel]').on('click', function (e) {
                    prevent = true;
                    $('html').removeClass('stm-site-preloader stm-after-hidden');
                });

                $(window).on('beforeunload', function (e, k) {
                    if (!prevent) {
                        $('html').addClass('stm-site-preloader stm-after-hidden');
                    } else {
                        prevent = false;
                    }
                });
            } else { // if preloader timer is set
                $('html').removeClass('stm-site-preloader stm-site-loaded');
            }
        }
    }

    function stmShowListingIconFilter() {
        $('.stm_icon_filter_label').on('click', function () {

            if (!$(this).hasClass('active')) {
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
        if (typeof stm_footer_terms !== 'undefined') {
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
                    if (typeof (taxonomy) != 'undefined' && typeof (slug) != 'undefined' && !enableSubmission) {
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

    $('.stm-form-1-end-unit input[type="text"]').on('blur', function () {
        if ($(this).val() == '') {
            $(this).removeClass('stm_has_value');
        } else {
            $(this).addClass('stm_has_value');
        }
    });

    function stm_listing_mobile_functions() {
        $('.listing-menu-mobile > li.menu-item-has-children > a').append('<span class="stm_frst_lvl_trigger"></span>');
        $('body').on('click', '.stm_frst_lvl_trigger', function (e) {
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').slideToggle();
            $(this).closest('li').toggleClass('show_submenu');
            $(this).closest('li').toggleClass('active');
            $(this).toggleClass('active');
        });

        $('.boats-menu-ipad > li.menu-item-has-children > a').addClass('has-child');
        $('body').on('click', 'a.has-child', function (e) {

            if ($(this).hasClass('active')) {
                $(this).parent().trigger('blur');
                $(this).toggleClass('active');
            } else {
                e.preventDefault();
                $(this).toggleClass('active');
            }
        });

        $('.boats-menu-ipad > li.menu-item-has-children > a.has-child').each(function () {
            $(this).parent().on('focusout', function () {
                $('.boats-menu-ipad > li.menu-item-has-children > a.has-child').removeClass('active');
            });
        });

        $('.lOffer-account-dropdown.stm-login-form-unregistered form input').on('focus', function () {

            $('.lOffer-account-unit').find('.lOffer-account').addClass('active');
            $('.lOffer-account-unit').find('.lOffer-account-dropdown.stm-login-form-unregistered').addClass('active');

        })

        $('.lOffer-account-dropdown.stm-login-form-unregistered form input').on('blur', function () {
            $('.lOffer-account-unit').find('.lOffer-account').removeClass('active');
            $('.lOffer-account-unit').find('.lOffer-account-dropdown.stm-login-form-unregistered').removeClass('active');
        })

        $('.stm-menu-trigger').on('click', function () {

            $('.lOffer-account').removeClass('active');
            $('.stm-user-mobile-info-wrapper').removeClass('active');

            $('.stm-opened-menu-listing').toggleClass('opened');
            $('.stm-opened-menu-magazine').toggleClass('opened');

            let $this = $(this);
            if ($this.hasClass('opened')) {
                $('html').trigger('removeNoScroll');
            } else {
                $('html').trigger('addNoScroll');
            }

            $this.toggleClass('opened');

        });

        $('.lOffer-account').on('click', function (e) {
            e.preventDefault();

            let $this = $(this),
                html = $('html');

            $('.stm-opened-menu-listing').removeClass('opened');
            $('.stm-opened-menu-magazine').removeClass('opened');
            html.trigger('removeNoScroll');
            $('.stm-menu-trigger').removeClass('opened');

            if (window.matchMedia("(max-width: 990px)").matches) {
                if ($this.hasClass('active')) {
                    html.trigger('removeNoScroll');
                } else {
                    html.trigger('addNoScroll');
                }
            }

            $(this).toggleClass('active');

            $(this).closest('.lOffer-account-unit').find('.stm-user-mobile-info-wrapper').toggleClass('active');
        });


        $('.stm-rent-lOffer-account').on('click', function (e) {
            e.preventDefault();

            $('.stm-opened-menu-listing').removeClass('opened');
            $('.stm-opened-menu-magazine').removeClass('opened');
            $('html').trigger('removeNoScroll');
            $('.stm-menu-trigger').removeClass('opened');

            $(this).toggleClass('active');

            $(this).closest('.stm-rent-lOffer-account-unit').find('.stm-user-mobile-info-wrapper').toggleClass('active');
        });

        $('body').on('click', function (e) {
            if ($(e.target).closest('#header').length === 0) {
                $('.lOffer-account').removeClass('active');
                $('.stm-user-mobile-info-wrapper').removeClass('active');
                $('.stm-login-form-unregistered').removeClass('active');
                $('.stm-opened-menu-listing').removeClass('opened');
                $('.stm-opened-menu-magazine').removeClass('opened');
                $('html').trigger('removeNoScroll');
                $('.stm-menu-trigger').removeClass('opened');
            }
        });


        /*Boats*/
        $('.stm-menu-boats-trigger').on('click', function () {
            let $this = $(this);
            if ($this.hasClass('opened')) {
                setTimeout(removeNoScroll)
            } else {
                setTimeout(addNoScroll)
            }
            $this.toggleClass('opened');
            $('.stm-boats-mobile-menu').toggleClass('opened');
        });

        $('.stm-boats-mobile-menu .listing-menu > li.menu-item-has-children > a').append('<span class="stm-boats-menu-first-lvl"></span>');

        $('body').on('click', '.stm-boats-menu-first-lvl', function (e) {
            e.preventDefault();
            $(this).closest('li').find('ul.sub-menu').toggle();
            $(this).parent().parent().toggleClass('active');
            $(this).toggleClass('active');
        });
    }

    function shortenText(element, maxLength) {
        element.each(function() {
            var text = $(this).text();

            if (text.length > maxLength) {
                $(this).text(text.slice(0, maxLength) + "...");
            }
        });
    }

    shortenText($('.lOffer-account-dropdown .name a'), 15);
    shortenText($('.stm-dealer-mail .mail a'), 27);

    var lazyTimeout;

    $('#stm-dealer-view-type').on('change', function () {
        var tabId = $(this).val();
        $('a[href=#' + tabId + ']').tab('show');
        $('.select-listing-type select').trigger('change');

        if (lazyTimeout) clearTimeout(lazyTimeout);
        setTimeout(function () {
            $("img.lazy").show().lazyload();
        }, 200);
    });

    $('.service-mobile-menu-trigger').on('click', function () {
        $('.header-service .header-menu').slideToggle();
        let $this = $(this);
        if ($this.hasClass('active')) {
            $('html').trigger('removeNoScroll');
        } else {
            $('html').trigger('addNoScroll');
        }
        $(this).toggleClass('active');
    });

    updateGridItemTitles();

})(jQuery);

function updateGridItemTitles() {
    var $ = jQuery;

    $('.car-title').each(function () {
        var $title = $(this);
        var maxChar = parseInt($title.attr('data-max-char'));
        var $labels = $title.find('.labels');

        if ($labels.length > 0 && ($(this).text().length > maxChar)) {
            var originalLabels = $labels.clone();
            $labels.remove();
            var originalText = $title.contents().filter(function () {
                return this.nodeType === 3;
            }).text().trim();

            if (originalText.length > maxChar) {
                var truncatedText = originalText.substr(0, maxChar) + '...';

                $title.html('').append(originalLabels).append(truncatedText);
            } else {
                $title.html(originalText).prepend($labels)
            }
        } else {
            if ($(this).attr('data-max-char') != 'undefined' && $(this).text().length > $(this).attr('data-max-char')) {
                $(this).text($(this).text().trim().substr(0, $(this).attr('data-max-char')) + '...');
            }
        }
    });
}

function stm_stretch_image() {
    var $ = jQuery;
    var position = '.stm-stretch-image-right';

    if ($(position).length) {
        var windowW = $(document).width();
        var containerW = $('.header-main .container').width();

        var marginW = (windowW - containerW) / 2;

        $(position + ' .vc_column-inner').css({
            'margin-right': '-' + marginW + 'px'
        });
    }

    position = '.stm-stretch-image-left';

    if ($(position).length) {
        var windowW = $(document).width();
        var containerW = $('.header-main .container').width();

        var marginW = (windowW - containerW) / 2;

        $(position + ' .vc_column-inner').css({
            'margin-left': '-' + marginW + 'px'
        });
    }
}

function stm_isotope_sort_function(currentChoice) {

    var $ = jQuery;
    var stm_choice = currentChoice;
    var $container = $('.stm-isotope-sorting');
    switch (stm_choice) {
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


var stmMotorsCaptcha = function () {
    jQuery('.g-recaptcha').each(function (index, el) {
        var $ = jQuery;
        grecaptcha.render(el, {'sitekey': $(el).data('sitekey')});
    });
};

function stm_check_mobile() {
    var isMobile = false; //initiate as false
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    return isMobile;
}

(function ($) {
    window.STMCascadingSelect = function STMCascadingSelect(container, relations) {
        let self = this;

        self.relations = relations;
        self.ctx = container;
        self.options = {
            selectBoxes: []
        };

        if (self.relations && Object.keys(self.relations).length) {
            let selectBoxes = [];

            $.each(self.relations, function (slug, options) {
                let selectBox = self.selectbox(slug, options);

                if (selectBox && typeof selectBox === 'object') {
                    selectBoxes.push(selectBox);
                }
            });

            if (selectBoxes.length) {
                self.options.selectBoxes = self.options.selectBoxes.concat(selectBoxes);
            }
        }

        $(container).cascadingDropdown(self.options);
    };

    STMCascadingSelect.prototype.selectbox = function (slug, config) {
        var parent = config.dependency;

        if (!$(this.selector(slug), this.ctx).length || (parent && !$(this.selector(parent), this.ctx).length)) {
            return null;
        }

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

})(jQuery);
