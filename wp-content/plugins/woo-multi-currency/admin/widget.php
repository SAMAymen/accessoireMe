<?php

/*
Class Name: WOOMULTI_CURRENCY_F_Admin_Widget
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015-2017 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Admin_Widget {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );


	}


	/**
	 * Init widget
	 */
	public function widgets_init() {
		register_widget( 'WMC_Widget' );
		register_widget( 'WMC_Widget_Rates' );
	}


}

?>