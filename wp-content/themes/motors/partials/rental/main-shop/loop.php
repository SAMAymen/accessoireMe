<?php
global $product;
$has_gallery = ( ! empty( $product->get_gallery_image_ids() ) ) ? true : false;
$col_1       = '';
$col_2       = 'col-md-12 col-sm-12';

if ( has_post_thumbnail() ) {
	$col_1 = 'col-md-4 col-sm-4 first';
	$col_2 = 'col-md-8 col-sm-8 second';
}

$s_title        = get_post_meta( get_the_ID(), 'cars_info', true );
$car_info       = stm_get_car_rent_info( get_the_ID() );
$excerpt        = get_the_excerpt( get_the_ID() );
$current_car    = stm_get_cart_items();
$order_values   = stm_get_rental_order_fields_values();
$order_days     = ( ! empty( $order_values['order_days'] ) ) ? $order_values['order_days'] : 0;
$current_car_id = 0;

if ( ! empty( $current_car['car_class'] ) ) {
	if ( ! empty( $current_car['car_class']['id'] ) ) {
		$current_car_id = $current_car['car_class']['id'];
	}
}

$current_car = '';

if ( get_the_ID() === intval( $current_car_id ) ) {
	$current_car = 'current_car';
}
$dates           = ( isset( $_COOKIE[ 'stm_calc_pickup_date_' . get_current_blog_id() ] ) ) ? stm_check_order_available( get_the_ID(), sanitize_text_field( $_COOKIE[ 'stm_calc_pickup_date_' . get_current_blog_id() ] ), sanitize_text_field( $_COOKIE[ 'stm_calc_return_date_' . get_current_blog_id() ] ) ) : array();
$rent_allow_data = apply_filters( 'get_max_min_days', get_the_ID(), $order_days );
$disable_car     = count( $dates ) > 0 || ! $rent_allow_data['allow'] ? 'stm-disable-car' : '';
$already_booked  = count( $dates ) > 0 ? true : false;

?>

<div class="stm_single_class_car <?php echo esc_attr( $current_car ); ?> <?php echo esc_attr( $disable_car ); ?>" id="product-<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="row">
		<div class="<?php echo esc_attr( $col_1 ); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="image">
					<?php the_post_thumbnail( 'stm-img-350', array( 'class' => 'img-responsive' ) ); ?>
					<?php if ( $has_gallery ) : ?>
						<div class="stm-rental-photos-unit stm-car-photos-<?php echo esc_attr( get_the_ID() ); ?>">
							<i class="stm-boats-icon-camera"></i>
							<span class="heading-font"><?php echo count( $product->get_gallery_image_ids() ); ?></span>
						</div>
						<script>
							jQuery(document).ready(function(){

								jQuery(".stm-car-photos-<?php echo esc_attr( get_the_ID() ); ?>").on('click', function() {
									jQuery(this).lightGallery({
										dynamic: true,
										dynamicEl: [
											<?php
											foreach ( $product->get_gallery_image_ids() as $car_photo ) :
												$image = wp_get_attachment_image_src( $car_photo, 'full', false, array( 'title' => get_post_field( 'post_title', $car_photo ) ) );
												?>
											{
												src  : "<?php echo esc_url( $image[0] ); ?>",
												thumb: "<?php echo esc_url( $image[0] ); ?>"
											},
											<?php endforeach; ?>
										],
										download: false,
										mode: 'lg-fade',
									})


								});
							});

						</script>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="<?php echo esc_attr( $col_2 ); ?>">
			<div class="row">

				<div class="col-md-6 col-sm-6">
					<div class="top">
						<div class="heading-font">
							<h3><?php the_title(); ?></h3>
							<?php if ( ! empty( $s_title ) ) : ?>
								<div class="s_title">
									<?php echo esc_html( $s_title ); ?>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $car_info ) ) : ?>
								<div class="infos">
									<?php
									foreach ( $car_info as $slug => $info ) :
										$name = $info['value'];
										if ( $info['numeric'] ) {
											$name = $info['value'] . ' ' . esc_html( $info['name'] );
										}
										$font = $info['font'];
										?>
										<div class="single_info stm_single_info_font_<?php echo esc_attr( $font ); ?>">
											<i class="<?php echo esc_attr( $font ); ?>"></i>
											<span><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $name, 'Rental option ' . $name ) ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $excerpt ) ) : ?>
							<div class="stm-more">
								<a href="#">
									<span><?php esc_html_e( 'More information', 'motors' ); ?></span>
									<i class="fas fa-angle-down"></i>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-6 col-sm-6">
					<?php get_template_part( 'partials/rental/main-shop/price' ); ?>
				</div>
				<?php if ( ! empty( $excerpt ) ) : ?>
					<div class="col-md-12 col-sm-12">
						<div class="more">
							<div class="lists-inline">
								<?php echo wp_kses_post( apply_filters( 'the_content', $excerpt ) ); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $disable_car ) ) : ?>
		<div class="stm-enable-car-date">
			<?php
			$formated_dates = array();

			foreach ( $dates as $val ) {
				$formated_dates[] = stm_get_locale_formated_date( $val, '%d %B' );
			}
			?>
			<?php if ( true === $already_booked ) : ?>
			<h3><?php echo esc_html__( 'This Class is already booked in: ', 'motors' ) . "<span class='yellow'>" . implode( '<span>,</span> ', $formated_dates ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>.</h3>
			<?php else : ?>
				<h3><?php echo esc_html( $rent_allow_data['message'] ); ?><span class="yellow"><?php echo esc_html( $rent_allow_data['days'] ); ?></span></h3>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
