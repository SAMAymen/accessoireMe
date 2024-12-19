<?php
$label       = 'free';
$version     = 'Current Version: ';
$new_version = '';

if ( isset( $args['is_premium'] ) ) {
	$label       = 'premium';
	$version     = 'Version: ';
}


if ( isset( $args['update_version'] ) ) {
	$new_version = 'New Version: ';
}

?>

<div class="stmadmin-plugin-loop <?php echo esc_attr( $args['slug'] ) . ' ' . implode( ' ', $args['sort'] ) ?>">
	<div class="img-wrap">
		<?php if ( isset( $args['is_active'] ) || isset( $args['has_update'] ) ): ?>
			<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=' . $args['slug'] ) ); ?>" class="btn-settings"><i
					class="stmadmin-icon-settings"></i>Settings</a>
		<?php endif; ?>
		<img
			src="<?php echo get_template_directory_uri() . '/assets/images/admin_theme_plugins/' . $args['slug'] . '.png'; ?>"
			srcset="<?php echo get_template_directory_uri() . '/assets/images/admin_theme_plugins/' . $args['slug'] . '@2x.png'; ?> 2x"/>
		<div class="plug-info-float">
			<ul>
				<?php
				if ( $args['required'] ) {
					echo '<li class="plug-required">REQUIRED</li>';
				}
				if ( ! empty( $args['core'] ) ) {
					echo '<li class="plug-core">CORE</li>';
				}
				if ( ! isset( $args['core'] ) || empty( $args['core'] ) ) {
					echo sprintf( '<li class="plug-%s">%s</li>', $label, $label );
				}
				?>
			</ul>
		</div>
	</div>
	<h3 class="plug-name">
		<?php echo esc_html( $args['name'] ); ?>
	</h3>
	<div class="info-wrap">
		<div class="info">
			<?php echo ( ! empty( $args['version'] ) ) ? sprintf( '<div class="curr-version">%s%s</div>', $version, $args['version'] ) : ''; ?>
			<?php echo ( ! empty( $args['update_version'] ) ) ? sprintf( '<div class="new-version">%s%s</div>', $new_version, $args['update_version'] ) : ''; ?>
			<?php echo ( ! empty( $args['author'] ) ) ? sprintf( '%s', $args['author'] ) : ''; ?>
		</div>
		<div class="action">
			<?php
			$link     = '#';
			$btnTitle = 'Activate';
			$class    = 'activate';
			$source   = '';

			if ( isset( $args['is_active'] ) ) {
				$link     = '#';
				$btnTitle = 'Deactivate';
				$class    = 'deactivate';
				$source   = ( ! empty( $args['source'] ) ) ? $args['source'] : '';
				$source   = ( empty( ! $source ) && ! empty( $args['download_link'] ) ) ? $args['download_link'] : '';
			}

			if ( isset( $args['has_update'] ) ) {
				$link     = '#';
				$btnTitle = 'Update';
				$class    = 'update';
				$source   = ( ! empty( $args['source'] ) ) ? $args['source'] : '';
				$source   = ( empty( ! $source ) && ! empty( $args['download_link'] ) ) ? $args['download_link'] : '';
			}

			if ( isset( $args['not_installed'] ) ) {
				$link     = '#';
				$btnTitle = 'Install';
				$class    = 'install';
				$source   = ( ! empty( $args['source'] ) ) ? $args['source'] : '';
				$source   = ( empty( ! $source ) && ! empty( $args['download_link'] ) ) ? $args['download_link'] : '';
			}

			echo "<a href='{$link}' id='{$args['slug']}' class='action_plugin {$class}' data-slug='{$args["slug"]}' data-filepath='{$args['file_path']}' data-source='{$source}' data-action='{$class}'>{$btnTitle}</a>";
			?>
		</div>
	</div>
	<div class="plugin-preloader">
		<div></div>
		<div></div>
		<div></div>
	</div>
	<?php if ( ! empty( $args['not_installed'] ) ) : ?>
		<script type="text/javascript">
            jQuery(document).ready(function () {
                var slug = '<?php echo esc_js( $args['slug'] ); ?>';

                //alert(stm_ajax_dashboard.stm_actions_plugin_info);

                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: {
                        'plugin_slug': slug,
                        'action': 'stm_get_plugin_info',
                        'security': stm_ajax_dashboard.stm_actions_plugin_info
                    },
                    beforeSend: function () {
                        jQuery('.' + slug).addClass('grid_item_disable');
                        //jQuery('#' + slug).hide();
                    },
                    complete: function (data) {
                        var dt = data.responseJSON;

                        if (typeof dt.version != 'undefined') {
                            jQuery('.' + slug).find('.info').append('<div>Version: ' + dt.version + '</div>' + dt.author);
                            jQuery('#' + slug).attr('data-source', dt.download_link);
                        }

                        jQuery('.' + slug).removeClass('grid_item_disable');
                    },
                    error: function () {
                    }

                });
            });
		</script>
	<?php endif; ?>
</div>

