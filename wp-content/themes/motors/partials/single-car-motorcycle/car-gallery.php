<?php
$sold             = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );
$cars_in_compare  = apply_filters( 'stm_get_compared_items', array() );
$show_compare     = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_compare' );
$badge_text       = get_post_meta( get_the_ID(), 'badge_text', true );
$special_car      = get_post_meta( get_the_ID(), 'special_car', true );
$badge_bg_color   = get_post_meta( get_the_ID(), 'badge_bg_color', true );
$badge_style      = '';

// remove "special" if the listing is sold
if ( ! empty( $sold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors' );
}

if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

?>

<?php if ( ! has_post_thumbnail() && stm_check_if_car_imported( get_the_id() ) ) : ?>
	<img
		src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/automanager_placeholders/plchldr798automanager.png' ); ?>"
		class="img-responsive"
		alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
		/>
<?php endif; ?>

<div class="stm-car-carousels stm-listing-car-gallery">
	<!--Actions-->
	<div class="stm-gallery-actions">
		<?php if ( ! empty( $show_compare ) ) : ?>
			<?php
				$active = '';
			if ( ! empty( $cars_in_compare ) ) {
				if ( in_array( get_the_ID(), $cars_in_compare, true ) ) {
					$active = 'active';
				}
			}
			?>
			<div class="stm-gallery-action-unit compare <?php echo esc_attr( $active ); ?>" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ); ?>" data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>">
				<i class="stm-service-icon-compare-new"></i>
				<span class="heading-font"><?php esc_html_e( 'Compare', 'motors' ); ?></span>
			</div>
		<?php endif; ?>
	</div>

	<?php stm_get_boats_image_hover( get_the_ID() ); ?>
	<div class="stm-big-car-gallery owl-carousel">

		<?php
		if ( has_post_thumbnail() ) :
			$full_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full' );
			// Post thumbnail first
			?>
			<div class="stm-single-image" data-id="big-image-<?php echo esc_attr( get_post_thumbnail_id( get_the_id() ) ); ?>">

				<?php if ( empty( $sold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>
					<div class="special-label special-label-small h6" <?php echo esc_attr( $badge_style ); ?>>
						<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
					</div>
				<?php elseif ( apply_filters( 'stm_sold_status_enabled', false ) && ! empty( $sold ) ) : ?>
					<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
					<div class="special-label special-label-small h6" <?php echo esc_attr( $badge_style ); ?>>
						<?php esc_html_e( 'Sold', 'motors' ); ?>
					</div>
				<?php endif; ?>

				<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
					<?php the_post_thumbnail( 'stm-img-1110-577', array( 'class' => 'img-responsive' ) ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="stm-single-image" data-id="big-image-0">
				<img
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Motor-big.jpg' ); ?>"
					class="img-responsive"
					alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
					/>
			</div>
		<?php endif; ?>

	</div>
</div>


<!--Enable carousel-->
<script>
	jQuery(document).ready(function($){
		var big = jQuery('.stm-big-car-gallery');
		var small = jQuery('.stm-thumbs-car-gallery');
		var flag = false;
		var duration = 800;

		big
			.owlCarousel({
				items: 1,
				smartSpeed: 800,
				dots: false,
				nav: false,
				margin:0,
				autoplay: false,
				loop: false,
				responsiveRefreshRate: 1000
			})
			.on('changed.owl.carousel', function (e) {
				jQuery('.stm-thumbs-car-gallery .owl-item').removeClass('current');
				jQuery('.stm-thumbs-car-gallery .owl-item').eq(e.item.index).addClass('current');
				if (!flag) {
					flag = true;
					small.trigger('to.owl.carousel', [e.item.index, duration, true]);
					flag = false;
				}
			});

		small
			.owlCarousel({
				items: 5,
				smartSpeed: 800,
				dots: false,
				margin: 22,
				autoplay: false,
				nav: true,
				navElement: 'div',
				loop: false,
				navText: [],
				responsiveRefreshRate: 1000,
				responsive:{
					0:{
						items:2
					},
					500:{
						items:4
					},
					768:{
						items:5
					},
					1000:{
						items:5
					}
				}
			})
			.on('click', '.owl-item', function(event) {
				big.trigger('to.owl.carousel', [jQuery(this).index(), 400, true]);
			})
			.on('changed.owl.carousel', function (e) {
				if (!flag) {
					flag = true;
					big.trigger('to.owl.carousel', [e.item.index, duration, true]);
					flag = false;
				}
			});

		if(jQuery('.stm-thumbs-car-gallery .stm-single-image').length < 6) {
			jQuery('.stm-single-car-page .owl-controls').hide();
			jQuery('.stm-thumbs-car-gallery').css({'margin-top': '22px'});
		}

		jQuery('.stm-big-car-gallery .owl-dots').remove();
		jQuery('.stm-big-car-gallery .owl-nav').remove();
	})
</script>
