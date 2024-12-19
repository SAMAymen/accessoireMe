<div class="profile-wrap">
	<div class="lOffer-account-unit">
		<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>" class="lOffer-account">
			<?php
			if ( is_user_logged_in() ) {
				$user_fields = apply_filters( 'stm_get_user_custom_fields', get_current_user_id() );

				if ( current_user_can( 'stm_dealer' ) ) {
					$ava = $user_fields['dealer_image'];
				} else {
					$ava = $user_fields['image'];
				}

				if ( ! empty( $ava ) ) :
					?>
					<div class="stm-dropdown-user-small-avatar">
						<img src="<?php echo esc_url( $ava ); ?>" class="img-responsive"/>
					</div>
				<?php else : ?>
					<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_profile_icon', 'fas fa-user' ) ); ?>
				<?php endif; ?>
			<?php } else { ?>
				<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_profile_icon', 'fas fa-user' ) ); ?>
			<?php } ?>
		</a>
		<?php get_template_part( 'partials/header/header-classified-five/parts/account-dropdown' ); ?>
	</div>
</div>

