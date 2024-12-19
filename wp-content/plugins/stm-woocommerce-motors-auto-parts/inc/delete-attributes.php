<?php

add_action( 'woocommerce_before_attribute_delete', 'stm_auto_parts_woocommerce_before_attribute_delete', 10, 3 );

function stm_auto_parts_woocommerce_before_attribute_delete( $id, $name, $taxonomy ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		die;
	}

	global $wpdb;

	$table_name = "{$wpdb->prefix}stm_wcmap_prod_atts_relation";

	$wpdb->query( $wpdb->prepare( 'DELETE FROM %s WHERE id = %s', $table_name, $taxonomy ) );

}
