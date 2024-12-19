<?php
/**
 * Shortcode attributes
 * @var $atts
 * */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) || ( ! empty( $active_tab_bg_color ) && '#153e4d' !== $active_tab_bg_color ) ) :
	?>
	<style>
		<?php if ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) : ?>
			.stm_dynamic_listing_filter .stm_dynamic_listing_filter_nav li {
				background: <?php echo wp_strip_all_tags( $inactive_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
				border-right: 1px solid <?php echo wp_strip_all_tags( $inactive_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
		<?php endif; ?>
		<?php if ( ! empty( $active_tab_bg_color ) && ( '#153e4d' !== $active_tab_bg_color || ( ! empty( $inactive_tab_bg_color ) && '#11323e' !== $inactive_tab_bg_color ) ) ) : ?>
			.stm_dynamic_listing_filter .stm_dynamic_listing_filter_nav li.active {
				background: <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
				border-right: 1px solid <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
			.stm_dynamic_listing_filter .tab-content {
				background-color: <?php echo wp_strip_all_tags( $active_tab_bg_color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> !important;
			}
		<?php endif; ?>
	</style>

	<?php
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
			$new_line['title']     = isset( $data[0] ) ? $data[0] : 0;
			$new_line['sub_title'] = isset( $data[1] ) ? $data[1] : '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$new_line['title']     = (float) str_replace( '%', '', $data[1] );
				$new_line['sub_title'] = isset( $data[2] ) ? $data[2] : '';
			}
			$param_values[] = $new_line;
		}
		$atts['items'] = rawurlencode( wp_json_encode( $param_values ) );
	}
}

$active_taxonomy_tab         = true;
$active_taxonomy_tab_active  = 'active';
$active_taxonomy_tab_content = 'in active';

if ( ! empty( $show_all ) && 'yes' === $show_all ) {
	$active_taxonomy_tab         = false;
	$active_taxonomy_tab_active  = '';
	$active_taxonomy_tab_content = '';
}

if ( empty( $filter_all ) ) {
	$active_taxonomy_tab        = true;
	$active_taxonomy_tab_active = 'active';
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
	<div class="stm_dynamic_listing_filter filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr( $css_class ); ?>">
		<!-- Nav tabs -->
		<ul class="stm_dynamic_listing_filter_nav clearfix heading-font" role="tablist">
			<?php if ( ! $active_taxonomy_tab ) : ?>
				<li role="presentation" class="active">
					<a href="#stm_all_listing_tab" aria-controls="stm_all_listing_tab" role="tab" data-toggle="tab">
						<?php echo esc_attr( $show_all_label ); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php
			if ( ! empty( $items ) && is_array( $items ) && count( $items[0] ) > 0 ) :
				$i = 0;
				foreach ( $items as $key => $item ) :

					if ( empty( $item['taxonomy_tab'] ) ) {
						continue;
					}

					$i++;
					$item_tab = str_replace( array( ',', ' ' ), '', $item['taxonomy_tab'] );
					$data     = explode( '|', $item_tab );
					if ( $i > 1 ) {
						$active_taxonomy_tab_active = '';
					}

					if ( ! empty( $item['tab_title_single'] ) && ! empty( $item['filter_selected'] ) ) :
						$slug = ( isset( $item['tab_id_single'] ) ) ? sanitize_title( $item['tab_id_single'] ) : sanitize_title( $item['tab_title_single'] );
						?>

					<li class="<?php echo esc_attr( $active_taxonomy_tab_active ); ?>">
						<a href="#<?php echo esc_attr( $slug ); ?>" aria-controls="<?php echo esc_attr( $slug ); ?>"
							role="tab" data-toggle="tab" data-value="<?php echo esc_attr( current( $data ) ); ?>"
							data-slug="<?php echo esc_attr( next( $data ) ); ?>">
							<?php echo esc_html( $item['tab_title_single'] ); ?>
						</a>
					</li>

				<?php endif; ?>
					<?php
				endforeach;
				$i = 0;
				?>
			<?php endif; ?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<?php if ( ! $active_taxonomy_tab ) : ?>
				<div role="tabpanel" class="tab-pane fade in active" id="stm_all_listing_tab">
					<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="GET">
						<?php echo wp_kses_post( $nonce_field ); ?>
						<div class="stm-filter-tab-selects filter stm-vc-ajax-filter">
							<?php apply_filters( 'stm_listing_filter_get_selects', $filter_all, 'stm_all_listing_tab', $words, $show_amount ); ?>
						</div>
						<button type="submit" class="heading-font">
							<i class="fas fa-search"></i>
							<?php
								$all = new WP_Query( $args );

								echo wp_kses_post( sprintf( '<span> %s </span> %s', $all->found_posts, $search_button_postfix ) );
							?>
						</button>
					</form>
				</div>
			<?php endif; ?>

			<?php
			if ( is_array( $items ) && count( $items ) > 0 ) :
				foreach ( $items as $key => $item ) :
					$i++;
					if ( $i > 1 ) {
						$active_taxonomy_tab_content = '';
					}

					if ( ! empty( $item['taxonomy_tab'] ) && ! empty( $item['tab_title_single'] ) && ! empty( $item['filter_selected'] ) ) :
						$slug = ( isset( $item['tab_id_single'] ) ) ? sanitize_title( $item['tab_id_single'] ) : sanitize_title( $item['tab_title_single'] );
						?>
						<div role="tabpanel" class="tab-pane fade <?php echo esc_attr( $active_taxonomy_tab_content ); ?>"
							id="<?php echo esc_attr( $slug ); ?>">
							<?php
							$tax_term = explode( ',', $item['taxonomy_tab'] );
							$tax_term = explode( ' | ', current( $tax_term ) );
							$slug     = current( $tax_term );
							$taxonomy = next( $tax_term );

							$taxonomy_count = apply_filters( 'stm_get_custom_taxonomy_count', 0, $slug, $taxonomy );
							?>
							<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="GET">
								<?php echo wp_kses_post( $nonce_field ); ?>
								<div class="stm-filter-tab-selects filter stm-vc-ajax-filter">
									<input type="hidden" name="<?php echo esc_attr( $taxonomy ); ?>" data-val="<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $slug ); ?>" class="no-cascading"/>
									<?php apply_filters( 'stm_listing_filter_get_selects', $item['filter_selected'], $slug, $words, $show_amount ); ?>
								</div>
								<button type="submit" class="heading-font">
									<i class="fas fa-search"></i>
									<?php echo wp_kses_post( sprintf( '<span> %s </span> %s', $taxonomy_count, $search_button_postfix ) ); ?>
								</button>
							</form>
						</div>

					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
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
