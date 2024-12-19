<?php if(have_posts()): ?>
    <div class="row stm_rental_archive_top">
        <div class="col-md-7 col-sm-7">
            <h2 class="title"><?php esc_html_e('Select vehicle/add-ons', 'motors'); ?></h2>
        </div>
        <div class="col-md-5 col-sm-5">
            <?php do_action('woocommerce_before_shop_loop'); ?>
        </div>
    </div>
    <div class="stm_notices">
        <?php wc_print_notices(); ?>
    </div>
    <?php while(have_posts()): ?>
        <?php the_post();
        get_template_part('partials/rental/main-shop/loop'); ?>
    <?php endwhile; ?>

    <script>
        (function($){
            $(document).ready(function(){
                var stmHash = window.location.hash;
                var headerOffset = 0;
                if($('.header-listing').hasClass('header-listing-fixed')) {
                    headerOffset = $('.header-listing').outerHeight();
                }
                if($(stmHash).length) {
                    $('html, body').animate({
                        scrollTop: $(stmHash).offset().top - headerOffset
                    }, 500);
                    $(stmHash).find('.stm-more').toggleClass('active');
                    $(stmHash).find('.more').slideToggle();
                }
            })
        })(jQuery);
    </script>
<?php else: ?>
    <h2>
        <?php echo esc_html__("There are no cars in this office", "motors"); ?>
    </h2>
<?php endif; ?>

<?php
echo paginate_links( array(
    'type'      => 'list',
    'prev_text' => '<i class="fas fa-angle-left"></i>',
    'next_text' => '<i class="fas fa-angle-right"></i>',
) );
?>
