<?php
global $post;

$labels = apply_filters( 'stm_get_car_listings', array() );

if ( ! empty( $labels ) && is_array( $labels ) ) {
	$labels = array_filter(
		$labels,
		function( $label ) use ( $post ) {
			$label_meta = $post->__get( $label['slug'] );

			return ( '' !== $label_meta && 'none' !== $label_meta && 'price' !== $label['slug'] );
		}
	);
}


if ( ! empty( $labels ) ) :
	?>
	<div class="car-meta-bottom">
		<ul>
			<?php
			foreach ( $labels as $label ) :
				$label_meta = $post->__get( $label['slug'] );
				?>
					<li>
						<?php if ( ! empty( $label['font'] ) ) : ?>
							<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
						<?php endif; ?>

						<span class="stm_label">
							<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $label['single_name'], 'Motos Grid Label Name' ) ); ?>:
						</span>

					<?php
					if ( ! empty( $label['numeric'] ) ) :
						$affix = '';
						if ( ! empty( $label['number_field_affix'] ) ) {
							$affix = $label['number_field_affix'];
						}

						if ( ! empty( $label['use_delimiter'] ) ) {
							$label_meta = number_format( abs( $label_meta ), 0, '', ' ' );
						}
						?>
							<span><?php echo esc_html( $label_meta . ' ' . $affix ); ?></span>
							<?php
							else :
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

								if ( ! empty( $datas ) ) :
									if ( count( $datas ) > 1 ) :
										?>
									<span
										class="stm-tooltip-link"
										data-toggle="tooltip"
										data-placement="bottom"
										title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
										<?php echo esc_html( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
									</span>

									<?php else : ?>
									<span><?php echo esc_html( implode( ', ', $datas ) ); ?></span>
										<?php
									endif;
								endif;
								?>

						<?php endif; ?>
					</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
