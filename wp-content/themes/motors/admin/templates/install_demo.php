<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$theme           = STM_Theme_Info::get_theme_info();
$auth_code       = STM_Theme_Activation::check_token();
$plugins         = apply_filters( 'stm_theme_plugins', array() );
$demos           = apply_filters( 'stm_theme_demos', array() );
$current_demo    = apply_filters( 'stm_theme_demo_layout', '' );
$current_builder = ( ! empty( $_GET['builder'] ) ) ? sanitize_text_field( $_GET['builder'] ) : '';
$current_builder = ( ! apply_filters( 'stm_theme_enable_elementor', false ) ) ? true : $current_builder;
$elementor_addon = apply_filters( 'stm_theme_elementor_addon', '' );
$elementor_addon = ( is_array( $elementor_addon ) ) ? json_encode( $elementor_addon ) : $elementor_addon;

?>
	<div class="wrap stm-admin-wrap  stm-admin-demos-screen">
		<?php STM_Theme_Admin_Pages::get_admin_tabs( 'demos' ); ?>

		<?php if ( ! empty( $auth_code ) || ( STM_Theme_Info::is_dev_mode() ) ) : ?>

			<!-- Top Panel of Demos -->
			<div class="top-panel
				<?php
				if ( ! apply_filters( 'stm_theme_enable_js_composer', true ) ) :
					?>
					only-elementor-builder
					<?php endif; ?>"
				>
				<div class="float_left">
					<h4>Choose Builder</h4>
					<?php if ( apply_filters( 'stm_theme_enable_js_composer', true ) ) : ?>
						<label class="builder
							<?php
							if ( 'js_composer' === $current_builder ) {
								echo 'checked';
							}
							?>
						">
							<input type="radio" name="builder" value="js_composer"
							<?php
							if ( 'js_composer' === $current_builder ) {
								echo 'checked';
							}
							?>
							>
							WP Bakery
						</label>
						<?php if ( apply_filters( 'stm_theme_enable_elementor', false ) ) : ?>
						<label class="builder
							<?php
							if ( 'elementor' === $current_builder ) {
								echo 'checked';
							}
							?>
						">
							<input type="radio" name="builder" value="elementor"
								<?php
								if ( 'elementor' === $current_builder ) {
									echo 'checked';
								}
								?>
							>
							Elementor
						</label>
						<?php endif; ?>

						<div class="builder-check">
							<i class="arrow-top"></i> <span>Please choose Page Builder first</span>
						</div>
					<?php else : ?>
						<label class="builder checked" style="display: none;">
							<input type="radio" name="builder" value="elementor" checked />
						</label>
					<?php endif; ?>

					<span class="privacy-label">
						<input type="checkbox" id="send_api" name="send_api" value="1" checked style="display: none;"> By selecting and installing a demo you agree to the terms in our
						<a href="https://stylemixthemes.com/privacy-policy/" target="_blank">Privacy Policy.</a>
					</span>
				</div>
				<div class="float_right">
					<h4>Search demo</h4>
					<input type="text" id="search_demo" placeholder="Enter demo name... ">
					<span class="tooltip-no-demos">No available demos</span>
				</div>
				<div class="clearfix"></div>
			</div>

			<!-- Demo List -->
			<div class="stm_demo_import_choices">
				<div class="privacy-unchecked-overlay"></div>
				<div class="no_demos">
					<i class="stmadmin-icon-box"></i>
					<p>No available Demos</p>
				</div>

				<script type="text/javascript">
					var stm_layouts = {};
				</script>
				<?php
				foreach ( $demos as $demo_key => $demo_value ) :
					$import_btn_text = ( $demo_key === $current_demo ) ? 'Reimport Demo' : 'Import Demo';
					?>
					<script type="text/javascript">
						stm_layouts['<?php echo esc_attr( $demo_key ); ?>'] = <?php echo wp_json_encode( apply_filters( 'stm_theme_layout_plugins', $demo_key ) ); ?>;
					</script>
					<label <?php if ( isset( $demo_value['builder'] ) ) : ?>class="builder-only" data-builder="<?php echo esc_attr($demo_value['builder']); ?>" <?php endif;
					?>>
						<div class="inner">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/admin/images/layouts/' . $demo_key . '.jpg' ); ?>" />
							<?php if ( $demo_key === $current_demo ) : ?>
								<div class="installed">Imported</div>
							<?php endif; ?>

							<div class="install" data-name="<?php echo esc_attr( $demo_value['label'] ); ?>" data-layout="<?php echo esc_attr( $demo_key ); ?>"
								<?php if ( isset( $demo_value['builder'] ) ) : ?>
									data-builder="<?php echo esc_attr( $demo_value['builder'] ); ?>"
								<?php endif; ?>
									data-slug="<?php echo esc_attr( $demo_value['slug'] ); ?>"><?php echo esc_html( $import_btn_text ); ?>
							</div>
							<a href="<?php echo esc_attr( STM_DEMO_SITE_URL . $demo_value['live_url'] ); ?>" class="preview_demo" target="_blank">
								Preview Demo
							</a>
							<span class="stm_layout__label"><?php echo esc_attr( $demo_value['label'] ); ?></span>
						</div>
					</label>
				<?php endforeach; ?>
			</div>

			<!-- Demo popup -->
			<div class="stm_install__demo_popup">
				<div class="stm_install__demo_popup_close"></div>
				<div class="inner">
					<div class="stm_install__demo_popup_close"></div>
					<div class="stm_install__demo_reset_notice" style="display: none;"></div>

					<!-- Privacy Policy -->
					<div class="privacy_policy" style="display: none;">
						<div class="popup_header">
							<div class="float_left">
								<div class="theme_info">
									<label>Privacy Policy</label>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="popup_body">
							<?php require_once __DIR__ . '/privacy_policy.php'; ?>
						</div>
					</div>

					<!-- Demo Install -->
					<div class="demo_install">
						<div class="popup_header">
							<div class="float_left">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/admin/images/theme-icon.svg' ); ?>" alt="theme">
								<div class="theme_info">
									<span><?php echo esc_html( STM_THEME_NAME ); ?></span> <label>Demo installation</label>
								</div>
							</div>
							<div class="float_right">
								<div class="demo_info">
									<span>Demo</span> <label class="layout_name"></label>
								</div>
								<div class="demo_info">
									<span>Builder</span> <label class="builder_name"></label>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="popup_body show_demo" <?php if ( ! empty( $current_demo ) ) : ?>style="display: none;"<?php endif; ?>>
							<?php $secondary_required_plugins = STM_Theme_Plugins::secondary_required_plugins();
							$installing_plugins               = array_keys( $plugins );
							if ( isset( $_GET['installing_plugins'] ) ) {
								$installing_plugins = explode( ',', $_GET['installing_plugins'] );
							}

							$importing_data = array(
								'theme_options',
								'content',
								'media',
								'sliders',
								'widgets',
							);
							if ( isset( $_GET['importing_data'] ) ) {
								$importing_data = explode( ',', $_GET['importing_data'] );
							} ?>

							<div class="float_left demo_plugins">
								<h4>Used Plugins</h4>

								<?php foreach ( $plugins as $plugin ) :
									$active = ( STM_Theme_Plugins::plugin_is_active( $plugin['slug'] ) ) ? 'installed' : 'not-installed';
									$active_text = ( $active == 'installed' ) ? 'installed and activated' : 'not installed';
									?>
									<div id="<?php echo sanitize_text_field( 'stm_' . $plugin['slug'] ); ?>" class="stm_plugin_info <?php echo esc_attr( $active ); ?>" data-active="<?php echo esc_attr( $active ); ?>" data-slug="<?php echo sanitize_text_field( $plugin['slug'] ); ?>" data-required="<?php if ( $plugin['required'] || in_array( $plugin['slug'], $secondary_required_plugins ) )
										echo 'required'; ?>">
										<label>
											<input type="checkbox" name="install_plugins[]" value="<?php echo sanitize_text_field( $plugin['slug'] ); ?>"
												<?php if ( $active == 'installed' || in_array( $plugin['slug'], $installing_plugins ) )
													echo 'checked'; ?>
												<?php if ( $active == 'installed' || $plugin['required'] || in_array( $plugin['slug'], $secondary_required_plugins ) )
													echo 'disabled'; ?>>
											<div class="title_box">
												<div class="label"><?php echo sanitize_text_field( $plugin['name'] ); ?></div>
												<span class="status"><?php echo sanitize_text_field( $active_text ); ?></span>
											</div>
										</label>
									</div>
								<?php endforeach; ?>

							</div>

							<div class="float_right demo_content">
								<h4>Demo Content</h4>

								<div id="import_data_theme_options" class="stm_content_info" data-status="not-imported">
									<label>
										<input type="checkbox" name="import_data[]" value="theme_options" <?php if ( in_array( 'theme_options', $importing_data ) )
											echo 'checked'; ?>>
										<div class="title_box">
											<div class="label">Theme Options
												<div class="content_tooltip">
													<span class="content_info"></span>
													<span class="content_tooltip_text">Import Theme Options / Rewrite All Current Settings</span>
												</div>
											</div>
											<span class="status">not imported</span>
										</div>
									</label>
								</div>

								<div id="import_data_content" class="stm_content_info" data-status="not-imported">
									<label>
										<input type="checkbox" name="import_data[]" value="content" <?php if ( in_array( 'content', $importing_data ) )
											echo 'checked'; ?>>
										<div class="title_box">
											<div class="label">Main Content
												<div class="content_tooltip">
													<span class="content_info"></span>
													<span class="content_tooltip_text">Import Posts, Pages, Custom Posts, and Custom Fields</span>
												</div>
											</div>
											<span class="status">not imported</span>
										</div>
									</label>
								</div>

								<?php if ( apply_filters( 'dashboard_hide_import_media', false ) ) : ?>
								<div id="import_data_media" class="stm_content_info" data-status="not-imported">
									<label>
										<input type="checkbox" name="import_data[]" value="media" <?php if ( in_array( 'media', $importing_data ) )
											echo 'checked'; ?>>
										<div class="title_box">
											<div class="label">Media
												<div class="content_tooltip">
													<span class="content_info"></span>
													<span class="content_tooltip_text">Import Media for Posts, Pages, Custom Posts</span>
												</div>
											</div>
											<span class="status">not imported</span>
										</div>
									</label>
								</div>
								<?php endif; ?>

								<?php if ( ! apply_filters( 'dashboard_hide_import_sliders', false ) ) : ?>
								<div id="import_data_sliders" class="stm_content_info" data-status="not-imported">
									<label>
										<input type="checkbox" name="import_data[]" value="sliders" <?php if ( in_array( 'sliders', $importing_data ) )
											echo 'checked'; ?>>
										<div class="title_box">
											<div class="label">Import Sliders
												<div class="content_tooltip">
													<span class="content_info"></span>
													<span class="content_tooltip_text">Import Sliders of Chosen Demo Site</span>
												</div>
											</div>
											<span class="status">not imported</span>
										</div>
									</label>
								</div>
								<?php endif; ?>

								<div id="import_data_widgets" class="stm_content_info" data-status="not-imported">
									<label>
										<input type="checkbox" name="import_data[]" value="widgets" <?php if ( in_array( 'widgets', $importing_data ) )
											echo 'checked'; ?>>
										<div class="title_box">
											<div class="label">Widgets & Menu
												<div class="content_tooltip">
													<span class="content_info"></span>
													<span class="content_tooltip_text">Import Widgets and Menu, Setup Home & Archive Pages</span>
												</div>
											</div>
											<span class="status">not imported</span>
										</div>
									</label>
								</div>
							</div>

							<div class="clearfix"></div>
						</div>
						<?php if ( ! empty( $current_demo ) ) : ?>
							<div class="popup_body reset_demo_body show_reset_demo">
								<p>Before installing a new pre-built website, it is recommended to clean up your
									WordPress database</p>
								<div class="reset_demo_content">
									<img src="<?php echo esc_url( get_template_directory_uri() . "/assets/admin/images/layouts/{$current_demo}.jpg" ); ?>" class="demo_image" alt="reset_demo">
									<div class="reset_demo_info">
										<i class="stmadmin-icon-warning"></i>
										<h5>This tool Does not create backups</h5>
										<fieldset class="danger">
											<legend>Deletes</legend>
											<p>All Pages, Posts, Custom Posts, Menus, Categories, Comments, etc.
												Deactivate bundled Plugins!</p>
										</fieldset>
										<fieldset class="success">
											<legend>Remains</legend>
											<p>Users and passwords, wp_options, files on your server.</p>
										</fieldset>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<div class="popup_footer show_demo" <?php if ( ! empty( $current_demo ) ) : ?>style="display: none;"<?php endif; ?>>
							<div class="demo_progress">
								<div class="demo_progress_info">
									<div class="demo_status">Import Progress</div>
									<div><span class="demo_percent">0</span>%</div>
								</div>
								<div class="demo_progress_bar">
									<div role="progressbar" class="progress_bar" style="width: 0%;"></div>
								</div>
								<div class="demo_error" style="display: none;">Please check your website after few
									minutes or Run Demo Import once again!
								</div>
							</div>
							<div class="stm_install__demo_start demo_button">Start Import</div>
						</div>
						<?php if ( ! empty( $current_demo ) ) : ?>
							<div class="popup_footer show_reset_demo">
								<label> <input type="checkbox" id="reset_policy"> I understand that there is NO UNDO
								</label>
								<div class="demo_buttons">
									<div id="reset_demo" class="demo_button danger disabled">Reset Now</div>
									<div id="skip_reset" class="demo_button primary">Skip</div>
								</div>
							</div>
						<?php endif; ?>

					</div>

				</div>
			</div>

		<?php else: ?>
			<div class="demos-error-view">
				<?php get_template_part( 'admin/templates/parts/plugins_install_denied_popup' ); ?>
			</div>
		<?php endif; ?>

	</div>

	<script>
		var dev_mode = <?php echo STM_Theme_Info::is_dev_mode() ? 'true' : 'false'; ?>;
		var site_url = '<?php echo esc_url( get_site_url() ); ?>';
		var theme_slug = '<?php echo esc_js( $theme['slug'] ); ?>';
		var import_nonce = '<?php echo esc_js( wp_create_nonce( 'stm_demo_import_content' ) ); ?>';
		var reset_nonce = '<?php echo esc_js( wp_create_nonce( 'stm_reset_demo' ) ); ?>';
        var elementor_addon = '<?php echo apply_filters( 'stm_theme_esc_variable', $elementor_addon ) ; ?>';
		var plugins = <?php echo html_entity_decode( json_encode( wp_list_pluck( $plugins, 'slug' ) ) ); ?>;
		var default_layout = '<?php echo apply_filters( 'stm_theme_default_layout', '' ); ?>';
		var default_layout_name = '<?php echo apply_filters( 'stm_theme_default_layout_name', '' ); ?>';

		<?php $importing_layout = $_GET['layout_importing'] ?? false; ?>
		<?php if ( ! empty( $importing_layout ) ) : ?>
		var importing_layout = '<?php echo esc_js( $importing_layout ); ?>';

		<?php if ( ! empty( $demos[ $importing_layout ] ) ) : ?>
		var importing_layout_name = '<?php echo esc_js( $demos[ $importing_layout ]['slug'] ); ?>';
		<?php endif;  ?>

		<?php if ( ! empty( $_GET['builder'] ) ) : ?>
		var importing_builder = '<?php echo esc_js( sanitize_text_field( $_GET['builder'] ) ); ?>';
		<?php endif; ?>

		<?php if ( ! empty( $_GET['installing_plugins'] ) ) : ?>
		var importing_install_plugins = '<?php echo esc_js( sanitize_text_field( $_GET['installing_plugins'] ) ) ?>'.split(',');
		<?php endif; ?>

		<?php endif; ?>

		jQuery(document).ready(function () {
			next_installable();
			show_popup();

			<?php if( ! empty( $importing_layout ) ) : ?>
			skip_reset();

			jQuery('.stm_demo_import_choices .install').click();

			setTimeout(function () {
				jQuery('.stm_install__demo_popup .inner .stm_install__demo_start').click();
			}, 1000);

			set_builder();
			hide_plugins(layout);

			window.history.pushState('', '', '<?php echo esc_url( remove_query_arg( [ 'layout_importing', 'builder', 'importing_data', 'installing_plugins' ] ) ) ?>');
			<?php endif; ?>
		});
	</script>
<?php

wp_enqueue_script( 'stm-theme-demo-import', get_template_directory_uri() . '/admin/assets/js/stm-demo-import.js', 'jQuery', NULL, true );
