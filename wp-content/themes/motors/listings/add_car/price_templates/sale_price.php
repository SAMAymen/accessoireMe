<div class="col-md-4 col-sm-12">
	<div class="stm_price_input">
		<div class="stm_label heading-font"><?php esc_html_e( 'Sale Price', 'motors' ); ?>
			(<?php echo esc_html( apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) ) ); ?>)
		</div>
		<input type="number" min="0" class="heading-font" name="stm_car_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" required/>
	</div>
</div>
