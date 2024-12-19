jQuery(document).ready(function ($) {
    'use strict';
    /*Fix custom symbol of price range in WooCommerce price filter widget*/
    $(document.body).on('price_slider_updated', function (event, min, max) {
        if (woocommerce_price_slider_params.currency_format_symbol.indexOf('#PRICE#') > -1) {
            $('.price_slider_amount span.from').html(accounting.formatMoney(min, {
                symbol: woocommerce_price_slider_params.currency_format_symbol.replace('#PRICE#', ''),
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            }));

            $('.price_slider_amount span.to').html(accounting.formatMoney(max, {
                symbol: woocommerce_price_slider_params.currency_format_symbol.replace('#PRICE#', ''),
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            }));
        }
    })
});