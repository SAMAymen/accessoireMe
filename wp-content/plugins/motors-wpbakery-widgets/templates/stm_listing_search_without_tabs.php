<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$args = array(
	'post_type'        => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'      => 'publish',
	'posts_per_page'   => 1,
	'suppress_filters' => 0,
);

if ( apply_filters( 'stm_sold_status_enabled', true ) ) {
	$args['meta_query'][] = array(
		'key'     => 'car_mark_as_sold',
		'value'   => '',
		'compare' => '=',
	);
}

$all = new WP_Query( $args );
$all = $all->found_posts;

if ( empty( $show_amount ) ) {
	$show_amount = 'no';
}

$words = array();

if ( ! empty( $select_prefix ) ) {
	$words['select_prefix'] = $select_prefix;
}

if ( ! empty( $select_affix ) ) {
	$words['select_affix'] = $select_affix;
}

if ( ! empty( $number_prefix ) ) {
	$words['number_prefix'] = $number_prefix;
}

if ( ! empty( $number_affix ) ) {
	$words['number_affix'] = $number_affix;
}

?>

	<div class="stm_dynamic_listing_filter stm_dynamic_listing_filter_without_tabs filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr( $css_class ); ?>">

		<!-- Tab panes -->
		<div class="tab-content">
			<div class="stm_listing_search_title heading-title">
				<i class="stm-icon-car_search"></i>
				<?php echo esc_html( $title ); ?>
			</div>
			<div class="tab-pane fade in active" id="stm_all_listing_tab_without_tabs">
				<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="GET">
					<div class="btn-wrap">
						<button class="reset-filter" type="submit" class="heading-font">
							<i class="stm-icon-reset"></i>
						</button>
						<button type="submit" class="heading-font border-btn">
							<i class="fas fa-search"></i> <?php echo esc_html__( 'Search', 'motors-wpbakery-widgets' ); ?>
						</button>
					</div>
					<div class="stm-filter-tab-selects filter stm-vc-ajax-filter">
						<?php apply_filters( 'stm_listing_filter_get_selects', $filter_all, 'stm_all_listing_tab', $words, $show_amount ); ?>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
$bind_tax = apply_filters( 'stm_data_binding_func', array(), true );
if ( ! empty( $bind_tax ) ) :
	?>

	<script>
		jQuery(function ($) {
			var options = <?php echo wp_json_encode( $bind_tax ); ?>, show_amount = <?php echo wp_json_encode( 'no' !== $show_amount ); ?>;

			if (show_amount) {
				$.each(options, function (tax, data) {
					$.each(data.options, function (val, option) {
						option.label += ' (' + option.count + ')';
					});
				});
			}

			$('.stm-filter-tab-selects.filter').each(function () {
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
