<?php
$filter_opt       = apply_filters( 'stm_get_single_car_listings', array() );
$data             = apply_filters( 'stm_single_car_data', $filter_opt );
$show_compare     = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_compare' );
$stm_car_location = get_post_meta( get_the_ID(), 'stm_car_location', true );
$cars_in_compare  = apply_filters( 'stm_get_compared_items', array() );
?>

<?php if ( ! empty( $data ) ) : ?>
	<div class="single-boat-data-units">
		<div class="single-boat-data">
			<?php foreach ( $data as $data_value ) : ?>
				<?php if ( ! apply_filters( 'stm_is_listing_price_field', true, $data_value['slug'] ) ) : ?>
					<?php $data_meta = get_post_meta( get_the_ID(), $data_value['slug'], true ); ?>
					<?php if ( ! apply_filters( 'is_empty_value', $data_meta ) && '' !== $data_meta ) : ?>
						<div class="t-row">
							<div class="t-label">
								<?php if ( ! empty( $data_value['font'] ) ) : ?>
									<i class="<?php echo esc_attr( $data_value['font'] ); ?>"></i>
								<?php endif; ?>
								<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $data_value['single_name'], 'Single listing ' . $data_value['single_name'] ) ); ?>
							</div>
							<?php if ( ! empty( $data_value['numeric'] ) && $data_value['numeric'] ) : ?>
								<div class="t-value h6">
									<?php echo esc_html( ucfirst( $data_meta ) ); ?>
								</div>
							<?php else : ?>
								<?php
								$data_meta_array = explode( ',', $data_meta );
								$datas           = array();

								if ( ! empty( $data_meta_array ) ) {
									foreach ( $data_meta_array as $data_meta_single ) {
										$data_meta = get_term_by( 'slug', $data_meta_single, $data_value['slug'] );
										if ( ! empty( $data_meta->name ) ) {
											$datas[] = esc_attr( $data_meta->name );
										}
									}
								}
								?>
								<div class="t-value h6">
									<?php echo esc_html( implode( ', ', $datas ) ); ?>
								</div>
							<?php endif; ?>
						</div>

					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( ! empty( $stm_car_location ) ) : ?>
				<div class="t-row">
					<div class="t-label">
						<i class="stm-boats-icon-pin"></i>
						<?php esc_html_e( 'Location', 'motors' ); ?>
					</div>
					<div class="t-value h6"><?php echo esc_attr( $stm_car_location ); ?></div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $show_compare ) ) : ?>
			<?php
			$active = '';
			if ( ! empty( $cars_in_compare ) ) {
				if ( in_array( get_the_ID(), $cars_in_compare, true ) ) {
					$active = 'active';
				}
			}
			?>
			<div class="stm-gallery-action-unit compare <?php echo esc_attr( $active ); ?>"
				data-id="<?php echo esc_attr( get_the_ID() ); ?>"
				data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ); ?>"
				data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
				>
				<i class="stm-boats-icon-add-to-compare"></i>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
