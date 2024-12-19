<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

global $wp_embed;
$embed   = '';
$video_w = 500;
$video_h = $video_w / 1.61;
if ( is_object( $wp_embed ) ) {
	$embed = $wp_embed->run_shortcode( '[embed width="' . $video_w . '"' . $video_h . ']' . $video_url . '[/embed]' );
}

$unique_id = wp_rand( 1, 99999 );

?>
<a href="#" id="youtube-play-video-wrap" data-src="<?php echo esc_attr( $video_url ); ?>" class="youtube-play-video-wrap-<?php echo esc_attr( $unique_id ); ?>">
	<div class="youtube-play-circle" style="background: <?php echo esc_attr( $color ); ?>">
		<i class="fas fa-play"></i>
	</div>
</a>
<div id="video-popup-wrap" class="video-popup-wrap video-popup-wrap-<?php echo esc_attr( $unique_id ); ?>" style="display: none;">
	<div class="video-popup">
		<div class="wpb_video_wrapper">
			<?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>

<script>
	(function($) {
		var yLG = $('.youtube-play-video-wrap-<?php echo esc_attr( $unique_id ); ?>');
		yLG.on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();

			$(this).lightGallery({
				iframe: true,
				youtubePlayerParams: {
					modestbranding: 1,
					showinfo: 0,
					rel: 0,
					controls: 0
				},
				dynamic: true,
				dynamicEl: [{
					src  : $('.video-popup-wrap-<?php echo esc_attr( $unique_id ); ?>').find('iframe').attr('src')
				}],
				download: false,
				mode: 'lg-fade',
			});
		})
	})(jQuery);
</script>
