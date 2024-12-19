<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Edostavka
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_WooCommerce_Edostavka {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_filter( 'wmc_excluded_shipping_methods_from_converting', array(
				$this,
				'wmc_excluded_shipping_methods_from_converting'
			) );
		}
	}

	public function wmc_excluded_shipping_methods_from_converting( $methods ) {
		if ( class_exists( 'WC_Edostavka' ) ) {
			$edostavka_methods = apply_filters( 'woocommerce_edostavka_shipping_methods_classes', array(
				'edostavka-package-door'                  => 'WC_Edostavka_Shipping_Package_Door',
				'edostavka-package-door-door'             => 'WC_Edostavka_Shipping_Package_Door_Door',
				'edostavka-package-door-stock'            => 'WC_Edostavka_Shipping_Package_Door_Stock',
				'edostavka-package-stock'                 => 'WC_Edostavka_Shipping_Package_Stock',
				'edostavka-econom-door'                   => 'WC_Edostavka_Shipping_Econom_Door',
				'edostavka-econom-stock'                  => 'WC_Edostavka_Shipping_Econom_Stock',
				'edostavka-express-light-door'            => 'WC_Edostavka_Shipping_Express_Light_Door',
				'edostavka-express-light-stock'           => 'WC_Edostavka_Shipping_Express_Light_Stock',
				'edostavka-express-light-door-stock'      => 'WC_Edostavka_Shipping_Express_Light_Door_Stock',
				'edostavka-express-light-door-door'       => 'WC_Edostavka_Shipping_Express_Light_Door_Door',
				'edostavka-international-express-door'    => 'WC_Edostavka_Shipping_International_Express_Door',
				'edostavka-magistral-express-stock'       => 'WC_Edostavka_Shipping_Magistral_Express_Stock',
				'edostavka-magistral-super-express-stock' => 'WC_Edostavka_Shipping_Magistral_Super_Express_Stock',
				'edostavka-oversize-express-stock'        => 'WC_Edostavka_Shipping_Oversize_Express_Stock',
				'edostavka-oversize-express-door'         => 'WC_Edostavka_Shipping_Oversize_Express_Door',
				'edostavka-oversize-express-door-stock'   => 'WC_Edostavka_Shipping_Oversize_Express_Door_Stock',
				'edostavka-oversize-express-door-door'    => 'WC_Edostavka_Shipping_Oversize_Express_Door_Door',
				'edostavka-super-express-18-door-door'    => 'WC_Edostavka_Shipping_Super_Express_18_Door_Door',
				'edostavka-cdek-express-door-door'        => 'WC_Edostavka_Shipping_CDEK_Express_Door_Door',
				'edostavka-cdek-express-stock-stock'      => 'WC_Edostavka_Shipping_CDEK_Express_Stock_Stock',
				'edostavka-cdek-express-door-stock'       => 'WC_Edostavka_Shipping_CDEK_Express_Door_Stock',
				'edostavka-cdek-express-stock-door'       => 'WC_Edostavka_Shipping_CDEK_Express_Stock_Door',
				'edostavka-package-door-postamat'         => 'WC_Edostavka_Shipping_Package_Door_Postamat',
				'edostavka-package-stock-postamat'        => 'WC_Edostavka_Shipping_Package_Stock_Postamat',
				'edostavka-express-light-door-postamat'   => 'WC_Edostavka_Shipping_Express_Light_Door_Postamat',
				'edostavka-express-light-stock-postamat'  => 'WC_Edostavka_Shipping_Express_Light_Stock_Postamat',
				'edostavka-econom-stock-postamat'         => 'WC_Edostavka_Shipping_Econom_Stock_Postamat',
				'edostavka-express-door-door'             => 'WC_Edostavka_Shipping_Express_Door_Door',
				'edostavka-express-door-stock'            => 'WC_Edostavka_Shipping_Express_Door_Stock',
				'edostavka-express-stock-door'            => 'WC_Edostavka_Shipping_Express_Stock_Door',
				'edostavka-express-stock-stock'           => 'WC_Edostavka_Shipping_Express_Stock_Stock',
				'edostavka-express-door-postamat'         => 'WC_Edostavka_Shipping_Express_Door_Postamat',
				'edostavka-express-stock-postamat'        => 'WC_Edostavka_Shipping_Express_Stock_Postamat'
			) );
			$methods           = array_merge( array_keys( $edostavka_methods ), array( 'edostavka' ), $methods );
		}

		return $methods;
	}
}