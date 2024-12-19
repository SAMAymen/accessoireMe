(function ($) {
    $(document).ready(function() {
        $('.stm-vmc-action-btn').on('click', function() {
            var btnParent = $(this).parent();
            var postId = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var email = $(this).attr('data-email');
            var carTitle = $(this).attr('data-title');
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: 'post_id=' + postId + '&vmc-car=' + carTitle + '&vmc-email=' + email + '&status=' + status + '&action=stm_ajax_set_vmc_status&security=' + setVMCStatus,
                success: function(data) {
                    if(status == 'declined') {
                        btnParent.parent().hide();
                    }

                    btnParent.addClass('hide-btn');
                    btnParent.parent().removeClass().addClass(data);
                }
            });
        });

        $('.vmc-modal-overlay').on('click', function() {
            $('.vmc-modal-wrap').hide();
            $('.vmc-send-btn').val('SEND');
        });

        $('.stm-vmc-reply-btn').on('click', function() {

            var postId = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var email = $(this).attr('data-email');
            var carTitle = $(this).attr('data-title');

            $('input[name="vmc-car"]').val(carTitle);
            $('input[name="vmc-email"]').val(email);
            $('input[name="vmc-postid"]').val(postId);
            $('input[name="vmc-status"]').val(status);

            $('.vmc-modal-wrap').show();
        });

        $('.vmc-send-btn').on('click', function (e){
            e.preventDefault();

            var data = $('form[name="vmc-reply-form"]').serialize();

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data + '&action=stm_ajax_send_vmc_reply&security=' + sendVMCReply,
                dataType: 'json',
                beforeSend: function () {
                    $('.vmc-send-btn').attr('style', 'opacity: 0.3;');
                },
                success: function(data) {
                    $('.vmc-send-btn').removeAttr('style').val(data.message);
                }
            });
        });

        $('.vmc-lghtbox').on('click', function() {
            var currSlide = $(this).attr('data-curr-slide');
            var modalId   = $(this).attr('data-modal-id');
            openModal(modalId);
            currentSlide(currSlide);
        });

        $('.prev').on('click', function() {
            plusSlides(-1);
        });

        $('.next').on('click', function() {
            plusSlides(1);
        });

        $('.close.cursor').on('click', function() {
            $(this).closest('.modal').hide().removeClass('open');
        });

        function openModal(id = 'myModal') {
            let modal =  document.getElementById(id)
            modal.style.display = "block";
            modal.classList.add('open');
        }

        function closeModal(id = 'myModal') {
            let modal =  document.getElementById(id);
            modal.style.display = "none";
            modal.classList.remove('open');
        }

        var slideIndex =   0;

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.querySelectorAll('.modal.open .mySlides');
            if ((n+1) >= slides.length) {
                $('.next').css('display', 'none')
            } else {
                $('.next').css('display', 'block');
            }

            if(n == 0) {
                $('.prev').css('display', 'none');
            } else {
                $('.prev').css('display', 'block');
            }

            for (i = 0; i < slides.length; i++) {
                if(i == slideIndex) {
                    slides[i].style.display = "block" ;
                    slideIndex = i;
                } else {
                    slides[i].style.display = "none";
                }
            }
        }
    });
})(jQuery);
