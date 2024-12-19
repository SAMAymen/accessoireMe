<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $unit
 */

$listing_rows_numbers_default_expanded = 'false';
if ( isset( $unit['listing_rows_numbers_default_expanded'] ) && 'open' === $unit['listing_rows_numbers_default_expanded'] ) {
	$listing_rows_numbers_default_expanded = 'true';
}
?>


<div class="stm-accordion-single-unit stm-modern-filter-unit-images <?php echo esc_attr( $unit['slug'] ); ?>">
	<a class="title <?php echo ( 'false' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'collapsed' : ''; ?>"
		data-toggle="collapse"
		href="#<?php echo esc_attr( $unit['slug'] ); ?>"
		aria-expanded="<?php echo esc_attr( $listing_rows_numbers_default_expanded ); ?>">
		<h5><?php echo esc_html( $unit['single_name'] ); ?></h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="collapse content <?php echo ( 'true' === esc_attr( $listing_rows_numbers_default_expanded ) ) ? 'in' : ''; ?>"
			id="<?php echo esc_attr( $unit['slug'] ); ?>">
			<div class="stm-accordion-content-wrapper">
				<div class="stm-single-unit-wrapper">
					<?php $number_of_images = 0; ?>
					<?php
					$images = 0;
					foreach ( $terms as $img_term ) {
						$images++;
						?>
						<?php if ( ! empty( $_GET[ $unit['slug'] ] ) && $_GET[ $unit['slug'] ] === $img_term->slug ) { ?>
						<script>
							jQuery(window).on('load', function () {
								var $ = jQuery;
								$('input[name="<?php echo esc_attr( $img_term->slug . '-' . $img_term->term_id ); ?>"]').trigger('click');
								$.uniform.update();
							});
						</script>
							<?php
						}

						$image = get_term_meta( $img_term->term_id, 'stm_image', true );
						if ( ! empty( $image ) ) {
							$image = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
							if ( false !== $image && ! empty( $image[0] ) ) {
								$category_image = $image[0];
							}
						}

						if ( ! empty( $image ) ) {
							$number_of_images++;
							?>
						<div class="stm-single-unit-image">
							<label>
									<?php if ( ! empty( $category_image ) ) { ?>
									<span class="image">
										<img class="img-reponsive"
											src="<?php echo esc_url( $category_image ); ?>"
											alt="<?php esc_attr_e( 'Brand', 'motors' ); ?>"/>
									</span>
								<?php } ?>
								<input type="checkbox"
										name="<?php echo esc_attr( $img_term->slug . '-' . $img_term->term_id ); ?>"
										data-name="<?php echo esc_attr( $img_term->name ); ?>"
								/>
								<span class="checkbox_title">
									<?php echo esc_attr( $img_term->name ); ?>
								</span>
							</label>
						</div>
						<?php
						}
					}

					if ( $number_of_images < count( $terms ) ) {
						?>
						<div class="stm-modern-view-others">
							<a href=""><?php echo esc_html_e( 'View all', 'motors' ); ?></a>
						</div>
						<div class="stm-modern-filter-others">
							<?php
							$non_images = 0;
							foreach ( $terms as $stm_term ) {
								$non_images++;
								?>

								<?php
								$image = get_term_meta( $stm_term->term_id, 'stm_image', true );
								if ( ! empty( $image ) ) {
									$image          = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
									$category_image = $image[0];
								}

								if ( empty( $image ) ) {
									?>
									<div class="stm-single-unit-image stm-no-image">
										<label>
											<input type="checkbox"
													name="<?php echo esc_attr( $stm_term->slug . '-' . $stm_term->term_id ); ?>"
													data-name="<?php echo esc_attr( $stm_term->name ); ?>"
											/>
											<?php echo esc_attr( $stm_term->name ); ?>
										</label>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
