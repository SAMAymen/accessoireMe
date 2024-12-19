<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Appointments
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Appointments {
	protected static $settings;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( is_plugin_active( 'woocommerce-appointments/woocommerce-appointments.php' ) ) {
			add_filter( 'wc_appointments_adjust_addon_cost', array(
				$this,
				'wc_appointments_adjust_addon_cost'
			), 10, 4 );
		}
	}

	public function wc_appointments_adjust_addon_cost( $adjusted_cost, $appointment_cost, $product, $posted ) {
		if ( self::$settings->get_current_currency() !== self::$settings->get_default_currency() ) {
			// Get addon cost.
			$addon_cost = $posted['wc_appointments_field_addons_cost'] ?? 0;

			// Adjust.
			if ( $addon_cost !== 0 ) {
				$adjusted_cost = floatval( $appointment_cost ) + wmc_revert_price( $addon_cost );
				$adjusted_cost = $adjusted_cost > 0 ? $adjusted_cost : 0; #turn negative cost to zero.
				// Do nothing.
			} else {
				$adjusted_cost = $appointment_cost;
			}
		}

		return $adjusted_cost;
	}
}