<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class WMC_Widget
 */
if ( ! class_exists( 'WMC_Widget_Rates' ) ) {


	class WMC_Widget_Rates extends WP_Widget {
		protected $settings;

		function __construct() {
			$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
			parent::__construct(
				'wmc_widget_rates', // Base ID
				esc_attr__( 'List Currency Rates', 'woo-multi-currency' ), // Name
				array( 'description' => esc_attr__( 'Show list currency exchange rates of Multi Currency for WooCommerce by VillaTheme', 'woo-multi-currency' ), ) // Args
			);
		}

		/**
		 * Show front end
		 *
		 * @param $args
		 * @param $instance
		 */
		public function widget( $args, $instance ) {
			if ( $this->settings->get_enable() ) {
				echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( $args['before_widget'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				if ( ! empty( $instance['title'] ) ) {
					echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

				echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( do_shortcode( apply_filters( 'wmc_shortcode', "[woo_multi_currency_rates]", $instance ) ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( $args['after_widget'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Fields in widget configuration
		 *
		 * @param $instance
		 */
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'woo-multi-currency' ); ?></label>
                <input placeholder="<?php echo esc_attr__( 'Please enter your title', 'woo-multi-currency' ) ?>"
                       class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>">
            </p>
			<?php do_action( 'wmc_after_widget_form', $instance, $this ) ?>
			<?php
		}

		/**
		 * Save widget configuration
		 *
		 * @param $new_instance
		 * @param $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance          = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';

			return apply_filters( 'wmc_save_widget_data', $instance, $new_instance, $old_instance );
		}


	}
}
?>