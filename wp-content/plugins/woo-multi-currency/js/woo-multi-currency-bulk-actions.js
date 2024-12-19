'use strict';

jQuery(document).ready(function () {
    var currencies = wmc_params.currencies;
    if (currencies.length > 0) {
        jQuery.each(currencies, function (index, currency) {
            var do_variation_action = 'wbs_regular_price-' + currency;
            jQuery('select.variation_actions').on(do_variation_action, function () {
                var value = window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value);
                if (value != null) {
                    jQuery('.wbs-variable-regular-price-' + currency).val(value).change();
                }
            });
        });
        jQuery.each(currencies, function (index, currency) {
            var do_variation_action = 'wbs_sale_price-' + currency;
            jQuery('select.variation_actions').on(do_variation_action, function () {
                var value = window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value);
                if (value != null) {
                    jQuery('.wbs-variable-sale-price-' + currency).val(value).change();
                }
            });
        });
    }
});