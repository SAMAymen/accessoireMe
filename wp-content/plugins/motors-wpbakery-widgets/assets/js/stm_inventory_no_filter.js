(function($) {
    "use strict";

    $(document).ready(function () {
        var inventoryContainer = $('.stm-inventory-no-filter-wrap .stm-isotope-sorting');
        var paginaContainer = $('.stm-inventory-no-filter-wrap .stm_ajax_pagination');
        var perPage = inventoryContainer.attr('data-per-page');
        var timeOut = false;
        $('body').on('click', '.stm-inventory-no-filter-wrap .stm_ajax_pagination a.page-numbers', function (e) {
            e.preventDefault();

            var current = $('.stm-inventory-no-filter-wrap .stm_ajax_pagination .page-numbers.current').text();
            var page = $(this).text();

            if($(this).hasClass('next')) {
                page = parseInt(current) + 1;
            }

            if($(this).hasClass('prev')) {
                page = parseInt(current) - 1;
            }

            $.ajax({
                url: ajaxurl,
                type: 'GET',
                dataType: 'json',
                context: this,
                data: '&posts_per_page=' + perPage + '&paged=' + page + '&action=stm_ajax_inventory_no_filter&security=' + stm_security_nonce,
                beforeSend: function () {
                    inventoryContainer.addClass('loading');
                    paginaContainer.addClass('loading');
                },
                success: function (data) {
                    inventoryContainer.empty().html(data.content);
                    $('.stm-inventory-no-filter-wrap .stm_ajax_pagination').empty().html(data.pagina);
                    timeOut = setTimeout(function () {
                        $('img.lazy').lazyload({
                            effect: "fadeIn",
                            failure_limit: Math.max('img'.length - 1, 0)
                        });
                        $('img').trigger('appear');
                        inventoryContainer.removeClass('loading');
                        paginaContainer.removeClass('loading');
                    }, 300);
                }
            });
        });
    });
})(jQuery);