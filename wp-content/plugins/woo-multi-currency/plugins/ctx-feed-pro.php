<?php

class WOOMULTI_CURRENCY_F_Plugin_CTX_Feed_Pro {
	public function __construct() {
		add_action( 'woo_feed_before_product_loop', function ( $productIds, $feedConfig ) {
			$data             = WOOMULTI_CURRENCY_F_Data::get_ins();
			$default_currency = $data->get_default_currency();

			if ( defined( 'WOO_FEED_PRO_FILE' ) ) {
				if ( $default_currency != $feedConfig['feedCurrency'] ) {
					$data->set_current_currency( $feedConfig['feedCurrency'] );
				} else {
					$data->set_current_currency( $default_currency );
				}
			} else {
				$feed_currency = $data->get_param( 'bot_currency' );
				if ( $feed_currency !== 'default_currency' ) {
					$data->set_current_currency( $feed_currency );
				} else {
					$data->set_current_currency( $default_currency );

				}
			}

		}, 10, 2 );

		add_action( 'woo_feed_after_product_loop', function ( $productIds, $feedConfig ) {
			$data          = WOOMULTI_CURRENCY_F_Data::get_ins();
			$currency_code = $data->get_default_currency();

			$data->set_current_currency( $currency_code );
		}, 10, 2 );

		add_filter( 'woo_feed_filter_product_link', function ( $link, $product, $config ) {
			$data             = WOOMULTI_CURRENCY_F_Data::get_ins();
			$default_currency = $data->get_default_currency();

			if ( defined( 'WOO_FEED_PRO_FILE' ) ) {
				$link = add_query_arg( [ 'wmc-currency' => $config->get_feed_currency() ], $link );
			} else {
				$feed_currency = $data->get_param( 'bot_currency' );
				if ( $feed_currency !== 'default_currency' ) {
					$link = add_query_arg( [ 'wmc-currency' => $feed_currency ], $link );
				} else {
					$link = add_query_arg( [ 'wmc-currency' => $default_currency ], $link );
				}
			}

			return $link;
		}, 10, 3 );
	}
}
