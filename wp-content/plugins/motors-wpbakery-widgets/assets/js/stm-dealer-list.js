(function($) {
    $(window).on('load', function() {
        var options = $('.stm_dynamic_listing_dealer_filter_form').data('options');
        var selects = [];

        $('.stm-filter-tab-selects.dealer-filter .row').each(function () {
            $(this).find('select').each(function () {
                selects.push($(this).attr('name'));
            });

            new STMCascadingSelect(this, options);
        });

        selects.forEach(function (sel) {
            const urlParams = new URLSearchParams(window.location.search);
            const myParam = urlParams.get(sel);

            if (myParam != null) {
                $('.stm-filter-tab-selects.dealer-filter .row select[name=' + sel + ']').select2().val(myParam).trigger('change');
            }
        });
    });
})(jQuery)
