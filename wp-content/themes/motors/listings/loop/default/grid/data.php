<?php
$labels = apply_filters( 'stm_get_car_listings', array() );

if ( ! empty( $labels ) ) :
	?>
	<div class="car-meta-bottom">
		<ul>
			<?php foreach ( $labels as $label ) : ?>
				<?php $label_meta = get_post_meta( get_the_id(), $label['slug'], true ); ?>
				<?php if ( '' !== $label_meta && 'price' !== $label['slug'] ) : ?>
					<li>
						<?php if ( ! empty( $label['font'] ) ) : ?>
							<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
						<?php endif; ?>
						<?php
						if ( ! empty( $label['numeric'] ) && $label['numeric'] ) :
							$affix = '';
							if ( ! empty( $label['number_field_affix'] ) ) {
								$affix = $label['number_field_affix'];
							}

							if ( ! empty( $label['use_delimiter'] ) ) {
								if ( is_numeric( $label_meta ) ) {
									$label_meta = floatval( $label_meta );
									$label_meta = number_format( abs( $label_meta ), 0, '', ' ' );
								}
							}
							?>
							<span><?php echo esc_html( stripslashes( $label_meta . ' ' . $affix ) ); ?></span>
						<?php else : ?>

							<?php
							$data_meta_array = explode( ',', $label_meta );
							$datas           = array();

							if ( ! empty( $data_meta_array ) ) {
								$data_meta = get_the_terms( get_the_ID(), $label['slug'] );

								if ( ! empty( $data_meta ) && ! is_wp_error( $data_meta ) ) {
									foreach ( $data_meta as $data_metas ) {
										$datas[] = esc_attr( $data_metas->name );
									}
								}
							}
							?>

							<?php if ( ! empty( $datas ) ) : ?>
								<?php
								if ( count( $datas ) > 1 ) {
									?>

									<span
											class="stm-tooltip-link"
											data-toggle="tooltip"
											data-placement="bottom"
											title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
														<?php echo esc_html( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
													</span>

								<?php } else { ?>
									<span><?php echo esc_html( implode( ', ', $datas ) ); ?></span>
								<?php } ?>
							<?php endif; ?>

						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
endif;
