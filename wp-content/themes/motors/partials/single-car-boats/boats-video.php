<?php

	//Getting gallery list
	$video_preview = get_post_meta(get_the_id(), 'video_preview', true);
	$gallery_video = get_post_meta(get_the_id(), 'gallery_video', true);

	$gallery_videos = get_post_meta(get_the_id(), 'gallery_videos', true);
	$gallery_videos_posters = get_post_meta(get_the_id(), 'gallery_videos_posters', true);
?>


<div class="stm-car-carousels">
	<div class="stm-big-car-gallery owl-carousel">

		<?php if(!empty($video_preview) and !empty($gallery_video)): ?>
			<?php $src = wp_get_attachment_image_src($video_preview, 'stm-img-796-466'); ?>
			<?php if(!empty($src[0])): ?>
				<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr($video_preview); ?>">
					<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video); ?>">
						<img src="<?php echo esc_url($src[0]); ?>" class="img-responsive" alt="<?php esc_attr_e('Video preview', 'motors'); ?>"/>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(!empty($gallery_videos) and !empty($gallery_videos_posters)): ?>
			<?php foreach($gallery_videos as $gallery_key => $gallery_video_single): ?>
				<?php if(!empty($gallery_videos_posters[$gallery_key])): ?>
					<?php $src = wp_get_attachment_image_src($gallery_videos_posters[$gallery_key], 'stm-img-796-466'); ?>
					<?php if(!empty($src[0])): ?>
						<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr($gallery_videos_posters[$gallery_key]); ?>">
							<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video_single); ?>">
								<img src="<?php echo esc_url($src[0]); ?>" class="img-responsive" alt="<?php esc_attr_e('Video preview', 'motors'); ?>"/>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>

	<?php if( !empty($video_preview) and !empty($gallery_video) ): ?>
		<div class="stm-thumbs-car-gallery owl-carousel">

			<?php if(!empty($video_preview) and !empty($gallery_video)): ?>
				<?php $src = wp_get_attachment_image_src($video_preview, 'stm-img-350-205'); ?>
				<?php if(!empty($src[0])): ?>
					<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr($video_preview); ?>">
						<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video); ?>">
							<img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e('Video preview', 'motors'); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if(!empty($gallery_videos) and !empty($gallery_videos_posters)): ?>
				<?php foreach($gallery_videos as $gallery_key => $gallery_video_single): ?>
					<?php if(!empty($gallery_videos_posters[$gallery_key])): ?>
						<?php $src = wp_get_attachment_image_src($gallery_videos_posters[$gallery_key], 'stm-img-350-205'); ?>
						<?php if(!empty($src[0])): ?>
							<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr($gallery_videos_posters[$gallery_key]); ?>">
								<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video_single); ?>">
									<img src="<?php echo esc_url($src[0]); ?>" class="img-responsive" alt="<?php esc_attr_e('Video preview', 'motors'); ?>"/>
								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	<?php endif; ?>
</div>

<!--Enable carousel-->
<script>
	jQuery(document).ready(function($){
		var big = $('.stm-big-car-gallery');
		var small = $('.stm-thumbs-car-gallery');
		var flag = false;
		var duration = 800;

		var owlRtl = false;
		if( $('body').hasClass('rtl') ) {
			owlRtl = true;
		}

		big
			.owlCarousel({
				rtl: owlRtl,
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
				$('.stm-thumbs-car-gallery .owl-item').removeClass('current');
				$('.stm-thumbs-car-gallery .owl-item').eq(e.item.index).addClass('current');
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
				big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
			})
			.on('changed.owl.carousel', function (e) {
				if (!flag) {
					flag = true;
					big.trigger('to.owl.carousel', [e.item.index, duration, true]);
					flag = false;
				}
			});

		if($('.stm-thumbs-car-gallery .stm-single-image').length < 6) {
			$('.stm-single-car-page .owl-controls').hide();
			$('.stm-thumbs-car-gallery').css({'margin-top': '22px'});
		}

		$('.vc_tta-tab').on('click', function(){
			setTimeout(function(){
				big.trigger('destroy.owl.carousel');
				big.html(big.find('.owl-stage-outer').html()).removeClass('owl-loaded');

				big
					.owlCarousel({
						rtl: owlRtl,
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
						$('.stm-thumbs-car-gallery .owl-item').removeClass('current');
						$('.stm-thumbs-car-gallery .owl-item').eq(e.item.index).addClass('current');
						if (!flag) {
							flag = true;
							small.trigger('to.owl.carousel', [e.item.index, duration, true]);
							flag = false;
						}
					});

				small.trigger('destroy.owl.carousel');
				small.html(small.find('.owl-stage-outer').html()).removeClass('owl-loaded');

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
						big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
					})
					.on('changed.owl.carousel', function (e) {
						if (!flag) {
							flag = true;
							big.trigger('to.owl.carousel', [e.item.index, duration, true]);
							flag = false;
						}
					});

                $('.fancy-iframe').lightGallery({
                    selector: 'this',
                    iframeMaxWidth: '80%',
                    mode: 'lg-fade',
                    download: false
                })
			}, 200);
		});
	})
</script>