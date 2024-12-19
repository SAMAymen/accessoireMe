<?php
	$_current_user = wp_get_current_user();
?>

<div class="stm-single-listing__actions">
	<div class="container">
		<div class="stm-single-listing__actions--inner">
			<div class="stm-single-listing__actions--left">
				<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $_current_user->ID ) ); ?>">
					<?php esc_html_e( 'Hello, ', 'motors' ); ?>
					<span><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $_current_user->ID, '', '', '' ) ); ?></span>
				</a>
			</div>
			<div class="stm-single-listing__actions--center">
				<?php get_template_part( 'partials/listing-cars/listing-list-owner', 'actions' ); ?>
			</div>
			<div class="stm-single-listing__actions--right">
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
					<span>
						<?php esc_html_e( 'Logout', 'motors' ); ?>
					</span>
					<i class="fa-solid fa-arrow-right-from-bracket"></i>
				</a>
			</div>
		</div>
	</div>
</div>
