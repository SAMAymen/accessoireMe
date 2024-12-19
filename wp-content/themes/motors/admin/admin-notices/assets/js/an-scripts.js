(function($) {
    "use strict";
    $(document).ready(function () {
        $('body').on('click', '.skip_pear_hb', function (e) {
            e.preventDefault();
            add_pear_hb_added_set_option('skip');
        });
    });

    function add_pear_hb_added_set_option(status) {
        $('.pear_hb_message').attr('style', 'display: none !important;');
        $.ajax({
            url: ajaxurl,
            type: "GET",
            data: 'add_pear_hb_status=' + status + '&action=stm_ajax_add_pear_hb&security=' + stm_ajax_add_pear_hb,
            success: function (data) {}
        });
    }

    $(document).on('click', '[data-type="discard"]', function (e) {
        if($(this).attr('data-key') != 'starter_theme') {
            e.preventDefault();
        }

        let $this = $(this);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_discard_admin_notice',
                pluginName: $(this).attr('data-key'),
            },
            success: function () {
                $this.closest('.stm-notice').fadeOut(10).remove();
            }
        });
    });

    function track_notices_clicks( notice_id, clickAndViewData ) {
        if ( typeof notice_id !== 'undefined' ) {
            let token = btoa(new Date().toISOString().slice(0, 10));
            let api_url = `${stmNotices.api_fetch_url}/wp-json/custom/v1/notice-click`;
            fetch( api_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'notice_id': notice_id,
                    'token':     token,
                    'data':      clickAndViewData,
                })
            })
              .then(response => response.json())
              .then(data => {
                  console.log(data.message);
              })
              .catch(error => {
                  console.error('error request:', error);
              });
        }
    }
    $(window).load(function() {
        $('.stm-notice-notice').each(function() {
            let dataId = $(this).data('id');
            let statusViews = $(this).data('status-views');
            if(statusViews !== 'viewed') {
                track_notices_clicks(dataId, 'views')
            }
        });

        $('.popup-dash-promo').each(function() {
            let dataId = $(this).data('id');
            let statusViews = $(this).data('status-views');
            if(statusViews !== 'viewed') {
                track_notices_clicks(dataId, 'views')
            }
        });
    })

    $(document).on('click', '.popup-dash-close', function () {
        let notice_id    = $('.not-show-again').attr('data-id');
        let status_click = $('.popup-dash-promo').attr('data-status-click');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_notice_status',
                notice_id: notice_id,
                notice_status: 'close',
                nonce: stmNotices.nonce,
            },
            success: function () {
                $('.popup-dash-promo').removeClass('show')
                $('body').removeClass('body-scroll-off');
            }
        });

        if (status_click !== 'clicked') {
            track_notices_clicks( notice_id, 'clicks' )
        }

    })

    $(document).on('click', '.stm-notice .notice-dismiss', function () {
        let $notice = $(this).closest('.stm-notice');
        let notice_id = $notice.attr('data-id');
        let status_click = $notice.attr('data-status-click');
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_notice_status',
                notice_id: notice_id,
                notice_status: 'close',
                nonce: stmNotices.nonce,
            },
            success: function () {
                console.log('success');
            }
        });
        if (status_click !== 'clicked') {
            track_notices_clicks(notice_id, 'clicks');
        }
    });

    $(document).on('click', '.not-show-again, .stm-dash-btn', function (e) {
        let $this        = $(this);
        let notice_id    = $('.not-show-again').attr('data-id');
        let status_click = $('.popup-dash-promo').attr('data-status-click');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_notice_status',
                notice_id: notice_id,
                notice_status: 'not-show-again',
                nonce: stmNotices.nonce,
            },
            success: function (response) {
                $this.closest('.stm-notice').fadeOut(10).remove();
                $('.popup-dash-promo').removeClass('show');
                $('body').removeClass('body-scroll-off');
            }
        });
        if (status_click !== 'clicked') {
            track_notices_clicks( notice_id, 'clicks' )
        }
    })

    $(document).on('click','.notice-show-again', function (e) {
        let $this        = $(this);
        let notice_id    = $($this).attr('data-id');
        let status_click = $($this).attr('data-status-click');

        e.preventDefault();
        if (notice_id.length > 0) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'stm_notice_status',
                    notice_id: notice_id,
                    notice_status: 'not-show-again',
                    nonce: stmNotices.nonce,
                },
                success: function (response) {
                    $this.closest('.stm-notice').fadeOut(10).remove();
                    $('.popup-dash-promo').removeClass('show');
                    $('body').removeClass('body-scroll-off');
                }
            });
            if (status_click !== 'clicked') {
                track_notices_clicks( notice_id, 'clicks' )
            }
        }
    });

    $(window).load(function() {
        if ($('.popup-dash-promo.show').length > 0) {
            let userScrollPosition = $(window).scrollTop();

            $('.popup-dash-promo').css('top', userScrollPosition);
            $('body').addClass('body-scroll-off');
        }

        if ($('.popup-dash-promo.show').length > 0) {
            $(document).on('click', '.popup-dash-promo', function (e) {
                if (!$(e.target).closest('.popup-dash-promo-content').length) {
                    let notice_id = $('.not-show-again').attr('data-id');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'stm_notice_status',
                            notice_id: notice_id,
                            notice_status: 'close',
                            nonce: stmNotices.nonce,
                        },
                        success: function (response) {
                            $('.popup-dash-promo').removeClass('show');
                            $('body').removeClass('body-scroll-off');
                        }
                    });
                }
            });
        }
    })

    function checkTextLength() {
        let element = $('.popup-dash-desc');
        let text = element.text().split(' ').join('');
        if (text.length > 260) {
            element.addClass('scroll');
        }
    }
    checkTextLength();

})(jQuery);
