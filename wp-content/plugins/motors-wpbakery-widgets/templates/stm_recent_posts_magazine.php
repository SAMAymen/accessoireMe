<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $number_of_posts ) ) {
	$number_of_posts = 1;
}

$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => $number_of_posts,
	'ignore_sticky_posts' => true,
);

$r = new WP_Query( $args );

$slide_object = get_category_by_slug( 'slide' );

$args = ( ! empty( $category_selected ) ) ? array(
	'hide_empty' => true,
	'include'    => $category_selected,
) : array(
	'hide_empty' => true,
	'exclude'    => array( $slide_object->term_id ),
);

$cat_list     = get_categories( $args );
$recent_posts = wp_create_nonce( 'stm_ajax_get_recent_posts_magazine' );
$url_ajax     = '&posts_per_page=' . $number_of_posts . '&action=stm_ajax_get_recent_posts_magazine&security=' . $recent_posts;

?>

<div id="stm_widget_recent_news" data-action="<?php echo esc_attr( $url_ajax ); ?>" class="stm_widget_recent_news" >
	<div class="recent-top">
		<div class="left">
			<?php if ( ! empty( $title ) ) : ?>
				<h4><?php echo esc_attr( $title ); ?></h4>
			<?php endif; ?>
		</div>
		<div class="right">
			<ul class="cat-list recent-cat-list">
				<li class="recent_news_cat active" data-slug="all" >
					<span class="heading-font"><?php echo esc_html__( 'All News', 'motors-wpbakery-widgets' ); ?></span>
				</li>
				<?php
				for ( $q = 0;$q < 2;$q++ ) :
					if ( isset( $cat_list[ $q ] ) ) :
						?>
					<li class="recent_news_cat" data-slug="<?php echo esc_html( $cat_list[ $q ]->slug ); ?>">
						<span class="heading-font"><?php echo esc_html( $cat_list[ $q ]->name ); ?></span>
					</li>
									<?php
				endif;
endfor;
				?>
			</ul>
			<?php if ( count( $cat_list ) > 2 ) : ?>
				<div class="recent-show-all">
					<span class="btn-show"></span>
				</div>
				<ul class="recent_hide_categories">
					<?php
					$cats_count = count( $cat_list );
					for ( $c = 2; $c < $cats_count; $c++ ) :
						?>
						<li class="recent_news_cat" data-slug="<?php echo esc_html( $cat_list[ $c ]->slug ); ?>">
							<span class="heading-font"><?php echo esc_html( $cat_list[ $c ]->name ); ?></span>
						</li>
					<?php endfor; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="recentNewsAnimate">
		<?php
		if ( $r->have_posts() ) {
			while ( $r->have_posts() ) {
				$r->the_post();
				get_template_part( 'partials/blog/content-list-magazine-loop' );
			}
		}
		?>
	</div>
</div>

<?php wp_reset_postdata(); ?>
