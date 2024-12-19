<?php
function stm_theme_import_widgets( $layout ) {

	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$widgets = STM_CONFIGURATIONS_PATH . '/demos/' . $layout . '/widgets/widget_data.json';

	if ( file_exists( $widgets ) ) {
		$encode_widgets_array = $wp_filesystem->get_contents( $widgets );
		stm_remove_all_widgets();
		stm_import_widgets( $encode_widgets_array );
	}

}

function stm_import_widgets( $widget_data ) {
	$json_data = $widget_data;
	$json_data = json_decode( $json_data, true );

	$sidebar_data = $json_data[0];
	$widget_data  = $json_data[1];

	$menu_object = wp_get_nav_menu_object( 'Widget menu' );

	if ( 'ev_dealer' === get_option( 'stm_motors_chosen_template' ) ) {
		$menu_object = wp_get_nav_menu_object( 'Bottom menu' );
	}

	if ( ! empty( $menu_object )
		&& ! empty( $menu_object->term_id )
		&& ! empty( $widget_data['nav_menu'] )
		&& ! empty( $widget_data['nav_menu'][2] )
		&& ! empty( $widget_data['nav_menu'][2]['nav_menu'] )
	) {
		$widget_data['nav_menu'][2]['nav_menu'] = $menu_object->term_id;
	}

	foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
		$widgets[ $widget_data_title ] = array();
		foreach ( $widget_data_value as $widget_data_key => $widget_data_array ) {
			if ( is_int( $widget_data_key ) ) {
				$widgets[ $widget_data_title ][ $widget_data_key ] = 'on';
			}
		}
	}
	
	unset( $widgets[''] );

	foreach ( $sidebar_data as $title => $sidebar ) {
		$count = count( $sidebar );
		for ( $i = 0; $i < $count; $i++ ) {
			$widget               = array();
			$widget['type']       = trim( substr( $sidebar[ $i ], 0, strrpos( $sidebar[ $i ], '-' ) ) );
			$widget['type-index'] = trim( substr( $sidebar[ $i ], strrpos( $sidebar[ $i ], '-' ) + 1 ) );
			if ( ! isset( $widgets[ $widget['type'] ][ $widget['type-index'] ] ) ) {
				unset( $sidebar_data[ $title ][ $i ] );
			}
		}

		$sidebar_data[ $title ] = array_values( $sidebar_data[ $title ] );
	}

	foreach ( $widgets as $widget_title => $widget_value ) {
		foreach ( $widget_value as $widget_key => $widget_value ) {
			$widgets[ $widget_title ][ $widget_key ] = $widget_data[ $widget_title ][ $widget_key ];
		}
	}

	$sidebar_data = array( array_filter( $sidebar_data ), $widgets );

	stm_widget_parse_import_data( $sidebar_data );
}


function stm_widget_parse_import_data( $import_array ) {
	global $wp_registered_sidebars;
	$sidebars_data    = $import_array[0];
	$widget_data      = $import_array[1];
	$current_sidebars = get_option( 'sidebars_widgets' );
	$new_widgets      = array();

	foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

		foreach ( $import_widgets as $import_widget ) :
			// if the sidebar exists
			if ( isset( $wp_registered_sidebars[ $import_sidebar ] ) ) :
				$title               = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
				$index               = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
				$current_widget_data = get_option( 'widget_' . $title );
				$new_widget_name     = stm_get_new_widget_name( $title, $index );
				$new_index           = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

				if ( ! empty( $new_widgets[ $title ] ) && is_array( $new_widgets[ $title ] ) ) {
					while ( array_key_exists( $new_index, $new_widgets[ $title ] ) ) {
						$new_index++;
					}
				}
				$current_sidebars[ $import_sidebar ][] = $title . '-' . $new_index;
				if ( array_key_exists( $title, $new_widgets ) ) {
					$new_widgets[ $title ][ $new_index ] = $widget_data[ $title ][ $index ];
					$multiwidget                         = $new_widgets[ $title ]['_multiwidget'];
					unset( $new_widgets[ $title ]['_multiwidget'] );
					$new_widgets[ $title ]['_multiwidget'] = $multiwidget;
				} else {
					$current_widget_data[ $new_index ] = $widget_data[ $title ][ $index ];
					$current_multiwidget               = isset( $current_widget_data['_multiwidget'] ) ? $current_widget_data['_multiwidget'] : false;
					$new_multiwidget                   = isset( $widget_data[ $title ]['_multiwidget'] ) ? $widget_data[ $title ]['_multiwidget'] : false;
					$multiwidget                       = ( $current_multiwidget != $new_multiwidget ) ? $current_multiwidget : 1;
					unset( $current_widget_data['_multiwidget'] );
					$current_widget_data['_multiwidget'] = $multiwidget;
					$new_widgets[ $title ]               = $current_widget_data;
				}

			endif;
		endforeach;
	endforeach;

	if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
		update_option( 'sidebars_widgets', $current_sidebars );

		foreach ( $new_widgets as $title => $content ) {
			update_option( 'widget_' . $title, $content );
		}

		return true;
	}

	return false;
}

function stm_get_new_widget_name( $widget_name, $widget_index ) {
	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array();

	if ( empty( $current_sidebars ) ) {
		return;
	}

	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( ! empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				$all_widget_array[] = $widget;
			}
		}
	}
	while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array, true ) ) {
		$widget_index++;
	}
	
	$new_widget_name = $widget_name . '-' . $widget_index;

	return $new_widget_name;
}

function stm_remove_all_widgets() {
	$current_sidebars = get_option( 'sidebars_widgets' );

	if ( is_array( $current_sidebars ) && ! empty( $current_sidebars ) ) {
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( ! empty( $widgets ) && 'wp_inactive_widgets' !== $sidebar ) {
				$current_sidebars[ $sidebar ] = array();
			}
		}
	} else {
		$current_sidebars = array();
	}

	update_option( 'sidebars_widgets', $current_sidebars );
}
