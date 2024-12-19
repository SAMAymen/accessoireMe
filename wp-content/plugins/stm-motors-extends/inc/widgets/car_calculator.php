<?php
class Stm_Calculator extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_calculator',
			'description' => __( 'STM Auto loan Calculator', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_calculator', __( 'STM Auto loan Calculator', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		get_template_part( 'partials/single-car-boats/boats-calculator' );
		?>
		<style type="text/css">
			.stm_auto_loan_calculator:after {
				content: '';
				display: block;
				position: absolute;
				top: 0;
				left: 0;
				background-image: url('<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/tmp/calc.jpg' ); ?>');
			}
		</style>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = $instance['title'];
		?>
	<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<?php
	}
}

function register_stm_calculator() {
	register_widget( 'Stm_Calculator' );
}
add_action( 'widgets_init', 'register_stm_calculator' );
