(function ($) {
    'use strict';
    $(document).ready(function () {

        $('.rate-skip-btn').on('click', function (e) {
            let type = $(this).attr('data-type');
            let key_btn = $(this).attr('data-key');

            if(type != 'sure') {
                e.preventDefault();
            }

            let pluginName = $(this).parent().find("input[name='plugin-name']").val();
            let pluginEvent = $(this).parent().find("input[name='plugin-event']").val();

            if (type && !$(this).prop('disabled')) {
                $(this).prop('disabled', true);
                let $this = $(this);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stm_ajax_admin_notice',
                        type,
                        pluginName,
                        pluginEvent,
                        key_btn
                    },
                    success: function () {
                        $this.closest('.anp-item-rating-wrap').fadeOut(10).remove();
                        checkIfEmpty();
                    },
                    complete: function () {
                        $(this).prop('disabled', false);
                    }
                });
            }
        });

        $('.starter-skip-btn').on('click', function (e) {
            if($(this).attr('data-key') != 'starter_theme') {
                e.preventDefault();
            }

            let $this = $(this),
                pluginName = 'starter_theme',
                pluginEvent = '',
                type = $(this).attr('data-type'),
                key_btn = $(this).attr('data-key');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'stm_ajax_admin_notice',
                    type,
                    pluginName,
                    pluginEvent,
                    key_btn
                },
                success: function () {
                    $this.closest('.anp-item-theme-update-wrap').fadeOut(10).remove();
                    checkIfEmpty();
                },
                complete: function () {
                    $(this).prop('disabled', false);
                }
            });

        });

        $('body').on('mouseenter', '#wp-admin-bar-admin-notification', function(e) {

            var wrapNotify = $(this);

            if( $(this).find('#wp-admin-bar-admin-notification-popup').hasClass('hover') ) {

                wrapNotify.find('.ab-icon').removeClass('has_new');

                $('.anp-items-wrap .anp-item-base.new').each(function() {
                    $(this).removeClass('new');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'stm_anp_notice_viewed',
                            item_key: $(this).data('notify'),
                            security: anp_script_object.anp_nonce
                        },
                        success: function () {
                            console.log('success');
                        }
                    });
                });
            }
        });

        $('body').on('click', '.add_review', function (e) {
            e.preventDefault();
            review_added_set_option( $(this), 'added' );
            var win = window.open($(this).attr('href'), '_blank');
            win.focus();
        });

        $('body').on('click', '.skip_review', function (e) {
            e.preventDefault();
            review_added_set_option( $(this), 'skip' );
        });

        function review_added_set_option($this, status) {
            $.ajax({
                url: ajaxurl,
                type: "GET",
                data: 'add_review_status=' + status + '&action=stm_ajax_add_review&security=' + stm_ajax_add_review,
                success: function (data) {
                    $this.closest('.anp-item-theme-rate-wrap').fadeOut(10).remove();
                    checkIfEmpty();
                }
            });
        }

        function checkIfEmpty() {
            if( $(".anp-items-wrap").find('.anp-item-base').length == 0 ) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stm_anp_notice_empty',
                        security: anp_script_object.anp_nonce
                    },
                    success: function (e) {
                        if(e.hasOwnProperty('html')) {
                            $(".anp-items-wrap").html(e.html);
                        }
                    }
                });
            }
        }

        function track_notices_clicks( notice_id, clickAndViewData ) {
            if ( typeof notice_id !== 'undefined' ) {
                let token = btoa(new Date().toISOString().slice(0, 10));
                let api_url = `${anp_script_object.api_fetch_url}/wp-json/custom/v1/notice-click`;
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
            $('.anp-item-base').each(function() {
                let dataId = $(this).data('notice-id');
                let statusViews = $(this).data('status-views');
                if(statusViews !== 'viewed') {
                    track_notices_clicks(dataId, 'views')
                }
            });

            $('.skip-notice').on('click', function () {
               let notice_id = $(this).attr('data-id');

                let $notice      = $(this).closest('.anp-item-base');
                let status_click = $notice.attr('data-status-click');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stm_notification_status',
                        notice_id: notice_id,
                        notice_status: 'close',
                        security: anp_script_object.anp_nonce
                    },
                    success: function () {
                        $notice.fadeOut(10).remove();
                    }
                });

               if( status_click !== 'clicked' ) {
                   track_notices_clicks( notice_id, 'clicks' )
               }
            })

            $('.anp-action-btn').on('click', function () {
                let notice_id = $(this).attr('data-id');

                let $notice      = $(this).closest('.anp-item-base');
                let status_click = $notice.attr('data-status-click');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stm_notification_status',
                        notice_id: notice_id,
                        notice_status: 'not-show-again',
                        security: anp_script_object.anp_nonce
                    },
                    success: function () {
                        $notice.fadeOut(10).remove();
                    }
                });

                if( status_click !== 'clicked' ) {
                    track_notices_clicks( notice_id, 'clicks' )
                }
            })
        })
    })
})(jQuery);
