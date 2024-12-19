<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $number_to_show ) ) {
	$number_to_show = 6;
}

if ( '' === $image ) {
	$image = '-1,-2,-3';
}

$image = explode( ',', $image );
$i     = - 1;

$our_partners = 'partners-carousel-' . wp_rand( 1, 99999 );

?>

<div class="stm-carousel-brands owl-carousel <?php echo esc_attr( $our_partners ); ?>">

	<?php
	if ( ! empty( $image ) ) :
		foreach ( $image as $attach_id ) :
			$i ++;
			$post_thumbnail = wpb_getImageBySize(
				array(
					'attach_id'  => $attach_id,
					'thumb_size' => $image_size,
				)
			);

			$thumbnail = $post_thumbnail['thumbnail'];
			?>

			<div class="brands-carousel-item">
				<div class="brands-carousel-item-inner">
					<?php echo wp_kses_post( $thumbnail ); ?>
				</div>
			</div>

			<?php
		endforeach;
	endif;
	?>

</div>

<script>
	(function($) {
		"use strict";

		var $owl = $('.<?php echo esc_js( $our_partners ); ?>');

		var owlRtl = false;
		if( $('body').hasClass('rtl') ) {
			owlRtl = true;
		}

		$(document).ready(function () {
			$owl.owlCarousel({
				rtl: owlRtl,
				items: 3,
				smartSpeed: 800,
				dots: false,
				margin:10,
				nav:true,
				navElement: 'div',
				autoplay: false,
				loop: true,
				navText: '',
				responsiveRefreshRate: 100,
				responsive:{
					0:{
						items:2
					},
					500:{
						items:3
					},
					768:{
						items:4
					},
					992: {
						items: 7	
					},
					1025:{
						items:<?php echo esc_html( $number_to_show ); ?>
					}
				}
			});
			$owl.on('click','.stm-owl-prev', function(){
				$owl.trigger('prev.owl.carousel');
			})
			$owl.on('click','.stm-owl-next', function(){
				$owl.trigger('next.owl.carousel');
			})
		});
	})(jQuery);
</script>
