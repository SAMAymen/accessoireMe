<?php
/**
 * Main class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

defined( 'YITH_WOOCOMPARE' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_Woocompare' ) ) {
	/**
	 * YITH Woocommerce Compare
	 *
	 * @since 1.0.0
	 */
	class YITH_Woocompare {

		/**
		 * Plugin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $obj = null;

		/**
		 * AJAX Helper
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $ajax = null;

		/**
		 * Constructor
		 *
		 * @return YITH_Woocompare_Admin | YITH_Woocompare_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

            add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

			if ( $this->is_frontend() ) {
				// Require frontend class.
				require_once 'class.yith-woocompare-frontend.php';

				$this->obj = new YITH_Woocompare_Frontend();
			} elseif ( $this->is_admin() ) {
				// Requires admin classes.
				require_once 'class.yith-woocompare-admin.php';

				$this->obj = new YITH_Woocompare_Admin();
			}

			// Add image size.
			YITH_Woocompare_Helper::set_image_size();

			// Let's filter the woocommerce image size.
			add_filter( 'woocommerce_get_image_size_yith-woocompare-image', array( $this, 'filter_wc_image_size' ), 10, 1 );

			return $this->obj;
		}

		/**
		 * Detect if is frontend
		 *
		 * @return bool
		 */
		public function is_frontend() {
			$is_ajax          = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$context_check    = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$actions_to_check = apply_filters( 'yith_woocompare_actions_to_check_frontend', array( 'woof_draw_products', 'prdctfltr_respond_550', 'wbmz_get_products', 'jet_smart_filters', 'productfilter' ) );
			$action_check     = isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ), $actions_to_check, true ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return (bool) YITH_Woocompare_Helper::is_elementor_editor() || ( ! is_admin() || ( $is_ajax && ( $context_check || $action_check ) ) );
		}

		/**
		 * Detect if is admin
		 *
		 * @return bool
		 */
		public function is_admin() {
			$is_ajax  = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$is_admin = ( is_admin() || $is_ajax && isset( $_REQUEST['context'] ) && sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ) === 'admin' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return apply_filters( 'yith_woocompare_check_is_admin', (bool) $is_admin );
		}

        /**
         * Declare support for WooCommerce features.
         *
         * @since 2.26.0
         */
        public function declare_wc_features_support() {
            if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
                $init = defined( 'YITH_WOOCOMPARE_FREE_INIT' ) ? YITH_WOOCOMPARE_FREE_INIT : false;
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $init, true );
            }
        }

		/**
		 * Load and register widgets
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function register_widgets() {
			register_widget( 'YITH_Woocompare_Widget' );
		}

		/**
		 * Filter WooCommerce image size attr
		 *
		 * @since 2.3.5
		 * @param array $size The default image size.
		 * @return array
		 */
		public function filter_wc_image_size( $size ) {

			$size_opt = get_option( 'yith_woocompare_image_size', array() );

			return array(
				'width'  => isset( $size_opt['width'] ) ? absint( $size_opt['width'] ) : 600,
				'height' => isset( $size_opt['height'] ) ? absint( $size_opt['height'] ) : 600,
				'crop'   => isset( $size_opt['crop'] ) ? 1 : 0,
			);
		}

	}
}
