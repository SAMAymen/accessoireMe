<?php
/**
 * Premium tab settings array
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

defined( 'YITH_WOOCOMPARE' ) || exit; // Exit if accessed directly.

return array(
	'premium' => array(
		'landing' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_woocompare_premium',
		),
	),
);
