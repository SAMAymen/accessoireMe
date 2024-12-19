<?php
$middle_infos     = apply_filters( 'stm_get_car_archive_listings', array() );
$stm_car_location = get_post_meta( get_the_ID(), 'stm_car_location', true );
$car              = get_post( get_the_ID() );
$distance         = '';

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

	$middle_infos['location'] = array(
		'single_name' => esc_html__( 'Location', 'motors' ),
		'slug'        => 'stm_car_location',
		'font'        => 'stm-boats-icon-pin',
		'numeric'     => 'true',
	);
	if ( ! empty( $distance ) ) {
		$middle_infos['location']['value'] = $distance;
	}
	?>

	<div class="meta-middle">
		<?php foreach ( $middle_infos as $middle_info ) : ?>
			<?php

			if ( empty( $middle_info['value'] ) ) {
				$data_meta = get_post_meta( get_the_id(), $middle_info['slug'], true );
			} else {
				$data_meta = $middle_info['value'];
			}
			$data_value = '';
			?>
			<?php
			if ( ! apply_filters( 'is_empty_value', $data_meta ) && '' !== $data_meta && 'price' !== $middle_info['slug'] ) :

				if ( ! empty( $middle_info['numeric'] ) && $middle_info['numeric'] ) :

					if ( ! empty( $middle_info['use_delimiter'] ) ) {
						$data_meta = number_format( abs( $data_meta ), 0, '', ' ' );
					}
					$data_value_affix = '';
					if ( isset( $middle_info['number_field_affix'] ) ) {
						$data_value_affix = $middle_info['number_field_affix'];
					}

					$data_value = ucfirst( $data_meta );
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

			<?php if ( ! apply_filters( 'is_empty_value', $data_value ) && '' !== $data_value ) : ?>
				<?php if ( 'price' !== $middle_info['slug'] && ! apply_filters( 'is_empty_value', $data_value ) ) : ?>
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
							<div class="name">
								<?php
								if ( is_array( $data_value ) ) {
									if ( count( $data_value ) > 1 ) {
										?>

										<div
											class="stm-tooltip-link"
											data-toggle="tooltip"
											data-placement="top"
											title="<?php echo esc_attr( implode( ', ', $data_value ) ); ?>">
											<?php echo wp_kses_post( $data_value[0] ) . ' <span class="stm-dots">...</span>'; ?>
										</div>

										<?php
									} else {
										echo esc_attr( implode( ', ', $data_value ) );
									}
								} else {
									echo esc_attr( $data_value . $data_value_affix );
								}
								?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div class="meta-bottom">
		<?php get_template_part( 'partials/listing-cars/listing-directive-list-loop', 'actions' ); ?>
	</div>
<?php endif; ?>
