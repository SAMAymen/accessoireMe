<?php
$gallery_video = get_post_meta(get_the_ID(), 'gallery_video', true);

if(!empty($gallery_video)): ?>
	<span class="video-preview fancy-iframe" data-url="<?php echo esc_url($gallery_video); ?>"><i class="fas fa-film"></i><?php esc_html_e('Video', 'motors'); ?></span>
<?php endif; ?>