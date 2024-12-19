<?php
/**
 * Created by PhpStorm.
 * User: NDA
 * Date: 03.01.2018
 * Time: 10:38
 */

$id       = get_the_ID();
$category = stm_motors_get_terms_array( $id, 'category', 'name', false );
$date     = get_the_date( 'd M Y', $id );

$comments_count = wp_count_comments( $id );
$post_views     = get_post_meta( $id, 'stm_car_views', true );
$post_views     = ( ! empty( $post_views ) ) ? $post_views : '0';
?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php echo esc_attr( post_class( 'stm_magazine_single_list no_deco' ) ); ?>>
	<div class="magazine-list-img">
		<?php the_post_thumbnail( 'stm-img-255' ); ?>
		<div class='fa-round'><i class='fa fa-share'></i></div>
	</div>
	<div class="stm-magazine-loop-data">
		<h3 class="top-content"><?php the_title(); ?></h3>
		<div class="middle-content">
			<?php if ( isset( $category[0] ) ) : ?>
				<div class="magazine-category normal-font">
					<?php echo esc_html( $category[0] ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $date ) ) : ?>
				<div class="magazine-loop-date">
					<i class="stm-icon stm-icon-ico_mag_calendar"></i>
					<div class="normal-font"><?php echo esc_attr( $date ); ?></div>
				</div>
			<?php endif; ?>
			<div class="magazine-loop-reviews">
				<i class="stm-icon-ico_mag_reviews"></i>
				<div class="normal-font"><?php echo esc_html( $comments_count->total_comments ); ?></div>
			</div>
			<div class="magazine-loop-views">
				<i class="stm-icon-ico_mag_eye"></i>
				<div class="normal-font"><?php echo esc_html( $post_views ); ?></div>
			</div>
		</div>
		<div class="bottom-content">
			<?php the_excerpt(); ?>
		</div>
	</div>
</a>
