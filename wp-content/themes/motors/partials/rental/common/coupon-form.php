<div class="stm_rental_coupon">
    <form class="stm_checkout_coupon" method="post">

        <p class="form-row form-row-first">
            <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'motors' ); ?>" id="coupon_code" value="" />
        </p>

        <p class="form-row form-row-last">
            <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'motors' ); ?>" />
        </p>

        <div class="clear"></div>
    </form>
</div>

<script>
    (function($){
        $(document).ready(function(){
            var $coupon = $('.stm_checkout_coupon .input-text');
            $coupon.on('focus', function(){
                $('.stm_checkout_coupon').addClass('active');
            });

            $coupon.on('focusout', function(){
                $('.stm_checkout_coupon').removeClass('active');
            })
        });
    })(jQuery)
</script>