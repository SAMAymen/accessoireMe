<?php
/**
 * Created by PhpStorm.
 * User: Villatheme-Thanh
 * Date: 30-09-19
 * Time: 8:18 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//$_SERVER['HTTP_USER_AGENT']='/google.com';

class WOOMULTI_CURRENCY_F_Plugin_Google_Index {
	protected $settings;
	protected $woosea_currency;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();

		if ( $this->settings->get_enable() ) {
			add_action( 'init', array( $this, 'set_currency_for_bot' ), 999 );

			/* Google Listings and Ads */
			add_filter( 'woocommerce_gla_product_attribute_values', [ $this, 'modify_gg_product_attributes' ], 10, 3 );
//			add_filter( 'woocommerce_gla_product_attribute_value_price', [ $this, 'convert_currency_regular_price' ], 10, 3 );

			/*WooCommerce Google Product Feed - Ademti Software Ltd*/
			add_filter( 'woocommerce_gpf_feed_item', [ $this, 'add_currency_arg_to_product_permalinks' ], 10, 2 );

			add_filter( 'wmc_woosea_product_data', [ $this, 'woosea_product_permalinks' ], 10 );

			add_action( 'woosea_start_render_product_feed', [ $this, 'add_change_price_hook' ], 10 );
			add_action( 'woosea_end_render_product_feed', [ $this, 'remove_change_price_hook' ], 10 );
		}
	}

	public function set_currency_for_bot() {
		$bot_currency = false;
		if ( $this->is_google_bot() ) {
			$bot_currency = apply_filters( 'wmc_set_currency_for_google_bot_index', $this->get_bot_currency() );
		} elseif ( $this->isBot() ) {
			$bot_currency = apply_filters( 'wmc_set_currency_for_bot_index', $this->get_bot_currency() );
		}
		if ( $bot_currency !== false ) {
			if ( $bot_currency ) {
				$this->settings->set_current_currency( $bot_currency );
			} else {
				$this->settings->set_fallback_currency();
			}
		}
	}

	private function get_bot_currency() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_plugin' ) ) {
			return '';
		}

		$bot_currency    = $this->settings->get_params( 'bot_currency' );
		$list_currencies = $this->settings->get_list_currencies();
		if ( $bot_currency === 'default_currency' ) {
			$bot_currency = $this->settings->get_default_currency();
		} elseif ( $bot_currency ) {
			if ( empty( $list_currencies[ $bot_currency ] ) ) {
				$bot_currency = '';
			}
		}
		$passed_currency = '';
		if ( ! empty( $_GET['wmc-currency'] ) ) {
			$passed_currency = sanitize_text_field( $_GET['wmc-currency'] );
		} elseif ( ! empty( $_GET['currency'] ) ) {
			$passed_currency = sanitize_text_field( $_GET['currency'] );
		}
		if ( $passed_currency ) {
			if ( ! empty( $list_currencies[ $passed_currency ] ) ) {
				$bot_currency = $passed_currency;
			}
		}

		return $bot_currency;
	}

	public function is_google_bot() {
		$google_bots = apply_filters( 'wmc_google_bots_list', array(
			'googlebot',
			'google-sitemaps',
			'appEngine-google',
			'feedfetcher-google',
			'googlealert.com',
			'AdsBot-Google',
			'google'
		) );
		foreach ( $google_bots as $bot ) {
			if ( self::check_bot( $bot ) ) {
				return true;
			}
		}

		return false;
	}

	public function isBot() {
		$bots = apply_filters( 'wmc_other_bots_list', array(
//			'pixel',//confused with google pixel device
			'facebook',
			'rambler',
			'aport',
			'yahoo',
			'msnbot',
			'turtle',
			'mail.ru',
			'omsktele',
			'yetibot',
			'picsearch',
			'sape.bot',
			'sape_context',
			'gigabot',
			'snapbot',
			'alexa.com',
			'megadownload.net',
			'askpeter.info',
			'igde.ru',
			'ask.com',
			'qwartabot',
			'yanga.co.uk',
			'scoutjet',
			'similarpages',
			'oozbot',
			'shrinktheweb.com',
			'aboutusbot',
			'followsite.com',
			'dataparksearch',
			'liveinternet.ru',
			'xml-sitemaps.com',
			'agama',
			'metadatalabs.com',
			'h1.hrn.ru',
			'seo-rus.com',
			'yaDirectBot',
			'yandeG',
			'yandex',
			'yandexSomething',
			'Copyscape.com',
			'domaintools.com',
			'Nigma.ru',
			'bing.com',
			'dotnetdotcom',
		) );
		foreach ( $bots as $bot ) {
			if ( self::check_bot( $bot ) ) {
				return true;
			}
		}

		return false;
	}

	private static function check_bot( $bot ) {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) && ( stripos( $_SERVER['HTTP_USER_AGENT'], $bot ) !== false || preg_match( '/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'] ) );
	}


	public function add_currency_query_to_product_link( $link ) {
		$bot_currency    = $this->settings->get_params( 'bot_currency' );
		$list_currencies = $this->settings->get_currencies();

		if ( $bot_currency === 'default_currency' ) {
			$bot_currency = $this->settings->get_default_currency();
		} elseif ( $bot_currency ) {
			if ( ! in_array( $bot_currency, $list_currencies ) ) {
				$bot_currency = '';
			}
		}

		return $bot_currency ? add_query_arg( [ 'wmc-currency' => $bot_currency ], $link ) : $link;
	}

	/**
	 * @param $attributes
	 * @param $product WC_Product
	 * @param $adapter
	 *
	 * @return mixed
	 */
	public function modify_gg_product_attributes( $attributes, $product, $adapter ) {
		$link               = ! empty( $attributes['link'] ) ? $attributes['link'] : $product->get_permalink();
		$attributes['link'] = $this->add_currency_query_to_product_link( $link );

		return $attributes;
	}

	public function convert_currency_regular_price( $price, $product, $tax_excluded ) {

		return $price;
	}

	public function add_currency_arg_to_product_permalinks( $feed_item, $wc_product ) {
		$feed_item->purchase_link = $this->add_currency_query_to_product_link( $feed_item->purchase_link );

		return $feed_item;
	}

	public function woosea_product_permalinks( $product_data ) {
		if ( ! empty( $product_data['link'] ) ) {
			$product_data['link'] = $this->add_currency_query_to_product_link( $product_data['link'] );
		}

		return $product_data;
	}

	public function add_change_price_hook() {
		/*Simple product*/
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price' ), 99, 2 );

		/*Variable price*/
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'convert_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'convert_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'convert_price' ), 99, 2 );

	}

	public function remove_change_price_hook() {
		/*Simple product*/
		remove_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price' ), 99 );
		remove_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price' ), 99 );
		remove_filter( 'woocommerce_product_get_price', array( $this, 'convert_price' ), 99 );

		/*Variable price*/
		remove_filter( 'woocommerce_product_variation_get_price', array( $this, 'convert_price' ), 99 );
		remove_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'convert_price' ), 99 );
		remove_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'convert_price' ), 99 );

	}

	public function convert_price( $price, $product ) {
		if ( ! $price ) {
			return $price;
		}

		if ( ! $this->woosea_currency ) {
			$this->woosea_currency = $this->settings->get_params( 'bot_currency' );
			$list_currencies       = $this->settings->get_currencies();

			if ( $this->woosea_currency === 'default_currency' ) {
				$this->woosea_currency = $this->settings->get_default_currency();
			} elseif ( $this->woosea_currency ) {
				if ( ! in_array( $this->woosea_currency, $list_currencies ) ) {
					$this->woosea_currency = '';
				}
			}
		}

		if ( $this->woosea_currency ) {
			$price = wmc_get_price( $price, $this->woosea_currency );
		}

		return $price;
	}
}