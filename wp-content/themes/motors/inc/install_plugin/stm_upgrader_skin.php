<?php

class Motors_Theme_Plugin_Upgrader_Skin extends WP_Upgrader_Skin {

	public $plugin                = '';
	public $plugin_active         = false;
	public $plugin_network_active = false;
	public $messages              = array();

	public function __construct( $args = array() ) {
		$defaults = array(
			'url'    => '',
			'plugin' => '',
			'nonce'  => '',
			'title'  => '',
		);
		$args     = wp_parse_args( $args, $defaults );

		$this->plugin = $args['plugin'];

		$this->plugin_active         = is_plugin_active( $this->plugin );
		$this->plugin_network_active = is_plugin_active_for_network( $this->plugin );

		parent::__construct( $args );
	}

	public function after() {}

	public function header() {}

	public function footer() {}

	public function feedback( $string, ...$args ) {
		if ( isset( $this->upgrader->strings[ $string ] ) ) {
			$string = $this->upgrader->strings[ $string ];
		}

		if ( strpos( $string, '%' ) !== false ) {
			if ( $args ) {
				$args   = array_map( 'strip_tags', $args );
				$args   = array_map( 'esc_html', $args );
				$string = vsprintf( $string, $args );
			}
		}

		if ( empty( $string ) ) {
			return;
		}

		$this->messages[] = $string;
	}
}
