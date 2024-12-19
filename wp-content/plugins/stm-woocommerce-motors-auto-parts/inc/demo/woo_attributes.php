<?php
function stm_woo_create_attr( $layout ) {
	global $wpdb;
	$atts = call_user_func( 'stm_atts_' . $layout );

	if ( ! empty( $atts ) ) {

		foreach ( $atts as $slug => $label_name ) {

			if ( strlen( $slug ) >= 28 ) {
				return new WP_Error( 'invalid_product_attribute_slug_too_long', sprintf( __( 'Name "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
			} elseif ( wc_check_if_attribute_name_is_reserved( $slug ) ) {
				return new WP_Error( 'invalid_product_attribute_slug_reserved_name', sprintf( __( 'Name "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
			} elseif ( taxonomy_exists( wc_attribute_taxonomy_name( $label_name ) ) ) {
				return new WP_Error( 'invalid_product_attribute_slug_already_exists', sprintf( __( 'Name "%s" is already in use. Change it, please.', 'woocommerce' ), $label_name ), array( 'status' => 400 ) );
			}

			$args = array(
				'hierarchical'   => true,
				'label'          => $label_name,
				'show_ui'        => true,
				'query_var'      => true,
				'rewrite'        => array(
					'slug' => $slug,
				),
				'singular_label' => $label_name,
			);

			register_taxonomy( $slug, array( 'product' ), $args );

			$data = array(
				'attribute_label'   => $label_name,
				'attribute_name'    => $slug,
				'attribute_type'    => 'select',
				'attribute_orderby' => 'menu_order',
				'attribute_public'  => 0, // Enable archives ==> true (or 1)
			);

			$results = $wpdb->insert( "{$wpdb->prefix}woocommerce_attribute_taxonomies", $data );

			if ( is_wp_error( $results ) ) {
				return new WP_Error( 'cannot_create_attribute', $results->get_error_message(), array( 'status' => 400 ) );
			}

			$id = $wpdb->insert_id;

			do_action( 'woocommerce_attribute_added', $id, $data );

			wp_schedule_single_event( time(), 'woocommerce_flush_rewrite_rules' );

			delete_transient( 'wc_attribute_taxonomies' );
		}
	}
}

function stm_atts_auto_parts() {
	$atts = array(
		'make'        => __( 'Make', 'stm-woocommerce-motors-auto-parts' ),
		'model'       => __( 'Model', 'stm-woocommerce-motors-auto-parts' ),
		'part-year'   => __( 'Year', 'stm-woocommerce-motors-auto-parts' ),
		'body'        => __( 'Body', 'stm-woocommerce-motors-auto-parts' ),
		'block-color' => __( 'Block Color', 'stm-woocommerce-motors-auto-parts' ),
		'new-or-used' => __( 'New Or Used', 'stm-woocommerce-motors-auto-parts' ),
		'part-number' => __( 'Part Number', 'stm-woocommerce-motors-auto-parts' ),
	);

	return $atts;
}
