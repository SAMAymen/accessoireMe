(function ($) {
    var element = '.vc_ui-panel-window .stm_datepicker_vc';
    $(element).datepicker({
        dateFormat: "m/d/y"
    });

    $(document).on("focus", element, function(){
        $(this).datepicker({
            dateFormat: "m/d/y"
        });
    });
})(jQuery);