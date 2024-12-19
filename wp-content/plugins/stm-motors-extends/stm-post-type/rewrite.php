<?php
// STM Post Type Rewrite subplugin
add_action( 'admin_menu', 'stm_register_post_types_options_menu' );

if ( ! function_exists( 'stm_register_post_types_options_menu' ) ) {
	function stm_register_post_types_options_menu() {
		add_submenu_page( 'tools.php', __( 'STM Post Types', 'stm_motors_extends' ), __( 'STM Post Types', 'stm_motors_extends' ), 'manage_options', 'stm_post_types', 'stm_post_types_options' );
	}
}

if ( ! function_exists( 'stm_post_types_options' ) ) {
	function stm_post_types_options() {

		if ( ! empty( $_POST['stm_post_types_options'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			update_option( 'stm_post_types_options', $_POST['stm_post_types_options'] ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		$options = get_option( 'stm_post_types_options' );

		$default_post_types_options = array(
			'listings'   => array(
				'title'        => __( 'Listings', 'stm_motors_extends' ),
				'plural_title' => __( 'Listings', 'stm_motors_extends' ),
				'rewrite'      => 'listings',
			),
			'stm_events' => array(
				'title'        => __( 'Events', 'stm_motors_extends' ),
				'plural_title' => __( 'Events', 'stm_motors_extends' ),
				'rewrite'      => 'events',
			),
			'stm_review' => array(
				'title'        => __( 'Review', 'stm_motors_extends' ),
				'plural_title' => __( 'Review', 'stm_motors_extends' ),
				'rewrite'      => 'review',
			),
		);

		$options = wp_parse_args( $options, $default_post_types_options );

		$content = '';

		$content .= '
	<div class="wrap">
		<h2>' . __( 'Custom Post Type Renaming Settings', 'stm_motors_extends' ) . '</h2>

		<form method="POST" action="">
			<table class="form-table">';
		foreach ( $default_post_types_options as $key => $value ) {
			$content .= '
				<tr valign="top">
					<th scope="row">
						<label for="' . $key . '_title">' . sprintf( __( '"%s" title (admin panel tab name)', 'stm_motors_extends' ), $value['title'] ) . '</label>
					</th>
					<td>
						<input type="text" id="' . $key . '_title" name="stm_post_types_options[' . $key . '][title]" value="' . $options[ $key ]['title'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="' . $key . '_plural_title">' . sprintf( __( '"%s" plural title', 'stm_motors_extends' ), $value['plural_title'] ) . '</label>
					</th>
					<td>
						<input type="text" id="' . $key . '_plural_title" name="stm_post_types_options[' . $key . '][plural_title]" value="' . $options[ $key ]['plural_title'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="' . $key . '_rewrite">' . sprintf( __( '"%s" rewrite (URL text)', 'stm_motors_extends' ), $value['plural_title'] ) . '</label>
					</th>
					<td>
						<input type="text" id="' . $key . '_rewrite" name="stm_post_types_options[' . $key . '][rewrite]" value="' . $options[ $key ]['rewrite'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top"><th scope="row"></th></tr>
				';
		}
		$content .= '</table>
			<p>' . __( "NOTE: After you change the rewrite field values, you'll need to refresh permalinks under Settings -> Permalinks", 'stm_motors_extends' ) . '</p>
			<br/>
			<p>
				<input type="submit" value="' . __( 'Save settings', 'stm_motors_extends' ) . '" class="button-primary"/>
			</p>
		</form>
	</div>
	';

		echo apply_filters( 'stm_pt_content_filter', $content );//phpcs:ignore
	}
}
