<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $vin_button_bg_color ) ) {
	$vin_button_bg_color = '';
}

if ( empty( $vin_button_text_color ) ) {
	$vin_button_text_color = '#fff';
}

if ( empty( $vin_button_text_hover_color ) ) {
	$vin_button_text_hover_color = '';
}

if ( empty( $vin_button_bg_hover_color ) ) {
	$vin_button_bg_hover_color = '';
}

?>

<style>
	body .stm_vehicle_vin_check .vinshortcodecont #stm_motors_vin_decoder #checkVin {
		background-color:<?php echo esc_attr( $vin_button_bg_color ); ?> !important;
		color:<?php echo esc_attr( $vin_button_text_color ); ?>!important;
		border-color:<?php echo esc_attr( $vin_button_bg_color ); ?>!important;
		transition: linear 0.1s !important;
		transform:none;
	}

	body  .stm_vehicle_vin_check .vinshortcodecont  #stm_motors_vin_decoder #checkVin:hover,
	.theme-motors  .stm_vehicle_vin_check .vinshortcodecont #stm_motors_vin_decoder button#checkVin:before{
		background-color:<?php echo esc_attr( $vin_button_bg_hover_color ); ?> !important;
		color:<?php echo esc_attr( $vin_button_text_hover_color ); ?>!important;
		border-color:<?php echo esc_attr( $vin_button_bg_hover_color ); ?>!important;
		transition:linear  0.1s !important;
		transform:none;
	}
</style>

<div class="stm_vehicle_vin_check">
	<?php
	echo do_shortcode( '[stm_motors_vin_decoders listing_vin=34]' );
	?>

</div>

<script>
	(function($) {
		$( document ).ready(function() {
			document.getElementById("checkVin").addEventListener('click',function ()
			{
				$('.wpb_single_image').css('display','none');

				$('#stm_motors_vin_decoder').css('justify-content','left');

				$('#stm_motors_vin_decoder').css('display','flex !important');

				$('.sample_vin').css('left','0px');

				$('.vc_custom_heading').css('text-align','left');

			});
			document.getElementsByClassName("sample_vin")[0].addEventListener('click',function ()
			{
				$(".vin").val('WBABW33426PX70804');
			});
		});
	})(jQuery)
</script>
