<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class WMC_Widget
 */
if ( ! class_exists( 'WMC_Widget' ) ) {


	class WMC_Widget extends WP_Widget {
		protected $settings;

		function __construct() {
			$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();
			parent::__construct(
				'wmc_widget', // Base ID
				esc_html__( 'Currency Selector', 'woo-multi-currency' ), // Name
				array( 'description' => esc_html__( 'Change display currency on shop page. Widget of Multi Currency for WooCommerce by VillaTheme', 'woo-multi-currency' ), ) // Args
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
				echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( do_shortcode( apply_filters( 'wmc_shortcode', "[woo_multi_currency]", $instance ) ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo WOOMULTI_CURRENCY_F_Data::wp_kses_post( $args['after_widget'] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Fields in widget configuration
		 *
		 * @param $instance
		 */
		public function form( $instance ) {
			$setting   = WOOMULTI_CURRENCY_F_Data::get_ins();
			$title     = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$layout    = isset( $instance['layout'] ) ? $instance['layout'] : '';
			$flag_size = isset( $instance['flag_size'] ) ? $instance['flag_size'] : 0;
			$items     = $setting->get_list_shortcodes();
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'woo-multi-currency' ); ?></label>
                <input placeholder="<?php echo esc_attr__( 'Please enter your title', 'woo-multi-currency' ) ?>"
                       class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout', 'woo-multi-currency' ) ?></label>
                <br/>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
					<?php foreach ( $items as $k => $item ) { ?>
                        <option value="<?php echo esc_attr( $k ) ?>" <?php selected( $k, $layout ) ?>><?php echo esc_html( $item ) ?></option>
					<?php } ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'flag_size' ) ); ?>"><?php esc_html_e( 'Flag size', 'woo-multi-currency' ) ?></label>
                <br/>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'flag_size' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'flag_size' ) ); ?>">

                    <option value="0" <?php selected( $flag_size, 0 ) ?>><?php esc_html_e( 'Small', 'woo-multi-currency' ) ?></option>
                    <option value="1" <?php selected( $flag_size, 1 ) ?>><?php esc_html_e( 'Large', 'woo-multi-currency' ) ?></option>

                </select>
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
			$instance              = array();
			$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['layout']    = ( ! empty( $new_instance['layout'] ) ) ? $new_instance['layout'] : '';
			$instance['flag_size'] = ( ! empty( $new_instance['flag_size'] ) ) ? $new_instance['flag_size'] : 0;

			return apply_filters( 'wmc_save_widget_data', $instance, $new_instance, $old_instance );
		}
	}
}
