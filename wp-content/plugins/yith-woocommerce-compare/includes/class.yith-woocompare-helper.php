<?php
/**
 * Helper class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Compare
 * @version 1.1.4
 */

defined( 'YITH_WOOCOMPARE' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_Woocompare_Helper' ) ) {
	/**
	 * YITH Woocommerce Compare helper
	 *
	 * @since 1.0.0
	 */
	class YITH_Woocompare_Helper {

		/**
		 * Set the image size used in the comparison table
		 *
		 * @since 1.0.0
		 */
		public static function set_image_size() {
			$size = get_option( 'yith_woocompare_image_size' );
			if ( ! $size ) {
				return;
			}

			add_image_size( 'yith-woocompare-image', $size['width'], $size['height'], isset( $size['crop'] ) );
		}

		/**
		 * The list of standard fields
		 *
		 * @since 1.0.0
		 * @access public
		 * @param boolean $with_attr If merge attributes taxonomies to fields.
		 * @return array
		 */
		public static function standard_fields( $with_attr = true ) {

			$fields = array(
				'image'       => __( 'Image', 'yith-woocommerce-compare' ),
				'title'       => __( 'Title', 'yith-woocommerce-compare' ),
				'price'       => __( 'Price', 'yith-woocommerce-compare' ),
				'add-to-cart' => __( 'Add to cart', 'yith-woocommerce-compare' ),
				'description' => __( 'Description', 'yith-woocommerce-compare' ),
				'sku'         => __( 'Sku', 'yith-woocommerce-compare' ),
				'stock'       => __( 'Availability', 'yith-woocommerce-compare' ),
				'weight'      => __( 'Weight', 'yith-woocommerce-compare' ),
				'dimensions'  => __( 'Dimensions', 'yith-woocommerce-compare' ),
			);

			if ( $with_attr ) {
				$fields = array_merge( $fields, self::attribute_taxonomies() );
			}

			return apply_filters( 'yith_woocompare_standard_fields_array', $fields );
		}

		/**
		 * Get Woocommerce Attribute Taxonomies
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public static function attribute_taxonomies() {

			$attributes = array();

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( empty( $attribute_taxonomies ) ) {
				return array();
			}
			foreach ( $attribute_taxonomies as $attribute ) {
				$tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
				if ( taxonomy_exists( $tax ) ) {
					$attributes[ $tax ] = ucfirst( $attribute->attribute_name );
				}
			}

			return $attributes;
		}

		/**
		 * Check if current screen is elementor editor
		 *
		 * @since 2.6.0
		 * @return boolean
		 */
		public static function is_elementor_editor() {

			if ( did_action( 'admin_action_elementor' ) ) {
				return \Elementor\Plugin::$instance->editor->is_edit_mode();
			}

			return is_admin() && isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ), array( 'elementor', 'elementor_ajax' ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}
}
