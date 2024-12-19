<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $action ) ) {
	$action = 'listings-result'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

$show_sold = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );

$post_type_name = __( 'Listings', 'motors' );
$post_type_icon = 'stm-icon-car_search';
if ( stm_is_multilisting() ) {
	$multi_listing = new STMMultiListing();
	$_listing      = $multi_listing->stm_get_current_listing();

	$post_type_name = $_listing['label'];
	if ( isset( $_listing['icon'] ) ) {
		$post_type_icon = $_listing['icon']['icon'];
	}
}
?>
<form action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="get" data-trigger="filter" data-action="<?php echo esc_attr( $action ); ?>">
	<div class="filter filter-sidebar ajax-filter">

		<?php
			/**
			 * Hook: stm_listings_filter_before.
			 *
			 * @hooked stm_listings_parent_list_response - 10
			 * @hooked stm_listings_filter_nonce_response - 15
			 */
			do_action( 'stm_listings_filter_before' );
		?>

		<?php if ( ! apply_filters( 'stm_is_dealer_two', false ) && ! apply_filters( 'stm_is_motorcycle', false ) ) : ?>
			<div class="sidebar-entry-header">
				<i class="<?php echo esc_attr( $post_type_icon ); ?>"></i>
				<span class="h4"><?php esc_html_e( 'Search Options', 'motors' ); ?></span>
			</div>
		<?php else : ?>
			<div class="sidebar-entry-header">
				<span class="h4"><?php esc_html_e( 'Search', 'motors' ); ?></span>
				<a class="heading-font" href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); // phpcs:ignore WordPress.Security ?>">
					<?php esc_html_e( 'Reset All', 'motors' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="row row-pad-top-24">

			<?php if ( empty( $filter['filters'] ) ) : ?>
				<div class="col-md-12 col-sm-12">
					<p class="text-muted text-center">
						<?php
						printf(
						/* translators: %s post type name */
							esc_html__( 'No categories created for %s', 'motors' ),
							esc_html( $post_type_name )
						);
						?>
					</p>
				</div>
				<?php
			else :
				do_action(
					'stm_listings_load_template',
					'filter/types/keywords',
					array(
						'position' => 'top',
					)
				);
				$parent_list = apply_filters( 'stm_listings_parent_list', false );
				if ( ! is_array( $parent_list ) ) {
					$parent_list = array();
				}
				foreach ( $filter['filters'] as $attribute => $config ) :

					if ( 'price' === $attribute && ! empty( $config['slider'] ) && ! empty( $config['numeric'] ) ) {
						continue;
					}

					if ( ! empty( $filter['options'][ $attribute ] ) ) :
						if ( ! empty( $config['slider'] ) && ! empty( $config['numeric'] ) ) :
							do_action(
								'stm_listings_load_template',
								'filter/types/slider',
								array(
									'taxonomy' => $config,
									'options'  => $filter['options'][ $attribute ],
								)
							);
						else :
							?>
								<div class="col-md-12 col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
									<div class="form-group">
										<?php
										do_action(
											'stm_listings_load_template',
											'filter/types/select',
											array(
												'options'  => $filter['options'][ $attribute ],
												'name'     => $attribute,
												'is_parent' => in_array( $attribute, $parent_list, true ),
												'multiple' => array_key_exists( 'is_multiple_select', $config ) ? $config['is_multiple_select'] : false,
											)
										);
										?>
									</div>
								</div>
							<?php
						endif;
					endif;
				endforeach;
				?>

				<?php
				if ( $show_sold && 'listings-sold' !== $action ) :
					$listing_status = apply_filters( 'stm_listings_input', '', 'listing_status' );
					?>
					<div class="col-md-12 col-sm-12 stm-filter_listing_status">
						<div class="form-group">
							<select name="listing_status" aria-label="<?php esc_attr_e( 'Select listing status', 'motors' ); ?>" class="form-control">
								<option value="">
									<?php esc_html_e( 'Listing status', 'motors' ); ?>
								</option>
								<option value="active" <?php selected( $listing_status, 'active' ); ?>>
									<?php esc_html_e( 'Active', 'motors' ); ?>
								</option>
								<option value="sold" <?php selected( $listing_status, 'sold' ); ?>>
									<?php esc_html_e( 'Sold', 'motors' ); ?>
								</option>
							</select>
						</div>
					</div>
				<?php endif; ?>

				<?php
				do_action( 'stm_listings_load_template', 'filter/types/location' );

				do_action(
					'stm_listings_load_template',
					'filter/types/features',
					array(
						'taxonomy' => 'stm_additional_features',
					)
				);

				do_action(
					'stm_listings_load_template',
					'filter/types/keywords',
					array(
						'position' => 'bottom',
					)
				);
			endif;
			?>

		</div>

		<!--View type-->
		<input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'view_type' ) ); ?>"/>
		<!--Filter links-->
		<input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
		<!--Popular-->
		<input type="hidden" name="popular" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'popular' ) ); ?>"/>
		<input type="hidden" name="s" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 's' ) ); ?>"/>
		<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>

		<?php if ( ! empty( $filter['filters'] ) ) : ?>
			<div class="sidebar-action-units">
				<input id="stm-classic-filter-submit" class="hidden" type="submit" value="<?php esc_html_e( 'Show cars', 'motors' ); ?>"/>

				<a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>" class="button"><span><?php esc_html_e( 'Reset all', 'motors' ); // phpcs:ignore WordPress.Security ?></span></a>
			</div>
		<?php endif; ?>

		<?php do_action( 'stm_listings_filter_after' ); ?>
	</div>

	<!--Classified price-->
	<?php
	if ( ! empty( $filter['options']['price'] ) && ! empty( $filter['filters']['price']['slider'] ) && ! empty( $filter['filters']['price']['numeric'] ) ) {
		do_action(
			'stm_listings_load_template',
			'filter/types/price',
			array(
				'taxonomy' => 'price',
				'options'  => $filter['options']['price'],
			)
		);
	}
	?>

	<?php
	if ( ! apply_filters( 'stm_is_aircrafts', true ) ) {
		do_action( 'stm_listings_load_template', 'filter/types/checkboxes', array( 'filter' => $filter ) );
		do_action( 'stm_listings_load_template', 'filter/types/links', array( 'filter' => $filter ) );
	}
	?>

</form>
