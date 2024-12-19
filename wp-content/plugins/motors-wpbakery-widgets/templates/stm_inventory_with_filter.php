<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $css
 * Shortcode class
 * @var WPBakeryShortCode $this
 */

use MotorsVehiclesListing\Helper\FilterHelper;
use MotorsVehiclesListing\Terms\Model\TermsModel;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$filter_by    = explode( ',', $atts['filter_all'] );
$is_inventory = get_the_ID() === intval( apply_filters( 'motors_vl_get_nuxy_mod', 0, 'listing_archive' ) );
$show_sold    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );

$row_classes = 'row stm_inventory_with_filter-wrap';
if ( $is_inventory ) {
	$row_classes .= ' is_page_inventory';
}
?>
<div class="<?php echo esc_attr( $row_classes ); ?>">
	<div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt">
		<?php
		$filters = ( function_exists( 'stm_listings_attributes' ) ) ? stm_listings_attributes(
			array(
				'where'  => array( 'use_on_car_filter' => true ),
				'key_by' => 'slug',
			)
		) : null;

		if ( ! empty( $filters ) ) :
			$selected_options = array();
			?>
		<form action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="GET" data-trigger="filter">
			<?php
			echo wp_kses_post( apply_filters( 'stm_listings_filter_nonce', false ) );

			foreach ( $filters as $checkbox ) {
				$_taxonomy                             = $checkbox['slug'];
				$listing_rows_numbers_default_expanded = 'false';
				if ( isset( $checkbox['listing_rows_numbers_default_expanded'] ) && 'open' === $checkbox['listing_rows_numbers_default_expanded'] ) {
					$listing_rows_numbers_default_expanded = 'true';
				}

				if ( ! empty( $_GET[ $_taxonomy ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( is_array( $_GET[ $_taxonomy ] ) && ! empty( $_GET[ $_taxonomy ][0] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$val = $_GET[ $_taxonomy ][0]; // phpcs:ignore WordPress.Security
					} else {
						$val = sanitize_text_field( wp_unslash( $_GET[ $_taxonomy ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					}

					$selected_options = $val;
					if ( ! is_array( $selected_options ) ) {
						$selected_options = array( '0' => $selected_options );
					}
				}

				if ( ! empty( $checkbox['enable_checkbox_button'] ) && 1 === $checkbox['enable_checkbox_button'] ) {
					$stm_checkbox_ajax_button = 'stm-ajax-checkbox-button';
				} else {
					$stm_checkbox_ajax_button = 'stm-ajax-checkbox-instant';
				}
				?>
					<div class="stm-accordion-single-unit stm-listing-directory-checkboxes <?php echo esc_attr( $stm_checkbox_ajax_button ); ?>">
						<a class="title <?php echo ( 'false' === $listing_rows_numbers_default_expanded ) ? 'collapsed' : ''; ?> "
							data-toggle="collapse" href="<?php echo sprintf( '#accordion-%s', esc_attr( $_taxonomy ) ); ?>"
							aria-expanded="<?php echo esc_attr( $listing_rows_numbers_default_expanded ); ?>">
							<h5><?php echo esc_html( $checkbox['single_name'] ); ?></h5>
							<span class="minus"></span>
						</a>
						<div class="stm-accordion-content">
							<div class="collapse content <?php echo ( 'true' === $listing_rows_numbers_default_expanded ) ? 'in' : ''; ?>"
								id="accordion-<?php echo esc_attr( $_taxonomy ); ?>">
								<div class="stm-accordion-content-wrapper stm-accordion-content-padded">
									<div class="stm-accordion-inner">
										<?php
										$filter_helper               = new FilterHelper();
										$filter_helper->filter_terms = TermsModel::get_stm_terms( array( $_taxonomy ) );
										$terms                       = $filter_helper->generate_default_filter_option( $_taxonomy );

										if ( ! empty( $terms ) ) {
											foreach ( $terms as $term_slug => $term_info ) {
												$label_class   = 'stm-option-label';
												$input_checked = '';
												if ( in_array( $term_slug, $selected_options, true ) ) {
													$label_class  .= ' checked';
													$input_checked = checked( 1, 1, false );
												}
												?>
												<label class="<?php echo esc_attr( $label_class ); ?>" data-taxonomy="<?php echo sprintf( 'stm-iwf-%s', esc_attr( $_taxonomy ) ); ?>">
													<input type="checkbox"
															name="<?php echo esc_attr( $_taxonomy ); ?>[]"
															value="<?php echo esc_attr( $term_slug ); ?>"
															<?php echo wp_kses_post( $input_checked ); ?>/>
													<span class="heading-font">
														<?php echo esc_html( $term_info['label'] ); ?>
														<span class="count" data-slug="stm-iwf-<?php echo esc_attr( $term_slug ); ?>">
															<?php printf( '(%s)', esc_html( $term_info['count'] ) ); ?>
														</span>
													</span>
												</label>
												<?php
											}
										}

										if ( ! empty( $checkbox['enable_checkbox_button'] ) && 1 === $checkbox['enable_checkbox_button'] ) :
											?>
											<div class="clearfix"></div>
											<div class="stm-checkbox-submit">
												<a class="button" href="#">
													<?php esc_html_e( 'Apply', 'motors-wpbakery-widgets' ); ?>
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
			}
			?>

			<?php
			if ( $show_sold ) :
				$listing_status = apply_filters( 'stm_listings_input', null, 'listing_status' );
				?>
				<div class="stm-accordion-single-unit stm-listing-directory-checkboxes <?php echo esc_attr( $stm_checkbox_ajax_button ); ?>">
					<a class="title collapsed"
						data-toggle="collapse" href="#accordion-filter_listing_status"
						aria-expanded="false">
						<h5><?php esc_html_e( 'Listing status', 'motors-wpbakery-widgets' ); ?></h5>
						<span class="minus"></span>
					</a>
					<div class="stm-accordion-content">
						<div class="collapse content" id="accordion-filter_listing_status">
							<div class="stm-accordion-content-wrapper stm-accordion-content-padded">
								<div class="stm-accordion-inner">
									<label class="stm-option-label <?php echo ( 'active' === $listing_status ) ? 'checked' : ''; ?>" data-taxonomy="stm-iwf-stm_active_listings">
										<input type="checkbox" name="listing_status" value="active" <?php checked( $listing_status, 'active' ); ?>/>
										<span class="heading-font"><?php esc_html_e( 'Active', 'motors-wpbakery-widgets' ); ?>
										<span class="count" data-slug="stm-iwf-stm_active_listings"><?php printf( '(%s)', esc_html( stm_get_listings_count_by_status() ) ); ?></span></span>
									</label>
									<label class="stm-option-label <?php echo ( 'sold' === $listing_status ) ? 'checked' : ''; ?>" data-taxonomy="stm-iwf-stm_sold_listings">
										<input type="checkbox" name="listing_status" value="sold" <?php checked( $listing_status, 'sold' ); ?>/>
										<span class="heading-font"><?php esc_html_e( 'Sold', 'motors-wpbakery-widgets' ); ?>
										<span class="count" data-slug="stm-iwf-stm_sold_listings"><?php printf( '(%s)', esc_html( stm_get_listings_count_by_status( 'sold' ) ) ); ?></span></span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>


			<input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' ), 'view_type' ) ); ?>"/>
			<input type="hidden" name="navigation_type" value="<?php echo esc_attr( $navigation ); ?>" />
			<input type="hidden" name="posts_per_page" value="<?php echo esc_attr( $posts_per_page ); ?>" />
			<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>
		</form>
		<?php endif; ?>
	</div>

	<div class="col-md-9 col-sm-12">

		<div class="stm-ajax-row">
			<div class="stm-action-wrap">
				<?php if ( $is_inventory ) : ?>
					<div class="showing heading-font">
					<?php
					printf(
						/* translators: 1. number of posts per page, 2. zero */
						wp_kses_post( __( '<b>Showing <span class="ac-showing">%1$s</span> jets</b> from <span class="ac-total">%2$s</span>', 'motors-wpbakery-widgets' ) ),
						esc_html( $posts_per_page ),
						0
					);
					?>
					</div>
					<?php
				else :
					printf( '<h2>%s</h2>', esc_html( $inventory_title ) );
				endif;

					do_action( 'stm_listings_load_template', 'filter/actions' );
				?>
			</div>
			<div id="listings-result">
				<?php do_action( 'stm_listings_load_results', array( 'posts_per_page' => $posts_per_page ), null, $navigation ); ?>
			</div>
		</div>

	</div> <!--col-md-9-->
</div>
