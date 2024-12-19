<?php


add_action( 'init', 'stm_wcmap_update_existing_shortcodes' );

function stm_wcmap_update_existing_shortcodes() {
	$order_by_values = array(
		'',
		__( 'Date', 'stm-woocommerce-motors-auto-parts' )  => 'date',
		__( 'ID', 'stm-woocommerce-motors-auto-parts' )    => 'ID',
		__( 'Author', 'stm-woocommerce-motors-auto-parts' ) => 'author',
		__( 'Title', 'stm-woocommerce-motors-auto-parts' ) => 'title',
		__( 'Modified', 'stm-woocommerce-motors-auto-parts' ) => 'modified',
		__( 'Random', 'stm-woocommerce-motors-auto-parts' ) => 'rand',
		__( 'Comment count', 'stm-woocommerce-motors-auto-parts' ) => 'comment_count',
		__( 'Menu order', 'stm-woocommerce-motors-auto-parts' ) => 'menu_order',
		__( 'Menu order & title', 'stm-woocommerce-motors-auto-parts' ) => 'menu_order title',
		__( 'Include', 'stm-woocommerce-motors-auto-parts' ) => 'include',
	);

	$order_way_values = array(
		'',
		__( 'Descending', 'stm-woocommerce-motors-auto-parts' ) => 'DESC',
		__( 'Ascending', 'stm-woocommerce-motors-auto-parts' ) => 'ASC',
	);

	$productsType = array(
		'',
		esc_html__( 'Featured Products', 'stm-woocommerce-motors-auto-parts' ) => 'featured',
		esc_html__( 'Sale Products', 'stm-woocommerce-motors-auto-parts' ) => 'sale',
		esc_html__( 'Best Selling Products', 'stm-woocommerce-motors-auto-parts' ) => 'best_selling',
		esc_html__( 'Top Rated Products', 'stm-woocommerce-motors-auto-parts' ) => 'top_rated',
	);

	if ( function_exists( 'vc_add_params' ) ) {

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_category_megamenu.php',
				'name'          => __( 'STM WooCommerce Category MegaMenu', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_category_megamenu',
				'icon'          => 'stm_wcmap_category_megamenu',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title',
					),
					array(
						'type'        => 'stm_wcmap_autocomplete_vc',
						'heading'     => __( 'Main taxonomies to fill', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'taxonomy',
						'description' => __( 'Type slug of the category (don\'t delete anything from autocompleted suggestions)', 'stm-woocommerce-motors-auto-parts' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_parts_search.php',
				'name'          => __( 'STM Parts Search', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_parts_search',
				'icon'          => 'stm_wcmap_parts_search',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_icon_filter.php',
				'name'          => __( 'STM Icon Filter', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_icon_filter',
				'icon'          => 'stm_wcmap_icon_filter',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => __( 'Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Select Filter Type', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'filter_type',
						'value'      => array(
							'By Attributes' => 'atts',
							'By Categories' => 'cats',
						),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => __( 'Show count', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'show_count',
						'value'      => array(
							__( 'Yes', 'stm-woocommerce-motors-auto-parts' ) => 'yes',
						),
						'std'        => 'yes',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_info_box.php',
				'name'          => __( 'STM AP Info Box', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_ap_info_box',
				'icon'          => 'stm_wcmap_ap_info_box',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Box background color', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'box_bg_color',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title Link', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title_link',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Title color', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title_color',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Price label', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'price_label',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Price value', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'price_value',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Price color', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'price_color',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Content color', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'content_color',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Text', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_ap_icon_box.php',
				'name'          => __( 'STM AP Icon Box', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_ap_icon_box',
				'icon'          => 'stm_wcmap_ap_icon_box',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'       => 'attach_image',
						'heading'    => __( 'Image', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => __( 'Text', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		$pages_data = stm_wcmap_get_post_data( 'product' );

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_special_product.php',
				'name'          => esc_html__( 'STM Special Product', 'stm-woocommerce-motors-auto-parts' ),
				'description'   => esc_html__( 'Show special product by title', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_special_product',
				'icon'          => 'icon-wpb-woocommerce',
				'category'      => esc_html__( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Select product', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'product_id',
						'admin_label' => true,
						'settings'    => array(
							'multiple'       => false,
							'sortable'       => false,
							'min_length'     => 1,
							'no_hide'        => false,
							'unique_values'  => true,
							'display_inline' => true,
							'values'         => $pages_data,
						),
					),
					array(
						'type'        => 'stm_datepicker_vc',
						'heading'     => esc_html__( 'Date end', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'datepicker',
						'holder'      => 'div',
						'description' => 'This is a required field',
					),
					array(
						'type'        => 'stm_timepicker_vc',
						'heading'     => esc_html__( 'Time end', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'timepicker',
						'description' => 'This is a required field',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Box title', 'stm-woocommerce-motors-auto-parts' ),
						'holder'     => 'div',
						'param_name' => 'content',
						'group'      => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency' => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Default Spacer height', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'height',
						'admin_label' => true,
						'group'       => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency'  => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Desctop (max 1300px) Spacer height', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'height_tablet_desktop',
						'group'      => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency' => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Tablet (landscape) Spacer height', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'height_tablet_landscape',
						'group'      => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency' => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Tablet Spacer height', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'height_tablet',
						'group'      => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency' => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Mobile Spacer height', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'height_mobile',
						'group'      => esc_html__( 'Content', 'stm-woocommerce-motors-auto-parts' ),
						'dependency' => array(
							'element' => 'block_size',
							'value'   => array( 'big_size' ),
						),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'Css', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'css',
						'group'      => __( 'Design options', 'stm-woocommerce-motors-auto-parts' ),
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_products_list.php',
				'name'          => __( 'STM WC Products', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_products_list',
				'icon'          => 'icon-wpb-woocommerce',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'description'   => __( 'Show multiple products by ID or SKU.', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Columns', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'columns',
						'value'       => array(
							4 => 4,
							3 => 3,
						),
						'std'         => '4',
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Number Posts', 'js_composer' ),
						'value'       => 8,
						'param_name'  => 'number_posts',
						'save_always' => true,
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Order by', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'orderby',
						'value'       => $order_by_values,
						'std'         => 'title',
						// Default WC value
						'save_always' => true,
						'description' => sprintf( __( 'Select how to sort retrieved products. More at %s. Default by Title', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => __( 'Sort order', 'stm-woocommerce-motors-auto-parts' ),
						'param_name'  => 'order',
						'value'       => $order_way_values,
						'std'         => 'ASC',
						// default WC value
						'save_always' => true,
						'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'stm-woocommerce-motors-auto-parts' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => __( 'Products Type', 'stm-woocommerce-motors-auto-parts' ),
						'param_name' => 'product_type',
						'value'      => $productsType,
						'std'        => '',
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'skus',
					),
				),
			)
		);

		vc_map(
			array(
				'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_compare.php',
				'name'          => __( 'STM YITH Compare', 'stm-woocommerce-motors-auto-parts' ),
				'base'          => 'stm_wcmap_compare',
				'category'      => __( 'STM Auto Parts', 'stm-woocommerce-motors-auto-parts' ),
				'params'        => array(),
			)
		);

		vc_map_update( 'vc_tta_tabs', array( 'html_template' => STM_WCMAP_PATH . '/vc_templates/stm_wcmap_ap_tabs.php' ) );

		vc_add_param(
			'vc_tta_tabs',
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Tabs Title', 'stm-woocommerce-motors-auto-parts' ),
				'param_name' => 'tabs_title',
			)
		);

	}
}

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'stm_wcmap_autocomplete_vc', 'stm_wcmap_autocomplete_vc_st_taxonomies', STM_WCMAP_URL . '/inc/vc_extends/jquery-ui.min.js' );
}

function stm_wcmap_autocomplete_vc_st_taxonomies( $settings, $value ) {
	return '<div class="stm_wcmap_autocomplete_vc_field">'
		. '<script type="text/javascript">'
		. 'var st_vc_taxonomies = ' . wp_json_encode( stm_wcmap_get_taxonomies() )
		. '</script>'
		. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_wcmap_autocomplete_vc stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
		esc_attr( $settings['param_name'] ) . ' ' .
		esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
		'</div>';
}

function stm_wcmap_get_taxonomies() {
	$taxonomies = array();

	$cats = get_terms(
		array(
			'orderby'      => 'id',
			'order'        => 'ASC',
			'fields'       => 'all',
			'show_count'   => 0,
			'hierarchical' => 1,
			'hide_empty'   => 0,
			'taxonomy'     => 'product_cat',
			'parent'       => 0,
		)
	);

	if ( ! is_wp_error( $cats ) ) {
		$i = 0;
		foreach ( $cats as $k => $cat ) {

			$taxonomies[ $cat->name ] = $cat->slug;

		}
	}

	return $taxonomies;

}

if ( class_exists( 'WpbakeryShortcodeParams' ) ) {
	vc_add_shortcode_param( 'stm_datepicker_vc', 'stm_datepicker_vc_st_ap', STM_WCMAP_URL . '/assets/js/datepicker.js' );
}
function stm_datepicker_vc_st_ap( $settings, $value ) {
	return '<div class="stm_datepicker_vc_field">'
		. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_datepicker_vc wpb_vc_param_value wpb-textinput ' .
		esc_attr( $settings['param_name'] ) . ' ' .
		esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
		'</div>';
}

if ( class_exists( 'WpbakeryShortcodeParams' ) ) {
	vc_add_shortcode_param( 'stm_timepicker_vc', 'stm_timepicker_vc_st_ap', STM_WCMAP_URL . '/assets/js/timepicker.js' );
}
function stm_timepicker_vc_st_ap( $settings, $value ) {
	return '<div class="stm_timepicker_vc_field">'
		. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_timepicker_vc wpb_vc_param_value wpb-textinput ' .
		esc_attr( $settings['param_name'] ) . ' ' .
		esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
		'</div>';
}
