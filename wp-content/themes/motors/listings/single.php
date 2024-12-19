<?php if( file_exists(get_stylesheet_directory() . '/single-listings.php') ){
	require_once(get_stylesheet_directory() . '/single-listings.php');
}else{
	require_once(get_template_directory() . '/single-listings.php');
} ?>