<?php

$middle_infos     = apply_filters( 'stm_get_car_archive_listings', array() );
$middle_infos[]   = 'location';
$total_infos      = count( $middle_infos );
$stm_car_location = get_post_meta( get_the_ID(), 'stm_car_location', true );

/*Get distance value*/
$car      = get_post( get_the_ID() );
$distance = '';

if ( isset( $car->stm_distance ) ) {
	$distance_affix   = apply_filters( 'stm_distance_measure_unit', '' );
	$distance_measure = apply_filters( 'stm_distance_measure_unit_value', '' );
	$distance         = $car->stm_distance;
	if ( 'kilometers' !== $distance_measure ) {
		$distance = $distance / 1.609344;
	}
	$distance = round( $distance, 1 ) . ' ' . $distance_affix;
}

if ( ! empty( $middle_infos ) ) : ?>
	<?php foreach ( $middle_infos as $middle_info ) : ?>

		<?php if ( 'location' !== $middle_info ) : ?>
			<?php
			$data_meta  = get_post_meta( get_the_id(), $middle_info['slug'], true );
			$data_value = '';
			?>
			<?php
			if ( '' !== $data_meta && 'none' !== $data_meta && 'price' !== $middle_info['slug'] ) :
				if ( ! empty( $middle_info['numeric'] ) && $middle_info['numeric'] ) :
					$affix = '';
					if ( ! empty( $middle_info['number_field_affix'] ) ) {
						$affix = $middle_info['number_field_affix'];
					}

					if ( ! empty( $middle_info['use_delimiter'] ) ) {
						if ( is_numeric( $data_meta ) ) {
							$data_meta = floatval( $data_meta );
							$data_meta = number_format( abs( $data_meta ), 0, '', ' ' );
						}
					}

					$data_value = ucfirst( $data_meta ) . ' ' . $affix;
				else :
					$data_meta_array = explode( ',', $data_meta );
					$data_value      = array();

					if ( ! empty( $data_meta_array ) ) {
						foreach ( $data_meta_array as $data_meta_single ) {
							$data_meta = get_the_terms( get_the_ID(), $middle_info['slug'] );

							if ( ! empty( $data_meta ) && ! is_wp_error( $data_meta ) ) {
								foreach ( $data_meta as $data_metas ) {
									$data_value[] = esc_attr( $data_metas->name );
								}
							}
							break;
						}
					}

				endif;

			endif;
			?>
		<?php endif; ?>

		<?php
		if ( 'location' === $middle_info ) :
			$data_value = '';
			?>
			<?php if ( ! empty( $stm_car_location ) || ! empty( $distance ) ) : ?>
				<div class="meta-middle-unit font-exists location">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="stm-service-icon-pin_big"></i></div>
						<div class="name"><?php esc_html_e( 'Distance', 'motors' ); ?></div>
					</div>

					<div class="value">
						<?php if ( ! empty( $distance ) ) : ?>
							<div
									class="stm-tooltip-link"
									data-toggle="tooltip"
									data-placement="bottom"
									title="<?php echo esc_attr( $distance ); ?>">
								<?php echo esc_html( $distance ); ?>
							</div>

						<?php else : ?>
							<div
									class="stm-tooltip-link"
									data-toggle="tooltip"
									data-placement="bottom"
									title="<?php echo esc_attr( $stm_car_location ); ?>">
								<?php echo esc_html( $stm_car_location ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="meta-middle-unit meta-middle-divider"></div>

			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $data_value ) ) : ?>
			<?php if ( 'price' !== $middle_info['slug'] && ! empty( $data_value ) ) : ?>
				<div class="meta-middle-unit 
				<?php
				if ( ! empty( $middle_info['font'] ) ) {
					echo esc_attr( 'font-exists' );
				}
				?>
				<?php echo ' ' . esc_attr( $middle_info['slug'] ); ?>">
					<div class="meta-middle-unit-top">
						<?php if ( ! empty( $middle_info['font'] ) ) : ?>
							<div class="icon"><i class="<?php echo esc_attr( $middle_info['font'] ); ?>"></i></div>
						<?php endif; ?>
						<div class="name"><?php echo esc_html( $middle_info['single_name'] ); ?></div>
					</div>

					<div class="value h5">
						<?php
						if ( is_array( $data_value ) ) {
							if ( count( $data_value ) > 1 ) {
								?>

								<div
										class="stm-tooltip-link"
										data-toggle="tooltip"
										data-placement="bottom"
										title="<?php echo esc_attr( implode( ', ', $data_value ) ); ?>">
									<?php echo esc_html( $data_value[0] ) . ' <span class="stm-dots">...</span>'; ?>
								</div>

								<?php
							} else {
								echo esc_html( implode( ', ', $data_value ) );
							}
						} else {
							echo esc_html( $data_value );
						}
						?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
