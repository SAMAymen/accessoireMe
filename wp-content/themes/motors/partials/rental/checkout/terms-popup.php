<?php
$terms_page = get_option( 'woocommerce_terms_page_id', false );

if ( ! empty( $terms_page ) ) :
	$page = get_post( $terms_page );
	if ( ! empty( $page ) ) {
		$content = apply_filters( 'the_content', $page->post_content );
	}
	if ( ! empty( $content ) ) :
		?>
		<div class="stm_terms_popup_dismiss"></div>
		<div class="stm_terms_popup">
			<i class="fas fa-times"></i>
			<?php echo wp_kses_post( $content ); ?>
		</div>
<?php // phpcs:disable ?>
		<script>
            (function ($) {
                "use strict";

                var stm_single_filter_link = false;
                var stmIsotope;
                var $container = $('.stm-isotope-sorting');

                $(document).ready(function () {
                    /*Terms and conditions rental*/
                    $(document).on("click", '.stm-template-car_rental .form-row.terms a', function (event) {
                        event.preventDefault();
                        $('.stm_terms_popup_dismiss, .stm_terms_popup').addClass('active');
                    });

                    $(document).on("click", '.stm_terms_popup_dismiss, .stm_terms_popup .fa-times', function (event) {
                        event.preventDefault();
                        $('.stm_terms_popup_dismiss, .stm_terms_popup').removeClass('active');
                    });
                })
            })(jQuery);
		</script>
		<?php // phpcs:enable ?>
	<?php endif; ?>
<?php endif; ?>
