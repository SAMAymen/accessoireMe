(function ($) {
    $(document).ready(function () {

        $('.big-wrap').lightGallery({
            selector: '.stm-cf-big',
            mode: 'lg-fade',
            download: false
        })

    });
    
    //if preloader timer not empty
    if (preloader_timer !== undefined || preloader_timer !== null || preloader_timer !== '') {
        setTimeout(function () {
            stmPreloader();
        }, preloader_timer);
    }
    
    $(window).on('load',function () {
        //if preloader timer empty
        if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') {
            stmPreloader();
        }
    });

    function stmPreloader() {
        if($('html').hasClass('stm-site-preloader')){
            $('html').addClass('stm-site-loaded');
            
            if (preloader_timer === undefined || preloader_timer === null || preloader_timer === '') { // if preloader timer is not set
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
            } else { // if preloader timer is set
                $('html').removeClass('stm-site-preloader stm-site-loaded');
            }
        }
    }
})(jQuery)