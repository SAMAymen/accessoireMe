<?php

/*
Class Name: WOOMULTI_CURRENCY_F_Admin_System
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015-2017 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Admin_System {


	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
	}

	public function page_callback() {
		?>
        <h2><?php esc_html_e( 'System Status', 'woo-multi-currency' ) ?></h2>
        <table cellspacing="0" id="status" class="widefat">
            <tbody>
            <tr>
                <td data-export-label="file_get_contents">file_get_contents</td>
                <td>
					<?php
					if ( function_exists( 'file_get_contents' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>
                </td>
            </tr>
            <tr>
                <td data-export-label="file_put_contents">file_put_contents</td>
                <td>
					<?php
					if ( function_exists( 'file_put_contents' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>

                </td>
            </tr>

            <tr>
                <td data-export-label="<?php esc_html_e( 'PHP Time Limit', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'PHP Time Limit', 'woo-multi-currency' ) ?></td>
                <td><?php echo esc_html( ini_get( 'max_execution_time' ) ); ?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_html_e( 'PHP Max Input Vars', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'PHP Max Input Vars', 'woo-multi-currency' ) ?></td>

                <td><?php echo esc_html( ini_get( 'max_input_vars' ) ); ?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_html_e( 'Memory Limit', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'Memory Limit', 'woo-multi-currency' ) ?></td>

                <td><?php echo esc_html( ini_get( 'memory_limit' ) ); ?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_html_e( 'Allow URL Open', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'Allow URL Open', 'woo-multi-currency' ) ?></td>
                <td>
					<?php
					if ( ini_get( 'allow_url_fopen' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>
                </td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_html_e( 'Session path', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'Session path', 'woo-multi-currency' ) ?></td>
                <td><?php
					$check_session = @fopen( session_save_path() . 'test-log.log', 'a' ) ? true : false;// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
					if ( $check_session ) {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span> <code class="private">' . esc_html( session_save_path() ) . '</code></mark> ';
					} else {
						/* translators: %1$s: logging file path */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'To allow logging, make %1$s writable.', 'woo-multi-currency' ), '<code>' . esc_html( session_save_path() ) . '</code>' ) . '</mark>';
					}
					?></td>
            </tr>
            <tr>
                <td data-export-label="<?php esc_html_e( 'Your IP', 'woo-multi-currency' ) ?>"><?php esc_html_e( 'Your IP', 'woo-multi-currency' ) ?></td>
                <td>
                    <ul>
                        <li><strong>
								<?php
								$ip = new WC_Geolocation();
								esc_html_e( '[WooCommerce] Your IP', 'woo-multi-currency' )
								?> : <?php echo esc_html( $ip->get_ip_address() ) ?>
                            </strong>
                        </li>
                        <li><strong>
								<?php
								esc_html_e( '[WooCommerce] Your GEO IP', 'woo-multi-currency' )
								?> : <?php echo esc_html( wp_json_encode( $ip->geolocate_ip() ) ) ?>
                            </strong>
                        </li>
                        <li><h3>
								<?php
								esc_html_e( '_SERVER system', 'woo-multi-currency' )
								?></h3>
                            <pre><?php print_r( array_map( 'esc_html', $_SERVER ) ) ?></pre>
                        </li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_submenu_page(
			'woo-multi-currency', esc_html__( 'System Status', 'woo-multi-currency' ), esc_html__( 'System Status', 'woo-multi-currency' ), 'manage_woocommerce', 'woo-multi-currency-system-status', array(
				$this,
				'page_callback'
			)
		);
	}
}