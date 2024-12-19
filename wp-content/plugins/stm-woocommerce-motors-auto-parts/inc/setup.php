<?php
add_action( 'after_setup_theme', 'stm_wcmap_plugin_setup' );
add_action( 'widgets_init', 'stm_wcmap_register_sidebar' );

function stm_wcmap_plugin_setup() {
	add_image_size( 'stm-wcmap-210-260', 210, 260, true );
}

function stm_wcmap_register_sidebar() {
	register_sidebar(
		array(
			'name'          => __( 'Single Product Sidebar', 'stm-woocommerce-motors-auto-parts' ),
			'id'            => 'single_product_sidebar',
			'description'   => __( 'WoocommerceW single product sidebar', 'stm-woocommerce-motors-auto-parts' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="widget_title"><h3>',
			'after_title'   => '</h3></div>',
		)
	);
}

