<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Location
 */
class WOOMULTI_CURRENCY_F_Frontend_Location {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			/*Check change currency. Can not code in init function because Widget price can not get symbol.*/
			$list_currencies = $this->settings->get_list_currencies();

			if ( ! isset( $_GET['wmc_location_nonce'] ) || wp_verify_nonce( wc_clean( wp_unslash( $_GET['wmc_location_nonce'] ) ), 'wmc_location_nonce' ) ) {
				if ( isset( $_GET['wmc-currency'] ) ) {
					$target_currency = str_replace( '/', '', sanitize_text_field( $_GET['wmc-currency'] ) );
					if ( ! empty( $list_currencies[ $target_currency ] ) ) {
						if ( $list_currencies[ $target_currency ]['hide'] !== '1' ) {
							$this->settings->set_current_currency( $target_currency );
						}
					}
				}
			}
			add_action( 'init', array( $this, 'init' ), 1 );
		}
	}

	public function init() {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}
		if ( isset( $_GET['wmc_location_nonce'] ) && ! wp_verify_nonce( wc_clean( wp_unslash( $_GET['wmc_location_nonce'] ) ), 'wmc_location_nonce' ) ) {
			return;
		}
		$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
		if ( $action === 'wc_facebook_background_product_sync' ) {
			return;
		}
		$auto_detect = $this->settings->get_auto_detect();
		$currencies  = $this->settings->get_currencies();
		switch ( $auto_detect ) {
			case 1:
				/*Auto select currency*/
				if ( $this->settings->getcookie( 'wmc_current_currency' ) ) {
					$return = true;
					switch ( $this->settings->get_geo_api() ) {
						case 1:
//							$wmc_ip_add = $this->settings->getcookie( 'wmc_ip_add' );
//							if ( $wmc_ip_add !== $this->get_ip() ) {
//								$this->settings->setcookie( 'wmc_ip_add', '', time() - 3600 );
//								$this->settings->setcookie( 'wmc_ip_info', '', time() - 3600 );
//								$return = false;
//							}
							break;
						case 2:
							/*Update wmc_ip_info cookie if country is different from one saved in $_SERVER to handle page cache issue*/
							/*This helps auto-detect currency work with Kinsta cache but it will override that customers manually switch to*/
							if ( $this->settings->getcookie( 'wmc_ip_info' ) ) {
								$ip_info = json_decode( base64_decode( $this->settings->getcookie( 'wmc_ip_info' ) ), true );
								if ( ! empty( $ip_info['country'] ) ) {
									$server_detect = self::get_country_code_from_headers();
									if ( $server_detect && $ip_info['country'] !== $server_detect ) {
										$ip_info['country'] = $server_detect;
										$this->settings->setcookie( 'wmc_ip_info', base64_encode( wp_json_encode( $ip_info ) ), time() + 86400 );
										$return = false;
									}
								}
							}
							break;
						default:
					}

					if ( $return ) {
						return;
					}
				}
				/*Do not run if a request is rest api or cron*/
				if ( WOOMULTI_CURRENCY_F_Data::is_request_to_rest_api() || ! empty( $_REQUEST['doing_wp_cron'] ) ) {
					return;
				}
				$detect_ip_currency = $this->detect_ip_currency();
//					echo '<pre>'.print_r($detect_ip_currency,true).'</pre>';
				if ( $this->settings->get_enable_currency_by_country() && isset( $detect_ip_currency['country_code'] ) && $detect_ip_currency['country_code'] ) {
					$currency_detected = '';
					foreach ( $currencies as $currency ) {
						$data = $this->settings->get_currency_by_countries( $currency );
						if ( in_array( $detect_ip_currency['country_code'], $data ) ) {
							$currency_detected = $currency;
							break;
						}
					}
					if ( $currency_detected ) {
						$this->settings->set_current_currency( $currency_detected );
					} else {
						$this->settings->set_current_currency( $detect_ip_currency['currency_code'] );
					}
				} elseif ( isset( $detect_ip_currency['currency_code'] ) && in_array( $detect_ip_currency['currency_code'], $currencies ) ) {
					$this->settings->set_current_currency( $detect_ip_currency['currency_code'] );
				} else {
					$this->settings->set_fallback_currency();
				}
				break;

			case 2:
				/*Create approximately*/
//				if ( $this->settings->getcookie( 'wmc_currency_rate' ) ) {
				if ( $ip_info = $this->settings->getcookie( 'wmc_ip_info' ) ) {
					$ip_info         = json_decode( base64_decode( $ip_info ) );
					$currencies_list = $this->settings->get_list_currencies();
					$db_rate         = isset( $currencies_list[ $ip_info->currency_code ]['rate'] ) ? $currencies_list[ $ip_info->currency_code ]['rate'] : '';
					$cookie_rate     = $this->settings->getcookie( 'wmc_currency_rate' );
					if ( $db_rate == $cookie_rate ) {
						return;
					}
				}

				$detect_ip_currency = $this->detect_ip_currency();
				if ( isset( $detect_ip_currency['currency_code'] ) ) {
					$this->settings->setcookie( 'wmc_currency_rate', $detect_ip_currency['currency_rate'], time() + 86400 );
					$this->settings->setcookie( 'wmc_currency_symbol', $detect_ip_currency['currency_symbol'], time() + 86400 );
				}
				break;
			default:

		}
	}

	/**
	 * @return array|bool
	 */
	protected function detect_ip_currency() {
		if ( $this->settings->getcookie( 'wmc_ip_info' ) ) {
			$geoplugin_arg = json_decode( base64_decode( $this->settings->getcookie( 'wmc_ip_info' ) ), true );
		} else {
			switch ( $this->settings->get_geo_api() ) {
				case 1:
					$ip_add = $this->get_ip();
					$this->settings->setcookie( 'wmc_ip_add', $ip_add, time() + 86400 );
					if ( ! class_exists( 'geoPlugin' ) ) {
						require_once WOOMULTI_CURRENCY_F_INCLUDES . 'geoplugin.class.php';
					}
					$geo_plugin = new geoPlugin();
					$geoplugin  = $geo_plugin->fetch( "http://www.geoplugin.net/php.gp?ip={$ip_add}&base_currency=" . $this->settings->get_default_currency() );

					if ( $geoplugin ) {
						$geoplugin = unserialize( $geoplugin );
					}

					$geoplugin_arg = array(
						'country'       => isset( $geoplugin['geoplugin_countryCode'] ) ? $geoplugin['geoplugin_countryCode'] : 'US',
						'currency_code' => isset( $geoplugin['geoplugin_currencyCode'] ) ? $geoplugin['geoplugin_currencyCode'] : 'USD',
					);
					if ( ! empty( $geoplugin['geoplugin_currencyConverter'] ) ) {
						$geoplugin_arg['currency_rate'] = $geoplugin['geoplugin_currencyConverter'];
					}
					break;
				case 2:
					$country_code  = self::get_country_code_from_headers();
					$geoplugin_arg = array(
						'country'       => $country_code,
						'currency_code' => $this->settings->get_currency_code( $country_code )
					);
					break;
				default:
					$ip            = new WC_Geolocation();
					$geo_ip        = $ip->geolocate_ip();
					$country_code  = isset( $geo_ip['country'] ) ? $geo_ip['country'] : '';
					$geoplugin_arg = array(
						'country'       => $country_code,
						'currency_code' => $this->settings->get_currency_code( $country_code )
					);
			}

			if ( $geoplugin_arg['country'] ) {
				$this->settings->setcookie( 'wmc_ip_info', base64_encode( wp_json_encode( $geoplugin_arg ) ), time() + 86400 );
			} else {
				return array();
			}
		}

		$auto_detect = $this->settings->get_auto_detect();
		if ( $auto_detect == 1 ) {
			/*Auto select currency*/
			if ( is_array( $geoplugin_arg ) && isset( $geoplugin_arg['currency_code'] ) ) {
				$currencies = $this->settings->get_currencies();
				if ( ! in_array( $geoplugin_arg['currency_code'], $currencies ) ) {
					$geoplugin_arg['currency_code'] = $this->settings->get_default_currency();
				}

				return array(
					'currency_code' => $geoplugin_arg['currency_code'],
					'country_code'  => $geoplugin_arg['country']
				);
			} else {
				return array();
			}
		} elseif ( $auto_detect == 2 ) {
			/*Approximately price*/
			if ( is_array( $geoplugin_arg ) && isset( $geoplugin_arg['currency_code'] ) ) {
				$currency_code = $geoplugin_arg['currency_code'];
				$country_code  = $geoplugin_arg['country'];
				$symbol        = get_woocommerce_currency_symbol( $geoplugin_arg['currency_code'] );
			} else {
				return array();
			}
			$currencies        = $this->settings->get_currencies();
			$main_currency     = $this->settings->get_default_currency();
			$list_currencies   = $this->settings->get_list_currencies();
			$currency_detected = '';
			if ( $this->settings->get_enable_currency_by_country() ) {
				foreach ( $currencies as $currency ) {
					$data = $this->settings->get_currency_by_countries( $currency );
					if ( in_array( $country_code, $data ) ) {
						$currency_detected = $currency;
						break;
					}
				}
			}

			if ( $currency_detected ) {
				if ( $currency_detected !== $this->settings->get_current_currency() ) {
					return array(
						'currency_code'   => $currency_detected,
						'currency_rate'   => $list_currencies[ $currency_detected ]['rate'],
						'currency_symbol' => get_woocommerce_currency_symbol( $currency_detected )
					);
				} else {
					return array();
				}

			} else if ( in_array( $currency_code, $currencies ) ) {
				return array(
					'currency_code'   => $currency_code,
					'currency_rate'   => $list_currencies[ $currency_code ]['rate'],
					'currency_symbol' => get_woocommerce_currency_symbol( $currency_code )
				);
			} else {
				$exchange_rate = $this->settings->get_exchange( $main_currency, $currency_code );
				if ( is_array( $exchange_rate ) && isset( $exchange_rate[ $currency_code ] ) ) {
					return array(
						'currency_code'   => $currency_code,
						'currency_rate'   => $exchange_rate[ $currency_code ],
						'currency_symbol' => $symbol
					);
				} else {
					return array();
				}
			}

		} else {
			return array();
		}
	}

	/**
	 * Return IP
	 * @return string
	 */
	protected function get_ip() {
		if ( defined( 'WOO_MULTI_CURRENCY_CUSTOM_IP' ) ) {
			if ( isset( $_SERVER[ WOO_MULTI_CURRENCY_CUSTOM_IP ] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER[ WOO_MULTI_CURRENCY_CUSTOM_IP ] ) );
			}
		}
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		} else if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = self::validate_server_ip( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} else if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = self::validate_server_ip( $_SERVER['HTTP_X_FORWARDED'] );
		} else if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = self::validate_server_ip( $_SERVER['HTTP_FORWARDED_FOR'] );
		} else if ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = self::validate_server_ip( $_SERVER['HTTP_FORWARDED'] );
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	/**
	 * @param $ip
	 *
	 * @return string
	 */
	private static function validate_server_ip( $ip ) {
		return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $ip ) ) ) ) ) );
	}

	private static function get_country_code_from_headers() {
		$country_code = '';

		$headers = array(
			'HTTP_GEOIP_COUNTRY_CODE',
			'GEOIP_COUNTRY_CODE',
			'HTTP_CF_IPCOUNTRY',
			'MM_COUNTRY_CODE',
			'HTTP_X_COUNTRY_CODE',
		);

		foreach ( $headers as $header ) {
			if ( ! empty( $_SERVER[ $header ] ) ) {
				$country_code = strtoupper( sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );
				break;
			}
		}

		return $country_code;
	}
}