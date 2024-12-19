<?php
/***
 * Layout id list:
 * car_dealer
 * car_dealer_two
 * car_dealer_elementor
 * ev_dealer
 * car_rental
 * car_rental_elementor
 * rental_two
 * car_magazine
 * listing
 * listing_two
 * listing_three
 * listing_four
 * listing_five
 * listing_one_elementor
 * service
 * motorcycle
 * boats
 * auto_parts
 * aircrafts
 * equipment
 */
if ( ! function_exists( 'stm_is_layout' ) ) {
	function stm_is_layout( $layout_name ) {
		$current_template = get_option( 'stm_motors_chosen_template', '' );

		if ( ! empty( $current_template ) && $layout_name === $current_template ) {
			return true;
		}

		return false;
	}

	add_filter( 'stm_is_layout', 'stm_is_layout' );
}

if ( ! function_exists( 'stm_is_car_dealer' ) ) {
	add_filter( 'stm_is_car_dealer', 'stm_is_car_dealer' );
	function stm_is_car_dealer() {
		$listing = apply_filters( 'stm_theme_demo_layout', 'car_dealer' );

		if ( $listing ) {
			if ( 'car_dealer' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		if ( apply_filters( 'stm_is_elementor_demo', false ) && ( apply_filters( 'stm_is_layout', 'car_dealer_elementor' ) || apply_filters( 'stm_is_layout', 'car_dealer_elementor_rtl' ) ) ) {
			return true;
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_ev_dealer' ) ) {
	add_filter( 'stm_is_ev_dealer', 'stm_is_ev_dealer' );
	function stm_is_ev_dealer() {
		$current_template = get_option( 'stm_motors_chosen_template', 'ev_dealer' );

		if ( ! empty( $current_template ) ) {
			if ( 'ev_dealer' === $current_template ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'stm_is_listing' ) ) {
	add_filter( 'stm_is_listing', 'stm_is_listing' );
	function stm_is_listing() {
		$listing = get_option( 'stm_motors_chosen_template' );

		if ( $listing ) {
			if ( 'listing' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		if ( apply_filters( 'stm_is_elementor_demo', false ) && apply_filters( 'stm_is_layout', 'listing_one_elementor' ) ) {
			return true;
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_two' ) ) {
	add_filter( 'stm_is_listing_two', 'stm_is_listing_two' );
	function stm_is_listing_two() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_two' === $listing || 'listing_two_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_two_elementor' ) ) {
	add_filter( 'stm_is_listing_two_elementor', 'stm_is_listing_two_elementor' );
	function stm_is_listing_two_elementor() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_two_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_three' ) ) {
	add_filter( 'stm_is_listing_three', 'stm_is_listing_three' );
	function stm_is_listing_three() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_three' === $listing || 'listing_three_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_three_elementor' ) ) {
	add_filter( 'stm_is_listing_three_elementor', 'stm_is_listing_three_elementor' );
	function stm_is_listing_three_elementor() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_three_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_four' ) ) {
	add_filter( 'stm_is_listing_four', 'stm_is_listing_four' );
	function stm_is_listing_four() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( ( 'listing_four' === $listing ) || ( 'listing_four_elementor' === $listing ) ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_four_elementor' ) ) {
	add_filter( 'stm_is_listing_four_elementor', 'stm_is_listing_four_elementor' );
	function stm_is_listing_four_elementor() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_four_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_five' ) ) {
	add_filter( 'stm_is_listing_five', 'stm_is_listing_five' );
	function stm_is_listing_five() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_five' === $listing || 'listing_five_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_five_elementor' ) ) {
	add_filter( 'stm_is_listing_five_elementor', 'stm_is_listing_five_elementor' );
	function stm_is_listing_five_elementor() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_five_elementor' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_listing_six' ) ) {
	add_filter( 'stm_is_listing_six', 'stm_is_listing_six' );
	function stm_is_listing_six() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'listing_six' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'is_listing' ) ) {
	add_filter( 'is_listing', 'is_listing' );
	function is_listing( $only = array() ) {
		if ( ! empty( $only ) && count( $only ) > 0 ) {
			$listing = apply_filters( 'stm_theme_demo_layout', '' );

			foreach ( $only as $layout ) {
				if ( $layout === $listing ) {
					return true;
				}
			}
		} else {
			if ( apply_filters( 'stm_is_listing_five', false ) && defined( 'ULISTING_VERSION' ) ) {
				return false;
			}

			if ( apply_filters( 'stm_is_listing', false ) || apply_filters( 'stm_is_listing_two', false ) || apply_filters( 'stm_is_listing_three', false ) || apply_filters( 'stm_is_listing_four', false ) || apply_filters( 'stm_is_listing_five', false ) || apply_filters( 'stm_is_layout', 'listing_one_elementor' ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'is_dealer' ) ) {
	add_filter( 'is_dealer', 'is_dealer' );
	function is_dealer( $only = array() ) {
		if ( count( $only ) > 0 ) {
			$listing = apply_filters( 'stm_theme_demo_layout', '' );

			foreach ( $only as $layout ) {
				if ( $layout === $listing ) {
					return true;
				}
			}
		} else {
			if ( apply_filters( 'stm_is_car_dealer', false ) || apply_filters( 'stm_is_dealer_two', false ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'stm_is_boats' ) ) {
	add_filter( 'stm_is_boats', 'stm_is_boats' );
	function stm_is_boats() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'boats' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_motorcycle' ) ) {
	add_filter( 'stm_is_motorcycle', 'stm_is_motorcycle' );
	function stm_is_motorcycle() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'motorcycle' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_service' ) ) {
	add_filter( 'stm_is_service', 'stm_is_service' );
	function stm_is_service() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'service' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_rental' ) ) {
	add_filter( 'stm_is_rental', 'stm_is_rental' );
	function stm_is_rental() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'car_rental' === $listing || 'car_rental_elementor' === $listing || 'rental_two' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_rental_one_elementor' ) ) {
	add_filter( 'stm_is_rental_one_elementor', 'stm_is_rental_one_elementor' );
	function stm_is_rental_one_elementor() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		return ( 'car_rental_elementor' === $listing ) ? true : false;
	}
}

if ( ! function_exists( 'stm_is_rental_two' ) ) {
	add_filter( 'stm_is_rental_two', 'stm_is_rental_two' );
	function stm_is_rental_two() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		return ( 'rental_two' === $listing ) ? true : false;
	}
}

if ( ! function_exists( 'stm_is_magazine' ) ) {
	add_filter( 'stm_is_magazine', 'stm_is_magazine' );
	function stm_is_magazine() {
		$listing = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $listing ) {
			if ( 'car_magazine' === $listing ) {
				$listing = true;
			} else {
				$listing = false;
			}
		}

		return $listing;
	}
}

if ( ! function_exists( 'stm_is_dealer_two' ) ) {
	add_filter( 'stm_is_dealer_two', 'stm_is_dealer_two' );
	function stm_is_dealer_two() {
		$dealer = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $dealer ) {
			if ( 'car_dealer_two' === $dealer || 'car_dealer_two_elementor' === $dealer ) {
				$dealer = true;
			} else {
				$dealer = false;
			}
		}

		return $dealer;
	}
}

if ( ! function_exists( 'stm_is_elementor_dealer_two' ) ) {
	add_filter( 'stm_is_elementor_dealer_two', 'stm_is_elementor_dealer_two' );
	function stm_is_elementor_dealer_two() {
		$dealer = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $dealer ) {
			if ( 'car_dealer_two_elementor' === $dealer ) {
				$dealer = true;
			} else {
				$dealer = false;
			}
		}

		return $dealer;
	}
}

if ( ! function_exists( 'stm_is_aircrafts' ) ) {
	add_filter( 'stm_is_aircrafts', 'stm_is_aircrafts' );
	function stm_is_aircrafts() {
		$dealer = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $dealer ) {
			if ( 'aircrafts' === $dealer ) {
				$dealer = true;
			} else {
				$dealer = false;
			}
		}

		return $dealer;
	}
}

if ( ! function_exists( 'stm_is_auto_parts' ) ) {
	add_filter( 'stm_is_auto_parts', 'stm_is_auto_parts' );
	function stm_is_auto_parts() {
		$dealer = apply_filters( 'stm_theme_demo_layout', '' );

		if ( $dealer ) {
			if ( 'auto_parts' === $dealer ) {
				$dealer = true;
			} else {
				$dealer = false;
			}
		}

		return $dealer;
	}
}

if ( ! function_exists( 'stm_is_equipment' ) ) {
	add_filter( 'stm_is_equipment', 'stm_is_equipment' );
	function stm_is_equipment() {
		$dealer = apply_filters( 'stm_theme_demo_layout', '' );

		if ( 'equipment' === $dealer ) {
			$dealer = true;
		} else {
			$dealer = false;
		}

		return $dealer;
	}
}

add_filter(
	'motors_vl_perpay',
	function () {
		$demos = array(
			'listing',
			'listing_two',
			'listing_three',
			'listing_four',
			'listing_five',
			'listing_six',
			'listing_one_elementor',
			'listing_two_elementor',
			'listing_three_elementor',
			'listing_four_elementor',
			'listing_five_elementor',
			'stm_is_dealer_two',
			'car_dealer_two_elementor',
			'car_dealer_two',
		);

		$choosen_template = get_option( 'stm_motors_chosen_template' );
		if ( in_array( $choosen_template, $demos, true ) ) {
			return true;
		}

		return false;
	}
);

add_filter(
	'motors_vl_perpay_stock',
	function () {
		return 'stm_is_dealer_two' === get_option( 'stm_motors_chosen_template' );
	}
);

add_filter(
	'stm_disable_settings_setup',
	function() {
		$demos            = array(
			'car_rental',
			'car_rental_elementor',
			'rental_two',
		);
		$choosen_template = get_option( 'stm_motors_chosen_template' );
		if ( ! in_array( $choosen_template, $demos, true ) ) {
			return true;
		}
		return false;
	}
);

add_filter(
	'motors_vl_get_nuxy_mod',
	function ( $default, $conf_name ) {
		$demos = array(
			'car_rental',
			'car_rental_elementor',
			'rental_two',
		);

		$choosen_template = get_option( 'stm_motors_chosen_template' );

		if ( in_array( $choosen_template, $demos, true ) ) {

			$confs = array(
				'google_api_key',
				'google_pin',
				'enable_recaptcha',
				'recaptcha_public_key',
				'recaptcha_secret_key',
			);

			if ( in_array( $conf_name, $confs, true ) ) {
				return apply_filters( 'stm_me_get_nuxy_mod', $default, $conf_name );
			}
		}

		return $default;
	},
	100,
	2
);
