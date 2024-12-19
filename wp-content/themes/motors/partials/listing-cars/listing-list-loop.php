<?php

if ( empty( $modern_filter ) ) {
	$modern_filter = false;
}

$image_data = array();
if ( ! empty( $custom_img_size ) ) {
	$image_data['custom_img_size'] = $custom_img_size;
}

do_action( 'stm_listings_load_template', 'loop/start', array( 'modern' => $modern_filter ) ); ?>
<?php do_action( 'stm_listings_load_template', 'loop/default/list/image', $image_data ); ?>

<div class="content">
	<div class="meta-top">
		<!--Price-->
		<?php do_action( 'stm_listings_load_template', 'loop/default/list/price' ); ?>
		<!--Title-->
		<?php do_action( 'stm_listings_load_template', 'loop/default/list/title' ); ?>
	</div>

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
