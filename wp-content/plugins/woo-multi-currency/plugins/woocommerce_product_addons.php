<?php

/**
 * Class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Product_Addons
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Plugin_Woocommerce_Product_Addons {
	protected static $settings;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		add_filter( 'woocommerce_product_addons_price_raw', array(
			$this,
			'woocommerce_product_addons_price_raw'
		), 10, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'woocommerce_get_item_data' ), 11, 2 );
		add_filter( 'woocommerce_product_addon_cart_item_data', array(
			$this,
			'woocommerce_product_addon_cart_item_data'
		), 10, 4 );

		add_filter( 'woocommerce_product_addons_order_line_item_meta', array(
			$this,
			'woocommerce_product_addons_order_line_item_meta'
		), 10, 4 );
		add_filter( 'woocommerce_product_addons_option_price_raw', array(
			$this,
			'woocommerce_product_addons_price_raw'
		), 10, 2 );
	}

	public function woocommerce_product_addon_cart_item_data( $data, $addon, $product_id, $post_data ) {
		if ( count( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( isset( $value['field_type'] ) && $value['field_type'] === 'custom_price' ) {
					$data[ $key ]['price'] = wmc_revert_price( $value['price'] );
					$data[ $key ]['value'] = $data[ $key ]['price'];
				}
			}
		}

		return $data;
	}

	public function woocommerce_product_addons_price_raw( $addon_price, $addon ) {
		$price_type = isset( $addon['price_type'] ) ? $addon['price_type'] : '';
		switch ( $price_type ) {
			case 'percentage_based':
				break;
			case 'quantity_based':
				if ( self::$settings->get_current_currency() !== self::$settings->get_default_currency() ) {
					$addon_price = wmc_get_price( $addon_price );
				}
				break;
			default:
				$addon_price = wmc_get_price( $addon_price );
		}

		return $addon_price;
	}

	public function woocommerce_get_item_data( $other_data, $cart_item ) {
		if ( self::$settings->get_default_currency() !== self::$settings->get_current_currency() && class_exists( 'WC_Product_Addons_Helper' ) ) {
			if ( ! empty( $cart_item['addons'] ) ) {
				foreach ( $cart_item['addons'] as $addon ) {
					$price = isset( $cart_item['addons_price_before_calc'] ) ? $cart_item['addons_price_before_calc'] : $addon['price'];
					$name  = $addon['name'];

					if ( 0 == $addon['price'] ) {

					} elseif ( 'percentage_based' === $addon['price_type'] && 0 == $price ) {

					} elseif ( 'percentage_based' !== $addon['price_type'] && $addon['price'] && apply_filters( 'woocommerce_addons_add_price_to_name', true, $addon ) ) {
						$old_name = $name . ' (' . wc_price( WC_Product_Addons_Helper::get_product_addon_price_for_display( $addon['price'], $cart_item['data'], true ) ) . ')';
						foreach ( $other_data as $other_data_k => $other_data_v ) {
							if ( $other_data_v['name'] === $old_name && $other_data_v['value'] === $addon['value'] ) {
								unset( $other_data[ $other_data_k ] );
								$other_data = array_values( $other_data );
								break;
							}
						}
						$name         .= ' (' . wc_price( wmc_get_price( WC_Product_Addons_Helper::get_product_addon_price_for_display( $addon['price'], $cart_item['data'], true ) ) ) . ')';
						$other_data[] = array(
							'name'    => $name,
							'value'   => $addon['value'],
							'display' => $addon['field_type'] === 'custom_price' ? wc_price( wmc_get_price( $addon['price'] ) ) : ( isset( $addon['display'] ) ? $addon['display'] : '' ),
						);
					} else {

					}
				}
			}
		}

		return $other_data;
	}

	public function woocommerce_product_addons_order_line_item_meta( $meta_data, $addon, $item, $values ) {
		$key           = $addon['name'];
		$price_type    = $addon['price_type'];
		$product       = $item->get_product();
		$product_price = $product->get_price();

		if ( $addon['price'] && 'percentage_based' === $price_type && 0 != $product_price ) {
			$addon_price = $product_price * ( $addon['price'] / 100 );
		} else {
			$addon_price = $addon['price'];
		}

		$price = html_entity_decode(
			wp_strip_all_tags( wc_price( wmc_get_price( WC_Product_Addons_Helper::get_product_addon_price_for_display( $addon_price, $values['data'] ) ) ) ),
			ENT_QUOTES, get_bloginfo( 'charset' )
		);

		if ( $addon['price'] && apply_filters( 'woocommerce_addons_add_price_to_name', true ) ) {
			$key .= ' (' . $price . ')';
		}

		if ( 'custom_price' === $addon['field_type'] ) {
			$addon['value'] = $addon['price'];
		}

		$meta_data = [
			'key'   => $key,
			'value' => $addon['value'],
			'id'    => $addon['id']
		];

		return $meta_data;
	}
}
