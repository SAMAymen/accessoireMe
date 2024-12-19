<?php

$middle_infos = apply_filters( 'stm_get_car_archive_listings', array() );

if ( get_post_type( get_the_ID() ) !== apply_filters( 'stm_listings_post_type', 'listings' ) ) {
	$middle_infos = apply_filters( 'stm_get_listings_filter', array(), get_post_type( get_the_ID() ), array( 'where' => array( 'use_on_car_archive_listing_page' => true ) ), true );
}

$middle_infos[] = 'location';

$total_infos = count( $middle_infos );

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

if ( ! empty( $middle_infos ) ) :
	?>

	<div class="meta-middle">
		<div class="meta-middle-row clearfix">
			<?php
				$counter = 0;
			foreach ( $middle_infos as $middle_info_key => $middle_info ) :

				if ( 'location' !== $middle_info ) :
					$data_meta = get_post_meta( get_the_id(), $middle_info['slug'], true );

					$data_value = '';
					?>
					<?php
					if ( '' !== $data_meta && 'price' !== $middle_info['slug'] ) :
						if ( ! empty( $middle_info['numeric'] ) ) :
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
			endif //location;
				?>

				<?php
				if ( 'location' === $middle_info ) :
					$data_value = '';
					?>
					<?php if ( ! empty( $stm_car_location ) || ! empty( $distance ) ) : ?>
					<div class="meta-middle-unit font-exists location">
						<div class="meta-middle-unit-top">
							<div class="icon"><i class="stm-service-icon-pin_big"></i></div>
							<div class="name"><?php esc_html_e( 'Location', 'motors' ); ?></div>
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
						<?php $counter ++; ?>
				<?php endif; ?>
			<?php endif; ?>

				<?php if ( ! empty( $data_value ) && '' !== $data_value ) : ?>
					<?php if ( 'price' !== $middle_info['slug'] && ! empty( $data_value ) ) : ?>
						<?php $counter ++; ?>
				<div class="meta-middle-unit 
						<?php
						if ( ! empty( $middle_info['font'] ) ) {
							echo esc_attr( 'font-exists' );
						}
						echo ' ' . esc_attr( $middle_info['slug'] );
						?>
				">
					<div class="meta-middle-unit-top">
						<?php if ( ! empty( $middle_info['font'] ) ) : ?>
							<div class="icon"><i class="<?php echo esc_attr( $middle_info['font'] ); ?>"></i></div>
						<?php endif; ?>
						<div class="name"><?php echo esc_html( $middle_info['single_name'] ); ?></div>
					</div>

					<div class="value">
						<?php
						if ( is_array( $data_value ) ) {
							if ( count( $data_value ) > 1 ) {
								?>
								<div
										class="stm-tooltip-link"
										data-toggle="tooltip"
										data-placement="bottom"
										title="<?php echo esc_attr( implode( ', ', $data_value ) ); ?>">
									<?php echo esc_attr( implode( ', ', $data_value ) ); ?>
								</div>
								<?php
							} else {
								echo esc_attr( implode( ', ', $data_value ) );
							}
						} else {
							echo esc_attr( $data_value );
						}
						?>
					</div>
				</div>
				<div class="meta-middle-unit meta-middle-divider"></div>
			<?php endif; ?>


					<?php if ( $counter % 4 == 0 ) : //phpcs:ignore?>
		</div>
						<?php
						$row_no_filled = $total_infos - ( $counter + 1 );
						if ( $row_no_filled < 5 ) {
							$row_no_filled = 'stm-middle-info-not-filled';
						} else {
							$row_no_filled = '';
						}
						?>
		<div class="meta-middle-row <?php echo esc_attr( $row_no_filled ); ?> clearfix">
			<?php endif; ?>

			<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
