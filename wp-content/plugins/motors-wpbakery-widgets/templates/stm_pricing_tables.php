<?php
// Variables.
$pricing_tables_count = 3;
// Pricing Tables - 1.

for ( $i = 1; $i <= 3; $i++ ) {
	${'pt_' . $i . '_title'}     = '';
	${'pt_' . $i . '_periods'}   = '';
	${'pt_' . $i . '_features'}  = '';
	${'pt_' . $i . '_link_text'} = '';
	${'pt_' . $i . '_link'}      = '';
}

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

/*  === CSS CLASS === */
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( 'three' === $pricing_tables_count ) {
	$pricing_tables_count = 3;
} elseif ( 'two' === $pricing_tables_count ) {
	$pricing_tables_count = 2;
} else {
	$pricing_tables_count = 1;
}


/*  === FEATURES === */
for ( $i = 1; $i <= $pricing_tables_count; $i++ ) {
	if ( isset( $atts[ 'pt_' . $i . '_features' ] ) && strlen( $atts[ 'pt_' . $i . '_features' ] ) > 0 ) {
		${'pt_' . $i . '_features'} = vc_param_group_parse_atts( $atts[ 'pt_' . $i . '_features' ] );
		if ( ! is_array( ${'pt_' . $i . '_features'} ) ) {
			$temp         = explode( ',', $atts[ 'pt_' . $i . '_features' ] );
			$param_values = array();
			foreach ( $temp as $value ) {
				$data                                      = explode( '|', $value );
				$new_line                                  = array();
				$new_line[ 'pt_' . $i . '_feature_title' ] = isset( $data[0] ) ? $data[0] : 0;
				$new_line[ 'pt_' . $i . '_feature_text' ]  = isset( $data[0] ) ? $data[0] : 0;
				if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
					$new_line[ 'pt_' . $i . '_feature_title' ] = (float) str_replace( '%', '', $data[1] );
					$new_line[ 'pt_' . $i . '_feature_text' ]  = (float) str_replace( '%', '', $data[1] );
				}
				$param_values[] = $new_line;
			}
			$atts[ 'pt_' . $i . '_features' ] = rawurlencode( wp_json_encode( $param_values ) );
		}
	}
}

/*  === PERIODS === */
for ( $i = 1; $i <= $pricing_tables_count; $i++ ) {
	if ( isset( $atts[ 'pt_' . $i . '_periods' ] ) && strlen( $atts[ 'pt_' . $i . '_periods' ] ) > 0 ) {
		${'pt_' . $i . '_periods'} = vc_param_group_parse_atts( $atts[ 'pt_' . $i . '_periods' ] );
		if ( ! is_array( ${'pt_' . $i . '_periods'} ) ) {
			$temp         = explode( ',', $atts[ 'pt_' . $i . '_periods' ] );
			$param_values = array();
			foreach ( $temp as $value ) {
				$data                                       = explode( '|', $value );
				$new_line                                   = array();
				$new_line[ 'pt_' . $i . '_periods_price' ]  = isset( $data[0] ) ? $data[0] : 0;
				$new_line[ 'pt_' . $i . '_periods_period' ] = isset( $data[0] ) ? $data[0] : 0;
				$new_line[ 'pt_' . $i . '_periods_link' ]   = isset( $data[0] ) ? $data[0] : 0;
				if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
					$new_line[ 'pt_' . $i . '_periods_price' ]  = (float) str_replace( '%', '', $data[1] );
					$new_line[ 'pt_' . $i . '_periods_period' ] = (float) str_replace( '%', '', $data[1] );
					$new_line[ 'pt_' . $i . '_periods_link' ]   = (float) str_replace( '%', '', $data[1] );
				}
				$param_values[] = $new_line;
			}
			$atts[ 'pt_' . $i . '_periods' ] = rawurlencode( wp_json_encode( $param_values ) );
		}
	}
}

/*  === Labels === */
for ( $i = 1; $i <= $pricing_tables_count; $i++ ) {
	if ( isset( $atts[ 'pt_' . $i . '_labels' ] ) && strlen( $atts[ 'pt_' . $i . '_labels' ] ) > 0 ) {
		${'pt_' . $i . '_labels'} = vc_param_group_parse_atts( $atts[ 'pt_' . $i . '_labels' ] );
		if ( ! is_array( ${'pt_' . $i . '_labels'} ) ) {
			$temp         = explode( ',', $atts[ 'pt_' . $i . '_labels' ] );
			$param_values = array();
			foreach ( $temp as $value ) {
				$data                                    = explode( '|', $value );
				$new_line                                = array();
				$new_line[ 'pt_' . $i . '_label_text' ]  = isset( $data[0] ) ? $data[0] : 0;
				$new_line[ 'pt_' . $i . '_label_color' ] = isset( $data[0] ) ? $data[0] : 0;
				$param_values[]                          = $new_line;
			}
			$atts[ 'pt_' . $i . '_labels' ] = rawurlencode( wp_json_encode( $param_values ) );
		}
	}
}

/* === LINK === */
for ( $i = 1; $i <= $pricing_tables_count; $i++ ) {
	if ( ! empty( ${'pt_' . $i . '_link'} ) ) {
		${'pt_' . $i . '_link'} = vc_build_link( ${'pt_' . $i . '_link'} );

		if ( empty( ${'pt_' . $i . '_link'}['target'] ) ) {
			${'pt_' . $i . '_link'}['target'] = '_self';
		}
	}
}

/* === ID === */
$stm_pricing_id = uniqid( 'stm-pricing-' );
?>
<div class="stm-pricing stm-pricing_<?php echo esc_attr( $pricing_tables_count ); ?>" id="<?php echo esc_attr( $stm_pricing_id ); ?>">
	<div class="stm-pricing__content clearfix">
		<div class="stm-pricing__side-panel">

			<div class="stm-pricing__filters heading-font">
				<ul>
					<?php $pricing_periods = array(); ?>
					<?php for ( $i = 1; $i <= $pricing_tables_count; $i++ ) : ?>
						<?php foreach ( ${'pt_' . $i . '_periods'} as ${'pt_' . $i . '_periods_item'} ) : ?>
							<?php if ( ! in_array( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_period' ], $pricing_periods, true ) ) : ?>
								<?php
								$pricing_periods[] = ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_period' ];
								$pp                = ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_period' ];
								$label             = esc_html__( 'Month', 'motors-wpbakery-widgets' );
								if ( 'yearly' === $pp ) {
									$label = esc_html__( 'Yearly', 'motors-wpbakery-widgets' );
								}
								?>
								<li class="stm-pricing__filter <?php echo( ( $pricing_periods[0] === $pp ) ? 'stm-pricing__filter_active' : '' ); ?>">
									<a data-period-filter="<?php echo esc_attr( $pp ); ?>" href="#">
										<?php echo esc_html( ucfirst( $label ) ); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endfor; ?>
				</ul>
			</div>

			<ul class="stm-pricing__features heading-font">
				<?php if ( ! empty( $stm_motors_price_label ) ) : ?>
					<li class="stm-pricing__feature motors-price-side"><?php echo esc_html( $stm_motors_price_label ); ?></li>
				<?php endif; ?>
				<?php foreach ( $pt_1_features as $pt_1_features_item ) : ?>
					<?php if ( ! empty( $pt_1_features_item['pt_1_feature_title'] ) ) : ?>
						<li class="stm-pricing__feature"><?php echo esc_html( $pt_1_features_item['pt_1_feature_title'] ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

		</div>
		<div class="stm-pricing__tables">
			<div class="stm-pricing__tables-row clearfix">
				<?php for ( $i = 1; $i <= $pricing_tables_count; $i++ ) : ?>
					<?php if ( ! empty( ${'pt_' . $i . '_periods'} ) ) : ?>
						<div class="stm-pricing__tables-col">
							<div class="stm-pricing__tables-col-inner">
								<div class="stm_pricing_table_col_labels heading-font clearfix">
									<?php if ( ! empty( ${'pt_' . $i . '_labels'} ) ) : ?>
										<?php foreach ( ${'pt_' . $i . '_labels'} as $label_item ) : ?>
											<?php if ( ! empty( $label_item[ 'pt_' . $i . '_label_text' ] ) ) : ?>
												<?php
												$color_label = '';
												if ( ! empty( $label_item[ 'pt_' . $i . '_label_color' ] ) ) {
													$color_label = 'style = background-color:' . $label_item[ 'pt_' . $i . '_label_color' ] . ';';
												}
												?>
												<div class="stm_pricing_table_single_label" <?php echo esc_attr( $color_label ); ?>><?php echo esc_attr( $label_item[ 'pt_' . $i . '_label_text' ] ); ?></div>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif ?>
								</div>
								<div class="stm-pricing-table heading-font">
									<?php if ( ! empty( ${'pt_' . $i . '_title'} ) ) : ?>
										<div class="stm-pricing-table__title"><?php echo esc_html( ${'pt_' . $i . '_title'} ); ?></div>
									<?php endif; ?>
									<ul class="stm-pricing-table__periods">
										<?php foreach ( ${'pt_' . $i . '_periods'} as ${'pt_' . $i . '_periods_item'} ) : ?>
											<li class="stm-pricing-table__periods-item"
												data-period="<?php echo esc_attr( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_period' ] ); ?>">
												<?php if ( ! empty( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_price' ] ) ) : ?>
													<div class="stm-pricing-table__price">
														<?php echo esc_html( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_price' ] ); ?>
													</div>
												<?php else : ?>
													<div class="stm-pricing-table__price">
														<i class="fas fa-minus"></i>
													</div>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
									<?php if ( is_array( ${'pt_' . $i . '_features'} ) && ! empty( ${'pt_' . $i . '_features'} ) ) : ?>
										<ul class="stm-pricing-table__features">
											<?php foreach ( ${'pt_' . $i . '_features'} as ${'pt_' . $i . '_features_item'} ) : ?>
												<li class="stm-pricing-table__feature">
													<?php if ( ! empty( ${'pt_' . $i . '_features_item'}[ 'pt_' . $i . '_feature_title' ] ) ) : ?>
														<div class="stm-pricing-table__feature-label"><?php echo esc_html( ${'pt_' . $i . '_features_item'}[ 'pt_' . $i . '_feature_title' ] ); ?></div>
													<?php endif; ?>
													<div class="stm-pricing-table__feature-value">
														<?php if ( isset( ${'pt_' . $i . '_features_item'}[ 'pt_' . $i . '_feature_text' ] ) ) : ?>
															<?php echo esc_html( ${'pt_' . $i . '_features_item'}[ 'pt_' . $i . '_feature_text' ] ); ?>
														<?php elseif ( isset( ${'pt_' . $i . '_features_item'}[ 'pt_' . $i . '_feature_check' ] ) ) : ?>
															<i class="fas fa-check"></i>
														<?php else : ?>
															<i class="fas fa-minus"></i>
														<?php endif; ?>
													</div>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
									<?php if ( ! empty( ${'pt_' . $i . '_periods'}[0][ 'pt_' . $i . '_periods_link' ] ) ) : ?>
										<?php foreach ( ${'pt_' . $i . '_periods'} as ${'pt_' . $i . '_periods_item'} ) : ?>
											<div class="stm-pricing-table__periods-link"
												data-period="<?php echo esc_attr( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_period' ] ); ?>">
												<?php if ( ! empty( ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_link' ] ) ) : ?>
													<div class="stm-pricing-table__action">
														<a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . ${'pt_' . $i . '_periods_item'}[ 'pt_' . $i . '_periods_link' ] ); ?>"
															class="button"><?php echo esc_html( ${'pt_' . $i . '_link_text'} ); ?>
															<i class="stm-icon stm-icon-arrow-right"></i></a>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php else : ?>
										<?php if ( isset( ${'pt_' . $i . '_add_to_cart'} ) && ! empty( ${'pt_' . $i . '_add_to_cart'} ) && ! empty( ${'pt_' . $i . '_link_text'} ) ) : ?>
											<div class="stm-pricing-table__action">
												<a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . ${'pt_' . $i . '_add_to_cart'} ); ?>"
													class="button"><?php echo esc_html( ${'pt_' . $i . '_link_text'} ); ?>
													<i class="stm-icon stm-icon-arrow-right"></i></a>
											</div>
										<?php elseif ( isset( ${'pt_' . $i . '_link'}['url'] ) && ! empty( ${'pt_' . $i . '_link_text'} ) ) : ?>
											<div class="stm-pricing-table__action">
												<a href="<?php echo esc_url( ${'pt_' . $i . '_link'}['url'] ); ?>"
													target="<?php echo esc_attr( ${'pt_' . $i . '_link'}['target'] ); ?>"
													class="button"><?php echo esc_html( ${'pt_' . $i . '_link_text'} ); ?>
													<i class="stm-icon stm-icon-arrow-right"></i></a>
											</div>
										<?php endif; ?>
									<?php endif; ?>

								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>
<script>
	(function ($) {
		"use strict";
		var pricingId = '<?php echo esc_js( $stm_pricing_id ); ?>';

		$(document).on('ready', function () {
			var activePeriod = $("#" + pricingId + ' .stm-pricing__filter_active > a').data("period-filter");

			$("#" + pricingId + ' .stm-pricing-table__periods-item,' + "#" + pricingId + ' .stm-pricing-table__periods-link').each(function () {
				if ($(this).data("period") == activePeriod) {
					$(this).addClass("stm-pricing-table__periods-item_active");
				}
			});

			// Filter fluid hover
			var activeFilterWidth = $("#" + pricingId + ' .stm-pricing__filter_active').width();
			$("#" + pricingId + ' .stm-pricing__filter_fluid-hover').width(activeFilterWidth);

			$("#" + pricingId + ' .stm-pricing__filter > a').on("click", function () {
				activePeriod = $(this).data('period-filter');

				$(this).parent().addClass('stm-pricing__filter_active').siblings().removeClass('stm-pricing__filter_active');

				$(this).closest('.stm-pricing__filters').find(".stm-pricing__filter_fluid-hover").css('left', $(this).position().left + 'px');

				$($(this).closest('.stm-pricing').find(".stm-pricing-table__periods-item")).each(function () {
					if ($(this).data("period") == activePeriod) {
						$(this).addClass("stm-pricing-table__periods-item_active").siblings().removeClass("stm-pricing-table__periods-item_active");
					}
				});

				$("#" + pricingId + ' .stm-pricing-table__periods-link').removeClass('stm-pricing-table__periods-item_active');
				$("#" + pricingId + ' .stm-pricing-table__periods-link[data-period="' + activePeriod + '"]').addClass('stm-pricing-table__periods-item_active');

				return false;
			});

		});

	})(jQuery);
</script>

