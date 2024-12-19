<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( defined( 'STM_LISTINGS' ) ) :
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

	$filter = apply_filters( 'stm_listings_filter_func', null );

	if ( ! empty( $taxonomy ) ) {
		$stm_tax = explode( ',', $taxonomy );
		unset( $stm_tax[ count( $stm_tax ) - 1 ] );
		$stm_taxonomy = implode( ',', $stm_tax );
	}

	$stm_taxonomy = ( ! empty( $stm_taxonomy ) ) ? $stm_taxonomy : 'make,serie,ca-year,price';

	?>

		<div class="stm_dynamic_listing_filter_with_rating filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr( $css_class ); ?>">

			<div class="top-filter-wrap">
				<div class="container">
					<h3>
						<?php echo esc_html( $title ); ?>
					</h3>
				</div>
				<div class="selected-filter heading-font"></div>
				<div class="c-r-remove-filter">
					<i class="fas fa-times-circle"></i>
				</div>
				<!-- Tab panes -->
				<div class="middle">
					<div class="filter">
						<form id="listing-with-review" action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="GET">
							<div class="stm-filter-tab-selects filter stm-vc-ajax-filter">
								<?php apply_filters( 'stm_listing_filter_get_selects', $stm_taxonomy, 'stm_all_listing_tab', $words, $show_amount ); ?>
							</div>
							<input type="hidden" name="result_with_posts" value="1" />
							<input type="hidden" name="posts_per_page" value="<?php echo esc_attr( $cars_quantity ); ?>" />
							<input type="hidden" name="filter-params" value="<?php echo esc_html( $stm_taxonomy ); ?>">
							<input type="hidden" name="offset" value="0" />
						</form>
					</div>
				</div>
			</div>


			<div id="filterResultBox">
				<?php do_action( 'stm_listings_load_template', 'filter/result_with_rating' ); ?>
			</div>

			<div class="load-more-btn-wrap">
				<a id="lmb-car-review" class="load-more-btn" href="">
					<?php esc_html_e( 'Load more', 'motors-wpbakery-widgets' ); ?>
				</a>
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
					if (selValue.includes(">")) {
						var str = selValue.replace(">", "").trim();
						$("input[name='" + min + "']").val(str);
						$("input[name='" + max + "']").val("");
					} else if (selValue.includes("-")) {
						var strSplit = selValue.split("-");
						$("input[name='" + min + "']").val(strSplit[0]);
						$("input[name='" + max + "']").val(strSplit[1]);
					} else {
						var str = selValue.replace(">", "").trim();
						$("input[name='" + min + "']").val("");
						$("input[name='" + max + "']").val(str);
					}
				});

			});

		</script>
	<?php endif; ?>
	<script>
		window.addEventListener('load', function () {
			stm_load_cars_with_review();
		});
	</script>
<?php endif; ?>
