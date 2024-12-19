<?php
$user_page      = get_queried_object();
$user_id        = $user_page->data->ID;
$user_image     = get_the_author_meta( 'stm_user_avatar', $user_id );
$image          = '';
$user_show_mail = '';
$user_show_mail = get_the_author_meta( 'stm_show_email', $user_id );
$user_phone     = get_the_author_meta( 'stm_phone', $user_id );

if ( ! empty( $user_image ) ) {
	$image = $user_image;
}

$query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, 'publish' ) : null;

$sidebar          = apply_filters( 'motors_vl_get_nuxy_mod', '1725', 'user_sidebar' );
$sidebar_position = apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'user_sidebar_position' );

$layout = stm_sidebar_layout_mode( $sidebar_position, $sidebar );
?>
<div class="container stm-user-public-profile">
	<input type="hidden" id="stm_dealer_view_type" value="list">
	<div class="row">
		<?php echo wp_kses_post( $layout['content_before'] ); ?>
		<div class="clearfix stm-user-public-profile-top">
			<div class="stm-user-name">
				<div class="image">
					<?php if ( ! empty( $image ) ) : ?>
						<img src="<?php echo esc_url( $image ); ?>"/>
					<?php else : ?>
						<i class="stm-service-icon-user"></i>
					<?php endif; ?>
				</div>
				<div class="title">
					<h4><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user_page->ID, '', '', '' ) ); ?></h4>
					<div class="stm-title-desc"><?php esc_html_e( 'Private Seller', 'motors' ); ?></div>
				</div>
			</div>
			<div class="stm-user-data-right">
				<?php if ( ! empty( $user_page->data->user_email ) && ! empty( $user_show_mail ) ) : ?>
					<div class="stm-user-email">
						<i class="fas fa-envelope-open"></i>
						<div class="mail-label"><?php esc_html_e( 'Seller email', 'motors' ); ?></div>
						<a href="mailto:<?php echo esc_attr( $user_page->data->user_email ); ?>" class="mail h4"><?php echo esc_attr( $user_page->data->user_email ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $user_phone ) ) : ?>
					<div class="stm-user-phone">
						<i class="stm-service-icon-phone_2"></i>
						<div class="phone h3"><?php echo esc_attr( $user_phone ); ?></div>
						<div class="phone-label"><?php esc_html_e( 'Seller phone', 'motors' ); ?></div>
					</div>
				<?php endif; ?>

			</div>
		</div> <!-- top profile -->

		<div class="stm-user-public-listing">
			<?php if ( ! is_null( $query ) && $query->have_posts() ) : ?>
				<h4 class="stm-seller-title"><?php esc_html_e( 'Seller Inventory', 'motors' ); ?></h4>
				<?php if ( stm_is_multilisting() ) : ?>
					<div class="multilisting-select">
						<?php
						$listings = stm_listings_multi_type_labeled( true );
						if ( ! empty( $listings ) ) :
							?>
							<div class="select-type select-listing-type" style="margin-right: 15px;">
								<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'motors' ); ?></div>
								<select data-user="<?php echo esc_attr( $user_id ); ?>" data-user-public="1">
									<option value="all"
											selected><?php esc_html_e( 'All listing types', 'motors' ); ?></option>
									<?php foreach ( $listings as $slug => $label ) : ?>
										<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; ?>><?php echo esc_html( $label );//phpcs:ignore WordPress.Security.NonceVerification.Recommended ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="archive-listing-page">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<?php get_template_part( 'partials/listing-cars/listing-list-directory', 'loop' ); ?>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<h4 class="stm-seller-title"
					style="color:#aaa;"><?php esc_html_e( 'No Inventory added yet.', 'motors' ); ?></h4>
			<?php endif; ?>
		</div>
		<?php echo wp_kses_post( $layout['content_after'] ); ?>
		<?php
		echo wp_kses_post( $layout['sidebar_before'] );
		if ( ! empty( $sidebar ) ) :
			$user_sidebar = get_post( $sidebar );
			if ( ! empty( $user_sidebar ) && ! is_wp_error( $user_sidebar ) ) :
				?>
				<div class="stm-user-sidebar">
					<?php
					if ( class_exists( \Elementor\Plugin::class ) && is_numeric( $sidebar ) ) :
						apply_filters( 'motors_render_elementor_content', $sidebar );
					else :
						?>
						<?php echo do_shortcode( $user_sidebar->post_content ); ?>
						<style type="text/css">
							<?php echo get_post_meta( $user_sidebar, '_wpb_shortcodes_custom_css', true ); //phpcs:ignore ?>
						</style>
					<?php endif; ?>
					<?php // phpcs:disable
					?>
					<script type="text/javascript">
						jQuery(window).on('load', function () {
							var $ = jQuery;
							var inputAuthor = '<input type="hidden" value="<?php echo esc_attr( $user_page->ID ); ?>" name="stm_changed_recepient"/>';
							$('.stm_listing_car_form form').append(inputAuthor);
						})
					</script>
					<?php // phpcs:ignore
					?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php echo wp_kses_post( $layout['sidebar_after'] ); ?>
	</div>
</div>

<?php global $wp; ?>
