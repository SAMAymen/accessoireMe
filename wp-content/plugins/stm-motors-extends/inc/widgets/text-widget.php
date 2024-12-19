<?php

class STM_WP_Widget_Text extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_wp_widget_text',
			'description' => __( 'STM Arbitrary text or HTML.', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_text', __( 'STM Text', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$text = apply_filters( 'stm_wp_widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} ?>
		<div class="textwidget"><?php echo ! empty( $instance['filter'] ) ? wp_kses_post( wpautop( $text ) ) : wp_kses_post( $text ); ?></div>

		<?php
		if ( ! empty( $instance['socials'] ) && $instance['socials'] ) :
			$socials = stm_get_header_socials( 'socials_widget_enable' );
			if ( ! empty( $socials ) ) {
				echo '<ul class="widget_socials_text list-unstyled clearfix">';
				foreach ( $socials as $key => $val ) :
					?>
					<li>
						<a href="<?php echo esc_url( $val ); ?>" target="_blank">
							<i class="fab fa-<?php echo esc_attr( $key ); ?>"></i>
						</a>
					</li>
					<?php
				endforeach;
				echo '</ul>';
			}
		endif;
		?>

		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		} // wp_filter_post_kses() expects slashed
		$instance['filter']  = ! empty( $new_instance['filter'] );
		$instance['socials'] = ! empty( $new_instance['socials'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text'  => '',
			)
		);
		$title    = $instance['title'];
		$text     = esc_textarea( $instance['text'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">
				<?php esc_html_e( 'Content:', 'stm_motors_extends' ); ?>
			</label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>">
				<?php echo wp_kses_post( apply_filters( 'the_content', $text ) ); ?>
			</textarea>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>"
				type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;
				<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>">
					<?php esc_html_e( 'Automatically add paragraphs', 'stm_motors_extends' ); ?>
				</label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'socials' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'socials' ) ); ?>"
				type="checkbox" <?php checked( isset( $instance['socials'] ) ? $instance['socials'] : 0 ); ?> />&nbsp;
				<label for="<?php echo esc_attr( $this->get_field_id( 'socials' ) ); ?>">
					<?php esc_html_e( 'Add Socials Widget', 'stm_motors_extends' ); ?>
				</label>
		</p>
		<?php
	}
}

add_action( 'widgets_init', 'register_stm_text_widget' );
function register_stm_text_widget() {
	register_widget( 'STM_WP_Widget_Text' );
}
