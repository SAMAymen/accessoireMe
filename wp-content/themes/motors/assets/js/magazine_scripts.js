/**
 * Created by Dima on 2/7/2018.
 */
(function ($) {
    "use strict";

    $(document).ready(function () {
        $('.post-content > .gallery').owlCarousel({
            items: 1,
            loop: true,
            margin: 10,
            nav: true,
            navElement: 'div',
        });

        $('body').on('click', '.features-show-all', function () {
            $(this).toggleClass('rotate_180');
            $('.features_hiden').toggleClass('features_show');
        });

        $('body').on('click', '.recent-show-all', function () {
            $(this).toggleClass('rotate_180');
            $('.recent_hide_categories').toggleClass('active');
        });

        $('.stm_listing_popular_makes').on({
            mouseenter: function () {
                $(".stm_listing_popular_makes a").addClass('opacity_07');
            },
            mouseleave: function () {
                $(".stm_listing_popular_makes a").removeClass('opacity_07');
            }
        });

        $(".stm_listing_popular_makes a").on({
            mouseenter: function () {
                $(this).addClass('opacity_1');
            },
            mouseleave: function () {
                $(this).removeClass('opacity_1');
            }
        });


        $(".apsc-icons-wrapper").on({
            mouseenter: function () {
                $(".apsc-icons-wrapper .apsc-each-profile a").addClass('opacity_07');
            },
            mouseleave: function () {
                $(".apsc-icons-wrapper .apsc-each-profile a").removeClass('opacity_07');
            }
        });

        $(".apsc-icons-wrapper .apsc-each-profile a").on({
            mouseenter: function () {
                $(this).addClass('opacity_1');
            },
            mouseleave: function () {
                $(this).removeClass('opacity_1');
            }
        });

        $('body').on('click', '.features-cat-list li', function () {
            $('.features-cat-list li').removeClass('active');
            $(this).addClass('active');
            $.ajax({
                url: ajaxurl,
                type: "GET",
                dataType: 'json',
                data: '&category=' + $(this).data('slug') + '&action=stm_ajax_sticky_posts_magazine&security=' + stm_security_nonce + $('#features_posts_wrap').data('action'),
                context: this,
                beforeSend: function (data) {
                    $('.features_posts_wrap').addClass('opacity_07');
                },
                success: function (data) {
                    $('.features_posts_wrap').html(data.html);
                    var w = $('.adsense-200-200').width();
                    $('.adsense-200-200').height(Math.floor(w));
                    $('.features_posts_wrap').removeClass('opacity_07');
                }
            });
        });

        $('body').on('click', '.recent-cat-list li, .recent_hide_categories li', function () {

            if ($('.recent_hide_categories').hasClass('active')) {
                $('.recent-show-all').toggleClass('rotate_180');
                $('.recent_hide_categories').toggleClass('active');
            }

            $('.recent-cat-list li, .recent_hide_categories li').removeClass('active');
            $(this).addClass('active');
            $.ajax({
                url: ajaxurl,
                type: "GET",
                dataType: 'json',
                data: '&category=' + $(this).data('slug') + $('#stm_widget_recent_news').data('action'),
                context: this,
                beforeSend: function (data) {
                    $('.recentNewsAnimate').addClass('opacity_07');
                },
                success: function (data) {
                    $('.recentNewsAnimate').html(data.html);
                    $('.recentNewsAnimate').removeClass('opacity_07');
                }
            });
        });

        $('body').on('click', '.event-loop', function () {
            var postId = $(this).data('id');
            $('.event-loop').removeClass('activeEvent');
            $(this).addClass('activeEvent');

            $.ajax({
                url: ajaxurl,
                type: "GET",
                dataType: 'json',
                data: '&action=stm_ajax_get_events&security=' + stm_security_nonce + '&post_id=' + postId,
                context: this,
                beforeSend: function (data) {
                    $('.event-content').addClass('opacity_07');
                },
                success: function (data) {
                    $('.event-content').html(data.html);
                    jQuery("[data-countdown]").each(function () {
                        var $this = jQuery(this), finalDate = $this.data('countdown');
                        $this.countdown(finalDate, function (event) {
                            $this.html(event.strftime("<span class='heading-font'>%D <small>" + countdownDay + "</small></span> "
                                + "<span class='heading-font'>%H <small>" + countdownHrs + "</small></span> "
                                + "<span class='heading-font'>%M <small>" + countdownMin + "</small></span> "
                                + "<span class='heading-font'>%S <small>" + countdownSec + "</small></span>"));
                        });
                    });
                    setTimeout(function () {
                        $('.event-content').removeClass('opacity_07');
                    }, 200);
                }
            });
        });

        $('.events-list .event-loop:first-child').addClass('activeEvent');

        $('.widget_media_gallery').lightGallery({
            selector: 'a',
            download: false,
            mode: 'lg-fade',
        });

    });

    $(window).on('load',function () {

        var fW = $('.features-big-wrap').height();
        $('.features_posts_wrap').attr('style', 'min-height: ' + fW + 'px;');

        var w = $('.adsense-200-200').width();
        $('.adsense-200-200').height(Math.floor(w));

        var owlRecent = $('.recent_videos_posts_wrap');

        owlRecent.owlCarousel({
            items: 2,
            loop: true,
            nav: false,
            dots: false,
            margin: 20,
            autoWidth: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1,
                    center: true
                },
                1024: {
                    items: 2,
                    center: false
                }
            }
        });

        $('.recent-owl-nav .next').on('click', function () {
            owlRecent.trigger('next.owl.carousel');
        });
        $('.recent-owl-nav .prev').on('click', function () {
            owlRecent.trigger('prev.owl.carousel', [300]);
        });

        $('.recent-videos-wrap-loop a').on('click', function (e) {
            e.preventDefault();

            var link = $(this).attr('href');

            $(this).lightGallery({
                dynamic: true,
                dynamicEl: [{
                        src: link
                    }],
                download: false,
                mode: 'lg-fade',

            });

            return false;

        });

        $('.widget_media_gallery').lightGallery({
            selector: 'a',
            download: false,
            mode: 'lg-fade',

        });
    });


})(jQuery);