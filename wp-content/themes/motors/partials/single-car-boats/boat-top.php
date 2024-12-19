<div class="clearfix stm-boats-single-top">
	<div class="pull-right">
		<!--Prices-->
		<div class="stm-boats-single-price">
			<?php get_template_part( 'partials/single-car-boats/boat', 'price' ); ?>
		</div>
	</div>
	<h1 class="title h2"><?php echo apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID() );//phpcs:ignore ?></h1>
</div>

<?php
$show_stock   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_stock' );
$stock_number = get_post_meta( get_the_id(), 'stock_number', true );
if ( $show_stock && ! empty( $stock_number ) ) :
	?>
	<div class="boats-stock">
		<?php esc_html_e( 'Stock#', 'motors' ); ?>
		<strong><?php echo esc_attr( $stock_number ); ?></strong>
	</div>
<?php endif; ?>
