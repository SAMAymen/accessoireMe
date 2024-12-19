<?php
$labels = apply_filters( 'stm_get_car_listings', array() );

if ( ! empty( $labels ) ) :
	?>
	<div class="car-meta-bottom">
		<ul>
			<?php foreach ( $labels as $label ) : ?>
				<?php $label_meta = get_post_meta( get_the_id(), $label['slug'], true ); ?>
				<?php if ( '' !== $label_meta && 'none' !== $label_meta && 'price' !== $label['slug'] ) : ?>
					<li>
						<?php if ( ! empty( $label['font'] ) ) : ?>
							<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
						<?php endif; ?>

						<?php
						if ( ! empty( $label['numeric'] ) ?? $label['numeric'] ) :
							$affix = '';
							if ( ! empty( $label['number_field_affix'] ) ) {
								$affix = $label['number_field_affix'];
							}

							if ( ! empty( $label['use_delimiter'] ) ) {
								$label_meta = number_format( abs( $label_meta ), 0, '', ' ' );
							}
							?>
							<span><?php echo esc_html( $label_meta . ' ' . $affix ); ?></span>
						<?php else : ?>

							<?php
							$data_meta_array = explode( ',', $label_meta );
							$datas           = array();

							if ( ! empty( $data_meta_array ) ) {
								foreach ( $data_meta_array as $data_meta_single ) {
									$data_meta = get_term_by( 'slug', $data_meta_single, $label['slug'] );
									if ( ! empty( $data_meta->name ) ) {
										$datas[] = $data_meta->name;
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
									<?php
								}
								?>
							<?php endif; ?>

						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
