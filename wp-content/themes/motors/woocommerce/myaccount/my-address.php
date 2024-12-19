<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( apply_filters( 'stm_is_rental_two', false ) ) {
	do_action( 'smt_mcr_account_addresses' );
} else {

	$customer_id = get_current_user_id();

	if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
		$page_title    = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Addresses', 'motors' ) );
		$get_addresses = apply_filters(
			'woocommerce_my_account_get_addresses',
			array(
				'billing'  => __( 'Billing address', 'motors' ),
				'shipping' => __( 'Shipping address', 'motors' ),
			),
			$customer_id
		);
	} else {
		$page_title    = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Address', 'motors' ) );
		$get_addresses = apply_filters(
			'woocommerce_my_account_get_addresses',
			array(
				'billing' => __( 'Billing address', 'motors' ),
			),
			$customer_id
		);
	}
	?>

	<div class="colored-separator text-left">
		<div class="first-long"></div>
		<div class="last-short"></div>
	</div>
	<h4><?php echo wp_kses_post( apply_filters( 'stm_balance_tags', $page_title ) ); ?></h4>

	<p class="myaccount_address">
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'motors' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>

	<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
		<div class="addresses">
	<?php endif; ?>

	<div class="row">
		<?php foreach ( $get_addresses as $name => $title ) : ?>

			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 address">
				<div class="colored-separator text-left">
					<div class="first-long"></div>
					<div class="last-short"></div>
				</div>
				<header class="title">
					<h4><?php echo wp_kses_post( apply_filters( 'stm_balance_tags', $title ) ); ?></h4>
				</header>
				<?php
				$address = apply_filters(
					'woocommerce_my_account_my_address_formatted_address',
					array(
						'country'    => array(
							'title' => __( 'Country', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_country', true ),
						),
						'first_name' => array(
							'title' => __( 'First Name', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_first_name', true ),
						),
						'last_name'  => array(
							'title' => __( 'Last Name', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_last_name', true ),
						),
						'company'    => array(
							'title' => __( 'Company', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_company', true ),
						),
						'address'    => array(
							'title' => __( 'Address', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_address_1', true ) . ' / ' . get_user_meta( $customer_id, $name . '_address_2', true ),
						),
						'city'       => array(
							'title' => __( 'City', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_city', true ),
						),
						'state'      => array(
							'title' => __( 'State', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_state', true ),
						),
						'postcode'   => array(
							'title' => __( 'Postcode', 'motors' ),
							'value' => get_user_meta( $customer_id, $name . '_postcode', true ),
						),
					),
					$customer_id,
					$name,
				);

				if ( ! $address ) {
					esc_html_e( 'You have not set up this type of address yet.', 'motors' );
				} else {
					$output = '';
				}
				$output .= '<table class="address-table">';
				$output .= '<tbody>';
				foreach ( $address as $value ) {
					$placeholder = '&nbsp;';
					if ( ! empty( $value['value'] ) ) {
						$placeholder = '';
					}
					$output .= '<tr><th>' . esc_html( $value['title'] ) . '</th><td>' . $placeholder . esc_html( $value['value'] ) . '</td></tr>';
				}
				$output .= '</tbody>';
				$output .= '</table>';
				echo wp_kses_post( apply_filters( 'stm_balance_tags', $output ) );

				/**
				 * Used to output content after core address fields.
				 *
				 * @param string $name Address type.
				 * @since 8.7.0
				 */
				do_action( 'woocommerce_my_account_after_my_address', $name );
				?>
				<footer>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="button edit"><?php esc_attr_e( 'Edit', 'motors' ); ?></a>
				</footer>
			</div>

		<?php endforeach; ?>
	</div>

	<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
		</div>
	<?php endif; ?>
	<?php
}
