<?php
/**
 * Builder attribute custom gallery
 *
 * Template can be modified by copying it to yourtheme/ulisting/builder/attribute/ulisting_gallery_style_1.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */


$id    = wp_rand( 1, 99999 );
$items = $args['model']->getAttributeValue( $element['params']['attribute'] );
foreach ( $items as $val ) {
	$full            = wp_get_attachment_image_src( $val->value, 'full' );
	$big             = wp_get_attachment_image_src( $val->value, 'c-f-gallery-big' );
	$thumbnail       = wp_get_attachment_image_src( $val->value, 'c-f-gallery-thumb' );
	$gallery_items[] = array(
		'sort'      => $val->sort,
		'full'      => ( $full ) ? $full : array( ulisting_get_placeholder_image_url() ),
		'big'       => ( $big ) ? $big : array( ulisting_get_placeholder_image_url() ),
		'thumbnail' => ( $thumbnail ) ? $thumbnail : array( ulisting_get_placeholder_image_url() ),
	);
}
\uListing\Classes\Vendor\ArrayHelper::multisort( $gallery_items, 'sort' );

$url = ( apply_filters( 'stm_is_listing_five', false ) ) ? STM_MOTORS_C_F_URL : STM_MOTORS_C_SIX_URL;
$ver = ( apply_filters( 'stm_is_listing_five', false ) ) ? STM_MOTORS_C_F_SS_V : STM_MOTORS_C_SIX_SS_V;

wp_enqueue_script( 'stm-ul-motors-gallery', $url . '/assets/js/stm-ul-motors-gallery.js', array( 'owl.carousel' ), $ver, true );
?>
<div id="carousel_example_<?php echo esc_attr( $id ); ?>" class="ulisting_gallery_style_1">
	<div class="big-carousel-wrap">
		<div class="big-wrap">
			<?php if ( ! empty( $gallery_items ) ) : ?>
				<?php
				$active = true;
				foreach ( $gallery_items as $item ) :
					?>
					<div class="big-item <?php echo ( esc_attr( $active ) ) ? 'active' : null; ?>">
						<a href="<?php echo esc_url( $item['full'][0] ); ?>" class="stm-cf-big" rel="stm-listing-gallery">
							<img src="<?php echo esc_url( $item['big'][0] ); ?>" class="d-block">
						</a>
					</div>
					<?php
					$active = false;
endforeach;
				?>
			<?php endif; ?>
		</div>
		<div class="single-img-actions">
			<!--PHOTO COUNT-->
			<div class="photo-count">
				<i class="fas fa-camera"></i>
				<?php echo esc_html( count( $gallery_items ) ); ?>
			</div>
			<div class="action-right">
				<!--COMPARE-->
				<?php
				if ( ulisting_listing_compare_active() ) :
					$active = null;
					if ( \uListing\ListingCompare\Classes\UlistingListingCompare::is_active( $args['model']->ID ) ) {
						$active = 'active';
					}
					?>
					<div <?php echo \uListing\Classes\Builder\UListingBuilder::generation_html_attribute( $element ); //phpcs:ignore ?> >
						<div class="ulisting_listing-compare">
							<?php
							$element['params']['listing_id'] = $args['model']->ID;
							$element['params']['active']     = $active;
							$element['params']['template']   = 'template_1';
							echo \uListing\ListingCompare\Classes\UlistingListingCompare::render_compare( $element['params'] );//phpcs:ignore
							?>
						</div>
					</div>
				<?php endif; ?>
				<!--WHISHLIST-->
				<?php if ( ulisting_wishlist_active() && isset( $element['params']['template'] ) ) : ?>
					<div <?php echo \uListing\Classes\Builder\UListingBuilder::generation_html_attribute( $element ); //phpcs:ignore ?> >
						<?php echo ( class_exists( "\uListing\UlistingWishlist\Classes\UlistingWishlist" ) && $element['params']['template'] ) ? \uListing\UlistingWishlist\Classes\UlistingWishlist::render_add_button( $element['params']['template'], $args['model'] ) : null;//phpcs:ignore ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="thumbs-wrap">
		<?php
		$i = 0;
		if ( ! empty( $gallery_items ) ) :
			foreach ( $gallery_items as $item ) :
				?>
				<div class="thumb">
					<img src="<?php echo esc_url( $item['thumbnail'][0] ); ?>" class="d-block">
				</div>
				<?php
				$i ++;
endforeach;
			?>
		<?php endif; ?>
	</div>
</div>
