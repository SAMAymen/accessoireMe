(function ($) {
    "use strict";
    $.stmTrigger = $.stmTrigger || {};
    $.stmTrigger.wp_media = $.stmTrigger.wp_media || [];
    $.stmTrigger.media_new = function () {
        var $body = $("body");
        $body.on('click', '.stm_upload_icon', $.stmTrigger.media_new_activate);
    };
    $.stmTrigger.media_new_activate = function (event) {
        event.preventDefault();
        var clicked = $(this), options = clicked.data();
        options.input_target = $('#' + options.target);
        var file_frame = wp.media(
            {
                frame: options.frame,
                library: {type: options.type},
                button: {text: options.button},
                className: options['class']
            });
        file_frame.on('select update insert', function () {
            $.stmTrigger.media_new_insert(file_frame, options);
        });
        file_frame.open();
    };
    $.stmTrigger.media_new_insert = function (file_frame, options) {
        var state = file_frame.state(), selection = state.get('selection').first().toJSON();
        options.input_target.val(selection.id).trigger('change');
        $("body").trigger(options.trigger, [selection, options]);
    };
    $(document).ready(function () {
        $.stmTrigger.media_new();
        $("body").on('cei_upload_font', $.stmTrigger.icon_insert);
        $("body").on('click', '.cei_delete_font', $.stmTrigger.icon_remove);
    });
    $.stmTrigger.icon_insert = function (event, selection, options) {
        options.input_target.val("");
        var msg = $('#msg');
        if (selection.subtype !== 'zip') {
            $('.spinner').hide();
            msg.html("<div class='error'><p>Please upload a valid ZIP file.<br/>You can create the file on icomoon.io</p></div>");
            msg.show();
            setTimeout(function () {
                msg.slideUp();
            }, 5000);
            return;
        }
        $.ajax({
            type: "POST",
            url: cei_window.ajax_url,
            data: {
                action: 'cei_upload_font_archive',
                values: selection,
                nonce: cei_window.nonces.cei_upload_font_archive,
            },
            beforeSend: function () {
                console.log(cei_window)
                $('.spinner').css({
                    opacity: 0,
                    display: "block",
                    visibility: 'visible',
                    position: 'absolute',
                    top: '21px',
                    left: '345px'
                }).animate({opacity: 1});
            },
            success: function (response) {
                $('.spinner').hide();
                if (response.match(/stm_font_added/)) {
                    msg.html("<div class='updated'><p>Icons Font added successfully! Reloading the page... </p></div>");
                    msg.show();
                    setTimeout(function () {
                        msg.slideUp();
                        location.reload();
                    }, 5000);
                }
                else {
                    msg.html("<div class='error'><p>Couldn't add the Icons Font.<br/>Error: " + response + "</p></div>");
                    msg.show();
                    setTimeout(function () {
                        msg.slideUp();
                    }, 5000);
                }
                if (typeof console != 'undefined') console.log(response);
            }
        });
    };
    $.stmTrigger.icon_remove = function (event) {
        event.preventDefault();
        if ( confirm('Are you sure to delete this Icons Font?') ) {
            var button = $(this),
                delete_font = button.data('delete');
            var msg = $('#msg');
            $.ajax({
                type: "POST",
                url: cei_window.ajax_url,
                data: {
                    action: 'cei_remove_font_archive',
                    delete_font,
                    nonce: cei_window.nonces.cei_remove_font_archive,
                },
                beforeSend: function () {
                    $('.spinner').css({
                        opacity: 0,
                        display: "block",
                        visibility: 'visible',
                        position: 'absolute',
                        top: '21px',
                        left: '345px'
                    }).animate({opacity: 1});
                },
                error: function () {
                    $('.spinner').hide();
                    msg.html("<div class='error'><p>Couldn't remove the Icons Font.<br/>Please reload the page, then try again</p></div>");
                    msg.show();
                    setTimeout(function () {
                        msg.slideUp();
                    }, 5000);
                },
                success: function (response) {
                    $('.spinner').hide();
                    if (response.match(/stm_font_removed/)) {
                        msg.html("<div class='updated'><p>Icons Font deleted successfully! Reloading the page...</p></div>");
                        msg.show();
                        setTimeout(function () {
                            msg.slideUp();
                            location.reload();
                        }, 5000);
                    }
                    else {
                        msg.html("<p><div class='error'><p>Couldn't remove the Icons Font.<br/>Reloading the page...</p></div>");
                        msg.show();
                        setTimeout(function () {
                            msg.slideUp();
                            location.reload();
                        }, 5000);
                    }
                    if (typeof console != 'undefined') console.log(response);
                }
            });
        }
    }
})(jQuery);