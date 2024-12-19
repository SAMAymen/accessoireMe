<?php
/**
 *
 * @var $post_id
 * @var $post_title
 * @var $post_content
 * @var $thumbnail_url
 * @var $button_text_post
 * @var $button_url_post
 * @var $status_click
 * @var $status_views
 * */
$img = empty($thumbnail_url) ? 'not-image' : ''
?>

<div class="popup-dash-promo show" data-id="<?php echo esc_attr( $post_id ); ?>" data-status-click="<?php echo esc_attr( $status_click ); ?>" data-status-views="<?php echo esc_attr( $status_views ); ?>">
	<div class="popup-dash-promo-content <?php echo $img; ?>">
		<?php if ( ! empty( $thumbnail_url ) ) { ?>
		<div class="popup-dash-promo-content-item">
			<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="">
		</div>
		<?php } ?>
		<div class="popup-dash-promo-content-item">
			<div class="popup-dash-close">
				<img src="<?php echo STM_ADMIN_NOTICES_URL . 'assets/img/close.svg'; ?>" alt="">
			</div>
			<div class="popup-dash-title">
				<?php echo esc_html( $post_title ); ?>
			</div>
			<div class="popup-dash-desc">
				<?php echo $post_content; ?>
			</div>
			<div class="popup-dash-actions">
				<?php if ( !empty( $button_text_post ) ) { ?>
					<a href="<?php echo esc_url( $button_url_post ); ?>" target="_blank" data-id="<?php echo esc_attr( $post_id ); ?>" class="stm-dash-btn">
					<?php echo esc_html( $button_text_post ); ?>
					</a>
				<?php } ?>
				<a href="#" data-id="<?php echo esc_attr( $post_id ); ?>" class="not-show-again">
					Not show me again
				</a>
			</div>
		</div>
	</div>
</div>
