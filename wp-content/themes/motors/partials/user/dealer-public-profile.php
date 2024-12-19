<?php
motors_include_once_scripts_styles( array( 'stmselect2', 'app-select2', 'addtoany-core' ) );

$user_page = get_queried_object();
$user_id   = $user_page->data->ID;
$user      = apply_filters( 'stm_get_user_custom_fields', $user_id );

$ratings        = stm_get_dealer_marks( $user_id );
$user_show_mail = get_the_author_meta( 'stm_show_email', $user_id );

$sidebar          = apply_filters( 'motors_vl_get_nuxy_mod', '1864', 'dealer_sidebar' );
$sidebar_position = apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'dealer_sidebar_position' );

$layout = stm_sidebar_layout_mode( $sidebar_position, $sidebar );

$inline_list = 'stm-inline-icons';
?>

	<div class="container stm-user-public-profile">
		<div class="row">
			<?php echo wp_kses_post( $layout['content_before'] ); ?>

			<div class="stm-dealer-public-profile">
				<div class="clearfix">
					<div class="stm-dealer-top-left">
						<h1 class="h3"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user_id, '', '', '' ) ); ?></h1>
						<?php if ( ! empty( $ratings['average'] ) ) : ?>
							<div class="stm-star-rating">
								<div class="inner">
									<div class="stm-star-rating-upper"
										style="width:<?php echo esc_attr( $ratings['average_width'] ); ?>"></div>
									<div class="stm-star-rating-lower"></div>
								</div>
								<div class="heading-font"><?php echo esc_attr( $ratings['average'] ); ?></div>
							</div>
						<?php endif; ?>
					</div>
					<div class="stm-dealer-top-right">
						<?php if ( ! empty( $user['logo'] ) ) : ?>
							<img src="<?php echo esc_url( $user['logo'] ); ?>" class="img-responsive"/>
						<?php else : ?>
							<img src="<?php stm_get_dealer_logo_placeholder(); ?>" class="img-responsive"/>
						<?php endif; ?>
					</div>
				</div>

				<div class="stm-dealer-main-info">
					<div class="clearfix">
						<?php
						if ( ! empty( $user['dealer_image'] ) ) :
							$inline_list = '';
							?>
							<div class="stm-dealer-image">
								<img src="<?php echo esc_url( $user['dealer_image'] ); ?>"/>
							</div>
						<?php endif; ?>
						<div class="stm-dealer-info <?php echo esc_attr( $inline_list ); ?>">
							<?php if ( ! empty( $user['location'] ) ) : ?>
								<div class="stm-dealer-info-unit location">
									<i class="stm-icon-pin"></i>
									<div class="inner">
										<h5><?php esc_html_e( 'Location', 'motors' ); ?></h5>
										<span><?php echo esc_html( $user['location'] ); ?></span>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $user['phone'] ) ) : ?>
								<?php $show_number = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_number' ); ?>
								<div class="stm-dealer-info-unit phone">
									<i class="stm-service-icon-sales_phone"></i>
									<div class="inner">
										<h5><?php esc_html_e( 'Sales Phone', 'motors' ); ?></h5>
										<?php if ( $show_number ) : ?>
											<?php if ( ! empty( $user['phone'] ) ) : ?>
												<span class="phone"><?php echo esc_attr( $user['phone'] ); ?></span>
											<?php endif; ?>
										<?php else : ?>
											<span class="phone"><?php echo wp_kses_post( substr_replace( $user['phone'], '*******', 3, strlen( $user['phone'] ) ) ); ?></span>
											<span class="stm-show-number" data-id="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html__( 'Show number', 'motors' ); ?></span>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $user['stm_sales_hours'] ) ) : ?>
								<div class="stm-dealer-info-unit sales_hours">
									<i class="stm-service-icon-sales_hours"></i>
									<div class="inner">
										<h5><?php esc_html_e( 'Sales Hours', 'motors' ); ?></h5>
										<span><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $user['stm_sales_hours'], 'Sales Hours' ) ); ?></span>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $user['email'] ) && ! empty( $user_show_mail ) ) : ?>
								<div class="stm-dealer-info-unit stm-user-email">
									<i class="stm-icon-mail"></i>
									<div class="inner">
										<h5><?php esc_html_e( 'Seller email', 'motors' ); ?></h5>
										<a href="mailto:<?php echo esc_attr( $user['email'] ); ?>" class="mail"><?php echo esc_attr( $user['email'] ); ?></a>
									</div>
								</div>
							<?php endif; ?>
							<div class="clearfix"></div>
							<div class="stm-dealer-bot-info">
								<?php if ( ! empty( $user['website'] ) ) : ?>
									<div class="stm_website_url">
										<a href="<?php echo esc_url( $user['website'] ); ?>" target="_blank">
											<i class="fas fa-external-link-alt"></i><?php esc_html_e( 'View Website', 'motors' ); ?>
										</a>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $user['socials'] ) ) : ?>
									<div class="socials clearfix">
										<?php foreach ( $user['socials'] as $social_key => $social ) : ?>
											<a href="<?php echo esc_url( $social ); ?>">
												<?php
												if ( 'facebook' === $social_key ) {
													$social_key = 'facebook-f';
												}
												?>
												<i class="fab fa-<?php echo esc_attr( $social_key ); ?>"></i>
											</a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if ( ! empty( $user['stm_seller_notes'] ) ) : ?>
						<div class="stm-seller-notes">
							<div class="heading-font"><?php esc_html_e( 'Seller\'s Notes', 'motors' ); ?></div>
							<?php echo esc_attr( stripslashes( $user['stm_seller_notes'] ) ); ?>
						</div>
					<?php endif; ?>

					<div class="stm-dealer-tabs">
						<!-- Nav tabs -->
						<ul role="tablist" class="stm-dealer-tabs-list heading-font clearfix">
							<li role="presentation" class="active">
								<a href="#stm_d_inv" aria-controls="stm_d_inv" role="tab" data-toggle="tab">
									<i class="fas fa-car"></i>
									<?php esc_html_e( 'Dealer\'s Inventory', 'motors' ); ?>
								</a>
							</li>
							<li role="presentation">
								<a href="#stm_d_rev" aria-controls="stm_d_rev" role="tab" data-toggle="tab">
									<i class="fas fa-star"></i>
									<?php esc_html_e( 'Dealer Reviews', 'motors' ); ?>
								</a>
							</li>
							<li role="presentation">
								<a href="#stm_w_rev" aria-controls="stm_w_rev" role="tab" data-toggle="tab">
									<i class="fas fa-edit"></i>
									<?php esc_html_e( 'Write a review', 'motors' ); ?>
								</a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="stm_d_inv">
								<?php get_template_part( 'partials/user/dealer', 'inventory' ); ?>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="stm_d_rev">
								<?php get_template_part( 'partials/user/dealer', 'reviews' ); ?>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="stm_w_rev">
								<?php get_template_part( 'partials/user/dealer-write', 'review' ); ?>
							</div>
						</div>
					</div>

				</div>

			</div>

			<?php echo wp_kses_post( $layout['content_after'] ); ?>

			<?php
			echo wp_kses_post( $layout['sidebar_before'] );

			if ( ! empty( $user['location_lat'] ) && ! empty( $user['location_lng'] ) && ! empty( $user['location'] ) ) {
				stm_dealer_gmap( $user['location_lat'], $user['location_lng'] );
			}
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
						<?php // phpcs:enable
						?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php echo wp_kses_post( $layout['sidebar_after'] ); ?>
		</div>
	</div>
<?php // phpcs:disable ?>
	<script type="text/javascript">
        jQuery(document).ready(function () {
            var $ = jQuery;
            if (location.hash !== '') {
                $('a[href="' + location.hash + '"]').tab('show');
            }
        })
	</script>
<?php // phpcs:enable ?>