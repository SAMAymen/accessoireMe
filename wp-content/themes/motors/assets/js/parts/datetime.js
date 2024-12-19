"use strict";
(function ($) {
    $(document).ready(function () {
        $.datetimepicker.setLocale(currentLocale);

        $('.stm-date-timepicker').datetimepicker({minDate: 0, lang: stm_lang_code});

        $('.stm-years-datepicker').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            lang: stm_lang_code,
            closeOnDateSelect: true
        });
    });
})(jQuery);
