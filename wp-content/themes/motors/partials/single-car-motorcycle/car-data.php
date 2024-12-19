<?php
$filter_opt        = apply_filters( 'stm_get_single_car_listings', array() );
$data              = apply_filters( 'stm_single_car_data', $filter_opt );
$post_id           = get_the_ID();
$vin_num           = get_post_meta( get_the_id(), 'vin_number', true );
$show_vin          = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_vin' );
$show_registered   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_registered' );

if ( $show_registered ) {
	$registered_date = get_post_meta( $post_id, 'registration_date', true );
}

?>

<?php if ( ! empty( $data ) ) : ?>
	<div class="single-car-data">
		<?php
		$show_certified_logo_1 = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_certified_logo_1' );
		$show_certified_logo_2 = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_certified_logo_2' );

		/*If automanager, and no image in admin, set default image carfax*/
		$history_link_1   = get_post_meta( get_the_ID(), 'history_link', true );
		$certified_logo_1 = get_post_meta( get_the_ID(), 'certified_logo_1', true );
		if ( stm_check_if_car_imported( get_the_ID() ) && empty( $certified_logo_1 ) && ! empty( $history_link_1 ) ) {
			$certified_logo_1 = 'automanager_default';
		}

		if ( ! empty( $certified_logo_1 ) && $show_certified_logo_1 ) :
			if ( 'automanager_default' === $certified_logo_1 ) {
				$certified_logo_1    = array();
				$certified_logo_1[0] = get_stylesheet_directory_uri() . '/assets/images/carfax.png';
			} else {
				$certified_logo_1 = wp_get_attachment_image_src( $certified_logo_1, 'stm-img-255' );
			}
			if ( ! empty( $certified_logo_1[0] ) ) {
				$certified_logo_1 = $certified_logo_1[0];
				?>
				<div class="text-center stm-single-car-history-image">
					<a href="<?php echo esc_url( $history_link_1 ); ?>" target="_blank">
						<img src="<?php echo esc_url( $certified_logo_1 ); ?>" class="img-responsive dp-in" />
					</a>
				</div>
				<?php
			}
		endif;

		$history_link_2   = get_post_meta( get_the_ID(), 'certified_logo_2_link', true );
		$certified_logo_2 = get_post_meta( get_the_ID(), 'certified_logo_2', true );
		if ( stm_check_if_car_imported( get_the_ID() ) && empty( $certified_logo_2 ) && ! empty( $history_link_2 ) ) {
			$certified_logo_2 = 'automanager_default';
		}

		if ( ! empty( $certified_logo_2 ) && $show_certified_logo_2 ) :
			if ( 'automanager_default' === $certified_logo_2 ) {
				$certified_logo_2    = array();
				$certified_logo_2[0] = get_stylesheet_directory_uri() . '/assets/images/carfax.png';
			} else {
				$certified_logo_2 = wp_get_attachment_image_src( $certified_logo_2, 'full' );
			}
			if ( ! empty( $certified_logo_2[0] ) ) {
				$certified_logo_2 = $certified_logo_2[0];
				?>
				<div class="text-center stm-single-car-history-image">
					<a href="<?php echo esc_url( $history_link_2 ); ?>" target="_blank">
						<img src="<?php echo esc_url( $certified_logo_2 ); ?>" class="img-responsive dp-in"/>
					</a>
				</div>
				<?php
			}
		endif;
		?>
		<table>
			<?php foreach ( $data as $data_value ) : ?>
				<?php
					$affix = '';
				if ( ! empty( $data_value['number_field_affix'] ) ) {
					$affix = $data_value['number_field_affix'];
				}
				?>

				<?php if ( 'price' !== $data_value['slug'] ) : ?>
					<?php $data_meta = get_post_meta( $post_id, $data_value['slug'], true ); ?>
					<?php if ( ! empty( $data_meta ) ) : ?>
						<tr>
							<td class="t-label heading-font"><?php echo esc_html( $data_value['single_name'] ); ?></td>
							<?php if ( ! empty( $data_value['numeric'] ) && $data_value['numeric'] ) : ?>
								<td class="t-value h6"><?php echo esc_attr( ucfirst( $data_meta . $affix ) ); ?></td>
							<?php else : ?>
								<?php
									$data_meta_array = explode( ',', $data_meta );
									$datas           = array();

								if ( ! empty( $data_meta_array ) ) {
									foreach ( $data_meta_array as $data_meta_single ) {
										$data_meta = get_term_by( 'slug', $data_meta_single, $data_value['slug'] );
										if ( ! empty( $data_meta->name ) ) {
											$datas[] = esc_attr( $data_meta->name ) . $affix;
										}
									}
								}
								?>
								<td class="t-value h6"><?php echo implode( ', ', $datas );//phpcs:ignore ?></td>
							<?php endif; ?>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<!--VIN NUMBER-->
			<?php if ( ! empty( $vin_num ) && $show_vin ) : ?>
				<tr>
					<td class="t-label"><?php esc_html_e( 'Vin', 'motors' ); ?></td>
					<td class="t-value t-vin h6"><?php echo esc_attr( $vin_num ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( ! empty( $registered_date ) && $show_registered ) : ?>
				<tr>
					<td class="t-label"><?php esc_html_e( 'Registered', 'motors' ); ?></td>
					<td class="t-value t-vin h6"><?php echo esc_attr( $registered_date ); ?></td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
<?php endif; ?>
