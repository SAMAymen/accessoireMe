jQuery(document).ready(function ($) {
    "use strict";

    wp.customize.notifications.add(
        'stm-wpcfto-notification',
        new wp.customize.Notification(
            'stm-wpcfto-notification', {
                dismissible: false,
                message: customize_data.message,
                type: 'error'
            }
        )
    );
});