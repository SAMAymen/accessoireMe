<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_F_Data {
	protected static $instance = null;
	protected static $allow_html = null;
	private $params;

	/**
	 * WOOMULTI_CURRENCY_F_Data constructor.
	 * Init setting
	 */
	public function __construct() {
		global $wmc_settings;

		if ( ! $wmc_settings ) {
			$wmc_settings                      = get_option( 'woo_multi_currency_params', array() );
			$wmc_settings['currency_core']     = get_option( 'woocommerce_currency' );
			$wmc_settings['decimals_core']     = get_option( 'woocommerce_price_num_decimals' );
			$wmc_settings['currency_pos_core'] = get_option( 'woocommerce_currency_pos' );
		}
		$this->params = $wmc_settings;
		$args         = array(
			'enable'             => 0,
			'enable_fixed_price' => 0,
			'currency_default'   => $wmc_settings['currency_core'],
			'cache_compatible'   => 0,

			'currency'                   => array( $wmc_settings['currency_core'] ),
			'currency_rate'              => array( 1 ),
			'currency_rate_fee'          => array( 0 ),
			'currency_hidden'            => array( 0 ),
			'currency_decimals'          => array( $wmc_settings['decimals_core'] ),
			'currency_custom'            => array( '' ),
			'currency_pos'               => array( $wmc_settings['currency_pos_core'] ),
			'auto_detect'                => 0,
			'enable_currency_by_country' => 0,

			/*Checkout*/
			'enable_multi_payment'       => 0,
			'enable_cart_page'           => 0,

			/*Design*/
			'enable_design'              => 0,
			'title'                      => '',
			'design_position'            => 0,
			'enable_collapse'            => 0,
			'text_color'                 => '#fff',
			'background_color'           => '#212121',
			'main_color'                 => '#f78080',
			'flag_custom'                => '',
			'sidebar_style'              => 0,

			//Product price switcher
			'price_switcher'          => 0,
			'price_switcher_position' => 0,

			//Shortcode
			'shortcode_bg_color'         => '#ffffff',
			'shortcode_active_bg_color'  => '#ffffff',
			'shortcode_color'            => '#212121',
			'shortcode_active_color'     => '#212121',
			'shortcode_border_color'     => 0,

			/*Auto update*/
			'is_checkout'                => 0,
			'is_cart'                    => 0,
			'conditional_tags'           => '',
			'custom_css'                 => '',
			'rate_decimals'              => 5,
			'checkout_currency'          => $wmc_settings['currency_core'],
			'checkout_currency_args'     => array(),
			'geo_api'                    => 0,
			'rel_nofollow'               => 0,
			'bot_currency'               => 'default_currency',
		);
		$this->params = apply_filters( 'wmc_settings_args', wp_parse_args( $this->params, $args ) );
	}

	public static function get_ins() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get option Price switcher
	 * @return mixed
	 */
	public function get_price_switcher() {
		return apply_filters( 'wmc_get_price_switcher', $this->params['price_switcher'] );
	}

	/**
	 * Enable collapse
	 * @return mixed
	 */
	public function enable_collapse() {
		return apply_filters( 'wmc_enable_collapse', $this->params['enable_collapse'] );
	}

	/**
	 * Get sidebar style
	 * @return mixed
	 */
	public function get_sidebar_style() {
		return apply_filters( 'wmc_get_sidebar_style', $this->params['sidebar_style'] );
	}


	/**
	 * Check Geo APi
	 * @return mixed
	 */
	public function get_geo_api() {
		return apply_filters( 'wmc_get_geo_api', $this->params['geo_api'] );
	}

	/**
	 * Check Conditional tag
	 * @return mixed
	 */
	public function get_conditional_tags() {
		return apply_filters( 'wmc_get_conditional_tags', $this->params['conditional_tags'] );
	}

	/**
	 * Check  hidden on cart page
	 * @return mixed
	 */
	public function is_cart() {
		return apply_filters( 'wmc_is_cart', $this->params['is_cart'] );
	}

	/**
	 * Check  hidden on checkout page
	 * @return mixed
	 */
	public function is_checkout() {
		return apply_filters( 'wmc_is_checkout', $this->params['is_checkout'] );
	}

	/**
	 * Get custom CSS
	 * @return mixed
	 */
	public function get_custom_css() {
		return apply_filters( 'wmc_get_custom_css', $this->params['custom_css'] );
	}


	/**237 countries.
	 * Two-letter country code (ISO 3166-1 alpha-2) => Three-letter currency code (ISO 4217).
	 *
	 * @param $country_code
	 *
	 * @return bool|mixed
	 */
	function get_currency_code( $country_code ) {
		if ( ! $country_code ) {
			return false;
		}
		$arg = array(
			'AF' => 'AFN',
			'AL' => 'ALL',
			'DZ' => 'DZD',
			'AS' => 'USD',
			'AD' => 'EUR',
			'AO' => 'AOA',
			'AI' => 'XCD',
			'AQ' => 'XCD',
			'AG' => 'XCD',
			'AR' => 'ARS',
			'AM' => 'AMD',
			'AW' => 'AWG',
			'AU' => 'AUD',
			'AT' => 'EUR',
			'AZ' => 'AZN',
			'BS' => 'BSD',
			'BH' => 'BHD',
			'BD' => 'BDT',
			'BB' => 'BBD',
			'BY' => 'BYR',
			'BE' => 'EUR',
			'BZ' => 'BZD',
			'BJ' => 'XOF',
			'BM' => 'BMD',
			'BT' => 'BTN',
			'BO' => 'BOB',
			'BA' => 'BAM',
			'BW' => 'BWP',
			'BV' => 'NOK',
			'BR' => 'BRL',
			'IO' => 'USD',
			'BN' => 'BND',
			'BG' => 'BGN',
			'BF' => 'XOF',
			'BI' => 'BIF',
			'KH' => 'KHR',
			'CM' => 'XAF',
			'CA' => 'CAD',
			'CV' => 'CVE',
			'KY' => 'KYD',
			'CF' => 'XAF',
			'TD' => 'XAF',
			'CL' => 'CLP',
			'CN' => 'CNY',
			'HK' => 'HKD',
			'CX' => 'AUD',
			'CC' => 'AUD',
			'CO' => 'COP',
			'KM' => 'KMF',
			'CG' => 'XAF',
			'CD' => 'CDF',
			'CK' => 'NZD',
			'CR' => 'CRC',
			'HR' => 'HRK',
			'CU' => 'CUP',
			'CY' => 'EUR',
			'CZ' => 'CZK',
			'DK' => 'DKK',
			'DJ' => 'DJF',
			'DM' => 'XCD',
			'DO' => 'DOP',
			'EC' => 'ECS',
			'EG' => 'EGP',
			'SV' => 'SVC',
			'GQ' => 'XAF',
			'ER' => 'ERN',
			'EE' => 'EUR',
			'ET' => 'ETB',
			'FK' => 'FKP',
			'FO' => 'DKK',
			'FJ' => 'FJD',
			'FI' => 'EUR',
			'FR' => 'EUR',
			'GF' => 'EUR',
			'TF' => 'EUR',
			'GA' => 'XAF',
			'GM' => 'GMD',
			'GE' => 'GEL',
			'DE' => 'EUR',
			'GH' => 'GHS',
			'GI' => 'GIP',
			'GR' => 'EUR',
			'GL' => 'DKK',
			'GD' => 'XCD',
			'GP' => 'EUR',
			'GU' => 'USD',
			'GT' => 'QTQ',
			'GG' => 'GGP',
			'GN' => 'GNF',
			'GW' => 'GWP',
			'GY' => 'GYD',
			'HT' => 'HTG',
			'HM' => 'AUD',
			'HN' => 'HNL',
			'HU' => 'HUF',
			'IS' => 'ISK',
			'IN' => 'INR',
			'ID' => 'IDR',
			'IR' => 'IRR',
			'IQ' => 'IQD',
			'IE' => 'EUR',
			'IM' => 'GBP',
			'IL' => 'ILS',
			'IT' => 'EUR',
			'JM' => 'JMD',
			'JP' => 'JPY',
			'JE' => 'GBP',
			'JO' => 'JOD',
			'KZ' => 'KZT',
			'KE' => 'KES',
			'KI' => 'AUD',
			'KP' => 'KPW',
			'KR' => 'KRW',
			'KW' => 'KWD',
			'KG' => 'KGS',
			'LA' => 'LAK',
			'LV' => 'EUR',
			'LB' => 'LBP',
			'LS' => 'LSL',
			'LR' => 'LRD',
			'LY' => 'LYD',
			'LI' => 'CHF',
			'LT' => 'EUR',
			'LU' => 'EUR',
			'MK' => 'MKD',
			'MG' => 'MGF',
			'MW' => 'MWK',
			'MY' => 'MYR',
			'MV' => 'MVR',
			'ML' => 'XOF',
			'MT' => 'EUR',
			'MH' => 'USD',
			'MQ' => 'EUR',
			'MR' => 'MRO',
			'MU' => 'MUR',
			'YT' => 'EUR',
			'MX' => 'MXN',
			'FM' => 'USD',
			'MD' => 'MDL',
			'MC' => 'EUR',
			'MN' => 'MNT',
			'ME' => 'EUR',
			'MS' => 'XCD',
			'MA' => 'MAD',
			'MZ' => 'MZN',
			'MM' => 'MMK',
			'NA' => 'NAD',
			'NR' => 'AUD',
			'NP' => 'NPR',
			'NL' => 'EUR',
			'AN' => 'ANG',
			'NC' => 'XPF',
			'NZ' => 'NZD',
			'NI' => 'NIO',
			'NE' => 'XOF',
			'NG' => 'NGN',
			'NU' => 'NZD',
			'NF' => 'AUD',
			'MP' => 'USD',
			'NO' => 'NOK',
			'OM' => 'OMR',
			'PK' => 'PKR',
			'PW' => 'USD',
			'PA' => 'PAB',
			'PG' => 'PGK',
			'PY' => 'PYG',
			'PE' => 'PEN',
			'PH' => 'PHP',
			'PN' => 'NZD',
			'PL' => 'PLN',
			'PT' => 'EUR',
			'PR' => 'USD',
			'QA' => 'QAR',
			'RE' => 'EUR',
			'RO' => 'RON',
			'RU' => 'RUB',
			'RW' => 'RWF',
			'SH' => 'SHP',
			'KN' => 'XCD',
			'LC' => 'XCD',
			'PM' => 'EUR',
			'VC' => 'XCD',
			'WS' => 'WST',
			'SM' => 'EUR',
			'ST' => 'STD',
			'SA' => 'SAR',
			'SN' => 'XOF',
			'RS' => 'RSD',
			'SC' => 'SCR',
			'SL' => 'SLL',
			'SG' => 'SGD',
			'SK' => 'EUR',
			'SI' => 'EUR',
			'SB' => 'SBD',
			'SO' => 'SOS',
			'ZA' => 'ZAR',
			'GS' => 'GBP',
			'SS' => 'SSP',
			'ES' => 'EUR',
			'LK' => 'LKR',
			'SD' => 'SDG',
			'SR' => 'SRD',
			'SJ' => 'NOK',
			'SZ' => 'SZL',
			'SE' => 'SEK',
			'CH' => 'CHF',
			'SY' => 'SYP',
			'TW' => 'TWD',
			'TJ' => 'TJS',
			'TZ' => 'TZS',
			'TH' => 'THB',
			'TG' => 'XOF',
			'TK' => 'NZD',
			'TO' => 'TOP',
			'TT' => 'TTD',
			'TN' => 'TND',
			'TR' => 'TRY',
			'TM' => 'TMT',
			'TC' => 'USD',
			'TV' => 'AUD',
			'UG' => 'UGX',
			'UA' => 'UAH',
			'AE' => 'AED',
			'GB' => 'GBP',
			'US' => 'USD',
			'UM' => 'USD',
			'UY' => 'UYU',
			'UZ' => 'UZS',
			'VU' => 'VUV',
			'VE' => 'VEF',
			'VN' => 'VND',
			'VI' => 'USD',
			'WF' => 'XPF',
			'EH' => 'MAD',
			'YE' => 'YER',
			'ZM' => 'ZMW',
			'ZW' => 'ZWD',
		);

		return apply_filters( 'wmc_get_currency_code', $arg[ $country_code ], $arg, $country_code );
	}

	/**Get country code by currency
	 *
	 * @param $currency_code
	 *
	 * @return array
	 */
	public function get_country_data( $currency_code ) {
		$countries     = array(
			'AFN' => 'AF',
			'ALL' => 'AL',
			'DZD' => 'DZ',
			'USD' => 'US',
			'EUR' => 'EU',
			'AOA' => 'AO',
			'XCD' => 'LC',
			'ARS' => 'AR',
			'AMD' => 'AM',
			'AWG' => 'AW',
			'AUD' => 'AU',
			'AZN' => 'AZ',
			'BSD' => 'BS',
			'BHD' => 'BH',
			'BDT' => 'BD',
			'BBD' => 'BB',
			'BYR' => 'BY',
			'BYN' => 'BY',
			'BZD' => 'BZ',
			'XOF' => 'BJ',
			'BMD' => 'BM',
			'BTN' => 'BT',
			'BOB' => 'BO',
			'BAM' => 'BA',
			'BWP' => 'BW',
			'NOK' => 'NO',
			'BRL' => 'BR',
			'BND' => 'BN',
			'BGN' => 'BG',
			'BIF' => 'BI',
			'KHR' => 'KH',
			'XAF' => 'CM',
			'CAD' => 'CA',
			'CVE' => 'CV',
			'KYD' => 'KY',
			'CLP' => 'CL',
			'CNY' => 'CN',
			'HKD' => 'HK',
			'COP' => 'CO',
			'KMF' => 'KM',
			'CDF' => 'CD',
			'NZD' => 'NZ',
			'CRC' => 'CR',
			'HRK' => 'HR',
			'CUP' => 'CU',
			'CZK' => 'CZ',
			'DKK' => 'DK',
			'DJF' => 'DJ',
			'DOP' => 'DO',
			'ECS' => 'EC',
			'EGP' => 'EG',
			'SVC' => 'SV',
			'ERN' => 'ER',
			'ETB' => 'ET',
			'FKP' => 'FK',
			'FJD' => 'FJ',
			'GMD' => 'GM',
			'GEL' => 'GE',
			'GHS' => 'GH',
			'GIP' => 'GI',
			'QTQ' => 'GT',
			'GGP' => 'GG',
			'GNF' => 'GN',
			'GWP' => 'GW',
			'GYD' => 'GY',
			'HTG' => 'HT',
			'HNL' => 'HN',
			'HUF' => 'HU',
			'ISK' => 'IS',
			'INR' => 'IN',
			'IDR' => 'ID',
			'IRR' => 'IR',
			'IQD' => 'IQ',
			'GBP' => 'GB',
			'ILS' => 'IL',
			'JMD' => 'JM',
			'JPY' => 'JP',
			'JOD' => 'JO',
			'KZT' => 'KZ',
			'KES' => 'KE',
			'KPW' => 'KP',
			'KRW' => 'KR',
			'KWD' => 'KW',
			'KGS' => 'KG',
			'LAK' => 'LA',
			'LBP' => 'LB',
			'LSL' => 'LS',
			'LRD' => 'LR',
			'LYD' => 'LY',
			'CHF' => 'CH',
			'MKD' => 'MK',
			'MGF' => 'MG',
			'MWK' => 'MW',
			'MYR' => 'MY',
			'MVR' => 'MV',
			'MRO' => 'MR',
			'MUR' => 'MU',
			'MXN' => 'MX',
			'MDL' => 'MD',
			'MNT' => 'MN',
			'MAD' => 'MA',
			'MZN' => 'MZ',
			'MMK' => 'MM',
			'NAD' => 'NA',
			'NPR' => 'NP',
			'ANG' => 'AN',
			'XPF' => 'WF',
			'NIO' => 'NI',
			'NGN' => 'NG',
			'OMR' => 'OM',
			'PKR' => 'PK',
			'PAB' => 'PA',
			'PGK' => 'PG',
			'PYG' => 'PY',
			'PEN' => 'PE',
			'PHP' => 'PH',
			'PLN' => 'PL',
			'QAR' => 'QA',
			'RON' => 'RO',
			'RUB' => 'RU',
			'RWF' => 'RW',
			'SHP' => 'SH',
			'WST' => 'WS',
			'STD' => 'ST',
			'SAR' => 'SA',
			'RSD' => 'RS',
			'SCR' => 'SC',
			'SLL' => 'SL',
			'SGD' => 'SG',
			'SBD' => 'SB',
			'SOS' => 'SO',
			'ZAR' => 'ZA',
			'SSP' => 'SS',
			'LKR' => 'LK',
			'SDG' => 'SD',
			'SRD' => 'SR',
			'SZL' => 'SZ',
			'SEK' => 'SE',
			'SYP' => 'SY',
			'TWD' => 'TW',
			'TJS' => 'TJ',
			'TZS' => 'TZ',
			'THB' => 'TH',
			'TOP' => 'TO',
			'TTD' => 'TT',
			'TND' => 'TN',
			'TRY' => 'TR',
			'TMT' => 'TM',
			'UGX' => 'UG',
			'UAH' => 'UA',
			'AED' => 'AE',
			'UYU' => 'UY',
			'UZS' => 'UZ',
			'VUV' => 'VU',
			'VEF' => 'VE',
			'VND' => 'VN',
			'YER' => 'YE',
			'ZMW' => 'ZM',
			'ZWD' => 'ZW',
			'BTC' => 'XBT',
			'ETH' => 'ETH',
			'MOP' => 'MO',
			'ZWL' => 'ZW',
		);
		$country_names = WC()->countries->countries;
		$data          = array();

		/*Custom Flag*/
		$custom_flags = $this->get_flag_custom();
		if ( is_array( $custom_flags ) && count( array_filter( $custom_flags ) ) ) {
			$countries = array_merge( $countries, $custom_flags );
		}

		if ( isset( $countries[ $currency_code ] ) && $currency_code ) {
			$data['code'] = $countries[ $currency_code ];
			switch ( $currency_code ) {
				case 'EUR':
					$data['name'] = esc_attr__( 'European Union', 'woo-multi-currency' );
					break;
				default:
					$data['name'] = isset( $country_names[ $countries[ $currency_code ] ] ) ? $country_names[ $countries[ $currency_code ] ] : 'Unknown';
			}

		} else {
			$data['code'] = '_unknown';
			$data['name'] = 'Unknown';
		}

		return $data;
	}

	/**
	 * Custom flag
	 * @return mixed
	 */
	public function get_flag_custom() {
		$value      = array();
		$data_codes = $this->params['flag_custom'];
		if ( $data_codes ) {
			$args = array_filter( explode( "\n", $data_codes ) );
			if ( count( $args ) ) {
				foreach ( $args as $arg ) {
					$code = array_filter( explode( ",", strtoupper( $arg ) ) );
					if ( count( $code ) == 2 ) {
						$code              = array_map( 'trim', $code );
						$value[ $code[0] ] = $code[1];
					}
				}
			}
		} else {
			return array();
		}

		return apply_filters( 'wmc_get_flag_custom', $value );
	}

	/**
	 * Get Links to redirect
	 * @return array
	 */
	public function get_links() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_woo_multi_currency_nonce'] ), 'woo_multi_currency_data' ) ) {
			return array();
		}

		$links               = array();
		$selected_currencies = $this->get_list_currencies();
		$current_currency    = $this->get_current_currency();
		$url                 = ! empty( $_POST['wmc_current_url'] ) ? sanitize_text_field( $_POST['wmc_current_url'] ) : false;
		if ( count( $selected_currencies ) ) {
			foreach ( $selected_currencies as $k => $currency ) {
				if ( $currency['hide'] ) {
					continue;
				}
				/*Remove unsupported currencies from widget and currency bar on checkout and cart page*/
//				if ( ( is_checkout() ) ) {
//					continue;
//				}
				/*Override min price and max price*/
				$arg = array( 'wmc-currency' => $k );
				if ( $current_currency == $k ) {
					if ( isset( $_GET['min_price'] ) ) {
						$arg['min_price'] = floatval( sanitize_text_field( $_GET['min_price'] ) );
					}
					if ( isset( $_GET['max_price'] ) ) {
						$arg['max_price'] = floatval( sanitize_text_field( $_GET['max_price'] ) );
					}
				} else {
					if ( isset( $_GET['min_price'] ) ) {
						$arg['min_price'] = ( floatval( sanitize_text_field( $_GET['min_price'] ) ) / $selected_currencies[ $current_currency ]['rate'] ) * $currency['rate'];
					}
					if ( isset( $_GET['max_price'] ) ) {
						$arg['max_price'] = ( floatval( sanitize_text_field( $_GET['max_price'] ) ) / $selected_currencies[ $current_currency ]['rate'] ) * $currency['rate'];
					}
				}
				$link        = apply_filters( 'wmc_get_link', add_query_arg( $arg, $url ), $k, $currency );
				$links[ $k ] = $link;
			}

		}

		return apply_filters( 'wmc_get_links', $links );
	}

	/**
	 * Get list currencies
	 * @return mixed
	 */
	public function get_list_currencies() {
		$data = array();
		if ( count( $this->params['currency'] ) ) {
			foreach ( $this->params['currency'] as $k => $currency ) {
				if ( ! isset( $this->params['currency_rate_fee'][ $k ] ) ) {
					$this->params['currency_rate_fee'][ $k ] = 0;
				}
				$data[ $currency ]['rate']     = ! $this->params['currency_rate_fee'][ $k ] ? $this->params['currency_rate'][ $k ] : $this->params['currency_rate'][ $k ] + $this->params['currency_rate_fee'][ $k ];
				$data[ $currency ]['pos']      = $this->params['currency_pos'][ $k ];
				$data[ $currency ]['decimals'] = $this->params['currency_decimals'][ $k ];
				$data[ $currency ]['custom']   = $this->params['currency_custom'][ $k ];
				$data[ $currency ]['hide']     = isset( $this->params['currency_hidden'][ $k ] ) ? $this->params['currency_hidden'][ $k ] : 0;
			}
		}


		return apply_filters( 'wmc_get_list_currencies', $data );
	}

	/**
	 * Get current currency
	 * @return mixed
	 */
	public function get_current_currency() {

		/*Check currency*/
		$selected_currencies = $this->get_currencies();
		$current_currency    = $this->getcookie( 'wmc_current_currency' );
		if ( ! $current_currency || ! in_array( $current_currency, $selected_currencies ) ) {
			$current_currency = get_option( 'woocommerce_currency' );
		}

		return $current_currency;
	}

	public function get_currencies() {

		return apply_filters( 'wmc_get_currencies', $this->params['currency'] );

	}

	/**
	 * Get Cookie or Session
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function getcookie( $name ) {

		return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : false;
	}

	/**
	 * List shortcodes on widget or content
	 * @return mixed
	 */
	public function get_list_shortcodes() {
		return apply_filters(
			'wmc_get_list_shortcodes', array(
				''                 => esc_html__( 'Default', 'woo-multi-currency' ),
				'plain_horizontal' => esc_html__( 'Plain Horizontal', 'woo-multi-currency' ),
				'plain_vertical'   => esc_html__( 'Plain Vertical', 'woo-multi-currency' ),
				'plain_vertical_2' => esc_html__( 'Listbox currency code', 'woo-multi-currency' ),
				'layout3'          => esc_html__( 'List Flag Horizontal', 'woo-multi-currency' ),
				'layout4'          => esc_html__( 'List Flag Vertical', 'woo-multi-currency' ),
				'layout5'          => esc_html__( 'List Flag + Currency Code', 'woo-multi-currency' ),
				'layout6'          => esc_html__( 'Horizontal Currency Symbols', 'woo-multi-currency' ),
				'layout9'          => esc_html__( 'Horizontal Currency Slide', 'woo-multi-currency' ),
				'layout7'          => esc_html__( 'Vertical Currency Symbols', 'woo-multi-currency' ),
				'layout8'          => esc_html__( 'Vertical Currency Symbols (circle)', 'woo-multi-currency' ),
				'layout10'         => esc_html__( 'Flag + Country + Currency + Symbol', 'woo-multi-currency' ),
			)
		);
	}

	/**
	 * Check fixed price
	 * @return mixed
	 */
	public function check_fixed_price() {
		return apply_filters( 'wmc_check_fixed_price', $this->params['enable_fixed_price'] );
	}

	/**
	 * Get title on design
	 * @return mixed
	 */
	public function get_design_title() {
		return apply_filters( 'wmc_get_design_title', $this->params['design_title'] );
	}

	/**
	 * Get Main color
	 * @return mixed
	 */
	public function get_main_color() {
		return apply_filters( 'wmc_get_main_color', $this->params['main_color'] );
	}

	/**
	 * Check design enable
	 * @return mixed
	 */
	public function get_enable_design() {
		if ( $this->params['enable_design'] && $this->params['enable'] ) {
			return apply_filters( 'wmc_get_enable_design', $this->params['enable_design'] );
		} else {
			return false;
		}
	}

	/**
	 * Get design position
	 * @return mixed
	 */
	public function get_design_position() {
		return apply_filters( 'wmc_get_design_position', $this->params['design_position'] );
	}

	/**
	 * Get text color on design
	 * @return mixed
	 */
	public function get_text_color() {
		return apply_filters( 'wmc_text_color', $this->params['text_color'] );
	}

	/**
	 * Get backround color of design
	 * @return mixed
	 */
	public function get_background_color() {
		return apply_filters( 'wmc_background_color', $this->params['background_color'] );
	}

	/**
	 * @param string $orginal_price
	 * @param string $other_price
	 *
	 * @return array
	 */
	public function get_exchange( $orginal_price = '', $other_price = '' ) {
		$rates = array();

		$data_rates = $this->get_default_exchange( $orginal_price, $other_price );

		if ( ! isset( $rates[ $orginal_price ] ) ) {
			$rates[ $orginal_price ] = 1;
		}
		if ( count( $rates ) ) {
			foreach ( $data_rates as $k => $rate ) {
				$rates[ $k ] = $rate == 1 ? 1 : number_format( round( $rate, $this->get_rate_decimals() ), $this->get_rate_decimals(), '.', '' );
			}
		}

		return $rates;
	}

	/**
	 * @param $orginal_price
	 * @param $other_price
	 *
	 * @return array|bool
	 */
	private function get_default_exchange( $orginal_price, $other_price ) {
		global $wp_version;
		$rates = array();

		if ( $orginal_price && $other_price ) {
			$url = 'https://api.villatheme.com/wp-json/exchange/v1';

			$request = wp_remote_post(
				$url, array(
					'user-agent' => 'WordPress/' . $wp_version . '; ' . get_site_url(),
					'timeout'    => 10,
					'body'       => array(
						'from' => $orginal_price,
						'to'   => $other_price
					)
				)
			);
			if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
				$rates = json_decode( trim( $request['body'] ), true );

			}
		} else {
			return false;
		}

		return apply_filters( 'wmc_get_exchange', $rates );

	}

	/**
	 * Get custom CSS
	 * @return mixed
	 */
	public function get_rate_decimals() {
		return apply_filters( 'wmc_get_rate_decimals', $this->params['rate_decimals'] );
	}

	public function get_country_freebase( $country_code ) {
		$countries = array(
			"AED" => "/m/02zl8q",
			"AFN" => "/m/019vxc",
			"ALL" => "/m/01n64b",
			"AMD" => "/m/033xr3",
			"ANG" => "/m/08njbf",
			"AOA" => "/m/03c7mb",
			"ARS" => "/m/024nzm",
			"AUD" => "/m/0kz1h",
			"AWG" => "/m/08s1k3",
			"AZN" => "/m/04bq4y",
			"BAM" => "/m/02lnq3",
			"BBD" => "/m/05hy7p",
			"BDT" => "/m/02gsv3",
			"BGN" => "/m/01nmfw",
			"BHD" => "/m/04wd20",
			"BIF" => "/m/05jc3y",
			"BMD" => "/m/04xb8t",
			"BND" => "/m/021x2r",
			"BOB" => "/m/04tkg7",
			"BRL" => "/m/03385m",
			"BSD" => "/m/01l6dm",
			"BTC" => "/m/05p0rrx",
			"BWP" => "/m/02nksv",
			"BYN" => "/m/05c9_x",
			"BZD" => "/m/02bwg4",
			"CAD" => "/m/0ptk_",
			"CDF" => "/m/04h1d6",
			"CHF" => "/m/01_h4b",
			"CLP" => "/m/0172zs",
			"CNY" => "/m/0hn4_",
			"COP" => "/m/034sw6",
			"CRC" => "/m/04wccn",
			"CUC" => "/m/049p2z",
			"CUP" => "/m/049p2z",
			"CVE" => "/m/06plyy",
			"CZK" => "/m/04rpc3",
			"DJF" => "/m/05yxn7",
			"DKK" => "/m/01j9nc",
			"DOP" => "/m/04lt7_",
			"DZD" => "/m/04wcz0",
			"EGP" => "/m/04phzg",
			"ETB" => "/m/02_mbk",
			"EUR" => "/m/02l6h",
			"FJD" => "/m/04xbp1",
			"GBP" => "/m/01nv4h",
			"GEL" => "/m/03nh77",
			"GHS" => "/m/01s733",
			"GMD" => "/m/04wctd",
			"GNF" => "/m/05yxld",
			"GTQ" => "/m/01crby",
			"GYD" => "/m/059mfk",
			"HKD" => "/m/02nb4kq",
			"HNL" => "/m/04krzv",
			"HRK" => "/m/02z8jt",
			"HTG" => "/m/04xrp0",
			"HUF" => "/m/01hfll",
			"IDR" => "/m/0203sy",
			"ILS" => "/m/01jcw8",
			"INR" => "/m/02gsvk",
			"IQD" => "/m/01kpb3",
			"IRR" => "/m/034n11",
			"ISK" => "/m/012nk9",
			"JMD" => "/m/04xc2m",
			"JOD" => "/m/028qvh",
			"JPY" => "/m/088n7",
			"KES" => "/m/05yxpb",
			"KGS" => "/m/04k5c6",
			"KHR" => "/m/03_m0v",
			"KMF" => "/m/05yxq3",
			"KRW" => "/m/01rn1k",
			"KWD" => "/m/01j2v3",
			"KYD" => "/m/04xbgl",
			"KZT" => "/m/01km4c",
			"LAK" => "/m/04k4j1",
			"LBP" => "/m/025tsrc",
			"LKR" => "/m/02gsxw",
			"LRD" => "/m/05g359",
			"LSL" => "/m/04xm1m",
			"LYD" => "/m/024xpm",
			"MAD" => "/m/06qsj1",
			"MDL" => "/m/02z6sq",
			"MGA" => "/m/04hx_7",
			"MKD" => "/m/022dkb",
			"MMK" => "/m/04r7gc",
			"MOP" => "/m/02fbly",
			"MRO" => "/m/023c2n",
			"MUR" => "/m/02scxb",
			"MVR" => "/m/02gsxf",
			"MWK" => "/m/0fr4w",
			"MXN" => "/m/012ts8",
			"MYR" => "/m/01_c9q",
			"MZN" => "/m/05yxqw",
			"NAD" => "/m/01y8jz",
			"NGN" => "/m/018cg3",
			"NIO" => "/m/02fvtk",
			"NOK" => "/m/0h5dw",
			"NPR" => "/m/02f4f4",
			"NZD" => "/m/015f1d",
			"OMR" => "/m/04_66x",
			"PAB" => "/m/0200cp",
			"PEN" => "/m/0b423v",
			"PGK" => "/m/04xblj",
			"PHP" => "/m/01h5bw",
			"PKR" => "/m/02svsf",
			"PLN" => "/m/0glfp",
			"PYG" => "/m/04w7dd",
			"QAR" => "/m/05lf7w",
			"RON" => "/m/02zsyq",
			"RSD" => "/m/02kz6b",
			"RUB" => "/m/01hy_q",
			"RWF" => "/m/05yxkm",
			"SAR" => "/m/02d1cm",
			"SBD" => "/m/05jpx1",
			"SCR" => "/m/01lvjz",
			"SDG" => "/m/08d4zw",
			"SEK" => "/m/0485n",
			"SGD" => "/m/02f32g",
			"SLL" => "/m/02vqvn",
			"SOS" => "/m/05yxgz",
			"SRD" => "/m/02dl9v",
			"SSP" => "/m/08d4zw",
			"STD" => "/m/06xywz",
			"SZL" => "/m/02pmxj",
			"THB" => "/m/0mcb5",
			"TJS" => "/m/0370bp",
			"TMT" => "/m/0425kx",
			"TND" => "/m/04z4ml",
			"TOP" => "/m/040qbv",
			"TRY" => "/m/04dq0w",
			"TTD" => "/m/04xcgz",
			"TWD" => "/m/01t0lt",
			"TZS" => "/m/04s1qh",
			"UAH" => "/m/035qkb",
			"UGX" => "/m/04b6vh",
			"USD" => "/m/09nqf",
			"UYU" => "/m/04wblx",
			"UZS" => "/m/04l7bl",
			"VEF" => "/m/021y_m",
			"VND" => "/m/03ksl6",
			"XAF" => "/m/025sw2b",
			"XCD" => "/m/02r4k",
			"XOF" => "/m/025sw2q",
			"XPF" => "/m/01qyjx",
			"YER" => "/m/05yxwz",
			"ZAR" => "/m/01rmbs",
			"ZMW" => "/m/0fr4f"
		);
		$data      = '';
		if ( $country_code && isset( $countries[ $country_code ] ) ) {
			$data = $countries[ $country_code ];
		}

		return $data;
	}

	/**Set currency in Cookie
	 *
	 * @param $currency_code
	 * @param bool $checkout
	 */
	public function set_current_currency( $currency_code, $checkout = true ) {
		if ( $currency_code ) {
			$this->setcookie( 'wmc_current_currency', $currency_code, time() + 60 * 60 * 24, '/' );
		}
		if ( ! $this->get_enable_multi_payment() && $checkout ) {
			$this->setcookie( 'wmc_current_currency_old', $currency_code, time() + 60 * 60 * 24, '/' );
		}
	}

	/**
	 * Set Cookie or Session
	 *
	 * @param        $name
	 * @param        $value
	 * @param int $time
	 * @param string $path
	 */
	public function setcookie( $name, $value, $time = 86400, $path = '/' ) {
		@setcookie( $name, $value, $time, $path );
		$_COOKIE[ $name ] = $value;
	}

	/**
	 * Check enable pay with multi currencies
	 * @return mixed
	 */
	public function get_enable_multi_payment() {
		return apply_filters( 'wmc_get_enable_multi_payment', $this->params['enable_multi_payment'] );

	}

	/**Get currency by country with WPML.org
	 *
	 * @param $language_slug
	 *
	 * @return array|mixed
	 */
	public function get_wpml_currency_by_language( $language_slug ) {

		if ( $language_slug ) {
			if ( isset( $this->params[ $language_slug . '_wpml_by_language' ] ) ) {
				$currency_code = $this->params[ $language_slug . '_wpml_by_language' ];
			} else {
				return array();
			}

			return apply_filters( 'wmc_get_currency_wpml_by_language' . $language_slug, $currency_code );
		} else {
			return array();
		}
	}

	/**Get currency by language
	 *
	 * @param $language_slug
	 *
	 * @return array|mixed
	 */
	public function get_currency_by_language( $language_slug ) {

		if ( $language_slug ) {
			if ( isset( $this->params[ $language_slug . '_by_language' ] ) ) {
				$currency_code = $this->params[ $language_slug . '_by_language' ];
			} else {
				return array();
			}

			return apply_filters( 'wmc_get_currency_by_language_' . $language_slug, $currency_code );
		} else {
			return array();
		}
	}

	/**Get currency by country
	 *
	 * @param $currency_code
	 *
	 * @return array|mixed
	 */
	public function get_currency_by_countries( $currency_code ) {

		if ( $currency_code ) {
			if ( isset( $this->params[ $currency_code . '_by_country' ] ) ) {
				$countries_code = $this->params[ $currency_code . '_by_country' ];
			} else {
				return array();
			}

			return apply_filters( 'wmc_get_currency_by_countries_' . $currency_code, $countries_code );
		} else {
			return array();
		}
	}

	/**Get payments available by currency code.
	 *
	 * @param $currency_code
	 *
	 * @return array|mixed
	 */
	public function get_payments_by_currency( $currency_code ) {

		if ( $currency_code ) {
			if ( isset( $this->params[ 'currency_payment_method_' . $currency_code ] ) ) {
				$payments = $this->params[ 'currency_payment_method_' . $currency_code ];
			} else {
				return array();
			}

			return apply_filters( 'wmc_get_payments_by_currency_' . $currency_code, $payments );
		} else {
			return array();
		}
	}

	/**
	 * Check enable currency by country
	 * @return mixed
	 */
	public function get_enable_currency_by_country() {
		return apply_filters( 'wmc_get_enable_currency_by_country', $this->params['enable_currency_by_country'] );

	}

	/**
	 * Get type of auto detect
	 * @return mixed
	 */
	public function get_auto_detect() {
		return apply_filters( 'wmc_get_auto_detect', $this->params['auto_detect'] );

	}

	/**
	 * Check Enable plugin
	 * @return mixed
	 */
	public function get_enable() {

		return apply_filters( 'wmc_get_enable', $this->params['enable'] );
	}

	/**
	 * Get currency default
	 * @return mixed
	 */
	public function get_default_currency() {
		return apply_filters( 'wmc_get_default_currency', $this->params['currency_default'] );

	}

	public function get_param( $param ) {
		return isset( $this->params[ $param ] ) ? $this->params[ $param ] : '';
	}

	public static function is_request_to_rest_api() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = '/' . untrailingslashit( rest_get_url_prefix() ) . '/';
		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		return false !== strpos( $request_uri, $rest_prefix );
	}

	public static function get_rel_nofollow() {
		$instance = self::get_ins();

		return $instance->get_param( 'rel_nofollow' ) ? 'rel=nofollow' : '';
	}

	public function get_params( $name = "", $language = '' ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			if ( $language ) {
				$name_language = $name . '_' . $language;
				if ( isset( $this->params[ $name_language ] ) ) {
					return apply_filters( 'woo_multi_currency_params-' . $name_language, $this->params[ $name_language ] );
				} else {
					return apply_filters( 'woo_multi_currency_params-' . $name_language, $this->params[ $name ] );
				}
			} else {
				return apply_filters( 'woo_multi_currency_params-' . $name, $this->params[ $name ] );
			}
		} else {
			return false;
		}
	}

	/**
	 * @param $price
	 * @param array $args
	 *
	 * @return float
	 */
	public static function convert_price_to_float( $price, $args = array() ) {
		$args           = apply_filters(
			'wc_price_args',
			wp_parse_args(
				$args,
				array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => wc_get_price_decimal_separator(),
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimals'           => wc_get_price_decimals(),
					'price_format'       => get_woocommerce_price_format(),
				)
			)
		);
		$original_price = $price;
		$negative       = $price < 0;
		$price          = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ), $original_price );
		$price          = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		return floatval( str_replace( array( $args['thousand_separator'], $args['decimal_separator'] ), array(
			'',
			'.'
		), $price ) );
	}

	public function set_fallback_currency() {
		$default_currency = $this->get_default_currency();
		$list_currencies  = $this->get_list_currencies();
		if ( $list_currencies[ $default_currency ]['hide'] !== '1' ) {
			$this->set_current_currency( $default_currency );
		} else {
			$need_set_default = true;
			foreach ( $list_currencies as $currency_code => $currency_data ) {
				if ( $currency_data['hide'] !== '1' ) {
					$this->set_current_currency( $currency_code );
					$need_set_default = false;
					break;
				}
			}
			if ( $need_set_default ) {
				$this->set_current_currency( $default_currency );
			}
		}
	}

	public static function wp_kses_post( $content ) {
		if ( self::$allow_html === null ) {
			self::$allow_html = wp_kses_allowed_html( 'post' );
			self::$allow_html = array_merge_recursive( self::$allow_html, array(
					'input'  => array(
						'type'         => 1,
						'id'           => 1,
						'name'         => 1,
						'class'        => 1,
						'placeholder'  => 1,
						'autocomplete' => 1,
						'style'        => 1,
						'value'        => 1,
						'size'         => 1,
						'checked'      => 1,
						'disabled'     => 1,
						'readonly'     => 1,
						'data-*'       => 1,
					),
					'form'   => array(
						'method' => 1,
						'id'     => 1,
						'class'  => 1,
						'action' => 1,
						'data-*' => 1,
					),
					'select' => array(
						'id'       => 1,
						'name'     => 1,
						'class'    => 1,
						'multiple' => 1,
						'onchange' => 1,
						'data-*'   => 1,
					),
					'option' => array(
						'value'    => 1,
						'selected' => 1,
						'data-*'   => 1,
					),
				)
			);
			foreach ( self::$allow_html as $key => $value ) {
				if ( $key === 'input' ) {
					self::$allow_html[ $key ]['data-*']   = 1;
					self::$allow_html[ $key ]['checked']  = 1;
					self::$allow_html[ $key ]['disabled'] = 1;
					self::$allow_html[ $key ]['readonly'] = 1;
				} elseif ( in_array( $key, array( 'div', 'span', 'a', 'form', 'select', 'option', 'tr', 'td' ) ) ) {
					self::$allow_html[ $key ]['data-*'] = 1;
				}
			}
		}
		add_filter( 'safe_style_css', array( __CLASS__, 'safe_style_css' ) );
		add_filter( 'safecss_filter_attr_allow_css', array( __CLASS__, 'safecss_filter_attr_allow_css' ), 10, 2 );
		$content = wp_kses( $content, self::$allow_html );
		remove_filter( 'safe_style_css', array( __CLASS__, 'safe_style_css' ) );
		remove_filter( 'safecss_filter_attr_allow_css', array( __CLASS__, 'safecss_filter_attr_allow_css' ) );

		return $content;
	}

	/**
	 * Allow transform
	 *
	 * @param $allow_css
	 * @param $css_test_string
	 *
	 * @return bool
	 */
	public static function safecss_filter_attr_allow_css( $allow_css, $css_test_string ) {
		if ( $css_test_string ) {
			if ( explode( ':', $css_test_string )[0] === 'transform' ) {
				$allow_css = true;
			}
		}

		return $allow_css;
	}

	/**
	 * Support transform
	 *
	 * @param $css
	 *
	 * @return array
	 */
	public static function safe_style_css( $css ) {
		$css[] = 'transform';

		return $css;
	}
}