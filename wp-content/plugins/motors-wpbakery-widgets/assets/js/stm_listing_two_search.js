(function($) {
    "use strict";

    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            /*$('#stm_first_tab').removeClass('expanded');
            $('#stm_second_tab').removeClass('expanded');
            $('#stm_third_tab').removeClass('expanded');*/
        });
        filterToggle();
    });

    function filterToggle() {
        $('.stm-more-options-wrap').on('click', function(){
            var tab = $(this).attr('data-tab');
            $('#stm_' + tab + '_tab').toggleClass('expanded');
            $('.stm-filter-tab-selects-' + tab + ' .stm-slide-content').toggleClass('expanded');

            if($(this).hasClass('expand')) {
                $(this).removeClass('expand');
                $(this).find('span .fas').removeClass('fa-angle-up').addClass('fa-angle-down');
            } else {
                $(this).addClass('expand');
                $(this).find('span .fas').removeClass('fa-angle-down').addClass('fa-angle-up');
            }

            $('.stm-filter-tab-selects-' + tab + ' .stm-slide-content .stm-select-col').slideToggle();
        });
    }
})(jQuery);