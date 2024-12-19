<?php
$fields             = stm_get_rental_order_fields_values();
$pickup_location_id = $fields['pickup_location_id'];
$args               = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => '15',
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'car_option',
		),
	),
);

if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_car_option_office' ) ) {
	$args['meta_query'] = array(
		array(
			'key'     => 'offices',
			'value'   => sprintf( ':%d;', $pickup_location_id ),
			'compare' => 'LIKE',
		),
	);
}

$p = new WP_Query( $args );
if ( $p->have_posts() ) :
	while ( $p->have_posts() ) :
		$p->the_post(); ?>
		<div class="stm_rental_options_archive">
			<?php get_template_part( 'partials/rental/product/option' ); ?>
		</div>

	<?php endwhile; ?>
	<script>
		jQuery(document).ready(function(){
			var $ = jQuery;
			$('.stm-manage-stock-yes a').on('click', function(e){
				e.preventDefault();
				e.stopPropagation();
				var stmHref = $(this).attr('href');
				var quantityValue = $(this).closest('.meta').find('.qty').val();
				var quantity = '&quantity=' + quantityValue;
				stmHref += quantity;
				window.location.href = stmHref;
			});
		})
	</script>
	<?php else : ?>
	<div class="stm_rental_options_archive">
		<div class="stm_rental_option">
			<h4 class="disabled-heading"><?php esc_html_e( 'No available vehicle add-ons', 'motors' ); ?></h4>
		</div>
	</div>
		<?php
	endif;
	wp_reset_postdata();
