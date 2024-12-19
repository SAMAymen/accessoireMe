<?php
$user_added = get_post_meta( get_the_ID(), 'stm_car_user', true );
if ( ! empty( $user_added ) ) :
	$dealer = apply_filters( 'stm_get_user_role', false, $user_added );
	$user   = get_userdata( $user_added );

	if ( ! is_wp_error( $user ) ) :
		$user_phone = get_the_author_meta( 'stm_phone', $user_added );
		$user_image = get_the_author_meta( 'stm_user_avatar', $user_added );
		$image      = '';

		if ( ! empty( $user_image ) ) {
			$image = $user_image;
		}
		if ( ! empty( $user ) ) :
			?>

			<li class="car-action-dealer-info">
				<div class="listing-archive-dealer-info clearfix">

					<?php if ( $dealer ) : ?>
						<div class="dealer-img">
							<div class="stm-dealer-image-custom-view">
								<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user->ID ) ); ?>">
									<?php $logo = get_the_author_meta( 'stm_dealer_logo', $user_added ); ?>
									<?php if ( empty( $logo ) ) : ?>
										<img class="img-responsive" src="<?php stm_get_dealer_logo_placeholder(); ?>">
									<?php else : ?>
										<img class="img-responsive" src="<?php echo esc_url( $logo ); ?>">
									<?php endif; ?>
								</a>
							</div>
						</div>
					<?php else : ?>
						<div class="dealer-image">
							<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user->ID ) ); ?>">
								<?php if ( empty( $image ) ) : ?>
									<div class="stm-user-image-empty">
										<i class="stm-service-icon-user"></i>
									</div>
								<?php else : ?>
									<img class="stm-user-image img-responsive" src="<?php echo esc_url( $image ); ?>">
								<?php endif; ?>
							</a>
						</div>
					<?php endif; ?>

					<?php
					$empty_user_info = '';
					if ( empty( $user_phone ) ) {
						$empty_user_info = 'stm_phone_disabled';
					}
					?>

					<div class="dealer-info-block <?php echo esc_attr( $empty_user_info ); ?>">
						<?php if ( $dealer ) : ?>
							<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user->ID ) ); ?>" class="title"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user->ID, '', '', '' ) ); ?></a>
						<?php else : ?>
							<div class="title"><span><?php esc_html_e( 'Personal Seller', 'motors' ); ?>: </span><a
									href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user->data->ID ) ); ?>"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user->ID, '', '', '' ) ); ?></a>
							</div>
						<?php endif; ?>
						<?php $showNumber = ( apply_filters( 'stm_is_aircrafts', false ) ) ? true : apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_number' ); ?>
							<div class="dealer-information">
								<?php if ( ! empty( $user_phone ) ) : ?>
									<?php if ( $showNumber ) : ?>
										<div class="phone"><i class="stm-service-icon-phone"></i><?php echo esc_attr( $user_phone ); ?></div>
									<?php else : ?>
										<i class="stm-service-icon-phone"></i><div class="phone"><?php echo substr_replace( $user_phone, '*******', 3, strlen( $user_phone ) );//phpcs:ignore ?></div>
										<span class="stm-show-number" data-listing-id="<?php echo get_the_ID(); ?>" data-id="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html__( 'Show number', 'motors' ); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<?php
								/*
									<a href="" class="send-message"><i class="stm-service-icon-mail"></i><span><?php esc_html_e("Message" , 'motors'); ?></span></a>
									*/
								?>
							</div>
					</div>
				</div>
			</li>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
