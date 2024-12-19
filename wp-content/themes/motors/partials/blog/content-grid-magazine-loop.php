<?php
$category = stm_motors_get_terms_array( get_the_ID(), 'category', 'name', false );
$date     = get_the_date( 'd M', get_the_ID() );
?>
<div class="col-md-6 col-sm-6 col-xs-12">
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php echo esc_attr( post_class( 'stm_magazine_single_grid no_deco' ) ); ?>>
		<div class="magazine-grid-img">
			<?php the_post_thumbnail( 'stm-img-398' ); ?>
		</div>
		<div class="stm-magazine-loop-data">
			<?php if ( isset( $category[0] ) ) : ?>
				<div class="magazine-category heading-font">
					<?php echo esc_html( $category[0] ); ?>
				</div>
			<?php endif; ?>
			<div class="news-meta-wrap">
				<h3 class="ttc">
					<?php the_title(); ?>
				</h3>
				<div class="left">
					<?php if ( ! empty( $date ) ) : ?>
						<div class="magazine-loop-Date">
							<i class="icon-ico_mag_reviews"></i>
							<div><?php echo esc_attr( $date ); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="right"></div>
			</div>
		</div>
	</a>
</div>
