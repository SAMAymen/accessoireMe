<?php
if ( ! empty( $user_features ) ) {
	$items = $user_features;
}

if ( $items ) {
	if ( ! empty( $id ) ) {
		$features_car = get_post_meta( $id, 'additional_features', true );
		$features_car = explode( ',', addslashes( $features_car ) );
	} else {
		$features_car = array();
	}

	foreach ( $items as $item ) { ?>
		<?php if ( isset( $item['tab_title_single'] ) ) : ?>
			<div class="stm-single-feature">
				<div class="heading-font"><?php echo esc_html( $item['tab_title_single'] ); ?></div>
				<?php
				$features = array();

				if ( isset( $item['tab_title_labels'] ) && ! empty( $item['tab_title_labels'] ) ) {
					$features = explode( ',', $item['tab_title_labels'] );
				} elseif ( ! empty( $item['tab_title_selected_labels'] ) ) {
					$features = $item['tab_title_selected_labels'];
				}
				?>
				<?php if ( ! empty( $features ) ) : ?>
					<?php foreach ( $features as $feature ) : ?>
						<div class="feature-single">
							<label>
								<?php
								if ( is_array( $feature ) ) {
									?>
									<input type="checkbox" value="<?php echo esc_attr( $feature['value'] ); ?>"
										name="stm_car_features_labels[]"
										<?php checked( in_array( $feature['value'], $features_car, true ) ); ?>
									>
									<span><?php echo esc_attr( $feature['label'] ); ?></span>
									<?php
								} else {
									$checked = in_array( $feature, $features_car, true ) ? 'checked' : '';
									?>
									<input type="checkbox" value="<?php echo esc_attr( $feature ); ?>"
										name="stm_car_features_labels[]" <?php echo esc_attr( $checked ); ?>/>
									<span><?php echo esc_attr( $feature ); ?></span>
								<?php } ?>
							</label>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
	}
}
