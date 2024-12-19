<?php
$vin        = get_post_meta( get_the_ID(), 'vin_number', true );
$price      = get_post_meta( get_the_ID(), 'price', true );
$sale_price = get_post_meta( get_the_ID(), 'sale_price', true );

$guruStyle  = apply_filters( 'motors_vl_get_nuxy_mod', 'STYLE1', 'carguru_style' );
$guruRating = apply_filters( 'motors_vl_get_nuxy_mod', 'GREAT_PRICE', 'carguru_min_rating' );
$guruHeight = apply_filters( 'motors_vl_get_nuxy_mod', '42', 'carguru_default_height' );

if ( ! empty( $sale_price ) ) {
	$price = $sale_price;
}

if ( ! empty( $guruStyle ) && ! empty( $vin ) && ! empty( $price ) ) :
	//phpcs:disable
	?>

	<script>

		var CarGurus = window.CarGurus || {};
		window.CarGurus = CarGurus;
		CarGurus.DealRatingBadge = window.CarGurus.DealRatingBadge || {};
		CarGurus.DealRatingBadge.options = {
			"style": "<?php echo esc_js( $guruStyle ); ?>",
			"minRating": "<?php echo esc_js( $guruRating ); ?>",
			<?php
			if ( false !== strpos( $guruStyle, 'STYLE' ) ) :
				?>
				"defaultHeight": "<?php echo esc_js( $guruHeight ); ?>"<?php endif; ?>
		};

		(function () {
			var script = document.createElement('script');
			script.src = "https://static.cargurus.com/js/api/en_US/1.0/dealratingbadge.js";
			script.async = true;
			var entry = document.getElementsByTagName('script')[0];
			entry.parentNode.insertBefore(script, entry);
		})();
	</script>

	<div class="stm_cargurus_wrapper <?php echo ( strpos( $guruStyle, 'STYLE' ) !== false ) ? 'cg_style' : 'cg_banner'; ?>">
		<span data-cg-vin="<?php echo esc_attr( $vin ); ?>" data-cg-price="<?php echo intval( $price ); ?>"></span>
	</div>

<?php
//phpcs:enable
endif;

