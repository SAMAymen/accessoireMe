<?php
$stock_number          = get_post_meta( get_the_ID(), 'stock_number', true );
$car_brochure          = get_post_meta( get_the_ID(), 'car_brochure', true );
$certified_logo_1      = get_post_meta( get_the_ID(), 'certified_logo_1', true );
$history_link_1        = get_post_meta( get_the_ID(), 'history_link', true );
$certified_logo_2      = get_post_meta( get_the_ID(), 'certified_logo_2', true );
$certified_logo_2_link = get_post_meta( get_the_ID(), 'certified_logo_2_link', true );

// Show car actions.
$show_stock                           = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_stock' );
$show_test_drive                      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' );
$show_compare                         = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' );
$show_share                           = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share' );
$show_pdf                             = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf' );
$show_certified_logo_1                = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_1' );
$show_certified_logo_2                = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_2' );
$listing_directory_enable_dealer_info = apply_filters( 'motors_vl_get_nuxy_mod', false, 'listing_directory_enable_dealer_info' );

/*If automanager, and no image in admin, set default image carfax*/

if ( stm_check_if_car_imported( get_the_ID() ) && empty( $certified_logo_1 ) && ! empty( $history_link_1 ) ) {
	$certified_logo_1 = 'automanager_default';
}

if ( function_exists( 'get_post_id_by_meta_k_v' ) ) {

	$review_id = get_post_id_by_meta_k_v( 'review_car', get_the_ID() );

	if ( ! is_null( $review_id ) ) {
		$performance = (int) get_post_meta( $review_id, 'performance', true );
		$comfort     = (int) get_post_meta( $review_id, 'comfort', true );
		$interior    = (int) get_post_meta( $review_id, 'interior', true );
		$exterior    = (int) get_post_meta( $review_id, 'exterior', true );

		$rating_sum = ( ( $performance + $comfort + $interior + $exterior ) / 4 );
	}
}
?>

<div class="single-car-actions">
	<ul class="list-unstyled clearfix">

		<?php if ( boolval( apply_filters( 'is_listing', array() ) ) || apply_filters( 'stm_is_aircrafts', false ) ) : ?>
			<?php if ( ! empty( $listing_directory_enable_dealer_info ) && ! empty( $listing_directory_enable_dealer_info ) && $listing_directory_enable_dealer_info ) : ?>
				<?php get_template_part( 'partials/user/listing-list-user', 'info' ); ?>
			<?php endif; ?>
		<?php endif; ?>

		<!--Stock num-->
		<?php if ( ! empty( $stock_number ) && ! empty( $show_stock ) && $show_stock ) : ?>
			<li>
				<div class="stock-num heading-font"><span><?php esc_html_e( 'stock', 'motors' ); ?># </span><?php echo esc_attr( $stock_number ); ?></div>
			</li>
		<?php endif; ?>

		<!--Schedule-->
		<?php if ( ! empty( $show_test_drive ) && $show_test_drive ) : ?>
			<li >
				<a href="#" class="car-action-unit stm-schedule 
				<?php
				if ( apply_filters( 'stm_is_aircrafts', false ) ) {
					echo 'heading-font';}
				?>
				" data-toggle="modal" data-target="#test-drive" onclick="stm_test_drive_car_title(<?php echo esc_js( get_the_ID() ); ?>, '<?php echo esc_js( get_the_title( get_the_ID() ) ); ?>')">
					<i class="stm-icon-steering_wheel"></i>
					<?php esc_html_e( 'Schedule Test Drive', 'motors' ); ?>
				</a>
			</li>
		<?php endif; ?>

		<!--Compare-->
		<?php if ( ! empty( $show_compare ) && $show_compare ) : ?>
			<li data-compare-id="<?php echo esc_attr( get_the_ID() ); ?>">
				<a href="#" class="car-action-unit add-to-compare stm-added 
				<?php
				if ( apply_filters( 'stm_is_aircrafts', false ) ) {
					echo 'heading-font';}
				?>
				" style="display: none;" data-id="<?php echo esc_attr( get_the_ID() ); ?>"
				data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
				data-action="remove"
				>
					<i class="stm-icon-added stm-unhover"></i>
					<span class="stm-unhover"><?php esc_html_e( 'in compare list', 'motors' ); ?></span>
					<div class="stm-show-on-hover">
						<i class="stm-icon-remove"></i>
						<?php esc_html_e( 'Remove from list', 'motors' ); ?>
					</div>
				</a>
				<a href="#" class="car-action-unit add-to-compare 
				<?php
				if ( apply_filters( 'stm_is_aircrafts', false ) ) {
					echo 'heading-font';}
				?>
				"
				data-id="<?php echo esc_attr( get_the_ID() ); ?>"
				data-action="add"
				data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
				>
					<i class="stm-icon-add"></i>
					<?php ( apply_filters( 'stm_is_aircrafts', false ) ) ? esc_html_e( 'compare', 'motors' ) : esc_html_e( 'Add to compare', 'motors' ); ?>
				</a>
			</li>
		<?php endif; ?>

		<!--PDF-->
		<?php if ( ! empty( $show_pdf ) && $show_pdf ) : ?>
			<?php if ( ! empty( $car_brochure ) ) : ?>
				<li>
					<a
						href="<?php echo esc_url( wp_get_attachment_url( $car_brochure ) ); ?>"
						class="car-action-unit stm-brochure 
						<?php
						if ( apply_filters( 'stm_is_aircrafts', false ) ) {
							echo 'heading-font';}
						?>
						"
						title="<?php esc_html_e( 'Download brochure', 'motors' ); ?>"
						download>
						<i class="stm-icon-brochure"></i>
						<?php ( apply_filters( 'stm_is_aircrafts', false ) || apply_filters( 'stm_is_listing_five', false ) ) ? esc_html_e( 'PDF brochure', 'motors' ) : esc_html_e( 'Car brochure', 'motors' ); ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endif; ?>


		<!--Share-->
		<?php if ( ! empty( $show_share ) && $show_share ) : ?>
			<li class="stm-shareble">
				<a href="#" class="car-action-unit stm-share 
				<?php
				if ( apply_filters( 'stm_is_aircrafts', false ) ) {
					echo 'heading-font';}
				?>
				">
					<i class="stm-icon-share"></i>
					<?php esc_html_e( 'Share this', 'motors' ); ?>
				</a>

				<div class="stm-a2a-popup">
					<?php echo wp_kses_post( apply_filters( 'stm_add_to_any_shortcode', get_the_ID() ) ); ?>
				</div>

			</li>
		<?php endif; ?>

		<!--Certified Logo 1-->
		<?php
		if ( ! empty( $certified_logo_1 ) && ! empty( $show_certified_logo_1 ) && $show_certified_logo_1 ) :
			if ( 'automanager_default' === $certified_logo_1 ) {
				$certified_logo_1    = array();
				$certified_logo_1[0] = get_stylesheet_directory_uri() . '/assets/images/carfax.png';
			} else {
				$certified_logo_1 = wp_get_attachment_image_src( $certified_logo_1, 'full' );
			}
			if ( ! empty( $certified_logo_1[0] ) ) {
				$certified_logo_1 = $certified_logo_1[0];

				?>

				<li class="certified-logo-1">
					<?php if ( ! empty( $history_link_1 ) ) : ?>
					<a href="<?php echo esc_url( $history_link_1 ); ?>" target="_blank">
						<?php endif; ?>
						<img src="<?php echo esc_url( $certified_logo_1 ); ?>" alt="<?php esc_attr_e( 'Logo 1', 'motors' ); ?>"/>
						<?php if ( ! empty( $history_link_1 ) ) : ?>
					</a>
				<?php endif; ?>
				</li>



			<?php } ?>
		<?php endif; ?>

		<!--Certified Logo 2-->
		<?php if ( ! empty( $certified_logo_2 ) && ! empty( $show_certified_logo_2 ) && $show_certified_logo_2 ) : ?>
			<?php
			$certified_logo_2 = wp_get_attachment_image_src( $certified_logo_2, 'full' );
			if ( ! empty( $certified_logo_2[0] ) ) {
				$certified_logo_2 = $certified_logo_2[0];
				?>


				<li class="certified-logo-2">
					<?php if ( ! empty( $certified_logo_2_link ) ) : ?>
					<a href="<?php echo esc_url( $certified_logo_2_link ); ?>" target="_blank">
						<?php endif; ?>
						<img src="<?php echo esc_url( $certified_logo_2 ); ?>"  alt="<?php esc_attr_e( 'Logo 2', 'motors' ); ?>"/>
						<?php if ( ! empty( $certified_logo_2_link ) ) : ?>
					</a>
				<?php endif; ?>
				</li>

			<?php } ?>
		<?php endif; ?>

		<?php if ( isset( $review_id ) && ! is_null( $review_id ) ) : ?>
			<li class="listing-features">
				<div class="rating">
					<div class="rating-stars">
						<i class="rating-empty"></i>
						<i class="rating-color" style="width: <?php echo esc_attr( $rating_sum ) * 20; ?>%;"></i>
					</div>
					<div class="rating-text heading-font">
						<?php
							/* translators: rating sum */
							echo sprintf( esc_html__( '%s out of 5.0', 'motors' ), esc_html( $rating_sum ) );
						?>
					</div>
				</div>
			</li>
		<?php endif; ?>

	</ul>
</div>
