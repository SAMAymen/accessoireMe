(function ($) {
    $(document).ready(function () {
        $('.stm-wcmap-title-wrap').on('click', function () {
             $(this).parent().parent().find('.stm-wcmap-icon-filter').toggleClass('only_title');
        });
    });
})(jQuery);