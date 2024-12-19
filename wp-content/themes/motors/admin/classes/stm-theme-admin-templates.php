<?php

class STM_Theme_Admin_Templates {

	public static function __callStatic( $name, $arguments ) {
		self::load_template( $name );
	}

	public static function load_template( $path ) {
		$located = locate_template( "admin/templates/{$path}.php" );
		if ( $located ) {
			load_template( $located );
		}
	}
}