<?php
$features = get_post_meta( get_the_id(), 'additional_features', true );
$features = ( ! empty( $features ) ) ? explode( ',', $features ) : '';

if ( ! empty( $features ) ) : ?>
	<div class="stm-single-listing-car-features">
		<div class="lists-inline">
			<ul class="list-style-2" style="font-size: 13px;">
				<?php foreach ( $features as $key => $feature ) : ?>
					<?php if ( 0 === $key % 4 && 0 !== $key ) : ?>
						</ul>
						<ul class="list-style-2" style="font-size: 13px;">
					<?php endif; ?>
				<li><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $feature, 'Car feature ' . $feature ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
