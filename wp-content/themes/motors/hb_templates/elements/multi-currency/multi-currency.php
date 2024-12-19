<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php
if ( class_exists( 'WooCommerce' ) && class_exists( 'WOOMULTI_CURRENCY_F' ) ) {
	motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2' ) );

	$wooSettings = new WOOMULTI_CURRENCY_F_Data();

	$currency_list    = $wooSettings->get_currencies();
	$current_currency = $wooSettings->get_current_currency();
	$links            = $wooSettings->get_links();
	?>

	<?php if ( count( $currency_list ) > 1 ) : ?>
		<div class="stm-multi-currency">
			<div class="stm-multi-currency__info">
				<div class="stm-multi-curr__text stm-multi-curr__text_nomargin">
					<?php echo wp_kses( ( ! empty( $element['data']['title'] ) ) ? $element['data']['title'] : esc_html__( 'Currency: ', 'motors' ), array( 'br' => array() ) ); ?>
				</div>
			</div>
			<div class="stm-multicurr-select">
				<select id="stm-multi-curr-select">
					<?php
					foreach ( $currency_list as $k => $item ) {
						$selected = ( $current_currency === $item ) ? 'selected' : '';
						$stm_link = ( $current_currency !== $item ) ? $links[ $item ] : '';
						echo '<option ' . esc_attr( $selected ) . ' value="' . esc_attr( $stm_link ) . '">' . esc_html( $item ) . ' (' . esc_html( get_woocommerce_currency_symbol( $item ) ) . ')</option>';
					}
					?>
				</select>
			</div>
		</div>
		<?php // @codingStandardsIgnoreStart ?>
		<script>
            (function ($) {
                $(document).ready(function () {
                    $('#stm-multi-curr-select').on('change', function () {
                        window.location = $(this).val();
                    });
                });
            })(jQuery);
		</script>
		<?php // @codingStandardsIgnoreEnd ?>
	<?php endif; ?>
<?php } else {
	echo esc_html__( 'Please, install WooCommerce & Multi Currency for WooCommerce', 'motors' );
} ?>
