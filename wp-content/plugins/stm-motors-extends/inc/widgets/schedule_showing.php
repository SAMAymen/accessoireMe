<?php

class Stm_Schedule_Showing extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'stm_schedule_showing', // Base ID.
			esc_html__( 'Schedule Showing', 'stm_motors_extends' ), // Name.
			array( 'description' => esc_html__( 'Schedule showing', 'stm_motors_extends' ) ) // Args.
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		}

		if ( ! empty( $instance['text'] ) ) {
			echo '<div class="content">';
				echo wp_kses_post( html_entity_decode( $instance['text'] ) );
			echo '</div>';
		}

		echo '<ul class="stm-list-duty">';
		if ( ! empty( $instance['phone'] ) ) {
			echo '<li class="widget_contacts_phone"><div class="icon"><i class="stm-service-icon-sales_phone"></i></div><div class="text">';
			if ( ! empty( $instance['phone_label'] ) ) {
				echo '<span>';
				echo wp_kses_post( html_entity_decode( $instance['phone_label'] ) );
				echo '</span>';
			}
			echo "<div class='heading-font'>";
			echo wp_kses_post( html_entity_decode( $instance['phone'] ) ) . '</div></div></li>';
		}
		echo '</ul>';

		?>
		<a href="#" class="button" data-toggle="modal" data-target="#test-drive">
			<i class="far fa-clock"></i>
			<?php esc_html_e( 'Schedule a Showing', 'stm_motors_extends' ); ?>
		</a>
		<?php

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title       = '';
		$text        = '';
		$phone       = '';
		$phone_label = '';

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = esc_html__( 'Contact', 'stm_motors_extends' );
		}

		if ( isset( $instance['text'] ) ) {
			$text = $instance['text'];
		}

		if ( isset( $instance['phone'] ) ) {
			$phone = $instance['phone'];
		}

		if ( isset( $instance['phone_label'] ) ) {
			$phone_label = $instance['phone_label'];
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'stm_motors_extends' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_attr( $text ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stm_motors_extends' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone_label' ) ); ?>"><?php esc_html_e( 'Phone Label:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone_label' ) ); ?>" type="text" value="<?php echo esc_attr( $phone_label ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = array();
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? esc_attr( $new_instance['title'] ) : '';
		$instance['text']        = ( ! empty( $new_instance['text'] ) ) ? esc_attr( $new_instance['text'] ) : '';
		$instance['phone']       = ( ! empty( $new_instance['phone'] ) ) ? esc_attr( $new_instance['phone'] ) : '';
		$instance['phone_label'] = ( ! empty( $new_instance['phone_label'] ) ) ? esc_attr( $new_instance['phone_label'] ) : '';

		return $instance;
	}

}

function register_schedule_showing() {
	register_widget( 'Stm_Schedule_Showing' );
}
add_action( 'widgets_init', 'register_schedule_showing' );
