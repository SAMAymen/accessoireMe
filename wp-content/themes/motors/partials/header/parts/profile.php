<?php
$header_profile = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );

if ( boolval( apply_filters( 'is_listing', array() ) ) && $header_profile ) :
	?>
	<div class="pull-right hdn-767">
		<div class="lOffer-account-unit">
			<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>"
				class="lOffer-account">
				<?php
				if ( is_user_logged_in() ) :
					$user_fields = apply_filters( 'stm_get_user_custom_fields', '' );
					if ( ! empty( $user_fields['image'] ) ) :
						?>
						<div class="stm-dropdown-user-small-avatar">
							<img src="<?php echo esc_url( $user_fields['image'] ); ?>"
								class="im-responsive"/>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php echo stm_me_get_wpcfto_icon( 'header_profile_icon', 'stm-service-icon-user' );//phpcs:ignore ?>
			</a>
			<?php get_template_part( 'partials/user/user', 'dropdown' ); ?>
			<?php get_template_part( 'partials/user/private/mobile/user' ); ?>
		</div>
	</div>
<?php endif; ?>
