(function ($) {
    $(document).ready(function () {
        $('select[name="filter_make"]').on('select2:select', function (e) {
            var parentId = $(this).val();
            if(parentId != '') getChild('make', parentId);
        });

        $('select[name="filter_model"]').on('select2:select', function (e) {
            var parentId = $(this).val();
            if(parentId != '') getChild('model', parentId);
        });

        function getChild(prodType, parentId) {

            $.ajax({
                url: window.wp_data.wcmap_ajax_url,
                type: "POST",
                dataType: 'json',
                data: '&catId=' + parentId + '&pType=' + prodType + '&action=stm_ajax_get_child_categories',
                beforeSend: function () {
                    if(prodType == 'make') {
                        $($('select[name="filter_model"]').data('select2').$container).addClass('opacity');
                    } else if(prodType == 'model') {
                        $($('select[name="filter_part-year"]').data('select2').$container).addClass('opacity');
                    }
                },
                success: function (msg) {
                    if(msg['opt']) {
                        if(prodType == 'make') {
                            $('select[name="filter_model"]').html(msg['opt']);
                            $('select[name="filter_model"]').select2().trigger('change');
                        } else if(prodType == 'model') {
                            $('select[name="filter_part-year"]').html(msg['opt']);
                            $('select[name="filter_part-year"]').select2().trigger('change');
                        }
                    }
                },
                afterSend: function () {
                    if(prodType == 'make') {
                        $($('select[name="filter_model"]').data('select2').$container).removeClass('opacity');
                    } else if(prodType == 'model') {
                        $($('select[name="filter_part-year"]').data('select2').$container).removeClass('opacity');
                    }
                },
            });
        }
    });
})(jQuery);