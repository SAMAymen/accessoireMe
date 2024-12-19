<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="container">
	<?php
	if ( ! empty( $breadcrumb ) ) {

		echo wp_kses_post( $wrap_before );

		foreach ( $breadcrumb as $key => $crumb ) {

			echo wp_kses_post( $before );

			if ( ! empty( $crumb[1] ) && count( $breadcrumb ) !== $key + 1 ) {
				echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
			} else {
				echo esc_html( $crumb[0] );
			}

			echo wp_kses_post( $after );

			if ( count( $breadcrumb ) !== $key + 1 ) {
				echo wp_kses_post( $delimiter );
			}
		}

		echo wp_kses_post( $wrap_after );

	}
	?>
</div> <!--container-->
