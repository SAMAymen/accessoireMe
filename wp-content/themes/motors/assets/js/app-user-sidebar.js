(function($) {
    "use strict";

    $(window).on('load',function(){
        stm_scroll_user_sidebar();
    });

    $(window).on('scroll', function(){
        stm_scroll_user_sidebar();
    });


    function stm_scroll_user_sidebar() {
        if($(window).width() > 820) {
            var $stm_window = $(window);
            var $stm_sidebar = $('.stm-sticky-user-sidebar .stm-user-private-sidebar');
            var $stm_sidebar_holder = $('.stm-sticky-user-sidebar');
    
            if($stm_sidebar.outerHeight() < $stm_window.outerHeight() && $stm_sidebar.length) {
                // if( $stm_sidebar.length ) {
                var currentScrollPos = $(window).scrollTop();
                var sidebarPos = $stm_sidebar_holder.offset().top;
        
                if(currentScrollPos > sidebarPos) {
                    $stm_sidebar.addClass('side-fixed');
                } else {
                    $stm_sidebar.removeClass('side-fixed');
                }
            }
        }
    }
})(jQuery);