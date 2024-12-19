(function ($) {
    $(document).ready(function () {

        if($('.stm-admin-ss-col-left').find('.stmadmin-icon-round-error').length > 0) {
            $('.system-status-tab-nav').addClass('has_error');
        }

        $('body').on('click', '.action_plugin', function (e){
            e.preventDefault();

            var action = $(this).data('action');
            var slug = $(this).data('slug');
            var filepath = $(this).data('filepath');
            var source = $(this).data('source');

            var btn = $(this);

            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'plugin_action': action,
                    'plugin_slug': slug,
                    'plugin_source': source,
                    'plugin_file_path': filepath,
                    'action': 'stm_actions_plugin',
                    'security': stm_ajax_dashboard.stm_actions_plugin
                },
                beforeSend: function () {
                    btn.addClass('waiting');
                },
                complete: function (data) {
                    var dt = data.responseJSON;

                    btn.removeClass('waiting');
                    if(typeof dt.btn_text != 'undefined') {
                        if(dt.hasOwnProperty('upgrade')) location.reload();

                        btn.removeClass(action).addClass(dt.btn_action).data('action', dt.btn_action).text(dt.btn_text);

                        if(typeof dt.version != 'undefined') {
                            btn.parent().parent().find('.curr-version').text('Version: ' + dt.version);
                            btn.parent().parent().find('.new-version').remove();
                        }

                    } else {
                        alert(dt.error);
                    }
                },
                error: function (data) {
                    var dt = data.responseJSON;

                    alert(dt.error);
                    btn.removeClass('waiting');
                }

            });
        });

        $('body').on('click', '#generate-dev-access-btn', function (e){
            e.preventDefault();

            $('.developer_access_popup_wrap').addClass('show');

            if(typeof $(this).attr('data-gda') == 'undefined') {
                $.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: {
                        'action': 'stm_generate_developer_access',
                        'security': stm_ajax_dashboard.stm_action_developer_access
                    },
                    beforeSend: function () {
                        $('.link-wrapper').addClass('preloader');
                    },
                    complete: function (data) {
                        var dt = data.responseJSON;

                        $('.link-wrapper').removeClass('preloader');

                        if(typeof dt.access_url != 'undefined') {
                            $('textarea.dev_access_link').text(dt.access_url);
                            $('#generate-dev-access-btn').attr('data-gda', 'created').find('h4').text(dt.btn_text);
                            $('#generate-dev-access-btn').find('span').text(dt.btn_desc);
                        }
                    },
                    error: function () {
                        $('.link-wrapper').removeClass('preloader');
                    }
                });
            }
        });

        $('body').on('click', '#revoke_developer_access', function (e){
            e.preventDefault();

            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'action': 'stm_revoke_developer_access',
                    'security': stm_ajax_dashboard.stm_action_developer_access
                },
                beforeSend: function () {
                    $('.link-wrapper').addClass('preloader');
                },
                complete: function (data) {
                    var dt = data.responseJSON;

                    $('.link-wrapper').removeClass('preloader');
                    $('textarea.dev_access_link').text('');
                    $('#generate-dev-access-btn').removeAttr('data-gda').find('h4').text(dt.btn_text);
                    $('#generate-dev-access-btn').find('span').text(dt.btn_desc);
                    $('.developer_access_popup_wrap').removeClass('show');
                },
                error: function () {
                    $('.link-wrapper').removeClass('preloader');
                }
            });
        });

        $('.close-gda').on('click', function () {
            $('.developer_access_popup_wrap').removeClass('show');
            $('#copy_developer_access_link').text('Copy Link');
        });

        $('body').on('click', '#copy_developer_access_link', function (e) {
            e.preventDefault();
            var devLink = $('.dev_access_link');

            devLink.select();
            document.execCommand("copy");

            $(this).text("Copied");
        });

        $iso = $('.plugins-grid');
        $iso.isotope({
            itemSelector: '.stmadmin-plugin-loop',
            layoutMode: 'fitRows',
            masonry: {
                columnWidth: '.stmadmin-plugin-loop'
            },
            filter: function() {
                return qsRegex ? $(this).text().match( qsRegex ) : true;
            }
        });

        $('.plugins_isotope_nav').on('click', function(e) {
            e.preventDefault();
            $('.plug-navigate ul li a').removeClass('active');
            $(this).addClass('active');
            $iso.isotope({filter: '.' + $(this).data('filter')});
            window.location.hash=$(this).data('filter');
        });

        var anchor = window.location.hash.replace('#', '');

        if($('.plug-navigate ul li a[data-filter="' + anchor + '"]').length > 0) {
            $('.plug-navigate ul li a[data-filter="' + anchor + '"]').addClass('active');
            $iso.isotope({filter: '.' + anchor});
        } else {
            $('.plug-navigate ul li:first-child a').addClass('active');
            $iso.isotope({filter: '.all'});
        }

        var qsRegex;
        var $quicksearch = $('#search_plugin').keyup( debounce( function() {
            qsRegex = new RegExp( $quicksearch.val(), 'gi' );
            $iso.isotope({filter: function() {
                    return qsRegex ? $(this).find('.plug-name').text().match( qsRegex ) : true;
                }});
        }, 200 ) );

        function debounce( fn, threshold ) {
            var timeout;
            threshold = threshold || 100;
            return function debounced() {
                clearTimeout( timeout );
                var args = arguments;
                var _this = this;
                function delayed() {
                    fn.apply( _this, args );
                }
                timeout = setTimeout( delayed, threshold );
            };
        }
    });
}) (jQuery)