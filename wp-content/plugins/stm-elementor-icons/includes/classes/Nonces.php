<?php

namespace SEI\Classes;

class Nonces {

	/**
	 * All Nonces
	 * @return array
	 */
	public static function get_nonces() {
		$list = [
			'cei_upload_font_archive',
			'cei_remove_font_archive'
		];

		$nonces = [];

		foreach ( $list as $slug ) {
			$nonces[$slug] = wp_create_nonce($slug);
		}

		return $nonces;
	}

}