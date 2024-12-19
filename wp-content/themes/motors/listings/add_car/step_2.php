<?php
$vars = array(
	'step_title'  => __( 'Select Your Car Features', 'stm_vehicles_listing' ),
	'step_number' => 2,
);
?>

<div class="stm-form-2-features clearfix">
	<?php
	do_action( 'stm_listings_load_template', 'add_car/step-title', $vars );
	do_action(
		'stm_listings_load_template',
		'add_car/step_2_items',
		array(
			'items' => $items,
			'id'    => $id,
		)
	);
	?>
</div>
