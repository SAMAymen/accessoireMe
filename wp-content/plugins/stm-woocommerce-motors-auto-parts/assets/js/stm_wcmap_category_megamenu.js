var $ = jQuery;
    $(document).ready(function () {
        var wList = $('.stm-wcmap-category-mm-wrap').width();
        var cWidth = $('#main .container').width();


        $('.stm-wcmap-subcats-content').attr('style', 'width: ' + (cWidth - wList) + 'px;');

        $('body').on('touchstart', '.stm_wcmap_mm_has_children .icon-ap-arrow', function (e) {
            $(this).parent().toggleClass('opened');
            return false;
        });
    });