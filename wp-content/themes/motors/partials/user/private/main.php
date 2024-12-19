<?php
$current = apply_filters( 'stm_account_current_page', '' );

motors_include_once_scripts_styles( array( 'jquery.countdown.js', 'chart-js' ) );

if ( 'inventory' === $current || stm_is_multilisting() ) {
	motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2' ) );
}

if ( 'settings' === $current || 'become-dealer' === $current ) {
	do_action( 'stm_google_places_script', 'enqueue' );
}
?>

<div class="stm-user-private-main">

	<?php if ( 'favourite' === $current ) : ?>

		<div class="archive-listing-page">
			<?php get_template_part( 'partials/user/private/user-favourite' ); ?>
		</div>

	<?php elseif ( 'my-plans' === $current ) : ?>
		<div class="my-plans-wrapper">
			<h4 class="stm-seller-title stm-main-title"><?php echo esc_html__( 'My Plans', 'motors' ); ?></h4>
			<?php get_template_part( 'partials/user/private/user-plans' ); ?>
		</div>
	<?php elseif ( 'settings' === $current ) : ?>

		<?php get_template_part( 'partials/user/private/' . ( apply_filters( 'stm_get_user_role', false, get_current_user_id() ) ? 'dealer-settings' : 'user-settings' ) ); ?>

	<?php elseif ( 'become-dealer' === $current ) : ?>

		<?php get_template_part( 'partials/user/private/become-dealer' ); ?>

		<?php
	elseif ( 'inventory' === $current ) :

		$query       = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( get_current_user_id(), 'any' ) : null;
		$query_ppl   = ( function_exists( 'stm_user_pay_per_listings_query' ) ) ? stm_user_pay_per_listings_query( get_current_user_id(), 'any' ) : null;
		$tabs_active = ( null !== $query && $query->have_posts() && null !== $query_ppl && $query_ppl->have_posts() ) ? true : false;
		?>

		<?php get_template_part( 'partials/user/private/user', 'inventory' ); ?>

		<?php if ( null !== $query && $query->have_posts() || null !== $query_ppl && $query_ppl->have_posts() ) : ?>
		<div class="archive-listing-page">
			<?php
			if ( $tabs_active ) :
				?>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item active">
						<a href="#pp" class="nav-link active heading-font" id="pp-tab" data-toggle="tab" role="tab" aria-controls="pp" aria-selected="true">
							<?php echo esc_html__( 'Subscription Listings', 'motors' ); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#ppl" class="nav-link heading-font" id="ppl-tab" data-toggle="tab" role="tab" aria-controls="ppl" aria-selected="false">
							<?php echo esc_html__( 'Pay Per Listings', 'motors' ); ?>
						</a>
					</li>
				</ul>
				<?php
			endif;

			if ( $tabs_active ) :
				?>
			<div class="tab-content">
				<div class="tab-pane active" id="pp" role="tabpanel" aria-labelledby="pp-tab">
					<?php endif; ?>

					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<?php get_template_part( 'partials/listing-cars/listing-list-directory-edit', 'loop' ); ?>
					<?php endwhile; ?>

					<?php if ( $tabs_active ) : ?>
				</div>
				<?php endif; ?>

				<?php if ( $tabs_active ) : ?>
				<div class="tab-pane" id="ppl" role="tabpanel" aria-labelledby="ppl-tab">
					<?php endif; ?>

					<?php
					if ( null !== $query_ppl && $query_ppl->have_posts() ) :
						while ( $query_ppl->have_posts() ) :
							$query_ppl->the_post();
							?>
							<?php get_template_part( 'partials/listing-cars/listing-list-directory-edit', 'loop' ); ?>
							<?php
						endwhile;
					endif;
					?>

					<?php if ( $tabs_active ) : ?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	<?php else : ?>
		<h4><?php esc_html_e( 'No listings yet', 'motors' ); ?></h4>
	<?php endif; ?>

		<?php
	else :

		do_action( 'stm_account_custom_page', $current );

	endif;
	?>

	<!-- Show "Site is on demo mode" modal alert -->
	<?php get_template_part( 'partials/user/private/demo-alert' ); ?>

</div>
