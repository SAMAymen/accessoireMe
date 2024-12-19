<?php
$header_opt_btn_link     = apply_filters( 'stm_me_get_nuxy_mod', '/add-a-car', 'header_listing_btn_link' );
$header_listing_btn_link = ( apply_filters( 'stm_is_listing_five', false ) && function_exists( 'stm_c_f_get_page_url' ) ) ? stm_c_f_get_page_url( 'add_listing' ) : $header_opt_btn_link;
$header_listing_btn_link = ( apply_filters( 'stm_is_listing_six', false ) && function_exists( 'stm_c_six_get_page_url' ) ) ? stm_c_six_get_page_url( 'add_listing' ) : $header_opt_btn_link;
$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', esc_html__( 'Add your item', 'motors' ), 'header_listing_btn_text' );
?>
<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && boolval( apply_filters( 'is_listing', array() ) ) && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) : ?>
	<div class="pull-right">
		<a href="<?php echo esc_url( $header_listing_btn_link ); ?>" class="listing_add_cart heading-font">
			<div>
				<span class="list-label heading-font">
					<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_listing_btn_text, 'Listing Button Text' ) ); ?>
				</span>
				<?php echo stm_me_get_wpcfto_icon( 'header_listing_btn_icon', 'stm-service-icon-listing_car_plus' );//phpcs:ignore ?>
			</div>
		</a>
	</div>
<?php endif; ?>
