<?php
// Getting gallery list
$listing_id = get_the_ID();

$gallery       = get_post_meta( $listing_id, 'gallery', true );
$video_preview = get_post_meta( $listing_id, 'video_preview', true );
$gallery_video = get_post_meta( $listing_id, 'gallery_video', true );

$sold             = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );

// remove "special" if the listing is sold
if ( ! empty( $sold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

$special_car = get_post_meta( $listing_id, 'special_car', true );

$badge_text     = get_post_meta( $listing_id, 'badge_text', true );
$badge_bg_color = get_post_meta( $listing_id, 'badge_bg_color', true );

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors' );
}

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

$placeholder_path = 'plchldr350.png';
if ( apply_filters( 'stm_is_boats', false ) ) {
	$placeholder_path = 'boats-placeholders/Boat-big.jpg';
} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$placeholder_path = 'Plane-big-wide.jpg';
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$placeholder_path = 'Motor-big.jpg';
}

$use_slider = 'yes';

if ( empty( $gallery ) && has_post_thumbnail() && empty( $video_preview ) ) {
	$use_slider = 'no';
}

?>

<?php if ( ! has_post_thumbnail() && stm_check_if_car_imported( $listing_id ) ) : ?>
	<img
			src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>"
			class="img-responsive"
			alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
	/>
<?php endif; ?>


<div class="stm-car-carousels">

	<!--New badge with videos-->
	<?php $car_media = apply_filters( 'stm_get_car_medias', array(), $listing_id ); ?>
	<?php if ( ! empty( $car_media['car_videos_count'] ) && $car_media['car_videos_count'] > 0 ) : ?>
		<div class="stm-car-medias">
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo esc_attr( get_the_id() ); ?>">
				<i class="fas fa-film"></i>
				<span><?php echo esc_html( $car_media['car_videos_count'] ); ?><?php esc_html_e( 'Video', 'motors' ); ?></span>
			</div>
		</div>

	<?php //phpcs:disable ?>
		<script>
            jQuery(document).ready(function () {

                jQuery(".stm-car-videos-<?php echo get_the_id(); ?>").on('click', function () {
                    jQuery(this).lightGallery({
                        dynamic: true,
                        dynamicEl: [
							<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
                            {
                                src: "<?php echo esc_url( $car_video ); ?>"
                            },
							<?php endforeach; ?>
                        ],
                        download: false,
                        mode: 'lg-fade',
                    })
                }); //click
            }); //ready

		</script>
	<?php //phpcs:enable ?>
	<?php endif; ?>

	<?php if ( empty( $sold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
			<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
		</div>
	<?php elseif ( apply_filters( 'stm_sold_status_enabled', false ) && ! empty( $sold ) ) : ?>
		<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
			<?php esc_html_e( 'Sold', 'motors' ); ?>
		</div>
	<?php endif; ?>

	<div class="stm-big-car-gallery owl-carousel">

		<?php
		if ( has_post_thumbnail() ) :
			$full_src = wp_get_attachment_image_src( get_post_thumbnail_id( $listing_id ), 'full' );
			// Post thumbnail first
			?>
			<div class="stm-single-image" data-id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
				<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
					<?php the_post_thumbnail( 'stm-img-796-466', array( 'class' => 'img-responsive' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( empty( $gallery ) && empty( $video_preview ) && empty( $gallery_video ) ) : ?>
			<div class="stm-single-image" data-id="big-image">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>" class="img-responsive" alt="Placeholder"/>
			</div>
		<?php endif; ?>

		<?php if ( empty( $gallery ) && empty( $video_preview ) && ! empty( $gallery_video ) ) : ?>
			<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
				<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path ); ?>" class="img-responsive" alt="Placeholder"/>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) && empty( $gallery ) ) : ?>
			<?php $src = wp_get_attachment_image_src( $video_preview, 'stm-img-796-466' ); ?>
			<?php if ( ! empty( $src[0] ) ) : ?>
				<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
					<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
						<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) && ! empty( $gallery ) ) : ?>
			<?php $src = wp_get_attachment_image_src( $video_preview, 'stm-img-796-466' ); ?>
			<?php if ( ! empty( $src[0] ) ) : ?>
				<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
					<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
						<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive"
							alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $gallery ) ) : ?>
			<?php foreach ( $gallery as $gallery_image ) : ?>
				<?php $src = wp_get_attachment_image_src( $gallery_image, 'stm-img-796-466' ); ?>
				<?php $full_src = wp_get_attachment_image_src( $gallery_image, 'full' ); ?>
				<?php if ( ! empty( $src[0] ) && get_post_thumbnail_id( get_the_ID() ) !== $gallery_image ) : ?>
					<div class="stm-single-image" data-id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
						<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
							<?php /* translators: full title */ ?>
							<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php printf( esc_attr__( '%s full', 'motors' ), esc_attr( get_the_title( $listing_id ) ) ); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( ! empty( $car_media['car_videos_posters'] ) && ! empty( $car_media['car_videos'] ) ) : ?>
			<?php
			foreach ( $car_media['car_videos_posters'] as $k => $val ) :
				$src = wp_get_attachment_image_src( $val, 'stm-img-350' );
				$k ++;
				$video_source = ( isset( $car_media['car_videos'][ $k ] ) ) ? $car_media['car_videos'][ $k ] : '';
				if ( ! empty( $src[0] ) ) :
					?>
					<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $val ); ?>">
						<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $video_source ); ?>">
							<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>


	<?php if ( has_post_thumbnail() || ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) ) : ?>
		<?php if ( 'yes' === $use_slider ) : ?>
		<div class="stm-thumbs-car-gallery owl-carousel">
			<?php
			if ( has_post_thumbnail() ) :
				// Post thumbnail first
				?>
				<div class="stm-single-image" id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
					<?php the_post_thumbnail( 'stm-img-255', array( 'class' => 'img-responsive' ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $gallery ) ) : ?>
				<?php foreach ( $gallery as $gallery_image ) : ?>
					<?php $src = wp_get_attachment_image_src( $gallery_image, 'stm-img-255' ); ?>
					<?php if ( ! empty( $src[0] ) && get_post_thumbnail_id( get_the_ID() ) !== $gallery_image ) : ?>
						<div class="stm-single-image" id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
							<?php /* translators: img alt */ ?>
							<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php printf( esc_attr__( '%s full', 'motors' ), esc_attr( get_the_title( $listing_id ) ) ); ?>"/>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) : ?>
					<?php $src = wp_get_attachment_image_src( $video_preview, 'stm-img-255' ); ?>
					<?php if ( ! empty( $src[0] ) ) : ?>
						<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
							<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
								<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>

			<?php if ( ! empty( $car_media['car_videos_posters'] ) && ! empty( $car_media['car_videos'] ) ) : ?>
				<?php
				foreach ( $car_media['car_videos_posters'] as $k => $val ) :
					$k ++;
					$src          = wp_get_attachment_image_src( $val, 'stm-img-255' );
					$video_source = ( isset( $car_media['car_videos'][ $k ] ) ) ? $car_media['car_videos'][ $k ] : '';
					if ( ! empty( $src[0] ) ) :
						?>
						<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
							<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $video_source ); ?>">
								<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	<?php endif; ?>

</div>


<!--Enable carousel-->
		<?php if ( 'yes' === $use_slider ) : ?>
<script>
jQuery(document).ready(function ($) {
	var big = jQuery('.stm-big-car-gallery');
	var small = jQuery('.stm-thumbs-car-gallery');
	var flag = false;
	var duration = 800;

	var owlRtl = false;
	if (jQuery('body').hasClass('rtl')) {
		owlRtl = true;
	}

	big
		.owlCarousel({
			rtl: owlRtl,
			items: 1,
			smartSpeed: 800,
			dots: false,
			nav: false,
			margin: 0,
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
			rtl: owlRtl,
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
			responsive: {
				0: {
					items: 2
				},
				500: {
					items: 4
				},
				768: {
					items: 5
				},
				1000: {
					items: 5
				}
			}
		})
		.on('click', '.owl-item', function (event) {
			big.trigger('to.owl.carousel', [jQuery(this).index(), 400, true]);
		});

	if (jQuery('.stm-thumbs-car-gallery .stm-single-image').length < 6) {
		jQuery('.stm-single-car-page .owl-controls').hide();
		jQuery('.stm-thumbs-car-gallery').css({'margin-top': '22px'});
	}

	jQuery('.stm-big-car-gallery .owl-dots').remove();
	jQuery('.stm-big-car-gallery .owl-nav').remove();

	jQuery('.stm-thumbs-car-gallery .owl-nav .owl-prev').on('click', function (e) {
		big.trigger('prev.owl.carousel');
	});

	jQuery('.stm-thumbs-car-gallery .owl-nav .owl-next').on('click', function (e) {
		big.trigger('next.owl.carousel');
	});
})
</script>
	<?php endif; ?>

	<?php endif; ?>
