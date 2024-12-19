<?php
class STM_WP_Address extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_wp_address',
			'description' => __( 'STM Address widget', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_address', __( 'STM Address', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$link = apply_filters( 'widget_link', empty( $instance['link'] ) ? '' : $instance['link'], $instance, $this->id_base );

		$text = apply_filters( 'stm_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} ?>
		<div class="stm-address-widget">
			<div><?php echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php if ( ! empty( $link ) ) : ?>
				<div class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Show on map', 'stm_motors_extends' ); ?></div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['link']  = $new_instance['link'];
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) ); // wp_filter_post_kses() expects slashed.
		}
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'link'  => '',
				'text'  => '',
			)
		);
		$title    = $instance['title'];
		$link     = $instance['link'];
		$text     = esc_textarea( $instance['text'] );
		?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
			<?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>">
			<?php esc_html_e( 'Google map link:', 'stm_motors_extends' ); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">
			<?php esc_html_e( 'Content:', 'stm_motors_extends' ); ?>
		</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>">
			<?php echo esc_html( $text ); ?>
		</textarea>
	</p>

	<p>
		<input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>" type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;
		<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>">
			<?php esc_html_e( 'Automatically add paragraphs', 'stm_motors_extends' ); ?>
		</label>
	</p>
		<?php
	}
}

add_action( 'widgets_init', 'register_stm_address' );
function register_stm_address() {
	register_widget( 'STM_WP_Address' );
}
