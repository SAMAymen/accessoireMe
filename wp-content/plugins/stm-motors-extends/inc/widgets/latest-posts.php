<?php
class STM_WP_Widget_Recent_Posts extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'stm_widget_recent_entries',
			'description' => __( 'Your site&#8217;s most recent Posts.', 'stm_motors_extends' ),
		);
		parent::__construct( 'stm-recent-posts', __( 'STM Last Post', 'stm_motors_extends' ), $widget_ops );
		$this->alt_option_name = 'stm_widget_recent_entries';

	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'stm_widget_recent_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo esc_html( $cache[ $args['widget_id'] ] );

			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'STM Recent Posts', 'stm_motors_extends' );

		$number_of_posts = ( ! empty( $instance['number_of_posts'] ) ) ? $instance['number_of_posts'] : __( '1', 'stm_motors_extends' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		if ( empty( $number_of_posts ) ) {
			$number_of_posts = 1;
		}

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page'      => $number_of_posts,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
				)
			)
		);

		if ( $r->have_posts() ) :
			?>
			<?php echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			if ( $title ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				?>
				<?php if ( apply_filters( 'stm_is_boats', false ) ) : ?>
					<a href="<?php the_permalink(); ?>" class="stm-boats-news clearfix">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="image">
								<?php the_post_thumbnail( 'thumbnail' ); ?>
							</div>
						<?php endif; ?>
						<div class="stm-post-content">
							<div class="title heading-font">
								<?php echo esc_html( wp_trim_words( get_the_title(), 7, '...' ) ); ?>
							</div>
							<div class="date">
								<i class="stm-icon-date"></i>
								<?php echo get_the_date(); ?>
							</div>
						</div>
					</a>
				<?php else : ?>
					<div class="stm-last-post-widget">
						<?php echo esc_html( wp_trim_words( get_the_excerpt(), 13, '...' ) ); ?>
						<?php $com_num = get_comments_number( get_the_id() ); ?>
						<?php if ( ! empty( $com_num ) ) { ?>
							<div class="comments-number">
								<a href="<?php the_permalink(); ?>"><i class="stm-icon-message"></i><?php echo esc_attr( $com_num ) . ' ' . esc_html__( 'Comment', 'stm_motors_extends' ); ?></a>
							</div>
						<?php } else { ?>
							<div class="comments-number">
								<a href="<?php the_permalink(); ?>"><i class="stm-icon-message"></i><?php esc_html_e( 'No comments', 'stm_motors_extends' ); ?></a>
							</div>
						<?php }; ?>
					</div>
				<?php endif; ?>
			<?php endwhile; ?>
			<?php echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			wp_reset_postdata();

		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$instance['title']           = wp_strip_all_tags( $new_instance['title'] );
		$instance['number_of_posts'] = wp_strip_all_tags( $new_instance['number_of_posts'] );

		return $instance;
	}

	public function form( $instance ) {
		$title           = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number_of_posts = isset( $instance['number_of_posts'] ) ? esc_attr( $instance['number_of_posts'] ) : '';
		$show_date       = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'stm_motors_extends' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>">
				<?php esc_html_e( 'Number of posts:', 'stm_motors_extends' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>"
				type="number" value="<?php echo esc_attr( $number_of_posts ); ?>"/>
		</p>
		<?php
	}
}

function register_stm_wp_widget_recent_posts() {
	register_widget( 'STM_WP_Widget_Recent_Posts' );
}
add_action( 'widgets_init', 'register_stm_wp_widget_recent_posts' );
