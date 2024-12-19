(function ($) {
    $(document).ready(function () {
        $('.remove td a').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var data = {
                action: yith_woocompare.actionremove,
                id: $(this).data('product_id'),
                context: 'frontend'
            };

            var tdCurrent = parseInt( $('.remove td').index($(this).parent()) )  ;
            tdCurrent = parseInt(tdCurrent + 2);

            if( typeof $.fn.block != 'undefined' ) {
                $(this).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff url(' + yith_woocompare.loader + ') no-repeat center',
                        backgroundSize: '16px 16px',
                        opacity: 0.6
                    }
                });
            }

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl.toString().replace( '%%endpoint%%', yith_woocompare.actionremove ),
                data: data,
                dataType:'html',
                success: function(){
                    $('.stm-wcmap-compare .compare-list tbody tr').each(function () {
                        $(this).find('td:nth-child(' + tdCurrent + ')').remove();
                    });
                }
            });
        });
    });
})(jQuery);