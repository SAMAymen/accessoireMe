(function ($) {
    $(document).ready(function () {
        $('.comment-form-rating .stars a').on('click', function () {
            $('.comment-form-rating .stars a').removeClass('selected');
            var starClass = $(this).attr('class');

            var stars = ['star-1', 'star-2', 'star-3', 'star-4', 'star-5'];
            var stop = false;

            stars.forEach( function (star) {
                if(star != starClass && !stop) {
                    $('.comment-form-rating .stars a.' + star).addClass('selected');
                } else if( star == starClass ) {
                    stop = true;
                }
            });

        });
    });
})(jQuery);