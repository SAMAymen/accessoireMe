<?php
class STM_Dealer_Car_Info extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_dealer_car_info',
			'description' => __( 'STM Dealer Info', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_dealer_car_info', __( 'STM Dealer Info', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// widget_yes + setting_no = no
		// widget_no + setting_yes = no
		// widget_yes + setting_yes = yes
		// by default widget = yes.

		$whatsapp_visibility = 'yes';

		$show_whatsapp_option = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_whatsapp' );

		if ( false === $show_whatsapp_option || ( empty( $instance['show_whatsapp'] ) && true === $show_whatsapp_option ) ) {
			$whatsapp_visibility = false;
		}

		$email_visibility = 'yes';

		$show_email_option = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_email' );

		if ( false === $show_email_option || ( empty( $instance['show_email'] ) && true === $show_email_option ) ) {
			$email_visibility = false;
		}

		get_template_part(
			'partials/single-car-listing/car',
			'dealer',
			array(
				'show_whatsapp' => $whatsapp_visibility,
				'show_email'    => $email_visibility,
			)
		);

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function update( $new_instance, $old_instance ) {
		$instance                  = $old_instance;
		$instance['title']         = $new_instance['title'];
		$instance['show_whatsapp'] = $new_instance['show_whatsapp'];
		$instance['show_email']    = $new_instance['show_email'];
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = $instance['title'];
		?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>

	<p>
		<input
			id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>"
			type="checkbox"
			<?php echo ( ! empty( $instance['filter'] ) ) ? 'checked="checked"' : ''; ?>
		/>&nbsp;
		<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>">
			<?php esc_html_e( 'Automatically add paragraphs', 'stm_motors_extends' ); ?>
		</label>
	</p>

	<p>
		<input
			id="<?php echo esc_attr( $this->get_field_id( 'show_whatsapp' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'show_whatsapp' ) ); ?>"
			type="checkbox"
			<?php echo ( ! empty( $instance['show_whatsapp'] ) ) ? 'checked="checked"' : ''; ?>
		/>&nbsp;
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_whatsapp' ) ); ?>">
			<?php esc_html_e( 'Show WhatsApp button', 'stm_motors_extends' ); ?>
		</label>
	</p>

	<p>
		<input
			id="<?php echo esc_attr( $this->get_field_id( 'show_email' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'show_email' ) ); ?>"
			type="checkbox"
			<?php echo ( ! empty( $instance['show_email'] ) ) ? 'checked="checked"' : ''; ?>
		/>&nbsp;
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_email' ) ); ?>">
			<?php esc_html_e( 'Show Email button', 'stm_motors_extends' ); ?>
		</label>
	</p>
		<?php
	}
}

add_action( 'widgets_init', 'register_stm_dealer_car_info' );
function register_stm_dealer_car_info() {
	register_widget( 'STM_Dealer_Car_Info' );
}
