<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$unique_class    = 'ev_gallery_' . wp_rand( 1, 99999 );
$car_media       = apply_filters( 'stm_get_car_medias', array(), get_the_ID() );
$show_compare    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_compare' );
$show_share      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_share' );
$show_calculator = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_calculator' );
$show_vin        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_vin' );
$show_test_drive = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_test_drive' );
$show_pdf        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_pdf' );
$car_brochure    = get_post_meta( get_the_ID(), 'car_brochure', true );
$vin_number      = get_post_meta( get_the_ID(), 'vin_number', true );
$plchldr         = 'plchldr350.png';
?>

<div class="stm_gallery_with_buttons <?php echo esc_attr( $css_class ); ?>">

	<div class="ev_gallery image">
		<div class="share_compare">
			<div class="share_wrap">
				<?php if ( $show_share ) : ?>
					<div class="gallery_btn stm-shareble">
						<i class="fas fa-share-alt"></i>
						<span>
							<?php esc_html_e( 'Share this', 'motors-wpbakery-widgets' ); ?>
						</span>
						<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) : ?>
						<div class="stm-a2a-popup">
							<?php echo wp_kses_post( apply_filters( 'stm_add_to_any_shortcode', get_the_ID() ) ); ?>
						</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="compare_wrap">
				<?php if ( $show_compare ) : ?>
					<div class="gallery_btn stm-listing-compare"
					data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
					data-id="<?php echo esc_attr( get_the_id() ); ?>"
					data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
					data-placement="bottom"
					>
						<i class="stm-icon-add"></i>
						<i class="stm-icon-remove"></i>
						<span class="add"><?php esc_html_e( 'Add to compare', 'motors-wpbakery-widgets' ); ?></span>
						<span class="remove"><?php esc_html_e( 'Remove from list', 'motors-wpbakery-widgets' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="gallery_overlay <?php echo esc_attr( $unique_class ); ?>">
			<div class="lightbox_trigger">
				<div class="fa-stack">
					<i class="stm-icon-light-zoom-in fa-stack-2x"></i>
					<i class="stm-icon-hexagon-fill fa-stack-1x"></i>
				</div>
			</div>
		</div>
		<?php get_template_part( 'partials/listing-cars/listing-directory', 'badges' ); ?>
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'stm-img-770-417', array( 'class' => 'img-responsive' ) ); ?>
		<?php else : ?>
			<img
			src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>"
			class="img-responsive"
			alt="<?php esc_attr_e( 'Placeholder', 'motors-wpbakery-widgets' ); ?>"
			/>
		<?php endif; ?>
	</div>

	<div class="gallery-buttons">

		<?php if ( $show_calculator ) : ?>
			<div class="gallery_action_btn">
				<a href="#!" data-toggle="modal" data-target="#get-car-calculator" class="button button-lg">
					<i class="stm-icon-percentage"></i>
					<?php esc_html_e( 'Loan calculator', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php
		if ( defined( 'STM_MOTORS_VIN_DECODERS_PATH' ) && $show_vin ) :
			if ( ! has_action( 'wp_footer', 'stm_vin_add_modal' ) ) {
				add_action( 'wp_footer', 'stm_vin_add_modal' );
			}
			?>
			<div class="gallery_action_btn">
				<a href="#!" class="button button-lg report_button" data-vin="<?php echo esc_attr( $vin_number ); ?>">
					<i class="far fa-check-square"></i>
					<?php esc_html_e( 'VIN report', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( $show_test_drive ) : ?>
			<div class="gallery_action_btn">
				<a href="#!" class="button button-lg" data-toggle="modal" data-target="#test-drive">
					<i class="stm-icon-steering-wheel-thin"></i>
					<?php esc_html_e( 'Test drive', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $car_brochure ) && $show_pdf ) : ?>
			<div class="gallery_action_btn">
				<a href="<?php echo esc_url( wp_get_attachment_url( $car_brochure ) ); ?>" class="button button-lg" download>
					<i class="stm-icon-brochures"></i>
					<?php esc_html_e( 'Car brochure', 'motors-wpbakery-widgets' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</div>

<!--Enable carousel-->
<script>
	jQuery(document).ready(function(){
		jQuery(".<?php echo esc_attr( $unique_class ); ?>").on('click', function(e) {
			e.preventDefault();
			jQuery(this).lightGallery({
				dynamic: true,
				dynamicEl: [
					<?php if ( ! empty( $car_media['car_photos'] ) ) : ?>
						<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
						{
							src  : "<?php echo esc_url( $car_photo ); ?>",
							thumb: "<?php echo esc_url( $car_photo ); ?>"
						},
						<?php endforeach; ?>
					<?php else : ?>
						{
							src : "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>",
							thumb: "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $plchldr ); ?>"
						}
					<?php endif; ?>
				],
				download: false,
				mode: 'lg-fade',
			})
		});
	});
</script>
