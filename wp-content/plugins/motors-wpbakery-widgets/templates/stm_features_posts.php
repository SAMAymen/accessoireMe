<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$sticky    = get_option( 'sticky_posts' );
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$posts_per_page = ( isset( $_GET['posts_per_page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['posts_per_page'] ) ) : 4;

$args = array(
	'post_type'   => 'any',
	'post__in'    => $sticky,
	'post_status' => 'publish',
);

$r = new WP_Query( $args );

$cats        = array();
$cats_filter = array();
foreach ( $r->posts as $k => $stm_post ) {
	$category_object = get_the_terms( $stm_post, 'category' );

	if ( $category_object ) {
		if ( ! $category_object ) {
			$category_object = get_the_terms( $stm_post, 'review_category' );
		} elseif ( ! $category_object ) {
			$category_object = get_the_terms( $stm_post, 'event_category' );
		}

		if ( ! is_null( $category_object[0]->name ) ) {
			$cats_filter[ $category_object[0]->slug ] = $category_object[0]->name;
		}
		$cats[ $k ]['name'] = $category_object[0]->name;
		$cats[ $k ]['slug'] = $category_object[0]->slug;
	}
}

$hidden_wrap = ( 'yes' === $use_adsense ) ? 3 : 4;
?>
<div id="features_posts_wrap" data-action="&hidenWrap=<?php echo esc_attr( $hidden_wrap ); ?>&adsense_position=<?php echo ( ! empty( $adsense_position ) ) ? esc_url( $adsense_position ) : 1; ?>&use_adsense=<?php echo ( ! empty( $use_adsense ) ) ? esc_url( $use_adsense ) : 'no'; ?>" class="stm-features-posts-main <?php echo esc_attr( $css_class ); ?>">
	<div class="features-top">
		<div class="left">
			<h2>
				<?php echo esc_html( $features_title ); ?>
			</h2>
		</div>
		<div class="right">
			<ul class="cat-list features-cat-list">
				<li class="active" data-slug="all"><span class="heading-font"><?php echo esc_html__( 'All Features', 'motors-wpbakery-widgets' ); ?></span></li>
				<?php foreach ( $cats_filter as $slug => $name ) : ?>
					<li data-slug="<?php echo esc_html( $slug ); ?>">
						<span class="heading-font"><?php echo esc_html( $name ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="features-show-all">
				<span class="btn-show"></span>
			</div>
		</div>
	</div>
	<div class="features_posts_wrap">
		<?php
		if ( $r->have_posts() ) {
			$num = 0;
			while ( $r->have_posts() ) {
				$r->the_post();
				if ( 0 === $num ) {
					get_template_part( 'partials/vc_loop/features_posts_big_loop' );
				} else {
					if ( $adsense_position === $num && 'yes' === $use_adsense ) {
						?>
					<div class="adsense-200-200">
						<?php
						if ( ! empty( $content ) ) {
							echo wp_kses_post( $content );}
						?>
					</div>
						<?php
					}
					if ( $num > $hidden_wrap ) {
						echo '<div class="features_hiden">';
					}
						get_template_part( 'partials/vc_loop/features_posts_small_loop' );
					if ( $num > $hidden_wrap ) {
						echo '</div>';
					}
				}

				$num++;
			}
		}
		?>
	</div>
</div>
<?php wp_reset_postdata(); ?>
