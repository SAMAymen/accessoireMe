<?php

if ( empty( $modern_filter ) ) {
	$modern_filter = false;
}

$hide_labels = apply_filters( 'motors_vl_get_nuxy_mod', false, 'hide_price_labels' );

do_action( 'stm_listings_load_template', 'loop/start', array( 'modern' => $modern_filter ) ); ?>
<?php do_action( 'stm_listings_load_template', 'loop/classified/list/image' ); ?>

	<div class="content">

		<!--Title-->
		<?php do_action( 'stm_listings_load_template', 'loop/classified/list/title_price', array( 'hide_labels' => $hide_labels ) ); ?>

		<!--Item parameters-->
		<div class="meta-middle">
			<?php do_action( 'stm_listings_load_template', 'loop/default/list/options' ); ?>
		</div>

		<!--Item options-->
		<div class="meta-bottom">
			<?php do_action( 'stm_listings_load_template', 'loop/default/list/features' ); ?>
		</div>
	</div>
</div>
