(function ($) {
    $(document).ready(function (){

        $('.stm-option-label').on('click', function (){
            var check = $(this).find('input[type="checkbox"]');
            (!check.is(':checked')) ? $(this).removeClass('checked') : $(this).addClass('checked');
        });

        $('body').on('click', '.stm-inventory-load-more-btn', function (e) {
            e.preventDefault();

            var inventoryContainer = ($('#stm_view_type').val() == 'grid') ? $('#listings-result .stm-isotope-sorting .car-listing-modern-grid') : $('#listings-result .stm-isotope-sorting');
            var loadMoreBtn = $(this);
            var page = $(this).attr('data-page');
            var ppp = $(this).attr('data-ppp');
            var nav = $(this).attr('data-nav');
            var offset = $(this).attr('data-offset');

            var form = $('.classic-filter-row form').serialize();

            $.ajax({
                url: stm_motors_current_ajax_url,
                type: 'GET',
                dataType: 'json',
                context: this,
                data: form + '&paged=' + page + '&offset=' + offset + '&ajax_action=listings-result-load',
                beforeSend: function () {
                    inventoryContainer.addClass('loading');
                    loadMoreBtn.addClass('loading');
                    loadMoreBtn.attr('data-page', parseInt(page) + 1);
                },
                success: function (data) {
                    if(data.total != 0) {
                        inventoryContainer.append(data.html);
                        if(data.offset == 0 || typeof(data.offset) == 'undefined') {
                            loadMoreBtn.hide();
                        } else {
                            loadMoreBtn.attr('data-offset', data.offset);
                        }

                        var resCount = (typeof (data.result_count) != 'undefined') ? parseInt($('.ac-showing').text()) + parseInt(data.result_count) : data.total;

                        $('.ac-showing').text(resCount);
                        $('.ac-total').text(data.total);
                    } else {
                        loadMoreBtn.hide();
                    }

                    timeOut = setTimeout(function () {
                        $('img.lazy').lazyload({
                            effect: "fadeIn",
                            failure_limit: Math.max('img'.length - 1, 0)
                        });
                        $('img').trigger('appear');
                        inventoryContainer.removeClass('loading');
                        loadMoreBtn.removeClass('loading');
                    }, 300);
                },
                error: function () {
                    loadMoreBtn.hide();
                }
            });
        });
    })
})(jQuery)