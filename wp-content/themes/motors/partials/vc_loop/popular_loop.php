<?php
$post_views = get_post_meta( get_the_ID(), 'stm_car_views', true );
$post_views = ( ! empty( $post_views ) ) ? $post_views : '0';
?>
<a href="<?php the_permalink(); ?>" class="stm-magazine-news clearfix">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="image">
			<?php the_post_thumbnail( 'stm-img-190-132' ); ?>
		</div>
	<?php endif; ?>
	<div class="stm-post-content">
		<div class="title heading-font">
			<?php echo wp_kses_post( wp_trim_words( get_the_title(), 8, '...' ) ); ?>
		</div>
		<div class="recomended-data">
			<?php $com_num = get_comments_number( get_the_id() ); ?>
			<div class="comments-number normal-font">
				<i class="stm-icon-ico_mag_reviews"></i><?php echo ( ! empty( $com_num ) ) ? esc_attr( $com_num ) : '0'; ?>
			</div>
			<div class="magazine-loop-views">
				<i class="stm-icon-ico_mag_eye"></i>
				<div class="normal-font"><?php echo esc_html( $post_views ); ?></div>
			</div>
		</div>
	</div>
</a>
