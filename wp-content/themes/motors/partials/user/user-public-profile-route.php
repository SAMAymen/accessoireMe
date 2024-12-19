<?php
	$user = get_queried_object();
	$roles = $user->roles;

	if(in_array('stm_dealer', $roles)){
		get_template_part('partials/user/dealer-public', 'profile');
	} else {
		get_template_part('partials/user/user-public', 'profile');
	}
?>