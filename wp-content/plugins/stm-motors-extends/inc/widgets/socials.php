<?php

class Stm_Socials_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'socials', // Base ID.
			__( 'Socials', 'stm_motors_extends' ), // Name.
			array( 'description' => __( 'Socials widget(customize order from theme options -> Social Media section -> Social Widget subsection )', 'stm_motors_extends' ) ) // Args.
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
		$title = ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="socials_widget_wrapper">';

		echo '<ul class="widget_socials list-unstyled clearfix">';

		foreach ( stm_me_wpcfto_kv_socials() as $val ) {
			$key = $val['key'];
			if ( ! empty( $instance[ $key ] ) ) {

				?>
				<li>
					<a href="<?php echo esc_url( $instance[ $key ] ); ?>" target="_blank">
						<i class="fab fa-<?php echo esc_attr( $key ); ?>"></i>
					</a>
				</li>
				<?php
			}
		}

		echo '</ul>';
		echo '</div>';

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = '';

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Social Network', 'stm_motors_extends' );
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		foreach ( stm_me_wpcfto_kv_socials() as $val ) :

			$soc = ( isset( $instance[ $val['key'] ] ) ) ? $instance[ $val['key'] ] : '';

			?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $val['key'] ) ); ?>"><?php echo esc_html( $val['label'] ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $val['key'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $val['key'] ) ); ?>" type="text" value="<?php echo esc_attr( $soc ); ?>">
		</p>
		<?php endforeach; ?>
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
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_attr( $new_instance['title'] ) : '';

		foreach ( stm_me_wpcfto_kv_socials() as $val ) {
			$instance[ $val['key'] ] = ( ! empty( $new_instance[ $val['key'] ] ) ) ? $new_instance[ $val['key'] ] : '';
		}

		return $instance;
	}

}

function stm_register_socials_widget() {
	register_widget( 'Stm_Socials_Widget' );
}
add_action( 'widgets_init', 'stm_register_socials_widget' );
