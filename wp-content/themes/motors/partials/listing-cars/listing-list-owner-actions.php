<?php
/**
 * @var $is_single
 */

if ( ! isset( $is_single ) ) {
	$is_single = false;
}


$car_is_sold       = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$featured_listings = apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_payments_for_featured_listing' );
$featured_period   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'featured_listing_period' );
$featuredStatus    = get_post_meta( get_the_ID(), 'car_make_featured_status', true );
$special_car       = get_post_meta( get_the_ID(), 'special_car', true );
$is_disabled       = ( 'draft' === get_post_status( get_the_ID() ) );
$is_pending        = ( 'pending' === get_post_status( get_the_ID() ) );
$sell_online       = false;

$status_type    = '';
$status_content = '';

if ( $is_disabled ) {
	$status_type    = 'disabled';
	$status_content = esc_html__( 'Disabled', 'motors' );
} elseif ( $is_pending ) {
	$status_type    = 'pending';
	$status_content = esc_html__( 'Pending...', 'motors' );
} elseif ( $car_is_sold ) {
	$status_type    = 'sold';
	$status_content = esc_html__( 'Marked as sold', 'motors' );
} elseif ( $featured_listings && ! empty( $featuredStatus ) && in_array(
	$featuredStatus,
	array(
		'completed',
		'processing',
		'on-hold',
		'pending',
	),
	true
) ) {
	$status_type       = 'featured';
	$featured_date     = get_post_meta( get_the_ID(), 'pay_featured_create_date', true );
	$featured_date_end = ! empty( $featured_date ) ? strtotime( '+' . $featured_period . ' days', $featured_date ) : '';

	if ( 'completed' !== $featuredStatus ) {
		$status_content = sprintf( esc_html__( 'Featured (requested, %s)', 'motors' ), $featuredStatus );
	} elseif ( ! empty( $featured_date_end ) && $featured_date_end > time() ) {
		$status_content = sprintf( esc_html__( 'Featured item (%1$s days, ends on %2$s)', 'motors' ), $featured_period, date( get_option( 'date_format' ), $featured_date_end ) );
	}
} elseif ( 'on' === $special_car ) {
	$status_type    = 'featured';
	$status_content = esc_html__( 'Featured. Special listing', 'motors' );
}

if ( ! $is_single && ! empty( $status_content ) ) : ?>
	<div class="listing-status listing-status-<?php echo esc_attr( $status_type ); ?>">
		<?php echo esc_html( $status_content ); ?>
	</div>
<?php endif; ?>

<div class="listing-owner-actions heading-font">

	<?php if ( ! $is_pending ) : ?>

		<?php if ( ! $is_disabled ) : ?>

			<?php if ( 'on' === $car_is_sold ) : ?>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_unmark_as_sold_car' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_unmark_as_sold_car' ) ); ?>" class="action-btn as_sold">
					<i class="far fa-check-square" aria-hidden="true"></i>
					<?php esc_html_e( 'Unmark as sold', 'motors' ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_mark_as_sold_car' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_mark_as_sold_car' ) ); ?>" class="action-btn">
					<i class="far fa-check-square" aria-hidden="true"></i>
					<?php esc_html_e( 'Mark as sold', 'motors' ); ?>
				</a>
			<?php endif; ?>

			<?php
			if ( $featured_listings && 'on' !== $car_is_sold ) :

				$featuredStatus = get_post_meta( get_the_ID(), 'car_make_featured_status', true );

				if ( ! $special_car && ( empty( $featuredStatus ) || ! in_array(
					$featuredStatus,
					array(
						'completed',
						'processing',
					),
					true
				) ) ) :
					?>
					<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_make_featured' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_make_featured' ) ); ?>" class="action-btn make_featured">
						<i class="fas fa-bookmark" aria-hidden="true"></i>
						<?php esc_html_e( 'Make Featured', 'motors' ); ?>
					</a>
					<?php
				elseif ( $is_single && 'on' === $special_car ) :
					?>
					<a href="#" class="action-btn make_featured marked_featured">
						<i class="fas fa-bookmark" aria-hidden="true"></i>
						<?php esc_html_e( 'Marked Featured', 'motors' ); ?>
					</a>
					<?php
				endif;
			elseif ( $is_single && 'on' === $special_car ) :
				?>
				<a href="#" class="action-btn make_featured marked_featured">
					<i class="fas fa-bookmark" aria-hidden="true"></i>
					<?php esc_html_e( 'Marked Featured', 'motors' ); ?>
				</a>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( 'draft' === get_post_status( get_the_ID() ) ) : ?>
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_enable_user_car' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_enable_user_car' ) ); ?>" class="action-btn enable_list">
				<i class="fas fa-eye"></i>
				<?php esc_html_e( 'Enable', 'motors' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_disable_user_car' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_disable_user_car' ) ); ?>" class="action-btn disable_list" data-id="<?php esc_attr( get_the_ID() ); ?>">
				<i class="fas fa-eye-slash"></i>
				<?php esc_html_e( 'Disable', 'motors' ); ?>
			</a>
		<?php endif; ?>

	<?php endif; ?>

	<a href="<?php echo esc_url( apply_filters( 'stm_get_add_page_url', 'edit', get_the_ID() ) ); ?>" class="action-btn action-btn-light">
		<i class="fas fa-pencil-alt"></i>
		<?php esc_html_e( 'Edit', 'motors' ); ?>
	</a>

	<?php if ( 'draft' === get_post_status( get_the_ID() ) ) : ?>

		<a class="action-btn action-btn-light action-btn-danger" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'stm_move_trash_car' => get_the_ID() ), apply_filters( 'stm_get_author_link', '' ) ), 'stm_move_trash_car' ) ); ?>" data-title="<?php the_title(); ?>">
			<i class="fas fa-trash-alt"></i>
		</a>

	<?php endif; ?>

</div>

