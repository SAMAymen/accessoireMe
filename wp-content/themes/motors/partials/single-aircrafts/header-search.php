<?php
motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2', 'stm-cascadingdropdown' ) );
$options    = get_option( 'stm_vehicle_listing_options', '' );
$filter_all = '';

$i = 0;
foreach ( $options as $k => $val ) {
	if ( ! empty( $val['use_on_single_header_search'] ) ) {
		if ( 0 !== $i ) {
			$filter_all .= ',';
		}
		$filter_all .= $val['slug'];
		$i ++;
	}
}

if ( ! empty( $filter_all ) ) :

	?>
	<div class="container stm-single-header-search-wrap filter-listing stm-vc-ajax-filter">
		<form action="<?php echo esc_attr( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="get">
			<div class="stm-single-header-search">
				<h4><?php echo wp_kses_post( '<span>Quick</span> search', 'motors' ); ?></h4>
				<div class="stm-filter-selects filter stm-vc-ajax-filter">
					<?php apply_filters( 'stm_listing_filter_get_selects', $filter_all ); ?>
				</div>
				<div class="filter-actions">
				<button type="submit" class="heading-font"><i class="fas fa-search"></i>
					<span></span> <?php echo esc_html__( 'Planes', 'motors' ); ?></button>
				</div>
			</div>
		</form>
	</div>
<?php endif; ?>
<?php
$bind_tax = stm_data_binding( true );

if ( ! empty( $bind_tax ) ) :
	?>

	<script>
		jQuery(function ($) {
			var options = <?php echo wp_json_encode( $bind_tax ); ?>;

			$('.stm-filter-selects.filter').each(function () {
				new STMCascadingSelect(this, options);
			});

			$("select[data-class='stm_select_overflowed']").on("change", function () {
				var sel = $(this);
				var selValue = sel.val();
				var selType = sel.attr("data-sel-type");
				var min = 'min_' + selType;
				var max = 'max_' + selType;

				if( selValue === null || selValue.length == 0 ) return;

				if (selValue.includes("<")) {
					var str = selValue.replace("<", "").trim();
					$("input[name='" + min + "']").val("");
					$("input[name='" + max + "']").val(str);
				} else if (selValue.includes("-")) {
					var strSplit = selValue.split("-");
					$("input[name='" + min + "']").val(strSplit[0]);
					$("input[name='" + max + "']").val(strSplit[1]);
				} else {
					var str = selValue.replace(">", "").trim();
					$("input[name='" + min + "']").val(str);
					$("input[name='" + max + "']").val("");
				}
			});
		});
	</script>
<?php endif; ?>
