!function ($) {
    var element = '.vc_ui-panel-window .stm_timepicker_vc';
    $(element).timepicker({});

    $(document).on("focus", element, function(){
        $(this).timepicker({});
    });
}(window.jQuery);