<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// Creating custom query for each tab.
$args = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => $per_page,
);

$products = new WP_Query( $args );

$random_number = wp_rand( 1, 99999 );

if ( class_exists( 'WooCommerce' ) ) :
	?>
	<div class="ev_online_shop_products <?php echo esc_attr( $css_class ); ?>">
		<div class="title_nav">
			<?php if ( ! empty( $title ) ) : ?>
				<div class="title heading-font" 
				<?php
				if ( ! empty( $title_color ) ) {
					?>
					style="color: <?php echo esc_attr( $title_color ); ?>" <?php } ?>>
					<?php echo esc_html( $title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $show_view_all_btn ) && 'yes' === $show_view_all_btn ) : ?>
				<div class="all_listings">
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view_all_button">
						<?php esc_html_e( 'Online shop', 'motors-wpbakery-widgets' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $products->have_posts() ) : ?>
			<div class="row car-listing-row">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					$product = wc_get_product( get_the_ID() );
					?>
					<div class="col-md-4 col-sm-6 col-xs-12 col-xxs-12 ev_shop_products_item">
						<div class="image">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php $img_2x = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-796-466' ); ?>
								<?php
								echo wp_get_attachment_image(
									get_post_thumbnail_id( get_the_ID() ),
									'stm-img-350-356',
									false,
									array(
										'data-retina' => $img_2x[0],
										'alt'         => get_the_title(),
									)
								);
								?>
							<?php else : ?>
								<?php if ( stm_check_if_car_imported( get_the_id() ) ) : ?>
									<img
										src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/automanager_placeholders/plchldr255automanager.png' ); ?>"
										class="img-responsive"
										alt="<?php esc_attr_e( 'Placeholder', 'motors-wpbakery-widgets' ); ?>"
										/>
								<?php else : ?>
									<img
										src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/plchldr255.png' ); ?>"
										class="img-responsive"
										alt="<?php esc_attr_e( 'Placeholder', 'motors-wpbakery-widgets' ); ?>"
										/>
								<?php endif; ?>
							<?php endif; ?>
							<div class="btn_wrap">
								<?php if ( $product->is_type( 'variable' ) ) : ?>
									<a href="<?php the_permalink(); ?>"
										class="add_to_cart_button"
										aria-label="<?php esc_attr_e( 'Select options', 'motors-wpbakery-widgets' ); ?>"
									>
										<?php echo esc_html__( 'Select options', 'motors-wpbakery-widgets' ); ?>
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
										value="<?php echo esc_attr( $product->get_id() ); ?>"
										class="ajax_add_to_cart add_to_cart_button"
										data-product_id="<?php echo esc_attr( get_the_ID() ); ?>"
										data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
										aria-label="<?php esc_attr_e( 'Add to cart', 'motors-wpbakery-widgets' ); ?>"
									>
										<?php echo esc_html__( 'Add to cart', 'motors-wpbakery-widgets' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div> <!-- image -->
						<a href="<?php the_permalink(); ?>">
							<h3 class="product_title">
								<?php the_title(); ?>
							</h3>
						</a>
						<p class="product_price">
							<?php echo wp_kses( $product->get_price_html(), array( 'span' => array( 'class' => array() ) ) ); ?>
						</p>
					</div>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="text-muted">
				<?php echo esc_html__( 'No products found', 'motors-wpbakery-widgets' ); ?>
			</p>
		<?php endif; ?>
	</div>
<?php endif; ?>
