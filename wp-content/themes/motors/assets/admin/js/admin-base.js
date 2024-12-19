(function ($) {
    $(document).ready(function () {
        $('.motors_vl_theme_install_button').on('click', function (e) {
            e.preventDefault();

            var src = $(this).attr('href');

            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'plugin': src,
                    'action': 'mvl_theme_install_base',
                    'nonce': mvl_nonces.mvl_theme_install_base
                },
                beforeSend: function () {
                    $(this).text('Installing');
                },
                complete: function (data) {
                    $(this).removeClass('loading');

                    data = data.responseJSON;

                    if (typeof data !== 'undefined') {
                        window.location.href = data.url;
                    } else {
                        window.location.reload();
                    }
                },
                error: function () {
                    window.location.reload();
                }

            });
        });
    });
})(jQuery)