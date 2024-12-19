<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $unit
 */


$slug     = $unit['slug'];
$mileages = array();
foreach ( $terms as $term ) {
	$mileages[] = intval( $term->name );
}
sort( $mileages );
?>

<div class="stm-accordion-single-unit stm-modern-dynamic-slider stm-modern-price-unit <?php echo esc_attr( $slug ); ?>"
	data-slider-name="<?php echo esc_attr( $slug ); ?>">
	<a class="title" data-toggle="collapse"
		href="#<?php echo esc_attr( $slug ); ?>" aria-expanded="true">
		<h5><?php echo esc_html( $unit['single_name'] ); ?></h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="collapse in content" id="<?php echo esc_attr( $slug ); ?>">
			<div class="stm-accordion-content-wrapper stm-modern-filter-<?php echo esc_attr( $slug ); ?>">

				<div class="stm-<?php echo esc_attr( $slug ); ?>-range-unit">
					<div class="stm-<?php echo esc_attr( $slug ); ?>-range"></div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-md-wider-right">
						<input type="text" name="min_<?php echo esc_attr( $slug ); ?>" id="stm_filter_min_<?php echo esc_attr( $slug ); ?>" readonly />
					</div>
					<div class="col-md-6 col-sm-6 col-md-wider-left">
						<input type="text" name="max_<?php echo esc_attr( $slug ); ?>" id="stm_filter_max_<?php echo esc_attr( $slug ); ?>" readonly />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function ($) {
		$(document).ready(function () {
			stmOptionsObj['<?php echo esc_attr( $slug ); ?>'] = {
				range: true,
				min: <?php echo esc_js( $mileages[0] ); ?>,
				max: <?php echo esc_js( $mileages[ count( $mileages ) - 1 ] ); ?>,
				values: [<?php echo esc_js( $mileages[0] ); ?>, <?php echo esc_js( $mileages[ count( $mileages ) - 1 ] ); ?>],
				step: 1,
				slide: function (event, ui) {
					$("#stm_filter_min_<?php echo esc_attr( $slug ); ?>").val(ui.values[0]);
					$("#stm_filter_max_<?php echo esc_attr( $slug ); ?>").val(ui.values[1]);
				}
			}

			$(".stm-<?php echo esc_attr( $slug ); ?>-range").slider(stmOptionsObj['<?php echo esc_attr( $slug ); ?>']);

			$("#stm_filter_min_<?php echo esc_attr( $slug ); ?>").val($(".stm-<?php echo esc_attr( $slug ); ?>-range").slider("values", 0));
			$("#stm_filter_max_<?php echo esc_attr( $slug ); ?>").val($(".stm-<?php echo esc_attr( $slug ); ?>-range").slider("values", 1));
		})
	})(jQuery);
</script>
