<?php
class STM_Listing_Car_Form extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_listing_car_form',
			'description' => __( 'STM Listing Car Form', 'stm_motors_extends' ),
		);
		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct( 'stm_listing_car_form', __( 'STM Listing Car Form', 'stm_motors_extends' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		if ( ! wp_style_is( 'stm_car_listing_contact_form', 'enqueued' ) ) {
			wp_enqueue_style( 'stm_car_listing_contact_form', get_template_directory_uri() . '/assets/css/dist/stm_car_listing_contact_form.css', null, get_bloginfo( 'version' ), 'all' );
		}

		$shortcode = apply_filters( 'widget_link', empty( $instance['shortcode'] ) ? '' : $instance['shortcode'], $instance, $this->id_base ); ?>

		<div class="stm_listing_car_form">
			<div class="stm-single-car-contact">
				<?php
				echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				if ( ! empty( $title ) ) :
					?>
					<div class="title">
						<i class="fas fa-paper-plane"></i>
						<?php echo esc_html( $title ); ?>
					</div>
					<?php
				endif;

				if ( ! empty( $shortcode ) ) {
					echo do_shortcode( $shortcode );
				}

				$user_added_by = get_post_meta( get_the_ID(), 'stm_car_user', true );

				if ( ! empty( $user_added_by ) ) :
					$user_data = get_userdata( $user_added_by );
					if ( $user_data ) :
						?>
						<script type="text/javascript">
							jQuery(document).ready(function(){
								var $ = jQuery;
								var inputAuthor = '<input type="hidden" value="<?php echo intval( $user_added_by ); ?>" name="stm_changed_recepient"/>';
								$('.stm_listing_car_form form').append(inputAuthor);
							})
						</script>
						<?php
					endif;
				endif;

				echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = $new_instance['title'];
		$instance['shortcode'] = $new_instance['shortcode'];
		return $instance;
	}

	public function form( $instance ) {
		$instance  = wp_parse_args(
			(array) $instance,
			array(
				'title'     => '',
				'shortcode' => '',
			)
		);
		$title     = $instance['title'];
		$shortcode = $instance['shortcode'];
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>"><?php esc_html_e( 'CF7 shortcode:', 'stm_motors_extends' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'shortcode' ) ); ?>" type="text" value="<?php echo esc_attr( $shortcode ); ?>" /></p>
		<?php
	}
}

function register_stm_listing_car_form() {
	register_widget( 'STM_Listing_Car_Form' );
}
add_action( 'widgets_init', 'register_stm_listing_car_form' );
