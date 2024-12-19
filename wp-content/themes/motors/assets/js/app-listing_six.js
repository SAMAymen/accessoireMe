(function ($) {
    $(document).ready(function () {

        $('.big-wrap').lightGallery({
            selector: '.stm-cf-big',
            mode: 'lg-fade',
            download: false
        })

        $('.widget_media_gallery').lightGallery({
            selector: 'a',
            download: false,
            mode: 'lg-fade',
        });

    });

    $(window).on('load',function () {
        stmPreloader();
    });

    function stmPreloader() {
        if($('html').hasClass('stm-site-preloader')){
            $('html').addClass('stm-site-loaded');

            setTimeout(function(){
                $('html').removeClass('stm-site-preloader stm-site-loaded');
            }, 250);

            var prevent = false;
            $('a[href^=mailto], a[href^=skype], a[href^=tel]').on('click', function(e) {
                prevent = true;
                $('html').removeClass('stm-site-preloader stm-after-hidden');
            });

            $(window).on('beforeunload', function(e, k){
                if(!prevent) {
                    $('html').addClass('stm-site-preloader stm-after-hidden');
                } else {
                    prevent = false;
                }
            });
        }
    }

})(jQuery)