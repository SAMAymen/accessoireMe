<?php

/**
 * Load Textdomain
 */
if ( ! is_textdomain_loaded( 'stm-elementor-icons' ) ) {
    load_plugin_textdomain( 'stm-elementor-icons', false, 'stm-elementor-icons/languages' );
}

/**
 * Init Admin Classes
 */
if ( is_admin() ) {
	\SEI\Classes\Admin\AdminMenu::init();
	new \SEI\Classes\Admin\CustomIcons();
}

/**
 * Init Classes
 */
new \SEI\Classes\LoadIcons();
