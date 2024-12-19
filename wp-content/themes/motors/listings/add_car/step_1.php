<?php
/**
 * @var $use_inputs
 * @var $stm_histories
 * @var $id
 * @var $taxonomy
*/

$data = apply_filters( 'stm_get_single_car_listings', array() );

$terms_args = array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => false,
	'fields'     => 'all',
	'pad_counts' => true,
);

?>
<div class="stm_add_car_form_1">
	<div class="stm-car-listing-data-single stm-border-top-unit ">
		<div class="title heading-font"><?php esc_html_e( 'Listing Item Details', 'motors' ); ?></div>
		<span class="step_number step_number_1 heading-font"><?php esc_html_e( 'step', 'motors' ); ?> 1</span>
	</div>

	<?php if ( ! empty( $taxonomy ) ) : ?>
		<div class="stm-form1-intro-unit">
			<div class="row">
				<?php
				foreach ( $taxonomy as $tax ) :
					$tax_info = apply_filters( 'stm_vl_get_all_by_slug', array(), $tax );

					$terms = array();

					if ( empty( $tax_info['listing_taxonomy_parent'] ) ) {
						$terms = apply_filters( 'stm_get_category_by_slug_all', array(), $tax, true );
					}

					$has_selected = '';

					if ( ! empty( $id ) ) {
						if ( isset( $tax_info['slug'] ) ) {
							$post_terms = wp_get_post_terms( $id, $tax_info['slug'] );
							if ( ! empty( $post_terms[0] ) ) {
								$has_selected = $post_terms[0]->slug;
							} elseif ( ! empty( $tax_info['slug'] ) ) {
								$has_selected = get_post_meta( $id, $tax_info['slug'], true );
							}
						}
					}

					$number_field = false;

					if ( $use_inputs && ! empty( $tax_info['numeric'] ) ) {
						$number_field = true;
					}
					?>
					<?php if ( ! empty( $tax_info ) ) : ?>
					<div class="col-md-3 col-sm-3 stm-form-1-selects">
						<div class="stm-label heading-font"><?php echo esc_html( stm_get_name_by_slug( $tax ) ); ?>*</div>
						<?php
						if ( $number_field ) :
							$value       = get_post_meta( $id, $tax_info['slug'], true );
							$placeholder = sprintf(
								/* translators: %1$s single name */
								esc_attr__( 'Enter %1$s', 'motors' ),
								$tax_info['single_name']
							);
							?>
							<input
									aria-label="<?php echo esc_attr( $placeholder ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									min="0"
									type="number"
									name="stm_f_s[<?php echo esc_attr( $tax ); ?>]"
									required />
							<?php
						else :
							$single_name = sprintf(
								/* translators: %s name option */
								esc_html__( 'Select %s', 'motors' ),
								esc_html( stm_get_name_by_slug( $tax ) )
							);
							?>
							<select class="add_a_car-select add_a_car-select-<?php echo esc_attr( $tax ); ?>"
									aria-label="<?php echo esc_attr( $single_name ); ?>"
									data-class="stm_select_overflowed"
									data-selected="<?php echo esc_attr( $has_selected ); ?>"
									name="stm_f_s[<?php echo esc_attr( str_replace( '-', '_pre_', $tax ) ); ?>]"
									required="required"
							>
								<option value="" <?php selected( $has_selected, '' ); ?>>
									<?php echo esc_html( $single_name ); ?>
								</option>
								<?php
								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
									foreach ( $terms as $term ) :
										?>
										<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $has_selected, $term->slug ); ?>>
											<?php echo esc_html( $term->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<style type="text/css">
			<?php foreach ( $taxonomy as $tax ) : //phpcs:disable ?>

            .stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', stm_get_name_by_slug($tax), 'Add A Car Step 1 Slug Name' ) ); ?>"] {
                background-color: transparent !important;
                border: 1px solid rgba(255, 255, 255, 0.5);
                color: #fff !important;
            }

            .stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', stm_get_name_by_slug($tax), 'Add A Car Step 1 Slug Name' ) ); ?>"] + .select2-selection__arrow b {
                color: rgba(255, 255, 255, 0.5);
            }

			<?php endforeach; //phpcs:enable ?>
		</style>
	<?php endif; ?>

	<div class="stm-form-1-end-unit clearfix">
		<?php if ( ! empty( $data ) && is_array( $taxonomy ) ) : ?>
			<?php foreach ( $data as $data_key => $data_unit ) : ?>
				<?php
				if ( ! in_array( $data_unit['slug'], $taxonomy, true ) ) :
					$tax_info = apply_filters( 'stm_vl_get_all_by_slug', array(), $data_unit['slug'] );
					$terms = array();
					if ( empty( $tax_info['listing_taxonomy_parent'] ) ) {
						$terms = get_terms( $data_unit['slug'], $terms_args );
					}

					$is_required = ( isset( $data_unit['required_filed'] ) && $data_unit['required_filed'] ) ? 'required' : '';
					?>
					<div class="stm-form-1-quarter">
						<?php
						if ( ! empty( $data_unit['numeric'] ) ) :
							$value = '';
							if ( ! empty( $id ) ) {
								$value = get_post_meta( $id, $data_unit['slug'], true );
							}

							$placeholder = sprintf(
								/* translators: %1$s single name, %2$s field affix */
								esc_attr__( 'Enter %1$s %2$s', 'motors' ),
								$data_unit['single_name'],
								( ! empty( $data_unit['number_field_affix'] ) ) ? '(' . $data_unit['number_field_affix'] . ')' : ''
							);
							?>
							<input
									type="number"
									class="form-control <?php echo ( ! empty( $value ) ) ? 'stm_has_value' : ''; ?>"
									name="stm_s_s_<?php echo esc_attr( $data_unit['slug'] ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									aria-label="<?php echo esc_attr( $placeholder ); ?>"
									placeholder="<?php echo esc_attr( $placeholder ); ?>"
									<?php echo esc_attr( $is_required ); ?>
							/>
							<?php
							else :
								$single_name = sprintf(
									/* translators: %1$s single name */
									esc_attr__( 'Select %1$s', 'motors' ),
									$data_unit['single_name']
								);
								$selected = '';
								if ( ! empty( $id ) ) {
									$selected = get_post_meta( $id, $data_unit['slug'], true );
								}
								?>
							<select name="stm_s_s_<?php echo esc_attr( $data_unit['slug'] ); ?>"
									data-selected="<?php echo esc_attr( $selected ); ?>"
									aria-label="<?php echo esc_attr( $single_name ); ?>"
									class="add_a_car-select add_a_car-select-<?php echo esc_attr( $data_unit['slug'] ); ?>" <?php echo esc_attr( $is_required ); ?>>
								<option value="" <?php selected( $selected, '' ); ?>>
									<?php echo esc_html( $single_name ); ?>
								</option>
								<?php
								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
									foreach ( $terms as $term ) :
										?>
										<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected, $term->slug ); ?>>
											<?php echo esc_html( $term->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						<?php endif; ?>
						<div class="stm-label">
							<?php if ( ! empty( $data_unit['font'] ) ) : ?>
								<i class="<?php echo esc_attr( $data_unit['font'] ); ?>"></i>
							<?php endif; ?>
							<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $data_unit['single_name'], 'Add A Car Step 1 Taxonomy Label ' . $data_unit['single_name'] ) ); ?>
							<?php
							if ( isset( $data_unit['required_filed'] ) && $data_unit['required_filed'] ) {
								echo '*';
							}
							?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

			<style type="text/css">
				<?php foreach ( $data as $data_unit ) : //phpcs:disable ?>

                .stm-form-1-end-unit .select2-selection__rendered[title="<?php echo esc_attr__('Select', 'motors'); ?> <?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $data_unit['single_name'], 'Add A Car Step 1 Taxonomy Label' ) ); ?>"] {
                    background-color: transparent !important;
                    border: 1px solid rgba(255, 255, 255, 0.5);
                    color: #888 !important;
                }

				<?php endforeach; //phpcs:enable ?>
			</style>

			<?php
			do_action(
				'stm_listings_load_template',
				'add_car/step_1_additional_fields',
				array(
					'histories' => $stm_histories,
					'post_id'   => $id,
				)
			);
			?>

			<?php
			$data_value            = get_post_meta( $id, 'stm_car_location', true );
			$data_value_lat        = get_post_meta( $id, 'stm_lat_car_admin', true );
			$data_value_lng        = get_post_meta( $id, 'stm_lng_car_admin', true );
			$data_location_address = get_post_meta( $id, 'stm_location_address', true );
			?>

			<div class="stn-add-car-location-wrap">
				<div class="stm-car-listing-data-single">
					<div class="title heading-font"><?php esc_html_e( 'Listing item Location', 'motors' ); ?></div>
				</div>
				<div class="stm-form-1-quarter stm_location stm-location-search-unit">
					<div class="stm-location-input-wrap stm-location">
						<div class="stm-label">
							<i class="stm-service-icon-pin_2"></i>
							<?php esc_html_e( 'Location', 'motors' ); ?>
						</div>
						<input type="text"
								name="stm_location_text"
								aria-label="<?php esc_attr_e( 'Enter ZIP or Address', 'motors' ); ?>"
								<?php
								if ( ! empty( $data_value ) ) :
									echo 'class="stm_has_value"';
									endif
								?>
								id="stm-add-car-location" value="<?php echo esc_attr( $data_value ); ?>"
								placeholder="<?php esc_attr_e( 'Enter ZIP or Address', 'motors' ); ?>"/>
					</div>
					<div class="stm-location-input-wrap stm-lng">
						<div class="stm-label">
							<i class="stm-service-icon-pin_2"></i>
							<?php esc_html_e( 'Latitude', 'motors' ); ?>
						</div>
						<input type="text"
							class="text_stm_lat"
							aria-label="<?php esc_attr_e( 'Enter Latitude', 'motors' ); ?>"
							name="stm_lat"
							value="<?php echo esc_attr( $data_value_lat ); ?>"
							placeholder="<?php esc_attr_e( 'Enter Latitude', 'motors' ); ?>"/>
					</div>
					<div class="stm-location-input-wrap stm-lng">
						<div class="stm-label">
							<i class="stm-service-icon-pin_2"></i>
							<?php esc_html_e( 'Longitude', 'motors' ); ?>
						</div>
						<input type="text"
							class="text_stm_lng"
							aria-label="<?php esc_attr_e( 'Enter Longitude', 'motors' ); ?>"
							name="stm_lng"
							value="<?php echo esc_attr( $data_value_lng ); ?>"
							placeholder="<?php esc_attr_e( 'Enter Longitude', 'motors' ); ?>"/>
					</div>
					<div class="stm-link-lat-lng-wrap">
						<a href="https://www.latlong.net/" target="_blank"><?php echo esc_html__( 'Lat and Long Finder', 'motors' ); ?></a>
					</div>
					<input type="hidden" name="stm_location_address" id="stm_location_address" value="<?php echo esc_attr( $data_location_address ); ?>">
				</div>
			</div>

		<?php endif; ?>
	</div>
</div>
