<?php
$trade_in_nonce = wp_create_nonce( 'stm_trade_in_nonce' );
$action_hash    = ( isset( $args['is_modal'] ) && $args['is_modal'] ) ? '#error-fields' : '';
$uniqid         = uniqid();
$listing_title  = apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_queried_object_id() ), get_queried_object_id() );

if ( apply_filters( 'stm_is_boats', false ) ) {
	$vehicle_type_title = 'Boat';
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$vehicle_type_title = 'Motorcycle';
} else {
	$vehicle_type_title = 'Car';
}

?>
	<div class="stm-sell-a-car-form stm-sell-a-car-form-<?php echo esc_attr( $uniqid ); ?>" data-form-id="<?php echo esc_attr( $uniqid ); ?>">
		<div class="form-navigation">
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<a href="#step-one" class="form-navigation-unit active" data-tab="step-one">
						<div class="number heading-font">1.</div>
						<div class="title heading-font">
							<?php
								$formatted_text = sprintf( __( '%s Information', 'motors' ), $vehicle_type_title );
								echo esc_html( $formatted_text );
							?>
						</div>
						<div class="sub-title">
							<?php
								$formatted_text = sprintf( __( 'Add your %s details', 'motors' ), strtolower( $vehicle_type_title ) );
								echo esc_html( $formatted_text );
							?>
						</div>
					</a>
				</div>
				<div class="col-md-4 col-sm-4">
					<a href="#step-two" class="form-navigation-unit" data-tab="step-two">
						<div class="number heading-font">2.</div>
						<div class="title heading-font">
							<?php
								$condition_text = sprintf( __( '%s Condition', 'motors' ), $vehicle_type_title );
								echo esc_html( $condition_text );
							?>
						</div>
						<div class="sub-title">
							<?php
								$details_text = sprintf( __( 'Add your %s details', 'motors' ), strtolower( $vehicle_type_title ) );
								echo esc_html( $details_text );
							?>
						</div>
					</a>
				</div>
				<div class="col-md-4 col-sm-4">
					<a href="#step-three" class="form-navigation-unit" data-tab="step-three">
						<div class="number heading-font">3.</div>
						<div class="title heading-font"><?php esc_html_e( 'Contact details', 'motors' ); ?></div>
						<div class="sub-title"><?php esc_html_e( 'Your contact details', 'motors' ); ?></div>
					</a>
				</div>
			</div>
		</div>
		<div class="form-content">
			<form method="POST" action="<?php echo esc_attr( $action_hash ); ?>" id="trade-in-default" enctype="multipart/form-data">
				<!-- STEP ONE -->
				<div class="form-content-unit active" id="step-one">
					<input type="hidden" name="trade_in_wpnonce" value="<?php echo esc_attr( $trade_in_nonce ); ?>"/>
					<input type="hidden" name="trade_in_submitted" value="false"/>
					<input type="hidden" name="sell_a_car" value="filled"/>
					<input type="hidden" name="car" value="<?php echo esc_attr( $listing_title ); ?>"/>
					<?php
					$post_make_value = '';
					if ( ! empty( $_POST['make'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_make_value = sanitize_text_field( wp_unslash( $_POST['make'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_model_value = '';
					if ( ! empty( $_POST['model'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_model_value = sanitize_text_field( wp_unslash( $_POST['model'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_stm_year_value = '';
					if ( ! empty( $_POST['stm_year'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_stm_year_value = sanitize_text_field( wp_unslash( $_POST['stm_year'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_transmission_value = '';
					if ( ! empty( $_POST['transmission'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_transmission_value = sanitize_text_field( wp_unslash( $_POST['transmission'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_mileage_value = '';
					if ( ! empty( $_POST['mileage'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_mileage_value = sanitize_text_field( wp_unslash( $_POST['mileage'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_vin_value = '';
					if ( ! empty( $_POST['vin'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_vin_value = sanitize_text_field( wp_unslash( $_POST['vin'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}
					?>
					<div class="row">
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Make', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_make_value ); ?>" name="make"
									data-need="true" required/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Model', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_model_value ); ?>" name="model"
									data-need="true" required/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Year', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_stm_year_value ); ?>"
									name="stm_year"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Transmission', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_transmission_value ); ?>"
									name="transmission"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label">
									<?php esc_html_e( 'Mileage', 'motors' ); ?>
								</div>
								<input type="text" value="<?php echo esc_attr( $post_mileage_value ); ?>"
									name="mileage"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'VIN', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_vin_value ); ?>" name="vin"/>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 col-sm-12">
							<?php
							$post_video_url_value = '';
							if ( ! empty( $_POST['video_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$post_video_url_value = sanitize_text_field( $_POST['video_url'] ); // phpcs:ignore WordPress.Security
							}

							$post_exterior_color_value = '';
							if ( ! empty( $_POST['exterior_color'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$post_exterior_color_value = sanitize_text_field( wp_unslash( $_POST['exterior_color'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							}

							$post_interior_color_value = '';
							if ( ! empty( $_POST['interior_color'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$post_interior_color_value = sanitize_text_field( wp_unslash( $_POST['interior_color'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							}

							$post_owner_value = '';
							if ( ! empty( $_POST['owner'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$post_owner_value = sanitize_text_field( wp_unslash( $_POST['owner'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							}
							?>
							<div class="form-upload-files">
								<div class="clearfix">
									<div class="stm-unit-photos">
										<h5 class="stm-label-type-2"><?php esc_html_e( 'Upload your car Photos', 'motors' ); ?></h5>
										<div class="upload-photos">
											<div class="stm-pseudo-file-input" data-placeholder="<?php esc_html_e( 'Choose file...', 'motors' ); ?>">
												<div class="stm-filename"><?php esc_html_e( 'Choose file...', 'motors' ); ?></div>
												<div class="stm-plus"></div>
												<input class="stm-file-realfield" type="file" name="gallery_images_0"/>
											</div>
										</div>
									</div>
									<div class="stm-unit-url">
										<h5 class="stm-label-type-2">
											<?php esc_html_e( 'Provide a hosted video url of your car', 'motors' ); ?>
										</h5>
										<input type="text" value="<?php echo esc_attr( $post_video_url_value ); ?>"
											name="video_url"/>
									</div>
								</div>
							</div>
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/radio.png' ); ?>"
								style="opacity:0; width:0; height:0;"/>

						</div>
					</div>

					<div class="row">
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Exterior color', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_exterior_color_value ); ?>"
									name="exterior_color"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Interior color', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_interior_color_value ); ?>"
									name="interior_color"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Owner', 'motors' ); ?></div>
								<input type="text" value="<?php echo esc_attr( $post_owner_value ); ?>" name="owner"/>
							</div>
						</div>
					</div>

					<a href="#" class="button sell-a-car-proceed" data-step="2">
						<?php esc_html_e( 'Save and continue', 'motors' ); ?>
					</a>
				</div>

				<!-- STEP TWO -->
				<div class="form-content-unit" id="step-two">
					<div class="vehicle-condition">
						<div class="vehicle-condition-unit">
							<div class="icon"><i class="stm-icon-car-relic"></i></div>
							<div class="title h5"><?php esc_html_e( 'What is the Exterior Condition?', 'motors' ); ?></div>
							<label>
								<input type="radio" name="exterior_condition"
									value="<?php esc_html_e( 'Extra clean', 'motors' ); ?>" checked/>
								<?php esc_html_e( 'Extra clean', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="exterior_condition"
									value="<?php esc_html_e( 'Clean', 'motors' ); ?>"/>
								<?php esc_html_e( 'Clean', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="exterior_condition"
									value="<?php esc_html_e( 'Average', 'motors' ); ?>"/>
								<?php esc_html_e( 'Average', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="exterior_condition"
									value="<?php esc_html_e( 'Below Average', 'motors' ); ?>"/>
								<?php esc_html_e( 'Below Average', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="exterior_condition"
									value="<?php esc_html_e( 'I don\'t know', 'motors' ); ?>"/>
								<?php esc_html_e( 'I don\'t know', 'motors' ); ?>
							</label>
						</div>
						<div class="vehicle-condition-unit">
							<div class="icon buoy"><i class="stm-icon-buoy"></i></div>
							<div class="title h5"><?php esc_html_e( 'What is the Interior Condition?', 'motors' ); ?></div>
							<label>
								<input type="radio" name="interior_condition"
									value="<?php esc_html_e( 'Extra clean', 'motors' ); ?>" checked/>
								<?php esc_html_e( 'Extra clean', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="interior_condition"
									value="<?php esc_html_e( 'Clean', 'motors' ); ?>"/>
								<?php esc_html_e( 'Clean', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="interior_condition"
									value="<?php esc_html_e( 'Average', 'motors' ); ?>"/>
								<?php esc_html_e( 'Average', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="interior_condition"
									value="<?php esc_html_e( 'Below Average', 'motors' ); ?>"/>
								<?php esc_html_e( 'Below Average', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="interior_condition"
									value="<?php esc_html_e( 'I don\'t know', 'motors' ); ?>"/>
								<?php esc_html_e( 'I don\'t know', 'motors' ); ?>
							</label>
						</div>
						<div class="vehicle-condition-unit">
							<div class="icon buoy-2"><i class="stm-icon-buoy-2"></i></div>
							<div class="title h5"><?php esc_html_e( 'Has vehicle been in accident', 'motors' ); ?></div>
							<label>
								<input type="radio" name="accident" value="<?php esc_html_e( 'Yes', 'motors' ); ?>"/>
								<?php esc_html_e( 'Yes', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="accident" value="<?php esc_html_e( 'No', 'motors' ); ?>"
									checked/>
								<?php esc_html_e( 'No', 'motors' ); ?>
							</label>
							<label>
								<input type="radio" name="accident"
									value="<?php esc_html_e( 'I don\'t know', 'motors' ); ?>"/>
								<?php esc_html_e( 'I don\'t know', 'motors' ); ?>
							</label>
						</div>
					</div>
					<a href="#" class="button sell-a-car-proceed" data-step="3">
						<?php esc_html_e( 'Save and continue', 'motors' ); ?>
					</a>
				</div>

				<!-- STEP THREE -->
				<div class="form-content-unit" id="step-three">
					<div class="contact-details">
						<?php
						$post_first_name_value = '';
						if ( ! empty( $_POST['first_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_first_name_value = sanitize_text_field( wp_unslash( $_POST['first_name'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_last_name_value = '';
						if ( ! empty( $_POST['last_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_last_name_value = sanitize_text_field( wp_unslash( $_POST['last_name'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_email_value = '';
						if ( ! empty( $_POST['email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_email_value = sanitize_text_field( wp_unslash( $_POST['email'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_phone_value = '';
						if ( ! empty( $_POST['phone'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_phone_value = sanitize_text_field( wp_unslash( $_POST['phone'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_comments_value = '';
						if ( ! empty( $_POST['comments'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_comments_value = sanitize_text_field( wp_unslash( $_POST['comments'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}
						?>
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<div class="contact-us-label"><?php esc_html_e( 'First name', 'motors' ); ?>*</div>
									<input type="text" value="<?php echo esc_attr( $post_first_name_value ); ?>"
										name="first_name"/>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<div class="contact-us-label"><?php esc_html_e( 'Last name', 'motors' ); ?>*</div>
									<input type="text" value="<?php echo esc_attr( $post_last_name_value ); ?>"
										name="last_name"/>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<div class="contact-us-label"><?php esc_html_e( 'Email Address', 'motors' ); ?>*
									</div>
									<input type="text" value="<?php echo esc_attr( $post_email_value ); ?>"
										name="email"/>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									<div class="contact-us-label"><?php esc_html_e( 'Phone number', 'motors' ); ?>*
									</div>
									<input type="text" value="<?php echo esc_attr( $post_phone_value ); ?>"
										name="phone"/>
								</div>
							</div>
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<div class="contact-us-label"><?php esc_html_e( 'Comments', 'motors' ); ?></div>
									<textarea name="comments"><?php echo esc_attr( $post_comments_value ); ?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<?php
							if ( ! empty( $recaptcha_enabled ) && $recaptcha_enabled && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) :
								wp_enqueue_script( 'stm_grecaptcha' );
								?>
								<script>
									function onSubmit(token) {
										jQuery("form#trade-in-default").trigger('submit');
									}
								</script>
							<input class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_public_key ); ?>"
								data-callback='onSubmit' type="submit"
								value="<?php esc_html_e( 'Save and finish', 'motors' ); ?>"/>
							<?php else : ?>
							<input type="submit" value="<?php esc_html_e( 'Save and finish', 'motors' ); ?>"/>
							<?php endif; ?>
						</div>
						<div class="disclaimer">
							<?php
							esc_html_e(
								'By submitting this form, you will be requesting trade-in value at no obligation and will be contacted within 48 hours by a sales representative.',
								'motors'
							);
							?>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="stm-sell-a-car-loader">
		<div class="stm-loader">
			<div class="stm-loader-inner">
				<div class="stm-loader circle">
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	</div>

	<div class="wpcf7-response-output wpcf7-mail-sent-ok hidden" id="error-fields">
		<?php esc_html_e( 'Mail successfully sent', 'motors' ); ?>
	</div>

	<div class="wpcf7-response-output wpcf7-validation-errors hidden" id="error-fields">
	</div>

