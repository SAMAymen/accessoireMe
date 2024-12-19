<div class="wrap">
	<h2>
		<?php esc_html_e( 'Custom Icons Manager', 'stm-elementor-icons' ); ?>
		<a href="#stm_upload_icon" class="add-new-h2 stm_upload_icon" data-target="iconfont_upload"
		   data-title="<?php echo esc_html__( 'Upload/Select Fontello Font Zip', 'stm-elementor-icons' ); ?>"
		   data-type="application/octet-stream, application/zip"
		   data-button="<?php echo esc_html__( 'Insert Fonts Zip File', 'stm-elementor-icons' ); ?>"
		   data-trigger="cei_upload_font"
		   data-class="media-frame ">
			<?php esc_html_e( 'Upload New Icons', 'stm-elementor-icons' ); ?>
		</a> &nbsp; <span class="spinner"></span></h2>
	<div id="msg"></div>
	<?php if ( ! empty( $fonts ) && is_array( $fonts ) ) : ?>
		<div class="metabox-holder meta-search">
			<div class="postbox">
				<h3>
					<input class="search-icon" type="text" placeholder="<?php esc_attr_e( 'Search', 'stm-elementor-icons' ); ?>"/>
					<span class="search-count"></span>
				</h3>
			</div>
		</div>
		<div class="stm_cei_icon_sets_wrap">
		<?php
		foreach ( $fonts as $font => $info ) :
			$icon_set   = array();
			$icons      = array();
			$upload_dir = wp_upload_dir();
			$path       = trailingslashit( $upload_dir['basedir'] );
			$file       = $path . $info['include'] . '/' . $info['config'];

			if ( ! empty( $info['json'] ) ) {
				$json = $path . $info['include'] . '/' . $info['json'];
				if ( file_exists( $json ) ) {
					$json   = json_decode( file_get_contents( $json ), true );
					$prefix = $json['preferences']['fontPref']['prefix'];
				}
			} else {
				$prefix = $font . '-';
			}
			if ( file_exists( $file ) ) {
				include $file;
			}

			if ( ! empty( $icons ) ) {
				$icon_set = array_merge( $icon_set, $icons );
			}
			if ( ! empty( $icon_set ) ) :
				foreach ( $icon_set as $icons ) {
					$count = count( $icons );
				}

				$button_label = apply_filters( 'stm_delete_iconset_button_label', esc_html__( 'Delete Icon Set', 'stm-elementor-icons' ), $font );
				?>
				<div class="icon_set-<?php esc_attr_e( $font ); ?> metabox-holder">
					<div class="postbox">
						<h3 class="icon_font_name"><strong><?php esc_html_e( ucfirst( $font ) ); ?></strong>
							<span class="fonts-count count-<?php esc_attr_e( $font ); ?>"><?php esc_html_e( $count ); ?></span>
							<button
								class="button button-secondary button-small cei_delete_font"
								data-delete="<?php echo esc_attr( $font ); ?>"
								data-title="<?php echo esc_attr( $button_label ); ?>">
							<?php echo esc_html( $button_label ); ?>
							</button>
							<div class="clearfix"></div>
						</h3>
						<div class="inside">
							<div class="icon_actions"></div>
							<div class="icon_search">
								<ul class="icons-list fi_icon">
								<?php
								foreach ( $icon_set as $icons ) :
									foreach ( $icons as $icon ) :
										?>
										<li
											title="<?php esc_attr_e( $icon['class'] ); ?>"
											data-icons="<?php esc_attr_e( $icon['class'] ); ?>"
											data-icons-tag="<?php esc_attr( $icon['tags'] ); ?>">
											<i class="<?php echo esc_attr( $prefix . $icon['class'] ); ?>"></i>
											<label class="icon"><?php esc_html_e( $icon['class'] ); ?></label>
										</li>
										<?php
									endforeach;
								endforeach;
								?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<?php
			endif;
		endforeach;
		?>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery(".search-icon").keyup(function () {
					jQuery(".fonts-count").hide();
					var filter = jQuery(this).val(), count = 0;
					jQuery(".icons-list li").each(function () {
						if (jQuery(this).attr("data-icons-tag").search(new RegExp(filter, "i")) < 0) {
							jQuery(this).fadeOut();
						} else {
							jQuery(this).show();
							count++;
						}
						if (count === 0) {
							jQuery(".search-count").html(' <?php esc_attr_e( __( "Can't find the icon?", 'stm-elementor-icons' ) ); ?> ' +
								'<a href="#stm_upload_icon" class="add-new-h2 stm_upload_icon" data-target="iconfont_upload" ' +
								'data-title="<?php esc_attr_e( __( 'Upload/Select Fontello Font Zip', 'stm-elementor-icons' ) ); ?>" ' +
								'data-type="application/octet-stream, application/zip" data-button="<?php esc_attr_e( __( 'Insert Fonts Zip File', 'stm-elementor-icons' ) ); ?>" ' +
								'data-trigger="cei_upload_font" data-class="media-frame"><?php esc_attr_e( __( 'Click here to upload', 'stm-elementor-icons' ) ); ?></a>');
						}
						else {
							jQuery(".search-count").html(count + ' <?php esc_attr_e( __( 'Icons found.', 'stm-elementor-icons' ) ); ?>');
						}
						if (filter === "") {
							jQuery(".fonts-count").show();
						}
					});
				});
			});
		</script>
	<?php else : ?>
		<div class="updated">
			<p><?php esc_html_e( 'No font icons uploaded. Upload some font icons to display here:', 'stm-elementor-icons' ); ?></p>
			<p><?php esc_html_e( '1. Custom Icons Set/Pack should be uploaded. Icon Fonts can be generated via', 'stm-elementor-icons' ); ?>
				<a href="https://icomoon.io/app/" target="_blank">https://icomoon.io/app/</a>.
			</p>
			<p><?php esc_html_e( '2. For using the custom icons, upload the pack of Icon Fonts.', 'stm-elementor-icons' ); ?></p>
			<p><?php esc_html_e( '3. Add your Custom Fonts or generate Icon Fonts through', 'stm-elementor-icons' ); ?>
				<a href="https://icomoon.io/app/" target="_blank">https://icomoon.io/app/</a>.</p>
		</div>
	<?php endif; ?>
</div>
