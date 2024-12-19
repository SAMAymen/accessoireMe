<?php
/**
 * Builder attribute location
 *
 * Template can be modified by copying it to yourtheme/ulisting/builder/attribute/location.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.4.1
 */

$unique_id = wp_rand( 1, 99999 );

$style = ( ! empty( $element['params']['style_template'] ) ) ? $element['params']['style_template'] : '';

if ( empty( $style ) ) :
	?>

<div <?php echo \uListing\Classes\Builder\UListingBuilder::generation_html_attribute( $element ); ?>>

	<?php
	$items = $args['model']->getAttributeValue( $element['params']['attribute'] );
	foreach ( $items as $val ) {
		$full            = wp_get_attachment_image_src( $val->value, 'full' );
		$thumbnail       = wp_get_attachment_image_src( $val->value, 'thumbnail' );
		$gallery_items[] = array(
			'sort'      => $val->sort,
			'full'      => ( $full ) ? $full : array( ulisting_get_placeholder_image_url() ),
			'thumbnail' => ( $thumbnail ) ? $thumbnail : array( ulisting_get_placeholder_image_url() ),
		);
	}
	\uListing\Classes\Vendor\ArrayHelper::multisort( $gallery_items, 'sort' )
	?>
	<div id="carousel_example_<?php echo esc_attr( $unique_id ); ?>" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<?php
			$i = 0;
			if ( ! empty( $gallery_items ) ) :
				foreach ( $gallery_items as $item ) :
					?>
					<li data-target="#carousel_example_<?php echo esc_attr( $unique_id ); ?>"
						data-slide-to="<?php echo esc_attr( $i ); ?>" <?php echo ( esc_attr( $i ) == 0 ) ? "class='active'" : null; ?> ></li>
					<?php
					$i++;
endforeach;
				?>
			<?php endif; ?>
		</ol>
		<div class="carousel-inner">
			<?php if ( ! empty( $gallery_items ) ) : ?>
				<?php
				$active = true;
				foreach ( $gallery_items as $item ) :
					?>
					<div class="carousel-item <?php echo ( esc_attr( $active ) ) ? 'active' : null; ?>">
						<img src="<?php echo esc_url( $item['full'][0] ); ?>" class="d-block">
					</div>
					<?php
					$active = false;
endforeach;
				?>
			<?php endif; ?>
		</div>
		<a class="carousel-control-prev" href="#carousel_example_<?php echo esc_attr( $unique_id ); ?>" role="button"
		   data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carousel_example_<?php echo esc_attr( $unique_id ); ?>" role="button"
		   data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>

<?php else : ?>
<div class="stm-gallery-custom-style">
	<?php
	echo \uListing\Classes\StmListingTemplate::load_template(
		'builder/attribute/' . $style,
		array(
			'args'    => $args,
			'element' => $element,
		)
	);
	?>
</div>
<?php endif; ?>
