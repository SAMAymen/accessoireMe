<?php
add_filter( 'single_listing_layout', 'me_single_listing_layout', 10, 1 );
function me_single_listing_layout( $conf ) {
	$conf = array_merge(
		$conf,
		array(
			'stm_single_car_page' =>
				array(
					'label'   => esc_html__( 'Single Listing Page Background', 'stm_motors_extends' ),
					'type'    => 'image',
					'submenu' => esc_html__( 'Single Listing Layout', 'stm_motors_extends' ),
				),
			'stm_car_link_quote'  =>
				array(
					'label'   => esc_html__( 'Request a Quote Link', 'stm_motors_extends' ),
					'type'    => 'text',
					'submenu' => esc_html__( 'Single Listing Layout', 'stm_motors_extends' ),
				),
			'show_quote_phone'    =>
				array(
					'label'   => esc_html__( 'Show Quote By Phone', 'stm_motors_extends' ),
					'type'    => 'checkbox',
					'submenu' => esc_html__( 'Page layout', 'stm_motors_extends' ),
				),
		)
	);

	return $conf;
}
