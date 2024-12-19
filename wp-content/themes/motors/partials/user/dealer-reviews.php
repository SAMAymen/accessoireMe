<?php
	$user = get_queried_object();
	$user_id = $user->ID;
	$ratings = stm_get_dealer_marks($user_id);
?>

<?php if(!empty($ratings['average'])): ?>
	<div class="stm-dealer-review-tab">
		<h4><?php esc_html_e('Dealer Rating', 'motors'); ?></h4>
		<div class="stm-dealer-rating clearfix">
			<div class="stm-dealer-average">
				<h5 class="text-center"><?php esc_html_e('Average rating', 'motors'); ?></h5>
				<div class="heading-font stm-average-number">
					<span class="stm-first"><?php echo esc_attr($ratings['average']); ?></span>
					<span class="stm-last">/5</span>
				</div>
				<div class="stm-star-rating">
					<div class="inner">
						<div class="stm-star-rating-upper" style="width:<?php echo esc_attr($ratings['average_width']); ?>"></div>
						<div class="stm-star-rating-lower"></div>
					</div>
				</div>
				<div class="stm-label text-center">
					(<?php printf( esc_html__('Based on %d %s', 'motors'), $ratings['count'], esc_html__('ratings', 'motors')); ?>.)
				</div>
			</div>
			<div class="stm-dealer-overall">
				<div class="stm-dealer-overall-inner">
					<?php for($i=1;$i<4;$i++):
						$current_label = 'rate' . $i . '_label';
						$current_rate = 'rate' . $i;
						$current_rate_width = 'rate' . $i . '_width';
						if(!empty($ratings[$current_label]) and !empty($ratings[$current_rate]) and !empty($ratings[$current_rate_width])): ?>
							<div class="stm-dealer-rate-part stm-dealer-rate-part-<?php echo esc_attr($i); ?>">
								<h4><?php echo esc_html($ratings[$current_label], 'motors'); ?></h4>
								<div class="stm-star-rating">
									<div class="inner">
										<div class="stm-star-rating-upper" style="width:<?php echo esc_attr($ratings[$current_rate_width]); ?>"></div>
										<div class="stm-star-rating-lower"></div>
									</div>
									<span><strong><?php echo number_format(esc_attr($ratings[$current_rate]), '1', '.', ''); ?></strong> <?php esc_html_e('out of', 'motors'); ?> 5.0</span>
								</div>
							</div>
						<?php endif; ?>
					<?php endfor; ?>

					<?php if(!empty($ratings['likes']) or !empty($ratings['dislikes'])): ?>
						<div class="stm-dealer-rate-part stm-dealer-rate-part-rec">
							<h4><?php esc_html_e('Recommend', 'motors'); ?></h4>
							<?php if(!empty($ratings['likes'])): ?>
								<div class="dp-in">
									<i class="far fa-thumbs-up"></i>
									<?php esc_html_e('Yes', 'motors'); ?>
									<strong>(<?php echo esc_attr($ratings['likes']); ?>)</strong>
								</div>
							<?php endif; ?>
							<?php if(!empty($ratings['likes'])): ?>
								<div class="dp-in">
									<i class="far fa-thumbs-down"></i>
									<?php esc_html_e('No', 'motors'); ?>
									<strong>(<?php echo esc_attr($ratings['dislikes']); ?>)</strong>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>


	<?php

	/*Reviews*/
	$reviews = stm_get_dealer_reviews($user_id);

	if($reviews->have_posts()): ?>
		<div class="stm-dealer-review-title heading-font"><?php esc_html_e('Reviews', 'motors'); ?><span>(<?php echo esc_attr($reviews->found_posts); ?>)</span></div>
			<div id="stm-dealer-reviews-units">
				<?php while($reviews->have_posts()): $reviews->the_post();
					get_template_part('partials/user/dealer-single', 'review');
				endwhile; ?>
			</div>

		<?php if($reviews->found_posts > 6): ?>
			<div class="stm-load-more-dealer-reviews">
				<a href="#" data-user="<?php echo esc_attr($user_id); ?>" data-offset="6"><span><?php esc_html_e('Show more', 'motors'); ?></span></a>
			</div>
		<?php endif;

	else: ?>
		<h4 class="stm_empty_reviews"><?php esc_html_e('Be the first to write a review!', 'motors'); ?></h4>
<?php endif; ?>
<?php else: ?>

	<h4 class="stm-login-review-leave"><?php esc_html_e('Be the first to write a review!', 'motors'); ?></h4>

<?php endif; ?>