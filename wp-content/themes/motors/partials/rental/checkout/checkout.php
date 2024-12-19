<?php if ( ! apply_filters( 'stm_is_rental_one_elementor', false ) ) : ?>
<div class="row stm_rental_archive_top">
	<div class="col-md-7 col-sm-7">
		<h2 class="title"><?php the_title(); ?></h2>
	</div>
	<div class="col-md-5 col-sm-5">
		<?php get_template_part( 'partials/rental/common/coupon', 'form' ); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-5">
		<?php get_template_part( 'partials/rental/product/info' ); ?>
	</div>
	<div class="col-md-7">
		<div class="stm_custom_rental_checkout">
			<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
		</div>
	</div>
</div>

<script>
	(function($){

		$(window).on('load', function(){
			var fields = '.stm_woocommerce_checkout_billing .form-row input, ' +
				'.stm_woocommerce_checkout_billing .form-row select';

			$(fields).each(function(){
				if($(this).val() == '') {
					$(this).closest('.form-row').removeClass('woocommerce-validated');
				}
			});

			$(document).on('focusout', fields, function(){
				if($(this).val() == '') {
					$(this).closest('.form-row').removeClass('woocommerce-validated');
				}
			})
		});

	})(jQuery)
</script>

	<?php
	get_template_part( 'partials/rental/checkout/terms', 'popup' );
	?>
<?php endif; ?>
