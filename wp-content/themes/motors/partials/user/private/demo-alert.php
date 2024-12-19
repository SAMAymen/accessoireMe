<?php //phpcs:ignore
$show_alert = false;
$demo       = apply_filters( 'stm_site_demo_mode', false );

// mark as sold on user private inventory
if ( true === $demo && isset( $_GET['stm_mark_as_sold_car'] ) ) {
	$show_alert = true;
}

if ( $show_alert ) :
	?>
	<div class="dismissable-demo-alert stm-no-available-adds-overlay"></div>
	<div class="dismissable-demo-alert stm-no-available-adds">
		<h3>
			<?php esc_html_e( 'Demo mode active', 'motors' ); ?>
		</h3>
		<p>
			<?php esc_html_e( 'This action cannot be performed while the website is on demo mode. For more details, please contact the website administrator.', 'motors' ); ?>
		</p>
		<div class="clearfix">
			<center>
				<button id="dismiss-demo-alert" class="button">
					<?php esc_html_e( 'Dismiss', 'motors' ); ?>
				</button>
			</center>
		</div>
	</div>
	<?php //phpcs:disable ?>
	<script>
        (function ($) {
            $('#dismiss-demo-alert').on('click', function () {
                $('.dismissable-demo-alert').addClass('hidden');
            });
        })(jQuery);
	</script>
<?php
//phpcs:enable
endif;
