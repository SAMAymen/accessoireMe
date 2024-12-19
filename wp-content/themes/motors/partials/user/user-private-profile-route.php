<?php
	$user = wp_get_current_user();
	$vars = get_queried_object();

	if ( $user->ID !== $vars->ID ) {
		get_template_part( 'partials/user/user-public-profile', 'route' );
	} else {
		$roles = $vars->roles;

		if ( in_array( 'stm_dealer', $roles ) ){
			get_template_part( 'partials/user/private/dealer' );
		} else {
			get_template_part( 'partials/user/private/user' );
		}
	}
?>