<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
}
// The Widget_Base class is not available immediately after plugins are loaded, so
// we delay the class' use until Elementor widgets are registered
add_action( 'elementor/widgets/widgets_registered', function () {
	require_once( 'widget.php' );

	$drop_down_widget = new WOOMULTI_CURRENCY_Elementor_Widget();

// Let Elementor know about our widget
	if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' )){
		Elementor\Plugin::instance()->widgets_manager->register( $drop_down_widget );
	}else {
		Elementor\Plugin::instance()->widgets_manager->register_widget_type( $drop_down_widget );
	}
} );