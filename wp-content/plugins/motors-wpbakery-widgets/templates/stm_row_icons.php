<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( ! empty( $filter_selected ) ) :
	$args = array(
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => false,
		'pad_counts' => true,
	);

	$terms = get_terms( $filter_selected, $args );

	$terms_images = array();
	$terms_text   = array();
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $stm_term ) {
			$image = get_term_meta( $stm_term->term_id, 'stm_image', true );
			if ( empty( $image ) ) {
				$terms_text[] = $stm_term;
			} else {
				$terms_images[] = $stm_term;
			}
		}
	}
	?>

	<div class="stm-boats-listing-icons <?php echo esc_attr( $css_class ); ?>">
		<?php foreach ( $terms_images as $stm_term ) : ?>
			<?php
			$image = get_term_meta( $stm_term->term_id, 'stm_image', true );
			if ( ! empty( $image ) ) :
				$image_dim = 'stm-img-190-132';
				if ( apply_filters( 'stm_is_motorcycle', false ) ) {
					$image_dim = 'stm-img-350';
				}
				$image          = wp_get_attachment_image_src( $image, $image_dim );
				$category_image = $image[0];

				$numeric = apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ? apply_filters( 'get_value_from_listing_category', false, $filter_selected, 'numeric' ) : true;
				$atts    = '?' . $filter_selected . '=' . $stm_term->slug;
				if ( ! $numeric ) {
					$atts = $stm_term->slug . '/';
				}

				?>
				<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) . $atts ); ?>"
					class="stm_listing_icon_filter_single" title="<?php echo esc_attr( $stm_term->name ); ?>">
					<div class="inner">
						<div class="image">
							<img src="<?php echo esc_url( $category_image ); ?>" alt="<?php echo esc_attr( $stm_term->name ); ?>" />
						</div>
						<div class="name heading-font"><?php echo esc_attr( $stm_term->name ); ?></div>
					</div>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

<?php endif; ?>
