<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( empty( $per_page ) ) {
	$per_page = 3; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

$args = array(
	'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
	'post_status'    => 'publish',
	'posts_per_page' => $per_page,
);

$args['meta_query'][] = array(
	'key'     => 'special_car',
	'value'   => 'on',
	'compare' => '=',
);

$special_query = new WP_Query( $args );
?>

<div class="stm-featured-boats-widget stm-boat-side-unit clearfix <?php echo esc_attr( $css_class ); ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<h4 class="title"><?php echo esc_attr( $title ); ?></h4>
	<?php endif; ?>
	<?php if ( $special_query->have_posts() ) : ?>

		<?php
		while ( $special_query->have_posts() ) :
			$special_query->the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="stm-featured-boats-w-units">
				<?php
					$price      = get_post_meta( get_the_id(), 'price', true );
					$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

				if ( ! empty( $sale_price ) ) {
					$price = $sale_price;
				}
				?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="image">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</div>
				<?php endif; ?>
				<div class="content car-listing-row">
					<div class="title heading-font">
						<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() ) ); ?>
					</div>
					<?php if ( ! empty( $price ) ) : ?>
						<div class="price heading-font">
							<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
						</div>
					<?php endif; ?>

					<?php
						$labels = apply_filters( 'stm_get_car_listings', array() );
						$labels = array_slice( $labels, 0, 2 );
					?>
					<div class="car-meta-bottom">
						<ul>
							<?php foreach ( $labels as $label ) : ?>
								<?php $label_meta = get_post_meta( get_the_id(), $label['slug'], true ); ?>
								<?php if ( ! empty( $label_meta ) && 'none' !== $label_meta && false === apply_filters( 'stm_is_listing_price_field', $label['slug'] ) ) : ?>
									<li>
										<?php if ( ! empty( $label['font'] ) ) : ?>
											<i class="<?php echo esc_attr( $label['font'] ); ?>"></i>
										<?php endif; ?>

										<?php if ( ! empty( $label['numeric'] ) && $label['numeric'] ) : ?>
											<span><?php echo esc_attr( $label_meta ); ?></span>
										<?php else : ?>

											<?php
											$data_meta_array = explode( ',', $label_meta );
											$datas           = array();

											if ( ! empty( $data_meta_array ) ) {
												foreach ( $data_meta_array as $data_meta_single ) {
													$data_meta = get_term_by( 'slug', $data_meta_single, $label['slug'] );
													if ( ! empty( $data_meta->name ) ) {
														$datas[] = esc_attr( $data_meta->name );
													}
												}
											}
											?>

											<?php if ( ! empty( $datas ) ) : ?>

												<?php
												if ( count( $datas ) > 1 ) {
													?>

													<span
														class="stm-tooltip-link"
														data-toggle="tooltip"
														data-placement="bottom"
														title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
															<?php echo esc_html( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
														</span>

												<?php } else { ?>
													<span><?php echo esc_html( implode( ', ', $datas ) ); ?></span>
													<?php
												}
												?>
											<?php endif; ?>

										<?php endif; ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</a>
		<?php endwhile; ?>
	<?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>
