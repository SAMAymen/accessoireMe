'use strict';
jQuery(document).ready(function ($) {
    let currency = woocommerce_multi_currency_admin_reports.currency;
    if (currency) {
        let $export = $('a.export_csv');
        if ($export.length > 0) {
            let download = $export.attr('download');
            if (download) {
                $export.attr('download', download.replace('.csv', '-' + currency + '.csv'));
            }
        }
    }
    $('.subsubsub').append($('.wmc-reports-currency-selector')).append($('.wmc-view-default-currency-container'));
    $('#wmc-view-default-currency').on('click', function () {
        let report_link = $(this).data('report_link');
        if (report_link) {
            window.location = report_link;
        }
    })
});
