<?php
/**
 * Shortcode attributes
 * @var $atts
 * */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$uniq_id = uniqid();
// phpcs:disable
if ( ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) || ( ! empty( $active_tab_bg_color ) && '#153e4d' !== $active_tab_bg_color ) ) :
	?>
	<style>
		<?php if ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) : ?>
			.stm_dynamic_listing_two_filter .stm_dynamic_listing_filter_nav li {
				background: <?php echo wp_strip_all_tags( $inactive_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
				border-right: 1px solid <?php echo wp_strip_all_tags( $inactive_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
		<?php endif; ?>
		<?php if ( ! empty( $active_tab_bg_color ) && ( '#153e4d' !== $active_tab_bg_color || ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) ) ) : ?>
			.stm_dynamic_listing_two_filter .stm_dynamic_listing_filter_nav li.active {
				background: <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
				border-right: 1px solid <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
			.stm_dynamic_listing_two_filter .tab-content {
				background-color: <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
		<?php endif; ?>
	</style>

<?php
// phpcs:enable
endif;

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );


if ( isset( $atts['items'] ) && strlen( $atts['items'] ) > 0 ) {
	$items = vc_param_group_parse_atts( $atts['items'] );
	if ( ! is_array( $items ) ) {
		$temp         = explode( ',', $atts['items'] );
		$param_values = array();
		foreach ( $temp as $value ) {
			$data                  = explode( '|', $value );
			$new_line              = array();
			$new_line['title']     = $data[0] ?? 0;
			$new_line['sub_title'] = $data[1] ?? '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$new_line['title']     = (float) str_replace( '%', '', $data[1] );
				$new_line['sub_title'] = $data[2] ?? '';
			}
			$param_values[] = $new_line;
		}
		$atts['items'] = rawurlencode( wp_json_encode( $param_values ) );
	}
}

$args = array(
	'post_type'              => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'            => 'publish',
	'posts_per_page'         => 1,
	'suppress_filters'       => 0,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
);

if ( apply_filters( 'stm_sold_status_enabled', true ) ) {
	$args['meta_query'][] = array(
		'key'     => 'car_mark_as_sold',
		'value'   => '',
		'compare' => '=',
	);
}

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

$nonce_field = apply_filters( 'stm_listings_filter_nonce', false );
?>
<div class="stm_dynamic_listing_two_filter filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr( $css_class ); ?>">
	<!-- Nav tabs -->
	<ul class="stm_dynamic_listing_filter_nav clearfix heading-font" role="tablist">
		<li role="presentation" class="active">
			<a href="#stm_first_tab" aria-controls="stm_first_tab" role="tab" data-toggle="tab">
				<?php echo esc_attr( $first_tab_label ); ?>
			</a>
		</li>
		<li role="presentation">
			<a href="#stm_second_tab" aria-controls="stm_second_tab" role="tab" data-toggle="tab">
				<?php echo esc_attr( $second_tab_label ); ?>
			</a>
		</li>
		<li role="presentation">
			<a href="#stm_third_tab" aria-controls="stm_third_tab" role="tab" data-toggle="tab">
				<?php echo esc_attr( $third_tab_label ); ?>
			</a>
		</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="stm_first_tab">
			<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="GET">
				<?php echo wp_kses_post( $nonce_field ); ?>
				<div class="btn-wrap">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
							<?php if ( count( explode( ',', $first_tab_tax ) ) > 4 ) : ?>
								<div class="stm-more-options-wrap" data-tab="first">
								<span>
									<?php echo esc_html__( 'Advanced search', 'motors-wpbakery-widgets' ); ?>
									<i class="fas fa-angle-down"></i>
								</span>
								</div>
							<?php endif; ?>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
							<button type="submit" class="heading-font">
								<i class="fas fa-search"></i>
								<?php
									$all = new WP_Query( $args );

									echo wp_kses_post( sprintf( '<span> %s </span> %s', $all->found_posts, $search_button_postfix ) );
								?>
							</button>
						</div>
					</div>
				</div>
				<div class="stm-filter-tab-selects stm-filter-tab-selects-first filter stm-vc-ajax-filter">
					<?php apply_filters( 'stm_listing_filter_get_selects', $first_tab_tax, 'stm_all_listing_tab', $words, $show_amount, true ); ?>
				</div>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="stm_second_tab">
			<form action="<?php echo esc_url( stm_review_archive_link() ); ?>" method="GET" class="stm-review-search-form">
				<?php echo wp_kses_post( $nonce_field ); ?>
				<div class="stm-filter-tab-selects stm-filter-tab-selects-second filter stm-vc-ajax-filter">
					<?php apply_filters( 'stm_listing_filter_get_selects', $second_tab_tax, 'stm_car_reviews_tab', $words, false, true ); ?>
				</div>
				<div class="btn-wrap">
					<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
						<?php if ( count( explode( ',', $second_tab_tax ) ) > 4 ) : ?>
							<div class="stm-more-options-wrap" data-tab="second">
							<span>
								<?php echo esc_html__( 'Advanced search', 'motors-wpbakery-widgets' ); ?>
								<i class="fas fa-angle-down"></i>
							</span>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
						<button type="submit" class="heading-font">
							<i class="fas fa-search"></i>
							<?php
								$args = wp_parse_args(
									array(
										'post_type'      => stm_review_post_type(),
										'posts_per_page' => -1,
										'meta_query'     => array(),
									),
									$args
								);

								$all = new WP_Query( $args );

								echo wp_kses_post( sprintf( '<span> %s </span> %s', $all->found_posts, $search_button_postfix ) );
								?>
						</button>
					</div>
				</div>
				<input type="hidden" name="listing_type" value="with_review"/>
				<?php
				$review_cars = array();
				foreach ( $all->posts as $review ) {
					if ( ! empty( get_post_meta( $review->ID, 'review_car', true ) ) ) {
						$review_cars[] = (int) get_post_meta( $review->ID, 'review_car', true );
					}
				}
				?>
				<script>
					var listings_with_reviews = '<?php echo json_encode( $review_cars ); ?>';
				</script>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="stm_third_tab">
			<div class="stm-filter-tab-selects stm-filter-tab-selects-third filter stm-vc-ajax-filter"
				id="value-my-car-<?php echo esc_attr( $uniq_id ); ?>">
				<?php
				if ( ! empty( $third_tab_tax ) ) {
					$html   = '<form method="post" name="vmc-form" type="multipart/formdata">';
					$html  .= $nonce_field;
					$html  .= '<div class="row">';
					$params = explode( ',', $third_tab_tax );
					$opt    = stm_get_value_my_car_options();
					$i      = 0;

					foreach ( $params as $k ) {
						if ( 4 === $i && count( $params ) > 4 ) {
							$html .= '<div class="stm-slide-content clearfix">';
						}

						if ( 'photo' === $k ) {
							$html .= '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col vmc-file-wrap">'; // input wrap div open.
							$html .= '<div class="file-wrap"><div class="input-file-holder"><span>' . __( 'Add Image', 'motors-wpbakery-widgets' ) . '</span><i class="fas fa-plus"></i><input type="file"  name="' . $k . '" /></div><span class="error"></span></div>';
						} else {
							$html .= '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col">'; // input wrap div open.
							$html .= '<input type="text" name="' . $k . '" placeholder="' . array_search( $k, $opt, true ) . '" />';
						}

						$html .= '</div>'; // input wrap div close.

						if ( ( count( $params ) - 1 ) === $i && count( $params ) > 4 ) {
							$html .= '</div>';
						}

						$i ++;
					}

					$html .= '</div>';
					$html .= '</form>';

					echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
			<div class="btn-wrap">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
						<?php if ( count( explode( ',', $third_tab_tax ) ) > 4 ) : ?>
							<div class="stm-more-options-wrap" data-tab="third">
							<span>
								<?php echo esc_html__( 'More Options', 'motors-wpbakery-widgets' ); ?>
								<i class="fas fa-angle-down"></i>
							</span>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
						<button id="vmc-btn" type="submit" class="vmc-btn-submit heading-font"
								data-widget-id="value-my-car-<?php echo esc_attr( $uniq_id ); ?>">
							<?php echo esc_html( $third_tab_label ); ?>
							<i class="fas fa-spinner"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$bind_tax = apply_filters( 'stm_data_binding_func', array(), true );

if ( ! empty( $bind_tax ) ) :
	?>

	<script>
		jQuery(function ($) {
			let options 	= Object.seal( <?php echo wp_json_encode( $bind_tax ); ?> ),
				show_amount = Boolean( <?php echo wp_json_encode( 'no' !== $show_amount ); ?> );

			if ( show_amount ) {
				for ( const item of Object.entries( options ) ) {
					if ( typeof item.options === "object" ) {
						for ( const option of Object.entries( item.options ) ) {
							option.label = option.label + ' (' + option.count + ')';
						}
					}
				}
			}

			$( '.stm-filter-tab-selects.filter' ).each(function () {
				new STMCascadingSelect( this, options );
			});

			$( 'select[data-class="stm_select_overflowed"]' ).on('change', function () {
				let min   = '',
					max   = '',
					$this = $(this),
					value = $this.val(),
					type  = $this.attr("data-sel-type");

				if( value === null || value.length === 0 ) return;

				if ( value.includes("<") ) {
					max = value.replace("<", "").trim();
				} else if ( value.includes("-") ) {
					let strSplit = value.split("-");

					min = strSplit.shift();
					max = strSplit.shift();
				} else {
					min = value.replace(">", "").trim();
				}

				$('input[name="min_' + type + '"]').val( min );
				$('input[name="max_' + type + '"]').val( max );
			});
		});
	</script>
<?php endif; ?>
