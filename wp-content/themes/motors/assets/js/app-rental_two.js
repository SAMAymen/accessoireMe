(function($) {
    $(document).ready(function () {
        $('.stm-show-rent-promo-info').on('click', function (e) {
            var popupId = $(this).attr('data-popup-id');
            $('#' + popupId).addClass('flex').show();
        });

        $('.stm-rental-ico-close').on('click', function () {
            var popupId = $(this).attr('data-close-id');
            $('#' + popupId).hide().removeClass('flex');
        });

        $('.show-discount-popup').on('click', function (e) {
            $('#stm-discount-by-days-popup').addClass('flex').show();
        });
    });
})(jQuery);